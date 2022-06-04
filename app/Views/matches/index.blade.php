@extends('app')

@section('matches_active', 'active')

@section('content')
    <main id="matches-index">
        @include('components.app.game-mode-selection', [
            'id' => 'matches_game_mode_id',
            'name' => 'matches_game_mode_id',
            'modes' => $modes
        ])
        <section class="matches">
            <div class="matches-table">
                @foreach($matches as $match)
                    @include('components.matches.match-row', ['match' => $match])
                @endforeach
            </div>

            <div class="load-container">
                @include('components.matches.load-button', ['matches' => $matches])
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script>
        let load_matches_url = '{{ route('live.matches.load')->full() }}';
    </script>

    <script type="module" src="{{ asset('/js/matches/index.js') }}"></script>
@endsection