import axios from "axios";
import { parse } from "postcss";
const globals = {
    // Add your global variables here
    // Example: apiUrl: 'https://api.example.com'
    prevArrow: `<button type="button" class="slick-prev-custom slick-arrow-custom">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="25" viewBox="0 0 16 25" fill="none">
            <g><path d="M14 23.5L3 12.5055L14 1.5" stroke="#031A35" stroke-width="4" stroke-miterlimit="10"/></g>
        </svg>
    </button>`,
    nextArrow: `<button type="button" class="slick-next-custom slick-arrow-custom">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="25" viewBox="0 0 16 25" fill="none">
            <path d="M2 23.5L13 12.5055L2 1.5" stroke="#031A35" stroke-width="4" stroke-miterlimit="10"/>
        </svg>
    </button>`,
};

class Modal {
    constructor() {
        this.selectors = {
            openElement: `.open-popup`,
            closeElement: ".closepop",
            overlay: ".popup-overlay",
        };

        // this.init();
    }

    init() {
        const { openElement } = this.selectors;
        // Event listener for the trigger button
        document.querySelectorAll(openElement).forEach((trigger) => {
            trigger.addEventListener("click", (e) => {
                e.preventDefault();
                const currentTarget = e.currentTarget;

                if (currentTarget.classList.contains("disabled")) {
                    return;
                }
                const popupId = currentTarget.getAttribute("data-target");
                const popup = document.getElementById(popupId);
                this.open(popup, currentTarget);
            });
        });

        // Event listener for the Escape key
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                document.querySelectorAll(".popup.active").forEach((popup) => {
                    this.close(popup);
                });
            }
        });
    }

    open(popup, trigger = null) {
        const { overlay, closeElement } = this.selectors;
        if (!popup) {
            console.error(`Popup not found`);
            return;
        }

        // Close all other popups
        document.querySelectorAll(".popup").forEach((popup) => {
            popup.classList.remove("active");
        });

        // Open the selected popup
        popup.classList.add("active");
        // document.querySelector("html").classList.add("no-scroll");
        document.body.classList.add("popup-open");
        popup
            .querySelectorAll(".form-message")
            .forEach((message) => (message.innerHTML = ""));

        // Event listener for the close button
        const closeBtn = popup.querySelector(closeElement);
        if (closeBtn) {
            closeBtn.addEventListener("click", (e) => {
                this.close(e.currentTarget);
            });
        }

        // Event listener for the overlay (if present)
        const overlayElement = popup.querySelector(overlay);
        if (overlayElement) {
            overlayElement.addEventListener("click", (e) => this.close(e));
        }

        document.dispatchEvent(
            new CustomEvent("popup:opened", {
                detail: {
                    message: "Popup opened",
                    popup,
                    trigger,
                },
            })
        );
    }

    close(popupElement) {
        const popup = popupElement.closest(".popup");
        popup.classList.remove("active");
        document.querySelector("html").classList.remove("no-scroll");
        document.body.classList.remove("popup-open");

        document.dispatchEvent(
            new CustomEvent("popup:closed", {
                detail: {
                    message: "Popup closed",
                    popup: popup,
                },
            })
        );
    }

    toggle(popupElement) {
        const popup = popupElement.closest(".popup");
        popup.classList.toggle("active");
    }
}

class UIController {
    constructor() {
        this.selectors = {
            menusButton: document.querySelector("#menus"),
            closeMenuButton: document.querySelector("#closeMenu"),
            menuContainer: document.querySelector("#menuContainer"),
            rewardsSlider: ".rewards-slider",
            featuredBubble: ".feature-bubble",
            bookSession: ".book-session",
            openPopupBtn: ".open-popup-btn ",
            timePicker: ".time-picker",
            upgradePlan: ".upgrade-plan-btn",
            closePopup: ".closepop",
            datePicker: "#datepicker",
            btnHasAccount: ".btn-has-account",
            popup: ".popup",
            popupHolder: ".popup-holder",
            pageOverlay: ".PageOverlay",
        };

        this.init();
    }

    // Initialize all the necessary event listeners and plugins
    init() {
        this.setupMenu();
        this.setupSliders();
        this.setupScrollButtons();
        this.stickyHeader();
        // this.setupPopups();
        // this.setupDatePicker();
        this.setUpReadMore();
    }

    // Sticky Header
    stickyHeader() {
        const header = document.querySelector(".header-section");
        const body = document.querySelector("body");
        const sticky = header.offsetTop;

        window.onscroll = function () {
            if (window.scrollY > sticky) {
                header.classList.add("sticky");
                body.classList.add("sticky-header");
            } else {
                header.classList.remove("sticky");
                body.classList.remove("sticky-header");
            }
        };
    }

