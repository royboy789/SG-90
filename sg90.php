<?php
/**
 * Plugin Name: SG-90 - Style Guide Creator
 * Plugin URI: http://arcctrl.com/plugins/sg-90
 * Description: This plugin will allow you to easily create style guide for your clients
 * Version: 1.2
 * Author: ARC(CTRL)
 * Author URI: http://www.arcctrl.com
 * License: GPL2
 */

define( 'SG90_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'SG90_PLUGINURL', plugins_url( '', __FILE__ ) );

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
new _sg_settings();
new _sg_meta_boxes();
new styleGuideShortcodes();
new pluginLicense();


/** ADMIN CSS **/
if( is_admin() )
	add_action( 'admin_enqueue_scripts', 'adminScripts' );

function adminScripts() {
	wp_enqueue_style( '_sg90_css', SG90_PLUGINURL.'/includes/default_boxes/css/sg90_admin_css.css', '', '1.0', 'all' );
}

/** INCLUDE ALL DEFAULT BOXES  **/
foreach (glob( SG90_PLUGINPATH."/includes/default_boxes/*.php") as $filename) {
	require $filename;
}

/** REGISTER SCRIPS **/
new SG_Register();

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

?>