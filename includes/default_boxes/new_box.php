<?php

class sg_new_box extends StyleGuideCreator {
	public $sg_title = 'Hello';
	
	public function admin( $post ) {
		$post_id = $post->ID;
		
		echo '<h2>HI</h2>';
	}
	
	

}	

new sg_new_box();
?>