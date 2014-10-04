var $ = jQuery;

function colorTemp() {
	var clrtmp = '<div><input type="text" class="colorTitle" name="_sg_colors[colorTitle][]" placeholder="Color Title" />';
	clrtmp += '<span class="colorCMYK">';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_colors[colorC][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_colors[colorM][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_colors[colorY][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_colors[colorK][]" />';
	clrtmp += '</span>';
	clrtmp += '<span class="colorRGB">';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_colors[colorR][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_colors[colorG][]" />';
		clrtmp += '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_colors[colorB][]" />';
	clrtmp += '</span>';
	clrtmp += '<input type="text" class="color" placeholder="Hex Value" name="_sg_colors[colorHex][]" />';
	clrtmp += '<a href="#" class="removeColor">x</a>';
	clrtmp += '</div>';
	
	return clrtmp;
}

$(document).ready(function($){

	var colorCount = 1;
	
	// COLOR ADD & REMOVE
	$('.addColor').on('click', function(e){
		e.preventDefault();
		$('div.colors').append(colorTemp());
	});
	
	$('body').on('click', 'a.removeColor', function(e){
		e.preventDefault();
		$(this).parent('div').remove();
	});

});