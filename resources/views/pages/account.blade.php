<x-app-layout>

    <div class="page-container">
        @include('partials.response')
        <!-- Your Books -->
        <div class="your-booking-section mgb-160">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="package-details bg-midnight">
                            <div class="package-info d-flex align-items-center">
                                <div class="package-image">
                                    <img src="assets/images/ac-pack-logo.svg" alt="User">
                                </div>
                                <div class="package-in">
                                    <p class="fs-20 lh-12 color-white fw-400">You are 25<i class="fst-normal fonts-arial">%</i> away from reaching <span class="fw-600">Peak Vitality</span></p>
                                    <a class="btn open-popup-btn border-white text-uppercase fs-16 moreInfo" href="javascript:void(0)"><span class="color-white">MORE INFO</span></a>
                                </div>
                            </div>
                            <div class="sesion-progress">
                                <div class="session-copy d-flex align-items-center justify-content-between">
                                    <p class="color-white fw-400 lh-11">9 sessions</p>
                                    <p class="color-white fw-600 lh-11">75<i class="fonts-arial">%</i>  vitality</p>
                                </div>
                                <div class="progress-bar bg-sky-blue" style="width: 100%">
                                    <span class="bg-orange" style="width: 50%"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <h2 class="fs-36 fw-700 lh-12 color-midnight font-hornsea-fc text-uppercase mg-0">YOUR bookings</h2>
                            <div class="view-history text-end">
                                <p class="fs-20 fw-400 color-midnight">View History</p>
                            </div>
                            <div class="calender-histotry">
                                <div class="calener-row d-flex align-items-center justify-content-between">
                                    <div class="calendar-lt d-flex align-items-center">
                                        <div class="c-day position-relative mgr-10">
                                            <img src="assets/images/days-bg.png" alt="User">
                                            <div class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                                <p>SEP <span>10</span></p>
                                            </div>
                                        </div>
                                        <div class="c-time">
                                            <p class="color-midnight fs-20 lh-11 fw-300">Sep. 10, 5:00 to 5:30 PM</p>
                                        </div>
                                    </div>
                                    <div class="calendar-rt">
                                        <a class="btn border-2x-blue color-midnight text-center border-radius-50 lh-50" href="javascript:void(0)"><span class="fs-16 color-midnight fw-600 text-uppercase">Edit</span></a>
                                    </div>
                                </div>
                                <div class="calener-row d-flex align-items-center justify-content-between">
                                    <div class="calendar-lt d-flex align-items-center">
                                        <div class="c-day position-relative mgr-10">
                                            <img src="assets/images/days-bg.png" alt="User">
                                            <div class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                                <p>SEP <span>10</span></p>
                                            </div>
                                        </div>
                                        <div class="c-time">
                                            <p class="color-midnight fs-20 lh-11 fw-300">Sep. 10, 5:00 to 5:30 PM</p>
                                        </div>
                                    </div>
                                    <div class="calendar-rt">
                                        <a class="btn border-2x-blue color-midnight text-center border-radius-50 lh-50" href="javascript:void(0)"><span class="fs-16 color-midnight fw-600 text-uppercase">Edit</span></a>
                                    </div>
                                </div>
                                <div class="calener-row d-flex align-items-center justify-content-between">
                                    <div class="calendar-lt d-flex align-items-center">
                                        <div class="c-day position-relative mgr-10">
                                            <img src="assets/images/days-bg.png" alt="User">
                                            <div class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                                <p>SEP <span>10</span></p>
                                            </div>
                                        </div>
                                        <div class="c-time">
                                            <p class="color-midnight fs-20 lh-11 fw-300">Sep. 10, 5:00 to 5:30 PM</p>
                                        </div>
                                    </div>
                                    <div class="calendar-rt">
                                        <a class="btn border-2x-blue color-midnight text-center border-radius-50 lh-50" href="javascript:void(0)"><span class="fs-16 color-midnight fw-600 text-uppercase">Edit</span></a>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="btn-wrap overflow-hidden d-flex">
                                <a class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50" href="javascript:void(0)">
                                    <span class="text-uppercase font-altivo fw-700 lh-1">Book Your First  Session</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Your Books end -->


        <!-- Rewards -->
        <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="slider-heading mgb-25">
                    <h2 class="fs-65 font-hornsea-fc color-midnight text-uppercase">REWARDS</h2>
                </div>
                <div class="rewards-slider d-flex mgb-100">
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="assets/images/rewards-1.png" alt="Rewards">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">50<span class="fonts-arial">%</span> discount on Shady Rays</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">STAGE 3</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="assets/images/rewards-2.png" alt="Rewards">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">50<span class="fonts-arial">%</span> discount on Shady Rays</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">STAGE 3</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="assets/images/rewards-3.png" alt="Rewards">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">50<span class="fonts-arial">%</span> discount on Shady Rays</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">STAGE 3</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="assets/images/rewards-4.png" alt="Rewards">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">50<span class="fonts-arial">%</span> discount on Shady Rays</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">STAGE 3</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="reward">
                        <a href="javascript:void(0)">
                            <div class="reward-img">
                                <img class="mw-100" src="assets/images/rewards-1.png" alt="Rewards">
                            </div>
                            <div class="reward-info">
                                <p class="fs-20 fw-300 lh-12 color-blue">50<span class="fonts-arial">%</span> discount on Shady Rays</p>
                                <div class="btn-wrap">
                                    <span class="s border-radius-50 bg-light-blue color-midnight fw-700 fs-20 lh-12 text-uppercase">STAGE 3</span>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <div class="view-more-rewards text-center">
                    <a class="book-session btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50 @guest open-popup @endguest" @guest data-target="book-a-session" href="javascript:void(0)" @else href="{{ route('client.slots.available') }}" @endguest><span class="fs-16 color-midnight fw-600 text-uppercase">view all rewards</span></a>
                </div>
            </div>
        </div>
        <!-- Rewards end -->


    </div>

@endsection

@section('scripts')
    @parent
@endsection
</x-app-layout>