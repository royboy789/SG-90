<?php
class sg_color_box extends StyleGuideCreator {
	public $sg_title = 'Colors';

	function __construct(){
		add_action( 'admin_enqueue_scripts', array( $this, 'metaScripts' ) );		
		parent::__construct();
	}

	function metaScripts() {
		wp_enqueue_script( 'colorBoxJS', SG60_PLUGINURL.'/includes/default_boxes/js/colorBoxJS.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_style( 'colorBoxCSS', SG60_PLUGINURL.'/includes/default_boxes/css/colorBoxCSS.css', '', '1.0', 'all' );
	}

	public function admin( $post ) {
		$post_id = $post->ID;
		
		$html = '<p><em>Color box in guide will be based on the hex value</em></p>';
		$html .= '<div class="colors">';
			if( !get_post_meta( $post_id, '_sg_colors', true ) ) { 
				$html .= '<div>';
					$html .= '<input type="text" class="colorTitle" name="_sg_colors[colorTitle][]" placeholder="Color Title" />';
					$html .= '<span class="colorCMYK">';
						$html .= '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_colors[colorC][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_colors[colorM][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_colors[colorY][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_colors[colorK][]" />';
					$html .= '</span>';
					$html .= '<span class="colorRGB">';
						$html .= '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_colors[colorR][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_colors[colorG][]" />';
						$html .= '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_colors[colorB][]" />';
					$html .= '</span>';
					$html .= '<input type="text" class="color" placeholder="Hex Value" name="_sg_colors[colorHex][]" />';
				$html .= '</div>';
			} else {
				$colorCount = 0;
				$color = get_post_meta( $post_id, '_sg_colors', false );
				$color = $color[0];
				while( count( $color['colorTitle'] ) -1 >= $colorCount ) {
					if( !empty( $color ) ):
						$html .= '<div>';
						
						$html .= '<input type="text" class="colorTitle" name="_sg_colors[colorTitle][]" placeholder="Color Title" value="';
							if( isset( $color['colorTitle'][$colorCount] ) ) $html .= $color['colorTitle'][$colorCount];
						$html .= '" />';
						
						$html .= '<span class="colorCMYK">';
							$html .= '<input type="text" class="colorRGB" placeholder="Cyan (C)" name="_sg_colors[colorC][]" value="';
								if( isset( $color['colorC'][$colorCount] ) ) $html .= $color['colorC'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Magenta (M)" name="_sg_colors[colorM][]" value="';
								if( isset( $color['colorM'][$colorCount] ) ) $html .= $color['colorM'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Yellow (Y)" name="_sg_colors[colorY][]" value="';
								if( isset( $color['colorY'][$colorCount] ) ) $html .= $color['colorY'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Key (K)" name="_sg_colors[colorK][]" value="';
								if( isset( $color['colorK'][$colorCount] ) ) $html .= $color['colorK'][$colorCount];
							$html .= '" />';
							
						$html .= '</span>';

						$html .= '<span class="colorRGB">';
							$html .= '<input type="text" class="colorRGB" placeholder="Red (R)" name="_sg_colors[colorR][]" value="';
								if( isset ( $color['colorR'][$colorCount] ) ) $html .= $color['colorR'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Green (G)" name="_sg_colors[colorG][]" value="';
								if( isset ( $color['colorG'][$colorCount] ) ) $html .= $color['colorG'][$colorCount];
							$html .= '" />';
							$html .= '<input type="text" class="colorRGB" placeholder="Blue (B)" name="_sg_colors[colorB][]" value="';
								if( isset ( $color['colorB'][$colorCount] ) ) $html .= $color['colorB'][$colorCount];
							$html .= '" />';
						$html .= '</span>';
							$html .= '<input type="text" class="colorHex" placeholder="Hex Code" name="_sg_colors[colorHex][]" value="';
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
		$html .= '<button class="button button-primary addColor">Add Color</button>';

		echo $html;
	}

	
	public function view( $sg_post_id ) {
		$colors = get_post_meta( $sg_post_id, '_sg_colors', false );
		if( $colors ):
			$template .= '<div class="row"><div class="col-md-12"><h2 class="text-center">COLORS</h2></div></div>';
			$template .= '<div class="row sg60_colors">';
			$i = 1;
				foreach( $colors as $color ){
					$template .= '';
					$template .='<div class="col-md-3"><div class="sg60_color">';
						$template .= '<span style="background:'.$color['colorHex'].'"></span>';
						if( isset( $color['colorTitle'] ) )
							$template .= '<strong class="text-center">'.$color['colorTitle'].'</strong>';
						$template .= '<p class="text-center">'.$color['colorHex'].'</p>';
						$template .= '<div class="row colorDefs"><div class="col-xs-6">';
							$template .= '<p><strong>C</strong>';
								if( isset( $color['colorCMYK']['c'] ) ) $template .= $color['colorCMYK']['c'];
							$template .= '</p>';
							$template .= '<p><strong>M</strong>';
								if( isset( $color['colorCMYK']['m'] ) ) $template .= $color['colorCMYK']['m'];
							$template .= '</p>';
							$template .= '<p><strong>Y</strong>';
								if( isset( $color['colorCMYK']['y'] ) ) $template .= $color['colorCMYK']['y'];
							$template .= '</p>';
							$template .= '<p><strong>K</strong>';
								if( isset( $color['colorCMYK']['k'] ) ) $template .= $color['colorCMYK']['k'];
							$template .= '</p>';
						$template .= '</div><div class="col-xs-6">';
							$template .= '<p><strong>R</strong>';
								if( isset( $color['colorRGB']['r'] ) ) $template .= $color['colorRGB']['r'];
							$template .= '</p>';
							$template .= '<p><strong>G</strong>';
								if( isset( $color['colorRGB']['g'] ) ) $template .= $color['colorRGB']['g'];
							$template .= '</p>';
							$template .= '<p><strong>B</strong>';
								if( isset( $color['colorRGB']['b'] ) ) $template .= $color['colorRGB']['b'];
							$template .= '</p>';
						$template .= '</div></div>';
							
					$template .= '</div></div>';
					if( $i%4 == 0 ) { $template .= '</div><div class="row sg60_colors">'; }
					$i++;
				}
			$template .= '</div>';
		endif;
		return $template;
	}
}

new sg_color_box();

?>