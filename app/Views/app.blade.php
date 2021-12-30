<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>League-Alyze</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" type="text/css">
</head>
<body>
    @include('components.app.nav')

    <main id="app">
        <div class="top-row">
            <button class="button" data-toggle="modal" data-target="#add-match-modal">
                <i class="fas fa-plus"></i>
                Add Match
            </button>
        </div>

        @yield('content', '')
    </main>

    @include('components.app.add-match-modal', ['champions' => $champions, 'roles' => $roles])

    <script type="module" src="{{ asset('/js/app.js') }}"></script>

    @yield('js', '')

</body>
</html>