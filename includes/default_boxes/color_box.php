<?php
class sg_color_box implements StyleGuideSection {
	
	function __construct(){
		$this->sg_title = 'Color Box';
		$this->sg_admin_title = 'Colors';
		add_action( 'admin_enqueue_scripts', array( $this, 'metaScripts' ) );
	}

	function metaScripts() {
		wp_enqueue_script( 'colorBoxJS', SG90_PLUGINURL.'/includes/default_boxes/build_admin/js/colorBoxJS.js', array( 'jquery' ), '1.0', false );
	}

	public function admin( $post ) {
		$post_id = $post->ID;
		
		$html = '<p><em>Color box in guide will be based on the hex value</em></p>';
		$html .= '<h2>'.str_replace( '_', ' ', $this->sg_admin_title ).'</h2>';
		$html .= '<div class="colors" id="'.$this->sg_admin_title.'">';
			
			$html .= '<div>';
			$layout = '';
			if( get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true ) ) {
				$layout =	get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
			};
			$html .= '<h2>Layout</h2>';
			$html .= '<label for="layout_3_column'.$this->sg_admin_title.'"><input id="layout_3_column'.$this->sg_admin_title.'" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_3_column"';
				if( $layout === 'layout_3_column' || $layout === '' ) { $html .= 'checked="checked"'; }
			$html .= '> 3 Column</label> &nbsp;&nbsp;&nbsp;';
			
			$html .= '<label for="layout_4_column'.$this->sg_admin_title.'"><input id="layout_4_column'.$this->sg_admin_title.'" type="radio" name="_sg_'.$this->sg_admin_title.'_media_image_layout" value="layout_4_column"';
				if( $layout === 'layout_4_column' || $layout === '' ) { $html .= 'checked="checked"'; }
			$html .= '> 4 Column</label> &nbsp;&nbsp;&nbsp;';
			
