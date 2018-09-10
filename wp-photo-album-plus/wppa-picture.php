<?php
/* wppa-picture.php
* Package: wp-photo-album-plus
*
* Make the picture html
* Version 6.9.12
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
		$title = wppa_zoom_in( $id );
	}

	// Create the html. To prevent mis-alignment of the audio control bar or to escape from the <a> tag for the pan controlbar
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
				wppa_get_lb_panorama_full_html( $id ) .
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

// Get full html for a lightbox pan image, e.g. ' data-panorama="'..."' for use in lightbox anchor link
function wppa_get_lb_panorama_full_html( $id ) {

	$result = wppa_get_lb_panorama_html( $id );
	if ( $result ) {
		return ' data-panorama="' . esc_attr( $result ) . '"';
	}
	else {
		return '';
	}
}

// Get the html for a lightbox pan image
function wppa_get_lb_panorama_html( $id ) {

	return wppa_get_panorama_html( array( 'id' => $id, 'lightbox' 	=> true, ) );
}

// Get the html for a pan image
function wppa_get_panorama_html( $args ) {

	// If no id given, quit
	if ( ! isset( $args['id'] ) ) return;

	$args['controls'] = ( wppa_opt( 'panorama_control' ) == 'all' ) || ( wppa_opt( 'panorama_control' ) == 'mobile' && wppa_is_mobile() );
	$args['manual'] = wppa_opt( 'panorama_manual' ) == 'all' ? true : false;
	$args['autorun'] = wppa_opt( 'panorama_autorun' ) == 'none' ? '' : wppa_opt( 'panorama_autorun' );
	$args['autorunspeed'] = wppa_opt( 'panorama_autorun_speed' );
	$args['zoomsensitivity'] = wppa_opt( 'panorama_wheel_sensitivity' );

	switch( wppa_is_panorama( $args['id'] ) ) {

		case '1':
			$result = wppa_get_spheric_pan_html( $args );

			// Save we have a spheric panorama on board for loading THREE.js
			wppa( 'has_panorama', true );
			break;
		case '2':
			$result = wppa_get_flat_pan_html( $args );
			break;
		default:
			$result = '';
	}

	return $result;
}

// Spheric 360deg pan
function wppa_get_spheric_pan_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 				=> '0',
							'mocc' 				=> '0',
							'width' 			=> false,
							'height' 			=> false,
							'haslink' 			=> false,
							'lightbox' 			=> 0,
							'controls' 			=> true,
							'autorun' 			=> '',
							'manual' 			=> true,
							'autorunspeed' 		=> '3',
							'zoomsensitivity' 	=> '3',

						);

	$args 				= wp_parse_args( $args, $defaults );

	$id 				= strval( intval ( $args['id'] ) );
	$mocc 				= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$width 				= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 			= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 			= $args['haslink'];
	$icsiz 				= wppa_opt( 'nav_icon_size_panorama' );
	$iconsize 			= $icsiz . 'px;';
	$lightbox 			= $args['lightbox'];
	$controls 			= $args['controls'];
	$autorun 			= $args['autorun'];
	$manual 			= $args['manual'];
	$autorunspeed 		= $args['autorunspeed'];
	$zoomsensitivity 	= $args['zoomsensitivity'];

	$url 				= esc_url( wppa_is_mobile() ? wppa_get_photo_url( $id ) : wppa_get_hires_url( $id ) );

	$result =
	( $lightbox ? $id . '.' : '' ) .
	( $haslink ? '</a>' : '' ) .
	( $lightbox ? '<div id="wppa-ovl-pan-container" >' : '' ) .
	'<div
		id="wppa-pan-div-' . $mocc . '"
		class="wppa-pan-div wppa-pan-div-' . $mocc . '"
		style="' . ( $controls ? 'margin-bottom:4px;' : '' ) . ( $manual ? 'cursor:grab;': '' ) . 'line-height:0;"
		>
	</div>' .
	( $controls ?
	'<div
		id="wppa-pctl-div-' . $mocc . '"
		class="wppa-pctl-div wppa-pctl-div-' . $mocc . '"
		style="text-align:center;"
		>' .
		( $lightbox ?
			'<span
				id="wppa-pctl-prev-' . $mocc . '"
				class="wppa-pan-prevnext"
				style="margin:0 2px 0 0;float:left;"
				>' .
				wppa_get_svghtml( 'Prev-Button', $iconsize, true ) .
			'</span>'
			:
			''
		) .
		'<span
			id="wppa-pctl-left-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Left-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-right-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Right-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-up-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Up-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-down-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Down-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-zoomin-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'ZoomIn', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-zoomout-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'ZoomOut', $iconsize, true ) .
		'</span>' .
		( $lightbox ?
			'<span
				id="wppa-pctl-next-' . $mocc . '"
				class="wppa-pan-prevnext"
				style="margin:0 0 0 2px;float:right;"
				>' .
				wppa_get_svghtml( 'Next-Button', $iconsize, true ) .
			'</span>'
			:
			''
		) .
	'</div>'
	:
	'' ) .

	( $lightbox ? '</div>' : '' ) .

	'<script>' .

		// Create image object and add the image url to it
		'var image' . $mocc . ' = new Image();
		image' . $mocc . '.src = "' . $url . '";' .

		// When document complete, run the main proc
		'jQuery(document).ready(function(){wppaDoSphericPanorama' . $mocc . '();});' .

		// The main proccedure
		'function wppaDoSphericPanorama' . $mocc . '(){' .

			// Wait until the image file has been completely loaded
			'if (!image' . $mocc . '.complete){setTimeout( wppaDoSphericPanorama' . $mocc . ', 100 );return;};' .

			// Var declarations
			'var
			$ 				= jQuery,
			uniqueId,
			manualControl 	= false,
			longitude 		= 180,
			latitude 		= 0,
			savedX,
			savedY,
			savedLongitude,
			savedLatitude,' .
			( $autorun == 'right' ? 'deltaX = 0.05 * ' . $autorunspeed . ' / 3,' : '' ) .
			( $autorun == 'left' ? 'deltaX = -0.05 * ' . $autorunspeed . ' / 3,' : '' ) .
			( $autorun == '' ? 'deltaX = 0,' : '' ) .
			'deltaY 		= 0,
			deltaFov 		= 0,
			fov 			= 75,
			abort 			= false,
			aspect 			= ' . $width / $height . ',
			div 			= $( "#wppa-pan-div-' . $mocc . '" ),
			left 			= $("#wppa-pctl-left-' . $mocc . '" ),
			right 			= $("#wppa-pctl-right-' . $mocc . '" ),
			up 				= $("#wppa-pctl-up-' . $mocc . '" ),
			down 			= $("#wppa-pctl-down-' . $mocc . '" ),
			zoomin 			= $("#wppa-pctl-zoomin-' . $mocc . '" ),
			zoomout 		= $("#wppa-pctl-zoomout-' . $mocc . '" ),
			prev 			= $("#wppa-pctl-prev-' . $mocc . '" ),
			next 			= $("#wppa-pctl-next-' . $mocc . '" );' .

			// Setting the global id, indicating the most recent invocation
			( $lightbox ? '
				wppaGlobalOvlPanoramaId++;
				uniqueId = wppaGlobalOvlPanoramaId;' :
				'' ) .

			// Lghtbox uses the global vars used for the webGL context
			( $lightbox ?
				// setting up the renderer
				'if ( ! wppaRenderer ) {
					wppaRenderer = new THREE.WebGLRenderer();
				}
				wppaRenderer.setSize(' . $width . ', ' . $height . ');
				$(div).append(wppaRenderer.domElement);' .

				// Creating a new scene if not yet available
				'if ( ! wppaScene ) {
					wppaScene = new THREE.Scene();
				}' .

				// Adding a camera
				'if ( ! wppaCamera ) {
					wppaCamera = new THREE.PerspectiveCamera(fov, aspect, 1, 1000);
					wppaCamera.target = new THREE.Vector3(0, 0, 0);
				}' .

				// Creation of a big sphere geometry
				'if ( ! wppaSphere ) {
					wppaSphere = new THREE.SphereGeometry(100, 100, 40);
					wppaSphere.applyMatrix(new THREE.Matrix4().makeScale(-1, 1, 1));
				}' .

				// Creation of the sphere material
				'if ( ! wppaSphereMaterial ) {
					wppaSphereMaterial = new THREE.MeshBasicMaterial();
				}
				wppaSphereMaterial.map = THREE.ImageUtils.loadTexture("' . $url . '");' .

				// geometry + material = mesh (actual object)
				'if ( ! wppaSphereMesh ) {
					wppaSphereMesh = new THREE.Mesh(wppaSphere, wppaSphereMaterial);
					wppaScene.add(wppaSphereMesh);
				}
				' :

				// setting up the wpparenderer
				'var wppaRenderer = new THREE.WebGLRenderer();
				wppaRenderer.setSize(' . $width . ', ' . $height . ');' .

				// Place the element
				'$(div).append(wppaRenderer.domElement);' .

				// Creating a new scene
				'var wppaScene = new THREE.Scene();' .

				// Adding a camera
				'var wppaCamera = new THREE.PerspectiveCamera(fov, aspect, 1, 1000);
				wppaCamera.target = new THREE.Vector3(0, 0, 0);' .

				// Creation of a big sphere geometry
				'var wppaSphere = new THREE.SphereGeometry(100, 100, 40);
				wppaSphere.applyMatrix(new THREE.Matrix4().makeScale(-1, 1, 1));' .

				// Creation of the sphere material
				'var wppaSphereMaterial = new THREE.MeshBasicMaterial();
				wppaSphereMaterial.map = THREE.ImageUtils.loadTexture("' . $url . '");' .

				// geometry + material = mesh (actual object)
				'var wppaSphereMesh = new THREE.Mesh(wppaSphere, wppaSphereMaterial);
				wppaScene.add(wppaSphereMesh);'
			) .

			// listeners
			( wppa_is_mobile() ?
				'$(right).on("touchstart", onRightMouseDown);
				$(right).on("touchend", onButtonUp);
				$(left).on("touchstart", onLeftMouseDown);
				$(left).on("touchend", onButtonUp);
				$(up).on("touchstart", onUpMouseDown);
				$(up).on("touchend", onButtonUp);
				$(down).on("touchstart", onDownMouseDown);
				$(down).on("touchend", onButtonUp);
				$(zoomin).on("touchstart", onZoomInMouseDown);
				$(zoomin).on("touchend", onButtonUp);
				$(zoomout).on("touchstart", onZoomOutMouseDown);
				$(zoomout).on("touchend", onButtonUp);'
				:
				( $manual ?
				'$(div).on("mousedown", onDivMouseDown);
				$(div).on("mousemove", onDivMouseMove);
				$(div).on("mouseup", onDivMouseUp);
				document.getElementById("wppa-pan-div-' . $mocc . '").addEventListener("wheel", onDivWheel);' : '' ) .
				'$(right).on("mousedown", onRightMouseDown);
				$(right).on("mouseup", onButtonUp);
				$(left).on("mousedown", onLeftMouseDown);
				$(left).on("mouseup", onButtonUp);
				$(up).on("mousedown", onUpMouseDown);
				$(up).on("mouseup", onButtonUp);
				$(down).on("mousedown", onDownMouseDown);
				$(down).on("mouseup", onButtonUp);
				$(zoomin).on("mousedown", onZoomInMouseDown);
				$(zoomin).on("mouseup", onButtonUp);
				$(zoomout).on("mousedown", onZoomOutMouseDown);
				$(zoomout).on("mouseup", onButtonUp);
				'
			) .

			// Common event handlers
			( $lightbox ? '
				if (prev) {
					prev.on("click", panPrev);
					next.on("click", panNext);
				}' : '' ) .

			// Install Resize hanler
			'$(window).on("DOMContentLoaded load resize orientationchange",onResize);' .

			// Resize
			'onResize();' .

			// Remove spinner
			'$("#wppa-ovl-spin").hide();' .

			// Doit!
			'render();' .

			// The rendering function
			'function render(){' .

				// See if a lightbox instance has to die
				( $lightbox ? 'if ( ! wppaOvlOpen || wppaOvlActivePanorama != ' . $id . ' || wppaGlobalOvlPanoramaId > uniqueId ) abort=true;' : '' ) .

				// If the abort flag is risen, die gracefully
				'if(abort){
					return;
				}' .

				'requestAnimationFrame(render);

				if ( ! manualControl ) {
					longitude += deltaX;
					latitude += deltaY;
				}' .

				// limiting latitude from -85 to 85 (cannot point to the sky or under your feet)
				'latitude = Math.max(-85, Math.min(85, latitude));' .

				// moving the wppaCamera according to current latitude (vertical movement) and longitude (horizontal movement)
				'wppaCamera.target.x = 500 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.cos(THREE.Math.degToRad(longitude));
				wppaCamera.target.y = 500 * Math.cos(THREE.Math.degToRad(90 - latitude));
				wppaCamera.target.z = 500 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.sin(THREE.Math.degToRad(longitude));
				wppaCamera.lookAt(wppaCamera.target);' .

				// calling again render function
				'wppaRenderer.render(wppaScene, wppaCamera);
			}' .

			// Mouse wheel
			'function onDivWheel(e) {
				e.preventDefault();
				deltaFov=-e.deltaY * ' . $zoomsensitivity . ' / 6;
				doZoom(true);
			}' .

			// Zoom in/out
			'function doZoom(once){
				fov += deltaFov;
				fov = Math.max(20, Math.min(120, fov));
				wppaCamera = new THREE.PerspectiveCamera(fov, aspect, 1, 1000);
				wppaCamera.target = new THREE.Vector3(0, 0, 0);
				if ( ! once && deltaFov != 0 ) {
					setTimeout(function(){doZoom()}, 25);
				}
				if ( once ) {
					deltaFov = 0;
				}
			}' .

			// Previous
			'function panPrev(e) {
				var stop;
				if ( ! stop ) {
					stop = true;
					$(this).css({opacity:0.5});
					$("#wppa-overlay-ic").css({display:"none"});
					$("#wppa-ovl-spin").show();
					wppaOvlShowPrev();
				}
			}' .

			// Next
			'function panNext(e) {
				var stop;
				if ( ! stop ) {
					stop = true;
					$(this).css({opacity:0.5});
					$("#wppa-overlay-ic").css({display:"none"});
					$("#wppa-ovl-spin").show();
					wppaOvlShowNext();
				}
			}' .

			// Manual movement on the image div
			'function onDivMouseDown(e){
				e.preventDefault();
				manualControl = true;
				savedX = e.clientX;
				savedY = e.clientY;
				savedLongitude = longitude;
				savedLatitude = latitude;
			}' .
			'function onDivMouseMove(e){
				if(manualControl){
					longitude = (savedX - e.clientX) * 0.1 + savedLongitude;
					latitude = (e.clientY - savedY) * 0.1 + savedLatitude;
				}
			}' .
			'function onDivMouseUp(e){
				manualControl = false;
				deltaX=0;
			}' .

			// Horizontal movement by buttons
			'function onRightMouseDown(e) {
				deltaX=0.2;
			}' .
			'function onLeftMouseDown(e) {
				deltaX=-0.2;
			}' .

			// Vertical movement by buttons
			'function onUpMouseDown(e) {
				deltaY=0.2;
			}' .
			'function onDownMouseDown(e) {
				deltaY=-0.2;
			}' .

			// Zooming
			'function onZoomInMouseDown(e) {
				deltaFov=-0.4;
				doZoom();
			}' .
			'function onZoomOutMouseDown(e) {
				deltaFov=0.4;
				doZoom();
			}' .

			// Release a button resets all deltas
			'function onButtonUp(e) {
				deltaX=0;
				deltaY=0;
				deltaFov=0;
			}' .

			// When a (responsive) resize is required, we resize the wppaScene
			'function onResize(e){' .

				( $lightbox ?

					// Show image container
					'$("#wppa-overlay-ic").css("display", "");
					$("#wppa-overlay-ic").css("width", "");' .

					// There are 4 possiblilities: all combi of 'Width is the limit or not' and 'Mode is normal or fullscreen'
					'var widthIsLim,
						modeIsNormal = wppaOvlMode == "normal";' .

					// Find container dimensions dependant of mode
					'var contWidth,	contHeight;

					if ( modeIsNormal ) {
						contWidth = window.innerWidth ? window.innerWidth : screen.width;
						contHeight = window.innerHeight ? window.innerHeight : screen.height;
					}
					else {
						contWidth = screen.width;
						contHeight = screen.height;
					}'.
//					alert("contWidth="+contWidth);' .

					// Initialize new display sizes
					'var newWidth,
						newHeight,
						topMarg,
						leftMarg,
						extraX = 8,
						extraY = 8 + ' . ( $controls ? $icsiz + 10 : 0 ) . ' + 30;' .

					// Add borderwidth in case of mode == normal
					'if ( modeIsNormal ) {
						extraX += 2 * ' . wppa_opt( 'ovl_border_width' ) . ';
						extraY += 2 * ' . wppa_opt( 'ovl_border_width' ) . ';
					}
					' .

					// Find out if the width is the limitng dimension
					'widthIsLim = ( contHeight > ( ( ( contWidth  - extraX ) / 2 ) + extraY ) );' .

					// Compute new sizes and margins
					'if ( widthIsLim ) {
						newWidth = contWidth - extraX;
						newHeight = newWidth / 2;
						topMarg = ( contHeight - newHeight - extraY ) / 2 + 20;' .
					'}
					else {
						newHeight = contHeight - extraY;
						newWidth = newHeight * 2;
						topMarg = 20;' .

					'}
					newWidth = parseInt(newWidth);
					newHeight = parseInt(newHeight);
					' .

					// Set css common for all 4 situations
					'$("#wppa-ovl-pan-container").css({marginTop:topMarg});
					$("#wppa-overlay-ic").css({marginTop:0});' .

					// Now set css for all 4 situations: Mode is normal
					'if ( modeIsNormal ) {' .

						// Common for mode normal
						'$("#wppa-ovl-pan-container").css({
							backgroundColor:"' . wppa_opt( 'ovl_theme' ) . '",
							padding:"' . wppa_opt( 'ovl_border_width' ) . 'px",
							borderRadius:"' . wppa_opt( 'ovl_border_radius' ) . 'px",
							width:newWidth,
								marginLeft:0
						});
						$( "#wppa-pctl-div-' . $mocc . '" ).css({marginLeft:0});' .

						// Limit specific
						'if ( widthIsLim ) {
							$("#wppa-overlay-ic").css({marginLeft:4});
						}
						else {
							$("#wppa-overlay-ic").css({marginLeft:(contWidth-newWidth)/2});
						}
					}' .

					// Mode is fullscreen
					'else {' .

						// Common for mode fullscreen
						'$("#wppa-overlay-ic").css({marginLeft:0});
						$("#wppa-ovl-pan-container").css({
							backgroundColor:"transparent",
							padding:0,
							borderRadius:0,
							width:newWidth,
							marginLeft:(contWidth-newWidth)/2
						});

						if ( widthIsLim ) {
							$("#wppa-pctl-div-' . $mocc . '").css({marginLeft:0});
						}
						else {
							$("#wppa-pctl-div-' . $mocc . '").css({marginLeft:0});
						}
					}

					wppaRenderer.setSize(newWidth, newHeight);
					doZoom(true);
				' :
				'
					var containerwidth = $(div).parent().width();
					var newWidth = containerwidth;
					var newHeight = newWidth * ' . ( $height / $width ) . ';
					wppaRenderer.setSize(newWidth, newHeight);
					doZoom(true);'
				) . '
			}' .
		'};

	</script>
	' . ( $haslink ? '<a>' : '' ) . '';

	return wppa_pan_min( $result );
}

// Non 360 flat pan
function wppa_get_flat_pan_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 				=> '0',
							'mocc' 				=> '0',
							'width' 			=> false,
							'height' 			=> false,
							'haslink' 			=> false,
							'lightbox' 			=> 0,
							'controls' 			=> true,
							'autorun' 			=> '',
							'manual' 			=> true,
							'autorunspeed' 		=> '3',
							'zoomsensitivity' 	=> '3',
						);

	$args 		= wp_parse_args( $args, $defaults );

	$id 				= strval( intval ( $args['id'] ) );
	$mocc 				= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$width 				= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 			= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 			= $args['haslink'];
	$icsiz 				= wppa_opt( 'nav_icon_size_panorama' );
	$iconsize 			= $icsiz . 'px;';
	$lightbox 			= $args['lightbox'];
	$controls 			= $args['controls'];
	$autorun 			= $args['autorun'];
	$manual 			= $args['manual'];
	$autorunspeed 		= $args['autorunspeed'];
	$zoomsensitivity 	= $args['zoomsensitivity'];

	switch ( $autorun ) {
		case 'right':
			$deltaX = $autorunspeed / 3;
			break;
		case 'left':
			$deltaX = - $autorunspeed / 3;
			break;
		default:
			$deltaX = '0';
	}

	$url 				= esc_url( wppa_is_mobile() ? wppa_get_photo_url( $id ) : wppa_get_hires_url( $id ) );

	$result =
	( $lightbox ? $id . '.' : '' ) .
	( $haslink ? '</a>' : '' ) .

	// The overall container
	( $lightbox ? '<div id="wppa-ovl-pan-container" >' : '' ) .

	// The canvas container
	'<div
		id="wppa-pan-div-' . $mocc . '"
		class="wppa-pan-div wppa-pan-div-' . $mocc . '"
		style="' . ( $controls ? 'margin-bottom:4px;' : '' ) . 'line-height:0;"
		>' .

		// The actual drawing area
		'<canvas
			id="wppa-pan-canvas-' . $mocc . '"
			style="background-color:black;' . ( $manual ? 'cursor:grab;' : '' ) . '"
			width="' . $width . '"
			height="' . ( $width / 2 ) . '"
		></canvas>' .

		// The preview image
		'<canvas
			id="wppa-pan-prev-canvas-' . $mocc . '"
			style="margin-top:4px;background-color:black;"
			width="' . $width . '"
			height=' . $height . '"
		></canvas>

	</div>' .

	// The controlbar
	( $controls ?
	'<div
		id="wppa-pctl-div-' . $mocc . '"
		class="wppa-pctl-div wppa-pctl-div-' . $mocc . '"
		style="text-align:center;"
		>' .
		( $lightbox ?
			'<span
				id="wppa-pctl-prev-' . $mocc . '"
				class="wppa-pan-prevnext"
				style="margin:0 2px 0 0;float:left;"
				>' .
				wppa_get_svghtml( 'Prev-Button', $iconsize, true ) .
			'</span>'
			:
			''
		) .
		'<span
			id="wppa-pctl-left-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Left-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-right-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Right-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-up-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Up-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-down-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'Down-4', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-zoomin-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'ZoomIn', $iconsize, true ) .
		'</span>
		<span
			id="wppa-pctl-zoomout-' . $mocc . '"
			style="margin:0 2px;"
			>' .
			wppa_get_svghtml( 'ZoomOut', $iconsize, true ) .
		'</span>' .
		( $lightbox ?
			'<span
				id="wppa-pctl-next-' . $mocc . '"
				class="wppa-pan-prevnext"
				style="margin:0 0 0 2px;float:right;"
				>' .
				wppa_get_svghtml( 'Next-Button', $iconsize, true ) .
			'</span>'
			:
			''
		) .
	'</div>'
	:
	'' ) .

	( $lightbox ? '</div>' : '' ) .

	'<script>' .

		// Create image object and add the image url to it
		'var image' . $mocc . ' = new Image();
		image' . $mocc . '.src = "' . $url . '";' .

		// When document complete, run the main proc
		'jQuery(document).ready(function(){wppaDoFlatPanorama' . $mocc . '();});' .

		// The main proccedure
		'function wppaDoFlatPanorama' . $mocc . '(){' .

			// Wait until the image file has been completely loaded
			'if (!image' . $mocc . '.complete){setTimeout( wppaDoFlatPanorama' . $mocc . ', 100 );return;}' .

			// Var declarations
			'var
			$ 				= jQuery,
			manualControl 	= false,
			zoomsensitivity = ' . $zoomsensitivity . ',
			deltaX 			= ' . $deltaX . ',
			deltaY 			= 0,
			deltaFactor 	= 1.0,
			autorun 		= ' . ( $autorun ? 'true' : 'false' ) . ',
			run 			= deltaX ? 5 : 4,
			busy 			= false,
			abort 			= false,
			div 			= $("#wppa-pan-div-' . $mocc . '"),
			canvas 			= document.getElementById("wppa-pan-canvas-' . $mocc . '"),
			prevCanvas 		= document.getElementById("wppa-pan-prev-canvas-' . $mocc . '"),
			left 			= $("#wppa-pctl-left-' . $mocc . '"),
			right 			= $("#wppa-pctl-right-' . $mocc . '"),
			up 				= $("#wppa-pctl-up-' . $mocc . '"),
			down 			= $("#wppa-pctl-down-' . $mocc . '"),
			zoomin 			= $("#wppa-pctl-zoomin-' . $mocc . '"),
			zoomout 		= $("#wppa-pctl-zoomout-' . $mocc . '"),
			prev 			= $("#wppa-pctl-prev-' . $mocc . '"),
			next 			= $("#wppa-pctl-next-' . $mocc . '"),
			canvasWidth 	= $(div).parent().width(),
			canvasHeight 	= canvasWidth / 2,
			savedCanvasX 	= 0,
			savedCanvasY 	= 0,
			fromHeight 		= image' . $mocc . '.height / 2,
			fromWidth 		= fromHeight * 2,
			fromX 			= ( image' . $mocc . '.width - fromWidth ) / 2,
			fromY 			= ( image' . $mocc . '.height - fromHeight ) / 2,
			centerX 		= fromX + fromWidth / 2,
			centerY 		= fromY + fromHeight / 2;' .

			// Install listeners
			( wppa_is_mobile() ?
				'right.on("touchstart", onRightMouseDown);
				right.on("touchend", onButtonUp);
				left.on("touchstart", onLeftMouseDown);
				left.on("touchend", onButtonUp);
				up.on("touchstart", onUpMouseDown);
				up.on("touchend", onButtonUp);
				down.on("touchstart", onDownMouseDown);
				down.on("touchend", onButtonUp);
				zoomin.on("touchstart", onZoomInMouseDown);
				zoomin.on("touchend", onButtonUp);
				zoomout.on("touchstart", onZoomOutMouseDown);
				zoomout.on("touchend", onButtonUp);'
				:
				( $manual ?
				'canvas.addEventListener("mousedown", onCanvasMouseDown);
				canvas.addEventListener("mousemove", onCanvasMouseMove);
				canvas.addEventListener("mouseup", onCanvasMouseUp);
				canvas.addEventListener("mouseout", onCanvasMouseUp);
				document.getElementById("wppa-pan-canvas-' . $mocc . '").addEventListener("wheel", onDivWheel);
				prevCanvas.addEventListener("mousedown", onCanvasMouseDown);
				prevCanvas.addEventListener("mousemove", onPrevCanvasMouseMove);
				prevCanvas.addEventListener("mouseup", onCanvasMouseUp);
				prevCanvas.addEventListener("mouseout", onCanvasMouseUp);' : '' ) .
				'right.on("mousedown", onRightMouseDown);
				right.on("mouseup", onButtonUp);
				left.on("mousedown", onLeftMouseDown);
				left.on("mouseup", onButtonUp);
				up.on("mousedown", onUpMouseDown);
				up.on("mouseup", onButtonUp);
				down.on("mousedown", onDownMouseDown);
				down.on("mouseup", onButtonUp);
				zoomin.on("mousedown", onZoomInMouseDown);
				zoomin.on("mouseup", onButtonUp);
				zoomout.on("mousedown", onZoomOutMouseDown);
				zoomout.on("mouseup", onButtonUp);'
			) .

			// Common event handlers
			( $lightbox ? '
				$("#wppa-fulls-btn").on("click", function(){abort=true;});
				if (prev) {
					prev.on("click", panPrev);
					next.on("click", panNext);
				}' : '' ) .

			// Install Resize handler
			'$(window).on("DOMContentLoaded load resize orientationchange",onResize' . $mocc . ');' .

			// Remove spinner
			'$("#wppa-ovl-spin").hide();' .

			// Do the rendering
			'render();' .

			// Resize
			'onResize' . $mocc . '();' .

			// The render function
			'function render(){' .

				( $lightbox ? 'if (!wppaOvlOpen) abort=true;' :'' ) .
				'if (abort) {
					ctx = null;
					prevctx = null;
					return;
				}
				if (run==0) return;
				if (busy) return;
				busy = true;' .

				( $lightbox ? 'if ( wppaOvlActivePanorama != ' . $id . ' ) return;' : '' ) .

				// manualControl is true when a drag on the canvas is being performed
				'if(!manualControl){' .

					// Panning
					'fromX += deltaX;
					fromY += deltaY;' .

					// Zooming
					'var newHeight = fromHeight / deltaFactor;
					var newWidth = fromWidth / deltaFactor;' .

					// Keep zooming in range
					'if ( deltaFactor != 1 && newHeight <= image' . $mocc . '.height && newHeight > 50 ) {
						fromX -= ( newWidth - fromWidth ) / 2;
						fromY -= ( newHeight - fromHeight ) / 2;
						fromWidth = newWidth;
						fromHeight = newHeight;
					}
				}' .

				// Keep viewport within image boundaries
				'fromX = Math.max(0, Math.min(image' . $mocc . '.width-fromWidth, fromX));' .
				'fromY = Math.max(0, Math.min(image' . $mocc . '.height-fromHeight, fromY));' .

				// Check for turningpoint in case autrun
				'if ( autorun ) {
					if ( fromX == 0 || fromX == ( image' . $mocc . '.width-fromWidth ) ) {
						deltaX *= -1;
					}
				}' .

				// Draw the image
				'var ctx = canvas.getContext("2d");' .
				'ctx.drawImage(image' . $mocc . ',fromX,fromY,fromWidth,fromHeight,0,0,canvas.width,canvas.height);' .

				// Draw the preview image
				'var prevctx = prevCanvas.getContext("2d");' .
				'prevctx.clearRect(0, 0, prevCanvas.width, prevCanvas.height);' .
				'prevctx.drawImage(image' . $mocc . ',0,0,image' . $mocc . '.width,image' . $mocc . '.height,0,0,prevCanvas.width,prevCanvas.height);' .

				// Draw viewport rect on preview image
				'var factor = prevCanvas.width / image' . $mocc . '.width;' .
				'prevctx.strokeRect(factor*fromX,factor*fromY,factor*fromWidth,factor*fromHeight);' .

				// Done so far
				'busy = false;' .

				// Re-render if needed
				'if (run>0) {' .
					'if (manualControl||autorun){setTimeout(function(){render()},25);}' .
					'else {setTimeout(function(){render()},5);}' .
				'}
				if(run<5)run--;' .
			'}' .

			// Previous
			'function panPrev(e) {
				e.preventDefault();
				abort = true;
				var stop;
				if ( ! stop ) {
					stop = true;
					$(this).css({opacity:0.5});
					$("#wppa-overlay-ic").css({display:"none"});
					$("#wppa-ovl-spin").show();
					wppaOvlShowPrev();
				}
			}' .

			// Next
			'function panNext(e) {
				e.preventDefault();
				abort = true;
				var stop;
				if ( ! stop ) {
					stop = true;
					$(this).css({opacity:0.5});
					$("#wppa-overlay-ic").css({display:"none"});
					$("#wppa-ovl-spin").show();
					wppaOvlShowNext();
				}
			}' .

			// Horizontal movement by button
			'function onRightMouseDown(e){
				e.preventDefault();
				run=5;deltaX=3;render();
			}' .

			'function onLeftMouseDown(e){
				e.preventDefault();
				run=5;deltaX=-3;render();
			}' .

			// Vertical movement by button
			'function onUpMouseDown(e){
				e.preventDefault();
				run=5;deltaY=-3;render();
			}' .

			'function onDownMouseDown(e){
				e.preventDefault();
				run=5;deltaY=3;render();
			}' .

			// Zooming
			'function onZoomInMouseDown(e){
				e.preventDefault();
				run=5;deltaFactor=1.005;render();
			}' .

			'function onZoomOutMouseDown(e){
				e.preventDefault();
				run=5;deltaFactor=0.995;render();
			}' .

			// Mouse wheel
			'function onDivWheel(e) {
				e.preventDefault();
				run=(autorun?5:4);
				deltaFactor = 1 + e.deltaY * zoomsensitivity / 1000;
				if ( ! autorun ) render();
				setTimeout(function(){deltaFactor = 1}, 25);
			}' .

			// When a navigation button is released, stop and reset all deltas
			'function onButtonUp(e) {
				e.preventDefault();
				deltaX=0;deltaY=0;deltaFactor=1;
				if ( ! ' . $lightbox . ' ) run--;
				run=4;
			}' .

			// When a (responsive) resize is required, we resize the wppaScene
			'function onResize' . $mocc . '(e){' .

				'if (abort) return;' .

				( $lightbox ?

					// Show image container
					'$("#wppa-overlay-ic").css("display", "");' .

					// There are 4 possiblilities: all combi of 'Width is the limit or not' and 'Mode is normal or fullscreen'
					'var widthIsLim,
						modeIsNormal = wppaOvlMode == "normal";' .

					// First find container dimensions dependant of mode
					'var contWidth,	contHeight;

					if ( modeIsNormal ) {
						contWidth = window.innerWidth ? window.innerWidth : screen.width;
						contHeight = window.innerHeight ? window.innerHeight : screen.height;
					}
					else {
						contWidth = screen.width;
						contHeight = screen.height;
					}
					newWidth = parseInt(newWidth);
					newHeight = parseInt(newHeight);
					' .

					// Initialize new display sizes
					'var newWidth,
						newHeight,
						topMarg,
						leftMarg,
						extraX = 8,
						extraY = 24 + ' . ( $controls ? $icsiz : 0 ) . ' + contWidth * ' . $height . ' / ' . $width . ' + 40;' .

					// Add borderwidth in case of mode == normal
					'if ( modeIsNormal ) {
						extraX += 2 * ' . wppa_opt( 'ovl_border_width' ) . ';
						extraY += 2 * ' . wppa_opt( 'ovl_border_width' ) . ';
					}
					' .

					// Find out if the width is the limitng dimension
					'widthIsLim = ( contHeight > ( ( contWidth / 2 ) + extraY ) );' .

					// Compute new sizes and margins
					'if ( widthIsLim ) {
						newWidth = contWidth - extraX;
						newHeight = newWidth / 2;
						topMarg = ( contHeight - newHeight - extraY ) / 2 + 20;' .
					'}
					else {
						newWidth = 2 * ( contHeight - ' . ( $controls ? $icsiz : 0 ) . ' - 24 - 40 ) / ( 1 + 2 * ' . $height . ' / ' . $width . ' );
						newHeight = newWidth / 2;
						topMarg = 20;' .

					'}' .

					// Set css common for all 4 situations
					'$("#wppa-ovl-pan-container").css({marginTop:topMarg});
					$("#wppa-overlay-ic").css({marginTop:0});

					canvas.width = newWidth;
					canvas.height = newHeight;
					prevCanvas.width = newWidth;
					prevCanvas.height = newWidth * ' . $height . ' / ' . $width . ';' .

					// Now set css for all 4 situations: Mode is normal
					'if ( modeIsNormal ) {' .

						// Common for mode normal
						'$("#wppa-ovl-pan-container").css({
							backgroundColor:"' . wppa_opt( 'ovl_theme' ) . '",
							padding:"' . wppa_opt( 'ovl_border_width' ) . 'px",
							borderRadius:"' . wppa_opt( 'ovl_border_radius' ) . 'px",
							width:newWidth,
								marginLeft:0
						});
						$( "#wppa-pctl-div-' . $mocc . '" ).css({marginLeft:0});' .

						// Limit specific
						'if ( widthIsLim ) {
							$("#wppa-overlay-ic").css({marginLeft:4});
						}
						else {
							$("#wppa-overlay-ic").css({marginLeft:(contWidth-newWidth)/2});
						}

					}' .

					// Mode is fullscreen
					'else {' .

						// Common for mode fullscreen
						'$("#wppa-overlay-ic").css({marginLeft:0});
						$("#wppa-ovl-pan-container").css({
							backgroundColor:"transparent",
							padding:0,
							borderRadius:0,
							width:newWidth,
							marginLeft:(contWidth-newWidth)/2
						});
						$("#wppa-pctl-div-' . $mocc . '").css({marginLeft:0});' .

						/*

						if ( widthIsLim ) {
							$("#wppa-pctl-div-' . $mocc . '").css({marginLeft:0});
						}
						else {
							$("#wppa-pctl-div-' . $mocc . '").css({marginLeft:(contWidth-newWidth)/2});

						} */ '
					}' .

					'run=(autorun?5:4);
					render();

				' :
				'canvasWidth = $(div).parent().width();' .
				'canvasHeight = canvasWidth / 2;' .
				'canvas.width = canvasWidth;' .
				'canvas.height = canvasHeight;' .
				'prevCanvas.width = canvasWidth;' .
				'prevCanvas.height = canvasWidth * ' . $height . ' / ' . $width . ';' .
				'run=(autorun?5:4);' .
				'render();'
				) .

			'}' .

			// when the mouse is pressed on the canvas, we switch to manual control and save current coordinates
			'function onCanvasMouseDown(e){

				e.preventDefault();

				manualControl = true;

				savedCanvasX = e.offsetX;
				savedCanvasY = e.offsetY;

				run=5;
				render();

			}' .

			'function onCanvasMouseMove(e){

				var factor = canvas.width / fromWidth;

				if ( manualControl ){

					var x = ( savedCanvasX - e.offsetX ) / factor + fromX;
					var y = ( savedCanvasY - e.offsetY ) / factor + fromY;

					if ( x > 0 && y > 0 && ( x + fromWidth ) < image' . $mocc . '.width && ( y + fromHeight ) < image' . $mocc . '.height ) {

						fromX = x;
						fromY = y;

						savedCanvasX = e.offsetX;
						savedCanvasY = e.offsetY;
					}
				}
			}' .

			'function onPrevCanvasMouseMove(e){

				var factor = prevCanvas.width / image' . $mocc . '.width;

				if (e.offsetX > factor * fromX &&
					e.offsetX < factor * ( fromX + fromWidth ) &&
					e.offsetY > factor * fromY &&
					e.offsetY < factor * ( fromY + fromHeight ) ) {

					$(prevCanvas).css("cursor","grab");
				}
				else {
					$(prevCanvas).css("cursor","default");
				}

				if ( manualControl && !busy ){

					if (e.offsetX > factor * fromX &&
						e.offsetX < factor * ( fromX + fromWidth ) &&
						e.offsetY > factor * fromY &&
						e.offsetY < factor * ( fromY + fromHeight ) ) {

						fromX = ( e.offsetX - savedCanvasX ) / factor + fromX;
						fromY = ( e.offsetY - savedCanvasY ) / factor + fromY;

						savedCanvasX = e.offsetX;
						savedCanvasY = e.offsetY;

					}
				}
			}' .

			'function onCanvasMouseUp(e){

				if ( manualControl ) {
					run=4;
					manualControl = false;
				}
			}' .
		'}

	</script>
	' . ( $haslink ? '<a>' : '' ) . '';

	return wppa_pan_min( $result );
}

// Minimize inine mixed html / js code
function wppa_pan_min( $result ) {
//	return $result; // debug
//  wppa_log('dbg','voor len='.strlen($result));

	// Remove tabs
	$result = str_replace( "\t", '', $result );

	// Remove newlines
	$result = str_replace( array( "\r\n", "\n\r", "\n", "\r" ), ' ', $result );

	// Trim operators
	$result = str_replace( array( ' = ',' + ',' * ',' / ' ), array( '=','+','*','/' ), $result );

	// Replace multiple spaces by one
	$olen = 0;
	$nlen = strlen( $result );
	do {
		$olen = $nlen;
		$result = str_replace( '  ', ' ', $result );
		$nlen = strlen( $result );
	} while ( $nlen != $olen );

	// Trim , ; and !
	$result = str_replace( array( ', ', '; ', '! ' ), array( ',', ';', '!' ), $result );

	// Trim braces
	$result = str_replace( array(  ' ) ', ') ', ' )' ), ')', $result );
	$result = str_replace( array(  ' ( ', '( ', ' (' ), '(', $result );

	// Remove space between html tags
	$result = str_replace( '> <', '><', $result );

// 	wppa_log('dbg',' na len='.strlen($result));
// 	wppa_dump($result);
	return $result;
}