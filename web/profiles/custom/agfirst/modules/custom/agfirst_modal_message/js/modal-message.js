(function ($, Drupal, w) {
    Drupal.behaviors.agfModalMessage = {
        attach: function (context, settings) {
            
            $(w, context).once().each(function() {

                /**
                 * modal is a singleton; only one modal on a given page.
                 */
                var displayCondition = $('#agfirst-modal-message').data('display-condition');
                var modalMessage = $('#agfirst-modal-message');
                var modalCookieName = 'suppress-modal-' + settings.modalMessage.id;


                if (typeof $.cookie(modalCookieName) == 'undefined') {
                    if (displayCondition == 'entrance') {
                        modalMessage.trigger('click');
                    } else if (displayCondition == 'exit') {
                        var showModal = function(e) {
                            if (modalMessage.is(':hidden') && typeof $.cookie(modalCookieName) == 'undefined') {
                                modalMessage.trigger('click');
                            }
                        };

                        $('body').on('mouseleave', showModal);
                        $(window).on('blur', showModal);
                    }
                }

                $(document).on('afterClose.fb', function( e, instance, slide ) {
                    $.cookie(modalCookieName, 1);
                });

            });

        }
    };
})(jQuery, Drupal, window);
  