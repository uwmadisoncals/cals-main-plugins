<?php
/* wppa-picture.php
* Package: wp-photo-album-plus
*
* Make the picture html
* Version 6.9.07
*
*/


// This function creates the html for the picture. May be photo, video, audio or photo with audio.
// The size will always be set to 100% width, so the calling wrapper div should take care of sizing.
// This function can be used for both resposive and static displays.
//
// Minimum requirements for input args:
//
// - id, The photo id ( numeric photo db table id )
// - type, Any one of the supported display types: sphoto, mphoto, xphoto, ( to be extended )
//
// Optional args:
//
// - class: Any css class specification.
//
// Returns: The html, or false on error.
// In case of error a red debug message will be printed directly to the output stream.
//
// Additional action: viewcount is bumped by this function if the displayed image is not a thumbnail sized one.
//
function wppa_get_picture_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 		=> '0',
							'type' 		=> '',
							'class' 	=> '',
							'width' 	=> false,
							'height' 	=> false,
						);
	$args 		= wp_parse_args( $args, $defaults );

	$id 		= strval( intval ( $args['id'] ) );
	$type 		= $args['type'];
	$class 		= $args['class'];

	// Check existance of required args
	foreach( array( 'id', 'type' ) as $item ) {
		if ( ! $args[$item] ) {
			wppa_dbg_msg( 'Missing ' . $item . ' in call to wppa_get_picture_html()', 'red' );
			return false;
		}
	}

	// Check validity of args
	if ( ! wppa_photo_exists( $id ) ) {
		wppa_dbg_msg( 'Photo ' . $id . ' does not exist in call to wppa_get_picture_html(). Type = ' . $type, 'red', 'force' );
		return false;
	}
	$types = array(	'sphoto', 		// Single image with optional border like slideshow border
					'mphoto',		// Media type like single image. Caption should be provided in wrappping div
					'xphoto',		// Like xphoto with extended features
					'cover', 		// Album cover image
					'thumb',		// Normal tumbnail
					'ttthumb',		// Topten
					'comthumb',		// Comment widget
					'fthumb',		// Filmthumb
					'twthumb',		// Thumbnail widget
					'ltthumb',		// Lasten widget
					'albthumb',		// Album widget
					);
	if ( ! in_array( $type, $types ) ) {
		wppa_dbg_msg( 'Unimplemented type ' . $type . ' in call to wppa_get_picture_html()', 'red', 'force' );
		return false;
	}

	// Get other data
	$link 		= wppa_get_imglnk_a( $type, $id );
	$isthumb 	= strpos( $type, 'thumb' ) !== false;
	$file 		= $isthumb ? wppa_get_thumb_path( $id ) : wppa_get_photo_path( $id );
	if ( $args['width'] && $args['height'] ) {
		$href 	= $isthumb ? wppa_get_thumb_url( $id, true, '', $args['width'], $args['height'] ) :
							 wppa_get_photo_url( $id, true, '', $args['width'], $args['height'] );
	}
	else {
		$href 	= $isthumb ? wppa_get_thumb_url( $id ) : wppa_get_photo_url( $id );
	}
	$autocol 	= wppa( 'auto_colwidth' ) || ( wppa( 'fullsize' ) > 0 && wppa( 'fullsize' ) <= 1.0 );
	$title 		= $link ? esc_attr( $link['title'] ) : esc_attr( stripslashes( wppa_get_photo_name( $id ) ) );
	$alt 		= wppa_get_imgalt( $id );

	// Find image style
	switch ( $type ) {
		case 'sphoto':
			$style = 'width:100%;margin:0;';
			if ( ! wppa_in_widget() ) {
				switch ( wppa_opt( 'fullimage_border_width' ) ) {
					case '':
						$style .= 	'padding:0;' .
									'border:none;';
						break;
					case '0':
						$style .= 	'padding:0;' .
									'border:1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';' .
									'box-sizing:border-box;';
						break;
					default:
						$style .= 	'padding:' . ( wppa_opt( 'fullimage_border_width' ) - '1' ) . 'px;' .
									'border:1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';' .
									'box-sizing:border-box;' .
									'background-color:' . wppa_opt( 'bgcolor_fullimg' ) . ';';

						// If we do round corners...
						if ( wppa_opt( 'bradius' ) > '0' ) {

							// then also here
							$style .= 'border-radius:' . wppa_opt( 'fullimage_border_width' ) . 'px;';
						}
				}
			}
			break;
		case 'mphoto':
		case 'xphoto':
			$style = 'width:100%;margin:0;padding:0;border:none;';
			break;
		default:
			wppa_dbg_msg( 'Style for type ' . $type . ' is not implemented yet in wppa_get_picture_html()', 'red', 'force' );
			return false;

	}
	if ( $link['is_lightbox'] ) {
//		$style .= 'cursor:' . wppa_wait() . ';'; //url( ' . wppa_get_imgdir() . wppa_opt( 'magnifier' ) . ' ),pointer;';
		$title = wppa_zoom_in( $id );
	}

	// Create the html. To prevent mis-alignment of the audio control bar or to escape from the <a> tag for the panorama controlbar
	// we wrap it in a div with zero fontsize and lineheight.
	$result = '<div style="font-size:0;line-height:0;" >';

	// The link
	if ( $link ) {

		// Link is lightbox
		if ( $link['is_lightbox'] ) {
			$lbtitle 	= wppa_get_lbtitle( $type, $id );
			$videobody 	= esc_attr( wppa_get_video_body( $id ) );
			$audiobody 	= esc_attr( wppa_get_audio_body( $id ) );
			$videox 	= wppa_get_videox( $id );
			$videoy 	= wppa_get_videoy( $id );
			$result .=
			'<a' .
				' href="' . $link['url'] . '"' .
				( $lbtitle ? ' ' . wppa( 'lbtitle' ) . '="'.esc_attr($lbtitle).'"' : '' ) .
				( $videobody ? ' data-videohtml="' . $videobody . '"' : '' ) .
				( $audiobody ? ' data-audiohtml="' . $audiobody . '"' : '' ) .
				( $videox ? ' data-videonatwidth="' . $videox . '"' : '' ) .
				( $videoy ? ' data-videonatheight="' . $videoy . '"' : '' ) .
				' ' . wppa( 'rel' ) . '="'.wppa_opt( 'lightbox_name' ).'"' .
				( $link['target'] ? ' target="' . $link['target'] . '"' : '' ) .
				' class="thumb-img"' .
				' id="a-' . $id . '-' . wppa( 'mocc' ) . '"' .
				' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
				' style="cursor:' . wppa_wait() . ';"' .
				' onclick="return false;"' .
				' >';
		}

		// Link is NOT lightbox
		else {
			$result .=
			'<a' .
				( wppa_is_mobile() ?
					' ontouchstart="wppaStartTime();" ontouchend="wppaTapLink(\'' . $id . '\',\'' . $link['url'] . '\');" ' :
					' onclick="_bumpClickCount( \'' . $id . '\' );window.open(\'' . $link['url'] . '\', \'' . $link['target'] . '\' )"'
				) .
				' title="' . $link['title'] . '"' .
				' class="thumb-img"' .
				' id="a-' . $id . '-' . wppa( 'mocc' ) . '"' .
				' style="cursor:pointer;"' .
				' >';
		}
	}

	// The image
	// Panorama? Only if browser supports html5
	if ( wppa_is_panorama( $id ) && wppa_browser_can_html5() ) {
		$result .= wppa_get_panorama_html( array( 	'id' 		=> $id,
													'width' 	=> $args['width'],
													'height'	=> $args['height'],
													'haslink' 	=> $link,
												) );
	}

	// Video?
	elseif ( wppa_is_video( $id ) ) {
		$result .=
		wppa_get_video_html( array( 'id' 		=> $id,
									'controls' 	=> ! $link,
									'style' 	=> $style,
									'class' 	=> $class,
								)
							);

	}

	// No video, just a photo
	else {
		$result .=
		'<img' .
			' id="ph-' . $id . '-' . wppa( 'mocc' ) . '"' .
			' src="' . $href . '"' .
			' ' . wppa_get_imgalt( $id ) .
			( $class ? ' class="' . $class . '" ' : '' ) .
			( $title ? ' title="' . $title . '" ' : '' ) .
			' style="' . $style . '"' .
		' />';
	}

	// Close the link
	if ( $link ) {
		$result .= '</a>';
	}

	// Add audio?			sphoto
	if ( wppa_has_audio( $id ) ) {

		$result .= '<div style="position:relative;z-index:11;" >';

		// Find style for audio controls
		switch ( $type ) {
			case 'sphoto':
				$pad = ( wppa_opt( 'fullimage_border_width' ) === '' ) ? 0 : wppa_opt( 'fullimage_border_width' );
				$bot = ( wppa_opt( 'fullimage_border_width' ) === '' ) ? 0 : wppa_opt( 'fullimage_border_width' );

				$style = 	'margin:0;' .
							'padding:0 ' . $pad . 'px;' .
							'bottom:' . $bot .'px;';

				$class = 	'size-medium wppa-sphoto wppa-sphoto-' . wppa( 'mocc' );
				break;
			case 'mphoto':
			case 'xphoto':
				$style = 	'margin:0;' .
							'padding:0;' .
							'bottom:0;';
				$class = 	'size-medium wppa-' . $type . ' wppa-' . $type . '-' . wppa( 'mocc' );
				break;
			default:
				$style = 	'margin:0;' .
							'padding:0;';

				$class = 	'';
		}

		// Get the html for audio
		$result .= wppa_get_audio_html( array(	'id' 		=> 	$id,
												'cursor' 	=> 	'cursor:pointer;',
												'style' 	=> 	$style .
																'position:absolute;' .
																'box-sizing:border-box;' .
																'width:100%;' .
																'border:none;' .
																'height:' . wppa_get_audio_control_height() . 'px;' .
																'border-radius:0;',
												'class' 	=> 	$class,
											)
									);
		$result .= '</div>';
	}

	$result .= '</div>';

	// Update statistics
	if ( ! wppa_in_widget() ) {
		wppa_bump_viewcount( 'photo', $id );
	}

	// Done !
	return $result;
}

