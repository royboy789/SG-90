<?php

class styleGuideShortcodes {
	
	function __construct() {
		add_shortcode( 'SG-90', array( $this, 'sgShortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'viewScripts' ) );
		
	}
	
	/* Front End CSS */
	public function viewScripts() {
		wp_enqueue_style( '_sg90_css', SG90_PLUGINURL.'/includes/default_boxes/css/sg90_css.css', '', '1.0', 'all' );
	}
	
	function sgShortcode( $atts ) {
		global $sg90_shortcode_html;

		$a = shortcode_atts( array(
			'id' => ''
		), $atts );
		
		if( empty( $a['id'] ) ) return '<p>Please define a SG-90 Style Guide ID in your shortcode <code>[SG-90 id="..."]</code></p>';
		
		$sg = get_post( intval( $a['id'] ) );
		
		if( $sg && $sg->post_type == 'style-guides' ) :
			
			//delete_post_meta( $sg->ID, '_sg_sections' );
			
			var_dump( SG_Factory::$sg_instances );
			$sections = get_post_meta( $sg->ID, '_sg_sections', false );
			
			foreach( $sections[0] as $section ) {
				$className = str_replace( '_new-sg-', '', $section['class'] );

				$class = SG_Factory::$sg_instances[$className];
				$newMeta = new $class( $section['title'], false );
				$newMeta->view( $sg->ID );
			}
			
			
		else:
			
			return '<p>Cannot find a Style Guide with that ID</p>';
		
		endif;
		
	}

}

?>