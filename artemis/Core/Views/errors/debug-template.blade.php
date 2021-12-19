<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>

    <link rel="icon" type="image/png" href="{{ $favicon }}"/>

    <style>
        {!! $css !!}
    </style>
</head>
<body>
    <header class="debug-header">
        <p class="exception">@if( $exception->code() !== 0 ){{ $exception->code() }} |  @endif{{ $exception->exception() }}</p>
        <p class="message">{{ $exception->message() }}</p>
    </header>

    <main class="debug-main">
        <div class="nav">
            <ul>
                <li>
                    <button class="active" data-target="#stack-trace">Stack trace</button>
                </li>
                <li>
                    <button data-target="#request" data-attach_margin="true">Request</button>
                </li>
                <li>
                    <button data-target="#routes" data-attach_margin="true">Routes</button>
                </li>
                <li>
                    <button data-target="#context" data-attach_margin="true">Context</button>
                </li>
            </ul>
        </div>
        <div class="main-content">
            @include('errors.partials.debug.stack-trace')

            @include('errors.partials.debug.request')

            @include('errors.partials.debug.routes', ['routes' => $routes])

            @include('errors.partials.debug.context')
        </div>
    </main>

    <script>
        {!! $js !!}
    </script>
</body>
</html>