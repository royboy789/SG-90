<?php

class sg_image_box implements StyleGuideSection {
	
	function __construct( $title = 'Image Box' ){
		$this->sg_title = 'Image Box';
		$this->sg_admin_title = $title;
		
		/** ADMIN SCRIPTS **/
		add_action( 'admin_enqueue_scripts', array( $this, 'metaScripts' ) );
		
		/** VIEW SCRIPS **/
		add_action( 'wp_enqueue_scripts', array( $this, 'modalScripts' ) );
		
	}
	
	function modalScripts() {
		wp_enqueue_script( '_sg_modalJS', SG90_PLUGINURL.'/includes/default_boxes/build_view/js/responsive_lightbox/jquery.lightbox.min.js', array( 'jquery' ), null, true );
		wp_enqueue_style( '_sg_modalCSS', SG90_PLUGINURL.'/includes/default_boxes/build_view/js/responsive_lightbox/jquery.lightbox.min.css', '', '1.0', 'all' );
	}
	
	function metaScripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'imageJS', SG90_PLUGINURL.'/includes/default_boxes/build_admin/js/image_upload.js', array( 'jquery' ), '1.0', false );
	}
	
	public function admin( $post ) {
		$post_id = $post->ID;
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		$html = '<div class="imageWrapper" id="_sgBox_'.$this->sg_admin_title.'">';
			$html .= '<h2>Modal</h2>';
			$html .= '<p><em>Load images in modal on click</em></p>';
			$html .= '<select type="checkbox" name="_sg_'.$this->sg_admin_title.'_media_image_modal">';
				$html .= '<option value="on"';
				if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_modal', true ) == 'on' )
					$html .= 'selected="selected"';
				$html .= '>On</option>';
				$html .= '<option value="off"';
				if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_modal', true ) == 'off' )
					$html .= 'selected="selected"';
				$html .= '>Off</option>';
			$html .= '</select>';
			$html .= '<h2>Layout</h2>';
			$html .= '<p><em>Column Layout requires Twitter Bootstrap to be loaded</em></p>';
			$layout = '';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true ) ) {
				$layout =	get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
			};
			
			$html .= '<label for="layout_showcase"><input id="layout_showcase" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_showcase"';
				if( $layout === 'layout_showcase' || $layout === '' ) { $html .= 'checked="checked"'; }
			$html .= '> Showcase</label> &nbsp;&nbsp;&nbsp;';
			
			$html .= '<label for="layout_col"><input id="layout_col" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_col"';
				if( $layout === 'layout_col' ) { $html .= 'checked="checked"'; }
			$html .= '> 4 Columns</label> &nbsp;&nbsp;&nbsp;';
			
			$html .= '<label for="layout_three_col"><input id="layout_three_col" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_three_col"';
				if( $layout === 'layout_three_col' ) { $html .= 'checked="checked"'; }
			$html .= '> 3 Columns</label> &nbsp;&nbsp;&nbsp;';
			
			$html .= '<label for="layout_two_col"><input id="layout_two_col" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_two_col"';
				if( $layout === 'layout_two_col' ) { $html .= 'checked="checked"'; }
			$html .= '> 2 Columns</label> <br/><br/>';
			
			$html .= '<label for="layout_off"><input id="layout_off" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_off"';
				if( $layout === 'layout_off' ) { $html .= 'checked="checked"'; }
			$html .= '> No layout</label>';
			
			$html .= '<h2>Images</h2>';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true ) ) {
				$images = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true );
				foreach( $images as $img ) {
					$html .= '<div class="imageContainer">';
						$html .= '<label for="_sg_'.$this->sg_admin_title.'_media_image[]" data-for="_sg_'.$this->sg_admin_title.'_media_image[]" class="upload_image"> IMAGE: &nbsp;';
						$html .= '<input type="text" name="_sg_'.$this->sg_admin_title.'_media_image[]" style="width:99%" placeholder="click to upload" value="'.$img.'" />';
						$html .= '</label> ';
						$html .= '<a href="#" class="removeImage" style="color: red">x</a>';
					$html .= '</div>';		
				}
			}
			$html .= '<div class="imageContainer">';
				$html .= '<label for="_sg_'.$this->sg_admin_title.'_media_image[]" data-for="_sg_'.$this->sg_admin_title.'_media_image[]" class="upload_image"> IMAGE: &nbsp;';
				$html .= '<input type="text" value="" name="_sg_'.$this->sg_admin_title.'_media_image[]" style="width:99%" placeholder="click to upload" />';
				$html .= '</label> ';
				$html .= '<a href="#" class="removeImage" style="color: red">x</a>';
			$html .= '</div>';
		$html .= '</div>';
		$html .= '<br/><button class="button button-primary addImage" data-title="'.$this->sg_admin_title.'" data-box="_sgBox_'.$this->sg_admin_title.'" >Add Image</button>';
		
		return $html;
	}
	
	public function view( $post_id ) {
		
		$images = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true );
		$layout =	get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
		
		if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true ) ) {
			$html = '<div class="imageViewWrapper ';
				if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_modal', true ) == 'on' ):
					$html .= 'lightboxOn';
				else:
					$html .= 'lightboxOff';
				endif;
			$html .= '">';
			$images = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true );
			
			switch ( $layout ) {
				case 'layout_off':
					foreach( $images as $img ) {
						if( !empty( $img ) ) 
							$html .= '<img src="'.$img.'" alt="SG-90 Style Guide" />';
					}	
				break;
				
				case 'layout_showcase':

					$html .= '<div class="row"><div class="col-md-12 text-center">';
						$html .= '<img data-img-url="'.$images[0].'" src="'.$images[0].'" alt="SG-90 Style Guide Creator" />';
					$html .= '</div></div>';
					
					
					if( isset( $images[1] ) ):
						$html .= '<div class="row">';
						$i = 0;
						foreach( $images as $img ) {
							if( $i == 0 || empty( $img ) ) : 
								$html .= ''; 
							else :
								$imageID = get_attach_id( $img );
								if( !is_null( $imageID ) ){
									$imageMed = wp_get_attachment_image_src( $imageID, 'medium' );
									$imageFull = wp_get_attachment_image_src( $imageID, 'full' );
								} else {
									$imageFull = array( $img );
									$imageMed = array( $img );
								}
								$html .= '<div class="col-md-3 text-center">';
									$html .= '<img class="img-responsive" data-img-url="'.$imageFull[0].'" src="'.$imageMed[0].'" alt="SG-90 Style Guide Creator" />';
								$html .= '</div>';
								if(  $i%4 === 0 ) { $html .= '</div><div class="row">'; }
							endif;
							$i++;
						}
						$html .= '</div>';
					endif;
				break;
				
				case 'layout_col' :
					$html .= '<div class="row">';
						$i = 1;
						foreach( $images as $img ) {
							$imageID = get_attach_id( $img );
							if( !is_null( $imageID ) ){
								$imageMed = wp_get_attachment_image_src( $imageID, 'medium' );
								$imageFull = wp_get_attachment_image_src( $imageID, 'full' );
							} else {
								$imageFull = array( $img );
								$imageMed = array( $img );
							}
							if( !empty( $img ) ):
								$html .= '<div class="col-md-3 text-center">';
									$html .= '<img data-img-url="'.$imageFull[0].'" src="'.$imageMed[0].'" alt="SG-90 Style Guide Creator" />';
								$html .= '</div>';
								if( $i%4 == 0 ) { $html .= '</div><div class="row">'; }
							endif;
							$i++;
						}
						$html .= '</div>';
				break;
				
				case 'layout_two_col' :
					$html .= '<div class="row">';
						$i = 1;
						foreach( $images as $img ) {
							$imageID = get_attach_id( $img );
							if( !is_null( $imageID ) ){
								$imageMed = wp_get_attachment_image_src( $imageID, 'medium' );
								$imageFull = wp_get_attachment_image_src( $imageID, 'full' );
							} else {
								$imageFull = array( $img );
								$imageMed = array( $img );
							}
							if( !empty( $img ) ):
								$html .= '<div class="col-md-6 text-center">';
									$html .= '<img data-img-url="'.$imageFull[0].'" src="'.$imageMed[0].'" alt="SG-90 Style Guide Creator" />';
								$html .= '</div>';
								if( $i%3 == 0 ) { $html .= '</div><div class="row">'; }
							endif;
							$i++;
						}
						$html .= '</div>';
				break;
				
				case 'layout_three_col' :
					$html .= '<div class="row">';
						$i = 1;
						foreach( $images as $img ) {
							$imageID = get_attach_id( $img );
							if( !is_null( $imageID ) ){
								$imageMed = wp_get_attachment_image_src( $imageID, 'medium' );
								$imageFull = wp_get_attachment_image_src( $imageID, 'full' );
							} else {
								$imageFull = array( $img );
								$imageMed = array( $img );
							}
							if( !empty( $img ) ):
								$html .= '<div class="col-md-4 text-center">';
									$html .= '<img data-img-url="'.$imageFull[0].'" src="'.$imageMed[0].'" alt="SG-90 Style Guide Creator" />';
								$html .= '</div>';
								if( $i%3 == 0 ) { $html .= '</div><div class="row">'; }
							endif;
							$i++;
						}
						$html .= '</div>';
				break;
				
				default: $html .= '';
				
			}
			$html .= '</div>';
			return $html;
		}
	}

}

$SG_Factory->register( 'sg_image_box' );
?>