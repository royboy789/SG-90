<?php
abstract class StyleGuideSection {
	/* TURN ON WP_DEBUG in your wp-config.php to view debugging meta box */
	
	public $sg_post;
	public $sg_admin_title = 'Title';
	public $sg_view_scripts = array();
	
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
		$name = $newTitle = str_replace( ' ' , '_', $name );
		self::$sg_instances[$name] = $class_name;
	}
	
	

}

class SG_Register {
	
	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'allScripts' ) );
	}
	
	function allScripts() {
		foreach( SG_Factory::$sg_instances as $key => $class ):
			$class = new $class('','');
			if( !empty( $class->sg_view_scripts ) ):
				foreach( $class->sg_view_scripts as $key => $value ){
					if( strpos( $value, '.js' ) && strpos( $value, '.js' ) > 1 ):
						wp_register_script( $key, $value, array( 'jquery' ), null, true );
					else:
						wp_register_style( $key, $value, '', '1.0', 'all' );
					endif;
				}
			endif;
		endforeach;
	}
}
?>