<tr>
    <th>{{ $key }}</th>

    @if( !is_array($value) )
        <td>{{ $value }}</td>
    @else
        <td>
            <table class="sub-table">
                @foreach( $value as $key2 => $value2 )
                    <tr>
                        <th>{{ $key2 }}</th>
                        <td>
                            @if( is_array($value2) )
                                [ ]
                            @else
                                {{ $value2 }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    @endif
</tr>