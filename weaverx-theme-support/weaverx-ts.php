<?php
/*
Plugin Name: Weaver Xtreme Theme Support
Plugin URI: http://weavertheme.com/plugins
Description: Weaver X Theme Support - a package of useful shortcodes and widgets that integrates closely with the Weaver X theme. This plugin Will also allow you to switch from Weaver X to any other theme and still be able to use the shortcodes and widgets from Weaver X with minimal effort.
Author: wpweaver
Author URI: http://weavertheme.com/about/
Version: 2.0.3
License: GPL V3

Weaver Xtreme Theme Support

Copyright (C) 2014-2015 Bruce E. Wampler - weaver@weavertheme.com

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

define ('WVRX_TS_VERSION','2.0.3');
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

// load traditional Weaver Xtreme Options

function weaver_xtreme_load_admin_action() {
	require_once(dirname( __FILE__ ) . '/admin/add-weaverx-sapi-options.php'); // NOW - load the traditional opions admin
}

add_action('weaver_xtreme_load_admin','weaver_xtreme_load_admin_action');

if ( ! ( function_exists( 'weaverxplus_plugin_installed' ) && version_compare(WEAVER_XPLUS_VERSION,'0.13','>') ) ) {

add_action('admin_menu', 'wvrx_ts_add_page_fields',11);	// allow X-Plus to override us

function wvrx_ts_add_page_fields() {
	add_meta_box('page-box', __('Weaver Xtreme Options For This Page (Theme Support Per Page Options)','weaverx-theme-support'), 'wvrx_ts_page_extras_load', 'page', 'normal', 'high');
	add_meta_box('post-box', __('Weaver Xtreme Options For This Post (Theme Support Per Post Options)','weaverx-theme-support'), 'wvrx_ts_post_extras_load', 'post', 'normal', 'high');
	global $post;
	$opts = get_option( apply_filters('weaverx_options','weaverx_settings') , array());	// need to fetch Weaver Xtreme options
	if ((isset($opts['_show_per_post_all']) && $opts['_show_per_post_all']) || function_exists('atw_slider_plugins_loaded') ) {
		$i = 1;
		$args=array( 'public'   => true, '_builtin' => false );
		$post_types = get_post_types($args,'names','and');
		foreach ($post_types  as $post_type ) {
			add_meta_box('post-box' . $i, __('Weaver Xtreme Options For This Post (Theme Support Per Post Options)','weaverx-theme-support'), 'wvrx_ts_post_extras', $post_type, 'normal', 'high');
			$i++;
		}
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
/* -------------------
    echo '<h3 class="atw-option-subheader">' . __('Select an Add-on Subtheme You Have Uploaded','weaverx-theme-support') . '</h3>';
    $addon_dir = weaverx_f_uploads_base_dir() . 'weaverx-subthemes/addon-subthemes/';
    $addon_url = weaverx_f_uploads_base_url() . 'weaverx-subthemes/addon-subthemes/';

    $addon_list = array();
    if($media_dir = @opendir($addon_dir)) {	    // build the list of themes from directory
        while ($m_file = readdir($media_dir)) {
            $len = strlen($m_file);
            $base = substr($m_file,0,$len-4);
            $ext = $len > 4 ? substr($m_file,$len-4,4) : '';
            if($ext == '.wxt' ) {
                $addon_list[] = $base;
            }
        }
    }

    if (!empty($addon_list)) {
        natcasesort($addon_list);

        $cur_addon = weaverx_getopt('wvrx_addon_name');
        if ($cur_addon)
            echo '<h3>' . __('Currently selected Add-on Subtheme: ','weaverx-theme-support') . ucwords(str_replace('-',' ',$cur_addon)) . '</h3>';
?>
    <form enctype="multipart/form-data" name='pick_added_theme' action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method='post'>

     <h4><?php _e('Select an add-on subtheme:','weaverx-theme-support'); ?></h4>

<?php
        foreach ($addon_list as $addon) {
            $name = ucwords(str_replace('-',' ',$addon));
?>
            <div style="float:left; width:200px;">
            <label><input type="radio" name="wvrx_addon_name"
<?php	    echo 'value="' . $addon . '"' . (weaverx_getopt('wvrx_addon_name') == $addon ? 'checked' : '') .
            '/> <strong>' . $name . '</strong><br />
            <img style="border: 1px solid gray; margin: 5px 0px 10px 0px;" src="' . $addon_url . $addon . '.jpg" width="150px" height="113px" /><label></div>' . "\n";
        }
?>
        <div style="clear:both;"></div>
        <br /><span class='submit'><input name="set_added_subtheme" type="submit" value="<?php _e('Set to Selected Add-on Subtheme','weaverx-theme-support' ); ?>" /></span>
        <small style="color:#b00;"><br /><?php _e('<strong>Note:</strong> Selecting a new subtheme will change only theme related settings.
    Options labelled with (&diams;) will be retained.
	You can use the Save/Restore tab to save a copy of all your current settings first.','weaverx-theme-support' ); ?></small>

        <?php weaverx_nonce_field('set_added_subtheme'); ?>

        <br /><br /><span class='atw-small-submit' style="margin-left:100px;"><input name="delete_added_subtheme" type="submit" value="<?php _e('Delete Selected Add-on Subtheme','weaverx-theme-support'); ?>" /></span> &nbsp;
		<small><?php _e('This will delete the selected Add-on Subtheme from the Add-on directory','weaverx-theme-support'); ?></small>
        <?php weaverx_nonce_field('delete_added_subtheme'); ?>
        </form>
<?php
    } else {
?>
	<p><?php _e('No Add-on Subthemes available.','weaverx-theme-support'); ?></p>
<?php
    }
    echo '<h3 class="atw-option-subheader">Upload an Add-on Subtheme From Your Computer</h3>';
?>
    <p><?php _e('You can find additional free and premium Add-on Subthemes for <em>Weaver Xtreme</em>','weaverx-theme-support'); ?>
    <a href="//xtreme.weavertheme.com/add-on-subthemes/" title="<?php _e('Weaver Xtreme Add-on Subthemes','weaverx-theme-support'); ?>"><strong><?php _e('HERE','weaverx-theme-support'); ?></strong></a>.</p>
<form name='form_added_theme' enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
    <table>
	<tr valign="top">
	    <td><strong><?php _e('Select Add-on Subtheme .zip file to upload:','weaverx-theme-support'); ?></strong>
		<input name="uploaded_addon" type="file" />
		<input type="hidden" name="uploadaddon" value="yes" />
            </td>
	</tr>
        <tr><td>
	    <span style="margin-left:50px;" class='submit'>
		<input name="upload_addon" type="submit" value="Upload Add-on Subtheme" /><br />
	    </span>&nbsp;<small><?php _e('<strong>Upload and Save</strong> an Add-on Subtheme or Subtheme collection from .zip file on your computer. Will be saved on your site\'s filesystem.','weaverx-theme-support'); ?></small>
	</td></tr>
    </table>
    <?php weaverx_nonce_field('upload_addon'); ?>
</form>

<?php
------------- */
}

