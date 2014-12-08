<?php

class _GENERATE_SG {

	public function createSG( $title, $template ) {
		if( post_type_exists( 'style-guides' ) ) {
			$post = array (
				'post_title'  => $title,
				'post_type'   => 'style-guides',
				'post_status' => 'publish',
				'post_content' => '<p>This is a Style Guide</p>'
			);
			$newSG = wp_insert_post( $post, true );

			if( !is_wp_error( $newSG ) ) {
				$json = json_decode( file_get_contents( dirname( __FILE__ ) . '/templates/' . $_POST['_sg_template'] . '.json' ), true );
				$sections = $json['sections'];
				
				
				foreach( $sections as $section ) {
					$this->createSection( $newSG, $section );
				}
				wp_redirect( admin_url( 'post.php?post=' . $newSG . '&action=edit' ) );

			} else {
				return $newSG;
			}

		} else {
			return 'Style Guides not defined';
		}
	}

	function createSection( $post_id, $section ) {
		$section['title'] = str_replace( ' ', '_', $section['title'] );
		
		switch( $section['type'] ) {
			case 'text' :
				$this->addTextBox( $post_id, $section['title'] , $section['content'] );
			break;
			case 'images':
				$this->addImages( $post_id, $section['title'], $section['layout'], $section['images'] );
			break;
			case 'fonts':
				$this->addFonts( $post_id, $section['title'], $section['fonts'] );
			break;
			case 'colors':
				$this->addColors( $post_id, $section['title'], $section['colors'] );
			break;
			case 'buttons':
				$this->addButtons( $post_id, $section['title'], $section['color'] );
			break;
		}
	}

	private function addImages( $post_id, $title = "Images", $layout = "layout_showcase", $images ) {
		/**
		 $images = [ src, src, src ];
		 **/
		$this->addSection( $title, '_new-sg-Image_Box', $post_id );

		update_post_meta( $post_id, '_sg_'.$title.'_media_image_layout', $layout );
		update_post_meta( $post_id, '_sg_'.$title.'_media_image_modal', 'on' );
		update_post_meta( $post_id, '_sg_'.$title.'_media_image', $images );

	}

	private function addFonts( $post_id, $title = "Fonts", $fonts ) {
		/**
		 $fonts = [ tag => array(), font => array(), variant => array() ];
		 **/
		$newFonts = array();
		$this->addSection( $title, '_new-sg-Google_Fonts', $post_id );

		update_post_meta( $post_id, '_sg_Fonts_gFont', $fonts );

	}

	private function addTextBox( $post_id, $title = "Text", $text ) {
		/**
		 $text must be HTML for formatting
		 **/
		$this->addSection( $title, '_new-sg-Text_Box', $post_id );
		update_post_meta( $post_id, '_sg_'.$title.'_textBox', $text );

	}

	private function addButtons( $post_id, $title = "Buttons", $buttonColor ) {
		/**
		 $buttonColor mus be hex string with hash (i.e #FF0099)
		 **/
		$this->addSection( $title, '_new-sg-Buttons_Box', $post_id );
		update_post_meta( $post_id, '_sg_'.$title.'_button', $buttonColor );

	}


	private function addColors( $post_id, $title = "Colors", $colors ) {
		/**
		 $colors = [ colorTitle => array(), colorHex => array(), colorC => array(), colorM => array(), colorY => array(), colorK => array(), colorR => array(), colorG => array(), colorB => array() ];
		 **/
		$this->addSection( $title, '_new-sg-Color_Box', $post_id );
		update_post_meta( $post_id, '_sg_'.$title.'_', $colors );
	}


	private function addSection( $title, $className, $post_id ) {
		if( get_post_meta( $post_id, '_sg_sections', false ) ){
			$sections = get_post_meta( $post_id, '_sg_sections', false );
			array_push( $sections[0], array( 'title' => $title, 'class' => $className ) );
			update_post_meta( $post_id, '_sg_sections', $sections[0] );
		} else {
			$sections = array( array( 'title' => $title, 'class' => $className ) );
			add_post_meta( $post_id, '_sg_sections', $sections );
		}
	}

}

$SG_TEMPLATE_ENGINE = new _GENERATE_SG();

?>