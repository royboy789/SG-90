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

define( 'SG60_PLUGINPATH', plugin_dir_path( __FILE__ ) );
define( 'SG60_PLUGINURL', plugins_url( '', __FILE__ ) );

require('includes/classes/sg_cpt_tax.php');
require('includes/admin/settings.php');
require('includes/admin/shortcodes.php');
//require('includes/admin/meta.php');


class StyleGuideCreator {
	
	function __construct(){
		global $wpdb;
		
		new sgInit();
		new styleAdmin();
		new styleGuideShortcodes();
		
		// SAVE
		add_action( 'save_post', array( $this, 'metaSave' ) );
	}

	private function save() {
		// if( 'style-guides' == get_post_type( $post_id ) && !empty( $_POST ) ):
		// endif;
	}

	public function _sg_box( $sg_boxTitle, $sg_metaHTML, $sg_feHTML ) {
		//add_meta_box( $sg_boxTitle, __( $sg_boxTitle, 'myplugin_textdomain' ), function() { echo $sg_metaHTML; }, 'style-guides', 'normal', 'high' );
	}
	
}

$sg90 = new StyleGuideCreator();

$sg90->_sg_box( 'Testing', '<p>hi</p>', '' );

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