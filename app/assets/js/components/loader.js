export let listenForLoaderToggle = function() {
    $('[data-toggle_loader]').click(function() {
        $('#main-loader').removeClass('hide-loader');
    });
};