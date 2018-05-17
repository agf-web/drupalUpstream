
(function ($, Drupal) {
  Drupal.behaviors.test = {
    attach: function (context, settings) {

      console.log('it works!')

      // $('.hamburgers', context).on('click', function (e) {
      //   e.preventDefault();
      //   $(this).toggleClass('is-active');
      //   console.log('CLICK EVENT')
      // })

      

      $('.hamburger', context)
        .once('behaviors--mobile-toggle')
        .on('click', function () {
          $(this).toggleClass('is-active');
        });
    }
  };
})(jQuery, Drupal);
