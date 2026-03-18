"use strict";

(function ($) {
  $(document).ready(function () {
    // Toggle the curriculum switcher
    $('[data-id="piksera-curriculum-switcher"]').click(function () {
      $('.piksera-curriculum-accordion__wrapper').toggleClass('piksera-curriculum-accordion__wrapper_opened');
    });

    // Open the first section by default
    $('.piksera-curriculum-accordion__wrapper:first').addClass('piksera-curriculum-accordion__wrapper_opened');
    $('.piksera-curriculum-accordion__wrapper:first .piksera-curriculum-accordion__list').slideDown(100);

    // Click event for curriculum accordion sections
    $('.piksera-curriculum-accordion__section').click(function () {
      var content = $(this).next('.piksera-curriculum-accordion__list'),
        isOpened = content.is(':visible');

      if (isOpened) {
        content.slideUp(100, function () {
          content.css('display', 'none');
        });
        $(this).parent().removeClass('piksera-curriculum-accordion__wrapper_opened');
      } else {
        content.slideDown(100, function () {
          content.css('display', 'block');
        });
        $(this).parent().addClass('piksera-curriculum-accordion__wrapper_opened');
      }
    });

    // Prevent default click behavior for disabled links
    $('.piksera-curriculum-accordion__link_disabled').click(function (event) {
      event.preventDefault();
    });

    // Hover effect for hint
    $('.piksera-hint').hover(
      function () {
        $(this).closest('.piksera-curriculum-accordion__list').css('overflow', 'visible');
      },
      function () {
        $(this).closest('.piksera-curriculum-accordion__list').css('overflow', 'hidden');
      });

    // Check visibility function (assuming this is related to your existing code)
    function checkVisibility() {
      // Add your visibility check logic here
    }

    // Your existing code for disabling submit button and checking visibility
    var container = $('.piksera-course-player-lesson'),
      submit_button = $('[data-id="piksera-course-player-lesson-submit"]');

    if (container.length > 0 && container.find('.piksera-course-player-lesson-video').length === 0) {
      submit_button.attr('disabled', 1);
      submit_button.addClass('piksera-button_disabled');
      checkVisibility();
      var parent = $(window).width() < 1025 ? window : '.piksera-course-player-content__wrapper';
      $(parent).on('scroll touchmove', function () {
        checkVisibility();
      });
    }
  });
})(jQuery);
