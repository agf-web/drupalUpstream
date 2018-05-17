// mobile toggle 

(function ($, Drupal) {
  Drupal.behaviors.mobileToggle = {
    attach: function (context, settings) {
      
      $('.fader', context)
        // .once('behaviors--fader')
        .on('click', function() {
          $('.hamburger').removeClass('is-active');
          $(this).removeClass('is-active--mobile-drawer');
          $('.region--site-header-right').removeClass('is-active');
        });

      $('.hamburger', context)
        .once('behaviors--mobile-toggle')
        .on('click', function () {
          $(this).toggleClass('is-active');
          $('.region--site-header-right').toggleClass('is-active')
          $('.fader').toggleClass('is-active--mobile-drawer');

          // $('.site-header')
          //   .toggleClass('is-active--mobile-drawer');


          // // Tai Note: I am using regular JS to add the active classes
          // // because of how I'm setting .scrollHeight (DOM API).
          // // setting it this way ensures the transform transition
          // // timing is correct, as opposed to setting the max-height
          // // in the style sheets (animation will be faster on remove)
          // var drawer = document.getElementsByClassName('region--site-header-right')[0];

          // if (drawer.style.maxHeight) {
          //   drawer.style.maxHeight = null;
          //   drawer.classList.remove('is-active', 'is-active--mobile');
          // } else {
          //   drawer.style.maxHeight = drawer.scrollHeight + 'px';
          //   drawer.classList.add('is-active', 'is-active--mobile');
          // }
        });
    }
  };
})(jQuery, Drupal);
