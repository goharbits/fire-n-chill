@if (!isset($show_form))
    <?php $show_form = true; ?>
@endif

@if ($show_form)
    <div style="display:none;" class="page-popup-section popup" aria-modal="credit-card" id="credit-card">
        <div class="popup-container position-relative">
            <div class="closepop position-absolute">
                <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
            </div>
            <div class="popup-title text-center">
                <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">Update credit card</h2>
            </div>
@endif
<div class="popup-holder">
    <div class="poup-form credit-card-form">

        <div class="card-item">
            <div class="card-item__side -front">
                <div class="card-item__focus" id="cardFocus"></div>
                <div class="card-item__cover">
                    <img alt="" src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}" class="card-item__bg" />
                </div>

                <div class="card-item__wrapper">
                    <div class="card-item__top">
                        <img src="{{ Vite::image('chip.png') }}" alt="" class="card-item__chip" />
                        <div class="card-item__type">
                            <img alt="{{ $credit_card ? $credit_card->CardType : 'visa' }}"
                                src="{{ Vite::image('card-type/' . ($credit_card ? strtolower($credit_card->CardType) : 'visa') . '.png') }}"
                                class="card-item__typeImg" id="cardTypeImage" />
                        </div>
                    </div>

                    <label class="card-item__number" id="cardNumberLabel">
                        <div id="cardNumber">#### #### #### {{ $credit_card ? $credit_card->LastFour : '####' }}</div>
                    </label>

                    <div class="card-item__content">
                        <label class="card-item__info" id="cardHolderLabel">
                            <div class="card-item__holder">Card Holder</div>
                            <div class="card-item__name" id="cardHolderName">
                                {{ $credit_card ? $credit_card->CardHolder : 'FULL NAME' }}</div>
                        </label>

                        <div class="card-item__date" id="cardDateLabel">
                            <label class="card-item__dateTitle">Expires</label>
                            <label class="card-item__dateItem"
                                id="cardExpiryMonth">{{ $credit_card ? $credit_card->ExpMonth : 'MM' }}</label> /
                            <label class="card-item__dateItem"
                                id="cardExpiryYear">{{ $credit_card ? $credit_card->ExpYear : 'YY' }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-item__side -back">
                <div class="card-item__cover">
                    <img alt="" src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}"
                        class="card-item__bg" />
                </div>
                <div class="card-item__band"></div>
                <div class="card-item__cvv">
                    <div class="card-item__cvvTitle">CVV</div>
                    <div class="card-item__cvvBand" id="cardCVV">***</div>
                </div>
            </div>
        </div>

        @if ($show_form)
            <form method="POST" action="{{ route('client.update.card') }}" data-js-form id="creditCardForm"
                data-encrypt="true">
                @csrf
        @endif
        <div class="card-form" {{ $show_form ? 'id=creditCardForm' : '' }}>
            <div class="card-list"></div>
            <div class="card-form__inner mt-5">
                <div class="card-input">
                    <label for="CreditCardNumber" class="card-input__label">Card Number</label>
                    <input type="tel" name="CreditCardNumber" data-encrypted-field class="card-input__input"
                        required autocomplete="off" maxlength="19"
                        value="{{ $credit_card ? $credit_card->CardNumber : '' }}" />
                </div>
                <div class="card-input">
                    <label for="CardHolder" class="card-input__label">Card Holder</label>
                    <input id="CardHolder" type="text" name="CardHolder" required class="card-input__input"
                        autocomplete="off" value="{{ $credit_card ? $credit_card->CardHolder : '' }}" />
                </div>
                <div class="card-form__row">
                    <div class="card-form__col">
                        <div class="card-form__group">
                            <label for="ExpMonth" class="card-input__label">Expiration Date</label>
                            <select name="ExpMonth" id="ExpMonth" data-encrypted-field
                                class="card-input__input -select" required
                                value="{{ $credit_card ? $credit_card->ExpMonth : '' }}"></select>
                            <select name="ExpYear" id="ExpYear" data-encrypted-field class="card-input__input -select"
                                required value="{{ $credit_card ? $credit_card->ExpYear : '' }}"></select>
                        </div>
                    </div>
                    <div class="card-form__col -cvv">
                        <div class="card-input">
                            <label for="CVV" class="card-input__label">CVV</label>
                            <input type="tel" data-encrypted-field name="CVV" class="card-input__input"
                                maxlength="4" autocomplete="off" required />
                        </div>
                    </div>
                </div>
                <div class="form-btn text-center">
                    <div class="field-block">
                        <p class="form-message"></p>
                    </div>
                    <div class="mgt-10 mx-auto">
                        <button type="submit"
                            class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn">
                            <span>{{ __($credit_card ? 'UPDATE CARD' : 'ADD CARD') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @if ($show_form)
            </form>
        @endif
    </div>
</div>
@if ($show_form)
    </div>
    </div>
@endif
