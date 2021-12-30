<div class="champion-card">
    <div class="card-header">
        <h3>{{ $champion->champion_played_as->name }}</h3>
        <img src="{{ asset($champion->champion_played_as->icon_path) }}" alt="{{ $champion->champion_played_as->name }} Icon">
    </div>
    <div class="card-body">
        <table>
            <tr>
                <th>Role</th>
                <td >
                    <img width="25px" src="{{ asset($champion->role->icon_path) }}" alt="{{ $champion->role->name }}">
                </td>
            </tr>
            <tr>
                <th>Matches</th>
                <td>{{ $champion->games_played }}</td>
            </tr>
            <tr>
                <th>Winrate</th>
                @if($champion->winrate >= 55)
                    @php
                        $winrate_color_class = 'text-light-green';
                    @endphp
                @elseif($champion->winrate >= 50 )
                    @php
                        $winrate_color_class = 'text-green';
                    @endphp
                @elseif($champion->winrate >= 47 )
                    @php
                        $winrate_color_class = 'text-orange';
                    @endphp
                @else
                    @php
                        $winrate_color_class = 'text-red';
                    @endphp
                @endif

                <td class="{{ $winrate_color_class }}">{{ formatNumber($champion->winrate, 2) }} %</td>
            </tr>
            <tr>
                <th>K/D/A</th>
                <td><span class="text-light-green">{{ formatNumber($champion->kills_avg, 1) }}</span>/<span class="text-red">{{ formatNumber($champion->deaths_avg, 1) }}</span>/<span class="text-green">{{ formatNumber($champion->assists_avg, 1) }}</span></td>
            </tr>
            <tr>
                <th>CS/min</th>
                <td>{{ formatNumber(($champion->cs_avg / $champion->game_time_avg), 1) }}</td>
            </tr>
        </table>
    </div>
</div>