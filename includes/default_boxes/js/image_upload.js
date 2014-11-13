var $ = jQuery;

$(document).ready( function($){
	var file_frame;
	
	$('.imageContainer:first').find('a.removeImage').hide();
	
	$('body').on('click', '.upload_image', function( e ){
		e.preventDefault();
		
		var inpt = $(this).children('input');
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame = {};
		}
		
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Upload Image',
			button: {
			text: "Select",
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});
	
		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();
			$(inpt).attr( 'value', attachment.url );
		});
		
		// Finally, open the modal
		file_frame.open();
		
	});
	
	$('body').on('click', '.addImage', function(e) {
		e.preventDefault();
		$('.imageContainer:first').find('a.removeImage').show();
		$('.imageContainer:first').clone().appendTo('.imageWrapper');
		$('.imageContainer:first').find('a.removeImage').hide();
		$('.imageContainer:last').find('input').val('');
	});
	
	$('body').on('click', '.removeImage', function(e) {
		e.preventDefault();
		$(this).parent('.imageContainer').remove();
	});
});