<?php

class styleGuideShortcodes {
	
	function __construct() {
		add_shortcode( 'sg-90', array( $this, 'sgShortcode' ) );
	}
	
	function sgShortcode( $atts ) {

		$a = shortcode_atts( array(
			'id' => ''
		), $atts );
		
		if( empty( $a['id'] ) ) return '<p>Please define a SG-90 Style Guide ID in your shortcode <code>[sg60 id="..."]</code></p>';
		
		$sg = get_post( intval( $a['id'] ) );
		
		if( $sg && $sg->post_type == 'style-guides' ) :

			return '<p>Here</p>';
			
		else:
			
			return '<p>Cannot find a Style Guide with that ID</p>';
		
		endif;
		
	}

}

?>