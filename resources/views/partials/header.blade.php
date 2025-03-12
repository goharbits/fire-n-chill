<!-- Overly Menu -->
@php
    $isHomeRoute = Route::currentRouteName() === 'home';
    $color = $isHomeRoute ? 'white' : 'blue';
    $fill = $isHomeRoute ? 'white' : '#031A35';
    $logo = $isHomeRoute ? 'fc-logo.svg' : 'fc-logo-blue.svg';
@endphp
<div id="menuContainer" class="overly-menu-section position-fixed top-0 h-100 pdt-40 bg-light-blue">

    <div id="closeMenu" class="menu-bar-close mgl-35 position-absolute">
        <img src="{{ Vite::image('close-icon-blue.svg') }}" alt="close">
    </div>

    <div class="container menus-wrap d-flex">
        <div class="header-menus text-left d-flex flex-column pdt-35 mgb-20">
            {{-- <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus" href="{{ route('home') }}#experience" data-scroll-target="#experience">EXPERIENCE</a> --}}
            <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus"
                href="{{ route('home') }}#services" data-scroll-target="#services">services</a>
            <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus"
                href="{{ route('home') }}#our-plans" data-scroll-target="#our-plans">pricing</a>
            <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus {{ Route::currentRouteName() == 'page.rewards' ? 'active' : '' }}"
                href="{{ route('page.rewards') }}">rewards</a>
            <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus {{ Route::currentRouteName() == 'page.contactus' ? 'active' : '' }}"
                href="{{ route('page.contactus') }}">contact</a>
            @auth
                <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">Account</a>
            @else
                <a class="fs-40 fw-700 lh-12 mgb-30 text-uppercase font-altivo color-midnight ls-72-minus {{ Route::currentRouteName() == 'login' ? 'active' : '' }}"
                    href="{{ route('login') }}">Login</a>
            @endauth
        </div>
        <div class="social-link-menus d-flex">
            <a class="bg-blue" href="https://www.instagram.com/firechillwellness/" target="_blank">
                <img src="{{ Vite::image('icon-insta.svg') }}" alt="instagram">
            </a>
            <a class="bg-blue" href="https://www.facebook.com/firechillwellness/" target="_blank">
                <img src="{{ Vite::image('icon-fb.svg') }}" alt="instagram">
            </a>
            <a class="bg-blue" href="https://www.tiktok.com/@firechillwellness" target="_blank">
                <img src="{{ Vite::image('icon-spoity.svg') }}" alt="instagram">
            </a>
        </div>
    </div>
</div>
<!-- Overly Menu menu -->

<!-- Header Section -->
@if (Route::currentRouteName() == 'home')
    <header class="header-section header-v2 header__homepage header--blue">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-sm-8 header__logo-container">
                    <a href="{{ route('home') }}">
                        <img class="white-header-logo" src="{{ Vite::image('fc-logo.svg') }}" alt="logo">
                        <img class="blue-header-logo" src="{{ Vite::image('fc-logo-blue.svg') }}" alt="logo">
                    </a>
                </div>
                <div
                    class="col-6 col-sm-4 d-flex align-items-center justify-content-end rt-header header__icon-container">
                    <a class="book-session btn ssm color-midnight text-center border-radius-50 lh-50"
                        @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span>Book
                            a Session</span></a>
                    <div id="menus" class="menu-bar mgl-35">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="25" viewBox="0 0 31 25"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2.29156 0.50293H0.291565V4.50293H2.29156H28.9635H30.9635V0.50293H28.9635H2.29156ZM0.291565 10.5002H2.29156H28.9635H30.9635V14.5002H28.9635H2.29156H0.291565V10.5002ZM0.558228 20.4971H2.55823H28.8508H30.8508V24.4971H28.8508H2.55823H0.558228V20.4971Z"
                                fill="white" />
                        </svg>
                    </div>
                    @auth
                        <div class="nav-item dropdown mgl-35">
                            <a id="navbarDropdown" data-target="accountDropdown" class="nav-link dropdown-toggle ms-1"
                                href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <circle cx="12.1936" cy="6" r="6" fill="#FF5B00" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M24 24C24 17.3726 18.6274 12 12 12C5.37258 12 0 17.3726 0 24H24Z"
                                        fill="#031A35" />
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-custom"
                                aria-labelledby="navbarDropdown" id="accountDropdown" style="display: none">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-user-circle"></i> Account
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
@else
    <header
        class="header-section header-v2 header__non-homepage{{ Auth::check() ? ' header--blue' : ' header--white' }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 col-md-app-3 col-lt">
                    <div id="menus" class="menu-bar mgl-35">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="25" viewBox="0 0 31 25"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2 0.50293H0V4.50293H2H28.6719H30.6719V0.50293H28.6719H2ZM0 10.5002H2H28.6719H30.6719V14.5002H28.6719H2H0V10.5002ZM0.266663 20.4971H2.26666H28.5592H30.5592V24.4971H28.5592H2.26666H0.266663V20.4971Z"
                                fill="#031A35"></path>
                        </svg>
                    </div>
                </div>
                <div class="col-8 col-md-app-6 col-middle">
                    <a href="{{ route('home') }}">
                        <img class="white-header-logo" src="{{ Vite::image('fc-logo.svg') }}" alt="logo">
                        <img class="blue-header-logo" src="{{ Vite::image('fc-logo-blue.svg') }}" alt="logo">
                    </a>
                </div>
                <div class="col-2 col-md-app-3 col-rt">
                    <a class="book-session btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50 "
                        @guest href="{{ route('guest.slots.available', ['guest' => 'true']) }}" @else href="{{ route('client.slots.available') }}" @endguest><span
                            class="fs-16 color-midnight fw-600 text-uppercase">Book a Session</span></a>
                    @auth
                        <div class="nav-item dropdown">
                            <a id="navbarDropdown" data-target="accountDropdown" class="nav-link dropdown-toggle ms-1"
                                href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <circle cx="12.1936" cy="6" r="6" fill="#FF5B00" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M24 24C24 17.3726 18.6274 12 12 12C5.37258 12 0 17.3726 0 24H24Z"
                                        fill="#031A35" />
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-custom"
                                aria-labelledby="navbarDropdown" id="accountDropdown" style="display: none">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-user-circle"></i> Account
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
@endif
<!-- Header Section -->
<div class="PageOverlay"></div>
