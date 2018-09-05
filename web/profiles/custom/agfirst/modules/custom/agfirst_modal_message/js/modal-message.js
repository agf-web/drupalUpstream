(function($) {

    /**
     * modal is a singleton; only one modal on a given page.
     */
    if ($('#agfirst-modal-message').data('display-condition') == 'entrance') {
        $('#agfirst-modal-message').trigger('click');
    }

    // TODO: handle unload event for showing "exit" modals

})(jQuery);
