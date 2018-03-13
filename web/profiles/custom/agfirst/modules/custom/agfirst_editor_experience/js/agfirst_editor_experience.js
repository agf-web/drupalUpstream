(function ($, Drupal) {
  Drupal.behaviors.agfirst_editor_nodeforms = {
    attach: function (context, settings) {

      // If there is a title and display title make sure display defaults to main until it has been set.
      $('input#edit-title-0-value', context).once('.agfirst-editor-experience').on('blur', function() {
        var display_title = $('input#edit-field-mma-title-0-value', context);
        if (display_title.length && display_title.val() == '') {
          display_title.val($(this).val());
        }
      });

    }
  };
})(jQuery, Drupal);
