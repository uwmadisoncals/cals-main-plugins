<?php
/* wppa-cron.php
* Package: wp-photo-album-plus
*
* Contains all cron functions
* Version 6.8.07
*
*
*/

// Are we in a cron job?
function wppa_is_cron() {

	if ( isset( $_GET['doing_wp_cron'] ) ) {
		return $_GET['doing_wp_cron'];
	}
	if ( defined( 'DOING_CRON' ) ) {
		return DOING_CRON;
	}
	return false;
}

// Activate our maintenance hook
add_action( 'wppa_cron_event', 'wppa_do_maintenance_proc', 10, 1 );

// Schedule maintenance proc
function wppa_schedule_maintenance_proc( $slug, $from_settings_page = false ) {
global $is_reschedule;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {
		if ( $is_reschedule || $from_settings_page ) {
			$delay = 5;
		}
		else switch ( $slug ) {
			case 'wppa_cleanup_index':			// 1 hour
				$delay = 3600;
				break;
			case 'wppa_remake_index_albums':
				$delay = 180;
				break;
			default:
				$delay = 10;
		}
		wp_schedule_single_event( time() + $delay, 'wppa_cron_event', array( $slug ) );
		$backtrace = debug_backtrace();
		$args = '';
		if ( is_array( $backtrace[1]['args'] ) ) {
			foreach( $backtrace[1]['args'] as $arg ) {
				if ( $args ) {
					$args .= ', ';
				}
				$args .= str_replace( "\n", '', var_export( $arg, true ) );
			}
			$args = trim( $args );
			if ( $args ) {
				$args = ' ' . str_replace( ',)', ', )', $args ) . ' ';
			}
		}
		elseif ( $backtrace[1]['args'] ) {
			$args = " '" . $backtrace[1]['args'] . "' ";
		}

		$re = $is_reschedule ? 're-' : '';
		wppa_log( 'Cron', '{b}' . $slug . '{/b} ' . $re . 'scheduled by {b}' . $backtrace[1]['function'] . '(' . $args . '){/b} on line {b}' . $backtrace[0]['line'] . '{/b} of ' . basename( $backtrace[0]['file'] ) . ' called by ' . $backtrace[2]['function'] );
	}

	// Update appropriate options
	update_option( $slug . '_status', 'Cron job' );
	update_option( $slug . '_user', 'cron-job' );

	// Inform calling Ajax proc about the results
	if ( $from_settings_page ) {
		echo '||' . $slug . '||' . 'Cron job' . '||0||reload';
	}

}

// Is cronjob crashed?
function wppa_is_maintenance_cron_job_crashed( $slug ) {

	// Asume not
	$result = false;

	// If there is a last timestamp longer than 5 minutes ago...
	$lasttime = get_option( $slug.'_lasttimestamp', '0' );
	if ( $lasttime && $lasttime < ( time() - 300 ) ) {

		// And the user is cron
		if ( get_option( $slug . '_user' ) == 'cron-job' ) {

			// And proc is not scheduled
			if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {

				// It is crashed
				$result = true;
			}
		}
	}

	// No last timestamp, maybe never started?
	elseif ( ! $lasttime ) {

		// Nothing done yet
		if ( get_option( $slug . 'last' ) == '0' ) {

			// Togo not calculated yet
			if ( get_option( $slug . 'togo' ) == '' ) {

				// If the user is cron
				if ( get_option( $slug . '_user' ) == 'cron-job' ) {

					// And proc is not scheduled
					if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {

						// It is crashed
						$result = true;
					}
				}
			}
		}
	}

	return $result;
}

// Activate our cleanup session hook
add_action( 'wppa_cleanup', 'wppa_do_cleanup' );

// Schedule cleanup session database table
function wppa_schedule_cleanup( $now = false ) {

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Immediate action requested?
	if ( $now ) {
		wp_schedule_single_event( time() + 1, 'wppa_cleanup' );
	}
	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_cleanup' ) ) {
		wp_schedule_event( time(), 'hourly', 'wppa_cleanup' );
	}
}

