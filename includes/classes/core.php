<?php
abstract class StyleGuideSection {
	/* TURN ON WP_DEBUG in your wp-config.php to view debugging meta box */
	
	public $sg_post;
	public $sg_admin_title = 'Title';
	
	/*
	IF YOU MODIFY THE CONSTRUCT FUNCTION MAKE SURE TO CALL
	parent::__construct();	
	
	*/
	
	function __construct( $title ){
	
		// Set instance title
		if( $title ) { $this->sg_admin_title = $title; }
		
		// Get ID from parameters 
		if( isset( $_GET['post'] ) ) {
			$this->sg_post = $_GET['post'];
		}		
	}

	/* OVERWRITE FOR ADMIN CODE */
	abstract function admin( $post );
	
	/* OVERWRITE FOR FRONT END CODE */
	abstract function view( $sg_post_id );

}


class SG_Factory {

	static public $sg_instances = array();
	
	public function register( $name, $class_name ) {
	
		self::$sg_instances[$name] = $class_name;

	}

}
?>