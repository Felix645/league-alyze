<div id="request" class="content">

        <table class="main-table">
            {{-- Request Section --}}

            <tr class="heading">
                <th></th>
                <td>Request</td>
            </tr>
            <tr>
                <th>URL</th>
                <td>{{ \Artemis\Support\Str::trim($request->url(), '/') }}</td>
            </tr>
            <tr>
                <th>Path</th>
                <td>{{ empty($request->path()) ? '/' : $request->path() }}</td>
            </tr>
            <tr>
                <th>Method</th>
                <td>{{ \Artemis\Support\Str::upper($request->method()) }}</td>
            </tr>

            {{-- End Request Section --}}


            <tr class="seperator">
                <td></td>
                <td></td>
            </tr>


            {{-- Headers Section --}}

            <tr class="heading margin-top">
                <th></th>
                <td>Headers</td>
            </tr>
            @forelse( $request->headers() as $key => $value )
                <tr>
                    <th>{{ $key }}</th>
                    <td>{{ $value }}</td>
                </tr>
            @empty
                <tr>
                    <th></th>
                    <td>---</td>
                </tr>
            @endforelse

            {{-- End Headers Section --}}


            <tr class="seperator">
                <td></td>
                <td></td>
            </tr>


            {{-- Body Section --}}

            <tr class="heading margin-top">
                <th></th>
                <td>Body</td>
            </tr>

            @each('errors.partials.debug.request.sub-table', $request->body(), 'value', 'errors.partials.debug.request.sub-table-nodata')

            {{-- End Body Section --}}


            <tr class="seperator">
                <td></td>
                <td></td>
            </tr>


            {{-- Files Section --}}

            <tr class="heading margin-top">
                <th></th>
                <td>Files</td>
            </tr>

            @each('errors.partials.debug.request.sub-table', $request->files(), 'value', 'errors.partials.debug.request.sub-table-nodata')

            {{-- End Files Section --}}


            <tr class="seperator">
                <td></td>
                <td></td>
            </tr>


            {{-- Session Section --}}

            <tr class="heading margin-top">
                <th></th>
                <td>Session</td>
            </tr>

            @each('errors.partials.debug.request.sub-table', $request->session(), 'value', 'errors.partials.debug.request.sub-table-nodata')

            {{-- End Session Section --}}
        </table>


    <div class="body">

    </div>

    <div class="files">

    </div>

    <div class="session">

    </div>
</div>