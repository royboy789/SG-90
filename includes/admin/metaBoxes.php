<?php

class _sg_meta_boxes {
	
	function __construct() {
		add_action( 'add_meta_boxes', array( $this, '_metaInit' ) );
		
		if( WP_DEBUG && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'style-guides' ) {
			add_action( 'add_meta_boxes', array( $this, '_allMetaInit' ) );
		}
	}
	
	public function _allMetaInit() {
		add_meta_box( 
			'AllMeta', 
			__( 'AllMeta', 'myplugin_textdomain' ), 
			array( $this, '_sg_allmeta' ), 
			'style-guides', 
			'normal', 
			'low' 
		);	
	}

	public function _sg_allmeta() {
		foreach( get_post_meta( intval( $_GET['post'] ) ) as $key => $value ){
			if( strpos( $key, '_sg_' ) === 0 ) {
				echo '<strong>'.$key.'</strong>:<br/>';
				var_dump( get_post_meta( intval( $_GET['post'] ), $key, true ) );
				echo '<br/>';
			}
		}
	}
	
	function styleGuideSections() {
		foreach( StyleGuideCreator::$sg_instances as $box ) {
			echo ' <button name="_new-sg-' . $box['title'] . '" class="button button-primary" value="1">New ' . $box['title'] . '</button>'; 
		}
		echo ' <button name="_delete-all-sg" class="button button-secondary" value="1">Delete All Data and Boxes</button>'; 
	}
	
	function _metaInit() {
		add_meta_box( 
			'Style Guide Sections', 
			__( 'Style Guide Sections', 'myplugin_textdomain' ), 
			array( $this, 'styleGuideSections' ), 
			'style-guides', 
			'normal', 
			'high'
		);
		
		$sections = get_post_meta( intval( $_GET['post'] ), '_sg_sections', false );
		if( !empty( $sections ) ):		
			$i = 1;
			foreach( $sections[0] as $section ) {
				$section = str_replace( '_new-sg-', '', $section );
				
				$class_index = findSgClass( $section );
				add_meta_box( 
					$section . 'Section: '. $i, 
					__( $section, 'myplugin_textdomain' ), 
					array( StyleGuideCreator::$sg_instances[$class_index]['object'], 'admin' ),
					'style-guides', 
					'normal', 
					'high'
				);
				$i++;	
			}
		endif;
	}
}

?>