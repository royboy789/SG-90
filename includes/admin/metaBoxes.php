<?php
class _sg_meta_boxes {
	
	function __construct() {
		
		if( $_GET && isset( $_GET['post'] ) )
			$sg_post = get_post( intval( $_GET['post'] ) );
		
		if( isset( $sg_post ) && $sg_post->post_status == 'publish' ) {
			add_action( 'add_meta_boxes', array( $this, '_metaInit' ) );	
		} else {
			add_action( 'add_meta_boxes', array( $this, '_metaPreSave' ) );	
		}
		
		if( WP_DEBUG && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'style-guides' ) {
			add_action( 'add_meta_boxes', array( $this, '_allMetaInit' ) );
		}
		
	}
	
	/** PRE SAVE **/
	public function _metaPreSave() {
		add_meta_box( 
			'Save Style Guide', 
			__( 'New Style Guide', 'myplugin_textdomain' ), 
			array( $this, '_sg_preSave' ), 
			'style-guides', 
			'normal', 
			'low' 
		);	
	}
	
	function _sg_preSave() {
		echo '<h2>New Style Guide Step 1</h2>';
		echo '<p>To start adding and customizing sections to your new style guide, please title and publish the style guide</p>';
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
		global $SG_Factory;
		echo '<label for="_new-title">Label:</label><br/><input name="_new-title" placeholder="Title" id="newBoxTitle" /><Br/><br/>';
		foreach( $SG_Factory->sg_instances as $key => $value ) {
			echo ' <button name="_new-sg-' . $key . '" class="button button-primary newBox" value="1">New ' . str_replace( '_', ' ', $key ) . '</button>'; 
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
		global $SG_Factory;
		
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
				
				if( isset( $SG_Factory->sg_instances[$sectionClass] ) ):
					$newMeta = new $SG_Factory->sg_instances[$sectionClass];
					$newMeta->sg_admin_title = $section['title'];
					add_meta_box( 
						$section['title'] . 'Section: '. $i, 
						__( str_replace( '_', ' ', $section['title'] ), 'myplugin_textdomain' ), 
						array( $this, '_adminContent' ),
						'style-guides', 
						'normal', 
						'default',
						array( 'classFunc' => $newMeta, 'index' => $i )
					);
				endif;
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