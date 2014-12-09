<?php

class sg_text_box implements StyleGuideSection {
	
	function __construct() {
		$this->sg_title = 'Text Box';
		$this->sg_admin_title = 'Text Box';
	}

	public function admin( $post ) {
		$post_id = $post->ID;
		echo '<h2>'.$this->sg_admin_title.'</h2>';		
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		
		wp_editor( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_textBox', true ), '_sg_'.$this->sg_admin_title.'_textBox', array( 'textarea_name' => '_sg_'.$this->sg_admin_title.'_textBox' ) );
		
	}
	
	public function view( $post_id ) {
		$content = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_textBox', true );
		$template = '<div class="textBoxWrapper">';
			$template .= nl2br( $content );
		$template .= '</div>';
		
		return $template;
	}

}

$SG_Factory->register( 'sg_text_box' );
?>