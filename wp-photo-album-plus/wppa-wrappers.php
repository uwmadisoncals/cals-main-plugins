<?php
/* wppa-wrappers.php
* Package: wp-photo-album-plus
*
* Contains wrappers for standard php functions
* For security and bug reasons
*
* Version 6.9.19
*
*/

// To fix a bug in PHP as that photos made with the selfie camera of an android smartphone
// erroneously cause the PHP warning 'is not a valid JPEG file' and cause imagecreatefromjpag crash.
function wppa_imagecreatefromjpeg( $file ) {

	ini_set( 'gd.jpeg_ignore_warning', true );
	$img = imagecreatefromjpeg( $file );
	return $img;
}

// Wrapper for copy( $from, $to ) that verifies that the pathnames are safe for our application
// In case of unexpected operation: Generates a warning in the wppa log, and does not perform the copy.
function wppa_copy( $from, $to ) {

	// First test if we are uploading
	if ( ! wppa_is_path_safe( $from ) && $_FILES ) {
		if ( ! wppa_is_path_safe( $to ) ) {
			wppa_log( 'War', 'Unsafe to path detected in wppa_copy(): ' . sanitize_text_field( $to ), true );
			return false;
		}
		return wppa_move_uploaded_file( $from, $to );
	}
	
	if ( ! wppa_is_path_safe( $from ) ) {
		wppa_log( 'War', 'Unsafe from path detected in wppa_copy(): ' . sanitize_text_field( $from ), true );
		return false;	// For diagnostic purposes, no return here yet
	}
	if ( ! wppa_is_path_safe( $to ) ) {
		wppa_log( 'War', 'Unsafe to path detected in wppa_copy(): ' . sanitize_text_field( $to ), true );
		return false; // For diagnostic purposes, no return here yet
	}
	return copy( $from, $to );
}

// Wrapper for move_uploaded_file( $from, $to ) that verifies that the pathnames are safe for our application
function wppa_move_uploaded_file( $from, $to ) {

	if ( ! wppa_is_path_safe( $to ) ) {
		wppa_log( 'War', 'Unsafe to path detected in move_uploaded_file(): ' . sanitize_text_field( $to ), true );
		return false; // For diagnostic purposes, no return here yet
	}
	$bret = move_uploaded_file( $from, $to );
	if ( ! $bret ) {
		wppa_log( 'War', 'Could not move uploaded file ' . sanitize_text_field( $from ) . ' to ' . sanitize_text_field( $to ), true );
	}
	return $bret;
}

// Wrapper for fopen
function wppa_fopen( $file, $mode ) {

	// Is path safe?
	if ( ! wppa_is_path_safe( $file ) ) {
		wppa_log( 'War', 'Unsafe to path detected in wppa_fopen(): ' . sanitize_text_field( $file ), true );
		return false; // For diagnostic purposes, no return here yet
	}

	// When opening for reading, the file must exist
	if ( strpos( $mode, 'r' ) !== false && ! is_file( $file ) ) {
		return false;
	}
	return fopen( $file, $mode );
}

// Utility to check if a given full filepath is safe to manipulate upon
function wppa_is_path_safe( $path ) {
static $safe_roots;
static $safe_files;
global $wppa_lang;
global $wppa_log_file;

	if ( empty( $safe_files ) ) {

		// The following files are safe to read or write to
		$safe_files = array( WPPA_PATH . '/wppa-init.' . $wppa_lang . '.js',
							 WPPA_PATH . '/wppa-dynamic.css',
							 WPPA_PATH . '/index.php',
							 WPPA_CONTENT_PATH . '/uploads/index.php',
							 $wppa_log_file,
							 WPPA_CONTENT_PATH . '/plugins/wp-photo-album-plus/img/audiostub.jpg',
							 );
	}

	if ( empty( $safe_roots ) ) {

		// The following root dirs are safe, including all their subdirs, to read/write into
		$safe_roots = array( WPPA_CONTENT_PATH . '/uploads/wppa',
							 WPPA_CONTENT_PATH . '/uploads/wppa-source',
							 WPPA_CONTENT_PATH . '/uploads/wppa-cdn',
							 WPPA_CONTENT_PATH . '/wppa-depot',
							 WPPA_CONTENT_PATH . '/' . wppa_opt( 'pl_dirname' ),
							 WPPA_CONTENT_PATH . '/' . wppa_opt( 'cache_root' ),
							 WPPA_PATH . '/fonts',
							 WPPA_PATH . '/watermarks',
							 WPPA_PATH . '/wppa-dump.txt',
							 WPPA_UPLOAD_PATH . '/temp/',
							 WPPA_UPLOAD_PATH . '/zips/',
							 );

	}

	// Verify specific files
	foreach( array_keys( $safe_files ) as $key ) {

		if ( $path == $safe_files[$key] ) {
			return true;
		}
	}

	// It is ok to import a remote file
	if ( strpos( strtolower( $path ), 'http://' ) === 0 || strpos( strtolower( $path ), 'https://' ) === 0 ) {
		return true;
	}

	// Verify roots
	foreach( array_keys( $safe_roots ) as $key ) {

		// Starts the path with a safe root?
		if ( strpos( $path, $safe_roots[$key] ) === 0 ) {

			// Funny chars in path?
			if ( $path != sanitize_text_field( $path ) ) {
				return false;
			}

			// Path traversal attempt?
			if ( strpos( $path, '../' ) !== false ) {
				return false;
			}

			// Passed tests
			return true;
		}
	}

	// No safe root
	return false;
}

