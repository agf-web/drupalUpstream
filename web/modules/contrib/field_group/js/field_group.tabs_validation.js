(function ($) {
  'use strict';

  /**
   * Behaviors for tab validation.
   */
  Drupal.behaviors.fieldGroupTabsValidation = {
    attach: function () {
        var fieldGroupTabsOpen = function ($field_group) {
            if ($field_group.data('verticalTab')) {
                $field_group.data('verticalTab').tabShow();
            } else {
                if ($field_group.data('horizontalTab')) {
                    $field_group.data('horizontalTab').tabShow();
                } else {
                    $field_group.attr('open', '');
                }
            }
        };

        var onInvalid = function(e) {
            $inputs.off('invalid', onInvalid);
            $(e.target).parents('details:not(:visible), details.horizontal-tab-hidden, details.vertical-tab-hidden').each(function() {
              fieldGroupTabsOpen($(this));
            });
            requestAnimationFrame(function () { $inputs.on('invalid', onInvalid); });
        };

        var $inputs = $('.field-group-tabs-wrapper :input');
        $inputs.on('invalid', onInvalid);
    }
  };

})(jQuery);
