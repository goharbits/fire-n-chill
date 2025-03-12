<x-app-layout>
    <!-- Page container -->
    <div class="page-container">

        <!-- Payment Method  -->
        <div class="pick-plan-section mgb-60">
            <div class="container">

                <form action="{{ route($guest ? 'guest.checkout' : 'client.checkout') }}" method="GET">
                    @csrf
                    @if ($guest)
                        <input type="hidden" name="guest" value="true">
                    @endif

                    @if (request()->has('StartDateTime'))
                        <input type="hidden" name="StartDateTime" value="{{ request()->StartDateTime }}">
                    @endif

                    <div class="page-title text-center mgb-50">
                        <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                            SELECT PACKAGE</h1>
                        <p class="fs-20 fw-300 lh-13 color-midnight">Choose a monthly subscription plan that works for
                            you.</p>
                    </div>

                    @include('partials.response')

                    <div class="row plan-cols mgb-60">
                        @if ($client_services->free_service)
                            <div class="col-12 plan-col position-relative mb-3">
                                <input class="plan-selector" data-plan-type="service" type="radio"
                                    value="{{ $client_services->free_service->service_id }}" name="service_id"
                                    id="service--{{ $client_services->free_service->service_id }}">
                                <div class="single-plan-picker single-plan-picker-free-session">
                                    <div class="plan-top d-flex align-items-center mgb-25">
                                        <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}"
                                            alt="Fire and Chill">
                                        <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">
                                            {{ $client_services->free_service->name }}</h3>
                                        <div
                                            class="plan-price-amount d-flex h-100 align-items-end free-session-type hidden-sm">
                                            <span class="color-midnight fs-40 fw-400 font-altivo"><i
                                                    class="fonts-arial fst-normal"></i>Free</span>
                                        </div>
                                    </div>

                                    <div class="plan-details-pricing d-flex justify-content-between">
                                        <div class="plan-ftrs">
                                            <div class="feature d-flex">
                                                <div class="feature-icon mgr-10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="16" viewBox="0 0 20 16" fill="none">
                                                        <path
                                                            d="M3.69901 6.08391L3.34722 5.71002L2.98274 6.07155L1.64788 7.39565L1.30037 7.74036L1.63761 8.09512L7.57836 14.3445L7.94074 14.7257L8.30313 14.3445L18.3624 3.76284L18.7075 3.39981L18.3439 3.05534L16.8465 1.637L16.4779 1.28783L16.1344 1.66177L7.93633 10.5874L3.69901 6.08391Z"
                                                            fill="white" stroke="white" />
                                                    </svg>
                                                </div>
                                                <div class="feature-text">
                                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-white">
                                                        {{ @$client_services->free_service->description_part }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="plan-price-amount d-flex h-100 align-items-end hidden-md">
                                            <span class="color-midnight fs-40 fw-400 font-altivo">Free</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isset($client_services->contracts) && count($client_services->contracts))
                            @foreach ($client_services->contracts as $i => $contract)
                                <?php
                                $description_parts = explode(',', $contract->description);
                                ?>

                                <div class="col-4 plan-col position-relative mb-4">
                                    <input class="plan-selector" type="radio" data-plan-type="contract"
                                        value="{{ $contract->id }}" name="contractId" id="contract--{{ $contract->id }}"
                                        @if ($i == 0) checked @endif>
                                    <div class="single-plan-picker">
                                        <div class="plan-top d-flex align-items-center mgb-25">
                                            <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}"
                                                alt="Fire and Chill">
                                            <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">
                                                {{ $contract->name }}</h3>
                                        </div>
                                        <div class="plan-details-pricing d-flex justify-content-between">
                                            <div class="plan-ftrs">

                                                @foreach ($description_parts as $i => $description_part)
                                                    <div class="feature d-flex{{ $i == 0 ? ' mgb-10' : '' }}">
                                                        <div class="feature-icon mgr-10">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="16" viewBox="0 0 20 16" fill="none">
                                                                <path
                                                                    d="M3.69901 6.08391L3.34722 5.71002L2.98274 6.07155L1.64788 7.39565L1.30037 7.74036L1.63761 8.09512L7.57836 14.3445L7.94074 14.7257L8.30313 14.3445L18.3624 3.76284L18.7075 3.39981L18.3439 3.05534L16.8465 1.637L16.4779 1.28783L16.1344 1.66177L7.93633 10.5874L3.69901 6.08391Z"
                                                                    fill="white" stroke="white" />
                                                            </svg>
                                                        </div>
                                                        <div class="feature-text">
                                                            <p class="fs-16 fw-400 lh-11 ls-04-minus color-white">
                                                                {{ $description_part }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="plan-price-amount d-flex h-100 align-items-end">
                                                <span class="color-midnight fs-40 fw-400 font-altivo"><i
                                                        class="fonts-arial fst-normal">$</i>{{ $contract->price }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if ($client_services->service)
                            <div class="col-4 plan-col position-relative">
                                <input class="plan-selector" data-plan-type="service" type="radio"
                                    value="{{ $client_services->service->service_id }}" name="service_id"
                                    id="service--{{ $client_services->service->service_id }}">
                                <div class="single-plan-picker">
                                    <div class="plan-top d-flex align-items-center mgb-25">
                                        <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}"
                                            alt="Fire and Chill">
                                        <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">
                                            {{ $client_services->service->name }}</h3>
                                    </div>
                                    <div class="plan-details-pricing d-flex justify-content-between">
                                        <div class="plan-ftrs">
                                            <div class="feature d-flex">
                                                <div class="feature-icon mgr-10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="16" viewBox="0 0 20 16" fill="none">
                                                        <path
                                                            d="M3.69901 6.08391L3.34722 5.71002L2.98274 6.07155L1.64788 7.39565L1.30037 7.74036L1.63761 8.09512L7.57836 14.3445L7.94074 14.7257L8.30313 14.3445L18.3624 3.76284L18.7075 3.39981L18.3439 3.05534L16.8465 1.637L16.4779 1.28783L16.1344 1.66177L7.93633 10.5874L3.69901 6.08391Z"
                                                            fill="white" stroke="white" />
                                                    </svg>
                                                </div>
                                                <div class="feature-text">
                                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-white">
                                                        {{ @$client_services->free_service->description_part }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="plan-price-amount d-flex h-100 align-items-end">
                                            <span class="color-midnight fs-40 fw-400 font-altivo"><i
                                                    class="fonts-arial fst-normal">$</i>{{ $client_services->service->price }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="check-out-btn text-center mgt-10">
                        <button type="submit"
                            class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>Proceed
                                to Checkout</span></button>
                        <div class="mgt-5">
                            <?php
                            $route = $guest ? 'guest.slots.available' : 'client.slots.available';
                            $params = $guest ? ['guest' => 'true'] : [];
                            ?>
                            <a class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline text-uppercase"
                                href="{{ route($route, $params) }}">Back
                                to Date and Time</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <!-- Payment Method end  -->
    </div>


    @section('scripts')
        @parent
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                const planSelectors = document.querySelectorAll('.plan-selector');
                const servicePlanSelectors = document.querySelectorAll('.plan-selector[data-plan-type="service"]');
                const contractPlanSelectors = document.querySelectorAll('.plan-selector[data-plan-type="contract"]');
                const guest = '{{ $guest }}' === 'true';

                planSelectors.forEach((planSelector) => {
                    console.log('here')
                    planSelector.addEventListener('change', function() {
                        const productType = this.getAttribute('data-plan-type');
                        if (productType === 'contract') {
                            servicePlanSelectors.forEach((servicePlanSelector) => {
                                servicePlanSelector.checked = false;
                            });
                        } else {
                            contractPlanSelectors.forEach((contractPlanSelector) => {
                                contractPlanSelector.checked = false;
                            });
                        }
                    });
                });
            });
        </script>
    @endsection

</x-app-layout>
