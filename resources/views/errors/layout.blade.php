<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Error') | {{ config('app.name') }}</title>
        <link rel="icon" href="{{ Vite::image('fevicon.png') }}" type="image/x-icon">
        
        @foreach (config('app.custom_fonts') as $font)
            <link rel="preload" href="{{ Vite::asset('resources/fonts/'.$font) }}" as="font" type="font/woff2">
        @endforeach

        @vite([
            'resources/css/fonts.css',
            'resources/css/style.css',
            'resources/css/app.scss',
            'resources/js/app.js',
            'resources/js/jquery-ui.min.js',
            'resources/js/custom.js'
        ])
        @yield('styles')
        <style>
            footer {
                border-top: 1px solid #e2e8f0;
            }
            .fixed-container {
                color: var(--midnight);
                text-align: center;
                min-height: 75vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin: 0 auto;
                padding: 20px;
            }
            .error-code {
                font-size: 6rem;
                font-weight: bold;
                margin: 0;
            }
            .error-title {
                font-size: 2rem;
                margin: 10px 0;
            }
            .error-message {
                margin: 20px 0;
            }
            a {
                text-decoration: none;
                font-weight: bold;
            }
            @media (min-width: 740px) {
                .page-container {
                    padding-top: 100px;
                }
            }
            .error-container {
                max-width: 600px;
                margin: 0 auto;
            }
        </style>

        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '958267852862940');fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=958267852862940&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Meta Pixel Code -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const header = document.querySelector('.header-section');                
                if (header.classList.contains('header__homepage')) {
                    header.classList.replace('header__homepage', 'header__nonhomepage');
                }
                if (header.classList.contains('header--white')) {
                    header.classList.replace('header--white', 'header--blue');
                }
            });
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased page-{{ currentPage() }}
        @auth logged-in @endauth"
        >
        @include('partials.header')
        <main>
            <div class="page-container">
                <div class="container position-relative mgb-40">
                    <div class="fixed-container">
                        <div class="error-container">
                            <h1 class="error-code">@yield('code', 'Error')</h1>
                            <p class="error-title">@yield('title', 'Something went wrong')</p>
                            <p class="error-message fs-20 fw-300 lh-15 ls-04-minus color-midnight">
                                @yield('message', 'An unexpected error occurred. Please try again later.')
                            </p>
                            <a class="btn ssm bg-orange color-white text-center border-radius-50 lh-50" href="{{ url('/') }}">Return to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('partials.footer')
        @yield('scripts')
    </body>
</html>
