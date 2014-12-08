<?php

global $SG_TEMPLATE_ENGINE;
require('sg_template_builder.php');

class _SG_Template_Core {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'templateMenus' ) );
		add_action( 'admin_init', array( $this, 'templateCheck' ) );
	}

	function templateMenus() {
		add_submenu_page( 'style-guide-main', 'SG-90 Templates', 'SG-90 Templates', 'delete_pages', 'sg-templates', array( $this, 'sgTemplates' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminScripts' ) );
	}

	function templateCheck() {
		global $SG_TEMPLATE_ENGINE;

		if( isset( $_POST ) && isset( $_POST['_sg_template_title'] ) ) {
			$SG_TEMPLATE_ENGINE->createSG( $_POST['_sg_template_title'], $_POST['_sg_template'] );
		}
	}
	
	function adminScripts() {
		wp_enqueue_style( 'sg_template_css', SG90_PLUGINURL. '/includes/admin/templateBuilder/scripts/sg_template_css.css', '', '1.0', 'all' );
		wp_enqueue_script(' sg_template_js', SG90_PLUGINURL. '/includes/admin/templateBuilder/scripts/sg_template_js.js', array( 'jquery' ), '1.0', true );
	}

	function sgTemplates() {
		//$json = json_decode( file_get_contents( dirname( __FILE__ ) . '/templates/' . $_POST['_sg_template'] . '.json' ), true );

		$screenshots = array();
		$files = glob( dirname( __FILE__ ) . '/templates/*' );
		foreach( $files as $file ) {
			$json = json_decode( file_get_contents( $file ), true );
			$screenshots[] = array( 'file' => $json['screenshot'], 'template' => $json['template'], 'title' => $json['title'] );
		}

		echo '<div id="poststuff">';
			echo '<div class="postbox-container">';
				echo '<div class="postbox">';
					echo '<div class="inside">';
					echo '<h2>SG Templates</h2>';
					echo '<p>Pre Loaded templates are a great way to get started with a new SG-90. </p>';
					echo '<h2>Step 1: Choose a template</h2>';
					echo '<ul class="sg_templates">';
					foreach( $screenshots as $template ):
						echo '<li>';
							echo '<a class="sg_template_choice" data-template="'.$template['template'].'">';
								echo '<img src="'.$template['file'].'" class="sg_template_image" alt="SG-90 Template" /><br/>';
								echo '<strong>'.$template['title'].'</strong>';
							echo '</a>';
						echo '</li>';
					endforeach;
					echo '</ul>';
					echo '<form action="'.admin_url('admin.php?page=sg-templates').'" method="post" id="newSG">';
						echo '<input type="hidden" name="_sg_template" value="" />';
						echo '<h2>Step 2: Name your new Style Guide</h2>';
						echo '<p><input name="_sg_template_title" placeholder="New SG Title" /></p>';
						echo '<h2>Step 3: Create your new Style Guide!</h2>';
						echo '<input type="submit" class="button button-primary" value="Create New Style Guide!" />';
					echo '</form>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}

?>