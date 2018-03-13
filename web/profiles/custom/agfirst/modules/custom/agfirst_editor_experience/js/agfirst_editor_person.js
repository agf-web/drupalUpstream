(function ($, Drupal) {
  Drupal.behaviors.agfirst_editor_nodeforms = {
    attach: function (context, settings) {

      // If there is a title and display title make sure display defaults to main until it has been set.
      var name_handler = function () {
        var fullName = $('input#edit-field-first-name-0-value').val() + ' ' + $('input#edit-field-last-name-0-value').val();
        if ($('input#edit-field-job-title-0-value').val() != '' ) {
          fullName += ' – ' + $('input#edit-field-job-title-0-value').val();
        }
        $('input#edit-title-0-value').val(fullName);
      };
      $('input#edit-field-job-title-0-value', context).once('.agfirst-editor-experience').on('blur', name_handler);
      $('input#edit-field-first-name-0-value', context).once('.agfirst-editor-experience').on('blur', name_handler);
      $('input#edit-field-last-name-0-value', context).once('.agfirst-editor-experience').on('blur', name_handler);

    }
  };
})(jQuery, Drupal);
