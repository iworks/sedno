document.addEventListener("DOMContentLoaded", function(event) {
    var body = document.getElementsByTagName('body')[0];
    body.classList.add('loaded');
    if (body.classList.contains('single-post')) {
        var container = document.getElementsByClassName('social-media-share');
        if (0 < container.length) {
            var elements = container[0].getElementsByTagName('a');
            for (i = 0; i < elements.length; i++) {
                var element = elements[i];
                if (element.classList.contains('copy')) {
                    element.addEventListener('click', function(event) {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(this.dataset.url);
                            return false;
                        }
                        const area = document.createElement('textarea');
                        body.appendChild(area);
                        area.value = this.dataset.url;
                        area.select();
                        document.execCommand('copy');
                        body.removeChild(area);
                        return false;
                    });
                } else {
                    element.addEventListener('click', function(event) {
                        var width = (840 < window.screen.width) ? 800 : window.screen.width - 40;
                        var height = (640 < window.screen.height) ? 600 : window.screen.height - 40;
                        var left = (window.screen.width - width) / 2;
                        var top = (window.screen.height - height) / 2;
                        window.open(
                            this.href,
                            this.classList[0],
                            'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=' + height + ',width=' + width + ',top=' + top + ',left=' + left
                        );
                        return false;
                    });
                }
            }
        }
    }
});