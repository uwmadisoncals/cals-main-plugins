<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    ipanorama
 * @subpackage ipanorama/public
 */
class iPanorama_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $plugin_name;
	
	
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $version;

	
	/**
	 * The post type of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $post_type;
	
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $post_type ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->post_type = $post_type;
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.3.0
	 */
	public function enqueue_styles() {
		$plugin_url = plugin_dir_url( dirname(__FILE__) );
		
		wp_enqueue_style( $this->plugin_name . '_ipanorama',    $plugin_url . 'lib/ipanorama.css', array(), $this->version, 'all' );
		//[lite]
		wp_enqueue_style( $this->plugin_name . '_ipanorama_wp', $plugin_url . 'lib/ipanorama.wp.css', array(), $this->version, 'all' );
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.3.0
	 */
	public function register_scripts() {
		$plugin_url = plugin_dir_url( dirname(__FILE__) );
		
		wp_register_script($this->plugin_name . '-three',     $plugin_url . 'lib/three.min.js', array( 'jquery' ), $this->version, true);
		wp_register_script($this->plugin_name . '-ipanorama', $plugin_url . 'lib/jquery.ipanorama.min.js', array( 'jquery' ), $this->version, true);
	}
	
	/**
	 * Print the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.3.0
	 */
	public function print_scripts() {
		global $ipanorama_plugin_shortcode_used;

		if ( ! $ipanorama_plugin_shortcode_used )
			return;

		wp_print_scripts($this->plugin_name . '-three');
		wp_print_scripts($this->plugin_name . '-ipanorama');
	}
	
	/**
	 * Init the edit screen of the plugin post type item
	 *
	 * @since 1.0.0
	 */
	public function public_init() {
		add_shortcode( $this->plugin_name, array( $this , 'shortcode') );
	}
	
	/**
	 * Helper function
	 *
	 * @since 1.3.5
	 */
	private function isLocal($name, $json, $jsonGlobal) {
		if( $jsonGlobal && property_exists($json, 'global') && $json->global->$name ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Helper function
	 *
	 * @since 1.3.5
	 */
	private function getValue($name, $json, $jsonGlobal, $type = 's', $echo = true, $onlyLocal = false) {
		$value = null;
		
		if( !$onlyLocal && $jsonGlobal && property_exists($json, 'global') && $json->global->$name ) {
			if( property_exists($jsonGlobal, $name) ) {
				$value = $jsonGlobal->$name;
			} else {
				return;
			}
		} else if ( property_exists($json, $name) ) {
			$value = $json->$name;
		} else {
			return;
		}
		
		if(!$echo) {
			return $value;
		}
		
		if($type == 's' && !is_null($value)) {
			echo $name . ': "' . $value . '",' . PHP_EOL;
		}
		
		if($type == 'n' && !is_null($value) ) {
			echo $name . ': ' . $value . ',' . PHP_EOL;
		}
		
		if($type == 'b') {
			echo $name . ': ' . ($value ? 'true' : 'false'). ',' . PHP_EOL;
		}
	}
	
	/**
	 * Shortcode output for the plugin
	 *
	 * @since 1.0.0
	 */
	public function shortcode( $atts ) {
		extract(
			shortcode_atts(
				array(
					'id' => 0,
					'slug' => '',
					'width' => NULL,
					'height' => NULL,
					'class' => NULL
				), $atts
			)
		);
		
		if ( !$id && !$slug ) {
			return __('Invalid iPanorama shortcode attributes', $this->plugin_name);
		}
		
		if ( !$id ) {
			$obj = get_page_by_path( $slug, OBJECT, $this->post_type );
			if ( $obj ) {
				$id = $obj->ID;
			} else {
				return __('Invalid iPanorama slug attribute', $this->plugin_name);
			}
		}
		?>
<?php
		// global was set during the rendering of the page
		global $ipanorama_plugin_shortcode_used;
		$ipanorama_plugin_shortcode_used = true;
		
		
		$cfg = html_entity_decode(get_post_meta( $id, 'ipnrm-meta-panorama-cfg', true ), ENT_QUOTES, 'UTF-8');
		$cfgGlobal = html_entity_decode(stripslashes(get_option( 'ipnrm-meta-global-cfg' )), ENT_QUOTES, 'UTF-8');
		
		// make parameters
		$json = json_decode($cfg);
		$jsonGlobal = json_decode($cfgGlobal);
		
		// panorama size
		$panoramaSize   = $this->getValue('panoramaSize', $json, $jsonGlobal, 's', false);
		$panoramaWidth  = $this->getValue('panoramaWidth', $json, $jsonGlobal, 's', false);
		$panoramaHeight = $this->getValue('panoramaHeight', $json, $jsonGlobal, 's', false);
		
		
		$panoramaWidthStyle  = ($width  != NULL ? 'width:'  . $width  . ';' : (($panoramaSize == 'fixed' && $panoramaWidth  > 0) ? 'width:'  . $panoramaWidth  . ';' : ($class ? NULL : 'width:100%;')));
		$panoramaHeightStyle = ($height != NULL ? 'height:' . $height . ';' : (($panoramaSize == 'fixed' && $panoramaHeight > 0) ? 'height:' . $panoramaHeight . ';' : ($class ? NULL : 'height:250px;')));
		
		
		// generate unique prefix for $id to avoid clashes with multiple same shortcode use
		$prefix  = strtolower(wp_generate_password( 5, false ));
		$id_data = 'ipnrm-data-' . $prefix . '-' . $id;
		$id      = 'ipnrm-' . $prefix . '-' . $id;
		
		$upload_dir = wp_upload_dir();
		$baseurl = $upload_dir["baseurl"];
		
		// turn on buffering 
		ob_start();
?>
<?php
	echo '<!-- ipanorama begin -->' . PHP_EOL;
?>
<?php // theme and effects CSS styles
	echo '<style>' . PHP_EOL;
	
	$value = $this->getValue('theme', $json, $jsonGlobal, 's', false);
	
	if(preg_match('/^ipnrm-theme-(.*)?/', $value, $matches)) {
		$theme = $matches[1];
		echo '@import url("' . plugin_dir_url( dirname(__FILE__) ) . 'lib/ipanorama.theme.' . $theme . '.css?ver=' . $this->plugin_name . '");' . PHP_EOL;
	} else {
		$json->theme = 'ipnrm-theme-default';
		echo '@import url("' . plugin_dir_url( dirname(__FILE__) ) . 'lib/ipanorama.theme.default.css?ver=' . $this->plugin_name . '");' . PHP_EOL;
	}
	
	if($this->getValue('popoverShowClass', $json, $jsonGlobal, 's', false) || $this->getValue('popoverHideClass', $json, $jsonGlobal, 's', false)) {
		echo '@import url("' . plugin_dir_url( dirname(__FILE__) ) . 'lib/effect.css?ver=' . $this->plugin_name .  '");' . PHP_EOL;
	}
	
	echo '</style>' . PHP_EOL;
?>
<?php // custom CSS styles
	if( $this->getValue('customCSS', $json, $jsonGlobal, 'b', false) ) { 
		echo '<style>' . PHP_EOL;
		echo $this->getValue('customCSSContent', $json, $jsonGlobal, 's', false, $this->isLocal('customCSS', $json, $jsonGlobal)). PHP_EOL;
		echo '</style>' . PHP_EOL;
	}
?>
<?php // HTML data for scene titles and popover content
	echo '<div id="' . $id_data . '" style="display:none;">' . PHP_EOL;
	
	foreach ($json->scenes as $key => $scene) {
		if( property_exists($scene, 'title') ) {
			echo '<div id="ipnrm-data-scene-' . $prefix . '-' . $key . '">';
			echo $scene->title;
			echo '</div>' . PHP_EOL;
		}
		
		if( property_exists($scene, 'hotSpots') ) {
			$index = 0;
			foreach($scene->hotSpots as $hotspot) {
				if( property_exists($hotspot, 'popoverContent') ) {
					echo '<div id="ipnrm-data-scene-' . $prefix . '-' . $key . '-popover-' . $index . '">';
					echo do_shortcode($hotspot->popoverContent);
					echo '</div>' . PHP_EOL;
				}
				$index = $index + 1;
			}
		}
	}
	
	echo '</div>' . PHP_EOL;
?>
<?php // HTML markup
	if( $panoramaWidthStyle || $panoramaHeightStyle ) {
		echo '<div id="' . $id . '" ' . ($class != NULL ? 'class="' . $class . '"' : '') . 'style="' . $panoramaWidthStyle . $panoramaHeightStyle . '"></div>' . PHP_EOL;
	} else {
		echo '<div id="' . $id . '" ' . ($class != NULL ? 'class="' . $class . '"' : '') . '></div>' . PHP_EOL;
	}
?>
<?php
	echo '<script type="text/javascript">' . PHP_EOL;
	echo 'jQuery( document ).ready(function( jQuery ) {' . PHP_EOL;
		echo 'jQuery( "#' . $id . '" ).ipanorama({' . PHP_EOL;
			$this->getValue('theme', $json, $jsonGlobal, 's');
			$this->getValue('imagePreview', $json, $jsonGlobal, 's');
			$this->getValue('autoLoad', $json, $jsonGlobal, 'b');
			$this->getValue('autoRotate', $json, $jsonGlobal, 'b');
			$this->getValue('autoRotateSpeed', $json, $jsonGlobal, 'n');
			$this->getValue('autoRotateInactivityDelay', $json, $jsonGlobal, 'n');
			$this->getValue('mouseWheelPreventDefault', $json, $jsonGlobal, 'b');
			$this->getValue('mouseWheelRotate', $json, $jsonGlobal, 'b');
			$this->getValue('mouseWheelRotateCoef', $json, $jsonGlobal, 'n');
			$this->getValue('mouseWheelZoom', $json, $jsonGlobal, 'b');
			$this->getValue('mouseWheelZoomCoef', $json, $jsonGlobal, 'n');
			$this->getValue('hoverGrab', $json, $jsonGlobal, 'b');
			$this->getValue('hoverGrabYawCoef', $json, $jsonGlobal, 'n');
			$this->getValue('hoverGrabPitchCoef', $json, $jsonGlobal, 'n');
			$this->getValue('grab', $json, $jsonGlobal, 'b');
			$this->getValue('grabCoef', $json, $jsonGlobal, 'n');
			$this->getValue('showControlsOnHover', $json, $jsonGlobal, 'b');
			$this->getValue('showSceneThumbsCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showSceneMenuCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showSceneNextPrevCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showShareCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showZoomCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showFullscreenCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('showAutoRotateCtrl', $json, $jsonGlobal, 'b');
			$this->getValue('sceneThumbsVertical', $json, $jsonGlobal, 'b');
			$this->getValue('sceneThumbsStatic', $json, $jsonGlobal, 'b');
			$this->getValue('title', $json, $jsonGlobal, 'b');
			$this->getValue('compass', $json, $jsonGlobal, 'b');
			$this->getValue('keyboardNav', $json, $jsonGlobal, 'b');
			$this->getValue('keyboardZoom', $json, $jsonGlobal, 'b');
			$this->getValue('sceneNextPrevLoop', $json, $jsonGlobal, 'b');
			$this->getValue('popover', $json, $jsonGlobal, 'b');
			$this->getValue('popoverTemplate', $json, $jsonGlobal, 's');
			$this->getValue('popoverPlacement', $json, $jsonGlobal, 's');
			$this->getValue('popoverShowTrigger', $json, $jsonGlobal, 's');
			$this->getValue('popoverHideTrigger', $json, $jsonGlobal, 's');
			$this->getValue('popoverShowClass', $json, $jsonGlobal, 's');
			$this->getValue('popoverHideClass', $json, $jsonGlobal, 's');
			$this->getValue('hotSpotBelowPopover', $json, $jsonGlobal, 'b');
			$this->getValue('mobile', $json, $jsonGlobal, 'b');
			if($this->getValue('customJS', $json, $jsonGlobal, 'b', false)) {
				echo 'onLoad: function() {' . PHP_EOL;
				echo $this->getValue('customJSContent', $json, $jsonGlobal, 's', false, $this->isLocal('customJS', $json, $jsonGlobal)) . PHP_EOL;
				echo '},' . PHP_EOL;
			}
			// scenes
			$this->getValue('sceneFadeDuration', $json, $jsonGlobal, 'n');
			$this->getValue('sceneBackgroundLoad', $json, $jsonGlobal, 'b');
			$this->getValue('sceneId', $json, false, 's');
			echo 'scenes: {' . PHP_EOL;
				foreach ($json->scenes as $key => $scene) {
					echo $key . ': {' . PHP_EOL;
					$this->getValue('type', $scene, false, 's');
					$this->getValue('cubeTextureCount', $scene, false, 's');
					$this->getValue('sphereWidthSegments', $scene, false, 'n');
					$this->getValue('sphereHeightSegments', $scene, false, 'n');
					if( $scene->type == 'cube' && property_exists($scene, 'cubeTextureCount') && $scene->cubeTextureCount != 'single') {
						echo 'image: {' . PHP_EOL;
						echo 'front: "' . $scene->image->front . '",' . PHP_EOL;
						echo 'back: "' . $scene->image->back . '",' . PHP_EOL;
						echo 'left: "' . $scene->image->left . '",' . PHP_EOL;
						echo 'right: "' . $scene->image->right . '",' . PHP_EOL;
						echo 'top: "' . $scene->image->top . '",' . PHP_EOL;
						echo 'bottom: "' . $scene->image->bottom . '",' . PHP_EOL;
						echo '},' . PHP_EOL;
					} else {
						echo 'image: "' . $scene->image .'",' . PHP_EOL;
					}
					$this->getValue('thumb', $scene, false, 'b');
					$this->getValue('thumbImage', $scene, false, 's');
					$this->getValue('saveCamera', $scene, false, 'b');
					$this->getValue('pitchLimits', $scene, false, 'b');
					$this->getValue('pitchLimitUp', $scene, false, 'n');
					$this->getValue('pitchLimitDown', $scene, false, 'n');
					$this->getValue('yawLimits', $scene, false, 'b');
					$this->getValue('yawLimitLeft', $scene, false, 'n');
					$this->getValue('yawLimitRight', $scene, false, 'n');
					$this->getValue('yaw', $scene, false, 'n');
					$this->getValue('pitch', $scene, false, 'n');
					$this->getValue('zoom', $scene, false, 'n');
					$this->getValue('compassNorthOffset', $scene, false, 'n');
					if($this->getValue('title', $scene, false, 'b', false)) {
						$this->getValue('titleHtml', $scene, false, 'b');
						echo 'titleSelector: "#ipnrm-data-scene-' . $prefix . '-' . $key . '",' . PHP_EOL;
					}
					if($this->getValue('hotSpots', $scene, false, 'b', false)) {
						echo 'hotSpots: [' . PHP_EOL;
						
						$index = 0;
						foreach($scene->hotSpots as $hotspot) {
							echo '{' . PHP_EOL;
							$this->getValue('yaw', $hotspot, false, 'n');
							$this->getValue('pitch', $hotspot, false, 'n');
							$this->getValue('sceneId', $hotspot, false, 's');
							$this->getValue('className', $hotspot, false, 's');
							if($this->getValue('content', $hotspot, false, 's', false)) {
								$content = $hotspot->content;
								$content = addcslashes($content, "\'");
								$content = str_replace(array("\n", "\t", "\r"),'', $content);
								
								echo 'content: "' . $content . '",' . PHP_EOL;
							}
							$this->getValue('link', $hotspot, false, 's');
							$this->getValue('linkNewWindow', $hotspot, false, 'b');
							$this->getValue('imageUrl', $hotspot, false, 's');
							$this->getValue('imageWidth', $hotspot, false, 'n');
							$this->getValue('imageHeight', $hotspot, false, 'n');
							if($this->getValue('popoverContent', $hotspot, false, 's', false)) {
								echo 'popoverLazyload: false,' . PHP_EOL;
								echo 'popoverSelector: "#ipnrm-data-scene-' . $prefix . '-' . $key . '-popover-' . $index . '",' . PHP_EOL;
								echo 'popoverSelectorDetach: true,' . PHP_EOL;
								$this->getValue('popoverHtml', $hotspot, false, 'b');
								$this->getValue('popoverShow', $hotspot, false, 'b');
								$this->getValue('popoverPlacement', $hotspot, false, 's');
								$this->getValue('popoverWidth', $hotspot, false, 'n');
							}
							
							echo '},' . PHP_EOL;
							$index = $index + 1;
						}
						
						echo ']' . PHP_EOL;
					}
					echo '},' . PHP_EOL;
				}
			echo '}' . PHP_EOL;
			
		echo '});' . PHP_EOL;
	echo '});' . PHP_EOL;
	
	//[lite]
	echo 'function ipnrm_setInfoLabel' . $prefix . '() {' . PHP_EOL;
	echo 'var $el = jQuery( "#' . $id . ' .ipnrm-controls");' . PHP_EOL;
	echo 'if($el.length) {' . PHP_EOL;
	echo 'var link = document.createElement("a");' . PHP_EOL;
	echo 'link.setAttribute("href", "https://1.envato.market/c/1288327/275988/4415?subId1=web&u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fipanorama-360-virtual-tour-builder-for-wordpress%2F17028820");' . PHP_EOL;
	echo 'link.setAttribute("target", "_blank");' . PHP_EOL;
	echo 'link.setAttribute("rel", "nofollow");' . PHP_EOL;
	echo 'link.className = "ipnrm-btn-info";' . PHP_EOL;
	echo '$el.append(link);' . PHP_EOL;
	echo 'return true;' . PHP_EOL;
	echo '}' . PHP_EOL;
	echo 'return false' . PHP_EOL;
	echo '};' . PHP_EOL;
	echo 'var timerId' . $prefix . ' = setInterval(function(){' . PHP_EOL;
	echo 'if(ipnrm_setInfoLabel' . $prefix . '()) {' . PHP_EOL;
	echo 'clearInterval( timerId' . $prefix . ' );' . PHP_EOL;
	echo '}' . PHP_EOL;
	echo '}, 3000);' . PHP_EOL;
	
	echo '</script>' . PHP_EOL;
?>
<?php
	echo '<!-- ipanorama end -->' . PHP_EOL;
?>
		<?php 
		// get the buffered content into a var
		$output = ob_get_contents();
		
		// clean buffer
		ob_end_clean();
		
		return $output;
	}
}