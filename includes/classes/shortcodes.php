<?php

class styleGuideShortcodes {
	
	function __construct() {
		add_shortcode( 'SG-90', array( $this, 'sgShortcode' ) );
		
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
			
			$sections = get_post_meta( $sg->ID, '_sg_sections', false );
			//var_dump( $sections );
			if( !empty( $sections ) ):
				$tmpl = '';
				
				foreach( $sections[0] as $section ) {
					$sectionClass = str_replace( '_new-sg-', '', $section['class'] );
					$class_index = findSgClass( $sectionClass );
					// CALL CLASS
					$class = StyleGuideCreator::$sg_instances[$class_index]['object'];
					$newMeta = new $class( $section['title'], false );
					//var_dump( $newMeta->sg_admin_title );
					// CALL CLASS VIEW FUNCTION
					$tmpl .= $newMeta->view( $sg->ID );
				}
				return $tmpl;
			else:
				$tmpl = '<p>Style Guide is empty</p>';
				return $tmpl;
			endif;
			
			/*
$output = '';
			
			foreach( StyleGuideCreator::$sg_instances as $sg_instance ) {
				$output .= $sg_instance->view( $sg->ID );
			}
			
			return $output;
*/
			
			
		else:
			
			return '<p>Cannot find a Style Guide with that ID</p>';
		
		endif;
		
	}

}

?>