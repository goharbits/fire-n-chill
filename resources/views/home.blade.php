<x-app-layout>

    <!-- Hero Section -->
    <div class="section hero-section overflow-hidden">
        <div class="full-container d-flex">
            <div class="col-6 bg-blue col1">
                <div class="hero-copies d-flex flex-column pdt-60">
                    <div class="hero-copy mgb-10">
                        <h2 class="text-uppercase color-white font-altivo mgb-35">
                            <span class="color-orange">Sauna</span> and
                            <span class="color-blue-light">Cold Plunge</span>
                            Wellness for
                            everyone
                        </h2>
                    </div>
                    <div class="hero-btn mgb-15 text-center">
                        <div>
                            <a class="btn book-session bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 mgb-20 @guest  @endguest"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest>
                                <span class="text-uppercase font-altivo fw-700 lh-1">Book Your First Session</span>
                            </a>
                        </div>
                        <div>
                            <a href="#our-plans" data-scroll-target="#our-plans"
                                class="color-white herocta text-decoration-underline fw-700 ls-1px">SEE ALL PLANS</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 position-relative col2">
                <div class="hero-video video-block">
                    <img class="fit-cover" src="{{ Vite::image('main-hero-banner.jpeg') }}" alt="Main Hero Banner">
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section -->

    <!-- Benefits-col -->
    <div class="section benefits-col-section overflow-hidden mgb-100" id="experience">
        <div class="container-full">
            <div class="row flex-nowrap hide-mob">
                <div class="col-4">
                    <div class="benefit-block position-relative">
                        <img class="d-block mw-100" src="{{ Vite::image('more-energy.jpeg') }}" alt="More Energy">
                        <div class="overly top-0 left-0 d-flex justify-content-center align-items-center h-100 w-100">
                            <h4
                                class="text-center color-white fs-80 lh-09 fw-700 font-hornsea-fc ls-320-minus text-uppercase">
                                More<br> Energy</h4>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="benefit-block position-relative">
                        <img class="d-block mw-100" src="{{ Vite::image('less-stress.jpeg') }}" alt="Less Stress">
                        <div class="overly top-0 left-0 d-flex justify-content-center align-items-center h-100 w-100">
                            <h4
                                class="text-center color-white fs-80 lh-09 fw-700 font-hornsea-fc ls-320-minus text-uppercase">
                                Less<br> Stress</h4>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="benefit-block position-relative">
                        <img class="d-block mw-100" src="{{ Vite::image('better-mood.jpeg') }}" alt="Better Mood">
                        <div class="overly top-0 left-0 d-flex justify-content-center align-items-center h-100 w-100">
                            <h4
                                class="text-center color-white fs-80 lh-09 fw-700 font-hornsea-fc ls-320-minus text-uppercase">
                                Better<br> Mood</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="show-mob">
                <div class="benefit-block-gif">
                    <img class="d-block mw-100" src="{{ Vite::image('more-energy.gif') }}" alt="More Energy">
                </div>
            </div>
        </div>
    </div>
    <!-- Benefits-col end -->

    <!-- Therapy time -->
    <div class="section therapy-time-seciton bg-white mgb-100" id="services">
        <div class="container">
            <div class="row">
                <div class="col-5 therapy-col1">
                    <div class="therapy-promo">
                        <img class="d-block mw-100 mgb-25 hide-mob" src="{{ Vite::image('min-30-copy-desk.svg') }}"
                            alt="30 min">
                        <h2 class="show-mob fw-700 fs-42 ls-04-minus color-midnight text-center font-altivo mgb-20">30
                            MINUTES<br> IN AND OUT</h2>
                        <p class="fs-20 fw-300 lh-15 ls-04-minus color-midnight mgb-35 hide-mob">We believe that Sauna
                            and Cold Plunge therapy is the single best and most comprehensive thing that you can
                            incorporate into your life to holistically improve all aspects of your health. And it’s
                            quite simple...</p>
                        <div class="btn-wrap hide-mob">
                            <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 @guest  @endguest"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                                    Your First Session</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-7 therapy-col2">
                    <div class="therapy-sub-cols d-flex h-100">
                        <div class="col-6 ts-col1 bg-orange text-center flex-column justify-content-center d-flex">
                            <div class="therapy-minutes text-center d-block">
                                <img width="100px" class="d-block mx-auto mgb-15"
                                    src="{{ Vite::image('private-20min.svg') }}" alt="30 min">
                            </div>
                            <h3 class="fs-24 fs-38 fw-700 lh-09 color-white mgb-15 font-altivo">PRIVATE<br> SAUNA</h3>
                            <p class="fs-22 lh-16 color-white fw-500 fonts-arial hide-mob mgb-10">+ Red Light Therapy!
                            </p>
                            <p class="fs-16 lh-16 color-white fw-500 fonts-arial show-mob mgb-10">+ Red Light Therapy!
                            </p>
                            <p class="fs-20 lh-16 color-white fw-400 sauna-description">Warm peaceful escape with your
                                favorite music</p>
                        </div>
                        <div class="col-6 ts-col2 bg-light-blue text-center flex-column justify-content-center d-flex">
                            <div class="therapy-minutes text-center d-block">
                                <img width="118px" class="d-block mx-auto mgb-15"
                                    src="{{ Vite::image('plunge-5min.svg') }}" alt="2-5 min">
                            </div>
                            <h3 class="fs-24 fs-38 fw-700 lh-09 color-midnight font-altivo mgb-15">COLD<br> PLUNGE</h3>
                            <span
                                class="fs-22 lh-16 color-midnight fw-300 fonts-arial hide-mob mgb-15">(optional)</span>
                            <span
                                class="fs-18 lh-16 color-midnight fw-300 fonts-arial show-mob mgb-15">(optional)</span>
                            <p class="fs-20 lh-16 color-midnight fw-400 sauna-description">A cool 55 degrees. It’s not
                                that bad - we promise :) </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Therapy time end -->

    <!-- Therapy Plunge -->
    <div class="section therapy-plunge-section bg-white mgb-80 overflow-hidden">
        <div class="therapy-plange-head mgb-60">
            <div class="row">
                <div class="col-6 px-0">
                    <div class="hot-sauna-model">
                        <img class="mx-auto" src="{{ Vite::image('hot-model-sauna-desk.jpeg') }}" alt="Hot Sauna">
                    </div>
                </div>
                <div class="col-6 bg-orange">
                    <div class="hot-sauna-copy pdl-30 pdr-30">
                        <picture class="mx-auto mw-100">
                            <source media="(min-width:740px)" srcset="{{ Vite::image('hot-plung-bw.svg') }}">
                            <source media="(min-width:200px)" srcset="{{ Vite::image('hot-plung-mob.svg') }}">
                            <img class="mx-auto mw-100" src="{{ Vite::image('hot-plung-bw.svg') }}" alt="Hot Sauna">
                        </picture>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-flex plunge-promo">
            <div class="col-6">
                <div class="short-info hide-mob" data-full-details="hot-sauna">
                    <p class="fs-20 fw-300 lh-17 ls-04-minus color-midnight mgb-15">Our full spectrum infrared saunas
                        feature roomy wooden cabins, mood lighting, redlight skin therapy, and custom surround sound for
                        music, audiobooks or meditations; all within your private escape that will replace your stresses
                        with endorphins and tropical vacation vibes.</p>
                    <p class="fs-20 fw-300 lh-17 ls-04-minus color-midnight mgb-15">You know what’s cool too?  A 20
                        minute sauna session mimics many of the effects of a 1-2 mile jog or other moderate cardio
                        workout just by sitting down and listening to music in the heat.</p>
                    <p class="fs-20 fw-300 lh-17 ls-04-minus color-midnight mgb-15">20 minutes at 155 degrees is our
                        formula for success. You’ll feel the heat & love how you feel.</p>
                    <div class="btn-wrap pdt-20 show-mob">
                        <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 @guest  @endguest"
                            @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                                Your First Session</span></a>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="plunge-list">
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-hot-orange.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Improve Cardiovascular health</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-hot-orange.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Increased mood via endorphin release
                        </p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-hot-orange.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Detoxification throughout the body</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-hot-orange.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Promotes weight loss and increased
                            metabolism</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-hot-orange.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Healing from injuries and muscle
                            recovery</p>
                    </div>

                    <div class="mob-short-desc">
                        <a class="fs-14 fw-700 color-midnight text-uppercase show-mob" href="javascript:void(0)"
                            data-read-more-link data-target="hot-sauna">
                            <span class="mgr-5 text-decoration-underline">learn more</span>
                            <img src="{{ Vite::image('arrow-angle-blue.svg') }}" alt="arrow">
                        </a>
                        <div class="btn-wrap pdt-20">
                            <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 @guest  @endguest"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                                    Your First Session</span></a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="section therapy-plunge-section bg-white mgb-80 overflow-hidden">
        <div class="therapy-plange-head mgb-60">
            <div class="row flex-row-reverse">
                <div class="col-6 px-0">
                    <div class="hot-sauna-model">
                        <img class="mx-auto" src="{{ Vite::image('cold-model-desk.jpeg') }}" alt="Cold Sauna">
                    </div>
                </div>
                <div class="col-6 bg-midnight px-0">
                    <div class="cold-plunge-copy pdl-30 pdr-30">
                        <img class="mx-auto mw-100" src="{{ Vite::image('cold-plug-lbw.svg') }}" alt="Cold Sauna">
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-flex plunge-promo">
            <div class="col-6">
                <div class="short-info hide-mob" data-full-details="cold-plunge">
                    <p class="fs-20 fw-300 lh-17 ls-04-minus color-midnight mgb-15">Channel your inner polar bear in a
                        challenging but doable 55 degrees for 2-5 minutes. Our Cold Plunge room features premium,
                        wood-adorned tubs in an icelandic setting made for photo opps and personal wellness alike.
                        Punch stress and low energy in the face with an invigorating, confidence inducing cold water
                        experience.</p>
                    <div class="btn-wrap pdt-20 show-mob">
                        <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 @guest  @endguest"
                            @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                                Your First Session</span></a>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="plunge-list">
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Boosts immunity</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Dopamine spike increases energy and
                            mood</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Improves metabolism</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Stress and mental resilience via
                            releasing beneficial cold shock proteins</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Helps the body burn calories and
                            maintain healthy weight</p>
                    </div>
                    <div class="list-item d-flex mgb-20 align-items-start">
                        <img src="{{ Vite::image('icon-col-p-blue.svg') }}" alt="Hot Sauna">
                        <p class="fs-22 fw-500 lh-12 ls-04-minus color-midnight">Supports Muscle recovery and repair
                        </p>
                    </div>
                    <div class="mob-short-desc">
                        <a class="fs-14 fw-700 color-midnight text-uppercase show-mob" href="javascript:void(0)"
                            data-read-more-link data-target="cold-plunge">
                            <span class="mgr-5 text-decoration-underline">learn more</span>
                            <img src="{{ Vite::image('arrow-angle-blue.svg') }}" alt="arrow">
                        </a>
                        <div class="btn-wrap pdt-20">
                            <a class="btn book-session sm bg-orange color-white text-center border-radius-50 lh-50 @guest  @endguest"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                                    Your First Session</span></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Therapy Plunge end -->

    <!-- Our Plans -->
    <div class="section plans-section bg-light-blue2 overflow-hidden pdt-100" id="our-plans">
        <div class="container">
            <div class="section-heading text-center overflow-hidden mgb-80">
                <h2 class="fs-48 fw-700 color-midnight font-altivo fw-700 ls-192-minus text-uppercase mgb-15">Our Plans
                </h2>
            </div>
            <div class="row mgb-25 col-2-pricing-plan">
                <div class="col-6">
                    <div class="plan-block border-radius-12 active">
                        <div class="plan-header d-flex align-items-center gap-5 mgb-40">
                            <div class="plan-icon">
                                <img class="default-img" src="{{ Vite::image('icon-fire-chill-blue.svg') }}"
                                    alt="Fire and Chill">
                                <img class="on-hover" src="{{ Vite::image('icon-fire-chill-orange.svg') }}"
                                    alt="Fire and Chill">
                            </div>
                            <div class="tag-wrap d-flex">
                                <span
                                    class="text-uppercase lh-1 color-midnight fw-700 bg-light-blue tag border-radius-50 d-flex align-items-center justify-content-center font-altivo">Most
                                    Popular</span>
                            </div>
                        </div>
                        <div class="plan-body d-flex align-items-center justify-content-between mgb-40">
                            <h3
                                class="fs-24 fw-700 color-midnight text-uppercase lh-1 font-altivo fs-24 fw-700 ls-72-minus">
                                Peak Vitality<br> Plan</h3>
                            <div class="plan-price d-flex align-items-end color-midnight fs-48 font-altivo fw-300">
                                <span class="price">$18.50</span> <sub class="plan-term fs-16 fw-400"><i
                                        class="fonts-arial fst-normal">/</i> session</sub>
                            </div>
                        </div>
                        <div class="plan-starting mgb-40">
                            <a class="btn book-session bg-orange color-white text-center border-radius-50 lh-50 d-block"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Get
                                    started</span></a>
                        </div>
                        <div class="plan-features d-flex">
                            <div class="feature d-flex mgr-60">
                                <div class="feature-icon mgr-10">
                                    <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                </div>
                                <div class="feature-text">
                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight">2 Sessions <i
                                            class="fonts-arial fst-normal">/</i> week</p>
                                </div>
                            </div>
                            <div class="feature d-flex">
                                <div class="feature-icon mgr-10">
                                    <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                </div>
                                <div class="feature-text">
                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight">$148 per month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="plan-block border-radius-12">
                        <div class="plan-header d-flex align-items-center justify-content-between mgb-40">
                            <div class="plan-icon">
                                <img class="default-img" src="{{ Vite::image('icon-fire-chill-blue.svg') }}"
                                    alt="Fire and Chill">
                                <img class="on-hover" src="{{ Vite::image('icon-fire-chill-orange.svg') }}"
                                    alt="Fire and Chill">
                            </div>
                        </div>
                        <div class="plan-body d-flex align-items-center justify-content-between mgb-40">
                            <h3
                                class="fs-24 fw-700 color-midnight text-uppercase lh-1 font-altivo fs-24 fw-700 ls-72-minus">
                                Super Vitality<br> Plan</h3>
                            <div
                                class="plan-price d-flex align-items-baseline color-midnight fs-48 font-altivo fw-300">
                                <sub class="fs-16 fw-400">less than </sub><span class="price">$10</span> <sub
                                    class="plan-term fs-16 fw-400"><i class="fonts-arial fst-normal">/</i>
                                    session</sub>
                            </div>
                        </div>
                        <div class="plan-starting mgb-40">
                            <a class="btn book-session bg-orange-hover text-center border-radius-50 lh-50 d-block @guest  @endguest"
                                @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Get
                                    started</span></a>
                        </div>
                        <div class="plan-features d-flex">
                            <div class="feature d-flex mgr-60">
                                <div class="feature-icon mgr-10">
                                    <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                </div>
                                <div class="feature-text">
                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight"><i
                                            class="fonts-arial fst-normal">Unlimited</i> Sessions <i
                                            class="fonts-arial fst-normal">/</i> week</p>
                                </div>
                            </div>
                            <div class="feature d-flex">
                                <div class="feature-icon mgr-10">
                                    <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                </div>
                                <div class="feature-text">
                                    <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight">$198 per month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mgb-25 col-1-pricing-plan">
                <div class="col-12">
                    <div class="plan-block border-radius-12">
                        <div class="row d-flex col-1-pricing-top">
                            <div class="col-6">
                                <div class="plan-header d-flex align-items-center mgb-40">
                                    <div class="plan-icon mgr-25">
                                        <img class="default-img" src="{{ Vite::image('icon-fire-chill-blue.svg') }}"
                                            alt="Fire and Chill">
                                        <img class="on-hover" src="{{ Vite::image('icon-fire-chill-orange.svg') }}"
                                            alt="Fire and Chill">
                                    </div>
                                    <h3
                                        class="fs-24 fw-700 color-midnight text-uppercase lh-1 font-altivo fs-24 fw-700 ls-72-minus mgr-40">
                                        30 MIN WELLNESS<br> SESSION</h3>
                                    <div
                                        class="plan-price d-flex align-items-end color-midnight fs-48 font-altivo fw-300">
                                        <span class="price">$32</span> <sub class="plan-term fs-16 fw-400"><i
                                                class="fonts-arial fst-normal">/</i> session</sub>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="plan-starting mgb-40">
                                    <a class="btn book-session bg-orange-hover text-center border-radius-50 lh-50 d-block @guest  @endguest"
                                        @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}"" @else href="{{ route('client.slots.available') }}" @endguest><span>Get
                                            started</span></a>
                                </div>
                            </div>
                        </div>

                        <div class="row  d-flex col-1-pricing-bottom">
                            <div class="plan-features d-flex justify-content-between">
                                <div class="feature d-flex mgr-60">
                                    <div class="feature-icon mgr-10">
                                        <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                    </div>
                                    <div class="feature-text">
                                        <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight">20 minute Sauna / Red
                                            Light Therapy + (optional) 2-5 minute Cold Plunge</p>
                                    </div>
                                </div>
                                <div class="feature d-flex">
                                    <div class="feature-icon mgr-10">
                                        <img src="{{ Vite::image('icon-check-blue.svg') }}" alt="Check">
                                    </div>
                                    <div class="feature-text">
                                        <p class="fs-16 fw-400 lh-11 ls-04-minus color-midnight">The exact, minimum
                                            amount of time that we believe will give you amazing results</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Plan end -->


    <!-- Full image -->
    <div class="section full-image-section mgb-80 text-center">
        <img class="mw-100" src="{{ Vite::image('full-bleed-photo.jpeg') }}" alt="">
    </div>
    <!-- Full image end -->

    <!-- Rewards Section -->
    <div class="section rewards-section overflow-hidden mgb-80">
        <div class="container show-mob rewards-mobs text-center mgb-30">
            <h2 class="fs-48 fw-700 color-midnight font-altivo fw-700 ls-192-minus text-uppercase mgb-15">UNLOCK
                REWARDS</h2>
            <p class="fs-20 fw-300 lh-15 ls-04-minus color-midnight mgb-15">Work up your way to reach «Peak Vitality»
                level as you do more and more sessions.</p>
            <p class="fs-20 fw-700 lh-15 ls-04-minus color-midnight mgb-15">Peak Vitality unlocks exclusive rewards
                while reaching peak physical wellness.</p>
        </div>
        <div class="container position-relative reward-container">
            <div class="mobile-bg text-end">
                <picture>
                    <source media="(min-width:740px)" srcset="{{ Vite::image('mobile-bg.png') }}">
                    <source media="(min-width:200px)" srcset="{{ Vite::image('mobile-bg-mob.png') }}">
                    <img src="{{ Vite::image('mobile-bg.png') }}" alt="Mobile Background">
                </picture>
            </div>
            <div class="overly w-100 h-100 d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="rewards-text">
                                <h2
                                    class="fs-48 fw-700 color-midnight font-altivo fw-700 ls-192-minus text-uppercase mgb-15 hide-mob">
                                    UNLOCK REWARDS</h2>
                                <p class="fs-20 fw-300 lh-15 ls-04-minus color-midnight mgb-15 hide-mob">Work up your
                                    way to reach «Peak Vitality» level as you do more and more sessions.</p>
                                <p class="fs-20 fw-700 lh-15 ls-04-minus color-midnight mgb-15 hide-mob">Peak Vitality
                                    unlocks exclusive rewards while reaching peak physical wellness.</p>
                                <div class="app-download d-flex pdt-25">
                                    <a href="https://apps.apple.com/us/app/fire-chill/id6615087895"
                                        target="_blank"><img src="{{ Vite::image('app-store.svg') }}"
                                            alt="App Store"></a>
                                    <a href="https://play.google.com/store/apps/details?id=com.app.fireandchill"
                                        target="_blank"><img src="{{ Vite::image('google-play.svg') }}"
                                            alt="Google Play"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rewards Section end -->

    <!-- Contact Area -->
    <div class="section contact-section overflow-hidden mgb-80">
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


    <!-- Social Media Feed Area -->
    <div class="section social-media-section d-block mgb-80">
        <div class="container-full">
            <div class="row social-media-row">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="col-auto social-media-col" tabindex="0">
                        <div class="social-card">
                            <div class="social-head justify-content-between align-items-center pdb-5 pdt-10">
                                <div class="profile-icon-title d-flex align-items-center">
                                    <div class="social-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            viewBox="0 0 32 32" fill="none">
                                            <circle cx="16" cy="16" r="16" fill="#0D243F" />
                                            <path
                                                d="M16.0564 7.95881C16.899 7.95881 17.7416 8.1785 18.4926 8.61329L23.6765 11.6202V20.458L18.4926 23.4649C17.7416 23.8997 16.899 24.1194 16.0564 24.1194C15.2138 24.1194 14.3712 23.8997 13.6201 23.4649L8.44083 20.458V11.6202L13.6247 8.61329C14.3758 8.1785 15.2184 7.95881 16.0564 7.95881ZM16.061 6.73682C14.9894 6.73682 13.9361 7.02058 13.0111 7.55606L7.82719 10.563L7.21813 10.9154V11.6202V20.458V21.1628L7.82719 21.5152L13.0111 24.5221C13.9361 25.0576 14.9894 25.3414 16.061 25.3414C17.1326 25.3414 18.1858 25.0576 19.1109 24.5221L24.2948 21.5152L24.9038 21.1628V20.458V11.6202V10.9154L24.2948 10.563L19.1063 7.55606C18.1858 7.02058 17.1326 6.73682 16.061 6.73682Z"
                                                fill="#FF5B00" />
                                            <path
                                                d="M17.911 11.8352C17.8744 11.6704 17.7599 11.1624 17.0913 10.1967C16.8669 9.87176 16.6197 9.55597 16.354 9.2539L16.0518 8.91064L15.7496 9.2539C15.4885 9.55597 15.2413 9.87176 15.0123 10.1967C14.3437 11.1624 14.2292 11.6659 14.1926 11.8352C14.1422 12.0686 14.1147 12.2105 14.1147 12.4073C14.1147 12.9519 14.3116 13.4691 14.6734 13.8535C15.0398 14.2472 15.5252 14.4623 16.0426 14.4623C16.5601 14.4623 17.0501 14.2472 17.4119 13.8535C17.7737 13.4645 17.9706 12.9519 17.9706 12.4073C17.9889 12.2105 17.966 12.0732 17.911 11.8352ZM15.7129 11.9496C15.4656 12.302 15.3191 12.5949 15.2687 12.8238C15.2413 12.9474 15.2321 13.0068 15.2321 13.0892C15.2321 13.1442 15.2367 13.1991 15.2458 13.2494C15.0489 13.0252 14.9344 12.7277 14.9344 12.4073C14.9344 12.2929 14.9482 12.2105 14.9894 12.0228C15.0627 11.6842 15.2962 11.2356 15.6763 10.6819C15.7954 10.5079 15.9236 10.3386 16.0564 10.1693C16.1892 10.334 16.3174 10.5079 16.4365 10.6819C16.8166 11.2311 17.0501 11.6842 17.1234 12.0228C17.1646 12.2105 17.1783 12.2929 17.1783 12.4073C17.1783 12.7277 17.0684 13.0252 16.8669 13.2494C16.8761 13.1945 16.8807 13.1442 16.8807 13.0892C16.8807 13.0068 16.8715 12.9474 16.844 12.8238C16.7937 12.5995 16.6426 12.302 16.3998 11.9496C16.3128 11.8215 16.2167 11.6979 16.1205 11.5789L16.0564 11.4965L15.9923 11.5789C15.8961 11.6979 15.7999 11.8215 15.7129 11.9496Z"
                                                fill="#FF5B00" />
                                            <path
                                                d="M22.0373 15.9729C22.0373 15.7761 22.0281 15.5793 22.0098 15.3871L17.9524 15.6434L21.2359 12.9752C21.0527 12.6548 20.8375 12.3573 20.5993 12.0781L17.4304 15.2452C17.0503 15.6251 17.0503 16.2476 17.4304 16.6274L20.6268 19.822C20.8649 19.5428 21.0756 19.2362 21.2588 18.9158L17.9524 16.2292L22.0144 16.4855C22.0281 16.3208 22.0373 16.1469 22.0373 15.9729Z"
                                                fill="#BBD6FF" />
                                            <path
                                                d="M15.4016 17.2731L12.1686 20.5043C12.4433 20.7423 12.7456 20.9528 13.0616 21.1405H13.0799L15.8 17.7949L15.539 21.9231C15.713 21.9368 15.8871 21.946 16.0611 21.946C16.2626 21.946 16.4595 21.9368 16.6518 21.9185L16.3908 17.7994L19.0926 21.1222C19.4132 20.9345 19.7109 20.7194 19.9902 20.4769L16.7892 17.2777C16.4045 16.8887 15.7817 16.8887 15.4016 17.2731Z"
                                                fill="#BBD6FF" />
                                            <path
                                                d="M14.6917 15.2501L11.5228 12.083C11.2847 12.3622 11.0694 12.6597 10.8862 12.9801L14.1697 15.6483L10.1123 15.392C10.094 15.5842 10.0848 15.7765 10.0848 15.9778C10.0848 16.1563 10.094 16.3302 10.1077 16.4996L14.1697 16.2433L10.8633 18.9298C11.0465 19.2502 11.2572 19.5523 11.4953 19.836L14.6917 16.6415C15.0718 16.2524 15.0718 15.63 14.6917 15.2501Z"
                                                fill="#BBD6FF" />
                                        </svg>
                                    </div>
                                    <div class="social-profile pdl-10">
                                        <h4 class="fs-14 fw-700 font-altivo color-black">fire<i
                                                class="fonts-arial fst-normal">&</i>chill</h4>
                                        <p class="fs-12 fw-300 font-arial">3,3<i class="fonts-arial fst-normal">4</i>9
                                            followers</p>
                                    </div>
                                </div>
                                <div class="view-profile">
                                    <a class="btn bg-sky-blue ssm border-radius-50 color-white text-center"
                                        href="https://www.instagram.com/firechillwellness/" target="_blank">
                                        <span>View profile</span>
                                    </a>
                                </div>
                            </div>
                            <img class="d-block mw-100" src="{{ Vite::image('sm-feed-' . $i . '.jpeg') }}"
                                alt="social Media">
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <!-- Social Media Feed end -->

    @section('scripts')
        @parent
    @endsection
</x-app-layout>
