<?php
if (!defined('ABSPATH'))
    exit;

function oxilab_flip_box_shortcode_function($styleid, $userdata) {
    $styleid = (int) $styleid;
    global $wpdb;
    $table_name = $wpdb->prefix . 'oxi_div_style';
    $table_list = $wpdb->prefix . 'oxi_div_list';
    $listdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_list WHERE styleid = %d ORDER by id ASC ", $styleid), ARRAY_A);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d ", $styleid), ARRAY_A);
    $stylename = $styledata['style_name'];
    $styledata = $styledata['css'];
    $styledata = explode('|', $styledata);
    include_once oxilab_flip_box_url . 'public/' . $stylename . '.php';
    wp_enqueue_style('oxilab-flip-box', plugins_url('public/style.css', __FILE__));
    wp_enqueue_style('animation', plugins_url('public/animation.css', __FILE__));
    wp_enqueue_script('oxilab-animation', plugins_url('public/animation.js', __FILE__));
    $stylefunctionmane = 'oxilab_flip_box_shortcode_function_' . $stylename . '';
    $stylefunctionmane($styleid, $userdata, $styledata, $listdata);
}


