<?php
/**
 * Plugin Name: SG-90 - Style Guide Creator
 * Plugin URI: http://arcctrl.com/plugins/sg-90
 * Description: This plugin will allow you to easily create style guide for your clients
 * Version: 1.0
 * Author: ARC(CTRL)
 * Author URI: http://www.arcctrl.com
 * License: GPL2
 */

define( 'SG90_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'SG90_PLUGINURL', plugins_url( '', __FILE__ ) );
define( 'SG90_CORE', plugin_dir_path( __FILE__ ).'/includes/classes/core.php' );

/** CORE CLASS & FACTORY **/
require('includes/classes/core.php');
$SG_Factory = new SG_Factory();

/** REGISTER CPT & TAX **/
require('includes/classes/sg_cpt_tax.php');

/** SHORTCODES **/
require('includes/classes/shortcodes.php');

/** SETTINGS & METABOXES  **/
require('includes/admin/settings.php');
require('includes/admin/metaBoxes.php');

/** EDD LICENSING **/
require('includes/admin/licensing.php');
require('includes/admin/activation.php');

/** INIT CLASSES **/
new sgInit();
new sg90Activation();
new _sg_settings();
new _sg_meta_boxes();
new styleGuideShortcodes();
new pluginLicense();


/** ADMIN CSS **/
if( is_admin() )
	add_action( 'admin_enqueue_scripts', 'adminScripts' );

function adminScripts() {
	global $post;
	if( isset( $post ) && $post->post_type === 'style-guides' ):
		wp_enqueue_script( 'sg90_admin_js', SG90_PLUGINURL.'/includes/default_boxes/js/sg90_admin_js.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( '_sg90_css', SG90_PLUGINURL.'/includes/default_boxes/css/sg90_admin_css.css', '', '1.0', 'all' );
	endif;
}


/** INCLUDE ALL DEFAULT BOXES  **/

foreach (glob( SG90_PLUGINPATH."/includes/default_boxes/*.php") as $filename) {
	require $filename;
}

function get_attach_id( $url ) {
	$parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}

	global $wpdb;

	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
	return $attachment[0];
}

function ensure_loaded_first( array $plugins ) {
    $key = array_search( plugin_basename( __FILE__ ), $plugins);
    if (false !== $key) {
        array_splice($plugins, $key, 1);
        array_unshift($plugins, plugin_basename( __FILE__ ));
    }
    return $plugins;
}

add_filter('pre_update_option_active_plugins', 'ensure_loaded_first');

?>