<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{ str_replace('\\', '/', ROOT_PATH) }}/artemis/Core/Views/css/styles.css">
    <title>Exception</title>

    <style>
        * {
            padding: 0;
            margin: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #bbc1d6;
        }

        .line-numbers > div.lines {
            counter-reset: line;
        }

        .line-numbers > div.lines > span {
            counter-increment: line;
        }

        .line-numbers > div.lines > span::before {
            content: counter(line);
        }

        .exception-header {
            width: 85%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
        }

        .exception-header h2 {
            font-size: 20px;
            color: #949a9c;
        }

        .exception-header h3 {
            font-size: 18px;
            color: #085670;
        }

        .trace-header {
            width: 85%;
            margin: 0 auto;
            color: white;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .trace-header h3 {
            background-color: #085670;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            text-align: center;
            padding: 10px 20px;
            cursor: pointer;
        }

        .trace-header h3:hover {
            background-color: #157ea5;
        }

        .trace-header h3:first-child {
            margin-right: 10px;
        }

        .trace-header h3:last-child {
            margin-left: 10px;
        }

        .trace-header h3.active {
            background-color: #157ea5;
        }

        .exception-trace {
            width: 85%;
            margin: 20px auto;
            display: none;
        }

        .exception-trace .trace-item {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .exception-trace .trace-item table tr th,
        .exception-trace .trace-item table tr td {
            padding-bottom: 7px;
        }

        .exception-trace .trace-item table * {
            text-align: left !important;
        }

        .exception-trace .trace-item table th {
            min-width: 100px;
            color: #085670;
        }

        .exception-template {
            width: 85%;
            margin: 20px auto;
            display: none;
        }

        .exception-template .line-numbers {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .exception-template .line-numbers .lines {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            background-color: #085670;
            padding: 5px 5px 5px 15px;
        }

        .exception-template .line-numbers .lines span {
            text-align: right;
            color: white;
        }

        .exception-template .line-numbers .code {
            background-color: white;
            padding: 5px 10px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="exception-header">
    @if( isset($exception_class) )
        <h2>{{ $exception_class }}</h2>
    @endif

    <h3>
        @if( isset($exception_code) )
            {{ $exception_code }} |
        @endif

        {{ $exception_message }}
    </h3>
</div>

@if( isset($exception_trace) || isset($template) )
    <div class="trace-header">
        @if( isset($exception_trace) )
            <h3 class="stack-trace" data-target=".exception-trace">Stack Trace</h3>
        @endif
        @if( isset($template) )
            <h3 class="template" data-target=".exception-template">Template</h3>
        @endif
    </div>
@endif

    @if( isset($exception_trace) )
        <div class="exception-trace">
            @foreach( $exception_trace as $index => $trace )
                <div class="trace-item">
                    <table>
                        <tbody>
                            <tr>
                                <th>Index</th>
                                <td>{{ $index }}</td>
                            </tr>
                            @if( isset($trace['file']) )
                                <tr>
                                    <th>File</th>
                                    <td>{{ $trace['file'] }}</td>
                                </tr>
                            @endif
                            @if( isset($trace['line']) )
                                <tr>
                                    <th>Line</th>
                                    <td>{{ $trace['line'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Function</th>
                                <td>
                                    @if( !empty($trace['class']) )
                                        {{ $trace['class'] }}{{ $trace['type'] }}{{ $trace['function'] }}()
                                    @else
                                        {{ $trace['function'] }}()
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Arguments</th>
                                <td>
                                    @if( !empty($trace['args']) )
                                        @foreach( $trace['args'] as $arg )
                                            @if( is_null($arg) )
                                                null,
                                            @elseif( is_object($arg) )
                                                {{ get_class($arg) }},
                                            @elseif( is_array($arg) )
                                                @if( !empty($arg) )
                                                    array[
                                                    @foreach( $arg as $key => $value )
                                                        @if( is_bool($value) )
                                                            @if( $value )
                                                                {{ $key }} => true,
                                                            @else
                                                                {{ $key }} => false,
                                                            @endif
                                                        @elseif( is_array($value) )
                                                            {{ $key }} => array[],
                                                        @else
                                                            {{ $key }} => {{ $value }},
                                                        @endif
                                                    @endforeach
                                                    ],
                                                @endif
                                            @else
                                                {{ $arg }},
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

<script>
    function toggleContent(header) {
        const content_selector = header.dataset.target;
        const content = document.querySelector(content_selector);
        content.style.display = 'none';
    }

    if( document.querySelector('.line-numbers') ) {
        const code = document.querySelector('.code').innerHTML;
        let count = (code.match(/<br>/g) || []).length + 1;
        let lines = document.querySelector('.lines');
        for( let i = 0; i < count; i++) {
            lines.appendChild(document.createElement("span"));
        }
    }

    if( document.querySelector('.trace-header h3') ) {
        const headers = document.querySelectorAll('.trace-header h3');

        headers.forEach( (item) => {
            item.addEventListener('click', (event) => {
                headers.forEach( (item) => {
                    item.classList.remove('active');
                    toggleContent(item);
                });
                const header = event.currentTarget;
                const content_selector = header.dataset.target;
                const content = document.querySelector(content_selector);
                header.classList.toggle('active');
                content.style.display = 'block';
            });
        });
    }
</script>

</body>
</html>
