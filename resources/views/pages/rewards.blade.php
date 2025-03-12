<x-app-layout>
    <!-- Page container -->
    <div class="page-container">
        <!-- Loyalty Block  -->
        <div class="rewards-sction mgb-80">
            <div class="container">
                <div class="page-title text-center mgb-50">
                    <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                        Loyalty
                    </h1>
                    <p class="fs-20 fw-300 color-midnight font-altivo lh-14">Your goal is to reach <strong>Peak Vitality</strong></p>
                    <p class="fs-20 fw-300 color-midnight font-altivo lh-14">{{ __("Complete 2 sessions per week for 12 weeks to achieve this goal and unlock rewards at each stage:") }}</p>
                </div>
            </div>
            {{-- <div class="container-lg">
                <div class="levels-slider skeleton-slider d-flex mgb-100" id="loyality-levels-slider"
                    data-route-url="{{ route('guest.get.loyality', ['guest' => 'true']) }}">
                    @for ($i = 0; $i < 4; $i++)
                        <!-- 6 skeleton items (adjust as needed) -->
                        <div class="reward">
                            <a href="javascript:void(0)" class="skeleton-box">
                                <div class="reward-img text-center">
                                    <div class="skeleton-img"></div>
                                    <p class="skeleton-button"></p>
                                    <p class="skeleton-text"></p>
                                </div>
                            </a>
                        </div>
                    @endfor
                </div>
            </div> --}}
        </div>
        <!-- Loyalty Block end  -->

        <!-- Rewards -->
        <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="slider-heading mgb-25">
                    <h2 class="fs-65 font-hornsea-fc color-midnight text-uppercase">{{ __('REWARDS') }}</h2>
                </div>
            </div>
            <div class="container-lg">
                <div class="rewards-slider skeleton-slider d-flex mgb-100" id="rewards-slider"
                    data-route-url="{{ route('guest.get.rewards', ['guest' => 'true']) }}"
                    >
                    @for ($i = 0; $i < 4; $i++)
                        <!-- 6 skeleton items (adjust as needed) -->
                        <div class="reward">
                            <a href="javascript:void(0)">
                                <div class="reward-img skeleton-box">
                                    <div class="skeleton-img"></div>
                                </div>
                                <div class="reward-info">
                                    <p class="skeleton-text"></p>
                                    <div class="btn-wrap">
                                        <span class="skeleton-button"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endfor
                </div>

                <div class="btn-wrap text-center">
                    <a class="btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50"
 @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest
                    >
                        <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Book your next session') }}</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Rewards end -->


    </div> <!-- EndPage container -->
    @include('partials.popups.loyality')
    @section('scripts')
        @parent

        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {

                /*
                * Rewards slider
                */
                new window.app.Rewards('#rewards-slider')

                /*
                * Loyalty slider
                */
                new window.app.Loyality('#loyality-levels-slider')

            });
        </script>
    @endsection

</x-app-layout>
