<x-app-layout>
    <!-- Page container -->
    <div class="page-container">

        <!-- Payment Method  -->
        <div class="pick-plan-section mgb-60">
            <div class="container">

                <form
                    @if (isset($contractId)) action="{{ route($guest ? 'guest.purchase.contracts' : 'sale.purchase.contracts') }}"
                    @elseif (isset($service_id))
                        action="{{ route($guest ? 'guest.sale.checkoutshoppingcart' : 'sale.checkoutshoppingcart') }}"
                    @else
                        action="{{ route($guest ? 'guest.purchase.contracts' : 'sale.purchase.contracts') }}" @endif
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

                    <div class="page-title text-center mgb-50">
                        <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                            Checkout</h1>
                    </div>

                    @include('partials.response')

                    @auth
                        <div class="credit-card-form mgb-60">

                            <div class="card-item">
                                <div class="card-item__side -front">
                                    <div class="card-item__focus"></div>
                                    <div class="card-item__cover">
                                        <img alt="" src="{{ Vite::image('cards-bg/' . rand(1, 25) . '.jpeg') }}"
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
                                            <div>#### #### #### {{ $credit_card ? $credit_card->LastFour : '####' }}</div>
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
                    @else
                        <div class="credit-card-form mgb-60">



                            <div class="card-form" id="creditCardForm">
                                <div class="card-list"></div>
                                <div class="card-form__inner mt-5">
                                    <div class="card-item mb-3">
                                        <div class="card-item__side -front">
                                            <div class="card-item__focus" id="cardFocus"></div>
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
                                                        <div class="card-item__name" id="cardHolderName">FULL NAME</div>
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
                                    @include('partials.include.user_detail_form')
                                    @include('partials.include.user_card_detail')

                                    <div class="form-field text-center">
                                        <p class="form-message"></p>
                                    </div>
                                    <div class="check-out-btn text-center mgt-10">
                                        <button type="submit"
                                            class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>Proceed
                                                to Checkout</span>
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
