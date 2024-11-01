jQuery( document ).ready( function($) {
    // Media uploader
    var customUploader;

    // Handle image upload
    $( '.custom-repeater-container' ).on( 'click', '.upload-image-button', function(e) {
        e.preventDefault();

        var button = $( this );
        var customImageField = button.siblings( '.custom-image-field' );
        var customImagePreview = button.siblings( '.custom-image-preview' );

        // If the uploader object has already been created, reopen the dialog
        if ( customUploader ) {
            customUploader.open();
            return;
        }

        // Extend the wp.media object
        customUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        // When a file is selected, grab the URL and set it as the text field's value
        customUploader.on( 'select', function() {
            var attachment = customUploader.state().get( 'selection' ).first().toJSON();
            customImageField.val( attachment.url );
            customImagePreview.attr( 'src', attachment.url );
        });

        // Open the uploader dialog
        customUploader.open();
    });

    // Remove image
    $( '.custom-repeater-container' ).on( 'click', '.remove-image-button', function(e) {
        e.preventDefault();

        var button = $(this);
        var customImageField = button.siblings( '.custom-image-field' );
        var customImagePreview = button.siblings( '.custom-image-preview' );

        // Clear the image URL
        customImageField.val('');
        customImagePreview.attr( 'src', '' );
    });
});