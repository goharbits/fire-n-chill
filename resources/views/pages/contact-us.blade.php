<x-app-layout>
    <div class="page-container">
        <div class="container position-relative mgb-40">
            <div class="popup-title text-center mgb-30">
                <h2 class="fw-700 font-hornsea-fc color-black fs-80 lh-11">{{ __('Contact us') }}</h2>
            </div>
            @include('partials.response')
            <div class="popup-holder">
                <div class="poup-form createNewAccount">
                    <form method="POST" action="{{ route('post.contactus') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="contactus">
                        <div class="field-block">
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                autocomplete="name" placeholder="{{ __('Name') }}">
                        </div>
                        <div class="field-block">
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="{{ __('Email') }}">
                        </div>
                        <div class="field-block">
                            <input type="text" name="subject" value="{{ old('subject') }}" required autofocus
                                autocomplete="subject" placeholder="{{ __('Subject') }}">
                        </div>
                        <div class="field-block">
                            <textarea rows="4" cols="50" name="message" required autocomplete="message"
                                placeholder="{{ __('Message') }}">{{ old('message') }}</textarea>
                        </div>
                        <div class="text-webkit-center mb-4 ">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button
                                class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn"
                                type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Area -->
        <div class="section contact-section overflow-hidden mgb-80 mgt-80">
            <div class="container">
                <div class="row d-flex align-items-center contact-blocks">
                    <div class="col-6">
                        <div class="map-block pdr-60">
                            <img class="mw-100" src="{{ Vite::image('map2.svg') }}" alt="Google Map">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contact-info pdl-40">
                            <div class="section-heading overflow-hidden mgb-60">
                                <h3 class="fs-38 fw-700 color-midnight font-altivo fw-700 ls-192-minus text-uppercase">
                                    we are here in middletown </h3>
                            </div>
                            <div class="contact-type d-flex align-items-center">
                                <img class="mgr-15" src="{{ Vite::image('icon-address-blue.svg') }}" alt="address">
                                <a class="fs-20 color-midnight ls-04px-minus fw-300 lh-1 pdt-5"
                                    href="https://www.google.com/maps/dir//12951+Shelbyville+Rd+Suite+112,+Middletown,+KY+40243/@38.2441756,-85.5933464,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x886999f5cae213e7:0xa53543a7a2c46010!2m2!1d-85.5109454!2d38.2442044?hl=en&authuser=0&entry=ttu&g_ep=EgoyMDI1MDExNS4wIKXMDSoASAFQAw%3D%3D"
                                    target="_blank">
                                    12951 Shelbyville Rd. Ste 112 Louisville, KY 40243
                                </a>
                            </div>
                            <div class="contact-type d-flex align-items-center">
                                <img class="mgr-15" src="{{ Vite::image('icon-call-blue.svg') }}" alt="address">
                                <span class="fs-20 color-midnight ls-04px-minus fw-300 lh-1 pdt-5">
                                    (502) 333-0906
                                </span>
                            </div>
                            <div class="contact-type d-flex align-items-center">
                                <img class="mgr-15" src="{{ Vite::image('icon-msg-blue.svg') }}" alt="address">
                                <a href="mailto:{{ config('app.support_email') }}"
                                    class="fs-20 color-midnight ls-04px-minus fw-300 lh-1 pdt-5" target="_blank">
                                    {{ config('app.support_email') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact Area end -->

    </div>

    @section('scripts')
        @parent
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection

</x-app-layout>
