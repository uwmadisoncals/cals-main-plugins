<?php
/* wppa-cron.php
* Package: wp-photo-album-plus
*
* Contains all cron functions
* Version 6.6.15
*
*
*/

// Are we in a cron job?
function wppa_is_cron() {

	return ( defined( 'DOING_CRON' ) || isset( $_GET['doing_wp_cron'] ) );
}

// Activate our maintenance hook
add_action( 'wppa_cron_event', 'wppa_do_maintenance_proc', 10, 1 );

// Schedule maintenance proc
function wppa_schedule_maintenance_proc( $slug, $from_settings_page = false ) {

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {
		wp_schedule_single_event( time() + 30, 'wppa_cron_event', array( $slug ) );
		$backtrace = debug_backtrace();
		$args = is_array( $backtrace[1]['args'] ) ? implode( ', ', $backtrace[1]['args'] ) : '';
		wppa_log( 'Cron', '{b}' . $slug . '{/b} scheduled by {b}' . $backtrace[1]['function'] . '(' . $args . '){/b} on line {b}' . $backtrace[0]['line'] . '{/b} of ' . basename( $backtrace[0]['file'] ) );
	}

	// Update appropriate options
	update_option( $slug . '_status', 'Scheduled cron job' );
	update_option( $slug . '_user', 'cron-job' );

	// Inform calling Ajax proc about the results
	if ( $from_settings_page ) {
		echo '||' . $slug . '||' . __( 'Scheduled cron job', 'wp-photo-album-plus' ) . '||0||reload';
	}

}

// Is cronjob crashed?
function wppa_is_maintenance_cron_job_crashed( $slug ) {

	// Asume not
	$result = false;

	// If there is a last timestamp longer than 15 minutes ago...
	$last = get_option( $slug.'_lasttimestamp', '0' );
	if ( $last && $last < ( time() - 900 ) ) {

		// And the user is cron
		if ( get_option( $slug . '_user' ) == 'cron-job' ){

			// And proc is not scheduled
			if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {

				// It is crashed
				$result = true;
			}
		}
	}

	return $result;
}

// Activate our cleanup session hook
add_action( 'wppa_cleanup', 'wppa_do_cleanup' );

// Schedule cleanup session database table
function wppa_schedule_cleanup( $now = false ) {

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
global $wppa_all_maintenance_slugs;

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
	foreach ( $wppa_all_maintenance_slugs as $slug ) {
		if ( wppa_is_maintenance_cron_job_crashed( $slug ) ) {
			$last = get_option( $slug . '_last' );
			update_option( $slug . '_last', $last + 1 );
			wppa_schedule_maintenance_proc( $slug );
			wppa_log( 'Cron', 'Crashed cron job {b}' . $slug . '{/b} re-animated at item {b}#' . $last . '{/b}' );
		}
	}

	// Remove 'deleted' photos from system
	$dels = $wpdb->get_col( "SELECT `id` FROM `".WPPA_PHOTOS."` WHERE `album` = '-9'" );
	foreach( $dels as $del ) {
		wppa_delete_photo( $del );
		wppa_log( 'Cron', 'Removed photo {b}' . $del . '{/b} from system' );
	}

	// Re-create permalink htaccess file
	wppa_create_pl_htaccess();

	// Cleanup index
	wppa_schedule_maintenance_proc( 'wppa_cleanup_index' );

}

// Activate treecount update proc
add_action( 'wppa_update_treecounts', 'wppa_do_update_treecounts' );

function wppa_schedule_treecount_update() {

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_update_treecounts' ) ) {
		wp_schedule_single_event( time() + 10, 'wppa_update_treecounts' );
	}
}

function wppa_do_update_treecounts() {
global $wpdb;

	$start = time();

	$albs = $wpdb->get_col( "SELECT `id` FROM `" . WPPA_ALBUMS . "` WHERE `a_parent` < '1' ORDER BY `id`" );

	foreach( $albs as $alb ) {
		$treecounts = wppa_get_treecounts_a( $alb );
		if ( $treecounts['needupdate'] ) {
			wppa_verify_treecounts_a( $alb );
			wppa_log( 'Cron', 'Cron fixed treecounts for ' . $alb );
		}
		if ( time() > $start + 30 ) {
			wppa_schedule_treecount_update();
			exit();
		}
	}
}