// The actual cleaner
function wppa_do_cleanup() {
global $wpdb;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	ob_start();

	wppa_log( 'Cron', '{b}wppa_cleanup{/b} started.' );

	// Start renew crypt processes if configured socket_accept
	if ( wppa_opt( 'crypt_albums_every' ) ) {
		$last = get_option( 'wppa_crypt_albums_lasttimestamp', '0' );
		if ( $last + wppa_opt( 'crypt_albums_every' ) * 3600 < time() ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_albums' );
			update_option( 'wppa_crypt_albums_lasttimestamp', time() );
		}
	}
	if ( wppa_opt( 'crypt_photos_every' ) ) {
		$last = get_option( 'wppa_crypt_photos_lasttimestamp', '0' );
		if ( $last + wppa_opt( 'crypt_photos_every' ) * 3600 < time() ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_photos' );
			update_option( 'wppa_crypt_photos_lasttimestamp', time() );
		}
	}

	// Cleanup session db table
	$lifetime 	= 3600;			// Sessions expire after one hour
	$savetime 	= 86400;		// Save session data for 24 hour
	$expire 	= time() - $lifetime;
	$purge 		= time() - $savetime;
	$wpdb->query( $wpdb->prepare( "UPDATE `" . WPPA_SESSION . "` SET `status` = 'expired' WHERE `timestamp` < %s", $expire ) );
	$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_SESSION ."` WHERE `timestamp` < %s", $purge ) );

	// Delete obsolete spam
	$spammaxage = wppa_opt( 'spam_maxage' );
	if ( $spammaxage != 'none' ) {
		$time = time();
		$obsolete = $time - $spammaxage;
		$iret = $wpdb->query( $wpdb->prepare( "DELETE FROM `".WPPA_COMMENTS."` WHERE `status` = 'spam' AND `timestamp` < %s", $obsolete ) );
		if ( $iret ) wppa_update_option( 'wppa_spam_auto_delcount', get_option( 'wppa_spam_auto_delcount', '0' ) + $iret );
	}

	// Re-animate crashed cronjobs
	wppa_re_animate_cron();

	// Find lost photos, update their album to -9, meaning trashed
	$album_ids = $wpdb->get_col( "SELECT `id` FROM `" . WPPA_ALBUMS . "`" );
	if ( ! empty( $album_ids ) ) {
		$lost = $wpdb->query( "UPDATE `" . WPPA_PHOTOS . "` SET `album` = '-9' WHERE `album` > '0' AND `album` NOT IN ( " . implode( ',', $album_ids ) . " ) " );
	}

	// Remove 'deleted' photos from system
	$dels = $wpdb->get_col( "SELECT `id` FROM `".WPPA_PHOTOS."` WHERE `album` <= '-9' AND `modified` < " . ( time() - 3600 ) );
	foreach( $dels as $del ) {
		wppa_delete_photo( $del );
		wppa_log( 'Cron', 'Removed photo {b}' . $del . '{/b} from system' );
	}

	// Re-create permalink htaccess file
	wppa_create_pl_htaccess();

	// Retry failed mails
	if ( wppa_opt( 'retry_mails' ) ) {

		$failed_mails = get_option( 'wppa_failed_mails' );
		if ( is_array( $failed_mails ) ) {

			foreach( array_keys( $failed_mails ) as $key ) {

				$mail = $failed_mails[$key];
				$mess = $mail['message'] . '(retried mail)';

				// Retry
				if ( wp_mail( $mail['to'], $mail['subj'], $mess, $mail['headers'], $mail['att'] ) ) {

					// Set counter to 0
					$failed_mails[$key]['retry'] = '0';
				}
				else {

					// Decrease retry counter
					$failed_mails[$key]['retry']--;
					wppa_log( 'Cron', 'Retried mail to ' . $mail['to'] . ' failed. Tries to go = ' . $failed_mails[$key]['retry'] );
				}
			}

			// Cleanup
			foreach( array_keys( $failed_mails ) as $key ) {
				if ( $failed_mails[$key]['retry'] < '1' ) {
					unset( $failed_mails[$key] );
				}
			}
		}

		// Store updated failed mails
		update_option( 'wppa_failed_mails', $failed_mails );
	}

	// Cleanup iptc and exif
	wppa_iptc_clean_garbage();
	wppa_exif_clean_garbage();

	// Cleanup qr cache
	if ( is_dir( WPPA_UPLOAD_PATH . '/qr' ) ) {
		$qrs = glob( WPPA_UPLOAD_PATH . '/qr/*.svg' );
		if ( ! empty( $qrs ) ) {
			$count = count( $qrs );
			if ( $count > 250 ) {
				foreach( $qrs as $qr ) @ unlink( $qr );
				wppa_log( 'Cron', $count . ' qr cache files removed' );
			}
		}
	}

	wppa_log( 'Cron', '{b}wppa_cleanup{/b} completed.' );

	$outbuf = ob_get_clean();
	if ( $outbuf ) {
		wppa_log( 'dbg', 'Cron unexpected output: ' . $outbuf );
	}
}

