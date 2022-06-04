<div class="match @if($match->is_win) win @else loss @endif">
    <div class="info">
        <div class="timestamp">{{ dateTime(now())->diffWith($match->created_at)->localize('en')->inWords() }}</div>
        <div class="seperator"></div>
        <div class="result">
            @if($match->is_win)
                <span class="win">Win</span>
            @else
                <span class="loss">Loss</span>
            @endif
        </div>
    </div>
    <div class="mode">
        <span class="loss">{{ $match->mode->title }}</span>
    </div>
    <div class="role">
        <img src="{{ asset($match->role->icon_path) }}" alt="{{ $match->role->name }}">
    </div>
    <div class="played-as">
        <img src="{{ asset($match->champion_played_as->icon_path) }}" alt="{{ $match->champion_played_as->name }}">
    </div>
    <div class="champion-divider">
        vs
    </div>
    <div class="played-against">
        <img src="{{ asset($match->champion_played_against->icon_path) }}" alt="{{ $match->champion_played_against->name }}">
    </div>
    <div class="kda">
        <div class="summary">
            <span class="kills">{{ $match->kills }}</span>/<span class="deaths">{{ $match->deaths }}</span>/<span class="assists">{{ $match->assists }}</span>
        </div>
        <div class="average">
            @if($match->deaths === 0)
                Perfect KDA
            @else
                {{ formatNumber((($match->kills + $match->assists) / $match->deaths), 1) }} KDA
            @endif

        </div>
    </div>
    <div class="cs">
        <div class="total">{{ $match->creep_score }} CS</div>
        <div class="per-minute">{{ formatNumber(($match->creep_score / ((($match->minutes * 60) + $match->seconds) / 60)), 1) }} CS/min</div>
    </div>
    <div class="gametime">{{ $match->minutes }}m {{ $match->seconds }}s</div>
</div>