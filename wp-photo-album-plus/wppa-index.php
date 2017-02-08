<?php
/* wppa-index.php
* Package: wp-photo-album-plus
*
* Contains all indexing functions
* Version 6.6.12
*
*
*/

// Add an item to the index
function wppa_index_add( $type, $id ) {
global $wpdb;
global $acount;
global $pcount;

	if ( $type == 'album' ) {

		// If there is a cron job running adding to the index and this is not that cron job, do nothing
		if ( get_option( 'wppa_remake_index_albums_user' ) == 'cron-job' && ! wppa_is_cron() ) {
			return;
		}

		// If no user runs the remake proc, start it as cron job
		if ( ! get_option( 'wppa_remake_index_albums_user' ) ) {
			wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
			return;
		}

		$album = wppa_cache_album( $id );

		// Find the raw text, all qTranslate languages
		$words = wppa_index_get_raw_album( $id );

		// Convert to santized array of ndexable words
		$words = wppa_index_raw_to_words( $words );

		// Process all the words to see if they must be added to the index
		foreach ( $words as $word ) {

			// Get the row of the index table where the word is registered.
			$indexline = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".WPPA_INDEX."` WHERE `slug` = %s", $word), ARRAY_A);

			// If this line does not exist yet, create it with only one album number as data
			if ( ! $indexline ) {
				wppa_create_index_entry( array( 'slug' => $word, 'albums' => $id ) );
			}

			// Index line already exutst, process this album id for this word
			else {

				// Convert existing album ids to an array
				$oldalbums = wppa_index_string_to_array( $indexline['albums'] );

				// If not in yet...
				if ( ! in_array( $id, $oldalbums ) ) {

					// Add it
					$oldalbums[] = $id;

					// Report addition
					wppa_log( 'Cron', 'Adding album # {b}'.$id.'{/b} to index slug {b}'.$word.'{/b}');

					// Covert to string again
					$newalbums = wppa_index_array_to_string( $oldalbums );

					// Update db
					$wpdb->query($wpdb->prepare( "UPDATE `".WPPA_INDEX."` SET `albums` = %s WHERE `id` = %s", $newalbums, $indexline['id']));

				}
			}
		}
		$acount ++;
	}

	elseif ( $type == 'photo' ) {

		// If there is a cron job running adding to the index and this is not that cron job, do nothing
		if ( get_option( 'wppa_remake_index_photos_user' ) == 'cron-job' && ! wppa_is_cron() ) {
			return;
		}

		// If no user runs the remake proc, start it as cron job
		if ( ! get_option( 'wppa_remake_index_photos_user' ) ) {
			wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
			return;
		}

		$thumb = wppa_cache_thumb($id);

		// Find the raw text
		$words = wppa_index_get_raw_photo($id);

		$words = wppa_index_raw_to_words($words);	// convert raw string to sanitized array
		foreach ( $words as $word ) {
			$indexline = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".WPPA_INDEX."` WHERE `slug` = %s", $word), ARRAY_A);
			if ( ! $indexline ) {	// create new entry
				wppa_create_index_entry( array( 'slug' => $word, 'photos' => $id ) );
			}
			else { 	// Add to entry
				$oldphotos = wppa_index_string_to_array($indexline['photos']);
				if ( ! in_array( $id, $oldphotos ) ) {
					$oldphotos[] = $id;
					wppa_log( 'Cron', 'Adding photo # {b}'.$id.'{/b} to index slug {b}'.$word.'{/b}');
					sort($oldphotos);
					$newphotos = wppa_index_array_to_string($oldphotos);
					$wpdb->query($wpdb->prepare( "UPDATE `".WPPA_INDEX."` SET `photos` = %s WHERE `id` = %s", $newphotos, $indexline['id']));
				}
			}

		}
		$pcount ++;
	}

	else wppa_dbg_msg('Error, unimplemented type in wppa_index_add().', 'red', 'force');
}