    // Scroll to top button
    setupScrollButtons() {
        const self = this;
        const { menuContainer } = this.selectors;

        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target) {
                const headerHeight =
                    document.querySelector(".header-section")?.offsetHeight ||
                    0;
                window.scrollTo({
                    top: target.offsetTop - headerHeight - 20,
                    behavior: "smooth",
                });
            }
        }

        document.querySelectorAll("[data-scroll-target]").forEach((button) => {
            button.addEventListener("click", function (e) {
                const urlWithoutHash = window.location.href.split("#")[0];
                const targetUrlWithoutHash = this.href.split("#")[0];
                if (urlWithoutHash === targetUrlWithoutHash) {
                    e.preventDefault();
                    self.closeMenu();
                }

                const targetSelector = this.getAttribute("data-scroll-target");
                const targetElement = document.querySelector(targetSelector);

                if (targetElement) {
                    if (menuContainer) {
                        menuContainer.classList.remove("active");
                    }
                    const headerHeight =
                        document.querySelector(".header-section")
                            ?.offsetHeight || 0;
                    window.scrollTo({
                        top: targetElement.offsetTop - headerHeight - 20,
                        behavior: "smooth",
                    });

                    // Update or replace the URL hash
                    history.pushState(null, null, targetSelector);
                }
            });
        });
    }

    setUpReadMore() {
        document.querySelectorAll("a[data-read-more-link]").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                const target = e.currentTarget.getAttribute("data-target");
                const targetContent = document.querySelector(
                    '[data-full-details="' + target + '"]'
                );
                targetContent.classList.remove("hide-mob");
                btn.nextElementSibling.style.display = "none";
                btn.classList.add("hide-mob");
                btn.classList.remove("show-mob");
            });
        });
    }

    closeMenu() {
        const { menuContainer, pageOverlay } = this.selectors;
        const htmlTag = document.querySelector("html");
        const PageOverlay = document.querySelector(pageOverlay);

        menuContainer.classList.remove("active");
        htmlTag.classList.remove("no-scroll");
        PageOverlay.classList.remove("is-visible");
    }

    // Menu Setup
    setupMenu() {
        const self = this;
        const { menusButton, closeMenuButton, menuContainer, pageOverlay } =
            this.selectors;
        const htmlTag = document.querySelector("html");
        const PageOverlay = document.querySelector(pageOverlay);

        menusButton.addEventListener("click", () => {
            menuContainer.classList.add("active");
            htmlTag.classList.add("no-scroll");
            PageOverlay.classList.add("is-visible");
        });

        PageOverlay.addEventListener("click", () => {
            self.closeMenu();
        });

        closeMenuButton.addEventListener("click", () => {
            self.closeMenu();
        });
    }

    // Slick Slider Setup
    setupSliders() {
        const { featuredBubble } = this.selectors;
        // Bubble
        $(featuredBubble).slick({
            infinite: true,
            slidesToShow: 2,
            adaptiveHeight: true,
            variableWidth: true,
            slidesToScroll: 1,
            centerMode: false,
            autoplay: true,
            autoplaySpeed: 0,
            cssEase: "ease-out",
            arrows: false,
            centerPadding: 0,
            speed: 10000,
            pauseOnHover: false,
            pauseOnFocus: false,
        });
    }

    openModal(event) {
        event.preventDefault();
        const currentTarget = event.currentTarget;
        const popupId = currentTarget.getAttribute("data-popup-id");
        document
            .querySelectorAll(this.selectors.popup)
            .forEach((popup) => popup.classList.remove("active"));
        document
            .querySelector(`${this.selectors.popup}[popup-id="${popupId}"]`)
            .classList.add("active");
    }

    // Popup Setup
    setupPopups() {
        const {
            bookSession,
            openPopupBtn,
            timePicker,
            upgradePlan,
            closePopup,
            popup,
            popupHolder,
            btnHasAccount,
        } = this.selectors;

        document
            .querySelectorAll(bookSession)
            .forEach((element) =>
                element.addEventListener("click", this.openModal.bind(this))
            );
        document
            .querySelectorAll(openPopupBtn)
            .forEach((element) =>
                element.addEventListener("click", this.openModal.bind(this))
            );
        document
            .querySelectorAll(timePicker)
            .forEach((element) =>
                element.addEventListener("click", this.openModal.bind(this))
            );
        document
            .querySelectorAll(upgradePlan)
            .forEach((element) =>
                element.addEventListener("click", this.openModal.bind(this))
            );
        document
            .querySelectorAll(btnHasAccount)
            .forEach((element) =>
                element.addEventListener("click", this.openModal.bind(this))
            );

        document.querySelectorAll(closePopup).forEach((element) =>
            element.addEventListener("click", () => {
                document
                    .querySelectorAll(popup)
                    .forEach((popup) => popup.classList.remove("active"));
                document
                    .querySelectorAll(popupHolder)
                    .forEach((holder) => holder.classList.remove("active"));
            })
        );
    }

    // Date Picker Setup
    setupDatePicker() {
        const { datePicker, timePicker } = this.selectors;

        $(datePicker).datepicker({
            onSelect: (date) => {
                $(timePicker).removeClass("disabled");
            },
        });
    }
}

class FormController {
    constructor() {
        this.selectors = {
            formSelector: "form[data-js-form]",
            submitButton: 'form[data-js-form] button[type="submit"]',
            formMessage: ".form-message:not(.inline-error)",
            popups: {
                register: "#register",
                verifyOtp: "#verify-otp",
                resendOtp: "#resend-otp",
                login: "#login",
            },
        };

        this.messages = {
            success: "Form submitted successfully",
            serverError:
                "An error occurred while submitting the form. Please try again later.",
            otpSuccess:
                'You are all set! Please login to your account <a class="color-midnight text-decoration-underline fw-300 fs-16 open-popup" data-target="login" href="javascript:void(0)">here</a>.',
        };

        this.classes = {
            loadingButton: "button--loading",
        };

        this.init();
    }