// Selectively clear caches
add_action( 'wppa_clear_cache', 'wppa_do_clear_cache' );

function wppa_schedule_clear_cache( $time = 10 ) {

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// If a cron job is scheduled in the far future and we need it earlier, cancel the existing
	$next_scheduled = wp_next_scheduled( 'wppa_clear_cache' );
	if ( $time == 10 && is_numeric( $next_scheduled ) && $next_scheduled > ( time() + $time ) ) {

		wp_unschedule_event( $next_scheduled, 'wppa_clear_cache' );
		$did_unschedule = true;
	}
	else {
		$did_unschedule = false;
	}

	// Schedule new event
	if ( ! wp_next_scheduled( 'wppa_clear_cache' ) ) {

		wp_schedule_single_event( time() + $time, 'wppa_clear_cache' );

		wppa_log( 'Cron', '{b}wppa_clear_cache{/b} ' . ( $did_unschedule ? 're-' : '' ) . 'scheduled for run in ' . $time . ' sec.' );
	}
}

// call the actusl cache deleting proc, and indicate only delete cache files with text 'data-wppa="yes"'
function wppa_do_clear_cache() {

	$relroot = trim( wppa_opt( 'cache_root' ), '/' );
	if ( ! $relroot ) {
		$relroot = 'cache';
	}
	$root = WPPA_CONTENT_PATH . '/' . $relroot;
	if ( is_dir( $root ) ) {

		wppa_log( 'Cron', '{b}wppa_clear_cache{/b} started.' );
		_wppa_do_clear_cache( $root );
		wppa_log( 'Cron', '{b}wppa_clear_cache{/b} completed.' );
	}
}
function _wppa_do_clear_cache( $dir ) {

	$needle = 'data-wppa="yes"';
	$fsos = glob( $dir . '/*' );
	if ( is_array( $fsos ) ) foreach ( $fsos as $fso ) {
		$name = basename( $fso );
		if ( $name == '.' || $name == '..' ) {}
		elseif ( is_dir( $fso ) ) {
			_wppa_do_clear_cache( $fso );
		}
		else {
			$file = fopen( $fso, 'rb' );
			if ( $file ) {
				$size = filesize( $fso );
				if ( $size ) {
					$haystack = fread( $file, $size );
					if ( strpos( $haystack, $needle ) !== false ) {
						fclose( $file );
						unlink( $fso );
						wppa_log( 'fso', 'Cron removed cachefile: {b}' . str_replace( WPPA_CONTENT_PATH, '', $fso ) . '{/b}' );
					}
					else {
						fclose( $file );
					}
				}
				else {
					fclose( $file );
				}
			}
		}
	}
	
	// Also delete tempfiles
	wppa_delete_obsolete_tempfiles();
}

// Activate treecount update proc
add_action( 'wppa_update_treecounts', 'wppa_do_update_treecounts' );

function wppa_schedule_treecount_update() {

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_update_treecounts' ) ) {
		$time = 10;
		wp_schedule_single_event( time() + $time, 'wppa_update_treecounts' );
		wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} scheduled for run in ' . $time . ' sec.' );
	}
}

function wppa_do_update_treecounts() {
global $wpdb;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} started.' );

	$start = time();

	$albs = $wpdb->get_col( "SELECT `id` FROM `" . WPPA_ALBUMS . "` WHERE `a_parent` < '1' ORDER BY `id`" );

	foreach( $albs as $alb ) {
		$treecounts = wppa_get_treecounts_a( $alb );
		if ( $treecounts['needupdate'] ) {
			wppa_verify_treecounts_a( $alb );
			wppa_log( 'Cron', 'Cron fixed treecounts for ' . $alb );
		}
		if ( time() > $start + 15 ) {
			wppa_schedule_treecount_update();
			exit();
		}
	}

	wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} completed.' );

	wppa_schedule_clear_cache( 600 );
}

function wppa_re_animate_cron() {
global $wppa_cron_maintenance_slugs;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	foreach ( $wppa_cron_maintenance_slugs as $slug ) {
		if ( wppa_is_maintenance_cron_job_crashed( $slug ) ) {
			$last = get_option( $slug . '_last' );
			update_option( $slug . '_last', $last + 1 );
			wppa_schedule_maintenance_proc( $slug );
			wppa_log( 'Cron', '{b}' . $slug . '{/b} re-animated at item {b}#' . $last . '{/b}' );
		}
	}
}