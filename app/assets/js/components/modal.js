export class Modal {
    /**
     * Modal Constructor
     * @param modal_id
     */
    constructor( modal_id ) {
        this.modal_id = modal_id;
    }


    /**
     * Executes callback function from arguments if a modal with the modal_id property is found
     * @param callback
     * @return void
     */
    show( callback ) {
        if( document.getElementById(this.modal_id) ) {
            this.modal = document.getElementById(this.modal_id);

            this.modal.addEventListener('modal-show', (event) => {
                this.button = event.detail.relatedButton;
                callback();
            });
        }
    }


    /**
     * Returns the data that corresponds to the given parameter from the button that triggered the modal
     * @param data
     * @returns {string | undefined}
     */
    buttonData( data ) {
        return this.button.dataset[data];
    }


    /**
     * Returns an html element inside the modal with the given css selector
     * @param selector
     * @returns {*}
     */
    element( selector ) {
        return this.modal.querySelector( selector );
    }


    /**
     * Registers all modal related buttons
     * @return void
     */
    static registerButtons() {
        this.registerShowButtons();
        this.registerCloseButtons();
        this.registerBackgroundClose();
    }


    /**
     * Registers the buttons which trigger a modal
     * @return void
     */
    static registerShowButtons() {
        if( document.querySelector('[data-toggle="modal"]') ) {
            let showButtons = document.querySelectorAll('[data-toggle="modal"]');

            showButtons.forEach( (item) => {
                item.addEventListener('click', (event) => {
                    event.preventDefault();
                    let button = event.currentTarget;
                    let modal_to_open = button.dataset.target;
                    let modal = document.querySelector(modal_to_open);

                    let modalShow = new CustomEvent('modal-show', {
                        'detail': {
                            relatedButton: button
                        }
                    });

                    modal.dispatchEvent(modalShow);
                    modal.classList.add('open');
                });
            });
        }
    }


    /**
     * Registers all buttons that should close their corresponding modal
     * @return void
     */
    static registerCloseButtons() {
        if( document.querySelector('.modal .modal-close[data-target]') ) {
            let closeButtons = document.querySelectorAll('.modal .modal-close[data-target]');

            closeButtons.forEach( (item) => {
                item.addEventListener('click', (event) => {
                    let button = event.currentTarget;
                    let modal_to_close = button.dataset.target;
                    let modal = document.querySelector(modal_to_close);
                    modal.classList.remove('open');
                })
            });
        }
    }


    /**
     * Registers all modal backgrounds to close its corresponding modal when the background is clicked
     * @return void
     */
    static registerBackgroundClose() {
        if( document.querySelector('.modal-background') ) {
            let modals = document.querySelectorAll('.modal-background');

            modals.forEach( (item) => {
                item.addEventListener('click', (event) => {
                    let modal = event.target;
                    modal.classList.remove('open');
                });
            });
        }
    }
}
