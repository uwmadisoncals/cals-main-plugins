<?php
/* wppa-source.php
* Package: wp-photo-album-plus
*
* Contains photo source file management routines
* Version 6.9.19
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function wppa_save_source( $file, $name, $alb ) {

	$doit = true;

	// Frontend not enabled and not ajax ?
	if ( ! is_admin() && ! wppa_switch( 'keep_source_frontend') ) {
		$doit = false;
	}

	// Frontend not enabled and ajax ?
	if ( isset( $_REQUEST['wppa-action'] ) &&
		$_REQUEST['wppa-action'] == 'do-fe-upload' &&
		! wppa_switch( 'keep_source_frontend') ) {
			$doit = false;
	}

	// Backend not enabled ?
	if ( ( ! isset( $_REQUEST['wppa-action'] ) || $_REQUEST['wppa-action'] != 'do-fe-upload' ) &&
		is_admin() &&
		! wppa_switch( 'keep_source_admin') ) {
			$doit = false;
	}

	if ( $doit ) {
		if ( ! is_dir( wppa_opt( 'source_dir' ) ) ) @ wppa_mktree( wppa_opt( 'source_dir' ) );
		$sourcedir = wppa_get_source_dir();
		if ( ! is_dir( $sourcedir ) ) @ wppa_mktree( $sourcedir );
		$albdir = wppa_get_source_album_dir( $alb );
		if ( ! is_dir( $albdir ) ) @ wppa_mktree( $albdir );
		if ( ! is_dir( $albdir ) ) {
			wppa_log( 'Err', 'Could not create source directory ' . $albdir );
		}
		$dest = $albdir . '/' . wppa_sanitize_file_name( $name );
		if ( $file != $dest ) {

			wppa_copy( $file, $dest );

		}
		if ( is_file( $dest ) ) {
			wppa_chmod( $dest );
		}
		else {
			wppa_log( 'Err', 'Could not save ' . $dest, true );
		}
	}
}

function wppa_delete_source( $name, $alb ) {

	if ( wppa_switch( 'keep_sync') ) {
		$path = wppa_get_source_album_dir( $alb ).'/'.$name;
		$path = wppa_strip_ext( $path );

		$all_paths = glob( $path . '.*' );
		$o1paths = glob( $path . '-o1.*' );

		if ( is_array( $all_paths ) && is_array( $o1paths ) ) {
			$all_paths = array_merge( $all_paths, $o1paths );
		}

		// Delete all possible file-extensions
		if ( is_array( $all_paths ) ) foreach( $all_paths as $p ) if ( is_file( $p ) ) {
			unlink( $p );								// Ignore error
		}

		// Remove album if empty
		$dir = wppa_get_source_album_dir( $alb );
		if ( is_dir( $dir ) ) {
			$files = glob( $dir . '/*.*' );
			if ( count( $files ) == 2 ) {						// . and .. only
				@ rmdir( wppa_get_source_album_dir( $alb ) );	// Ignore error
			}
		}
	}
}

function wppa_move_source( $name, $from, $to ) {
global $wppa_supported_photo_extensions;

	// Source files can have uppercase extensions.
	$temp = array();
	foreach( $wppa_supported_photo_extensions as $ext ) {
		$temp[] = strtoupper( $ext );
	}
	$supext = array_merge( $wppa_supported_photo_extensions, $temp );

	if ( wppa_switch( 'keep_sync') ) {
		$frompath 	= wppa_get_source_album_dir( $from ).'/'.wppa_strip_ext($name);
		$todir 		= wppa_get_source_album_dir( $to );
		$topath 	= wppa_get_source_album_dir( $to ).'/'.wppa_strip_ext($name);
		if ( ! is_dir( $todir ) ) @ wppa_mktree( $todir );

		foreach( $supext as $ext ) {
			if ( is_file( $frompath.'.'.$ext ) ) {

				// rename. Will fail if target already exists
				wppa_rename( $frompath.'.'.$ext, $topath.'.'.$ext );
				wppa_rename( $frompath.'-o1.'.$ext, $topath.'-o1.'.$ext );

				// therefor delete if still exists
				if ( is_file( $frompath.'.'.$ext ) ) {
					@ unlink( $frompath.'.'.$ext );
				}
				if ( is_file( $frompath.'-o1.'.$ext ) ) {
					@ unlink( $frompath.'-o1.'.$ext );
				}
			}
		}
	}
}

// rename without warnings
function wppa_rename( $from, $to ) {

	$from 	= str_replace( '../', '', $from );
	$to 	= str_replace( '../', '', $to );

	if ( is_file( $from ) ) {
		if ( is_file( $to ) ) {
			wppa_copy( $from, $to );
			unlink( $from );
		}
		else {
			rename( $from, $to );
		}
	}
}

function wppa_copy_source( $name, $from, $to ) {
global $wppa_supported_photo_extensions;

	// Source files can have uppercase extensions.
	$temp = array();
	foreach( $wppa_supported_photo_extensions as $ext ) {
		$temp[] = strtoupper( $ext );
	}
	$supext = array_merge( $wppa_supported_photo_extensions, $temp );

	if ( wppa_switch( 'keep_sync') ) {
		$frompath 	= wppa_get_source_album_dir( $from ).'/'.wppa_strip_ext($name);
		$todir 		= wppa_get_source_album_dir( $to );
		$topath 	= wppa_get_source_album_dir( $to ).'/'.wppa_strip_ext($name);
		if ( ! is_dir( $todir ) ) @ wppa_mktree( $todir );

		foreach( $supext as $ext ) {
			if ( is_file( $frompath.'.'.$ext ) ) {
				wppa_copy( $frompath.'.'.$ext, $topath.'.'.$ext );
			}
			if ( is_file( $frompath.'-o1.'.$ext ) ) {
				wppa_copy( $frompath.'-o1.'.$ext, $topath.'-o1.'.$ext );
			}
		}
	}
}

function wppa_delete_album_source( $album ) {
	if ( wppa_switch( 'keep_sync') ) {
		@ rmdir( wppa_get_source_album_dir( $album ) );
	}
}
