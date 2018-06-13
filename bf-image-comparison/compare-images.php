<?php
/**
 * Plugin Name: B&F Image Comparison
 * Plugin URI: http://wpprocare.com
 * Description: B&F Image Comparison plugin allows you to create the effect of comparing the two images before and after.
 * Version: 1.0
 * Author: WPProcare
 * Author URI: http://wpprocare.com
 * License: FREE
 */

if(!class_exists('bgimg_compare_images')) {
    class bgimg_compare_images {
    	function __construct() {
            if(function_exists('add_shortcode'))
            	add_shortcode( 'compare' , array(&$this, 'bgimg_ci_func') );
        }

        function bgimg_ci_func($atts = array(), $content = null) {
            extract(shortcode_atts(array('before' => '',
            								'after' => '',
            								'width' => '600px',
            								'height' => '400px'), $atts));
            return "<div class='compare_img' style='width = $width; height = $height'><div class='img' style='background-image: url($after);'></div><div class='img divisor' style='background-image: url($before);'></div></div>" . $javascript;
        }
    }
}
function bgimg_load() {
    global $p;
    $p = new bgimg_compare_images();
}
add_action( 'plugins_loaded', 'bgimg_load' );

// addBtn
function bgimg_compare_img_shortcode_mce_button_init() {
    if(!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
        return;
    }
    add_filter('mce_external_plugins', 'bgimg_compare_img_shortcode_script');
    add_filter('mce_buttons', 'bgimg_compare_img_add_shortcode_button');
}
add_action('init', 'bgimg_compare_img_shortcode_mce_button_init');
 
function bgimg_compare_img_shortcode_script($plugin_array) {
    $plugin_array['compare_img_shortcode'] = plugin_dir_url( __FILE__ ) . 'addBtn.js';
    return $plugin_array;
}
 
function bgimg_compare_img_add_shortcode_button($buttons) {
    $buttons[] = 'compare_img_shortcode';
    return $buttons;
}
function bgimg_scripts() {
	wp_enqueue_style( 'bgimg-css', plugin_dir_url( __FILE__ )."/style.css" );
	wp_enqueue_script('bgimg-scrip', plugin_dir_url( __FILE__ )."/javascript.js");
}

add_action( 'wp_enqueue_scripts', 'bgimg_scripts' );