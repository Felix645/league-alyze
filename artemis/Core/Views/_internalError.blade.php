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
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap");
        * {
            padding: 0;
            margin: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            height: 100vh;
        }

        body p {
            font-size: 1.7rem;
            font-weight: 400;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            color: #919191;
        }
    </style>
</head>
<body>
    <p>{{ $code }} | {{ $message }}</p>
</body>
</html>