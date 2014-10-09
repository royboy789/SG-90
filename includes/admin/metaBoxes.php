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
		echo '<label for="_new-title">Title:</label><br/><input name="_new-title" placeholder="Title" required /><Br/><br/>';
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
				$sectionClass = str_replace( '_new-sg-', '', $section['class'] );
				
				$class_index = findSgClass( $sectionClass );
				$class = StyleGuideCreator::$sg_instances[$class_index]['object'];
				$newMeta = new $class( $section['title'], false );
				array_push( StyleGuideCreator::$sg_boxes_set, $newMeta );
				
				add_meta_box( 
					$newMeta->sg_title . 'Section: '. $i, 
					__( $newMeta->sg_title, 'myplugin_textdomain' ), 
					array( $newMeta, 'admin' ),
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