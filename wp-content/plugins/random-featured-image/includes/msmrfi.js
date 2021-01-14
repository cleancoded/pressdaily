/**
 * Plugin Name: Random Featured Image
 */
jQuery( document ).ready( function( $ ) {
	var customUploader;
	$( '#upload_image_button' ).on( 'click', function( event ){
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( customUploader ) {
			customUploader.open();
			return;
		}
		// Extend the wp.media
		customUploader = wp.media.frames.customUploader = wp.media({
			title: 'Select Images',
			button: {
				text: 'Use These Images',
			},
			multiple: 'add'
		});
		customUploader.on( 'select', function() {
			var oldIdList = $( '#msmrfi_field_imageid' ).val();
			var idList = [];
			var selection = customUploader.state().get( 'selection' );
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				$( '#selected_images' ).append('<div class="msmimage" style="float:left;margin: 10px;"><img style="width:100px;" src="' +attachment.url+ '"><br><a id="' +attachment.id+ '" href="#remove">' +msmrfi_text.remove_link+ '</a></div>');
				idList.push( attachment.id );
			});
			if ( oldIdList.length > 0) {
				$( '#msmrfi_field_imageid' ).val( oldIdList+','+idList.join() );
			} else {
				$( '#msmrfi_field_imageid' ).val( idList.join() );
			}
		});
		customUploader.open();
	});

	$( "#selected_images" ).on( "click", "a", function() {
		$( this ).parents( '.msmimage' ).addClass( 'hidden' );

		// Remove id from id list	
		var idToRemove = $( this ).attr( 'id' );		// get id from <a> id

		// Convert id string field to array
		var msmIds = $( '#msmrfi_field_imageid' ).val().split(',');

		// Array search/remove id
		msmIds = jQuery.grep(msmIds, function( n, i ) {
			  return ( n !== idToRemove );
		});
		// Convert array back to string & set field
		$( '#msmrfi_field_imageid' ).val( msmIds.join(',') );

	});

	var bypassWarning = false;
	$( '#submit' ).on( 'click', function() {
		bypassWarning = true;	
	});

	var msmrfiFieldImageId = $( '#msmrfi_field_imageid' ).val();
    $( '#msmrfi_field_imageid' ).data( 'old_value', msmrfiFieldImageId );

    window.onbeforeunload = function () {
    	if ( !bypassWarning ) {
	        if ($('#msmrfi_field_imageid').data('old_value') !== $('#msmrfi_field_imageid').val()) {
	            return 'You have unsaved changes!';
	        }
    	}
    }

});
