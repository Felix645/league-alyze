<div class="route-container">
    <table class="main-table">
        <tr>
            <th>Path</th>
            <td>{{ $route->getPath() }}</td>
        </tr>
        <tr>
            <th>Action</th>
            <td>
                @if( $route->hasController() )
                    {{ $route->getController() . '::' . $route->getAction() . '()' }}
                @elseif( $route->hasCallback() )
                    {{ 'Closure' }}
                @else
                    Invalid Action defined!
                @endif
            </td>
        </tr>
        <tr>
            <th>Name</th>
            <td>
                @if( $route->getName() )
                    {{ $route->getName() }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <th>Middlewares</th>
            <td>
                @if( $route->hasMiddlewares() )
                    @foreach( $route->getMiddlewares() as $middleware )
                        @if( $loop->first )
                            {{ "$middleware" }}
                        @else
                            {{ ", $middleware" }}
                        @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>
    </table>
</div>