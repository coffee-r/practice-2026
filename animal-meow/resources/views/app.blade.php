<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="人間社会に疲れた人が作った、動物の言葉でしか呟けないSNS">

        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:description" content="人間社会に疲れた人が作った、動物の言葉でしか呟けないSNS">
        <meta property="og:image" content="{{ config('app.url') }}/images/ogp.png">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:image" content="{{ config('app.url') }}/images/ogp_summary.png">
        <meta name="twitter:description" content="人間社会に疲れた人が作った、動物の言葉でしか呟けないSNS">

        <title inertia>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Google tag (gtag.js) -->
        @production
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-K9D6BFDXNW"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
        </script>
        @endproduction

        <!-- Scripts -->
        @routes
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body class="font-sans bg-blue-100 bg-opacity-20 antialiased overflow-y-scroll">
        @inertia
    </body>
</html>
