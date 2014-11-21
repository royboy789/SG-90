<?php
interface StyleGuideSection {
	
	public $sg_admin_title = 'Title';
	public $sg_view_scripts = array();

	/* OVERWRITE FOR ADMIN CODE */
	public function admin( $post );
	
	/* OVERWRITE FOR FRONT END CODE */
	public function view( $sg_post_id );

}


class SG_Factory {

	private $sg_instances = array();
	
	public function register( $class_name ) {
		$box = new $class_name();
		array_push($this->$sg_instances, $box);

		$this->addScripts($box);

		return $box;
	}
	
	private function addScripts($box) {
		// @TODO: add_action() goes here?
		if( !empty( $box->sg_view_scripts ) ):
			foreach( $box->sg_view_scripts as $key => $value ){
				if( strpos( $value, '.js' ) && strpos( $value, '.js' ) > 1 ):
					wp_register_script( $key, $value, array( 'jquery' ), null, true );
				else:
					wp_register_style( $key, $value, '', '1.0', 'all' );
				endif;
			}
		endif;
	}

}

// @TODO: Delete this class and refactor the logic into the addScripts() above
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