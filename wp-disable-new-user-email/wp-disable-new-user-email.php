<?php
/**
 * @package Disable_new_user_mail
 * @version 1.0
 */
/*
Plugin Name: Disable new user mail
Plugin URI: 
Description: Prevents sending a username and password email out when adding a new user.
Author: David J. Lee
Version: 1.0
Author URI: http://lee.dj/
*/

if ( !function_exists('wp_new_user_notification') ) {
  function wp_new_user_notification( ) {}
}

?>