// Get the html for a panorama image
function wppa_get_panorama_html( $args ) {
	
	if ( ! isset( $args['id'] ) ) return;
	
	switch( wppa_is_panorama( $args['id'] ) ) {
		
		case '1':
			$result = wppa_get_spheric_panorama_html( $args );
			break;
		case '2':
			$result = wppa_get_flat_panorama_html( $args );
			break;
		default:
			$result = '';		
	}
	
	return $result;
}

// Spheric 360deg panorama
function wppa_get_spheric_panorama_html( $args ) {
	
	// Init
	$defaults 	= array( 	'id' 		=> '0',
							'mocc' 		=> '0',
							'width' 	=> false,
							'height' 	=> false,
							'haslink' 	=> false,
						);

	$args 		= wp_parse_args( $args, $defaults );

	$id 		= strval( intval ( $args['id'] ) );
	$mocc 		= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$width 		= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 	= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 	= $args['haslink'];
	$iconsize 	= '32px';

	$url 	= wppa_get_hires_url( $id );

	$result =
	( $haslink ? '</a>' : '' ) .
	'<div' .
		' id="wppa-panorama-div-' . $mocc . '"' .
		' class="wppa-panorama-div wppa-panorama-div-' . $mocc . '"' .
		' style="margin-bottom:4px;"' .
		' >' .
	'</div>' .
	'<div' .
		' id="wppa-panoramacontrol-div-' . $mocc . '"' .
		' class="wppa-panoramacontrol-div wppa-panoramacontrol-div-' . $mocc . '"' .
		' style="text-align:center;margin-bottom:4px;"' .
		' >' .
		'<span' .
			' id="wppa-panoramacontrol-left-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Left-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-right-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Right-4', $iconsize ) . 
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-up-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Up-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-down-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Down-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-zoomin-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'ZoomIn', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-zoomout-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'ZoomOut', $iconsize ) .
		'</span>' .
	'</div>' .
	'<script>' .

		'jQuery(document).ready(function(){' .

			'var manualControl = false,' .
				'longitude = 0,' .
				'latitude = 0,' .
				'savedX,' .
				'savedY,' .
				'savedLongitude,' .
				'savedLatitude,' .
				'deltaX = 0,' .
				'deltaY = 0,' .
				'deltaFov = 0,' .
				'fov = 75,' .
				'run = true,' .
				'busy = false,' .
				'aspect = ' . $width / $height . ',' .
				'div = document.getElementById("wppa-panorama-div-' . $mocc . '"),' .
				'left = document.getElementById("wppa-panoramacontrol-left-' . $mocc . '"),' .
				'right = document.getElementById("wppa-panoramacontrol-right-' . $mocc . '"),' .
				'up = document.getElementById("wppa-panoramacontrol-up-' . $mocc . '"),' .
				'down = document.getElementById("wppa-panoramacontrol-down-' . $mocc . '"),' .
				'zoomin = document.getElementById("wppa-panoramacontrol-zoomin-' . $mocc . '"),' .
				'zoomout = document.getElementById("wppa-panoramacontrol-zoomout-' . $mocc . '");' .

			// setting up the renderer
			'renderer' . $mocc . ' = new THREE.WebGLRenderer();' .
			'renderer' . $mocc . '.setSize(' . $width . ', ' . $height . ');' .

			// Place the element
			'div.appendChild(renderer' . $mocc . '.domElement);' .

			// creating a new scene
			'var scene = new THREE.Scene();' .

			// adding a camera
			'var camera = new THREE.PerspectiveCamera(fov, aspect, 1, 1000);' .
			'camera.target = new THREE.Vector3(0, 0, 0);' .

			// creation of a big sphere geometry
			'var sphere = new THREE.SphereGeometry(100, 100, 40);' .
			'sphere.applyMatrix(new THREE.Matrix4().makeScale(-1, 1, 1));' .

			// creation of the sphere material
			'var sphereMaterial = new THREE.MeshBasicMaterial();' .
			'sphereMaterial.map = THREE.ImageUtils.loadTexture("' . $url . '");' .

			// geometry + material = mesh (actual object)
			'var sphereMesh = new THREE.Mesh(sphere, sphereMaterial);' .
			'scene.add(sphereMesh);' .

			// listeners
			( wppa_is_mobile() ? /*
				div.addEventListener("touchstart", onDivMouseDown, false);
				div.addEventListener("touchmove", onDivMouseMove, false);
				div.addEventListener("touchend", onDivMouseUp, false); */
				'right.addEventListener("touchstart", function(){run=true;deltaX=0.2;render();}, false);' .
				'right.addEventListener("touchend", function(){run=false;deltaX=0}, false);' .
				'left.addEventListener("touchstart", function(){run=true;deltaX=-0.2;render();}, false);' .
				'left.addEventListener("touchend", function(){run=false;deltaX=0}, false);' .
				'up.addEventListener("touchstart", function(){run=true;deltaY=0.2;render();}, false);' .
				'up.addEventListener("touchend", function(){run=false;deltaY=0}, false);' .
				'down.addEventListener("touchstart", function(){run=true;deltaY=-0.2;render();}, false);' .
				'down.addEventListener("touchend", function(){run=false;deltaY=0}, false);' .
				'zoomin.addEventListener("touchstart", function(){run=true;deltaFov=-0.2;doZoom();render();}, false);' .
				'zoomin.addEventListener("touchend", function(){run=false;deltaFov=0}, false);' .
				'zoomout.addEventListener("touchstart", function(){run=true;deltaFov=0.2;doZoom();render();}, false);' .
				'zoomout.addEventListener("touchend", function(){run=false;deltaFov=0}, false);'
				: 
				'div.addEventListener("mousedown", onDivMouseDown, false);' .
				'div.addEventListener("mousemove", onDivMouseMove, false);' .
				'div.addEventListener("mouseup", onDivMouseUp, false);' .
				'right.addEventListener("mousedown", function(){run=true;deltaX=0.2;render();}, false);' .
				'right.addEventListener("mouseup", function(){run=false;deltaX=0}, false);' .
				'left.addEventListener("mousedown", function(){run=true;deltaX=-0.2;render();}, false);' .
				'left.addEventListener("mouseup", function(){run=false;deltaX=0}, false);' .
				'up.addEventListener("mousedown", function(){run=true;deltaY=0.2;render();}, false);' .
				'up.addEventListener("mouseup", function(){run=false;deltaY=0}, false);' .
				'down.addEventListener("mousedown", function(){run=true;deltaY=-0.2;render();}, false);' .
				'down.addEventListener("mouseup", function(){run=false;deltaY=0}, false);' .
				'zoomin.addEventListener("mousedown", function(){run=true;deltaFov=-0.2;doZoom();render();}, false);' .
				'zoomin.addEventListener("mouseup", function(){run=false;deltaFov=0}, false);' .
				'zoomout.addEventListener("mousedown", function(){run=true;deltaFov=0.2;doZoom();render();}, false);' .
				'zoomout.addEventListener("mouseup", function(){run=false;deltaFov=0}, false);'
			) .
			
			// Init and Resize hanler
			'jQuery(window).on("DOMContentLoaded load resize scroll",onResize' . $mocc . ');' .

			// Doit!
			'render();' .

			'function render(){

				if(!run)return;
				if(busy)return;
				busy = true;

				requestAnimationFrame(render);

				if(!manualControl){
					longitude += deltaX;
					latitude += deltaY;
				}' .

				// limiting latitude from -85 to 85 (cannot point to the sky or under your feet)
				'latitude = Math.max(-85, Math.min(85, latitude));' .

				// moving the camera according to current latitude (vertical movement) and longitude (horizontal movement)
				'camera.target.x = 500 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.cos(THREE.Math.degToRad(longitude));
				camera.target.y = 500 * Math.cos(THREE.Math.degToRad(90 - latitude));
				camera.target.z = 500 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.sin(THREE.Math.degToRad(longitude));
				camera.lookAt(camera.target);' .

				// calling again render function
				'renderer' . $mocc . '.render(scene, camera);

				busy = false;
			}' .

			// Zoom in/out
			'function doZoom(){

				fov += deltaFov;
				fov = Math.max(20, Math.min(120, fov));
				camera = new THREE.PerspectiveCamera(fov, aspect, 1, 1000);
				camera.target = new THREE.Vector3(0, 0, 0);
				if (run) setTimeout(function(){doZoom()}, 25);
			}' .

			// when the mouse is pressed, we switch to manual control and save current coordinates
			'function onDivMouseDown(event){

				event.preventDefault();

				manualControl = true;

				savedX = event.clientX;
				savedY = event.clientY;

				savedLongitude = longitude;
				savedLatitude = latitude;

				run=true;
				render();

			}' .

			// when the mouse moves, if in manual contro we adjust coordinates
			'function onDivMouseMove(event){

				if(manualControl){
					longitude = (savedX - event.clientX) * 0.1 + savedLongitude;
					latitude = (event.clientY - savedY) * 0.1 + savedLatitude;
				}

			}' .

			// when the mouse is released, we turn manual control off
			'function onDivMouseUp(event){

				manualControl = false;
				run=false;

			}' .
			
			// When a (responsive) resize is required, we resize the scene
			'function onResize' . $mocc . '(event){
				var containerwidth = div.parentNode.clientWidth;
				var newWidth = containerwidth;
				var newHeight = newWidth * ' . ( $height / $width ) . ';
				renderer' . $mocc . '.setSize(newWidth, newHeight);
				run=true;
				render();
				doZoom();
				run=false;
				
			}' .

		'});

	</script>
	' . ( $haslink ? '<a>' : '' ) . '';

	return $result;
}

