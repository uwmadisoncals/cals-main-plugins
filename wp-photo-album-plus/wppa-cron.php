<?php
/* wppa-cron.php
* Package: wp-photo-album-plus
*
* Contains all cron functions
* Version 6.6.11
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
function wppa_schedule_maintenance_proc( $slug, $first = false ) {

	// Schedule cron job
	wp_schedule_single_event( time() + ( $first ? 10 : 30 ), 'wppa_cron_event', array( $slug ) );

	// Update appropriate options
	update_option( $slug . '_status', 'Scheduled cron job' );
	update_option( $slug . '_user', 'cron-job' );

	// Inform calling Ajax proc about the results
	echo '||' . $slug . '||' . __( 'Scheduled cron job', 'wp-photo-album-plus' ) . '||0||reload';

}

// Activate our cleanup session hook
add_action( 'wppa_cleanup', 'wppa_do_cleanup' );

// Schedule cleanup session database table
function wppa_schedule_cleanup() {

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_cleanup' ) ) {
		wp_schedule_event( time(), 'hourly', 'wppa_cleanup' );
	}
}

// The actual cleaner
function wppa_do_cleanup() {
global $wpdb;

	// Cleanup session db table
	$lifetime 	= 3600;			// Sessions expire after one hour
	$savetime 	= 86400;		// Save session data for 24 hour
	$expire 	= time() - $lifetime;
	$purge 		= time() - $savetime;
	$wpdb->query( $wpdb->prepare( "UPDATE `" . WPPA_SESSION . "` SET `status` = 'expired' WHERE `timestamp` < %s", $expire ) );
	$wpdb->query( $wpdb->prepare( "DELETE FROM `" . WPPA_SESSION ."` WHERE `timestamp` < %s", $purge ) );

	// Re-create permalink htaccess file
	wppa_create_pl_htaccess();

}

// Activate treecount update proc
add_action( 'wppa_update_treecounts', 'wppa_do_update_treecounts' );

function wppa_schedule_treecount_update() {

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_update_treecounts' ) ) {
		wp_schedule_single_event( time() + 30, 'wppa_update_treecounts' );
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
//			wppa_log( 'Dbg', 'Cron fixed treecounts for ' . $alb );
		}
		if ( time() > $start + 30 ) {
			wppa_schedule_treecount_update();
			exit();
		}
	}
}