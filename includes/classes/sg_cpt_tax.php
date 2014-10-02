<?php
class sgInit {
	
	function __construct() {
		add_action( 'init', array( $this, 'styleGuideCPT' ) );
		// SINGLE 
		add_filter( 'the_content', array( $this, 'singleTemplate' ), 20 );
		// SEO BOXES
		add_action('admin_init', array( $this, 'seoCheck' ) );
	}
	
	function styleGuideCPT() {
		$labels = array(
			'name'               => _x( 'Style Guides', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Style Guide', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Style Guides', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Style Guide', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'New Style Guide', 'style guide', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'New Style Guide', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Style Guide', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Style Guide', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Style Guide', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Style Guides', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Style Guides', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Style Guides:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No style guides found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No style guides found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'style-guides' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' )
		);

		register_post_type( 'style-guides', $args );
		flush_rewrite_rules();
	}
	
	function singleTemplate( $content ) {
		if( 'style-guides' === get_post_type() ) {
			global $post;
			$content = do_shortcode( '[sg-90 id="'.$post->ID.'"]' );
		}
		
		return $content;
	}

	function seoCheck(){
		if( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ){
			add_action( 'add_meta_boxes', array( $this, 'remove_wp_seo_box' ), 100000 );
		}
	}

	function remove_wp_seo_box() {
		remove_meta_box( 'wpseo_meta', 'style-guides', 'normal' );
	}

}
?>