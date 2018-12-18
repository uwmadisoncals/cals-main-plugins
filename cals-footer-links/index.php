<?php
/**
 * Plugin Name: Wordpress Network Footer Links
 * Plugin URI: http://cals.wisc.edu
 * Description: Network Plugin to add links to the footer
 * Version: 1.0
 * Author: Al Nemec
 * Author URI: http://cals.wisc.edu
 */



// Future settings for overrides at the site level.

/*function networkfooterlinks_register_settings() {
    add_option( 'networkfooterlinks_option_login', 'https://webhosting.cals.wisc.edu/wp-admin');
    register_setting( 'networkfooterlinks_options_group', 'networkfooterlinks_option_login', 'networkfooterlinks_callback' );

    add_option( 'networkfooterlinks_option_request', 'https://wptheme.webhosting.cals.wisc.edu/contact-us/');
    register_setting( 'networkfooterlinks_options_group', 'networkfooterlinks_option_request', 'networkfooterlinks_callback' );

    add_option( 'networkfooterlinks_option_docs', 'https://webhosting.cals.wisc.edu/docs-list/');
    register_setting( 'networkfooterlinks_options_group', 'networkfooterlinks_option_docs', 'networkfooterlinks_callback' );
 }
 add_action( 'admin_init', 'networkfooterlinks_register_settings' );


 function networkfooterlinks_register_options_page() {
    add_options_page('Page Title', 'CALS Footer Links', 'manage_options', 'networkfooterlinks', 'networkfooterlinks_options_page');
  }
  add_action('admin_menu', 'networkfooterlinks_register_options_page');


  function networkfooterlinks_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h1>CALS Footer Links</h1>
  <form method="post" action="options.php">
  <?php settings_fields( 'networkfooterlinks_options_group' ); ?>
  <h3>Login Link</h3>
  <p>Enter the full url where users should login.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="networkfooterlinks_option_login">URL</label></th>
  <td><input type="text" id="networkfooterlinks_option_login" name="networkfooterlinks_option_name" value="<?php echo get_option('networkfooterlinks_option_login'); ?>" /></td>
  </tr>
  </table>

  <h3>Request Help URL</h3>
  <p>Enter the full url where users request help.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="networkfooterlinks_option_request">URL</label></th>
  <td><input type="text" id="networkfooterlinks_option_request" name="networkfooterlinks_option_name" value="<?php echo get_option('networkfooterlinks_option_request'); ?>" /></td>
  </tr>
  </table>

  <h3>Documenation URL</h3>
  <p>Enter the full url where users get documentation.</p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="networkfooterlinks_option_docs">URL</label></th>
  <td><input type="text" id="networkfooterlinks_option_docs" name="networkfooterlinks_option_name" value="<?php echo get_option('networkfooterlinks_option_docs'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}*/



// Ok, on to the code that is actually running.

add_action("network_admin_menu", "networkfooterlinks_new_menu_items");
function networkfooterlinks_new_menu_items() {

	add_submenu_page(
		'settings.php', // Parent element
		'Network Footer Links', // Text in browser title bar
		'Network Footer Links', // Text to be displayed in the menu.
		'manage_options', // Capability
		'network-footer-links', // Page slug, will be displayed in URL
		'networkfooterlinks_settings_page_1' // Callback function which displays the page
	);
}

function networkfooterlinks_settings_page_1() {

	echo '<div class="wrap">
		<h1>WordPress Network Footer Links</h1>
		<form method="post" action="edit.php?action=networkfooterlinksaction">';
			wp_nonce_field( 'networkfooterlinks-validate' );
			echo '

			<table class="form-table">
				<tr>
					<th scope="row"><label for="networkfooterlinks_option_login">Login URL</label></th>
					<td>
						<input name="networkfooterlinks_option_login" class="regular-text" type="text" id="networkfooterlinks_option_login" value="' . esc_attr( get_site_option( 'networkfooterlinks_option_login') ) . '" />
						<p class="description">Full URL to login.</p>
					</td>
                </tr>

                <tr>
					<th scope="row"><label for="networkfooterlinks_option_request">Request for Help URL</label></th>
					<td>
						<input name="networkfooterlinks_option_request" class="regular-text" type="text" id="networkfooterlinks_option_request" value="' . esc_attr( get_site_option( 'networkfooterlinks_option_request') ) . '" />
						<p class="description">Full URL to contact form.</p>
					</td>
                </tr>

                <tr>
					<th scope="row"><label for="networkfooterlinks_option_docs">Documentation URL</label></th>
					<td>
						<input name="networkfooterlinks_option_docs" class="regular-text" type="text" id="networkfooterlinks_option_docs" value="' . esc_attr( get_site_option( 'networkfooterlinks_option_docs') ) . '" />
						<p class="description">Full URL for documentation.</p>
					</td>
				</tr>
			</table>';
			submit_button();
        echo '</form></div>';
        echo '<h2>Current Footer CSS</h2>';
        echo '<p>Copy the following css overrides and apply per theme to improve visual continuity for each site. Styles below are the the next closest overriding specificity to what is declared by the plugin.</p>';
        echo '<blockquote><pre><code> div.networkfooterWrapper {
            background: rgba(0,0,0,0.7);
            position:relative;
            z-index:6;
        }

        div.networkfooterlinks {
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

        div.networkfooterlinks a:link,
        div.networkfooterlinks a:hover,
        div.networkfooterlinks a:visited,
        div.networkfooterlinks a:active {
            color: rgba(255,255,255,0.7);
        }
            </code></pre></blockquote>';

}

