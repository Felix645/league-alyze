import {hideLoader, showLoader} from "../components/loader.js";

(function() {
    const game_mode_selector = '#home_game_mode_id';
    const content_selector = 'main#home > .content' ;

    let changeGameModListener = function() {
        $(game_mode_selector).change(function() {
            showLoader();

            let mode_id = $(this).children("option:selected").val();
            let url = load_matches_url + '?mode=' + mode_id;

            $.get(url)
                .done(function(data) {
                    if( !data.status || data.status !== 200 ) {
                        // TODO: Do some error handling
                        hideLoader();
                        return;
                    }

                    $(content_selector).replaceWith($(data.data.html_content));

                    hideLoader();
                })
                .fail(function(data) {
                    console.log('fail');
                    hideLoader();
                });
        });
    };

    changeGameModListener();
})();