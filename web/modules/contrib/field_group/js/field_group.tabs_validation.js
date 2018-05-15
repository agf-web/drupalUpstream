(function ($) {
  'use strict';

  /**
   * Behaviors for tab validation.
   */
  Drupal.behaviors.fieldGroupTabsValidation = {
    attach: function (context, settings) {
      var fieldGroupTabsOpen = function () {
        var $field_group = $(this);
        if ($field_group.data('verticalTab')) {
          $field_group.data('verticalTab').tabShow();
        }
        else {
          if ($field_group.data('horizontalTab')) {
            $field_group.data('horizontalTab').tabShow();
          }
          else {
            $field_group.attr('open', '');
          }
        }
      };
      $('.field-group-tabs-wrapper :input', context).each(function (i) {
        var $field_group_input = $(this);
        this.addEventListener('invalid', function (e) {
          // Open any hidden parents first.
          $(e.target).parents('details:not(:visible), details.horizontal-tab-hidden, details.vertical-tab-hidden').each(fieldGroupTabsOpen);
        }, false);
        // Open any parents for submission validation errors.
        if ($field_group_input.hasClass('error')) {
          $field_group_input.parents('details:not(:visible), details.horizontal-tab-hidden, details.vertical-tab-hidden').each(fieldGroupTabsOpen);
        }
      });
    }
  };

})(jQuery);
