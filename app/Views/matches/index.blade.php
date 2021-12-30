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
        let button_selector = 'button#load';
        let matches_table_selector = 'main#matches-index .matches .matches-table';

        let loadMatchesListener = function() {
            $(button_selector).click(function() {
                let next_page = $(this).data('next_page');
                console.log(next_page);

                $.get(load_matches_url + next_page)
                .done(function(data) {
                    if( !data.status || data.status !== 200 ) {
                        // TODO: Do some error handling
                        return;
                    }

                    $(matches_table_selector).append($(data.data.html_matches));
                    $(button_selector).replaceWith($(data.data.html_button));

                    loadMatchesListener();
                })
                .fail(function(data) {
                    // TODO: Do some error handling
                });
            });

        };

        loadMatchesListener();
    </script>
@endsection