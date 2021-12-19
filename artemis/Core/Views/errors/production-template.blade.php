<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{ str_replace('\\', '/', ROOT_PATH) }}artemis/Core/Views/css/internal_error.css">
    <title>Error</title>

    <style>
        {!! $css !!}
    </style>
</head>
<body>
<p>{{ $code }} | {{ $message }}</p>
</body>
</html>