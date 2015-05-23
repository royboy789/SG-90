var $ = jQuery;

function colorTemp(title) {
	var clrtmp = '<div><input type="text" class="colorTitle" name="_sg_' + title + '_[colorTitle][]" placeholder="Color Title" />';
	clrtmp += '<span class="colorCMYK">';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_' + title + '_[colorC][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_' + title + '_[colorM][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_' + title + '_[colorY][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_' + title + '_[colorK][]" />';
	clrtmp += '</span>';
	clrtmp += '<span class="colorRGB">';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_' + title + '_[colorR][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_' + title + '_[colorG][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_' + title + '_[colorB][]" />';
	clrtmp += '</span>';
	clrtmp += '<input type="text" class="color" placeholder="Hex Value" name="_sg_' + title + '_[colorHex][]" />';
	clrtmp += '<a href="#" class="removeColor">x</a>';
	clrtmp += '</div>';
	
	return clrtmp;
}

$(document).ready(function($){

	var colorCount = 1;
	
	// COLOR ADD & REMOVE
	$('.addColor').on('click', function(e){
		e.preventDefault();
		$('div#'+$(this).data('title')).append( colorTemp( $(this).data('title') ) );
	});
	
	$('body').on('click', 'a.removeColor', function(e){
		e.preventDefault();
		$(this).parent('div').remove();
	});

});