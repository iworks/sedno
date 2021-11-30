;
window.addEventListener('load', function() {
    var int_505_slider_z_index = 10;
    var int_505_slider_slider_timeout = 8000;
    var int_505_slider_main_slider = document.getElementById('main-slider');
    var int_505_slider_slider_menu;
    var int_505_slider_slider_elements;
    var int_505_slider_current_index = -1;
    if (null === int_505_slider_main_slider) {
        return;
    }
    int_505_slider_slider_menu = int_505_slider_main_slider.getElementsByTagName('nav');
    if (null === int_505_slider_slider_menu) {
        return;
    }
    int_505_slider_slider_menu = int_505_slider_slider_menu[0];
    int_505_slider_slider_elements = int_505_slider_slider_menu.getElementsByTagName('li');
    if (1 > int_505_slider_slider_elements.length) {
        return;
    }
    /**
     * Switch to helper
     */
    function int_505_slider_slider_switch_to(index) {
        var li = int_505_slider_slider_elements[index];
        var el = li.getElementsByTagName('a')[0];
        var id = el.dataset.id;
        var articles = int_505_slider_main_slider.getElementsByTagName('article');
        var article = document.getElementById('post-' + id);
        /**
         * set menu
         */
        for (var i = 0; i < int_505_slider_slider_elements.length; i++) {
            int_505_slider_slider_elements[i].classList.remove('active');
        }
        li.classList.add('active');
        /**
         * set article
         */
        for (var i = 0; i < articles.length; i++) {
            articles[i].classList.remove('active');
        }
        article.classList.add('active');
        article.style.zIndex = int_505_slider_z_index;
        article.style.backgroundImage = 'url(' + article.dataset.int5o5src + ')';
        /**
         * increment
         */
        int_505_slider_z_index++;
        clearTimeout(window.int_505_slider_slider_timeout_object);
        window.int_505_slider_slider_timeout_object = setTimeout(window.int_505_slider_slider, int_505_slider_slider_timeout);
    }
    /**
     * handle autoswitch
     */
    window.int_505_slider_slider = function() {
        /**
         * sanitize index
         */
        if (0 > parseInt(int_505_slider_current_index)) {
            int_505_slider_current_index = 1;
        }
        if (int_505_slider_current_index >= int_505_slider_slider_elements.length ) {
            int_505_slider_current_index = 0;
        }
        /**
         * run Forest!
         */
        int_505_slider_slider_switch_to(int_505_slider_current_index++);
    };
    /**
     * first run
     */
    window.int_505_slider_slider_timeout_object = setTimeout(int_505_slider_slider, int_505_slider_slider_timeout);
    /**
     * attach class
     */
    int_505_slider_slider_elements[0].classList.add('active');
    int_505_slider_main_slider.getElementsByTagName('article')[0].style.backgroundImage = 'url(' + int_505_slider_main_slider.getElementsByTagName('article')[0].dataset.int5o5src + ')';
    /**
     * bind Menu click
     */
    for (var i = 0; i < int_505_slider_slider_elements.length; i++) {
        int_505_slider_slider_elements[i].getElementsByTagName('a')[0].addEventListener('click', function() {
            int_505_slider_slider_switch_to(this.dataset.index);
            return false;
        }, {passive: true});
    }
}, {passive: true});