// PHP unserialize() is unsafe because it can produce dangerous objects
// This function unserializes arrays only, except when scabn is on board
// In case of error or dangerous data, returns an empty array
function wppa_unserialize( $xstring, $is_session = false ) {

	if ( version_compare( PHP_VERSION, '7.0.0') >= 0 ) {
		if ( $is_session && get_option( 'wppa_use_scabn' ) == 'yes' ) {
			return unserialize( $xstring, array( 'allowed_classes' => array( 'wfCart' ) ) );
		}
		else {
			return unserialize( $xstring, array( 'allowed_classes' => false ) );
		}
	}
	else {

		$string = $xstring;
		$result = array();

		// Assume its an array, else return the input string
		$type 	= substr( $string, 0, 2 );
		$string	= substr( $string, 2 );

		$cpos 	= strpos( $string, ':' );
		$count 	= substr( $string, 0, $cpos );
		$string = substr( $string, $cpos + 1 );
		$string	= trim( $string, '{}' );

		if ( $type != 'a:' ) {
//			wppa_log( 'Err', 'Not serialized arraydata encountered in wppa_unserialize()' );
			return array();
		}

		// Process data items
		while ( strlen( $string ) ) {

			// Decode the key
			$keytype = substr( $string, 0, 2 );
			$string  = substr( $string, 2 );
			switch ( $keytype ) {

				// Integer key
				case 'i:':
					$cpos 	 = strpos( $string, ';' );
					$key 	= intval( substr( $string, 0, $cpos ) );
					$string = substr( $string, $cpos + 1 );
					break;

				// String key
				case 's:':
					$cpos 	= strpos( $string, ':' );
					$keylen	= intval( substr( $string, 0, $cpos ) );
					$string = substr( $string, $cpos + 1 );
					$cpos 	= strpos( $string, ';' );
					$key 	= substr( $string, 1, $keylen );
					$string = substr( $string, $cpos + 1 );
					break;

				// Unimplemented key type
				default:
//					wppa_log( 'Err', 'Unimplemented keytype ' . $keytype . ' encountered in wppa_unserialize(' . $xstring . ')', true );
					return array();
			}

			// Decode the data
			$datatype = substr( $string, 0, 2 );
			$string   = substr( $string, 2 );

			switch ( $datatype ) {

				// Integer data
				case 'i:':
					$cpos 	= strpos( $string, ';' );
					$data 	= intval( substr( $string, 0, $cpos ) );
					$string = substr( $string, $cpos + 1 );
					break;

				// String data
				case 's:':
					$cpos 	 = strpos( $string, ':' );
					$datalen = intval( substr( $string, 0, $cpos ) );
					$string  = substr( $string, $cpos + 1 );
					$data 	 = substr( $string, 1, $datalen );
					$string  = substr( $string, $datalen + 3 );
					break;

				// Boolean
				case 'b:':
					$data 	 = substr( $string, 0, 1 ) == '1';
					$string  = substr( $string, 2 );
					break;

				// NULL
				case 'N;':
					$data 	 = NULL;
					break;

				// Array data
				case 'a:':
					$cbpos  = strpos( $string, '}' );
					$data 	= wppa_unserialize( 'a:' . substr( $string, 0, $cbpos + 1 ) );
					$string = substr( $string, $cbpos + 1 );
					break;

				// Unimplemented data type
				default:
//					wppa_log( 'Err', 'Unimplemented data type ' . $datatype . ' encountered in wppa_unserialize(' . $xstring . ')', true );
					return array();
			}

			// Add to result array
			$result[$key] = $data;
		}

		return $result;
	}
}
