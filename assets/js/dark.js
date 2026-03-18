$(parent).on('scroll', function() {
    var windowTop = $(window).scrollTop();
    var windowBottom = windowTop + $(window).height();
    var materialsElement = $('.piksera-course-player-lesson-materials');

    if (materialsElement.length > 0) {
        var materialsElementTop = materialsElement.offset().top;
        var materialsElementBottom = materialsElementTop + materialsElement.height();

        if (materialsElementTop >= windowTop && materialsElementBottom <= windowBottom) {
            $('.piksera-course-player-header__navigation [data-id="materials"]').addClass('piksera-tabs__item_active');
            $('.piksera-course-player-header__navigation [data-id="lesson"]').removeClass('piksera-tabs__item_active');
        } else {
            $('.piksera-course-player-header__navigation [data-id="materials"]').removeClass('piksera-tabs__item_active');
            $('.piksera-course-player-header__navigation [data-id="lesson"]').addClass('piksera-tabs__item_active');
        }
    }
});

$('.piksera-course-player-header__dark-mode').find('.piksera-dark-mode-button').click(function() {
    var dark_mode = false;

    if ($(this).hasClass('piksera-dark-mode-button_style-dark')) {
        dark_mode = true;
    }

    $('.piksera-course-player-header').toggleClass('piksera-course-player-header_dark-mode');
    $('.piksera-course-player-content').toggleClass('piksera-course-player-content_dark-mode');
    $('.piksera-course-player-navigation').toggleClass('piksera-course-player-navigation_dark-mode');
    $('.piksera-course-player-header__curriculum').find('.piksera-switch-button').toggleClass('piksera-switch-button_dark-mode');
    $('.piksera-course-player-header').find('.piksera-tabs').toggleClass('piksera-tabs_dark-mode');
    $('.piksera-course-player-curriculum').find('.piksera-curriculum-accordion').toggleClass('piksera-curriculum-accordion_dark-mode');
    $('.piksera-course-player-curriculum').find('.piksera-progress').toggleClass('piksera-progress_dark-mode');
    $('.piksera-course-player-navigation').find('.piksera-nav-button').toggleClass('piksera-nav-button_dark-mode');
    $('.piksera-course-player-discussions').find('.piksera-discussions').toggleClass('piksera-discussions_dark-mode');
    $('.piksera-course-player-quiz').find('.piksera-pagination').toggleClass('piksera-pagination_dark-mode');
    $('.piksera-course-player-quiz__navigation-tabs').find('.piksera-tabs-pagination').toggleClass('piksera-tabs-pagination_dark-mode');
    $('.piksera-alert').toggleClass('piksera-alert_dark-mode');
    $('.piksera-course-player-content').find('.piksera-countdown').toggleClass('piksera-countdown_dark-mode');
    $('.piksera-course-player-content').find('.piksera-file-upload').toggleClass('piksera-file-upload_dark-mode');
    $('.piksera-course-player-content').find('.piksera-file-attachment').toggleClass('piksera-file-attachment_dark-mode');
    $('.piksera-course-player-content').find('.piksera-hint').toggleClass('piksera-hint_dark-mode');
    $('.piksera-course-player-content').find('.piksera-wp-editor').toggleClass('piksera-wp-editor_dark-mode');

    ChangeEditorDarkMode(!dark_mode);
});

function ChangeEditorDarkMode(dark_mode) {
    if ($('.piksera-course-player-assignments__edit').length > 0) {
        var editor_id = $('.piksera-course-player-assignments__edit').data('editor');
        var editor = tinyMCE.get(editor_id);

        var body_dark_styles = settings.theme_fonts ? "\n                    body {\n                        line-height: normal;\n                        background-color: rgba(23,23,23,1);\n                        color: rgba(255,255,255,0.7); }\n                    " :
            "\n                    body {\n                        font-family: 'Albert Sans', sans-serif;\n                        line-height: normal;\n                        background-color: rgba(23,23,23,1);\n                        color: rgba(255,255,255,0.7); }\n                    ";

        var body_light_styles = settings.theme_fonts ? "\n                    body {\n                        line-height: normal;\n                        background-color: rgba(255,255,255,1);\n                        color: rgba(0,25,49,1);\n                    " :
            "\n                    body {\n                        font-family: 'Albert Sans', sans-serif;\n                        line-height: normal;\n                        background-color: rgba(255,255,255,1);\n                        color: rgba(0,25,49,1);\n                    ";

        if (editor.iframeElement === undefined) {
            setTimeout(function() {
                ChangeEditorDarkMode();
            }, 500);
        } else {
            var customStyles = dark_mode ? body_dark_styles : body_light_styles;
            var iframeDocument = editor.iframeElement.contentDocument || editor.iframeElement.contentWindow.document;
            var _styleElement = iframeDocument.createElement('style');
            _styleElement.innerHTML = customStyles;
            iframeDocument.head.appendChild(_styleElement);
        }

        var styleElement = document.createElement('style');
        var styles = dark_mode ? "\n                body .mce-container.mce-panel.mce-floatpanel {\n                    background-color: rgba(30,30,30,1);\n                    border: 1px solid rgba(255,255,255,.05);\n                    border-radius: 4px;\n                    color: rgba(255,255,255,1);\n                    margin-top: 3px;\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                    background-color: rgba(255,255,255,.05);\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                    background-color: rgba(255,255,255,.05);\n                }\n                " :
            "\n                body .mce-container.mce-panel.mce-floatpanel {\n                    background-color: rgba(255,255,255,1);\n                    border: 1px solid rgba(238,241,247,1);\n                    border-radius: 4px;\n                    color: rgba(0,25,49,1);\n                    margin-top: 3px;\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                    background-color: rgba(34,122,255,1);\n                    color: rgba(255,255,255,1);\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                    background-color: rgba(34,122,255,1);\n                    color: rgba(255,255,255,1);\n                }\n                ";
        styleElement.textContent = styles;
        document.head.appendChild(styleElement);
    }
}


