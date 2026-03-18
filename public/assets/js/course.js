"use strict";

(function ($) {
    $(document).ready(function () {
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);

        // Check for URL parameter to open curriculum sidebar
        if (url.searchParams.has('curriculum_open') || localStorage.getItem('curriculum_open') === 'yes') {
            showCurriculumSidebar();
        }

        // Curriculum Toggle
        $('[data-id="piksera-curriculum-switcher"]').click(function () {
            toggleCurriculumSidebar();
        });

        // Curriculum Mobile Close
        $('.piksera-course-player-curriculum__mobile-close').click(function () {
            hideCurriculumSidebar();
        });

        // Other curriculum-related actions
        // ...

        // Discussions Initialization
        if (url.searchParams.has('discussions_open') || localStorage.getItem('discussions_open') === 'yes') {
            // Code to handle discussions visibility
        }

        // Discussions Toggle
        $('.piksera-course-player-header__discussions').click(function () {
            // Code to toggle discussions visibility
        });

        // Comments Loading
        var search_value = '';
        var currentOffset = 0;
        get_comments(currentOffset, false, search_value);

        // Comments Adding/Replying
        $('[data-id="piksera-discussions-add-comment"]').click(function (e) {
            // Code to handle adding comments
        });

        $(document).on('click', '.piksera-discussions__reply-button', function () {
            // Code to handle replying to comments
        });

        // Function to show curriculum sidebar
        function showCurriculumSidebar() {
            // Code to show curriculum sidebar
            // For example:
            $('.piksera-course-player-curriculum').addClass('piksera-course-player-curriculum_open');
            $('.piksera-course-player-content').addClass('piksera-course-player-content_open-sidebar');
        }

        // Function to hide curriculum sidebar
        function hideCurriculumSidebar() {
            // Code to hide curriculum sidebar
            // For example:
            $('.piksera-course-player-curriculum').removeClass('piksera-course-player-curriculum_open');
            $('.piksera-course-player-content').removeClass('piksera-course-player-content_open-sidebar');
        }

        // Function to toggle curriculum sidebar
        function toggleCurriculumSidebar() {
            if ($('.piksera-course-player-curriculum').hasClass('piksera-course-player-curriculum_open')) {
                hideCurriculumSidebar();
            } else {
                showCurriculumSidebar();
            }
        }
    });
})(jQuery);
"use strict";

(function ($) {
  $(document).ready(function () {
    $('.piksera-course-player-header__discussions').click(function () {
      var container = $(this);

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
    });
  });
})(jQuery);