// Non 360 flat panorama
function wppa_get_flat_panorama_html( $args ) {
	
	// Init
	$defaults 	= array( 	'id' 		=> '0',
							'mocc' 		=> '0',
							'width' 	=> false,
							'height' 	=> false,
							'haslink' 	=> false,
						);

	$args 		= wp_parse_args( $args, $defaults );

	$id 		= strval( intval ( $args['id'] ) );
	$mocc 		= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$width 		= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 	= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 	= $args['haslink'];
	$iconsize 	= '32px';

	$url 	= wppa_get_hires_url( $id );

	$result =
	( $haslink ? '</a>' : '' ) .
	
	// The container
	'<div' .
		' id="wppa-panorama-div-' . $mocc . '"' .
		' class="wppa-panorama-div wppa-panorama-div-' . $mocc . '"' .
		' style="margin-bottom:4px;"' .
		' >' .
		
		// The actual drawing area
		'<canvas' .
			' id="wppa-panorama-canvas-' . $mocc . '"' .
			' style="width:100%;height:50%;"' .
			' >' .		
		'</canvas>' .
		
		// The hidden image
		'<img' .
			' id="wppa-panorama-img-' . $mocc . '"' .
			' src="' . $url . '"' .
			' style="display:none;position:fixed;max-width:100000px;max-height:10000px;"' .
		' />' .
	'</div>' .
	
	// The controlbar
	'<div' .
		' id="wppa-panoramacontrol-div-' . $mocc . '"' .
		' class="wppa-panoramacontrol-div wppa-panoramacontrol-div-' . $mocc . '"' .
		' style="text-align:center;margin-bottom:4px;"' .
		' >' .
		'<span' .
			' id="wppa-panoramacontrol-left-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Left-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-right-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Right-4', $iconsize ) . 
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-up-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Up-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-down-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'Down-4', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-zoomin-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'ZoomIn', $iconsize ) .
		'</span>' .
		'<span' .
			' id="wppa-panoramacontrol-zoomout-' . $mocc . '"' .
			' style="margin:2px;"' .
			' >' .
			wppa_get_svghtml( 'ZoomOut', $iconsize ) .
		'</span>' .
	'</div>' .
	
	// The preview image
	'<img' .
		' id="wppa-panorama-prev-img-' . $mocc . '"' .
		' src="' . $url . '"' .
		' style="width:100%;"' .
	' />' .
		
	'<script>' .

		'jQuery(document).ready(function(){' .
		
			'var manualControl = false,' .
				'zoomFactor = 1.0,' .
				'deltaX = 0,' .
				'deltaY = 0,' .
				'deltaFactor = 1.0,' .
				'run = true,' .
				'busy = false,' .
				'div = document.getElementById("wppa-panorama-div-' . $mocc . '"),' .
				'canvas = document.getElementById("wppa-panorama-canvas-' . $mocc . '"),' .
				'image = document.getElementById("wppa-panorama-img-' . $mocc . '"),' .
				'left = document.getElementById("wppa-panoramacontrol-left-' . $mocc . '"),' .
				'right = document.getElementById("wppa-panoramacontrol-right-' . $mocc . '"),' .
				'up = document.getElementById("wppa-panoramacontrol-up-' . $mocc . '"),' .
				'down = document.getElementById("wppa-panoramacontrol-down-' . $mocc . '"),' .
				'zoomin = document.getElementById("wppa-panoramacontrol-zoomin-' . $mocc . '"),' .
				'zoomout = document.getElementById("wppa-panoramacontrol-zoomout-' . $mocc . '"),' .
				'imgWidth = jQuery(image).width(),' .
				'imgHeight = jQuery(image).height(),' .
				'canvasWidth = div.parentNode.clientWidth,' .
				'canvasHeight = canvasWidth / 2;' .
