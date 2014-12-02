<?php
	
class sg90Activation {
	function __construct() {
		register_activation_hook( SG90_PLUGINPATH, array( $this, 'install' ) );
	}
	function install() {
		if( !get_option( '_sg_bootstrap' ) )
			update_option( '_sg_bootstrap', 'on' );     
	}
}

?>