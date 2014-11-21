<?php

class sg_text_box extends StyleGuideSection {

	public function admin( $post ) {
		$post_id = $post->ID;
		echo '<h2>'.$this->sg_admin_title.'</h2>';		
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		
		wp_editor( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_textBox', true ), '_sg_'.$this->sg_admin_title.'_textBox', array( 'textarea_name' => '_sg_'.$this->sg_admin_title.'_textBox' ) );
		
	}
	
	public function view( $post_id ) {
		$content = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_textBox', true );
		echo nl2br( $content );
	}
	
	

}	

$init = new SG_Factory();
$init->register( 'Text Box', 'sg_text_box' );
?>