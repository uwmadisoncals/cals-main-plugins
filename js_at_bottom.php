<?php
/*
Plugin Name: Put JS at bottom
Plugin URI: http://www.vq20.com
Description: This plugin moves the "wp_print_scripts" action from wp_head to wp_footer to add all links to javascript files to the end of the document. 
Version: 0.1
Author: Vidal Quevedo
Author URI: http://www.vq20.com
*/


remove_action('wp_head', 'wp_print_scripts');
add_action('wp_footer', 'wp_print_scripts');


?>