    init() {
        this.setupForm();
    }

    setupForm() {
        const { formSelector, submitButton, formMessage, popups } =
            this.selectors;
        const { loadingButton } = this.classes;
        const { serverError, otpSuccess } = this.messages;

        document.querySelectorAll(formSelector).forEach((form) => {
            form.addEventListener("submit", async function (event) {
                event.preventDefault(); // Prevent the form from submitting the traditional way

                const form = event.target;
                const formData = new FormData(form); // Create a FormData object from the form
                const submitBtn =
                    form.querySelector(submitButton) ||
                    document.querySelector(`button[form="${form.id}"]`);
                const message = form.querySelector(formMessage);
                const formType = formData.get("form_type");
                const encryptionEnabled =
                    form.getAttribute("data-encrypt") === "true";
                const encryptedFieldInputs = form.querySelectorAll(
                    "[data-encrypted-field]"
                );
                const formObject = {};
                let encryptedData = {};
                formData.forEach((value, key) => {
                    formObject[key] = value;
                });

                message.innerHTML = "";
                message.classList.remove("error");
                document
                    .querySelectorAll(".form-message.inline-error")
                    .forEach((errorElement) => errorElement.remove());
                submitBtn.disabled = true;
                submitBtn.classList.add(loadingButton);

                if (encryptionEnabled && encryptedFieldInputs.length) {
                    let encryptedFields = {};
                    encryptedFieldInputs.forEach((field) => {
                        const key = field.getAttribute("name");
                        encryptedFields[key] = formObject[key];
                    });

                    // Encrypt the form data
                    const stringifiedData = JSON.stringify(encryptedFields);
                    try {
                        const response = await new EncryptFormData(
                            stringifiedData
                        ).init();
                        if (response.success && response.data) {
                            encryptedData = response.data;
                        }
                    } catch (error) {
                        console.error("Error encrypting data:", error);
                    }
                }

                const submitData = { ...formObject, ...encryptedData };

                try {
                    let action = form.getAttribute("action");
                    if (
                        window.location.protocol === "https:" &&
                        action.startsWith("http://")
                    ) {
                        action = action.replace("http://", "https://");
                    }
                    const response = await fetch(action, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        body: JSON.stringify(submitData),
                    });

                    if (!response.ok) {
                        throw await response.json();
                    }

                    const responseJson = await response.json(); // Parse the JSON response

                    if (
                        responseJson.status < 200 ||
                        responseJson.status > 299
                    ) {
                        let errorMessage = "";
                        if (
                            formType === "login" &&
                            responseJson.status === 400
                        ) {
                            errorMessage =
                                'Your email is not verified. Please verify it <a class="color-midnight text-decoration-underline fw-300 fs-16 open-popup" data-target="resend-otp" href="javascript:void(0)">here</a>.';
                        } else {
                            errorMessage =
                                typeof responseJson.message === "string"
                                    ? responseJson.message
                                    : responseJson.message.error;
                        }

                        message.innerHTML = errorMessage;
                        message.classList.add("error");
                        Utils.attachOpenPopupEvent(
                            message.querySelector(".open-popup")
                        );
                        return;
                    }

                    if (responseJson?.errors) {
                        const errors = responseJson?.errors;
                        if (typeof errors === "object") {
                            Object.keys(errors).forEach((key) => {
                                const field = form.querySelector(
                                    `[name="${key}"]`
                                );
                                if (field) {
                                    const errorElement =
                                        document.createElement("div");
                                    errorElement.classList.add("error-message");
                                    errorElement.innerText = errors[key][0];
                                    field.parentNode.appendChild(errorElement);
                                }
                            });
                        }
                        message.classList.add("error");
                        return;
                    }

                    message.innerHTML = responseJson.message;
                    if (responseJson.redirect_url) {
                        if (responseJson.redirect_url === "reload") {
                            window.location.reload();
                            return;
                        }
                        window.location.href = responseJson.redirect_url;
                        return;
                    }

                    if (formType === "register") {
                        form.reset();
                        new Modal().open(
                            document.querySelector(popups.verifyOtp)
                        );
                    } else if (formType === "otp") {
                        form.reset();
                        message.classList.remove("error");
                        message.classList.add(
                            "alert-success",
                            "d-block",
                            "px-2",
                            "py-2"
                        );
                        message.innerHTML = otpSuccess;
                        Utils.attachOpenPopupEvent(
                            message.querySelector(".open-popup")
                        );
                        // new Modal().open(document.querySelector(popups.login));
                    } else if (formType === "resend-otp") {
                        form.reset();
                        new Modal().open(
                            document.querySelector(popups.verifyOtp)
                        );
                    }
                } catch (error) {
                    message.classList.add("error");
                    if (error.errors) {
                        const errors = error.errors;
                        if (typeof errors === "object") {
                            Object.keys(errors).forEach((key) => {
                                const field = form.querySelector(
                                    `[name="${key}"]`
                                );
                                if (field) {
                                    const errorElement =
                                        document.createElement("div");
                                    errorElement.classList.add(
                                        "form-message",
                                        "error",
                                        "inline-error"
                                    );
                                    errorElement.innerText = errors[key][0];
                                    field.parentNode.appendChild(errorElement);
                                }
                            });
                        }
                    } else if (error.message) {
                        if (typeof error.message === "object") {
                            message.innerHTML = error.message?.error;
                        } else {
                            message.innerHTML = error.message;
                        }
                    } else {
                        message.innerHTML = serverError;
                    }
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove(loadingButton);
                }
            });
        });
    }
}

