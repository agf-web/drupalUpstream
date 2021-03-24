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
                var modalClick = $('.btn-fancybox-trigger');


                if (typeof $.cookie(modalCookieName) == 'undefined') {
                    if (displayCondition == 'entrance') {
                        modalClick.trigger('click');
                    } else if (displayCondition == 'exit') {
                        var showModal = function(e) {
                            if (modalMessage.is(':hidden') && typeof $.cookie(modalCookieName) == 'undefined') {
                                modalClick.trigger('click');
                            }
                        };

                        $('body').on('mouseleave', showModal);
                        $(window).on('blur', showModal);
                    }
                }

                $(document).on('afterClose.fb', function( e, instance, slide ) {
                    $.cookie(modalCookieName, 1, { expires: 10 });
                });

            });

        }
    };
})(jQuery, Drupal, window);
  