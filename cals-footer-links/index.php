<?php
/**
 * Plugin Name: CALS Footer Links
 * Plugin URI: http://cals.wisc.edu
 * Description: Network Plugin to add links to the footer
 * Version: 1.0
 * Author: Al Nemec
 * Author URI: http://cals.wisc.edu
 */


function calsfooterlinks_register_settings() {
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
}

add_action('wp_footer', 'cals_footer_inject');
function cals_footer_inject() { ?>
    <style>
        .calsfooterWrapper {
            background: rgba(0,0,0,0.5);
        }

        .calsfooterLinks {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            column-gap: 2rem;
            margin: 0 auto;
            max-width: 600px;
            text-align: center;
            font-size: 0.8rem;
            padding: 1rem;
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
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
            <a href="<?php echo get_option('calsfooterlinks_option_login'); ?>" target="_blank">Login</a>

            <a href="<?php echo get_option('calsfooterlinks_option_request'); ?>" target="_blank">Request Help</a>

            <a href="<?php echo get_option('calsfooterlinks_option_docs'); ?>" target="_blank">Help Docs</a>
        </div>
    </div>

<?php }