// Convert raw data string to indexable word array
function wppa_index_raw_to_words($xtext, $noskips = false, $minlen = '2' ) {

	$ignore = array( 	'"', "'", '`', '\\', '>', '<', ',', ':', ';', '!', '?', '=', '_',
						'[', ']', '(', ')', '{', '}', '..', '...', '....', "\n", "\r",
						"\t", '.jpg', '.png', '.gif', '&#039', '&amp',
						'w#cc0', 'w#cc1', 'w#cc2', 'w#cc3', 'w#cc4', 'w#cc5', 'w#cc6', 'w#cc7', 'w#cc8', 'w#cc9'
					);
	if ( wppa_switch( 'index_ignore_slash' ) ) {
		$ignore[] = '/';
	}
	if ( $noskips ) $skips = array();
	else $skips = get_option( 'wppa_index_skips', array() );

	$result = array();
	if ( $xtext ) {
		$text = strtolower($xtext);
		$text = html_entity_decode($text);
		$text = wppa_strip_tags($text, 'script&style');	// strip style and script tags inclusive content
		$text = str_replace('>', '> ', $text);			// Make sure <td>word1</td><td>word2</td> will not endup in 'word1word2', but in 'word1' 'word2'
		$text = strip_tags($text);						// Now strip the tags
														// Strip qTranslate language shortcodes: [:*]
		$text = str_replace($ignore, ' ', $text);		// Remove funny chars
		$text = str_replace(array('è', 'é', 'ë'), 'e', $text);	// Remove accents
		$text = str_replace(array('ò', 'ó', 'ö'), 'o', $text);	//
		$text = str_replace(array('à', 'á', 'ä'), 'a', $text);	//
		$text = str_replace(array('ù', 'ú', 'ü'), 'u', $text); 	//
		$text = str_replace(array('ì', 'í', 'ï'), 'i', $text); 	//
		$text = str_replace('ç', 'c', $text);				// Remoce cédille
		$text = trim($text);
		$text = trim($text, " ./-");
		while ( strpos($text, '  ') ) $text = str_replace('  ', ' ', $text);	// Compress spaces
		$words = explode(' ', $text);
		foreach ( $words as $word ) {
			$word = trim($word);
			$word = trim($word, " ./-");
			if ( strlen($word) >= $minlen && ! in_array($word, $skips) ) $result[] = $word;
			if ( strpos($word, '-') !== false ) {
				$fracts = explode('-', $word);
				foreach ( $fracts as $fract ) {
					$fract = trim($fract);
					$fract = trim($fract, " ./-");
					if ( strlen($fract) >= $minlen && ! in_array($fract, $skips) ) $result[] = $fract;
				}
			}
		}
	}

	// Remove numbers optionaly
	if ( wppa_switch( 'search_numbers_void' ) ) {
		foreach ( array_keys( $result ) as $key ) {
			$t = ltrim( $result[$key], '0' ); 	// Strip leading zeroes
			if ( ! $t || is_numeric( $t ) ) {
				unset( $result[$key] );
			}
		}
	}

	// Remove dups and sort
	$result = array_unique( $result );

	return $result;
}

// Expand compressed string
function wppa_index_string_to_array($string) {
	// Anything?
	if ( ! $string ) return array();
	// Any ranges?
	if ( ! strstr($string, '..') ) {
		$result = explode(',', $string);
		foreach( array_keys($result) as $key ) {
			$result[$key] = strval($result[$key]);
		}
		return $result;
	}
	// Yes
	$temp = explode(',', $string);
	$result = array();
	foreach ( $temp as $t ) {
		if ( ! strstr($t, '..') ) $result[] = intval($t);
		else {
			$range = explode('..', $t);
			$from = $range['0'];
			$to = $range['1'];
			while ( $from <= $to ) {
				$result[] = strval($from);
				$from++;
			}
		}
	}

	foreach( array_keys($result) as $key ) {
		$result[$key] = strval($result[$key]);
	}

	return $result;
}

// Remove duplicates from array
function wppa_index_array_remove_dups( $array ) {
	$temp = $array;
	sort( $temp, SORT_NUMERIC );
	$array = array();
	$oldval = false;
	foreach ( array_keys( $temp ) as $key ) {
		if ( $temp[$key] != $oldval ) {
			$array[] = $temp[$key];
			$oldval	 = $temp[$key];
		}
	}
	return $array;
}

// Compress array ranges and convert to string
function wppa_index_array_to_string( $array ) {

	// Remove empty elements
	foreach( array_keys( $array ) as $idx ) {
		if ( ! $array[$idx] ) {
			unset( $array[$idx] );
		}
	}

	sort( $array, SORT_NUMERIC );
	$result = '';
	$lastitem = '-1';
	$isrange = false;
	foreach ( $array as $item ) {
		if ( $item == $lastitem+'1' ) {
			$isrange = true;
		}
		else {
			if ( $isrange ) {	// Close range
				$result .= '..'.$lastitem.','.$item;
				$isrange = false;
			}
			else {				// Add single item
				$result .= ','.$item;
			}
		}
		$lastitem = $item;
	}
	if ( $isrange ) {	// Don't forget the last if it ends in a range
		$result .= '..'.$lastitem;
	}
	$result = trim($result, ',');
	return $result;
}