			$html .= '</div>';
			if( !get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_', true ) ) {
				// NEW COLOR BOX
				$html .= '<div>';
					$html .= '<input type="text" class="colorTitle" name="_sg_'.$this->sg_admin_title.'_[colorTitle][]" placeholder="Color Title" />';
					$html .= '<span class="colorCMYK">';
						$html .= '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_'.$this->sg_admin_title.'_[colorC][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_'.$this->sg_admin_title.'_[colorM][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_'.$this->sg_admin_title.'_[colorY][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_'.$this->sg_admin_title.'_[colorK][]" />';
					$html .= '</span>';
					$html .= '<span class="colorRGB">';
						$html .= '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_'.$this->sg_admin_title.'_[colorR][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_'.$this->sg_admin_title.'_[colorG][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_'.$this->sg_admin_title.'_[colorB][]" />';
					$html .= '</span>';
					$html .= '<input type="text" class="color" placeholder="Hex Value" name="_sg_'.$this->sg_admin_title.'_[colorHex][]" />';
				$html .= '</div>';
			} else {
				// LOAD EXISTING COLORS
				$colorCount = 0;
				$color = get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_', false );
				$color = $color[0];
				while( count( $color['colorTitle'] ) -1 >= $colorCount ) {
					if( !empty( $color ) ):
						$html .= '<div>';
						
						$html .= '<input type="text" class="colorTitle" name="_sg_'.$this->sg_admin_title.'_[colorTitle][]" placeholder="Color Title" value="';
							if( isset( $color['colorTitle'][$colorCount] ) ) $html .= $color['colorTitle'][$colorCount];
						$html .= '" />';
						
						$html .= '<span class="colorCMYK">';
							$html .= '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_'.$this->sg_admin_title.'_[colorC][]" value="';
								if( isset( $color['colorC'][$colorCount] ) ) $html .= $color['colorC'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_'.$this->sg_admin_title.'_[colorM][]" value="';
								if( isset( $color['colorM'][$colorCount] ) ) $html .= $color['colorM'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_'.$this->sg_admin_title.'_[colorY][]" value="';
								if( isset( $color['colorY'][$colorCount] ) ) $html .= $color['colorY'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_'.$this->sg_admin_title.'_[colorK][]" value="';
								if( isset( $color['colorK'][$colorCount] ) ) $html .= $color['colorK'][$colorCount];
							$html .= '" />';
							
						$html .= '</span>';

						$html .= '<span class="colorRGB">';
							$html .= '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_'.$this->sg_admin_title.'_[colorR][]" value="';
								if( isset ( $color['colorR'][$colorCount] ) ) $html .= $color['colorR'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_'.$this->sg_admin_title.'_[colorG][]" value="';
								if( isset ( $color['colorG'][$colorCount] ) ) $html .= $color['colorG'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_'.$this->sg_admin_title.'_[colorB][]" value="';
								if( isset ( $color['colorB'][$colorCount] ) ) $html .= $color['colorB'][$colorCount];
							$html .= '" />';
						$html .= '</span>';
							$html .= '<input type="text" class="colorHex" placeholder="Hex Code" name="_sg_'.$this->sg_admin_title.'_[colorHex][]" value="';
								if( isset ( $color['colorHex'][$colorCount] ) ) $html .= $color['colorHex'][$colorCount];
							$html .= '" />';
							if( $colorCount > 0 )
								$html .= '<a href="#" class="removeColor">x</a>';
						$html .= '</div>';
						$colorCount++;
					endif;
				}
			}
		$html .= '</div>';
		$html .= '<button class="button button-primary addColor" data-title="'.$this->sg_admin_title.'" >Add Color</button>';

		return $html;
	}

	
	public function view( $sg_post_id ) {
		$template = '';
		$colors = get_post_meta( $sg_post_id, '_sg_'.$this->sg_admin_title.'_', false );
		if( !$colors ) { return ''; }
		
		$layout =	get_post_meta( $sg_post_id, '_sg_'.$this->sg_admin_title.'_media_image_layout', true );
		
		switch( $layout ) {
		
			case 'layout_3_column':
				$colors = $colors[0];
				$template .= '<div class="row sg_colors">';
				$i = 0;
				while( count( $colors['colorTitle'] ) - 1 >= $i ) {
					$template .= '';
					$template .='<div class="col-md-4"><div class="sg_color">';
						$template .= '<div class="colorBox" style="background:'.$colors['colorHex'][$i].'"></div>';
						$template .= '<div class="row colorDefs"><div class="col-xs-12">';
							if( isset( $colors['colorTitle'][$i] ) )
								$template .= '<strong class="title">'.$colors['colorTitle'][$i].'</strong>';
							$template .= '<p>HEX: '.$colors['colorHex'][$i].'</p>';
							if( !empty( $colors['colorC'][$i] ) ) $template .= '<p>CMYK: ';
								if( !empty( $colors['colorC'][$i] ) ) $template .= $colors['colorC'][$i].' / ';
								if( !empty( $colors['colorM'][$i] ) ) $template .= $colors['colorM'][$i].' / ';
								if( !empty( $colors['colorY'][$i] ) ) $template .= $colors['colorY'][$i].' / ';
								if( !empty( $colors['colorK'][$i] ) ) $template .= $colors['colorK'][$i];
							$template .= '</p>';
							if( $colors['colorR'][$i] !== '' || $colors['colorR'][$i] == '0' ) $template .= '<p>RGB: ';
								if( $colors['colorR'][$i] !== '' || $colors['colorR'][$i] == '0' ) $template .= $colors['colorR'][$i];
								if( $colors['colorG'][$i] !== '' || $colors['colorG'][$i] === '0' ) $template .= ' / '.$colors['colorG'][$i];
								if( $colors['colorB'][$i] !== '' || $colors['colorB'][$i] === '0' ) $template .= ' / '.$colors['colorB'][$i];
							$template .= '</p>';
						$template .= '</div></div>';
					$template .= '</div></div>';
				$i = $i + 1;
				if( $i % 3 === 0 && $i !== 0 ) { $template .= '</div><div class="row sg_colors">'; }
				$i = $i - 1;
				$i++;
				}
				$template .= '</div>';
			break;
			
			case 'layout_4_column':
				$colors = $colors[0];
				$template .= '<div class="row sg_colors">';
				$i = 0;
				while( count( $colors['colorTitle'] ) - 1 >= $i ) {
					$template .= '';
					$template .='<div class="col-md-3"><div class="sg_color">';
						$template .= '<div class="colorBox" style="background:'.$colors['colorHex'][$i].'"></div>';
						$template .= '<div class="row colorDefs"><div class="col-xs-12">';
							if( isset( $colors['colorTitle'][$i] ) )
								$template .= '<strong class="title">'.$colors['colorTitle'][$i].'</strong>';
							$template .= '<p>HEX: '.$colors['colorHex'][$i].'</p>';
							if( !empty( $colors['colorC'][$i] ) ) $template .= '<p>CMYK: ';
								if( !empty( $colors['colorC'][$i] ) ) $template .= $colors['colorC'][$i].' / ';
								if( !empty( $colors['colorM'][$i] ) ) $template .= $colors['colorM'][$i].' / ';
								if( !empty( $colors['colorY'][$i] ) ) $template .= $colors['colorY'][$i].' / ';
								if( !empty( $colors['colorK'][$i] ) ) $template .= $colors['colorK'][$i];
							$template .= '</p>';
							if( $colors['colorR'][$i] !== '' || $colors['colorR'][$i] == '0' ) $template .= '<p>RGB: ';
								if( $colors['colorR'][$i] !== '' || $colors['colorR'][$i] == '0' ) $template .= $colors['colorR'][$i];
								if( $colors['colorG'][$i] !== '' || $colors['colorG'][$i] === '0' ) $template .= ' / '.$colors['colorG'][$i];
								if( $colors['colorB'][$i] !== '' || $colors['colorB'][$i] === '0' ) $template .= ' / '.$colors['colorB'][$i];
							$template .= '</p>';
						$template .= '</div></div>';
					$template .= '</div></div>';
				$i = $i + 1;
				if( $i % 4 === 0 && $i !== 0 ) { $template .= '</div><div class="row sg_colors">'; }
				$i = $i - 1;
				$i++;
				}
				$template .= '</div>';			
			break;
		}
		return $template;
	}
}

$SG_Factory->register( 'sg_color_box' );
?>