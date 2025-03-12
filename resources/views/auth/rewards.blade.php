<x-app-layout>
    <!-- Page container -->
    <div class="page-container">
        <section class="current-level-section">
            <div class="container">
                <div class="row level-info-row">
                    <div class="col-12 col-md-6 level-info-column">
                        <div class="current-level">
                            <div class="level-info text-center">
                                @if (is_null($current_level))
                                    <h4 class="fs-30 fw-700 color-white font-altivo lh-14 mgb-10">{{ __('LEVEL 0') }}</h4>
                                    <div class="level-image">
                                        <img src="{{ asset('Loyalty-dark/new/week-0.png') }}" alt="Level image" width="" height="">
                                    </div>
                                @else
                                    <h4 class="fs-30 fw-700 color-white font-altivo lh-14 mgb-10">{{ $current_level->title }}</h4>
                                    <div class="level-image">
                                        <img src="{{ $current_level->image }}" alt="Level image" width="218" height="229">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 level-info-column">
                        <div class="earned-reward text-center">
                            @if (!is_null($current_level))
                                <h4 class="fs-34 fw-700 color-midnight font-altivo lh-12 mgb-15">{{ __('Reward') }}</h4>
                                <p class="fs-20 fw-300 color-midnight font-altivo lh-14 mgb-20">{{ $current_level->reward_title }} </p>
                                <img src="{{ $current_level->reward_image }}" alt="Reward image" width="182" height="182">
                            @else
                                <h4 class="fs-34 fw-700 color-midnight font-altivo lh-14">{{ __('No rewards') }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mgt-45 mgb-80">
            <div class="container">
                <div class="mw-900 text-center">
                    <div class="page-title">
                        <h2 class="fs-36 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-altivo mgb-15">
                            {{ __('Peak Vitality Member') }}</h2>
                    </div>
                    <div class="page-description">
                        <p class="fs-18 fw-300 color-midnight font-altivo lh-14">
                            {{ __('Work towards peak vitality to be your best self. Complete 2 sessions per week for 12 weeks to achieve this goal and unlock rewards at each stage:') }}
                        </p>
                        <div class="btn-wrap">
                            <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 mgt-20 @guest open-popup @endguest" @guest data-target="book-a-session" href="javascript:void(0)" @else href="{{ route('client.slots.available') }}" @endguest>
                                <span class="text-uppercase font-altivo fw-700 lh-1">Book Your next Session</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Loyalty Block  -->
        {{-- <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="page-title text-center mgb-50">
                    <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                        Loyalty</h1>
                    @if ($contract)
                        <p class="fs-20 fw-300 color-midnight font-altivo lh-14">Your goal is to reach <strong>{{ __($contract->name) }}</strong></p>
                        <p class="fs-20 fw-300 color-midnight font-altivo lh-14">{{ __("Complete $contract->weekly_sessions sessions per week for 12 weeks to
                            achieve this goal and unlock rewards at each stage:") }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="container-lg">
                <div class="levels-slider skeleton-slider d-flex mgb-100" id="loyality-levels-slider"
                    data-route-url="{{ route('client.get.loyality') }}">
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
            </div>
        </div> --}}
        <!-- Loyalty Block end  -->

        <!-- Rewards -->
        <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="slider-heading mgb-25">
                    <h2 class="fs-65 font-hornsea-fc color-midnight text-uppercase">{{ __('Levels & REWARDS') }}</h2>
                </div>
                <div class="rewards-slider skeleton-slider d-flex mgb-100" id="rewards-slider"
                    data-center-mode="false"
                    data-initial-slide="{{ $current_level ? ($current_level->level-1) : 0 }}"
                    >

                    @foreach ($rewards as $reward)
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="{{ $reward->image }}" alt="Rewards" width="239" height="239">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">{{ $reward->description }}</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">{{ $reward->title }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                        
                    {{-- @for ($i = 0; $i < 12; $i++)
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
                    @endfor --}}
                </div>

                {{-- <div class="btn-wrap text-center">
                    <a class="btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50" href="{{ route('client.slots.available') }}">
                        <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Book your next session') }}</span>
                    </a>
                </div> --}}
            </div>
        </div>
        <!-- Rewards end -->


    </div> <!-- EndPage container -->

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
