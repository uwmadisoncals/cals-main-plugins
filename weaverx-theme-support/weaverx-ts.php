<?php
/*
Plugin Name: Weaver Xtreme Theme Support
Plugin URI: http://weavertheme.com/plugins
Description: Weaver X Theme Support - a package of useful shortcodes and widgets that integrates closely with the Weaver X theme. This plugin Will also allow you to switch from Weaver X to any other theme and still be able to use the shortcodes and widgets from Weaver X with minimal effort.
Author: wpweaver
Author URI: http://weavertheme.com/about/
Version: 2.1.1
License: GPL V3

Weaver Xtreme Theme Support

Copyright (C) 2014-2016 Bruce E. Wampler - weaver@weavertheme.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/* CORE FUNCTIONS
*/
$theme = get_template_directory();

if ( strpos( $theme, '/weaver-xtreme') !== false ) {		// only load if Weaver Xtreme is the theme

define ('WVRX_TS_VERSION','2.1.1');
define ('WVRX_TS_MINIFY','.min');		// '' for dev, '.min' for production
define ('WVRX_TS_APPEARANCE_PAGE', false );

function wvrx_ts_installed() {
    return true;
}


function wvrx_ts_plugins_url($file,$ext) {
    return plugins_url($file,__FILE__) . $ext;
}

function wvrx_ts_enqueue_scripts() {	// action definition

    if (function_exists('wvrx_ts_slider_header')) wvrx_ts_slider_header();

    //-- Weaver X PLus js lib - requires jQuery...

    // put the enqueue script in the tabs shortcode where it belongs

    //wp_enqueue_script('wvrxtsJSLib', wvrx_ts_plugins_url('/js/wvrx-ts-jslib', WVRX_TS_MINIFY . '.js'),array('jquery'),WVRX_TS_VERSION);


    // add plugin CSS here, too.

    wp_register_style('wvrx-ts-style-sheet',wvrx_ts_plugins_url('weaverx-ts-style', WVRX_TS_MINIFY.'.css'),null,WVRX_TS_VERSION,'all');
    wp_enqueue_style('wvrx-ts-style-sheet');
}

add_action('wp_enqueue_scripts', 'wvrx_ts_enqueue_scripts' );

require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-runtime-lib.php'); // NOW - load the basic library
require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-widgets.php'); 		// widgets runtime library
require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-shortcodes.php'); // load the shortcode definitions
require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-per-page-customizer.php');

// load traditional Weaver Xtreme Options

function weaver_xtreme_load_admin_action() {
	require_once(dirname( __FILE__ ) . '/admin/add-weaverx-sapi-options.php'); // NOW - load the traditional opions admin

}

add_action('weaver_xtreme_load_admin','weaver_xtreme_load_admin_action');

if ( ! function_exists( 'weaverxplus_plugin_installed' ) ) {

add_action('admin_menu', 'wvrx_ts_add_page_fields',11);	// allow X-Plus to override us

function wvrx_ts_add_page_fields() {
	add_meta_box('page-box', __('Weaver Xtreme Options For This Page (Theme Support Per Page Options)','weaverx-theme-support'), 'wvrx_ts_page_extras_load', 'page', 'normal', 'high');
	add_meta_box('post-box', __('Weaver Xtreme Options For This Post (Theme Support Per Post Options)','weaverx-theme-support'), 'wvrx_ts_post_extras_load', 'post', 'normal', 'high');
	global $post;
	$opts = get_option( apply_filters('weaverx_options','weaverx_settings') , array());	// need to fetch Weaver Xtreme options

	$i = 1;
	$args=array( 'public'   => true, '_builtin' => false );
	$post_types = get_post_types($args,'names','and');
	foreach ($post_types  as $post_type ) {
		add_meta_box('post-box' . $i, __('Weaver Xtreme Options For This Post Type (Theme Support Per Post Options)','weaverx-theme-support'), 'wvrx_ts_post_extras_pt', $post_type, 'normal', 'high');
		$i++;
	}

require_once(dirname( __FILE__ ) . '/includes/wvrx-ts-admin-page-posts.php');	// per page-posts admin - needs to be here

}

function wvrx_ts_page_extras_load() {
	wvrx_ts_page_extras();
}

function wvrx_ts_post_extras_load() {
	wvrx_ts_post_extras();
}
}

// ======================================== subthemes ========================================
add_action('weaverx_child_show_extrathemes','wvrx_ts_child_show_extrathemes_action');

function wvrx_ts_child_show_extrathemes_action() {
	return;
// old code found in version before 2.0.4

}

add_action('weaverx_child_process_options','wvrx_ts_child_process_options');
function wvrx_ts_child_process_options() {
// old code found in version before 2.0.4

	if ( weaverx_submitted('toggle_shortcode_prefix') ) {
		$val = get_option('wvrx_toggle_shortcode_prefix');
		if ( $val ) {
			delete_option('wvrx_toggle_shortcode_prefix');
			weaverx_save_msg(__("Weaver Xtreme Theme Support Shortcodes NOT prefixed with 'wvrx_'", 'weaverx-theme-support'));
		} else {
			update_option('wvrx_toggle_shortcode_prefix', 'wvrx_');
			weaverx_save_msg(__("Weaver Xtreme Theme Support Shortcodes now prefixed with 'wvrx_'", 'weaverx-theme-support'));
		}
	} else if ( weaverx_submitted('show_per_page_report')) {
		wvrx_ts_per_page_report();
	}

}

// old code found in version before 2.0.4


    add_action('weaverx_child_saverestore','wvrx_ts_child_saverestore_action');
function wvrx_ts_child_saverestore_action() {
	return;
	/* ------------------
    echo '<h3 class="atw-option-subheader" style="font-style:italic">' . __('Use the <em>Weaver Xtreme Subthemes</em>
 tab to upload Add-on Subthemes.</h3><p>You can upload extra add-on subthemes you\'ve downloaded using the
 Subthemes tab. Note: the Save and Restore options on this page are for the custom settings you
 have created. These save/restore options are not related to Add-on Subthemes, although you can
 modify an Add-on Subtheme, and save your changes here.</p>','weaverx-theme-support');
 --------------- */
}

	//add_action('weaverx_check_updates', 'weaverx_check_updates_action');

/*function weaverx_check_updates_action() {
	require_once('wp-updates-theme-1411.php');
	$theme = basename(get_template_directory());
	new WPUpdatesThemeUpdater_1411( 'http://wp-updates.com/api/2/theme', $theme );
} */

// --------------------------------------
function wvrx_ts_per_page_report() {
	echo '<div style="border:1px solid black; padding:1em;background:#F8FFCC;width:70%;margin:1em auto 1em auto;">';
	echo "<h2>" . __('Show Pages and Posts with  Per Page / Per Post Settings','weaverx-axtreme') . "</h2>\n";
	echo "<h3>" . __('Posts','weaverx-axtreme') . "</h3>\n";
	wvrx_ts_scan_section('post');
	echo "<h3>" . __('Pages','weaverx-axtreme') . "</h3>\n";
	wvrx_ts_scan_section('page');
	echo "</div>\n";
}

function wvrx_ts_scan_section($what) {

	$post_fields = array('_pp_category', '_pp_tag', '_pp_onepost', '_pp_orderby', '_pp_sort_order',
	'_pp_author', '_pp_posts_per_page', '_pp_primary-widget-area', '_pp_secondary-widget-area', '_pp_sidebar_width',
	'_pp_top-widget-area','_pp_bottom-widget-area','_pp_sitewide-top-widget-area', '_pp_sitewide-bottom-widget-area',
	'_pp_post_type', '_pp_hide_page_title','_pp_hide_site_title','_pp_hide_menus','_pp_hide_header_image',
	'_pp_hide_footer','_pp_hide_header','_pp_hide_sticky', '_pp_force_post_full','_pp_force_post_excerpt',
	'_pp_show_post_avatar', '_pp_bodyclass', '_pp_fi_link', '_pp_fi_location', '_pp_post_styles',
	'_pp_hide_top_post_meta','_pp_hide_bottom_post_meta', '_pp_stay_on_page', '_pp_hide_on_menu', '_pp_show_featured_img',
	'_pp_hide_infotop','_pp_hide_infobottom', '_pp_hide_visual_editor', '_pp_masonry_span2', '_show_post_bubble',
	'_pp_hide_post_title', '_pp_post_add_link', '_pp_hide_post_format_label', '_pp_page_layout', '_pp_wvrx_pwp_type',
	'_pp_wvrx_pwp_cols', '_pp_post_filter', '_pp_header-widget-area' ,'_pp_footer-widget-area',
	'_pp_hide_page_infobar', '_pp_hide_n_posts','_pp_fullposts', '_pp_pwp_masonry','_pp_pwp_compact','_pp_pwp_compact_posts',
	'_primary-widget-area', '_secondary-widget-area', '_header-widget-area', '_footer-widget-area', '_sitewide-top-widget-area',
	'_sitewide-bottom-widget-area', '_page-top-widget-area', '_page-bottom-widget-area'
	);

	$args = array('posts_per_page' => -1, 'post_type' => $what, 'post_status' => 'any' );
	echo '<ul>';

	$allposts = get_posts($args);
	foreach ($allposts as $post) {
		$id = $post->ID;
		setup_postdata($post);
		$meta = get_post_meta( $id );
		if (!empty($meta)) {
			$type = $post->post_type;
			$title = esc_html($post->post_title);
			$link = esc_url(get_permalink($id));
			$tlink = "<a href='{$link}' alt='Post {$id}' target='_blank'>{$title}</a>";
			$heading = false;
			foreach ($meta as $name => $val_array) {		// old value gets put into $val_array[0]
				if (in_array($name, $post_fields) ) {
					$val = $val_array[0];					// easier to work with
					if ($type == 'page') {
						echo "<li><strong><em>{$tlink}</em></strong> " . __('has Per Page settings.','weaverx-axtreme') . "</li>\n";
					} else {
						echo "<li><strong><em>{$tlink}</em></strong> " . __('has Per Post settings.','weaverx-axtreme') . "</li>\n";
					}
					break;
				}
			}
		}
	}
	echo '</ul>';
}
}	// end only load if Weaver Xtreme installed

?>
