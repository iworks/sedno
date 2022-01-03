/*! SEDNO - v1.0.0
 * 
 * Copyright (c) 2022;
 * Licensed GPLv2+
 */
(function($) {
    $(document).ready(function() {
        var searchRequest;
        $('.iworks-most-important').sortable();
        $('.iworks-most-important input[type=text]').autocomplete({
            minChars: 2,
            source: function(term, suggest) {
                try {
                    searchRequest.abort();
                } catch (e) {}
                searchRequest = $.post(
                    ajaxurl, {
                        search: term.term,
                        action: 'iworks_posts_search',
                        nonce: $('#_sedno_most_important_nonce').val()
                    },
                    function(res) {
                        suggest(res.data);
                    }
                );
            },
            select: function(event, ui) {
                var $parent = $(this).closest('li');
                $('.image', $parent).html(
                    '<img src="' + ui.item.thumbnail + '" width="80" />'
                );
                $('h3', $parent).html(ui.item.label);
                $('.excerpt', $parent).html(ui.item.excerpt);
                $('.id', $parent).val(ui.item.id);
            }
        });
    });
}(jQuery));