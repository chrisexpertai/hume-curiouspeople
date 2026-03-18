"use strict";

(function ($) {
  $(document).ready(function () {
    $('.piksera-course-player-header__discussions-toggler').click(function () {
      toggleDiscussions();
    });

    $('.piksera-course-player-header__discussions-close-btn').click(function () {
      toggleDiscussions();
    });

    $('.piksera-course-player-discussions__mobile-close').click(function () {
        toggleDiscussions();
      });


    function toggleDiscussions() {
      var container = $('.piksera-course-player-header__discussions');

      if (container.hasClass('piksera-course-player-header__discussions_open')) {
        container.css('transition', '0.3s');
      } else {
        container.css('transition', '1.25s');
      }

      if (window.matchMedia('(max-width: 1024px)').matches) {
        $('.piksera-course-player-discussions')
          .find('.piksera-course-player-quiz__navigation-tabs')
          .toggleClass('piksera-course-player-quiz__navigation-tabs_show');
      }

      var currentUrl = window.location.href;
      var url = new URL(currentUrl);

      if (url.searchParams.has('discussions_open')) {
        url.searchParams.delete('discussions_open');
      }

      history.pushState({}, '', url.toString());

      if (localStorage.getItem('discussions_open') === 'yes') {
        localStorage.removeItem('discussions_open');
      }

      container.toggleClass('piksera-course-player-header__discussions_open');
      $('body').toggleClass('piksera-course-player-body-hidden');
      $('.piksera-course-player-discussions').toggleClass('piksera-course-player-discussions_open');

      if (!$('.piksera-course-player-curriculum').hasClass('piksera-course-player-curriculum_open')) {
        $('.piksera-course-player-content').toggleClass('piksera-course-player-content_open-sidebar');
      }
    }
  });
})(jQuery);
