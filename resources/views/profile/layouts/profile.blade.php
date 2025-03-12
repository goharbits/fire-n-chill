<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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

    </head>
    <body class="font-sans text-gray-900 antialiased page-{{ currentPage() }}
        @auth logged-in @endauth">
        @include('partials.header')
        <main>
            <div class="page-container">
                <div class="container position-relative mgb-40">
                    {{ $slot }}
                </div>
            </div>
        </main>
        @include('partials.footer')
        @yield('scripts')
    </body>
</html>
