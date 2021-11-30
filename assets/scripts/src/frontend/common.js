/*
(function($) {
    $(document).ready(function() {
        $('body').addClass('loaded');
        $('.social-media-share a').on('click', function(e) {
            var width = (840 < window.screen.width) ? 800 : window.screen.width - 40;
            var height = (640 < window.screen.height) ? 600 : window.screen.height - 40;
            var left = (window.screen.width - width) / 2;
            var top = (window.screen.height - height) / 2;
            e.preventDefault();
            window.console.log('menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=' + height + ',width=' + width + ',top=' + top + ',left=' + left);
            window.open(
                $(this).attr('href'),
                $(this).data('social'),
                'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=' + height + ',width=' + width + ',top=' + top + ',left=' + left
            );
            return false;
        });
    });
})(jQuery);
*/
