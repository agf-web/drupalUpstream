// menu: detect children and hook up show/hide children behavior

(function ($, Drupal) {
  Drupal.behaviors.menuDetectChildren = {
    attach: function (context, settings) {
      $('.menu.menu--main .toggle-children', context)
        .once('behaviors--menuDetectChildren', function () {
          
        })
        .on('click', function (e) {
          e.preventDefault();
          $(this).toggleClass('is-active');

          var sibling_ul = [].slice.call(this.parentNode.children) // convert from array-like to real array using slice
            .filter(function(child) { return child.tagName.toLowerCase() === 'ul'; });

          if (sibling_ul[0].style.maxHeight) {
            sibling_ul[0].style.maxHeight = null;
            sibling_ul[0].classList.remove('is-open');
          } else {
            sibling_ul[0].style.maxHeight = sibling_ul[0].scrollHeight + 'px';
            sibling_ul[0].classList.add('is-open');
          }
        })

      // $('.hamburger', context)
      //   .once('behaviors--mobile-toggle')
      //   .on('click', function () {
      //     $(this).toggleClass('is-active');

      //     // Tai Note: I am using regular JS to add the active classes
      //     // because of how I'm setting .scrollHeight (DOM API).
      //     // setting it this way ensures the transform transition
      //     // timing is correct, as opposed to setting the max-height
      //     // in the style sheets (animation will be faster on remove)
      //     var drawer = document.getElementsByClassName('region--site-header-right')[0];

      //     if (drawer.style.maxHeight) {
      //       drawer.style.maxHeight = null;
      //       drawer.classList.remove('is-active', 'is-active--mobile');
      //     } else {
      //       drawer.style.maxHeight = drawer.scrollHeight + 'px';
      //       drawer.classList.add('is-active', 'is-active--mobile');
      //     }
      //   });
    }
  };
})(jQuery, Drupal);
