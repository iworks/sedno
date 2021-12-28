(function($) {
    function iworks_post_media_add_bind(obj, event, $container) {
        var file_frame;
        var wp_media_post_id;
        var set_to_post_id = $('#post_ID').val();
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if (file_frame) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param('post_id', set_to_post_id);
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            var template = wp.template('iworks-media-file-row');
            // We set multiple to false so only get one image from the uploader
            var attachment = file_frame.state().get('selection').first().toJSON();
            $container.append(template(attachment));
            $('button', $container).on('click', function() {
                $(this).closest('.iworks-media-file-row').detach();
            });
            /**
             * Restore the main post ID
             */
            wp.media.model.settings.post.id = wp_media_post_id;
        });
        // Finally, open the modal
        file_frame.open();
    }
    $(document).ready(function() {
        var $iworks_media_containers = $('.iworks-media-container');
        $iworks_media_containers.each(function(index, iworks_single_container) {
            var $iworks_single_container = $(iworks_single_container);
            $('.button-add-file', $iworks_single_container).on('click', function(event) {
                iworks_post_media_add_bind(this, event, $iworks_single_container);
                return false;
            });
            $('button', $iworks_single_container).on('click', function() {
                $(this).closest('.iworks-media-file-row').detach();
            });
        });
    });
}(jQuery));