add_action( 'network_admin_edit_networkfooterlinksaction', 'networkfooterlinks_save_settings' );

function networkfooterlinks_save_settings(){

	check_admin_referer( 'networkfooterlinks-validate' ); // Nonce security check

	update_site_option( 'networkfooterlinks_option_login', $_POST['networkfooterlinks_option_login'] );

    update_site_option( 'networkfooterlinks_option_request', $_POST['networkfooterlinks_option_request'] );

    update_site_option( 'networkfooterlinks_option_docs', $_POST['networkfooterlinks_option_docs'] );


	wp_redirect( add_query_arg( array(
		'page' => 'network-footer-links',
		'updated' => true ), network_admin_url('settings.php')
	));

	exit;

}


add_action( 'network_admin_notices', 'networkfooterlinks_custom_notices' );

function networkfooterlinks_custom_notices(){

	if( isset($_GET['page']) && $_GET['page'] == 'network-footer-links' && isset( $_GET['updated'] )  ) {
		echo '<div id="message" class="updated notice is-dismissible"><p>Settings updated. You\'re the best!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}

}

add_action('wp_footer', 'network_footer_inject');
function network_footer_inject() { ?>
    <style>
        .networkfooterWrapper {
            background: rgba(0,0,0,0.7);
            position:relative;
            z-index:6;
        }

        .networkfooterlinks {
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

        .networkfooterlinks a:link,
        .networkfooterlinks a:hover,
        .networkfooterlinks a:visited,
        .networkfooterlinks a:active {
            color: rgba(255,255,255,0.7);
        }
    </style>

    <div class="networkfooterWrapper">
        <div class="networkfooterlinks">
            <a href="<?php echo get_site_option('networkfooterlinks_option_login'); ?>" class="footerLogin" target="_blank">Login</a>

            <a href="<?php echo get_site_option('networkfooterlinks_option_request'); ?>" target="_blank">Request Help</a>

            <a href="<?php echo get_site_option('networkfooterlinks_option_docs'); ?>" target="_blank">Help Docs</a>



        </div>
    </div>

    <script>
        document.querySelector(".footerLogin").addEventListener("click", function(e) {

            // Disable default link. In the event JS fails to load this link will just take them to the central login WP login.
            e.preventDefault();

            // Set cookie timer for 5 min, enough time to get through Net ID with MFA.
            var now = new Date();
            now.setSeconds(now.getSeconds() + 300);

            // Query the DB to find the real root domain of the current site. This is needed for sites with domain mapping to properly re-route.
            <?php

            global $wpdb;
            $wpdb->dmtable = $wpdb->base_prefix . 'blogs';

            $custom_blog_id = get_current_blog_id();
            $domain = $wpdb->get_results( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = $custom_blog_id LIMIT 1" );
            if ( $domain ) {
                $rootdomain = $domain[0]->domain;

                // Get second level domain to set cookie root domain. Note: Come back in the future and clean up this mess.
                $string = $rootdomain;
                preg_match('@^(?:http://)?([^/]+)@i', $string, $matches);
                $host = $matches[1];
                preg_match('/[^.]+\\.[^.]+$/', $host, $matches);
                $basedomain = '.'.$matches[0];

            } else {

                // In the unlikely event we can't determine a site url use the server http host. This route will work when domain mapping is not in use.
                $rootdomain = $_SERVER['HTTP_HOST'];
            } ?>


            // Set cross sub domain cookie
            document.cookie = "frontface=<?php echo $rootdomain ?>; expires=" + now.toUTCString() + "; path=/; domain=<?php echo $basedomain ?>;"

            // Proceed to Net ID -> WP Dashboard with cookie set.
            window.location.href = "<?php echo get_site_option('networkfooterlinks_option_login'); ?>";
        });

    </script>

<?php }


add_action('admin_menu', 'check_refer');

function check_refer() {

if (isset($_COOKIE['frontface'])) {

    //Get cookie dashboard url and current url to determine if you have arrived.
    $url = $_COOKIE['frontface']."/wp-admin/";
    $current = $_SERVER['HTTP_HOST']."/wp-admin/";


    if($url != $current) {
        // If you haven't arrived yet, set the header to forward the user on.
        if( isset($_SERVER['HTTPS'] ) ) {
            header('Location: https://'.$url, true, 302);
        } else {
            header('Location: http://'.$url, true, 302);
        }

    } else {

        // You should have landed at the appropriate dashboard, ditch the cookie.

        // Grab the current http host to expire the cookie
        $rootdomain = $_SERVER['HTTP_HOST'];
        $string = $rootdomain;
        preg_match('@^(?:http://)?([^/]+)@i', $string, $matches);
        $host = $matches[1];
        preg_match('/[^.]+\\.[^.]+$/', $host, $matches);
        $basedomain = '.'.$matches[0];

        setcookie("frontface", "", time()-3600, "/", $basedomain);

    }
} else {
    // No cookie is present
}



}