(function() {
    let changer = function(target, targetButton, buttons, contentSelector) {
        const contents = document.querySelectorAll(contentSelector);
        const targetContent = document.querySelector(target);

        contents.forEach((item) => {
            item.classList.remove('show');
        });

        buttons.forEach((item) => {
            item.classList.remove('active');
        });

        targetContent.classList.add('show');
        targetButton.classList.add('active');

        let body = document.querySelector('body');

        if( targetButton.dataset.attach_margin ) {
            body.classList.add('margin-bottom');
        } else {
            body.classList.remove('margin-bottom');
        }
    };

    let buttonListener = function(buttonsSelector, contentSelector) {
        const buttons = document.querySelectorAll(buttonsSelector);

        buttons.forEach((item) => {
            item.addEventListener('click', (event) => {
                let button = event.currentTarget;
                let target = button.dataset.target;

                changer(target, button, buttons, contentSelector);
            });
        });
    };

    let navListener = function() {
        buttonListener('main .nav button', 'main .main-content .content');
    };

    let stackTraceListener = function() {
        buttonListener('main #stack-trace .trace .container', 'main #stack-trace .file-content');
    };

    navListener();
    stackTraceListener();
})();