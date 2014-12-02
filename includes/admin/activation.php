<?php

class sg90Activation {
	function __construct() {
		register_activation_hook( SG90_PLUGINPATH.'sg90.php', array( $this, 'install' ) );
	}
	function install() {
		if( !get_option( '_sg_bootstrap' ) )
			update_option( '_sg_bootstrap', 'on' );     
	}
}

?>