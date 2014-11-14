<?php

class sg_image_box extends StyleGuideCreator {
	public $sg_title = 'Images';
	
	function __construct( $title, $array ){
		add_action( 'admin_enqueue_scripts', array( $this, 'metaScripts' ) );
		parent::__construct( $title, $array );
	}
	
	function metaScripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'imageJS', SG90_PLUGINURL.'/includes/default_boxes/js/image_upload.js', array( 'jquery' ), '1.0', false );
	}
	
	public function admin( $post ) {
		$post_id = $post->ID;
		$this->sg_admin_title = str_replace( ' ', '_', $this->sg_admin_title );
		$html = '<div class="imageWrapper">';
			$html .= '<h2>'.$this->sg_admin_title.'</h2>';
			
			$html .= '<h2>Layout</h2>';
			$html .= '<p><em>Column Layout requires Twitter Bootstrap to be loaded</em></p>';
			$layout = '';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true ) ) {
				$layout =	get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
			};
			
			$html .= '<label for="layout_showcase"><input id="layout_showcase" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_showcase"';
				if( $layout === 'layout_showcase' || $layout === '' ) { $html .= 'checked="checked"'; }
			$html .= '> Showcase</label><br/>';
			
			$html .= '<label for="layout_col"><input id="layout_col" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_col"';
				if( $layout === 'layout_col' ) { $html .= 'checked="checked"'; }
			$html .= '> 4 Column</label><br/>';
			
			$html .= '<label for="layout_off"><input id="layout_off" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_off"';
				if( $layout === 'layout_off' ) { $html .= 'checked="checked"'; }
			$html .= '> No layout</label>';
			
			$html .= '<h2>Images</h2>';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true ) ) {
				$images = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true );
				foreach( $images as $img ) {
					$html .= '<div class="imageContainer">';
						$html .= '<label for="_sg_'.$this->sg_admin_title.'_media_image[]" data-for="_sg_'.$this->sg_admin_title.'_media_image[]" class="upload_image"> IMAGE: &nbsp;';
						$html .= '<input type="text" name="_sg_'.$this->sg_admin_title.'_media_image[]" style="width:30%" placeholder="click to upload" value="'.$img.'" />';
						$html .= '</label> ';
						$html .= '<a href="#" class="removeImage" style="color: red">x</a>';
					$html .= '</div>';		
				}
			}
			$html .= '<div class="imageContainer">';
				$html .= '<label for="_sg_'.$this->sg_admin_title.'_media_image[]" data-for="_sg_'.$this->sg_admin_title.'_media_image[]" class="upload_image"> IMAGE: &nbsp;';
				$html .= '<input type="text" value="" name="_sg_'.$this->sg_admin_title.'_media_image[]" style="width:30%" placeholder="click to upload" />';
				$html .= '</label> ';
				$html .= '<a href="#" class="removeImage" style="color: red">x</a>';
			$html .= '</div>';
		$html .= '</div>';
		$html .= '<br/><button class="button button-primary addImage" data-title="'.$this->sg_admin_title.'" >Add Image</button>';
		
		return $html;
	}
	
	public function view( $post_id ) {
		$images = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true );
		$layout =	get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
		
		if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image', true ) ) {
			$html = '';
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
					
					$i = 0;
					if( $images[1] ):
						$html .= '<div class="row">';
						foreach( $images as $img ) {
							if( $i++ === 0 || empty( $img ) ) continue ;
							$html .= '<div class="col-md-3 text-center">';
								$html .= '<img class="img-responsive" data-img-url="'.$img.'" src="'.$img.'" alt="SG-90 Style Guide Creator" />';
							$html .= '</div>';
							if( $i%4 == 0 ) { $html .= '</div><div class="row">'; }
						
						}
						$html .= '</div>';
					endif;
				break;
				
				case 'layout_col' :
					$html .= '<div class="row">';
						$i = 1;
						foreach( $images as $img ) {
							if( !empty( $img ) ):
								$html .= '<div class="col-md-3 text-center">';
									$html .= '<img class="img-responsive" data-img-url="'.$img.'" src="'.$img.'" alt="SG-90 Style Guide Creator" />';
								$html .= '</div>';
								if( $i%4 == 0 ) { $html .= '</div><div class="row">'; }
							endif;
							$i++;
						}
						$html .= '</div>';
				break;
				
				default: $html .= '';
				
			}
		}
		
		echo $html;
	}
	
	

}	

new sg_image_box( 'Image Box', true );
?>