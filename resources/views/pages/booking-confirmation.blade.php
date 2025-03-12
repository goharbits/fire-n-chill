<x-app-layout>
    <!-- Page container -->
    <div class="page-container">
        <!-- Payment Method  -->
        <div class="plan-confirmation mgb-100">
            <div class="container">

                @auth
                    <div class="page-title text-center mgb-50">
                        <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">{{ __('YOU’ve BOOKED A SESSION') }}</h1>
                    </div>
                    @include('partials.response')
                    <div class="plan-confirmation-view text-center mgb-50">
                        <img class="mgb-25" src="{{ $clientVitality->image_path }}" alt="">
                        <p class="fs-20 fw-300 color-midnight font-altivo lh-14">Progress towards PEAK VITALITY: {{ $clientVitality->total_vitality_achieved }}%<br> <strong>Peak Vitality</strong></p>
                    </div>

                    <div class="sesion-progress mgb-60">
                        <div class="session-copy d-flex align-items-center justify-content-between">
                            <p class="color-midnight fw-400 lh-11">{{ $clientVitality->total_session_booked }}
                                {{ __('sessions') }}</p>
                            <p class="color-midnight fw-600 lh-11">{{ $clientVitality->total_vitality_achieved }}%</p>
                        </div>
                        <div class="progress-bar bg-sky-blue" style="width: 100%">
                            <span class="bg-orange"
                                style="width: {{ Number::percentage($clientVitality->total_vitality_achieved) }}"></span>
                        </div>
                    </div>

                    <div class="learn-more-planc text-center">
                        <div class="btn-wrap">
                            <a class="btn upgrade-plan-btn fw-700 ssm border-2x-blue color-midnight text-center border-radius-50 lh-50 text-uppercase mgb-40" href="{{ route('client.show.rewards') }}"><span>{{ __('LEARN MORE') }}</span></a>
                        </div>
                    </div>
                @else
                    <div class="page-title text-center mgb-50">
                        <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                            {{ __('Your session is booked') }}
                        </h1>
                        <div class="plan-confirmation-view text-center mgb-50">
                            <p class="fs-20 fw-300 color-midnight font-altivo lh-14">
                                We'll see you soon in Middletown! <br>
                                Navigate to 12951 Shelbyville Road, Louisville, KY 40243 at your appointment time. See you there!<br>
                                A confirmation of your booking has been sent to your email. <br>To make the most of your experience, consider creating an account with us.
                            </p>
                        </div>
                        <div class="btn-wrap text-center">
                            <a class="btn ssm bg-orange color-white text-center border-radius-50 lh-50"
                                href="{{ route('register') }}">
                                <span class="fs-20 color-white fw-600 text-uppercase">{{ __('Create an account') }}</span>
                            </a>
                        </div>
                    </div>
                @endauth

            </div>
        </div>
        <!-- Payment Method end  -->

    </div> <!-- EndPage container -->

    @section('scripts')
        <script type="module"></script>
    @endsection

</x-app-layout>
