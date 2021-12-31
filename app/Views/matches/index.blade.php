@extends('app')

@section('matches_active', 'active')

@section('content')
    <main id="matches-index">
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
        let load_matches_url = '{{ route('live.matches.load')->full() . '?page=' }}';
    </script>

    <script type="module" src="{{ asset('/js/matches/index.js') }}"></script>
@endsection