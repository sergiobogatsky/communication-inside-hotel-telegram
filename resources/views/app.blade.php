<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{csrf_token()}}">

        <title>communication-inside-hotel-telegram</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.8.95/css/materialdesignicons.css">
        <link href="{{asset('css/app.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

    </head>
    <body>
        <div id="app">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>

        <script src="{{asset('js/app.js')}}"></script>

    </body>
</html>
