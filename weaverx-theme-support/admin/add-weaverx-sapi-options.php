<?php
/* This loads the Admin stuff. It is invoked from functions.php.
 *
 * This ultimately will be used to load different admin interfaces -
 * like the a default Customizer version for WP.org, or the traditional Theme Options version (which it does now)
 */

if (current_user_can('edit_posts')) {


	add_action('admin_head', 'weaverx_admin_ts_head');

function weaverx_admin_ts_head() {	// action definition
	require_once(dirname(__FILE__ ) . '/admin-lib-ts.php');
	require_once(dirname(__FILE__ ) . '/admin-lib-ts-2.php');

	add_action('weaverx_admin_saverestore', 'weaverx_ts_weaverx_admin_saverestore');

	add_action('weaverx_admin_subthemes', 'weaverx_ts_weaverx_admin_subthemes');
	add_action('weaverx_admin_mainopts', 'weaverx_ts_weaverx_admin_mainopts');
	add_action('weaverx_admin_advancedopts', 'weaverx_ts_weaverx_admin_advancedopts');
}

/* function wvrx_admin_error_notice() {
    echo 'This request contained ' . count( $_POST ) . ' POST vars, ' . count( $_GET ) . ' GET vars, and ' . count( $_COOKIE ) . ' Cookies.';
	echo '<pre>POST:'; var_dump($_POST); echo '</pre>';
	echo '<pre>GET:'; var_dump($_GET); echo '</pre>';
	echo '<pre>COOKIE:'; var_dump($_COOKIE); echo '</pre>';
}

//add_action( 'admin_notices', 'wvrx_admin_error_notice' );
// admin actions function definitions
*/

function weaverx_ts_weaverx_admin_subthemes() {
	require_once( dirname(__FILE__ ) . '/admin-subthemes.php');

	weaverx_admin_subthemes();
}

function weaverx_ts_weaverx_admin_mainopts() {

	require_once( dirname(__FILE__ ) . '/admin-mainopts.php');

	weaverx_admin_mainopts();
}

function weaverx_ts_weaverx_admin_advancedopts() {

	require_once( dirname(__FILE__ ) . '/admin-advancedopts.php');

	weaverx_admin_advancedopts();
}

function weaverx_ts_weaverx_admin_saverestore() {

	require_once( dirname(__FILE__ ) . '/admin-saverestore.php');

	weaverx_ts_admin_saverestore();
}

}	// END IF CAN EDIT POSTS ---------------------------------------------------------------------


?>
