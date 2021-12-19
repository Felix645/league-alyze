<div id="stack-trace" class="content show">
    <div class="trace">
        @foreach( $stack_trace as $trace_number => $trace )
            <div class="container @if( $loop->first ) active @endif @if( $loop->last ) last @endif" data-target="#trace-number-{{ $trace_number }}">
                <div class="trace-number">
                    {{ $trace_number }}
                </div>
                <div class="file-info">
                    <div class="file">
                        {{ $trace->file() }}
                    </div>
                    <div class="class">
                        {{ $trace->class() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @foreach( $stack_trace as $trace_number => $trace )
        <div id="trace-number-{{ $trace_number }}" class="file-content @if( $loop->first ) show @endif">
            <div class="file-info">
                <div class="class">
                    @if( !empty($trace->class()) && !empty($trace->function()) )
                        <span class="class-string">{{ $trace->class() }}</span><span class="method-string">::{{ $trace->function() }}</span>
                    @endif
                </div>

                <div class="file">
                    <span class="file-string">{{ $trace->file() }}</span><span class="line-string">:{{ $trace->line() }}</span>
                </div>
            </div>

            <div class="code-wrapper">
                <div class="code">
                    @foreach( $trace->linesOfCode() as $line_number => $line )
                        <div class="row">
                            <div class="line-number @if( $line_number === $trace->line() ) active @endif">
                                {{ $line_number }}
                            </div>
                            <div class="line @if( $line_number === $trace->line() ) active @endif">
                                <pre>{{ $line }}</pre>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>