<?php
/**
 * Plugin Name: CALS Footer Links
 * Plugin URI: http://cals.wisc.edu
 * Description: Network Plugin to add links to the footer
 * Version: 1.0
 * Author: Al Nemec
 * Author URI: http://cals.wisc.edu
 */


/*function calsfooterlinks_register_settings() {
    add_option( 'calsfooterlinks_option_login', 'https://webhosting.cals.wisc.edu/wp-admin');
    register_setting( 'calsfooterlinks_options_group', 'calsfooterlinks_option_login', 'calsfooterlinks_callback' );

    add_option( 'calsfooterlinks_option_request', 'https://wptheme.webhosting.cals.wisc.edu/contact-us/');
    register_setting( 'calsfooterlinks_options_group', 'calsfooterlinks_option_request', 'calsfooterlinks_callback' );

    add_option( 'calsfooterlinks_option_docs', 'https://webhosting.cals.wisc.edu/docs-list/');
    register_setting( 'calsfooterlinks_options_group', 'calsfooterlinks_option_docs', 'calsfooterlinks_callback' );
 }
 add_action( 'admin_init', 'calsfooterlinks_register_settings' );


 function calsfooterlinks_register_options_page() {
    add_options_page('Page Title', 'CALS Footer Links', 'manage_options', 'calsfooterlinks', 'calsfooterlinks_options_page');
  }
  add_action('admin_menu', 'calsfooterlinks_register_options_page');


  function calsfooterlinks_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h1>CALS Footer Links</h1>
  <form method="post" action="options.php">
  <?php settings_fields( 'calsfooterlinks_options_group' ); ?>
  <h3>Login Link</h3>
  <p>Enter the full url where users should login.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="calsfooterlinks_option_login">URL</label></th>
  <td><input type="text" id="calsfooterlinks_option_login" name="calsfooterlinks_option_name" value="<?php echo get_option('calsfooterlinks_option_login'); ?>" /></td>
  </tr>
  </table>

  <h3>Request Help URL</h3>
  <p>Enter the full url where users request help.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="calsfooterlinks_option_request">URL</label></th>
  <td><input type="text" id="calsfooterlinks_option_request" name="calsfooterlinks_option_name" value="<?php echo get_option('calsfooterlinks_option_request'); ?>" /></td>
  </tr>
  </table>

  <h3>Documenation URL</h3>
  <p>Enter the full url where users get documentation.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="calsfooterlinks_option_docs">URL</label></th>
  <td><input type="text" id="calsfooterlinks_option_docs" name="calsfooterlinks_option_name" value="<?php echo get_option('calsfooterlinks_option_docs'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}*/


add_action("network_admin_menu", "calsfooterlinks_new_menu_items");
function calsfooterlinks_new_menu_items() {

	add_submenu_page(
		'settings.php', // Parent element
		'CALS Footer Links', // Text in browser title bar
		'CALS Footer Links', // Text to be displayed in the menu.
		'manage_options', // Capability
		'cals-footer-links', // Page slug, will be displayed in URL
		'calsfooterlinks_settings_page_1' // Callback function which displays the page
	);



}

function calsfooterlinks_settings_page_1() {

	echo '<div class="wrap">
		<h1>CALS Network Footer Links</h1>
		<form method="post" action="edit.php?action=calsfooterlinksaction">';
			wp_nonce_field( 'calsfooterlinks-validate' );
			echo '

			<table class="form-table">
				<tr>
					<th scope="row"><label for="calsfooterlinks_option_login">Login URL</label></th>
					<td>
						<input name="calsfooterlinks_option_login" class="regular-text" type="text" id="calsfooterlinks_option_login" value="' . esc_attr( get_site_option( 'calsfooterlinks_option_login') ) . '" />
						<p class="description">Full URL to login.</p>
					</td>
                </tr>

                <tr>
					<th scope="row"><label for="calsfooterlinks_option_request">Request for Help URL</label></th>
					<td>
						<input name="calsfooterlinks_option_request" class="regular-text" type="text" id="calsfooterlinks_option_request" value="' . esc_attr( get_site_option( 'calsfooterlinks_option_request') ) . '" />
						<p class="description">Full URL to contact form.</p>
					</td>
                </tr>

                <tr>
					<th scope="row"><label for="calsfooterlinks_option_docs">Documentation URL</label></th>
					<td>
						<input name="calsfooterlinks_option_docs" class="regular-text" type="text" id="calsfooterlinks_option_docs" value="' . esc_attr( get_site_option( 'calsfooterlinks_option_docs') ) . '" />
						<p class="description">Full URL for documentation.</p>
					</td>
				</tr>
			</table>';
			submit_button();
		echo '</form></div>';

}

