<?php

class sg_button_box extends StyleGuideCreator {
	public $sg_title = 'Buttons';
	
	public function admin( $post ) {
		$post_id = $post->ID;
		$html = '<h2>'.str_replace( '_', ' ', $this->sg_admin_title ).'</h2>';		
		$html .= '<p><em>Buttons default to Twitter Bootstrap classes (.btn, .btn-primary)</em></p>';
		
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );		
		$color = '';
		if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_button', true ) ) {
			$color = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_button', true );
		}
		$html .= '<input value="'.$color.'" name="_sg_'.$this->sg_admin_title.'_button" type="text" placeholder="Color (hex)" />';
		
		return $html;		
		
	}
	
	public function view( $post_id ) {
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		$buttons = array( 'btn-lg' => 'Button Large', 'btn-primary' => 'Button Primary', 'btn-sm' => 'Button Small' );
		$color = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_button', true );
		
		echo '<div class="row"><div class="col-md-12 text-center">';
		foreach( $buttons as $key => $value ){
			echo '<div class="btn btn-primary '.$key.'" style="background-color:'.$color.';margin:10px 0;">'.$value.'</div><br/>';	
		}
		echo '</div></div>';
	}
	
	

}	

new sg_button_box( 'Button Box' );
?>