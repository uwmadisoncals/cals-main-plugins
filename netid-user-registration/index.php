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



// Redefine user notification function
/*if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);
 
        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);
 
        $message  = sprintf(__('New user registration on your site %s:'), get_option('blogname')) . "rnrn";
        $message .= sprintf(__('Username: %s'), $user_login) . "rnrn";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "rn";
 
        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);
 
        if ( empty($plaintext_pass) )
            return;
 
        $message  = __('Hi there,') . "rnrn";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "rnrn";
        $message .= wp_login_url() . "rn";
        $message .= sprintf(__('Username: %s'), $user_login) . "rn";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "rnrn";
        $message .= sprintf(__('If you have any problems, please contact us at %s.'), get_option('admin_email')) . "rnrn";
        //$message .= __('Adios!');
 
        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
 
    }
}*/

?>