<?php
/*
Plugin Name: PPM Accordion
Plugin URI: http://perfectpointmarketing.com/plugins/ppm-accordion
Description: This plugin will add an expand collapse accordion feature inside a post or page.
Author: Perfect Point Marketing
Author URI: http://perfectpointmarketing.com
Version: 1.0
*/


/*Some Set-up*/
define('PPM_ACCORDION_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );


/* Adding Latest jQuery from Wordpress */
function ppm_accordion_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'ppm_accordion_latest_jquery');

/* Adding plugin javascript Main file */
wp_enqueue_script('ppm-accordion-plugin-main', PPM_ACCORDION_PLUGIN_PATH.'js/ppm-accordion-main.js', array('jquery'));

/* Adding plugin javascript active file */
wp_enqueue_script('ppm-accordion-plugin-script-active', PPM_ACCORDION_PLUGIN_PATH.'js/ppm-accordion-active.js', array('jquery'), '1.0', true);

/* Adding Plugin custm CSS file */
wp_enqueue_style('ppm-accordion-plugin-style', PPM_ACCORDION_PLUGIN_PATH.'css/style.css');





/* Add Slider Shortcode Button on Post Visual Editor */

function ppmaccordion_button() {
	add_filter ("mce_external_plugins", "ppmaccordion_button_js");
	add_filter ("mce_buttons", "ppmaccordionb");
}

function ppmaccordion_button_js($plugin_array) {
	$plugin_array['wptuts'] = plugins_url('js/accordian-button.js', __FILE__);
	return $plugin_array;
}

function ppmaccordionb($buttons) {
	array_push ($buttons, 'ppmaccordiontriger');
	return $buttons;
}
add_action ('init', 'ppmaccordion_button'); 




/* Generates Toggles Shortcode */
function ppm_accordion_main($atts, $content = null) {
	return ('<div id="ppm-tabs">'.do_shortcode($content).'</div>');
}
add_shortcode ("ppmaccordion", "ppm_accordion_main");

function ppm_accordion_toggles($atts, $content = null) {
	extract(shortcode_atts(array(
        'title'      => ''
    ), $atts));
	
	return ('<h3>' .$title. '</h3><div><div class="tab_content">' .$content. '</div></div>');
}
add_shortcode ("ppmtoggle", "ppm_accordion_toggles");


?>