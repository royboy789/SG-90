<?php
	
class sg90Activation {
	static function install() {
		if( !get_option( '_sg_bootstrap' ) )
			update_option( '_sg_bootstrap', 'on' );     
	}
}
register_activation_hook( SG90_PLUGINPATH, array( 'sg90Activation', 'install' ) );

?>