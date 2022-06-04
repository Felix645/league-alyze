@extends('app')

@section('home_active', 'active')

@section('content')
    <main id="home">
        <div class="form-group table-filter">
            <div class="input table-filter">
                <label for="home_game_mode_id">Game Mode</label>
                <select name="game_mode_id" id="home_game_mode_id">
                    <option value="{{ \App\Models\GameMode::ALL_ID }}" selected>All</option>
                    @foreach($modes as $mode)
                        <option value="{{ $mode->id }}">{{ $mode->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('components.home.content', compact('top_champions', 'top_roles'))
    </main>
@endsection

@section('js')
    <script>
        let load_matches_url = '{{ route('live.home.load')->full() }}';
    </script>

    <script type="module" src="{{ asset('/js/home/index.js') }}"></script>
@endsection