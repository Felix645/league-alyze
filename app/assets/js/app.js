import {Modal} from "./components/modal.js";
import {listenForLoaderToggle} from "./components/loader.js";

(function() {
    Modal.registerButtons();
    listenForLoaderToggle();
})();