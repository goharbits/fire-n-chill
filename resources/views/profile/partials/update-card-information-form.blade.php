<section>
    <header>
        <h2 class="mb-4 color-midnight">
            {{ __('Billing Information') }}
        </h2>

        <p class="mt-1 color-midnight">
            {{ __("Update your credit card's information.") }}
        </p>
    </header>
    <div class="container-sm mx-0 px-0">
        <div class="mt-5 space-y-6">
            <div class="card-item">
                <div class="card-item__side -front">
                    <div class="card-item__focus" id="cardFocus"></div>
                    <div class="card-item__cover">
                        <img alt="" src="{{ Vite::image('cards-bg/'.rand(1,25).'.jpeg') }}" class="card-item__bg" />
                    </div>
        
                    <div class="card-item__wrapper">
                        <div class="card-item__top">
                            <img src="{{ Vite::image('chip.png') }}" alt="" class="card-item__chip" />
                            <div class="card-item__type">
                                <img alt="{{ $credit_card ? $credit_card->CardType : 'visa' }}" src="{{ Vite::image('card-type/'.( $credit_card ? strtolower($credit_card->CardType) : 'visa' ).'.png') }}" class="card-item__typeImg"
                                    id="cardTypeImage" />
                            </div>
                        </div>
        
                        <label class="card-item__number" id="cardNumberLabel">
                            <div id="cardNumber">#### #### #### {{ $credit_card ? $credit_card->LastFour : '####' }}</div>
                        </label>
        
                        <div class="card-item__content">
                            <label class="card-item__info" id="cardHolderLabel">
                                <div class="card-item__holder">{{ $credit_card ? $credit_card->CardHolder : 'Card Holder' }}</div>
                                <div class="card-item__name" id="cardHolderName">{{ $credit_card ? $credit_card->LastFour : 'FULL NAME' }}</div>
                            </label>
        
                            <div class="card-item__date" id="cardDateLabel">
                                <label class="card-item__dateTitle">Expires</label>
                                <label class="card-item__dateItem" id="cardExpiryMonth">{{ $credit_card ? $credit_card->ExpMonth : 'MM' }}</label> /
                                <label class="card-item__dateItem" id="cardExpiryYear">{{ $credit_card ? $credit_card->ExpYear : 'YY' }}</label>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="card-item__side -back">
                    <div class="card-item__cover">
                        <img alt="" src="{{ Vite::image('cards-bg/'.rand(1,25).'.jpeg') }}" class="card-item__bg" />
                    </div>
                    <div class="card-item__band"></div>
                    <div class="card-item__cvv">
                        <div class="card-item__cvvTitle">CVV</div>
                        <div class="card-item__cvvBand" id="cardCVV">***</div>
                    </div>
                </div>
            </div>
            
            <div class="card-form mt-5">
                <div class="card-list"></div>
                <div class="card-form__inner mt-5">
                    <form method="post" action="{{ route('client.update.card') }}" class="mt-6 space-y-6" id="creditCardForm" data-encrypt="true" data-js-form>
                        @csrf
            
                        <div class="field-block">
                            <x-input-label for="CreditCardNumber" :value="__('Card Number')" />
                            <x-text-input id="CreditCardNumber" data-encrypted-field name="CreditCardNumber" type="tel"  maxlength="19" class="mt-1 block w-100" :value="old('CreditCardNumber', $credit_card->CardNumber ?? '' )" required autofocus autocomplete="CreditCardNumber" />
                            <x-input-error class="mt-2" :messages="$errors->get('CreditCardNumber')" />
                        </div>
                
                        <div class="field-block">
                            <x-input-label for="CardHolder" :value="__('Card Holder')" />
                            <x-text-input id="CardHolder" name="CardHolder" type="text" class="mt-1 block w-100" :value="old('CardHolder', $credit_card->CardHolder ?? '' )" required autofocus autocomplete="CardHolder" />
                            <x-input-error class="mt-2" :messages="$errors->get('CardHolder')" />
                        </div>
                
        
                        <div class="d-flex justify-content-around align-items-center w-100 mb-2 gap-3">
                            <div class="field-block w-100">
                                <x-input-label for="ExpMonth" :value="__('Expiry Month')" />
                                <select id="ExpMonth" data-encrypted-field name="ExpMonth" class="mt-2" required value="{{ old('ExpMonth', $credit_card->ExpMonth ?? '' ) }}"></select>
                                <x-input-error class="mt-2" :messages="$errors->get('ExpMonth')" />
                            </div>
                    
                            <div class="field-block w-100">
                                <x-input-label for="ExpYear" :value="__('Expiry Year')" />
                                <select id="ExpYear" data-encrypted-field name="ExpYear" class="mt-2" required value="{{ old('ExpYear', $credit_card->ExpYear ?? '' ) }}"></select>
                                <x-input-error class="mt-2" :messages="$errors->get('ExpYear')" />
                            </div>
                        </div>
                
                        <div class="field-block">
                            <x-input-label for="CVV" :value="__('CVV')" />
                            <x-text-input id="CVV" data-encrypted-field name="CVV" type="tel"  maxlength="4" class="mt-1 block w-100" autofocus autocomplete="off" required />
                            <x-input-error class="mt-2" :messages="$errors->get('CVV')" />
                        </div>
                
                        <div class="btn-wrap d-flex justify-content-center flex-column gap-4">
                            @if (session('status') === 'card-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-success fs-15 text-center"
                                >{{ __('Credit card details saved.') }}</p>
                            @endif
                            <x-primary-button>{{ __( $credit_card ? 'UPDATE CARD' : 'ADD CARD' ) }}</x-primary-button>
                
                        </div>
                    </form>
                </div>
            </div>    
        </div>
    </div>
</section>