// Remove an item from the index Use this function if you do NOT know the current photo data matches the index info
function wppa_index_remove( $type, $id ) {
global $wpdb;

	// If there is a cron job running cleaning the index and this is not that cron job, do nothing
	if ( get_option( 'wppa_cleanup_index_user' ) == 'cron-job' && ! wppa_is_cron() ) {
		return;
	}

	// If no user runs the cleanup proc, start it as cron job
	if ( ! get_option( 'wppa_cleanup_index_user' ) ) {
		wppa_schedule_maintenance_proc( 'wppa_cleanup_index' );
		return;
	}

	$iam_big = ( $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_INDEX."`" ) > '10000' );	// More than 10.000 index entries,
	if ( $iam_big && $id < '100' ) return;	// Need at least 3 digits to match

	if ( $type == 'album' ) {
		if ( $iam_big ) {
			// This is not strictly correct, the may be 24..28 when searching for 26, this will be missed. However this will not lead to problems during search.
			$indexes = $wpdb->get_results( "SELECT * FROM `".WPPA_INDEX."` WHERE `albums` LIKE '".$id."'", ARRAY_A );
		}
		else {
			// There are too many results on large systems, resulting in a 500 error, but it is strictly correct
			$indexes = $wpdb->get_results( "SELECT * FROM `".WPPA_INDEX."` WHERE `albums` <> ''", ARRAY_A );
		}
		if ( $indexes ) foreach ( $indexes as $indexline ) {
			$array = wppa_index_string_to_array($indexline['albums']);
			foreach ( array_keys($array) as $k ) {
				if ( $array[$k] == intval($id) ) {
					unset ( $array[$k] );
					$string = wppa_index_array_to_string($array);
					$wpdb->query( "UPDATE `".WPPA_INDEX."` SET `albums` = '".$string."' WHERE `id` = ".$indexline['id'] );
				}
			}
		}
	}
	elseif ( $type == 'photo' ) {
		if ( $iam_big ) {
			// This is not strictly correct, the may be 24..28 when searching for 26, this will be missed. However this will not lead to problems during search.
			$indexes = $wpdb->get_results( "SELECT * FROM `".WPPA_INDEX."` WHERE `photos` LIKE '%".$id."%'", ARRAY_A );
		}
		else {
			$indexes = $wpdb->get_results( "SELECT * FROM `".WPPA_INDEX."` WHERE `photos` <> ''", ARRAY_A );
			// There are too many results on large systems, resulting in a 500 error, but it is strictly correct
		}
		if ( $indexes ) foreach ( $indexes as $indexline ) {
			$array = wppa_index_string_to_array($indexline['photos']);
			foreach ( array_keys($array) as $k ) {
				if ( $array[$k] == intval($id) ) {
					unset ( $array[$k] );
					$string = wppa_index_array_to_string($array);
					$wpdb->query( "UPDATE `".WPPA_INDEX."` SET `photos` = '".$string."' WHERE `id` = ".$indexline['id'] );
				}
			}
		}
	}
	else wppa_dbg_msg('Error, unimplemented type in wppa_index_remove().', 'red', 'force');

	$wpdb->query( "DELETE FROM `".WPPA_INDEX."` WHERE `albums` = '' AND `photos` = ''" );	// Cleanup empty entries
}

// Use this function if you know the current photo data matches the index info. Mostly fails...
function wppa_index_quick_remove($type, $id) {
global $wpdb;

	// If there is a cron job running cleaning the index and this is not that cron job, do nothing
	if ( get_option( 'wppa_cleanup_index_user' ) == 'cron-job' && ! wppa_is_cron() ) {
		return;
	}

	// If no user runs the cleanup proc, start it as cron job
	if ( ! get_option( 'wppa_cleanup_index_user' ) ) {
		wppa_schedule_maintenance_proc( 'wppa_cleanup_index' );
		return;
	}

	if ( $type == 'album' ) {

		$album = wppa_cache_album($id);

		$words = stripslashes( $album['name'] ).' '.stripslashes( $album['description'] ).' '.$album['cats'];
		$words = wppa_index_raw_to_words($words);

		foreach ( $words as $word ) {
			$indexline = $wpdb->get_row("SELECT * FROM `".WPPA_INDEX."` WHERE `slug` = '".$word."'", ARRAY_A);
			$array = wppa_index_string_to_array($indexline['albums']);
			foreach ( array_keys($array) as $k ) {
				if ( $array[$k] == $id ) {
					unset ( $array[$k] );
					$string = wppa_index_array_to_string($array);
					if ( $string || $indexline['photos'] ) {
						$wpdb->query("UPDATE `".WPPA_INDEX."` SET `albums` = '".$string."' WHERE `id` = ".$indexline['id']);
					}
					else {
						$wpdb->query("DELETE FROM `".WPPA_INDEX."` WHERE `id` = ".$indexline['id']);
					}
				}
			}
		}

	}
	elseif ( $type == 'photo') {

		$thumb = wppa_cache_thumb($id);

		// Find the raw text
		$words = stripslashes( $thumb['name'] ).' '.$thumb['filename'].' '.stripslashes( $thumb['description'] ).' '.$thumb['tags'];
		$coms = $wpdb->get_results($wpdb->prepare( "SELECT `comment` FROM `" . WPPA_COMMENTS . "` WHERE `photo` = %s AND `status` = 'approved'", $thumb['id'] ), ARRAY_A );
		if ( $coms ) foreach ( $coms as $com ) {
			$words .= ' '.stripslashes( $com['comment'] );
		}
		$words = wppa_index_raw_to_words($words, 'noskips');

		foreach ( $words as $word ) {
			$indexline = $wpdb->get_row("SELECT * FROM `".WPPA_INDEX."` WHERE `slug` = '".$word."'", ARRAY_A);
			$array = wppa_index_string_to_array($indexline['photos']);
			foreach ( array_keys($array) as $k ) {
				if ( $array[$k] == $id ) {
					unset ( $array[$k] );
					$string = wppa_index_array_to_string($array);
					if ( $string || $indexline['albums'] ) {
						$wpdb->query("UPDATE `".WPPA_INDEX."` SET `photos` = '".$string."' WHERE `id` = ".$indexline['id']);
					}
					else {
						$wpdb->query("DELETE FROM `".WPPA_INDEX."` WHERE `id` = ".$indexline['id']);
					}
				}
			}
		}
	}
}

