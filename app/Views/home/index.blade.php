@extends('app')

@section('home_active', 'active')

@section('content')
    <main id="home">
        @include('components.app.game-mode-selection', [
            'id' => 'home_game_mode_id',
            'name' => 'home_game_mode_id',
            'modes' => $modes
        ])

        @include('components.home.content', compact('top_champions', 'top_roles'))
    </main>
@endsection

@section('js')
    <script>
        let load_matches_url = '{{ route('live.home.load')->full() }}';
    </script>

    <script type="module" src="{{ asset('/js/home/index.js') }}"></script>
@endsection