//				'scaleFactor = imgHeight / canvasHeight;' .
				
				
				
				// listeners
				( wppa_is_mobile() ? /*
					div.addEventListener("touchstart", onDivMouseDown, false);
					div.addEventListener("touchmove", onDivMouseMove, false);
					div.addEventListener("touchend", onDivMouseUp, false); */
					'right.addEventListener("touchstart", function(){run=true;deltaX=0.2;render();}, false);' .
					'right.addEventListener("touchend", function(){run=false;deltaX=0}, false);' .
					'left.addEventListener("touchstart", function(){run=true;deltaX=-0.2;render();}, false);' .
					'left.addEventListener("touchend", function(){run=false;deltaX=0}, false);' .
					'up.addEventListener("touchstart", function(){run=true;deltaY=0.2;render();}, false);' .
					'up.addEventListener("touchend", function(){run=false;deltaY=0}, false);' .
					'down.addEventListener("touchstart", function(){run=true;deltaY=-0.2;render();}, false);' .
					'down.addEventListener("touchend", function(){run=false;deltaY=0}, false);' .
					'zoomin.addEventListener("touchstart", function(){run=true;deltaFov=-0.2;doZoom();render();}, false);' .
					'zoomin.addEventListener("touchend", function(){run=false;deltaFov=0}, false);' .
					'zoomout.addEventListener("touchstart", function(){run=true;deltaFov=0.2;doZoom();render();}, false);' .
					'zoomout.addEventListener("touchend", function(){run=false;deltaFov=0}, false);'
					: 
//					'div.addEventListener("mousedown", onDivMouseDown, false);' .
//					'div.addEventListener("mousemove", onDivMouseMove, false);' .
//					'div.addEventListener("mouseup", onDivMouseUp, false);' .
					'right.addEventListener("mousedown", function(){run=true;deltaX=2;render();}, false);' .
					'right.addEventListener("mouseup", function(){run=false;deltaX=0}, false);' .
					'left.addEventListener("mousedown", function(){run=true;deltaX=-2;render();}, false);' .
					'left.addEventListener("mouseup", function(){run=false;deltaX=0}, false);' .
					'up.addEventListener("mousedown", function(){run=true;deltaY=-2;render();}, false);' .
					'up.addEventListener("mouseup", function(){run=false;deltaY=0}, false);' .
					'down.addEventListener("mousedown", function(){run=true;deltaY=2;render();}, false);' .
					'down.addEventListener("mouseup", function(){run=false;deltaY=0}, false);' .
					'zoomin.addEventListener("mousedown", function(){run=true;deltaFactor=1.01;render();}, false);' .
					'zoomin.addEventListener("mouseup", function(){run=false;deltaFactor=1;}, false);' .
					'zoomout.addEventListener("mousedown", function(){run=true;deltaFactor=0.99;render();}, false);' .
					'zoomout.addEventListener("mouseup", function(){run=false;deltaFactor=1;}, false);'
				) .

				// Init and Resize handler
				'jQuery(window).on("DOMContentLoaded load resize scroll",onResize' . $mocc . ');' .

				'
				var fromHeight = imgHeight;
				var fromWidth = fromHeight * 2;
				
				var savedFromX = ( imgWidth - fromWidth ) / 2;
				var savedFromY = ( imgHeight - fromHeight ) / 2;
				
				var scaleFactor = imgHeight / canvasHeight;
				' .
				
				// Doit!
				'render();' .

				// The render function
				'function render(){
					if (!run) return;
					if (busy) return;
					busy = true;
					if(!manualControl){' .
					
						// Panning
						'savedFromX += deltaX;
//						savedFromX = Math.max(0, Math.min(imgWidth-fromWidth, savedFromX));
						savedFromY += deltaY;
//						savedFromY = Math.max(0, Math.min(imgHeight-fromHeight, savedFromY));' .
						
						// Zooming
						'
//				wppaConsoleLog(zoomFactor+" "+deltaFactor+" "+zoomFactor * deltaFactor);
						zoomFactor = zoomFactor * deltaFactor;
//				wppaConsoleLog( zoomFactor+" "+deltaFactor);
						zoomFactor = Math.max(1, Math.min(10, zoomFactor));
						fromX = savedFromX + ( zoomFactor - 1 ) * scaleFactor*fromWidth/zoomFactor / 4; 
						fromY = savedFromY + ( zoomFactor - 1 ) * scaleFactor*fromHeight/zoomFactor / 4; 
						
						fromX = Math.max(0, Math.min(imgWidth-fromWidth, fromX));
						fromY = Math.max(0, Math.min(imgHeight-fromHeight, fromY));
					
					}
					var context = canvas.getContext("2d");

//wppaConsoleLog(fromX+", "+fromY+", "+scaleFactor*fromWidth/zoomFactor+", "+scaleFactor*fromHeight/zoomFactor+", 0, 0, canvasWidth="+canvasWidth+", canvasHeight="+canvasHeight+", zoomFactor="+zoomFactor);
					context.drawImage(image,
											fromX,
											fromY,
											scaleFactor*fromWidth/zoomFactor,
											scaleFactor*fromHeight/zoomFactor,
											0,0,canvasWidth,canvasHeight);
					busy = false;
					if (run) setTimeout(function(){render()},25);
				}' .
				
				// When a (responsive) resize is required, we resize the scene
				'function onResize' . $mocc . '(event){

					canvasWidth = div.parentNode.clientWidth;
					canvasHeight = canvasWidth / 2;
					scaleFactor = imgHeight / canvasHeight;
				
					run=true;
					render();
					run=false;
					
				}' .

		'});
		
	</script>
	' . ( $haslink ? '<a>' : '' ) . '';

	return $result;
}
