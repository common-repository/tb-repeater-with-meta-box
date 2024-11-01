jQuery( document ).ready(function($) {
    // Add Row button
    $( 'body' ).on( 'click', '.custom-repeater-container .add-row-button', function(e) {
        e.preventDefault();

        var container = $( this ).closest( '.custom-repeater-container' );
        var fieldsContainer = container.find( '.custom-repeater-fields' );
        var newRow = fieldsContainer.find( '.custom-repeater-row:first' ).clone();

        // Clear input values in the new row
        newRow.find( 'input[type="text"]' ).val( '' );
        newRow.find( 'textarea').val( '' );
        newRow.find( '.custom-image-field' ).val( '' );
        newRow.find( '.custom-image-preview' ).attr( 'src', '' );

        // Append the new row to the fields container
        fieldsContainer.append(newRow);

        console.log(newRow.find('.custom-image-field').val());

    });

    // Remove Row button
    $( 'body' ).on( 'click', '.custom-repeater-container .remove-row-button', function(e) {
        e.preventDefault();

        var row = $(this).closest('.custom-repeater-row');
        row.remove();
    });

    // Upload Image button
    $( 'body' ).on( 'click', '.custom-repeater-container .upload-image-button', function(e) {
        e.preventDefault();
        // Add your image upload logic here
    });

    // Remove Image button
    $( 'body' ).on( 'click', '.custom-repeater-container .remove-image-button', function(e) {
        e.preventDefault();
        // Add your image removal logic here
    });
});