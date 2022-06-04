import {hideLoader, reapplyLoaderListener, showLoader} from "../components/loader.js";

(function() {
    let matchesAreReloaded = false;
    let button_selector = 'button#load';
    let matches_table_selector = 'main#matches-index .matches .matches-table';

    let game_mode_selector = '#matches_game_mode_id';

    let reloadMatches = function(data) {
        console.log(matchesAreReloaded);

        if( matchesAreReloaded ) {
            $(matches_table_selector).empty();
        }

        if( !data.status || data.status !== 200 ) {
            // TODO: Do some error handling
            hideLoader();
            return;
        }

        $(matches_table_selector).append($(data.data.html_matches));
        $(button_selector).replaceWith($(data.data.html_button));

        loadMatchesListener();
        reapplyLoaderListener(button_selector);
        hideLoader();
    };

    let loadMatchesListener = function() {
        $(button_selector).click(function() {
            let next_page = $(this).data('next_page');
            let mode_id = $(game_mode_selector).children("option:selected").val();
            let url = load_matches_url + '?page=' + next_page + '&mode=' + mode_id;

            $.get(url)
                .done(reloadMatches)
                .fail(function(data) {
                    console.log('fail');
                    hideLoader();
                });
        });

    };

    let changeGameModeListener = function() {
        $(game_mode_selector).change(function(){
            showLoader();

            let mode_id = $(this).children("option:selected").val();
            let url = load_matches_url + '?page=1' + '&mode=' + mode_id;

            matchesAreReloaded = true;

            $.get(url)
                .done(reloadMatches)
                .fail(function(data) {
                    console.log('fail');
                    hideLoader();
                })
                .always(function() {
                    matchesAreReloaded = false;
                });
        })
    };

    loadMatchesListener();
    changeGameModeListener();
})();