add_action('weaverx_child_process_options','wvrx_ts_child_process_options');
function wvrx_ts_child_process_options() {

    /* ---------------
	if (weaverx_submitted('set_added_subtheme') ) {	// Set to selected addon - theme
        if (isset($_POST['wvrx_addon_name']))
        {
            $name = $_POST['wvrx_addon_name'];

            $openname = weaverx_f_uploads_base_dir() . 'weaverx-subthemes/addon-subthemes/' . $name . '.wxt';
            $contents = file_get_contents($openname);

                if (!weaverx_ex_set_current_to_serialized_values($contents,'weaverx_uploadit:'.$openname)) {
                    echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">' .
__('Sorry, there was a problem uploading your add on theme. The name you picked did not have a valid
Weaver Xtreme theme file in the /weaverx-subthemes/addon-subthemes directory.','weaverx-theme-support')  . '</em></strong></p></div>';
            } else {
                weaverx_save_msg(__('Weaver Xtreme theme reset to ','weaverx-theme-support') .
                ucwords(str_replace('-',' ',$name )) . ' add-on subtheme.');
                weaverx_setopt('wvrx_addon_name',$name);
            }
        }
    }

    else if (weaverx_submitted('delete_added_subtheme') ) {	// Delete selected addon theme
        if (isset($_POST['wvrx_addon_name']))
        {
            $name = $_POST['wvrx_addon_name'];
            @unlink(weaverx_f_uploads_base_dir() . 'weaverx-subthemes/addon-subthemes/' . $name . '.wxt');
            @unlink(weaverx_f_uploads_base_dir() . 'weaverx-subthemes/addon-subthemes/' . $name . '.jpg');
            weaverx_save_msg(__('Deleted ','weaverx-theme-support') .
                ucwords(str_replace('-',' ',$name )) . __(' add-on subtheme.','weaverx-theme-support'));
        }
    }

    else if (weaverx_submitted('upload_addon')
	&& isset($_POST['uploadaddon'])
	&& $_POST['uploadaddon'] == 'yes') {
        // upload theme from users computer
        // they've supplied and uploaded a file
        $ok = wvrx_ts_wunpackzip('uploaded_addon', weaverx_f_uploads_base_dir() . 'weaverx-subthemes/addon-subthemes/');
    }

	else
	-------------------- */
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

/*-----------------
function wvrx_ts_wunpackzip($uploaded, $to_dir) {
    // upload theme from users computer
    // they've supplied and uploaded a file
    // This version and the one in Aspen Plus must be identical...

    $ok = true;     // no errors so far

    if (isset($_FILES[$uploaded]['name']))	// uploaded_addon
        $filename = $_FILES[$uploaded]['name'];
    else
        $filename = "";

    if (isset($_FILES[$uploaded]['tmp_name'])) {
        $openname = $_FILES[$uploaded]['tmp_name'];
    } else {
        $openname = "";
    }

    //Check the file extension
    $check_file = strtolower($filename);
    $per = '.';
    $end = explode($per, $check_file);		// workaround for PHP strict standards warning
    $ext_check = end($end);

    if (false && !weaverx_f_file_access_available()) {
        $errors[] = __('Sorry - Theme unable to access files.','weaverx-theme-support') . '<br />';
        $ok = false;
    }

    if ($filename == "") {
        $errors[] = __('You didn\'t select a file to upload.','weaverx-theme-support') . '<br />';
        $ok = false;
    }

    if ($ok && $ext_check != 'zip'){
        $errors[] = __("Uploaded files must have <em>.zip</em> extension.",'weaverx-theme-support') . "<br />";
        $ok = false;
    }

    if ($ok) {
        if (!weaverx_f_exists($openname)) {
            $errors[] = '<strong><em style="color:red;">' .
__('Sorry, there was a problem uploading your file. You may need to check your folder permissions
or other server settings.','weaverx-theme-support') . '</em></strong><br />' . __('Trying to use file','weaverx-theme-support') . "'$openname'";
            $ok = false;
        }
    }

    if ($ok) {
        // should be ready to go, but check out WP_Filesystem
        if (! WP_Filesystem()) {
                function wvrx_ts_wvx_return_direct() { return 'direct'; }
                add_filter('filesystem_method', 'wvrx_ts_wvx_return_direct');
                $try2 = WP_Filesystem();
                remove_filter('filesystem_method', 'wvrx_ts_wvx_return_direct');
                if (!$try2) {
                    $errors[] = __('Sorry, there\'s a problem trying to use the WordPress unzip function. Please
    see the FAQ at weavertheme.com support for more information.','weaverx-theme-support');
                    $ok = false;
                }
        }
    }
    if ($ok) {
        // $openname has uploaded .zip file to use
        // $filename has name of file uploaded
        $is_error = unzip_file( $openname, $to_dir );
        if ( !is_wp_error( $is_error ) ) {
            weaverx_save_msg(__('File ','weaverx-theme-support') . $filename . __(' successfully uploaded and unpacked to: <br />','weaverx-theme-support') . $to_dir);
            @unlink($openname);	// delete temp file...
        } else {
            $errors[] = __("Sorry, unpacking the .zip you selected file failed. You may have a corrupt .zip file, or there many a file permissions problem on your WordPress installation.",'weaverx-theme-support');
            $errors[] = $is_error->get_error_message();
            $ok = false;
        }
    }
    if (!$ok) {
        echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">' . __('ERROR','weaverx-theme-support') . '</em></strong></p><p>';
        foreach($errors as $error){
            echo $error.'<br />';
        }
        echo '</p></div>';
    }
    return $ok;
}
---------------- */

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

function weaverx_check_updates_action() {
	require_once('wp-updates-theme-1411.php');
	$theme = basename(get_template_directory());
	new WPUpdatesThemeUpdater_1411( 'http://wp-updates.com/api/2/theme', $theme );
}

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
