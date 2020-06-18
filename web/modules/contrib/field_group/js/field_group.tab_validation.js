(function ($) {
  'use strict';

  /**
   * Make sure tab field groups which contain invalid data are expanded when they first load, and also
   * when someone clicks the submit button.
   */
  Drupal.behaviors.fieldGroupTabValidation = {
    attach: function () {
      var openTabsWithInvalidFields = function() {
        $('.field-group-tab :invalid').parents('details').children('summary[aria-expanded=false]').click();
      }

      // When a form is first loaded, open tabs with invalid fields.
      openTabsWithInvalidFields();

      // Also, when someone tries to submit a form, open tabs with invalid fields.
      $('#edit-submit').on('click', openTabsWithInvalidFields);
    }
  };

})(jQuery);
