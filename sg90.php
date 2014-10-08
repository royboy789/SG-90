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
require('includes/classes/shortcodes.php');
require('includes/admin/settings.php');
require('includes/admin/metaBoxes.php');

class StyleGuideCreator {
	/* TURN ON WP_DEBUG in your wp-config.php to view debugging meta box */
	
	public $sg_post;
	public $sg_title  = 'New Box';
	public static $sg_instances = [];

	/*
		IF YOU MODIFY THE CONSTRUCT FUNCTION MAKE SURE TO CALL
			parent::__construct();	
			
	*/

	function __construct(){			
		array_push( $this::$sg_instances, array( 'object' => $this, 'title' => $this->sg_title ) );

		if( isset( $_GET['post'] ) ) {
			$this->sg_post = $_GET['post'];
		}
	}

	/* OVERWRITE FOR ADMIN CODE */
	public function admin( $post ) {
		echo '<h3>New Meta Box</h3>';
	}
	
	/* OVERWRITE FOR FRONT END CODE */
	public function view( $sg_post_id ) {
		return '<h2>'.$sg_post_id.'</h2>';
	}
		
}

new sgInit();
new _sg_settings();
new _sg_meta_boxes();
new styleGuideShortcodes();

foreach (glob( SG60_PLUGINPATH."/includes/default_boxes/*.php") as $filename) {
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

function findSgClass( $title ) {
   foreach ( StyleGuideCreator::$sg_instances as $key => $val ) {
       if ( $val['title'] === $title ) {
		   return $key;
       }
   }
   return null;
}

?>