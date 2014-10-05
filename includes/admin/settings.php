<?php
class styleAdmin {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'styleGuideMenus' ) );
		// SAVE
		add_action( 'save_post', array( $this, 'metaSave' ) );
		
		if( get_option( '_sg_bootstrap' ) && get_option( '_sg_bootstrap' ) == 'on' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'bootstrapCode' ) );
		}
	}

	function styleGuideMenus() {
		add_menu_page( 'SG-90', 'SG-90', 'delete_pages', 'style-guide-main', array( $this, 'styleGuideMain' ), 'dashicons-welcome-write-blog', 90 );
		add_submenu_page( 'style-guide-main', 'Add Style Guide', 'Add Style Guide', 'delete_pages', 'post-new.php?post_type=style-guides' );
		add_submenu_page( 'style-guide-main', 'Style Guide Trash', 'Get Shortcodes', 'delete_pages', 'admin.php?page=style-guide-main' );
		add_submenu_page( 'style-guide-main', 'SG-90 Settings', 'SG-90 Settings', 'delete_pages', 'sg-settings', array( $this, 'sgSettings' ) );
	}
	
	function bootstrapCode() {
		wp_enqueue_style( 'sg_bootstrap', SG60_PLUGINURL.'/includes/css/sgStyles.css', '', '1.0', 'all' );
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
	
	function sgSettings() {
		if( isset( $_POST['_sg_bootstrap'] ) ) {
			update_option( '_sg_bootstrap', $_POST['_sg_bootstrap'] );
		}
		$_sg_bootstrap = get_option( '_sg_bootstrap', "on" );
		echo '<h2>SG-90 Settings</h2>';
		echo '<div id="poststuff">';
			echo '<div class="postbox-container">';
				echo '<div class="postbox">';
					echo '<h3>Twitter Bootstrap</h3>';
					echo '<div class="inside">';
						echo '<p><a href="http://www.getbootstrap.com" target="_blank">Twitter Bootstrap</a> is a responsive framework. The SG plugins utilize it for maintaining columns, however if your theme already loads Twitter Bpotstrap, we recommend turning it off here to keep from loading resources twice</p>';
						echo '<form action="'.admin_url('admin.php?page=sg-settings').'" method="post">';
							echo '<div class="checkbox form-group">';
								echo '<label>';
									echo '<input type="radio" name="_sg_bootstrap" value="on" ';
									if( $_sg_bootstrap == 'on' ) { echo 'checked'; }
									echo ' /> Load Bootstrap';
								echo '</label><br/><Br/>';
								echo '<label>';
									echo '<input type="radio" name="_sg_bootstrap" value="off" ';
									if( $_sg_bootstrap == 'off' ) { echo 'checked'; }
									echo ' /> Do not Bootstrap';
								echo '</label>';
							echo '</div>';
							echo '<div class="form-group" style="margin-top: 20px;">';
								echo '<input class="button" type="submit" value="Save Settings" />';
							echo '</div>';
						echo '</form>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	
}
?>