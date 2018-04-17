<?php
/* wppa-wpdb-update.php
* Package: wp-photo-album-plus
*
* Contains low-level wpdb routines that update records
* Version 6.8.04
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Album
function wppa_update_album( $args ) {
global $wpdb;

	if ( ! is_array( $args ) ) {
		if ( wppa_is_int( $args ) ) {
			$args = array( 'id' => $args, 'modified' => time() );
		}
		else {
			return false;
		}
	}
	if ( ! $args['id'] ) return false;
	if ( ! wppa_cache_album( $args['id'] ) ) return false;
	$id = $args['id'];

	$need_re_index = false;
	foreach ( array_keys( $args ) as $itemname ) {
		$itemvalue = $args[$itemname];
		$doit = false;

		// Sanitize input
		switch( $itemname ) {
			case 'id':
				break;
			case 'name':
				$itemvalue = wppa_strip_tags( $itemvalue, 'all' );
				$doit = true;
				$need_re_index = true;
				break;
			case 'description':
				$itemvalue = balanceTags( $itemvalue, true );
				$itemvalue = wppa_strip_tags( $itemvalue, 'script&style' );
				$doit = true;
				$need_re_index = true;
				break;
			case 'modified':
				if ( ! $itemvalue ) {
					$itemvalue = time();
				}
				$doit = true;
				break;
			case 'cats':
				$itemvalue = wppa_sanitize_tags( $itemvalue );
				$doit = true;
				$need_re_index = true;
				break;
			case 'scheduledtm':
				$doit = true;
				break;
			case 'main_photo':
				if ( wppa_is_int( $itemvalue ) ) {
					$doit = true;
				}
				break;
			case 'crypt':
				$doit = true;
				break;
			case 'custom':
				$doit = true;
				$need_re_index = true;
				break;

			default:
				wppa_log( 'Error', 'Not implemented in wppa_update_album(): '.$itemname );
				return false;
		}

		if ( $doit ) {
			if ( $wpdb->query( $wpdb->prepare( "UPDATE `".WPPA_ALBUMS."` SET `".$itemname."` = %s WHERE `id` = %s LIMIT 1", $itemvalue, $id ) ) ) {
				wppa_cache_album( 'invalidate' );
			}
		}
	}

	// Update index
	if ( $need_re_index ) {
		wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
		wppa_clear_cache();
	}

	return true;

/*
		`a_order`,
		`main_photo`,
		`a_parent`,
		`p_order_by`,
		`cover_linktype`,
		`cover_linkpage`,
		`owner`,
		`upload_limit`,
		`alt_thumbsize`,
		`default_tags`,
		`cover_type`,
		`suba_order_by`,
		`views`,
		`cats`
*/
}

// Photo
function wppa_update_photo( $args ) {
global $wpdb;

	if ( ! is_array( $args ) ) {
		if ( wppa_is_int( $args ) ) {
			$args = array( 'id' => $args, 'modified' => time() );
		}
		else {
			return false;
		}
	}
	if ( ! $args['id'] ) return false;
	$thumb = wppa_cache_thumb( $args['id'] );
	if ( ! $thumb ) return false;
	$id = $args['id'];

	// If Timestamp update, make sure modified is updated to now
	if ( isset( $args['timestamp'] ) ) {
		$args['modified'] = time();
	}

	$need_re_index = false;
	foreach ( array_keys( $args ) as $itemname ) {
		$itemvalue = $args[$itemname];
		$doit = false;

		// Sanitize input
		switch( $itemname ) {
			case 'id':
				break;
			case 'name':
				$itemvalue = wppa_strip_tags( $itemvalue, 'all' );
				$doit = true;
				$need_re_index = true;
				break;
			case 'description':
				$itemvalue = balanceTags( $itemvalue, true );
				$itemvalue = wppa_strip_tags( $itemvalue, 'script&style' );
				$doit = true;
				$need_re_index = true;
				break;
			case 'timestamp':
			case 'modified':
				if ( ! $itemvalue ) {
					$itemvalue = time();
				}
				$doit = true;
				break;
			case 'scheduledtm':
			case 'scheduledel':
			case 'exifdtm':
			case 'page_id':
				$doit = true;
				break;
			case 'status':
				$doit = true;
				break;
			case 'tags':
				$itemvalue = wppa_sanitize_tags( $itemvalue );
				$doit = true;
				$need_re_index = true;
				break;
			case 'thumbx':
			case 'thumby':
			case 'photox':
			case 'photoy':
			case 'videox':
			case 'videoy':
				$itemvalue = intval( $itemvalue );
				$doit = true;
				break;
			case 'ext':
				$doit = true;
				break;
			case 'filename':
				$itemvalue = wppa_sanitize_file_name( $itemvalue );
				$doit = true;
				$need_re_index = true;
				break;
			case 'stereo':
				$doit = true;
				break;
			case 'custom':
				$doit = true;
				$need_re_index = true;
				break;
			case 'crypt':
				$doit = true;
				break;
			case 'owner':
				$doit = true;
				$need_re_index = true;
				break;
			case 'album':
				$doit = true;
				$need_re_index = true;
				break;
			case 'magickstack':
				$doit = true;
				break;

			default:
				wppa_log( 'Error', 'Not implemented in wppa_update_photo(): '.$itemname );
				return false;
		}

		if ( $doit ) {
			if ( $wpdb->query( $wpdb->prepare( "UPDATE `".WPPA_PHOTOS."` SET `".$itemname."` = %s WHERE `id` = %s LIMIT 1", $itemvalue, $id ) ) ) {
				wppa_cache_photo( 'invalidate', $id );
			}
		}
	}

	// Update index
	if ( $need_re_index ) {
		wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
		wppa_clear_cache();
	}

	return true;
}