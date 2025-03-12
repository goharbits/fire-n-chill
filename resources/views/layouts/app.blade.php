<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

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
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '958267852862940');fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=958267852862940&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Meta Pixel Code -->
    </head>

    <body class="page-{{ currentPage() }} 
        @auth logged-in @endauth">
        @include('partials.header')
        <main>
            {{ $slot }}
        </main>
        @include('partials.footer')
        @yield('scripts')
    </body>

</html>