<?php
   /*
   Plugin Name: Twenty20 Image Before-After
   Plugin URI: https://wordpress.org/plugins/twenty20/
   Description: Need to highlight the differences between two images? Makes it easy with Twenty20 plugin.
   Version: 1.2
   Author: Zayed Baloch
   Author URI: http://www.zayed.xyz/
   License: GPL2
   */

defined('ABSPATH') or die("No script kiddies please!");
define( 'ZB_T20_VER',   '1.2' );
define( 'ZB_T20_URL', plugins_url( '', __FILE__ ) );
define( 'ZB_T20_DOMAIN',  'zb_twenty20' );

// INITIALIZE PLUGIN
function twenty20_dir_init() {
  load_plugin_textdomain( ZB_T20_DOMAIN );
}
add_action( 'init', 'twenty20_dir_init' );

include_once('inc/enqueue.php');
include_once('inc/twenty20-shortcode.php');
include_once('inc/widget-twenty20.php');
if ( class_exists( 'WPBakeryShortCode' ) ) {
  require_once( 'inc/twenty20-shortcode-vc.php' );
}
