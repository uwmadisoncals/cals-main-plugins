<?php
/* wppa-exif-iptc-common.php
* Package: wp-photo-album-plus
*
* exif and iptc common functions
* version 6.7.12
*
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Translate iptc tags into  photo dependant data inside a text
function wppa_filter_iptc($desc, $photo) {
global $wpdb;
global $wppa_iptc_labels;
global $wppa_iptc_cache;

	if ( strpos($desc, '2#') === false ) return $desc;	// No tags in desc: Nothing to do

	// Get te labels if not yet present
	if ( ! is_array( $wppa_iptc_labels ) ) {
		$wppa_iptc_labels = $wpdb->get_results( "SELECT * FROM `" . WPPA_IPTC . "` WHERE `photo` = '0' ORDER BY `tag`", ARRAY_A );
	}

	// If in cache, use it
	$iptcdata = false;
	if ( is_array( $wppa_iptc_cache ) ) {
		if ( isset( $wppa_iptc_cache[$photo] ) ) {
			$iptcdata = $wppa_iptc_cache[$photo];
		}
	}

	// Get the photo data
	if ( $iptcdata === false ) {
		$iptcdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . WPPA_IPTC . "` WHERE `photo`=%s ORDER BY `tag`", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_iptc_cache[$photo] = $iptcdata;
	}

	// Init
	$temp = $desc;
	$prevtag = '';
	$combined = '';

	// Process all iptclines of this photo
	if ( ! empty( $iptcdata ) ) {
		foreach ( $iptcdata as $iptcline ) {
			$tag = $iptcline['tag'];
			if ( $prevtag == $tag ) {			// add a next item for this tag
				$combined .= ', '.htmlspecialchars( strip_tags( $iptcline['description'] ) );
			}
			else { 							// first item of this tag
				if ( $combined ) { 			// Process if required
					$temp = str_replace( $prevtag, $combined, $temp );
				}
				$combined = htmlspecialchars( strip_tags( $iptcline['description'] ) );
				$prevtag = $tag;
			}
		}

		// Process last
		$temp = str_replace( $tag, $combined, $temp );
	}

	// Process all labels
	if ( $wppa_iptc_labels ) {
		foreach( $wppa_iptc_labels as $iptclabel ) {
			$tag = $iptclabel['tag'];

			// convert 2#XXX to 2#LXXX to indicate the label
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;
			$temp = str_replace( $tag, __( $iptclabel['description'] ), $temp );
		}
	}

	// Remove untranslated
	$pos = strpos($temp, '2#');
	while ( $pos !== false ) {
		$tmp = substr($temp, 0, $pos).__('n.a.', 'wp-photo-album-plus').substr($temp, $pos+5);
		$temp = $tmp;
		$pos = strpos($temp, '2#');
	}

	return $temp;
}

// Translate exif tags into  photo dependant data inside a text
function wppa_filter_exif( $desc, $photo ) {
global $wpdb;
global $wppa_exif_labels;
global $wppa_exif_cache;

	if ( strpos($desc, 'E#') === false ) return $desc;	// No tags in desc: Nothing to do

	// Get tha labels if not yet present
	if ( ! is_array( $wppa_exif_labels ) ) {
		$wppa_exif_labels = $wpdb->get_results( "SELECT * FROM `" . WPPA_EXIF . "` WHERE `photo` = '0' ORDER BY `tag`", ARRAY_A );
	}

	// If in cache, use it
	$exifdata = false;
	if ( is_array( $wppa_exif_cache ) ) {
		if ( isset( $wppa_exif_cache[$photo] ) ) {
			$exifdata = $wppa_exif_cache[$photo];
		}
	}

	// Get the photo data
	if ( $exifdata === false ) {
		$exifdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . WPPA_EXIF . "` WHERE `photo`=%s ORDER BY `tag`", $photo ), ARRAY_A );

		// Save in cache, even when empty
		$wppa_exif_cache[$photo] = $exifdata;
	}

	// Init
	$temp = $desc;
	$prevtag = '';
	$combined = '';

	// Process all exiflines of this photo
	if ( ! empty( $exifdata ) ) {
		foreach ( $exifdata as $exifline ) {

			$tag = $exifline['tag'];
			if ( $prevtag == $tag ) {			// add a next item for this tag
				$combined .= ', ' . wppa_format_exif( $tag, $exifline['description'] );
			}
			else { 							// first item of this tag
				if ( $combined ) { 			// Process if required
					$temp = str_replace( $prevtag, $combined, $temp );
				}
				$combined = wppa_format_exif( $tag, $exifline['description'] );
				$prevtag = $tag;
			}
		}

		// Process last
		$temp = str_replace( $tag, $combined, $temp );
	}

	// Process all labels
	if ( $wppa_exif_labels ) {
		foreach( $wppa_exif_labels as $exiflabel ) {
			$tag = $exiflabel['tag'];

			// convert E#XXX to E#LXXX to indicate the label
			$t = substr( $tag, 0, 2 ) . 'L' . substr( $tag, 2 );
			$tag = $t;

			$temp = str_replace( $tag, __( $exiflabel['description'] ), $temp );
		}
	}

	// Remove untranslated
	$pos = strpos($temp, 'E#');
	while ( $pos !== false ) {
		$tmp = substr( $temp, 0, $pos ) . '<span title="' . esc_attr( __( 'No data', 'wp-photo-album-plus' ) ) . '">' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>' . substr( $temp, $pos+6 );
		$temp = $tmp;
		$pos = strpos($temp, 'E#');
	}

	// Return result
	return $temp;
}

function wppa_format_exif( $tag, $data ) {
global $wppa_exif_error_output;

	if ( $data ) {
		switch ( $tag ) {

			case 'E#0100': 	// Image width (pixels), Short or long, 1 item
			case 'E#0101': 	// Image length (pixels), Short or long, 1 item

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				$result = $data . ' ' . __( 'px.', 'wp-photo-album-plus' );
				return $result;
				break;

// 	E#0110		Camera						Already formatted correctly  Example: Canon EOS 50D

			case 'E#011A': 	// XResolution
			case 'E#011B': 	// YResolution

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = ( $x / $y );
				return $result;
				break;

			case 'E#0128': 	// Resolution unit
			case 'E#A210': 	// FocalPlaneResolutionUnit

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 2:
						$result = __( 'inches', 'wp-photo-album-plus' );
						break;
					case 3:
						$result = __( 'centimeters', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}
				return $result;
				break;

//	E#0132		Date Time					Already formatted correctly
//	E#013B		Photographer				Already formatted correctly

			case 'E#A20E':	// FocalPlaneXResolution
			case 'E#A20F':	// FocalPlaneYResolution

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( $x / $y );
				return $result;
				break;

			case 'E#0213': 	// YCbCrPositioning

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 1:
						$result = __( 'centered', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'co-sited', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}

				return $result;
				break;

			case 'E#9201': 	// Shutter speed value

				if ( ! wppa_is_valid_rational( $data, true ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( 10 * $x / $y ) / 10;
				return $result;
				break;


	/*
	E#8298		Copyright					Already formatted correctly
				Location					Formatted into one line according to the 3 tags below:  2#092, 2#090, 2#095, 2#101
											2#092		Sub location
											2#090		City
											2#095		State
											2#101		Country

	aux:Lens	Lens						Already formatted correctly - See line 66 in sample photo exifdata.jpg attached  Example aux:Lens="EF300mm f/4L IS USM +1.4x"
	*/
	//	E#920A		Focal length				Must be formatted:  420/1 = 420 mm
			case 'E#920A':

				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$z = round( $x / $y );
				if ( $z < 10 ) {
					$result = round( $x * 10 / $y ) / 10 . ' mm.';
				}
				else {
					$result = round( $x / $y ) . ' mm.';
				}
				return $result;
				break;

	//	E#9206		Subject distance			Must be formatted:  765/100 = 7,65 m.
			case 'E#9206':
				
				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}
				
				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];
			
				if ( $x == 0 || $y == 0 ) {
					$result = '<span title="' . esc_attr( __( 'Impossible data', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
				}
				else {
					if ( $temp[1] != 0 ) {
						$result = round( 100*$temp[0]/$temp[1] ) / 100;
					}
					if ( $result == -1 || $result > 10000 ) {
						$result = '&infin;';
					}
					else {
						$result .= ' m.';
					}
				}
				
				return $result;				
				break;

			case 'E#829A': 	// Exposure time

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				// 1 s.
				if ( $x / $y == 1 ) {
					$result = '1 s.';
					return $result;
				}

				// Normal: 1/nn s.
				if ( $x == 1 ) {
					$result = $data . ' s.';
					return $result;
				}

				// 'nn/1'
				if ( $y == 1 ) {
					$result = $x . ' s.';
					return $result;
				}

				// Simplify nnn/mmm > 1
				if ( ( $x / $y ) > 1 ) {
					$result = sprintf( '%2.1f', $x / $y );
					if ( substr( $result, -2 ) == '.0' ) { 	// Remove trailing '.0'
						$result = substr( $result, 0, strlen( $result ) -2 ) . ' s.';
					}
					else {
						$result .= ' s.';
					}
					return $result;
				}

				// Simplify nnn/mmm < 1
				$v = $y / $x;
				$z = round( $v ) / $v;
				if ( 0.99 < $z && $z < 1.01 ) {
					if ( round( $v ) == '1' ) {
						$result = '1 s.';
					}
					else {
						$result = '1/' . round( $v ) . ' s.';
					}
				}
				else {
					$z = $x / $y;
					$i = 2;
					$n = 0;
					while ( $n < 2 && $i < strlen( $z ) ) {
						if ( substr( $z, $i, 1 ) != '0' ) {
							$n++;
						}
						$i++;
					}
					$result = substr( $z, 0, $i ) . ' s.';
				}
				return $result;
				break;

			case 'E#829D':	// F-Stop

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				// Bogus data?
				if ( $x / $y > 100 ) {
					$result = '<span title="' . esc_attr( __( 'Impossible data', 'wp-photo-album-plus' ) ) . ':' . $data . '"  >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
					return $result;
				}

				// Valid meaningful data
				$result = 'f/' . ( round( 10 * $x / $y ) / 10 );
				
				return $result;
				break;

			case 'E#9202': 	// Aperture value
			case 'E#9205': 	// Max aperture value

				// Invalid format?
				if ( ! wppa_is_valid_rational( $data ) ) {
					return $wppa_exif_error_output;
				}

				// Format is valid
				$temp = explode( '/', $data );
				$x = $temp[0];
				$y = $temp[1];

				$result = round( 10 * $x / $y ) / 10;
				
				return $result;
				break;

	/*
	E#8827		ISO	Speed Rating			Already formatted correctly
	E#9204		Exposure bias				Already formatted correctly
	*/

			case 'E#8822': 	// Exposure program
				switch ( $data ) {
					case '0': $result = __('Not Defined', 'wp-photo-album-plus'); break;
					case '1': $result = __('Manual', 'wp-photo-album-plus'); break;
					case '2': $result = __('Program AE', 'wp-photo-album-plus'); break;
					case '3': $result = __('Aperture-priority AE', 'wp-photo-album-plus'); break;
					case '4': $result = __('Shutter speed priority AE', 'wp-photo-album-plus'); break;
					case '5': $result = __('Creative (Slow speed)', 'wp-photo-album-plus'); break;
					case '6': $result = __('Action (High speed)', 'wp-photo-album-plus'); break;
					case '7': $result = __('Portrait', 'wp-photo-album-plus'); break;
					case '8': $result = __('Landscape', 'wp-photo-album-plus'); break;
					case '9': $result = __('Bulb', 'wp-photo-album-plus'); break;
					default:
						$result = '<span title="' . esc_attr( __( 'Unknown', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
				}
				return $result;
				break;

			case 'E#9204': 	// Exposure bias value
				if ( $data ) $result = $data . ' EV';
				else $result = '';
				return $result;
				break;

			case 'E#9207':	// Metering mode
				switch ( $data ) {
					case '1': $result = __('Average', 'wp-photo-album-plus'); break;
					case '2': $result = __('Center-weighted average', 'wp-photo-album-plus'); break;
					case '3': $result = __('Spot', 'wp-photo-album-plus'); break;
					case '4': $result = __('Multi-spot', 'wp-photo-album-plus'); break;
					case '5': $result = __('Multi-segment', 'wp-photo-album-plus'); break;
					case '6': $result = __('Partial', 'wp-photo-album-plus'); break;
					case '255': $result = __('Other', 'wp-photo-album-plus'); break;
					default:
						$result = '<span title="' . esc_attr( __( 'Unknown', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
				}
				return $result;
				break;

			case 'E#9209':	// Flash
				// PHP7 '0x0' etc generates warning because octal strings are no longer suppported, but they are real strings here
				switch ( $data ) {
					case '0'.'x0':
					case '0': $result = __('No Flash', 'wp-photo-album-plus'); break;
					case '0'.'x1':
					case '1': $result = __('Fired', 'wp-photo-album-plus'); break;
					case '0'.'x5':
					case '5': $result = __('Fired, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x7':
					case '7': $result = __('Fired, Return detected', 'wp-photo-album-plus'); break;
					case '0'.'x8':
					case '8': $result = __('On, Did not fire', 'wp-photo-album-plus'); break;
					case '0'.'x9':
					case '9': $result = __('On, Fired', 'wp-photo-album-plus'); break;
					case '0'.'xd':
					case '13': $result = __('On, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'xf':
					case '15': $result = __('On, Return detected', 'wp-photo-album-plus'); break;
					case '0'.'x10':
					case '16': $result = __('Off, Did not fire', 'wp-photo-album-plus'); break;
					case '0'.'x14':
					case '20': $result = __('Off, Did not fire, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x18':
					case '24': $result = __('Auto, Did not fire', 'wp-photo-album-plus'); break;
					case '0'.'x19':
					case '25': $result = __('Auto, Fired', 'wp-photo-album-plus'); break;
					case '0'.'x1d':
					case '29': $result = __('Auto, Fired, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x1f':
					case '31': $result = __('Auto, Fired, Return detected', 'wp-photo-album-plus'); break;
					case '0'.'x20':
					case '32': $result = __('No flash function', 'wp-photo-album-plus'); break;
					case '0'.'x30':
					case '48': $result = __('Off, No flash function', 'wp-photo-album-plus'); break;
					case '0'.'x41':
					case '65': $result = __('Fired, Red-eye reduction', 'wp-photo-album-plus'); break;
					case '0'.'x45':
					case '69': $result = __('Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x47':
					case '71': $result = __('Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus'); break;
					case '0'.'x49':
					case '73': $result = __('On, Red-eye reduction', 'wp-photo-album-plus'); break;
					case '0'.'x4d':
					case '77': $result = __('Red-eye reduction, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x4f':
					case '79': $result = __('On, Red-eye reduction, Return detected', 'wp-photo-album-plus'); break;
					case '0'.'x50':
					case '80': $result = __('Off, Red-eye reduction', 'wp-photo-album-plus'); break;
					case '0'.'x58':
					case '88': $result = __('Auto, Did not fire, Red-eye reduction', 'wp-photo-album-plus'); break;
					case '0'.'x59':
					case '89': $result = __('Auto, Fired, Red-eye reduction', 'wp-photo-album-plus'); break;
					case '0'.'x5d':
					case '93': $result = __('Auto, Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus'); break;
					case '0'.'x5f':
					case '95': $result = __('Auto, Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus'); break;
					default:
						$result = '<span title="' . esc_attr( __( 'Unknown', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
				}

				return $result;
				break;

			case 'E#A001': 	// ColorSpace

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 1:
						$result = __( 'sRGB', 'wp-photo-album-plus' );
						break;
					case 0xFFFF:
						$result = __( 'Uncalibrated', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}

				return $result;
				break;

			case 'E#A402': 	// ExposureMode

				if ( ! wppa_is_valid_integer( $data ) ) {
					return $wppa_exif_error_output;
				}

				switch ( $data ) {
					case 0:
						$result = __( 'Auto exposure', 'wp-photo-album-plus' );
						break;
					case 1:
						$result = __( 'Manual exposure', 'wp-photo-album-plus' );
						break;
					case 2:
						$result = __( 'Auto bracket', 'wp-photo-album-plus' );
						break;
					default:
						$result = __( 'reserved', 'wp-photo-album-plus' );
				}

				return $result;
				break;


			// Unformatted
			default:
				$result = $data;
				return $result;
		}
	}

	// Empty data
	else {
		$result = '<span title="' . esc_attr( __( 'No data', 'wp-photo-album-plus' ) ) . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
	}

	return $result;
}

function wppa_is_valid_rational( $data, $signed = false ) {
global $wppa_exif_error_output;

	// Must contain a '/'
	if ( strpos( $data, '/' ) == false ) {
		$wppa_exif_error_output = '<span title="' . esc_attr( __( 'Missing /', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
		return false;
	}

	// make array
	$t = explode( '/', $data );

	// Divide by zero?
	if ( $t[1] == 0 ) {
		$wppa_exif_error_output = '<span title="' . esc_attr( __( 'Divide by zero', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
		return false;
	}

	// Signed while not permitted?
	if ( ! $signed && ( $t[0] < 0 || $t[1] < 0 ) ) {
		$wppa_exif_error_output = '<span title="' . esc_attr( __( 'Must be positive', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
		return false;
	}

	// Ok.
	return true;
}

function wppa_is_valid_integer( $data, $signed = false ) {
global $wppa_exif_error_output;

	// Must be integer
	if ( ! wppa_is_int( $data ) ) {
		$wppa_exif_error_output = '<span title="' . esc_attr( __( 'Invalid format', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
		return false;
	}

	// Signed while not permitted?
	if ( ! $signed && $data < 0 ) {
		$wppa_exif_error_output = '<span title="' . esc_attr( __( 'Must be positive', 'wp-photo-album-plus' ) ) . ':' . $data . '" >' . __( 'n.a.', 'wp-photo-album-plus' ) . '</span>';
		return false;
	}

	// Ok.
	return true;
}

function wppa_iptc_clean_garbage( $photo ) {
global $wpdb;

	$items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . WPPA_IPTC ."` WHERE `photo` = %s", $photo ), ARRAY_A );
	if ( is_array( $items ) ) {
		foreach( $items as $item ) {
			$txt = sanitize_text_field( $item['description'] );
			$txt = str_replace( array(chr(0),chr(1),chr(2),chr(3),chr(4),chr(5),chr(6),chr(7)), '', $txt );

			// Cleaned text empty?
			if ( ! $txt ) {

				// Garbage text, remove from photo
				$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_IPTC . "` WHERE `id` = %s", $item['id'] ) );

				// Current label still used?
				$in_use = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `" . WPPA_IPTC . "` WHERE `photo` <> '0' AND `tag` = %s", $item['tag'] ) );
				if ( ! $in_use ) {
					$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_IPTC . "` WHERE `photo` = '0' AND `tag` = %s", $item['tag'] ) );
					wppa_log( 'dbg', 'Iptc tag label' . $item['tag'] . ' removed.' );
				}
			}
		}
	}
}

function wppa_exif_clean_garbage( $photo ) {
global $wpdb;

	$items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . WPPA_EXIF ."` WHERE `photo` = %s", $photo ), ARRAY_A );
	if ( is_array( $items ) ) {
		foreach( $items as $item ) {
			$txt = sanitize_text_field( $item['description'] );
			$txt = str_replace( array(chr(0),chr(1),chr(2),chr(3),chr(4),chr(5),chr(6),chr(7)), '', $txt );

			// Cleaned text empty?
			if ( ! $txt ) {

				// Garbage
				$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_EXIF . "` WHERE `id` = %s", $item['id'] ) );

				// Current label still used?
				$in_use = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `" . WPPA_EXIF . "` WHERE `photo` <> '0' AND `tag` = %s", $item['tag'] ) );
				if ( ! $in_use ) {
					$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_EXIF . "` WHERE `photo` = '0' AND `tag` = %s", $item['tag'] ) );
					// wppa_log( 'dbg', 'Exif tag label ' . $item['tag'] . ' removed.' );
				}
			}
		}
	}
}