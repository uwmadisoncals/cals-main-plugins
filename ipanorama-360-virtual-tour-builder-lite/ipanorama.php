<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://avirtum.com
 * @since             1.0.0
 * @package           ipanorama
 *
 * @wordpress-plugin
 * Plugin Name:       iPanorama 360
 * Plugin URI:        http://avirtum.com
 * Description:       iPanorama 360 is the WordPress plugin out there that lets you create awesome virtual tours for clients from directly inside the WordPress admin in seconds. The plugin supports hotspots for providing information about any part of the scene or for navigation to other rooms/areas. It uses their own tooltip system, you can enrich it with text, images, video and other online media. Use this plugin to create interactive tours, maps and presentations.
 * Version:           1.3.8
 * Author:            Avirtum
 * Author URI:        http://codecanyon.net/user/avirtum/portfolio?ref=avirtum
 * Text Domain:       ipanorama
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ipanorama-activator.php
 */
function activate_ipanorama() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipanorama-activator.php';
	iPanorama_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ipanorama-deactivator.php
 */
function deactivate_ipanorama() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipanorama-deactivator.php';
	iPanorama_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ipanorama' );
register_deactivation_hook( __FILE__, 'deactivate_ipanorama' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ipanorama.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ipanorama() {
	$plugin = new iPanorama();
	$plugin->run();
}
run_ipanorama();
