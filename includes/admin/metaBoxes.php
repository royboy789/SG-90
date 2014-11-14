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
			'Style Guide Debug', 
			__( 'Style Guide Debugging', 'myplugin_textdomain' ), 
			array( $this, '_sg_allmeta' ), 
			'style-guides', 
			'normal', 
			'low' 
		);	
	}

	public function _sg_allmeta() {
		echo '<p><em>Turns on when WP_DEBUG is set to true</em></p>';
		foreach( get_post_meta( intval( $_GET['post'] ) ) as $key => $value ){
			if( strpos( $key, '_sg_' ) === 0 ) {
				echo '<strong>'.$key.'</strong>:<br/>';
				var_dump( get_post_meta( intval( $_GET['post'] ), $key, true ) );
				echo '<br/>';
			}
		}
	}
	
	function styleGuideSections() {
		echo '<label for="_new-title">Title:</label><br/><input name="_new-title" placeholder="Title" id="newBoxTitle" /><Br/><br/>';
		foreach( StyleGuideCreator::$sg_instances as $box ) {
			echo ' <button name="_new-sg-' . $box['title'] . '" class="button button-primary newBox" value="1">New ' . str_replace( '_', ' ', $box['title'] ) . '</button>'; 
		}
		echo ' <button name="_delete-all-sg" class="button button-secondary deleteAll" value="1">Reset Style Guide</button>'; 
	}
	
	function styeGuideOrder( $post, $data ) {
		$i = 1;
		echo '<p><em>Drag and Drop to reorder the Style Guide sections when they are displayed using the shortcode</em></p>';
		echo '<ul id="sortableOrder">';
		foreach( $data['args']['sections'][0] as $section ) {
			echo '<li data-orig="'.$i.'" style="padding:5px;border:1px solid gray"><strong>'.str_replace( '_', ' ', $section['title'] ).'</strong><input name="_reorder_sg[]" type="hidden" value="'.$i.'" min="1" max="'.count( $data['args']['sections'][0] ).'" /></li>';
		$i++;
		}
		echo '</ul>';
	}
	
	function _metaInit() {
		add_meta_box( 
			'Add Style Guide Section', 
			__( 'Add Style Guide Section', 'myplugin_textdomain' ), 
			array( $this, 'styleGuideSections' ), 
			'style-guides', 
			'normal', 
			'high'
		);
		
		$sections = get_post_meta( intval( $_GET['post'] ), '_sg_sections', false );
		if( !empty( $sections ) ):
			if( count( $sections[0] ) > 1 ){
				add_meta_box(
					'Style Guide Order',
					__( 'Style Guide Order', 'myplugin_textdomain' ),
					array( $this, 'styeGuideOrder' ),
					'style-guides',
					'side',
					'default',
					array( 'sections' => $sections )
				);
			}
			$i = 1;
			foreach( $sections[0] as $section ) {
				$sectionClass = str_replace( '_new-sg-', '', $section['class'] );
				
				$class_index = findSgClass( $sectionClass );
				$class = StyleGuideCreator::$sg_instances[$class_index]['object'];
				$newMeta = new $class( $section['title'], false );
				array_push( StyleGuideCreator::$sg_boxes_set, $newMeta );
				
				add_meta_box( 
					$newMeta->sg_title . 'Section: '. $i, 
					__( str_replace( '_', ' ', $newMeta->sg_title ), 'myplugin_textdomain' ), 
					array( $this, '_adminContent' ),
					'style-guides', 
					'normal', 
					'default',
					array( 'classFunc' => $newMeta, 'index' => $i )
				);
				$i++;	
			}
		endif;
	}
	
	function _adminContent( $post, $data ) {
		echo $data['args']['classFunc']->admin( $post );
		echo '<br/><br/><button class="button button-primary" name="_remove_section" value="'.$data['args']['index'].'">Remove Section</a>';
	}
}

?>