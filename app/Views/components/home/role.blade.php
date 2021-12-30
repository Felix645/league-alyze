<div class="role-card">
    <div class="card-header">
        <img src="{{ asset($role->role->icon_path) }}" alt="{{ $role->role->name }}">
    </div>
    <div class="card-body">
        <table>
            <tr>
                <th>Matches</th>
                <td>{{ $role->games_played }}</td>
            </tr>
            <tr>
                <th>Winrate</th>

                @if( $role->games_played > 0 )
                    @if($role->winrate >= 55)
                        @php
                            $winrate_color_class = 'text-light-green';
                        @endphp
                    @elseif($role->winrate >= 50 )
                        @php
                            $winrate_color_class = 'text-green';
                        @endphp
                    @elseif($role->winrate >= 47 )
                        @php
                            $winrate_color_class = 'text-orange';
                        @endphp
                    @else
                        @php
                            $winrate_color_class = 'text-red';
                        @endphp
                    @endif

                    <td class="{{ $winrate_color_class }}">{{ formatNumber($role->winrate, 2) }} %</td>
                @else
                    <td>N/A</td>
                @endif
            </tr>
            <tr>
                <th>K/D/A</th>

                @if( $role->games_played > 0 )
                    <td><span class="text-light-green">{{ formatNumber($role->kills_avg, 1) }}</span>/<span class="text-red">{{ formatNumber($role->deaths_avg, 1) }}</span>/<span class="text-green">{{ formatNumber($role->assists_avg, 1) }}</span></td>
                @else
                    <td>N/A</td>
                @endif
            </tr>

            <tr>
                <th>CS/min</th>

                @if( $role->games_played > 0 )
                    <td>{{ formatNumber(($role->cs_avg / $role->game_time_avg), 1) }}</td>
                @else
                    <td>N/A</td>
                @endif
            </tr>
        </table>
    </div>
</div>