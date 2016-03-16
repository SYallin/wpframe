<?php
if (!class_exists('Image_View')){
	class Image_View{
		protected $image;
		protected $size = 'full';
		protected $responsive = false;
		protected $arguments;
		protected $error = false;


		function __construct( $image, $arguments = array(), $responsive = false, $size = 'full' ){
			$this->image = $this->get_attached($image);
			$this->arguments = !empty($arguments) ? $arguments : false;
			$this->responsive = $responsive ? true : false;
			$this->size = $size;
			if(!is_array($this->image) || empty($this->image)){
				$this->error = true;
				//throw new Exception('Unable to parse image');
			}
		}

		protected function get_attached( $image) {
			$a = array();
			$img_post = false;
			if (is_array($image) && array_key_exists('mime_type',$image)){

				$a = $image;

			} elseif(is_object($image) && (!empty($image->post_type) && $image->post_type == 'attachment') && wp_attachment_is_image($image->ID)){

				$img_post = $image;

			} elseif(is_numeric($image)){
				if(!($img_post = get_post($image)) || !wp_attachment_is_image($image)){
					$this->error = true;
					return false;
				}
			}
			if (is_object($img_post) && !is_wp_error($img_post)){
				$thumb_id = 0;
				$a = array(
					'ID'			=> $img_post->ID,
					'title'       	=> $img_post->post_title,
					'filename'    	=> wp_basename( $img_post->guid ),
					'alt'			=> get_post_meta($img_post->ID, '_wp_attachment_image_alt', true),
					'author'		=> $img_post->post_author,
					'description'	=> $img_post->post_content,
					'caption'		=> $img_post->post_excerpt,
					'name'			=> $img_post->post_name,
					'date'			=> $img_post->post_date_gmt,
					'modified'		=> $img_post->post_modified_gmt,
					'mime_type'		=> $img_post->post_mime_type,
					'icon'			=> wp_mime_type_icon( $img_post->ID )
				);
				$src = wp_get_attachment_image_src( $img_post->ID, 'full' );
				$a['url'] = $src[0];
				$a['width'] = $src[1];
				$a['height'] = $src[2];

				if( $sizes = get_intermediate_image_sizes() ) {

					$a['sizes'] = array();

					foreach( $sizes as $size ) {

						$src = wp_get_attachment_image_src( $thumb_id, $size );

						$a['sizes'][ $size ] = $src[0];
						$a['sizes'][ $size . '-width' ] = $src[1];
						$a['sizes'][ $size . '-height' ] = $src[2];

					}

				}

			}
			return $a;

		}

		protected function get_image_sizes( $size = 'full' ) {

			global $_wp_additional_image_sizes;

			$sizes = array();
			$get_intermediate_image_sizes = get_intermediate_image_sizes();
			foreach( $get_intermediate_image_sizes as $_size ) {
					if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
							$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
							$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
							$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
					} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
									'width' => $_wp_additional_image_sizes[ $_size ]['width'],
									'height' => $_wp_additional_image_sizes[ $_size ]['height'],
									'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
							);
					}
			}
			if ( $size ) {
					if( isset( $sizes[ $size ] ) ) {
							return $sizes[ $size ];
					} else {
							return false;
					}
			}
			return $sizes;
		}

		public function get_image_size_url($size = false){
			if (empty($size)){
				$size = $this->size;
			}
			if (!empty($this->image)){
				return (!empty($this->image['sizes'][$size])) ? $this->image['sizes'][$size] : $this->image['url'];
			} else{
				$size_arr = $this->get_image_sizes($size);
				return $size_arr[$size];
			}
		}
		public function is_error(){
			return $this->error;
		}

		public function get_image_html($size=false, $args = false, $response = null){

			$img_size = !empty($size) ? $size : $this->size;
			$img_args = !empty($args) ? $args : $this->arguments;
			$img_respons = ($response !== null) ? $response : $this->responsive;
			$html = false;
			$size_att = '';
			if (!empty($this->image) && is_array($this->image)){
				$attrs = '';
				$src = $this->get_image_size_url($img_size);
				$style = '';
				if ($this->image['mime_type'] == 'image/svg+xml'){
					$style_sizes = $this->get_image_sizes($img_size);
					$style = ' style="max-width: '.$style_sizes['width'].'px; max-height: '.$style_sizes['height'].'px;"';
					if ($img_respons){
						$width = '100%';
						$height = 'auto';
					} else{
						$string = @file_get_contents($this->image['url']);
						if (!empty($string) && function_exists('simplexml_load_string')){
							$xml = simplexml_load_string($string);
							$xmlattributes = $xml->attributes();
							$width = !empty($xmlattributes) ? (float) $xmlattributes->width : 0;
							$height = !empty($xmlattributes) ?(float) $xmlattributes->height : 0;
						}
						if (!empty($width) && !empty($height)){
							$cw = (float)$style_sizes['width']/$width;
							$ch = (float)$style_sizes['height']/$height;
							if ($cw <= $ch){
								$width = round($width*$cw);
								$height = round($height*$cw);
							} else{
								$width = round($width*$ch);
								$height = round($height*$ch);
							}
						} else{
							$width = $style_sizes['width'];
							$height = $style_sizes['height'];
						}
					}
				}else {
					$width = (!empty($this->image['sizes'][$img_size.'-width'])) ? $this->image['sizes'][$img_size.'-width'] : $this->image['width'];
					$height = (!empty($this->image['sizes'][$img_size.'-height'])) ? $this->image['sizes'][$img_size.'-height'] : $this->image['height'];
				}

				if (!empty($img_args['alt'])){
					$alt = esc_attr($img_args['alt']);
				} else{
					$alt = (!empty($this->image['alt'])) ? $this->image['alt'] : $this->image['title'];
				}

				if (is_array($img_args)){
					foreach ($img_args as $key=>$value){
						if (!empty($value) && $key != 'alt'){
							$attrs .= ' '.$key.'="'.$value.'"';
						}
					}
				}
				if ($img_respons){
					$size_att = '';
				} else{
					$size_att = " height=\"$height\" width=\"$width\"";
				}
				$html = "<img{$attrs} src=\"{$src}\"{$size_att} alt=\"{$alt}\"{$style}>";
			}
			return $html;
		}

		// args:
		// array(array('size'=>'full','size2x'=>'full','media'=>'(max-width: 767px)'),..)
		public function get_picture_html($picture_arg = array()){
			$html = false;

			if (!empty($this->image) && is_array($this->image)){
				if (empty($picture_arg)){
					$picture_arg = array(
						array(
							'size' => 'full',
							'size2x' => 'full',
							'media' => '(max-width: 767px)',
						),
						array(
							'size' => 'full',
							'size2x' => 'full',
							'media' => '',
						),
					);
				}
				$alt = (!empty($this->image['alt'])) ? $this->image['alt'] : $this->image['title'];


				$args_text = array();

				if (is_array($this->arguments)){
					foreach($this->arguments as $key => $value){
						$args_text[] = $key.'="'.$value.'"';

					}
					$args_text = is_array($args_text) ? ' '.implode(' ',$args_text) : $args_text;
				} else{
					$args_text = '';
				}

				$small_media = '';
				$normal_media = '';
				$large_media = '';
				$src = (!empty($this->image['sizes'][$this->size])) ? $this->image['sizes'][$this->size] : $this->image['url'];

				$html .= "<picture>\n";
				$html .= "<!--[if IE 9]><video style=\"display: none;\"><![endif]-->\n";
				foreach($picture_arg as $s){
					if ($s['size'] && $s['size2x']){

						$size = (!empty($this->image['sizes'][$s['size']])) ? $this->image['sizes'][$s['size']] : $src;
						$size2x = (!empty($this->image['sizes'][$s['size2x']])) ? $this->image['sizes'][$s['size2x']] : $src;

						$media = (!empty($s['media'])) ? ' media="'.$s['media'].'"' : '';

						$html .= "<source srcset=\"" . $size . ", " . $size2x . " 2x\"". $media .">\n";
					}
				}

				$html .= "<!--[if IE 9]></video><![endif]-->\n";
				$html .= "<img src=\"" . $src . "\" alt=\"" . $alt . "\" " . $args_text . ">";
				$html .= "</picture>\n";
			}

			return $html;
		}
		public function get_image_obj(){
			return $this->image;
		}

		public function setSize($size){
			$this->size = $size;
		}

		public function setResponsive($resp){
			$this->responsive = $resp ? true : false;
		}

		public function setArguments($arguments){
			if(is_array($arguments)){
				$this->arguments = $arguments;
			} else{
				$args = explode('&',$arguments);
				if(is_array($args)){
					$new_args = array();
					foreach($args as $arg){
						$val = explode('=',$arg,2);
						if (count($val) == 2){
							$new_args[$val[0]] = $val[1];
						} else{
							$new_args[$val[0]] = $val[0];
						}
					}
					$this->arguments = $new_args;
				}
			}
		}

	}
}
