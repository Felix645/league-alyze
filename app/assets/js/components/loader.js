export let showLoader = function() {
    $('#main-loader').removeClass('hide-loader');
};

export let listenForLoaderToggle = function() {
    $('[data-toggle_loader]').click(function() {
        showLoader();
    });
};

export let hideLoader = function() {
    $('#main-loader').addClass('hide-loader');
}

export let reapplyLoaderListener = function(selector) {
    $(selector).click(function() {
        showLoader();
    });
};