class CreditCard {
    constructor() {
        const form = document.getElementById("creditCardForm");
        if (!form) {
            return;
        }
        this.setupForm();
    }

    setupForm() {
        // DOM elements cache

        const inputCardNumber = document.querySelector(
            'input[name="CreditCardNumber"]'
        );
        const inputCardHolder = document.querySelector(
            'input[name="CardHolder"]'
        );
        const inputCardMonth = document.querySelector(
            'select[name="ExpMonth"]'
        );
        const inputCardYear = document.querySelector('select[name="ExpYear"]');
        const inputCardCVV = document.querySelector('input[name="CVV"]');

        const cardNumberElement = document.getElementById("cardNumber");
        const cardHolderElement = document.getElementById("cardHolderName");
        const cardExpiryMonthElement =
            document.getElementById("cardExpiryMonth");
        const cardExpiryYearElement = document.getElementById("cardExpiryYear");
        const cardCVVElement = document.getElementById("cardCVV");
        const cardTypeImage = document.getElementById("cardTypeImage");

        const cartItem = document.querySelector(".card-item");

        // Card type patterns
        const CARDS = {
            visa: "^4",
            amex: "^(34|37)",
            mastercard: "^5[1-5]",
            discover: "^6011",
            unionpay: "^62",
            troy: "^9792",
            diners: "^(30[0-5]|36)",
        };

        // Get card type based on card number pattern
        const getCardType = (cardNumber) => {
            for (const [card, pattern] of Object.entries(CARDS)) {
                const regex = new RegExp(pattern);
                if (regex.test(cardNumber)) {
                    return card;
                }
            }
            return "visa"; // default to Visa
        };

        // Mask all but the last 4 digits of the card number
        const maskCardNumber = (cardNumber) =>
            cardNumber.replace(/.(?=.{4,}$)/g, "*");

        // Update UI for card details
        const updateCardUI = () => {
            const cardNumber = inputCardNumber.value.replace(/\D/g, "");
            const cardHolder = inputCardHolder.value;
            const cardMonth = inputCardMonth.value.replace(/\D/g, "");
            const cardYear = inputCardYear.value.replace(/\D/g, "");
            const cardCVV = inputCardCVV.value.replace(/\D/g, "");
            // Update card number with masking
            cardNumberElement.textContent =
                maskCardNumber(cardNumber) || "#### #### #### ####";

            // Update card holder name
            cardHolderElement.textContent = cardHolder || "FULL NAME";

            // Update expiry date
            cardExpiryMonthElement.textContent = cardMonth || "MM";
            cardExpiryYearElement.textContent = cardYear
                ? cardYear.slice(-2)
                : "YY";

            // Update CVV with masking
            cardCVVElement.textContent = cardCVV.replace(/./g, "*") || "***";

            // Update card type image
            const cardType = getCardType(cardNumber);
            const src = cardTypeImage.src;

            // Extract the directory path (everything before the last '/')
            const directoryPath = src.substring(0, src.lastIndexOf("/"));

            // Update the image src by replacing only the file name
            cardTypeImage.src =
                CARD_IMAGES[cardType] || `${directoryPath}/${cardType}.png`;
        };

        // Helper function to attach event listeners to multiple elements
        const attachEventListeners = (elements, eventType, handler) => {
            elements.forEach((element) => {
                element.addEventListener(eventType, handler);
            });
        };

        // Event listeners for form inputs
        attachEventListeners(
            [
                inputCardNumber,
                inputCardHolder,
                inputCardMonth,
                inputCardYear,
                inputCardCVV,
            ],
            "input",
            updateCardUI
        );

        // Current Year and Arrays for Months and Years
        const currentYear = new Date().getFullYear();
        const monthsArr = Array.from({ length: 12 }, (x, i) =>
            (i + 1).toString().padStart(2, "0")
        );
        const yearsArr = Array.from({ length: 20 }, (x, i) => currentYear + i);

        // Variables to store form inputs
        let cardNumber = "";

        // Handle form changes
        function handleFormChange(event) {
            const { name, value } = event.target;
            updateState(name, value);
        }

        // Update state function
        function updateState(name, value) {
            // You can handle the state logic or display updates here
            // console.log(`${name}: ${value}`);
        }

        // Handle card number input and formatting
        function onCardNumberChange(event) {
            let value = event.target.value.replace(/\D/g, "");
            if (/^3[47]\d{0,13}$/.test(value)) {
                value = value
                    .replace(/(\d{4})/, "$1 ")
                    .replace(/(\d{4}) (\d{6})/, "$1 $2 ");
            } else if (/^3(?:0[0-5]|[68]\d)\d{0,11}$/.test(value)) {
                value = value
                    .replace(/(\d{4})/, "$1 ")
                    .replace(/(\d{4}) (\d{6})/, "$1 $2 ");
            } else if (/^\d{0,16}$/.test(value)) {
                value = value
                    .replace(/(\d{4})/, "$1 ")
                    .replace(/(\d{4}) (\d{4})/, "$1 $2 ")
                    .replace(/(\d{4}) (\d{4}) (\d{4})/, "$1 $2 $3 ");
            }
            let cardNumber = value.trim();
            if (cardNumber == "") {
                event.target.value = "";
            }
            updateState("cardNumber", cardNumber);
        }

        function onCvvInput(event) {
            let value = event.target.value.replace(/\D/g, "");
            if (/^\d{0,4}$/.test(value)) {
                value = value.replace(/(\d{4})/, "$1 ");
            }
            let cardCVV = value.trim();
            if (cardCVV == "") {
                event.target.value = "";
            }
            updateState("cardCVV", cardCVV);
        }

        // Handle CVV focus and blur
        function onCvvFocus() {
            cartItem.classList.add("-active");
        }

        function onCvvBlur() {
            cartItem.classList.remove("-active");
        }

        // Handle focus and blur for other inputs
        function onInputFocus(event, fieldName) {
            updateState(`${fieldName}Focused`, true);
        }

        function onInputBlur(event) {
            updateState("isFocused", false);
        }

        // Populate months and years
        const selectedMonth = inputCardMonth.getAttribute("value") || "";
        monthsArr.forEach((month) => {
            const option = document.createElement("option");
            option.value = month;
            option.textContent = month;
            if (month == selectedMonth) {
                option.selected = true;
            }
            inputCardMonth.appendChild(option);
        });

        // Populate years
        const selectedYear = inputCardYear.getAttribute("value") || "";
        yearsArr.forEach((year) => {
            const option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            if (year == selectedYear) {
                option.selected = true;
            }
            inputCardYear.appendChild(option);
        });

        // Event listeners
        inputCardNumber.addEventListener("input", onCardNumberChange);
        inputCardCVV.addEventListener("input", onCvvInput);
        inputCardCVV.addEventListener("focus", onCvvFocus);
        inputCardCVV.addEventListener("blur", onCvvBlur);
        inputCardHolder.addEventListener("input", handleFormChange);
        inputCardMonth.addEventListener("change", handleFormChange);
        inputCardYear.addEventListener("change", handleFormChange);
    }
}

