<?php

class sg_new_box implements StyleGuideSection {
	$this->sg_admin_title = 'Test';
	$this->sg_view_scripts = array();
	
	public function admin( $post ) {
		$post_id = $post->ID;
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		
		echo '<h2>'.$this->sg_admin_title.' Box</h2>';
		echo '<input name="_sg_'.$this->sg_admin_title.'_newBox" placeholder="Testing"';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_newBox', true ) )
				echo 'value="'.get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_newBox', true ).'"';
		echo ' />';
		
	}
	
	public function view( $post_id ) {
		echo get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_newBox', true ).'<br>';
	}

}

// $SG_Factory->register( 'sg_new_box' );
?>