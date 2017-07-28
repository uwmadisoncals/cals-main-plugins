<?php

/*
Plugin Name: NetID User Registration
Plugin URI: http://it.cals.wisc.edu/
Description: Modifies the "Add User" UI to account for NetID integration.
Author: Al Nemec - CALS IT
Version: 1.0
Author URI: http://alnemec.com
*/

function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('add-user-ui.css', __FILE__));
    wp_enqueue_script(
		'my-admin-theme-actions',
		plugins_url( '/add-user-ui.js' , __FILE__ ),
		array( 'jquery' )
	);
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');





?>