add_action( 'network_admin_edit_calsfooterlinksaction', 'calsfooterlinks_save_settings' );

function calsfooterlinks_save_settings(){

	check_admin_referer( 'calsfooterlinks-validate' ); // Nonce security check

	update_site_option( 'calsfooterlinks_option_login', $_POST['calsfooterlinks_option_login'] );

    update_site_option( 'calsfooterlinks_option_request', $_POST['calsfooterlinks_option_request'] );

    update_site_option( 'calsfooterlinks_option_docs', $_POST['calsfooterlinks_option_docs'] );


	wp_redirect( add_query_arg( array(
		'page' => 'cals-footer-links',
		'updated' => true ), network_admin_url('settings.php')
	));

	exit;

}


add_action( 'network_admin_notices', 'calsfooterlinks_custom_notices' );

function calsfooterlinks_custom_notices(){

	if( isset($_GET['page']) && $_GET['page'] == 'cals-footer-links' && isset( $_GET['updated'] )  ) {
		echo '<div id="message" class="updated notice is-dismissible"><p>Settings updated. You\'re the best!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}

}

add_action('wp_footer', 'cals_footer_inject');
function cals_footer_inject() { ?>
    <style>
        .calsfooterWrapper {
            background: rgba(0,0,0,0.7);
        }

        .calsfooterLinks {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            column-gap: 2rem;
            margin: 0 auto;
            max-width: 600px;
            text-align: center;
            font-size: 0.7rem;
            padding: 1rem;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            color: rgba(255,255,255,0.7);
        }

        .calsfooterLinks a:link,
        .calsfooterLinks a:hover,
        .calsfooterLinks a:visited,
        .calsfooterLinks a:active {
            color: rgba(255,255,255,0.7);
        }
    </style>

    <div class="calsfooterWrapper">
        <div class="calsfooterLinks">
            <a href="<?php echo get_site_option('calsfooterlinks_option_login'); ?>" target="_blank">Login</a>

            <a href="<?php echo get_site_option('calsfooterlinks_option_request'); ?>" target="_blank">Request Help</a>

            <a href="<?php echo get_site_option('calsfooterlinks_option_docs'); ?>" target="_blank">Help Docs</a>
        </div>
    </div>

     <?php
     unset($_COOKIE['frontface']);
     setcookie('frontface', $_SERVER['HTTP_HOST'], time()+100, '/', '.wisc.edu'); ?>

<?php }


add_action('admin_menu', 'check_refer');

function check_refer() {
    //setcookie('token', base64_encode(serialize($token)), time()+10800, '/', '.mydomain.com');

if (isset($_COOKIE['frontface'])) {
    $url = $_COOKIE['frontface']."/wp-admin/";
    $current = $_SERVER['HTTP_HOST']."/wp-admin/";
    //header('Location: ' . $url, true, 302);

    //echo '<div style="position: fixed; bottom: 50px; right: 10px; z-index:3;">Would loop: '.$_SERVER['HTTP_HOST'].'</div>';

    if($url != $current) {
        //echo '<div style="position: fixed; bottom: 0px; right: 10px; z-index:3;">Go to: '.$url.'</div>';
        header('Location: https://'.$url, true, 302);
    } else {
        //You should have no landed at the appropriate dashboard, ditch the cookie.

        unset($_COOKIE['frontface']);
        $res = setcookie('frontface', '', time() - 3600, '/', '.wisc.edu');
        //Thinks it will loop so avoids.
        //echo '<div style="position: fixed; bottom: 0px; right: 10px; z-index:3;">Would loop: '.$url.'</div>';
    }
} else {
    //echo '<div style="position: fixed; bottom: 0px; right: 10px; z-index:3;">No Cookie :(</div>';
}



}