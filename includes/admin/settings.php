<?php
class styleAdmin {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'styleGuideMenus' ) );
		// SAVE
		add_action( 'save_post', array( $this, 'metaSave' ) );
	}

	function styleGuideMenus() {
		add_menu_page( 'SG-90', 'SG-90', 'delete_pages', 'style-guide-main', array( $this, 'styleGuideMain' ), 'dashicons-welcome-write-blog', 90 );
		add_submenu_page( 'style-guide-main', 'Add Style Guide', 'Add Style Guide', 'delete_pages', 'post-new.php?post_type=style-guides' );
		add_submenu_page( 'style-guide-main', 'Style Guide Trash', 'Get Shortcodes', 'delete_pages', 'admin.php?page=style-guide-main' );
	}
	
	public function metaSave( $post_id ) {
		if( 'style-guides' == get_post_type( $post_id ) && !empty( $_POST ) ):
			foreach( $_POST as $key => $value ) {
				if( strpos( $key, '_sg_' ) === 0 ) {
					if( get_post_meta( $post_id, $key ) && empty( $value ) ) { 
						delete_post_meta( $post_id, $key );
					} else {
						update_post_meta( $post_id, $key, $value );	
					}
				}
			}
		endif;
	}

	function styleGuideMain() {
		$args = array( 'post_type' => 'style-guides' );
		$loop = new WP_Query( $args );
		echo '<img src="'.SG60_PLUGINURL.'/includes/admin/img/sg60-logo.png" class="sg60Logo" />';
		echo '<h2>SG-90 Style Guides</h2>';
		echo '<table class="widefat">';
			echo '<thead>';
				echo '<th>Style Guide Title</th>';
				echo '<th>Shortcode</th>';
				echo '<th>Edit</th>';
			echo '</thead>';
			echo '<tbody>';
				if( $loop->have_posts() ) : while( $loop->have_posts() ) : $loop->the_post();
					echo '<tr>';
						echo '<td>'.get_the_title().'</td>';
						echo '<td><code>[SG-90 id="'.get_the_ID().'"]</code></td>';
						echo '<td><a href="'.admin_url('post.php?post='.get_the_ID().'&action=edit').'">Edit</a></td>';
					echo '</tr>';
				endwhile;
				else:
					echo '<tr><td colspan="3" align="center">No Style Guides Created <br/><br/>';
						echo '<a class="button button-primary" href="'.admin_url('post-new.php?post_type=style-guides').'">Create SG-90 Style Guide</a>';
					echo '</td>';
				endif;
			echo '</tbody>';
		echo '</table>';

		echo '<div class="wrap feature-filter">';
		    echo '<table width="100% cellpadding="5" cellspacing="0" border="0" id="bottomTable">';
		    	echo '<thead>';
		    		echo '<tr><td colspan="2"><div>';
		    			include( 'text/sg60_top_column.php' );
		    			echo '</div><div class="text-center"><a class="button button-primary" href="'.admin_url('post-new.php?post_type=style-guides').'">Create SG-90 Style Guide</a>';
		    		echo '</div></td></td>';
		    		echo '<tr>';
		    			echo '<th><h3>SG-90 Style Guide Creator</h3></th>';
		    			echo '<th><h3>SG-90 &amp; ARC(CTRL) NEWS</h3></th>';
		    		echo '<tr>';
		    	echo '</thead>';
		    	echo '<tbody>';		    		
		    		echo '<tr>';
		    			echo '<td valign="top" width="50%"><div>';
		    				include( 'text/sg60_left_column.php' );
		    			echo '</div></td>';
		    			echo '<td valign="top"><div>';
		    				include( 'text/sg60_right_column.php' );
		    			echo '</div></td>';
		    		echo '</tr>';
		    	echo '</tbody>';
		    echo '</table>';
		    echo '<br class="clear">';
		echo '</div>';
	}
	
	function sg60settings() {
		echo '<h2>Edit Style Guides</h2>';
	}
	
}
?>