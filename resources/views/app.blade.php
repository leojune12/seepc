<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'seePC') }}</title>

        <meta name="og:title" content="seePC">
        <meta name="og:description" content="Publish your PC. Let other developers see your work station.">
        <meta name="og:image" content="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/LCM%2BL_Working_Apple_I.jpg/800px-LCM%2BL_Working_Apple_I.jpg">
        {{--<meta name="og:url" content="https://seepc.herokuapp.com">--}}

        <!-- Fonts -->
        {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">--}}

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
