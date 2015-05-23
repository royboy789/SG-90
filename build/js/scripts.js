var $ = jQuery;

$(document).ready(function(){
	$('.newBox').on('click', function(e) {
		var titleBox = $('input#newBoxTitle'),
		val = titleBox.val();
		
		if( val.length < 1 ) {
			e.preventDefault();
			titleBox.css({ 'border': '1px solid red' });
			alert( 'Please give yout new box a title' );	
		}
		
	});
	
	$('.deleteAll').on('click', function(e){
		var conf = confirm( 'Are you sure you want to delete all Style Guide data? ');
		if( !conf ) { e.preventDefault(); }	
	});
	
	if( $('#sortableOrder').length > 0 ){
		$('#sortableOrder').children('li').css({ 'cursor': 'move', 'background': '#FFF', 'text-align': 'center' });
		
		$('#sortableOrder').css({ 'height': $('#sortableOrder').outerHeight() + 20 }).sortable();
	}
	
});