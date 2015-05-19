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
		global $sg90_shortcode_html, $SG_Factory;
		
		$a = shortcode_atts( array(
			'id' => ''
		), $atts );
		
		if( empty( $a['id'] ) ) return '<p>Please define a SG-90 Style Guide ID in your shortcode <code>[SG-90 id="..."]</code></p>';
		
		$sg = get_post( intval( $a['id'] ) );
		
		if( $sg && $sg->post_type == 'style-guides' ) :
			
			$html = '<div id="sg90Wrapper">';
			$sections = get_post_meta( $sg->ID, '_sg_sections', false );
			
			if( empty( $sections ) ) { return '<p>This Style Guide is empty, add some Style Guide Sections</p>'; }
			
			foreach( $sections[0] as $section ) {
				$className = str_replace( '_new-sg-', '', $section['class'] );
				if( ! isset( $SG_Factory->sg_instances[$className] ) ) { continue; }
				$newMeta = new $SG_Factory->sg_instances[$className];
					$newMeta->sg_admin_title = $section['title'];
				$html .= $newMeta->view( $sg->ID );
			}
			$html .= '</div>';
			return $html;
		else:			
			return '<p>Cannot find a Style Guide with that ID</p>';
		
		endif;
		
	}

}

?>