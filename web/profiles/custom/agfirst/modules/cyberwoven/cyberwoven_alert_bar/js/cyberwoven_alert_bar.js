(function ($, Drupal) {
  Drupal.behaviors.cyberwovenAlertBar = {
    attach: function (context, settings) {

      var cyberwoven_alert_bar_closed = $.cookie('cyberwoven_alert_bar_closed', Number);
      if (!cyberwoven_alert_bar_closed) {
        $('#cw-alert-bar').addClass('show-alert');
      }

      $('.alert-bar-close', context).on('click', function (e) {
        e.preventDefault();
        if ($('#cw-alert-bar').length > 0) {
          $('#cw-alert-bar').removeClass('show-alert');
          $.cookie('cyberwoven_alert_bar_closed', 1);
        }
      });

    }
  };
})(jQuery, Drupal);
