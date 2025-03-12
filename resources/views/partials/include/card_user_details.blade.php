<div class="credit-card-form mgb-60">
    <div class="card-form" id="creditCardForm">
        <div class="card-list"></div>
        <div class="card-form__inner">
            <div class="card-item mb-4">
                <div class="card-item__side -front">
                    <div class="card-item__focus" id="cardFocus"></div>
                    <div class="card-item__cover">
                        <img alt="" src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}"
                            class="card-item__bg" />
                    </div>

                    <div class="card-item__wrapper">
                        <div class="card-item__top">
                            <img src="{{ Vite::image('chip.png') }}" alt="" class="card-item__chip" />
                            <div class="card-item__type">
                                <img alt="visa" src="{{ Vite::image('card-type/visa.png') }}"
                                    class="card-item__typeImg" id="cardTypeImage" />
                            </div>
                        </div>

                        <label class="card-item__number" id="cardNumberLabel">
                            <div id="cardNumber">#### #### #### ####</div>
                        </label>

                        <div class="card-item__content">
                            <label class="card-item__info" id="cardHolderLabel">
                                <div class="card-item__holder">Card Holder</div>
                                <div class="card-item__name" id="cardHolderName">FULL NAME
                                </div>
                            </label>

                            <div class="card-item__date" id="cardDateLabel">
                                <label class="card-item__dateTitle">Expires</label>
                                <label class="card-item__dateItem" id="cardExpiryMonth">MM</label>
                                /
                                <label class="card-item__dateItem" id="cardExpiryYear">YY</label>
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
            @include('partials.include.user_detail_form')
            @include('partials.include.user_card_detail')
            @include('partials.include.error_with_button')

        </div>
    </div>
</div>
