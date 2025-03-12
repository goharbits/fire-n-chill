<x-app-layout>
    <!-- Page container -->
    <div class="page-container">

        <!-- Payment Method  -->
        <div class="pick-plan-section mgb-60">
            <div class="container">
                <form
                    @if (isset($contractId)) action="{{ route($guest ? 'guest.purchase.contracts' : 'sale.purchase.contracts') }}"
                    @elseif (isset($service_id))
                        @if ($service_id == 1)
                               action="{{ route($guest ? 'guest.sale.checkoutshoppingcart.free.service' : 'sale.checkoutshoppingcart.free.service') }}"
                        @else
                            action="{{ route($guest ? 'guest.sale.checkoutshoppingcart' : 'sale.checkoutshoppingcart') }}" @endif
                @else action="{{ route($guest ? 'guest.purchase.contracts' : 'sale.purchase.contracts') }}"
                    @endif
                    id="checkout-form" data-encrypt="true" data-js-form method="POST">
                    @csrf
                    @if ($guest)
                        <input type="hidden" name="guest" value="true">
                    @endif

                    @if (isset($contractId))
                        <input type="hidden" name="contractId" value="{{ $contractId }}">
                    @elseif (isset($service_id))
                        <input type="hidden" name="service_id" value="{{ $service_id }}">
                    @endif

                    @if ($StartDateTime)
                        <input type="hidden" name="StartDateTime" value="{{ $StartDateTime }}">
                    @endif

                    <div class="page-title text-center mgb-50 ">
                        <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc ">
                            Checkout</h1>
                    </div>

                    @include('partials.response')

                    @auth
                        @if ($service_id == 1)
                            <div class="credit-card-form mgb-60">
                                <div class="card-form" id="creditCardForm">
                                    <div class="card-list"></div>
                                    <div class="card-form__inner">
                                        <input type="hidden" name="first_name" value="{{ @$user->first_name }}">
                                        <input type="hidden" name="email" value="{{ @$user->email }}">
                                        <div class="form-field text-center">
                                            <p class="form-message"></p>
                                        </div>
                                        <div class="check-out-btn text-center mgt-10">
                                            <button type="submit"
                                                class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>
                                                    Book Appointment</span>
                                            </button>
                                            <div class="mgt-5">
                                                <?php
                                                $route = $guest ? 'guest.service.status' : 'client.service.status';
                                                $params = $guest ? ['guest' => 'true', 'StartDateTime' => request()->StartDateTime] : ['StartDateTime' => request()->StartDateTime];
                                                ?>
                                                <a class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline text-uppercase"
                                                    href="{{ route($route, $params) }}">Back to plan selection</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="credit-card-form mgb-60">
                                <div class="card-item">
                                    <div class="card-item__side -front">
                                        <div class="card-item__focus"></div>
                                        <div class="card-item__cover">
                                            <img alt=""
                                                src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}"
                                                class="card-item__bg" />
                                        </div>

                                        <div class="card-item__wrapper">
                                            <div class="card-item__top">
                                                <img src="{{ Vite::image('chip.png') }}" alt=""
                                                    class="card-item__chip" />
                                                <div class="card-item__type">
                                                    <img alt="{{ $credit_card ? $credit_card->CardType : 'visa' }}"
                                                        src="{{ Vite::image('card-type/' . ($credit_card ? strtolower($credit_card->CardType) : 'visa') . '.png') }}"
                                                        class="card-item__typeImg" />
                                                </div>
                                            </div>

                                            <label class="card-item__number">
                                                <div>#### #### #### {{ $credit_card ? $credit_card->LastFour : '####' }}
                                                </div>
                                            </label>

                                            <div class="card-item__content">
                                                <label class="card-item__info">
                                                    <div class="card-item__holder">Card Holder</div>
                                                    <div class="card-item__name">
                                                        {{ $credit_card ? $credit_card->CardHolder : 'FULL NAME' }}</div>
                                                </label>

                                                <div class="card-item__date">
                                                    <label class="card-item__dateTitle">Expires</label>
                                                    <label
                                                        class="card-item__dateItem">{{ $credit_card ? $credit_card->ExpMonth : 'MM' }}</label>
                                                    /
                                                    <label
                                                        class="card-item__dateItem">{{ $credit_card ? $credit_card->ExpYear : 'YY' }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-item__side -back">
                                        <div class="card-item__cover">
                                            <img alt=""
                                                src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}"
                                                class="card-item__bg" />
                                        </div>
                                        <div class="card-item__band"></div>
                                        <div class="card-item__cvv">
                                            <div class="card-item__cvvTitle">CVV</div>
                                            <div class="card-item__cvvBand" id="cardCVV">***</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-field text-center mt-5 mb-3">
                                <p class="form-message"></p>
                            </div>
                            <div class="check-out-btn text-center mgt-10">
                                @if ($user->hasPaymentMethod())
                                    <input type="hidden" name="LastFour" value="{{ $user->last_four }}">
                                    <div class="mgb-20 mgt-20">
                                        <a href="javascript:void(0)"
                                            class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline text-uppercase open-popup"
                                            data-target="credit-card"><span>Update card details</span></a>
                                    </div>
                                    <button type="submit"
                                        class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>Proceed
                                            to Checkout</span></button>
                                @else
                                    <div>
                                        <a href="javascript:void(0)"
                                            class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40 open-popup"
                                            data-target="credit-card">
                                            <span>Add card</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        @if ($service_id == 1)
                            @include('partials.include.user_detail')
                        @else
                            <div class="page-title text-center mb-3">
                                <h5 class="fs-35 color-midnight  text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                                    Select Payment Method</h5>
                            </div>
                            <div class="page-title text-center">
                                <div class="payment-options">
                                    <div class="payment-option">
                                        <input type="radio" id="pay-in-person" name="payment_mode" value="by_person"
                                            checked />
                                        <label for="pay-in-person">Pay In-Person</label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="pay-with-card" name="payment_mode" value="by_card" />
                                        <label for="pay-with-card">Pay with Card</label>
                                    </div>
                                </div>
                            </div>
                            <div class="page-title text-center mt-2">
                                <p>Reserve your best time and our team will assist you <br> with custom pricing options when
                                    you are in.</p>
                            </div>
                            <div class="payment-methods">
                                <div id="pay-in-person-content" class="payment-content">
                                    @include('partials.include.user_detail')
                                </div>
                                <div id="pay-with-card-content" class="payment-content">
                                    @include('partials.include.card_user_details')
                                </div>
                            </div>
                        @endif
                    @endauth
                </form>

            </div>
        </div>
        <!-- Payment Method end  -->
    </div>

    @auth
        @include('partials.popups.credit-card')
    @endauth

    @section('scripts')
        @parent
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                const submitButton = document.querySelector('.submit-form-btn');
                const form = document.getElementById('checkout-form');

                submitButton.addEventListener('click', function() {

                    // Check if the form is valid
                    if (form.checkValidity()) {
                        submitButton.disabled = true;
                        form.submit();
                    } else {
                        // Highlight invalid fields
                        form.reportValidity();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                // Get references to the radio buttons and content divs
                const payInPersonRadio = document.getElementById('pay-in-person');
                const payWithCardRadio = document.getElementById('pay-with-card');
                const payInPersonContent = document.getElementById('pay-in-person-content');
                const payWithCardContent = document.getElementById('pay-with-card-content');

                if (payInPersonRadio && payWithCardRadio) {
                    const togglePaymentMethod = () => {
                        // Remove content dynamically and display relevant one
                        if (payWithCardRadio.checked) {
                            if (payInPersonContent.parentElement) {
                                payInPersonContent.parentElement.removeChild(
                                    payInPersonContent); // Remove pay-in-person content
                            }
                            // Append payWithCardContent to the container
                            if (!payWithCardContent.parentElement) {
                                document.querySelector('.payment-methods').appendChild(payWithCardContent);
                            }
                            enableInputs(payWithCardContent);
                            disableInputs(payInPersonContent);
                        } else if (payInPersonRadio.checked) {
                            if (payWithCardContent.parentElement) {
                                payWithCardContent.parentElement.removeChild(
                                    payWithCardContent); // Remove pay-with-card content
                            }
                            // Append payInPersonContent to the container
                            if (!payInPersonContent.parentElement) {
                                document.querySelector('.payment-methods').appendChild(payInPersonContent);
                            }
                            enableInputs(payInPersonContent);
                            disableInputs(payWithCardContent);
                        }
                    };

                    // Function to disable inputs within a container
                    const disableInputs = (container) => {
                        const inputs = container.querySelectorAll('input, select, textarea');
                        inputs.forEach((input) => input.setAttribute('disabled', 'true'));
                    };

                    // Function to enable inputs within a container
                    const enableInputs = (container) => {
                        const inputs = container.querySelectorAll('input, select, textarea');
                        inputs.forEach((input) => input.removeAttribute('disabled'));
                    };

                    // Add event listeners to radio buttons
                    payInPersonRadio.addEventListener('change', togglePaymentMethod);
                    payWithCardRadio.addEventListener('change', togglePaymentMethod);

                    // Initial state check
                    togglePaymentMethod();
                }
            });


            document.addEventListener('DOMContentLoaded', function() {

                const planSelectors = document.querySelectorAll('.plan-selector');
                const servicePlanSelector = document.querySelector('.plan-selector[data-plan-type="service"]');
                const contractPlanSelectors = document.querySelectorAll('.plan-selector[data-plan-type="contract"]');
                const form = document.getElementById('checkout-form');
                const guest = '{{ $guest }}' === 'true';

                planSelectors.forEach((planSelector) => {
                    planSelector.addEventListener('change', function() {
                        const productType = this.getAttribute('data-plan-type');
                        if (productType === 'contract') {
                            servicePlanSelector.checked = false;
                            form.action =
                                "{{ route($guest ? 'guest.purchase.contracts' : 'sale.purchase.contracts') }}";
                        } else {
                            contractPlanSelectors.forEach((contractPlanSelector) => {
                                contractPlanSelector.checked = false;
                            });
                            form.action =
                                "{{ route($guest ? 'guest.sale.checkoutshoppingcart' : 'sale.checkoutshoppingcart') }}";
                        }
                    });
                });
            });
        </script>
    @endsection

</x-app-layout>