// Re-index an edited item
function wppa_index_update($type, $id) {
	wppa_index_remove($type, $id);
	wppa_index_add($type, $id);
}

// The words in the new photo description should be left out
function wppa_index_compute_skips() {

	$user_skips 	= wppa_opt( 'search_user_void' );
	$system_skips 	= 'w#name,w#filename,w#owner,w#displayname,w#id,w#tags,w#cats,w#timestamp,w#modified,w#views,w#amx,w#amy,w#amfs,w#url,w#hrurl,w#tnurl,w#pl';
	$words 			= wppa_index_raw_to_words( wppa_opt( 'newphoto_description' ) . ' ' . $user_skips . ' ' . $system_skips, 'noskips' );
	sort( $words );

	$result = array();
	$last = '';
	foreach ( $words as $word ) {	// Remove dups
		if ( $word != $last ) {
			$result[] = $word;
			$last = $word;
		}
	}
	update_option( 'wppa_index_skips', $result );
}

// Find the raw text for album, all qTranslate languages
function wppa_index_get_raw_album( $id ) {
	$album = wppa_cache_album( $id );
	$words = wppa_get_album_desc( $id ) . ' ' . wppa_get_album_name( $id );
//	$words = stripslashes($album['name']).' '.stripslashes($album['description']);
	if ( wppa_switch( 'search_cats' ) ) {
		$words .= ' '.$album['cats'];
	}
	return $words;
}

function wppa_index_get_raw_photo( $id ) {
global $wpdb;

	$thumb 	= wppa_cache_thumb( $id );
/*
	$desc 	= stripslashes($thumb['description']);
	$desc 	= wppa_filter_iptc( $desc, $thumb['id'] );	// Render IPTC tags
	$desc 	= wppa_filter_exif( $desc, $thumb['id'] );	// Render EXIF tags

	$custom = wppa_get_photo_item( $id, 'custom' );
	if ( $custom ) {
		$custom_data = unserialize( $custom );
		for ( $i = 0; $i < 10; $i++ ) {
			if ( wppa_switch( 'custom_visible_'.$i ) ) {		// May be displayed
				$desc = str_replace( 'w#cd'.$i, __( stripslashes( $custom_data[$i] ) ), $desc );	// Data
			}
			else {
				$desc = str_replace( 'w#cd'.$i, '', $desc );	// Data
			}
		}
	}
	$words 	= stripslashes($thumb['name']).' '.$thumb['filename'].' '.$desc;
*/
	$words = wppa_get_photo_desc( $id ) . ' ' . wppa_get_photo_name( $id );

	if ( wppa_switch( 'search_tags' ) ) $words .= ' '.$thumb['tags'];																					// Tags
	if ( wppa_switch( 'search_comments' ) ) {
		$coms = $wpdb->get_results($wpdb->prepare( "SELECT `comment` FROM `" . WPPA_COMMENTS . "` WHERE `photo` = %s AND `status` = 'approved'", $thumb['id'] ), ARRAY_A );
		if ( $coms ) {
			foreach ( $coms as $com ) {
				$words .= ' '.stripslashes( $com['comment'] );
			}
		}
	}

	return $words;
}