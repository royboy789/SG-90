var $ = jQuery;
$(document).ready(function(){
	$('a.sg_template_choice').on('click', function(e) {
		if( $('a.sg_template_choice.chosen').length > 0 ) {
			$('a.sg_template_choice.chosen').removeClass('chosen');
		}
		$('input[name="_sg_template"]').val( $(this).data('template') );
		$(this).addClass('chosen');
	});
	
	$('form#newSG').on('submit', function(e) {
		if( $('input[name="_sg_template"]').length < 2 ) {
			e.preventDefault();
			$(this).append( '<p style="color:red">Please Choose a Template above</p>' );	
		}
	});
	
});