@extends('app')

@section('matches_active', 'active')

@section('content')
    <main id="matches-index">
        <div class="form-group table-filter">
            <div class="input table-filter">
                <label for="game_mode_id">Game Mode</label>
                <select name="game_mode_id" id="matches_game_mode_id">
                    <option value="{{ \App\Models\GameMode::ALL_ID }}" selected>All</option>
                    @foreach($modes as $mode)
                        <option value="{{ $mode->id }}">{{ $mode->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
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