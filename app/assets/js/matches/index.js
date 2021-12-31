import {hideLoader} from "../components/loader.js";
import {reapplyLoaderListener} from "../components/loader.js";

(function() {
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
                    reapplyLoaderListener(button_selector);
                    hideLoader();
                })
                .fail(function(data) {
                    // TODO: Do some error handling
                });
        });

    };

    loadMatchesListener();
})();