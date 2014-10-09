<?php
class sg_color_box extends StyleGuideCreator {
	public $sg_title = 'Colors';
	private $iteration = 0;

	function __construct( $title, $array ){
		add_action( 'admin_enqueue_scripts', array( $this, 'metaScripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'viewScripts' ) );
		parent::__construct( $title, $array );
	}

	function metaScripts() {
		wp_enqueue_script( 'colorBoxJS', SG60_PLUGINURL.'/includes/default_boxes/js/colorBoxJS.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_style( 'colorBoxCSS', SG60_PLUGINURL.'/includes/default_boxes/css/colorBoxCSS.css', '', '1.0', 'all' );
	}
	
	function viewScripts() {
		wp_enqueue_style( 'colorBoxCSSView', SG60_PLUGINURL.'/includes/default_boxes/css/colorBoxCSS.css', '', '1.0', 'all' );
	}

	public function admin( $post ) {
		$post_id = $post->ID;
		
		$html = '<p><em>Color box in guide will be based on the hex value</em></p>';
		$html .= '<h2>'.$this->sg_admin_title.'</h2>';
		$html .= '<div class="colors">';
			if( !get_post_meta( $post_id, '_sg_'.$this->sg_admin_title.'_', true ) ) { 
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
		$html .= '<button class="button button-primary addColor">Add Color</button>';

		echo $html;
	}

	
	public function view( $sg_post_id ) {
		$template = '';
		$colors = get_post_meta( $sg_post_id, '_sg_colors', false );
		$colors = $colors[0];
		if( $colors ):
			$template .= '<div class="row"><div class="col-md-12"><h2 class="text-center">COLORS</h2></div></div>';
			$template .= '<div class="row sg_colors">';
			$i = 0;
			while( count( $colors['colorTitle'] ) -1 >= $i ) {
				$template .= '';
				$template .='<div class="col-md-3"><div class="sg_color">';
					$template .= '<div style="height:150px;background:'.$colors['colorHex'][$i].'"></div>';
					if( isset( $colors['colorTitle'][$i] ) )
						$template .= '<strong class="text-center">'.$colors['colorTitle'][$i].'</strong>';
					$template .= '<p class="text-center">'.$colors['colorHex'][$i].'</p>';
					$template .= '<div class="row colorDefs"><div class="col-xs-6">';
						$template .= '<p><strong>C</strong> ';
							if( isset( $colors['colorC'][$i] ) ) $template .= $colors['colorC'][$i];
						$template .= '</p>';
						$template .= '<p><strong>M</strong> ';
							if( isset( $colors['colorM'][$i] ) ) $template .= $colors['colorM'][$i];
						$template .= '</p>';
						$template .= '<p><strong>Y</strong> ';
							if( isset( $colors['colorY'][$i] ) ) $template .= $colors['colorY'][$i];
						$template .= '</p>';
						$template .= '<p><strong>K</strong> ';
							if( isset( $colors['colorK'][$i] ) ) $template .= $colors['colorK'][$i];
						$template .= '</p>';
					$template .= '</div><div class="col-xs-6">';
						$template .= '<p><strong>R</strong> ';
							if( isset( $colors['colorR'][$i] ) ) $template .= $colors['colorR'][$i];
						$template .= '</p>';
						$template .= '<p><strong>G</strong> ';
							if( isset( $colors['colorG'][$i] ) ) $template .= $colors['colorG'][$i];
						$template .= '</p>';
						$template .= '<p><strong>B</strong> ';
							if( isset( $colors['colorB'][$i] ) ) $template .= $colors['colorB'][$i];
						$template .= '</p>';
					$template .= '</div></div>';		
				$template .= '</div></div>';
				if( $i%4 == 0 && $i !== 0 ) { $template .= '</div><div class="row sg60_colors">'; }
				$i++;
			}
			$template .= '</div>';
		endif;
		return $template;
	}
}

new sg_color_box( 'Colors', true );

?>