<?php
interface StyleGuideSection {
	
	/* OVERWRITE FOR ADMIN CODE */
	public function admin( $post );
	
	/* OVERWRITE FOR FRONT END CODE */
	public function view( $sg_post_id );

}


class SG_Factory {

	public $sg_instances = array();
	
	public function register( $class_name ) {
		$box = new $class_name();
		$title = str_replace( ' ' , '_', $box->sg_title );
		
		$this->sg_instances[$title] = $box;

		return $box;
	}

}

?>