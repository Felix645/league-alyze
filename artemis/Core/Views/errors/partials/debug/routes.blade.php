<div id="routes" class="content">
    <div class="routes">
        @foreach( $routes as $method => $method_routes)
            <h3>{{ strtoupper($method) }}</h3>

            @each('errors.partials.debug.routes.route', $method_routes ?? [], 'route', 'errors.partials.debug.routes.routes-nodata')
        @endforeach
    </div>
</div>