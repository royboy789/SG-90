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
		array_push( $this::$sg_instances, $this );

		if( isset( $_GET['post'] ) ) {
			$this->sg_post = $_GET['post'];
		}
		
		add_action( 'add_meta_boxes', array( $this, '_metaInit' ) );
		
		if( WP_DEBUG && get_post_meta( $this->sg_post ) ) {
			add_action( 'add_meta_boxes', array( $this, '_allMetaInit' ) );
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
	
	
	
	/*
	
	DO NOT OVERWRITE FUNCTIONS BELOW
	
	*/

	public function _allMetaInit() {
		add_meta_box( 
			'AllMeta', 
			__( 'AllMeta', 'myplugin_textdomain' ), 
			array( $this, '_sg_allmeta' ), 
			'style-guides', 
			'normal', 
			'low' 
		);	
	}

	public function _sg_allmeta() {
		foreach( get_post_meta( $this->sg_post ) as $key => $value ){
			if( strpos( $key, '_sg_' ) === 0 ) {
				echo '<strong>'.$key.'</strong>:<br/>';
				var_dump( get_post_meta( $this->sg_post, $key, true ) );
			}
		}
	}

	public function _metaInit() {
		add_meta_box( 
			$this->sg_title, 
			__( $this->sg_title, 'myplugin_textdomain' ), 
			array( $this, 'admin' ), 
			'style-guides', 
			'normal', 
			'high'
		);
	}
		
}

new sgInit();
new styleAdmin();
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

?>