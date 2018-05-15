(function ($) {
  'use strict';

  /**
   * Behaviors for details validation.
   */
  Drupal.behaviors.fieldGroupDetailsValidation = {
    attach: function (context, settings) {
      $('.field-group-details :input', context).each(function (i) {
        var $field_group_input = $(this);
        this.addEventListener('invalid', function (e) {
          // Open any hidden parents first.
          $(e.target).parents('details:not([open])').each(function () {
            $(this).attr('open', '');
          });
        }, false);
        if ($field_group_input.hasClass('error')) {
          $field_group_input.parents('details:not([open])').each(function () {
            $(this).attr('open', '');
          });
        }
      });
    }
  };

})(jQuery);