class Utils {
    selectors = {
        navItemDropdown: ".nav-link.dropdown-toggle",
    };

    constructor() {
        this.setupEventListeners();
    }

    // Setup all event listeners
    setupEventListeners() {
        // Event listener for the dropdown menu
        const { navItemDropdown } = this.selectors;
        document.querySelectorAll(navItemDropdown).forEach((dropdown) => {
            dropdown.addEventListener("click", this.toggleDropdown.bind(this));
        });
    }

    // Get the value of a query string from a URL
    getQueryStringValue(key, url = window.location.href) {
        return new URL(url).searchParams.get(key);
    }

    // Toggle dropdown
    toggleDropdown(e) {
        const target = e.currentTarget.dataset.target;
        const dropdownMenu = document.getElementById(target);
        if (
            dropdownMenu.style.display === "none" ||
            dropdownMenu.style.display === ""
        ) {
            dropdownMenu.style.display = "block";
        } else {
            dropdownMenu.style.display = "none";
        }
    }

    static initSlider(sliderSelector) {
        const centerMode =
            $(sliderSelector).data("center-mode") == false ? false : true;
        const initialSlide = $(sliderSelector).data("initial-slide") || 0;

        $(`${sliderSelector}:not(.slick-initialized)`).slick({
            infinite: true,
            initialSlide: parseInt(initialSlide),
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: globals.prevArrow,
            nextArrow: globals.nextArrow,
            centerMode: centerMode,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 740,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 540,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }

    static attachOpenPopupEvent(sourceElement) {
        if (!sourceElement) {
            return;
        }
        sourceElement.addEventListener("click", (e) => {
            e.preventDefault();
            const popupId = sourceElement.getAttribute("data-target");
            const popup =
                document.querySelector(`.popup[popup-id="${popupId}"]`) ||
                document.querySelector(`#${popupId}`);
            if (!popup) {
                return;
            }
            document
                .querySelectorAll(".popup")
                .forEach((popup) => popup.classList.remove("active"));
            popup
                .querySelectorAll(".form-message")
                .forEach((message) => (message.innerHTML = ""));
            popup.classList.add("active");
            Utils.attachClosePopupEvent(popup);
        });
    }

    static attachClosePopupEvent(popup) {
        if (!popup) {
            return;
        }
        popup.querySelector(".closepop").addEventListener("click", (e) => {
            e.preventDefault();
            popup.classList.remove("active");
        });
    }
}

class BookingSession {
    constructor(config = {}) {
        this.defaultConfig = {
            confirmBtnText: "Pick [timeslot]",
            bookableItems: null,
            date: null,
            popup: document.querySelector(".pick-time"),
            startDateTimeInput: null,
        };

        this.config = { ...this.defaultConfig, ...config };

        let bookableItems = this.config.bookableItems;
        if (!bookableItems) {
            const bookableItemsElement =
                document.getElementById("bookableItems");
            if (!bookableItemsElement) {
                return; // Do nothing if no bookable items are provided
            }

            bookableItems = JSON.parse(
                bookableItemsElement.textContent || "{}"
            );

            if (!bookableItems) {
                return; // Do nothing if no bookable items are provided
            }
        }

        this.bookableItems = bookableItems;
        this.enabledDates = Object.keys(bookableItems);
        this.date = this.config.date;
        this.popup = this.config.popup;
        // Extract selectors inside the selectors object
        this.selectors = {
            bookingSessionForm: document.querySelector(".booking-session-form"),
            timepickerField: document.querySelector(".time-picker"),
            timepickerPopupBtn: document.querySelector(
                ".time-picker .open-popup"
            ),
            bookAppointmentButton: document.querySelector("#book-appoinment"),
            timePickerPopup: this.popup,
            confirmBtn: document.getElementById("confirm-time-slot"),
            formMessage: document.querySelector(".form-message"),
            timeItems: document.querySelectorAll(".time-item"),
        };

        // this.initDatepicker();
    }

    // Utility method to format a date as 'YYYY-MM-DD'
    formatDate(date) {
        return moment(new Date(date)).format("YYYY-MM-DD");
    }

    // Method to check if a date is in the enabled dates array
    enableOnlyTheseDates(date) {
        const dateString = this.formatDate(date);
        return [this.enabledDates.includes(dateString)];
    }

    // Method to handle date selection
    handleDateSelect(date) {
        const formattedDate = this.formatDate(date);
        this.date = formattedDate;
        const timeSlots = this.bookableItems[formattedDate];

        this.setupTimePicker(timeSlots);
        this.updateSelectedDateDisplay(date);
        this.resetConfirmButton();
        this.setupConfirmButton();
    }

    // Method to setup the time picker UI
    setupTimePicker(timeSlots, enableTimePickerUI = true) {
        const { timeItems } = this.selectors;

        timeItems.forEach((slot) => {
            const slotTime = slot.getAttribute("data-time");

            if (timeSlots[slotTime]) {
                slot.classList.remove("booked");
            } else {
                slot.classList.add("booked");
            }

            slot.onclick = () => {
                this.selectTimeSlot(slot);
            };
        });

        if (enableTimePickerUI) {
            this.enableTimePickerUI();
        }
    }

    // Method to enable the time picker UI elements
    enableTimePickerUI() {
        const { timepickerField, timepickerPopupBtn, bookAppointmentButton } =
            this.selectors;

        timepickerField.classList.remove("disabled");
        timepickerPopupBtn.classList.remove("disabled");
        bookAppointmentButton.disabled = true;
    }

    // Method to update the selected date display
    updateSelectedDateDisplay() {
        const { date } = this;
        const { timePickerPopup } = this.selectors;

        const formattedDisplayDate = moment
            .parseZone(new Date(date).toUTCString())
            .format("MMMM, dddd Do");
        timePickerPopup.querySelector(".selected-date").textContent =
            formattedDisplayDate;
    }

    // Method to reset the confirm button
    resetConfirmButton() {
        const { confirmBtn } = this.selectors;
        confirmBtn.disabled = true;
        confirmBtn.querySelector("span").textContent = "PICK A TIME";
    }

    // Method to setup the confirm button's click handler
    setupConfirmButton() {
        const { date } = this;
        const {
            confirmBtn,
            bookAppointmentButton,
            timePickerPopup,
            bookingSessionForm,
        } = this.selectors;

        confirmBtn.onclick = (e) => {
            e.preventDefault();
            const selectedSlot = timePickerPopup.querySelector(
                ".time-item.selected"
            );
            if (!selectedSlot) {
                const formMessage =
                    bookingSessionForm.querySelector("form-message");
                formMessage.classList.add("error");
                formMessage.textContent = "Please select a time slot";
                return; // Do nothing if no slot is selected
            }

            const selectedSlotTime = selectedSlot.getAttribute("data-time");

            timePickerPopup.classList.remove("active");

            const startDateTime = moment(
                `${date} ${selectedSlotTime.split(" - ")[0]}`,
                "YYYY-MM-DD HH:mm A"
            ).format("YYYY-MM-DDTHH:mm:ss");

            const StartDateTimeInput = bookingSessionForm.querySelector(
                'input[name="StartDateTime"]'
            );
            if (StartDateTimeInput) {
                StartDateTimeInput.value = startDateTime;
            }

            const start_date_time_input = bookingSessionForm.querySelector(
                'input[name="start_date_time"]'
            );
            if (start_date_time_input) {
                start_date_time_input.value = startDateTime;
            }

            bookingSessionForm.querySelector("#timepicker").textContent =
                selectedSlotTime;
            bookAppointmentButton.disabled = false;
        };
    }

    // Method to handle time slot selection
    selectTimeSlot(slot) {
        if (slot.classList.contains("booked")) {
            return; // Do nothing if the slot is disabled
        }

        const { timeItems, confirmBtn } = this.selectors;

        // Remove 'selected' class from all slots
        timeItems.forEach((s) => s.classList.remove("selected"));

        // Add 'selected' class to the clicked slot
        slot.classList.add("selected");

        // Enable the confirm button and update its text
        const selectedSlot = slot.getAttribute("data-time");
        const selectedSlotText = slot.getAttribute("data-time-text");

        if (selectedSlot && this.config.startDateTimeInput) {
            this.config.startDateTimeInput.value = moment(
                `${this.date} ${selectedSlot.split(" - ")[0]}`,
                "YYYY-MM-DD HH:mm"
            ).format("YYYY-MM-DDTHH:mm:ss");
        }
        confirmBtn.disabled = false;
        confirmBtn.querySelector("span").textContent =
            this.config.confirmBtnText.replace("[timeslot]", selectedSlotText);
    }

    // Method to initialize the datepicker
    initDatepicker() {
        $("#datepicker").datepicker({
            dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            changeYear: true, // Enables year dropdown
            yearRange:
                new Date().getFullYear() +
                ":" +
                (new Date().getFullYear() + 25),
            onSelect: (date) => this.handleDateSelect(date),
            beforeShowDay: (date) => this.enableOnlyTheseDates(date),
        });
    }
}

/*
 * Rewards slider
 */
class Rewards {
    constructor(sliderSelector) {
        if (!sliderSelector) {
            return console.warn(
                `Invalid reward slider selector "${sliderSelector}" is provided.`
            );
        }

        this.slider = document.querySelector(sliderSelector);
        if (!this.slider) {
            return console.warn(
                `Reward slider with selector "${sliderSelector}" does not exist.`
            );
        }

        this.sliderSelector = sliderSelector;
        Utils.initSlider(this.sliderSelector);
        this.init();
    }

    displayRewards(rewards) {
        const rewardsSlider = this.slider;

        if (!rewards.length) {
            return;
        }

        $(`${this.sliderSelector}.slick-initialized`).slick("unslick");

        rewardsSlider.classList.remove("skeleton-slider");
        rewardsSlider.innerHTML = "";
        rewards.forEach((reward) => {
            const rewardItem = `
            <div class="reward">
                <a href="javascript:void(0)">
                    <div class="reward-img">
                        <img class="mw-100" src="${reward.image}"
                            alt="Rewards">
                    </div>
                    <div class="reward-info">
                        <p class="fs-20 fw-300 lh-12 color-blue">${reward.description}</p>
                        <div class="btn-wrap">
                            <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">${reward.title}</span>
                        </div>
                    </div>
                </a>
            </div>`;

            rewardsSlider.insertAdjacentHTML("beforeend", rewardItem);
        });

        Utils.initSlider(this.sliderSelector);
    }

    init() {
        const self = this;
        let routeUrl = this.slider.dataset?.routeUrl;
        if (!routeUrl) {
            return console.warn(`Invalid route URL ${routeUrl} provided`);
        }

        if (
            window.location.protocol === "https:" &&
            routeUrl.startsWith("http://")
        ) {
            routeUrl = routeUrl.replace("http://", "https://");
        }
        if (!routeUrl) {
            return console.warn(`Invalid route URL ${routeUrl} provided`);
        }

        async function fetchRewards() {
            try {
                const response = await axios.get(routeUrl);

                if (response.data.status !== 200) {
                    return [];
                }

                self.displayRewards(response.data.data);
            } catch (error) {
                return [];
            }
        }

        fetchRewards();
    }
}

/*
 * Loyality slider
 */
class Loyality {
    constructor(sliderSelector) {
        if (!sliderSelector) {
            return console.warn(
                `Invalid loyalty slider selector "${sliderSelector}" is provided.`
            );
        }

        this.slider = document.querySelector(sliderSelector);
        if (!this.slider) {
            return console.warn(
                `Loyalty slider with selector "${sliderSelector}" does not exist.`
            );
        }

        this.sliderSelector = sliderSelector;
        Utils.initSlider(this.sliderSelector);
        this.init();
    }

    displayLoyaltyLevels(levels) {
        const levelsSlider = this.slider;

        if (!levels.length) {
            return;
        }

        $(`${this.sliderSelector}.slick-initialized`).slick("unslick");

        levelsSlider.classList.remove("skeleton-slider");
        levelsSlider.innerHTML = "";
        levels.forEach((level) => {
            const levelItem = `
            <div class="level">
                <a data-id="${
                    level.id
                }" href="javascript:void(0)" class="open-popup ${
                level.selected ? "bg-midnight" : ""
            }" data-target="loyality">
                    <div class="level-card ${
                        level.image ? "" : "skeleton-box"
                    } text-center">
                        <img class="mw-100" src="${
                            level.image
                        }" alt="Rewards" width="100" height="100">
                        <p class="${
                            level.title ? "" : "skeleton-button"
                        } border-radius-50 ${
                level.selected ? "color-white" : "color-midnight"
            } fw-700 fs-20 lh-12 text-uppercase">${level.title}</p>
                        <p class="${level.week ? "" : "skeleton-text"} ${
                level.selected ? "color-white" : "color-midnight"
            }">${level.week}</p>
                    </div>
                </a>
            </div>`;

            levelsSlider.insertAdjacentHTML("beforeend", levelItem);
        });

        new Modal().init();

        Utils.initSlider(this.sliderSelector);

        document.addEventListener("popup:opened", (e) => {
            const popup = e.detail.popup;
            const trigger = e.detail.trigger;

            if (popup.id !== "loyality") {
                return;
            }

            const loyaltyLevel = levels.find(
                (level) => level.id == trigger.dataset.id
            );
            const loyaltyPopup = document.getElementById("loyality");
            const loyaltyPopupTitle =
                loyaltyPopup.querySelector(".popup-title h2");
            const loyaltyPopupImage =
                loyaltyPopup.querySelector(".popup-logo img");
            const loyaltyPopupDescription =
                loyaltyPopup.querySelector(".popup-title p");
            const loyaltyPopupButton =
                loyaltyPopup.querySelector(".btn-wrap a");

            if (!loyaltyLevel) {
                return;
            }

            loyaltyPopupTitle.textContent = loyaltyLevel.title;
            loyaltyPopupImage.src = loyaltyLevel.image;
            loyaltyPopupDescription.textContent = loyaltyLevel.description;
            loyaltyPopupButton.href = loyaltyLevel.link;
        });
    }

    init() {
        const self = this;
        let routeUrl = this.slider.dataset?.routeUrl;
        if (
            window.location.protocol === "https:" &&
            routeUrl.startsWith("http://")
        ) {
            routeUrl = routeUrl.replace("http://", "https://");
        }
        if (!routeUrl) {
            return console.warn(`Invalid route URL ${routeUrl} provided`);
        }

        async function fetchLoyaltyLevels() {
            try {
                const response = await axios.get(routeUrl);

                if (response.data.status !== 200) {
                    return [];
                }

                self.displayLoyaltyLevels(response.data.data);
            } catch (error) {
                return [];
            }
        }

        fetchLoyaltyLevels();
    }
}

class EncryptFormData {
    constructor(data) {
        this.data = data;
    }

    async init() {
        return await this.convert();
    }

    async convert(data = this.data) {
        try {
            if (!data) {
                throw new Error(
                    `Invalid data of type "${typeof data}" is provided.`,
                    error
                ); // Return null for invalid data
            }

            if (typeof data !== "string") {
                if (data instanceof FormData) {
                    const formObject = {};
                    data.forEach((value, key) => {
                        formObject[key] = value;
                    });
                    data = JSON.stringify(formObject);
                }

                // Encrypt the form data
                if (typeof data === "object") {
                    data = JSON.stringify(data);
                }
            }

            if (typeof data !== "string") {
                throw new Error(
                    `Invalid data of type "${typeof data}" is provided.`,
                    error
                ); // Return null for invalid data
            }

            const cipherText = await this.encryptDataRSA(data);

            return await this.getEncryptedData(cipherText);
        } catch (error) {
            throw new Error("Error converting data:", error);
        }
    }

    async importPublicKey() {
        try {
            const pem = PUBLIC_RSA_KEY;
            const binaryDer = window.atob(
                pem.replace(
                    /-----BEGIN PUBLIC KEY-----|-----END PUBLIC KEY-----|\s+/g,
                    ""
                )
            );
            const binaryBuffer = Uint8Array.from(binaryDer, (char) =>
                char.charCodeAt(0)
            );
            return await window.crypto.subtle.importKey(
                "spki",
                binaryBuffer.buffer,
                {
                    name: "RSA-OAEP",
                    hash: { name: "SHA-256" },
                },
                true,
                ["encrypt"]
            );
        } catch (error) {
            throw new Error("Error importing public key:", error);
        }
    }

    async encryptDataRSA(plainText) {
        try {
            const cryptoKey = await this.importPublicKey();

            const enc = new TextEncoder();
            const encodedData = enc.encode(plainText);
            const encryptedData = await window.crypto.subtle.encrypt(
                {
                    name: "RSA-OAEP",
                },
                cryptoKey,
                encodedData
            );
            return btoa(String.fromCharCode(...new Uint8Array(encryptedData)));
        } catch (error) {
            throw new Error("Error encrypting data: " + error.message);
        }
    }

    async getEncryptedData(cipherText) {
        try {
            const response = await fetch("/api/submit-form", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ data: cipherText }),
            });

            if (!response.ok) {
                // Check if the response is OK
                throw new Error("Network response was not ok:", error);
            }

            return await response.json(); // Parse the JSON response
        } catch (error) {
            throw new Error("Fetch error:", error);
        }
    }
}

window.app = {
    Modal,
    UIController,
    FormController,
    CreditCard,
    Utils,
    BookingSession,
    Loyality,
    Rewards,
};

// Instantiate the UI Controller to set everything up
document.addEventListener("DOMContentLoaded", () => {
    new Modal().init();
    new UIController();
    new FormController();
    new CreditCard();
    new Utils();
    new BookingSession().initDatepicker();
});
