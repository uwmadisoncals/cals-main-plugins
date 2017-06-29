<?php

// # Weaver X SW Globals ==============================================================
$wvrx_ts_opts_cache = false;	// internal cache for all settings

function wvrx_ts_help_link($ref, $label) {

    $t_dir = wvrx_ts_plugins_url('/help/' . $ref, '');
    $pp_help =  '<a style="text-decoration:none;" href="' . $t_dir . '" target="_blank" title="' . $label . '">'
		. '<span style="color:red; vertical-align: middle; margin-left:.25em;" class="dashicons dashicons-editor-help"></span></a>';
    echo $pp_help ;
}


// ===============================  options =============================

add_filter('widget_text', 'do_shortcode');		// add shortcode processing to standard text widget


// Interface to Weaver Xtreme

function wvrx_ts_fix_short($prefix, $msg ) {
	if ( $prefix ) {
		$m = str_replace('[/', '////', $msg);
		$m = str_replace('[', '[' . $prefix, $m);
		echo str_replace('////', '[/' . $prefix, $m);
	}
	else
		echo $msg;
}

add_action('weaverx_theme_support_addon','wvrx_ts_theme_support_addon');
function wvrx_ts_theme_support_addon() {

	$theme = get_template_directory();

	$is_xtreme = strpos( $theme, '/weaver-xtreme') !== false;

?>
<div class="a-plus">
	<p><strong style="font-size:110%;"><?php
	if ($is_xtreme) _e('You have Weaver Xtreme Theme Support installed.','weaverx-theme-support' /*adm*/);
	else  _e('You have Weaver Xtreme (Foundation) Theme Support installed.','weaverx-theme-support' /*adm*/);
	echo ' (V ' . WVRX_TS_VERSION . ')'; ?></strong><br />
	<?php _e('This section shows the shortcodes and widgets available with Weaver Xtreme (and Foundation) Theme Support.
Click the<span style="color:red; vertical-align: middle; margin-left:.25em;" class="dashicons dashicons-editor-help"></span> button to open help entry.','weaverx-theme-support' /*adm*/); ?></p>

<?php
	$prefix = get_option('wvrx_toggle_shortcode_prefix');
	if ( $prefix )
		echo '<h3 style="color:red;">' . __("Weaver Xtreme Theme Support Shortcodes now prefixed with 'wvrx_'", 'weaverx-theme-support') . '</h3>';
?>

    <h3><?php _e('Shortcodes','weaverx-theme-support' /*adm*/); ?></h3>
    <ul>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Blog Info - [bloginfo]</span> - Display blog info as provided by WordPress bloginfo function','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#bloginfo',__('Help for Blog Info','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[bloginfo name='WP bloginfo name' style='style-rules']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Box - [box]</span> - Display content in a Box','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#box',__('Help for Box','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[box background=#fff align=left border=true border_rule='border-css' border_radius=4 color=#000 margin=1 padding=1 shadow=1 style='style-rules' width=100]text[/box]",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">DIV - [div]text[/div]</span> - Wrap content in a &lt;div&gt; tag','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#scdiv',__('Help for Header Div','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[div id='class_id' class='class_name' style='style_values']text[/div]",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li<?php wvrx_ts_fix_short($prefix, __('><span class="atw-blue">Header Image - [header_image]</span> - Display default header image','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#headerimage',__('Help for Header Image','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[header_image h='size' w='size' style='inline-style']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>

    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">HTML - [html]</span> - Wrap content in any HTML tag','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#schtml',__('Help for HTML','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[html html-tag args='parameters']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">iFrame - [iframe]</span> - Display external content in an iframe','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#sciframe',__('Help for iframe','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[iframe src='//example.com' height=600 percent=100 style='style']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
	<li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Login - [login style="CSS Style"]</span> - Show simple Login/Logout link','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#sclogin',__('Help for login','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[login style=\"CSS Style\"]",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>

    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Show If- [show_if]</span> - Show content only if args meet specified conditions','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#scshowif',__('Help for Show/Hide If','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __('[show|hide_if device=device logged_in=true/false not_post_id=id-list post_id=id-list user_can=what]text[/show|hide_if]','weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Hide If - [hide_if]</span> - Hide content','weaverx-theme-support' /*adm*/)); ?>
    </li>

    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Site Tagline - [site_tagline style="style" matchtheme=false]</span> - Display the site tagline','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#sitetitlesc',__('Help for Site Tagline','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[site_tagline style='inline-style']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Site Title - [site_title style="style" matchtheme=false]</span> - Display the site title','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#sitetitlesc',__('Help for Site Title','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[site_title style='inline-style']",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">SPAN - [span]text[/span]</span> - Wrap content in a &lt;span&gt; tag','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#scdiv',__('Help for Span','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __("[span id='class_id' class='class_name' style='style_values']text[/span]",'weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Tab Group - [tab_group]</span> - Display content on separate tabs','weaverx-theme-support' /*adm*/));?>
        <?php wvrx_ts_help_link('help.html#tab_group',__('Help for Tab Group','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __('[tab_group][tab]...[/tab][tab]...[/tab][/tab_group]','weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">Vimeo - [vimeo]</span> - Display video from Vimeo responsively, with options','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#video',__('Help for Video','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __('[vimeo vimeo-url id=videoid sd=0 percent=100 center=1 color=#hex autoplay=0 loop=0 portrait=1 title=1 byline=1]','weaverx-theme-support' /*adm*/)); ?></code>
    </li>

    <li><?php wvrx_ts_fix_short($prefix, __('<span class="atw-blue">YouTube - [youtube]</span> - Display video from YouTube responsively, with options','weaverx-theme-support' /*adm*/)); ?>
        <?php wvrx_ts_help_link('help.html#video',__('Help for Video','weaverx-theme-support' /*adm*/));?><br />
        <code><?php wvrx_ts_fix_short($prefix, __('[youtube youtube-url id=videoid sd=0 percent=100 center=1 rel=0 privacy=0  see_help_for_others]','weaverx-theme-support' /*adm*/)); ?></code>
    </li>
    </ul>
	    <form enctype="multipart/form-data" name='toggle_shortcode' action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method='post'>

<?php
	if ( $is_xtreme ) {
		if ( $prefix )
			$button = __("Remove 'wvrx_' prefix from shortcode names: [ bloginfo ], etc.", 'weaverx-theme-support');
		else
			$button = __("Add 'wvrx_' to shortcode names: [ wvrx_bloginfo ], etc.", 'weaverx-theme-support');
	?>
		<div style="clear:both;"></div>
			<span class='submit'><input class="button-primary" name="toggle_shortcode_prefix" type="submit" value="<?php echo $button; ?>" /></span>
			<br /><small> <?php _e("To avoid conflicts with other plugins, you can add a 'wvrx_' prefix to these shortcodes.", 'weaver-xtreme /*adm*/'); ?> </small>
			<?php weaverx_nonce_field('toggle_shortcode_prefix'); ?>
			</form>
<?php	} ?>
		<br />

    <h3><?php _e('Widgets','weaverx-theme-support' /*adm*/); ?></h3>
    <ul>
    <li><?php _e('<span class="atw-blue">Weaver Login Widget</span> - Simplified login widget','weaverx-theme-support' /*adm*/); ?>
        <?php wvrx_ts_help_link('help.html#widg-login',__('Help for Login Widget','weaverx-theme-support' /*adm*/));?>
    </li>

    <li><?php _e('<span class="atw-blue">Weaver Per Page Text</span> - Display text on a per page basis, based on a Custom Field value','weaverx-theme-support' /*adm*/); ?>
        <?php wvrx_ts_help_link('help.html##widg_pp_text',__('Help for Per Page Text Widget','weaverx-theme-support' /*adm*/));?>
    </li>

    <li><?php _e('<span class="atw-blue">Weaver Text 2 Col</span> - Display text in two columns - great for wide top/bottom widgets','weaverx-theme-support' /*adm*/); ?>
        <?php wvrx_ts_help_link('help.html#widg_text_2',__('Help for Two Column Text Widget','weaverx-theme-support' /*adm*/));?>
    </li>
    </ul>

<?php if ( $is_xtreme ) { ?>
	<h3><?php _e('Per Page/Post Settings','weaverx-theme-support' /*adm*/); ?></h3>
	<p> <?php _e("Click the following button to produce a list of links to all pages and posts that have Per Page or Per Post settings.", 'weaver-xtreme /*adm*/'); ?></p>
	<div style="clear:both;"></div>
		<form enctype="multipart/form-data" name='toggle_shortcode' action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method='post'>
        <span class='submit'><input class="button-primary" name="show_per_page_report" type="submit" value="<?php _e('Show Pages and Posts with Per Page/Post Settings', 'weaver-xtreme /*adm*/'); ?>" /></span>
        <?php weaverx_nonce_field('show_per_page_report'); ?>
		</form><br /><br />
<?php } ?>
</div>

<?php
}

add_action('weaverx_more_help', 'weaverx_ts_more_help');
function weaverx_ts_more_help() {
?>
<hr />
<script>jQuery(document).ready(function(){jQuery('#wvrx-sysinfo').click(function(){jQuery('#wvrx-sysinfo').copyme();});
jQuery('#btn-sysinfo').click(function(){jQuery('#wvrx-sysinfo').copyme();}); });</script>

<h3><?php _e('Your System and Configuration Info','weaverx-theme-support' /*adm*/); ?></h3>
<?php
    $sys = weaverx_ts_get_sysinfo();
?>
<div style="float:left;max-width:60%;"><textarea id="wvrx-sysinfo" readonly class="wvrx-sysinfo no-autosize" style="font-family:monospace;" rows="12" cols="50"><?php echo $sys;?></textarea></div>
<div style="margin-left:20px;max-width:40%;float:left;"><?php _e('<p>This information can be used to help us diagnose issues you might be having with Weaver Xtreme.
If you are asked by a moderator on the <a href="//forum.weavertheme.com" target="_blank">Weaver Xtreme Support Forum</a>, please use the "Copy to Clipboard"
button and then Paste the Sysinfo report directly into a Forum post.</p>
<p>Please note that there is no personally identifying data in this report except your site\'s URL. Having your site URL is important to help us
diagnose the problem, but you can delete it from your forum post right after you paste if you need to.</p>','weaverx-theme-support');?></div>
<div style="clear:both;margin-bottom:20px;"></div>

<button id="btn-sysinfo" class="button-primary">Copy To Clipboard</button>
<?php
	//if (WEAVERX_DEV_MODE && isset($GLOBALS['POST_COPY']) && $GLOBALS['POST_COPY'] != false ) {
	//	echo '<pre>$_POST:'; var_dump($GLOBALS['POST_COPY']); echo '</pre>';
	//}
}

add_action('weaverx_ts_show_version','weaverx_ts_show_version_action');
function weaverx_ts_show_version_action() {
	echo "<!-- Weaver Xtreme Theme Support " . WVRX_TS_VERSION . " --> ";
}

function weaverx_ts_get_sysinfo() {

		global $wpdb;

		$theme      = wp_get_theme()->Name . ' (' . wp_get_theme()->Version .')';
		$frontpage	= get_option( 'page_on_front' );
		$frontpost	= get_option( 'page_for_posts' );
		$fr_page	= $frontpage ? get_the_title( $frontpage ).' (ID# '.$frontpage.')'.'' : 'n/a';
		$fr_post	= $frontpage ? get_the_title( $frontpost ).' (ID# '.$frontpost.')'.'' : 'n/a';
		$jquchk		= wp_script_is( 'jquery', 'registered' ) ? $GLOBALS['wp_scripts']->registered['jquery']->ver : 'n/a';

		$return = '### Weaver System Info ###' . "\n\n";

		// Basic site info
		$return .= '        -- WordPress Configuration --' . "\n\n";
		$return .= 'Site URL:                 ' . site_url() . "\n";
		$return .= 'Home URL:                 ' . home_url() . "\n";
		$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";
		$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
		$return .= 'Language:                 ' . get_locale() . "\n";
		//$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . "\n";
		$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'WP Memory Limit:          ' . WP_MEMORY_LIMIT . "\n";
		$return	.= 'Permalink:                ' . get_option( 'permalink_structure' ) ."\n";
		$return	.= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";
		$return	.= 'Page On Front:            ' . $fr_page . "\n";
		$return	.= 'Page For Posts:           ' . $fr_post . "\n";
		$return	.= 'Current Theme:            ' . $theme . "\n";
		$return	.= 'Post Types:               ' . implode( ', ', get_post_types( '', 'names' ) )."\n";

		// Plugin Configuration
		$return .= "\n" . '        -- Weaver Xtreme Configuration --' . "\n\n";
		$return .= 'Weaver Xtreme Version:    ' . WEAVERX_VERSION . "\n";
		$return .= '   Theme Support Version: ' . WVRX_TS_VERSION . "\n";
		if ( defined( 'WEAVER_XPLUS_VERSION' ) )
		$return .= '   Xtreme Plus Version:   ' . WEAVER_XPLUS_VERSION . "\n";

		// Server Configuration
		$return .= "\n" . '        -- Server Configuration --' . "\n\n";
		$return .= 'Operating System:         ' . php_uname( 's' ) . "\n";
		$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
		$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
		$return	.= 'jQuery Version:           ' . $jquchk . "\n";

		$return .= 'Server Software:          ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

		// PHP configs... now we're getting to the important stuff
		$return .= "\n" . '        -- PHP Configuration --' . "\n\n";
		//$return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
		$return .= 'Local Memory Limit:       ' . ini_get( 'memory_limit' ) . "\n";
		$return .= 'Server Memory Limit:      ' . get_cfg_var('memory_limit') . "\n";
		$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
		$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
		$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
		$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

		// WordPress active plugins
		$return .= "\n" . '        -- WordPress Active Plugins --' . "\n\n";
		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );
		foreach( $plugins as $plugin_path => $plugin ) {
			if( !in_array( $plugin_path, $active_plugins ) )
				continue;
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
		}

		// WordPress inactive plugins
		$return .= "\n" . '        -- WordPress Inactive Plugins --' . "\n\n";
		foreach( $plugins as $plugin_path => $plugin ) {
			if( in_array( $plugin_path, $active_plugins ) )
				continue;
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
		}

		if( is_multisite() ) {
			// WordPress Multisite active plugins
			$return .= "\n" . '        -- Network Active Plugins --' . "\n\n";
			$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			foreach( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );
				if( !array_key_exists( $plugin_base, $active_plugins ) )
					continue;
				$plugin  = get_plugin_data( $plugin_path );
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
			}
		}

		$return .= "\n" . '### End System Info ###' . "\n";
		return $return;
}

// ======================================= MCE CSS FILE GENARATION ================================

add_action('weaverx_save_mcecss', 'weaverx_ts_save_mcecss');		// theme support plugin saved editor css in file

function weaverx_ts_save_mcecss() {
	// generate and save mcecss style file

	if (!weaverx_f_file_access_available() || !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$save_dir = weaverx_f_uploads_base_dir() . 'weaverx-subthemes';
	$save_url = weaverx_f_uploads_base_url() . 'weaverx-subthemes';

	$usename = 'editor-style-wvrx.css';

	$theme_dir_exists = weaverx_f_mkdir($save_dir);
	if (!$theme_dir_exists) {
		weaverx_f_file_access_fail(__('Unable to create directory to save editor style file. Probably a file system permission problem. Directory', 'weaver-xtreme' /*adm*/) . $save_dir);
		return;
	}

	$theme_dir_writable = $theme_dir_exists;

   if (!weaverx_f_is_writable($save_dir)) {
		weaverx_f_file_access_fail(__('Directory not writable to save editor style file. Probably a file system permission problem. Directory: ', 'weaver-xtreme' /*adm*/) . $save_dir);
		return;
	}

	$filename = $save_dir . '/'. $usename;    // we will add txt

	if (!$theme_dir_writable || !$theme_dir_exists || !($handle = weaverx_f_open($filename, 'w')) ) {
		weaverx_f_file_access_fail(__('Unable to create editor style file. Probably a file system permission problem. File: ', 'weaver-xtreme' /*adm*/) . $filename);
		return;
	}

	weaverx_ts_output_edit_style($handle);

	if (!weaverx_f_close($handle)) {
		weaverx_f_file_access_fail(__('Unable to create editor css file. Probably a file system permission problem. File: ', 'weaver-xtreme' /*adm*/) . $filename);
		return '';
	}

	return $save_url . '/' . $usename;
}

function weaverx_ts_output_edit_style( $handle ) {

	$put = sprintf("/* WARNING: Do not edit this file. It is dynamically generated. Any edits you make will be overwritten. */
/* This file generated using %s %s subtheme: %s */\n",WEAVERX_THEMENAME, WEAVERX_VERSION, weaverx_getopt('themename'));

	if ( ( $twidth = weaverx_getopt_default( 'theme_width_int', WEAVERX_THEME_WIDTH ) ) ) {
	/*  figure out a good width - we will please most of the users, most of the time
		We're going to assume that mostly people will use the default layout -
		we can't actually tell if the editor will be for a page or a post at this point.
		And let's just assume the default sidebar widths.
	*/
		$sb_layout = weaverx_getopt_default('layout_default', 'right');
		switch ( $sb_layout ) {
			case 'left':
			case 'left-top':
				$sb_width = weaverx_getopt_default( 'left_sb_width_int', 25 );
				break;
			case 'split':
			case 'split-top':
				$sb_width = weaverx_getopt_default( 'left_split_sb_width_int', 25 )
							+ weaverx_getopt_default( 'right_split_sb_width_int', 25 );
				break;
			case 'one-column':
				$sb_width = 0;
				break;
			default:            // right
				$sb_width = weaverx_getopt_default( 'right_sb_width_int', 25 );
				break;
		}

		$content_w = $twidth - ( $twidth * (float)( $sb_width / 95.0 ) );   // .95 by trial and error

		//  calculate theme width based on default layout and width of sidebars.

	$put .=  "html .mceContentBody {max-width:96%;width:" . $content_w . "px;}\n";
	$put .=  "#content html .mceContentBody {max-width:96%;width:96%;}\n";
	}

	if (($base_font_px = weaverx_getopt('site_fontsize_int')) == '' )
		$base_font_px = 16;
	$base_font_px = (float)$base_font_px;
	$font_size = 'default';

	if (!is_page() && ($area_font = weaverx_getopt_default('post_font_size','default')) != 'default' )
		$font_size = $area_font;
	else if (($area_font = weaverx_getopt_default('content_font_size','default')) != 'default' )
		$font_size = $area_font;
	else if (($area_font = weaverx_getopt_default('container_font_size','default')) != 'default' )
		$font_size = $area_font;
	else if (($area_font = weaverx_getopt_default('wrapper_font_size','default')) != 'default' )
		$font_size = $area_font;

	switch ( $font_size ) {		// find conversion factor
		case 'xxs-font-size':
			$h_fontmult = 0.625;
			break;
		case 'xs-font-size':
			$h_fontmult = 0.75;
			break;
		case 's-font-size':
			$h_fontmult = 0.875;
			break;
		case 'l-font-size':
			$h_fontmult = 1.125;
			break;
		case 'xl-font-size':
			$h_fontmult = 1.25;
			break;
		case 'xxl-font-size':
			$h_fontmult = 1.5;
			break;
		default:
			$h_fontmult = 1;
			break;
	}

	$em_font_size = ( $base_font_px / 16.0) * $h_fontmult ;
	$put .= "label,th,thead th,tr,td,.mceContentBody,body{font-size:" . $em_font_size . "em;}\n";



	$val = weaverx_getopt_default('content_font_family', 'inherit');
	if ( $val == 'inherit' )
		$val = weaverx_getopt_default('container_font_family', 'inherit' );
	if ( $val == 'inherit' )
		$val = weaverx_getopt('wrapper_font_family');
	if ( $val != 'inherit' ) {    	// found a font {
		// these are not translatable - the values are used to define the actual font definition

		$fonts = array(
			'sans-serif' => 'Arial,sans-serif',
			'arialBlack' => '"Arial Black",sans-serif',
			'arialNarrow' => '"Arial Narrow",sans-serif',
			'lucidaSans' => '"Lucida Sans",sans-serif',
			'trebuchetMS' => '"Trebuchet MS", "Lucida Grande",sans-serif',
			'verdana' => 'Verdana, Geneva,sans-serif',
			'alegreya-sans' => "'Alegreya Sans', sans-serif",
			'roboto' => "'Roboto', sans-serif",
			'roboto-condensed' => "'Roboto Condensed', sans-serif",
			'source-sans-pro' => "'Source Sans Pro', sans-serif",


			'serif' => 'TimesNewRoman, "Times New Roman",serif',
			'cambria' => 'Cambria,serif',
			'garamond' => 'Garamond,serif',
			'georgia' => 'Georgia,serif',
			'lucidaBright' => '"Lucida Bright",serif',
			'palatino' => '"Palatino Linotype",Palatino,serif',
			'alegreya' => "'Alegreya', serif",
			'roboto-slab' => "'Roboto Slab', serif",
			'source-serif-pro' => "'Source Serif Pro', serif",

			'monospace' => '"Courier New",Courier,monospace',
			'consolas' => 'Consolas,monospace',
			'inconsolata' => "'Inconsolata', monospace",
			'roboto-mono' => "'Roboto Mono', sans-serif",

			'papyrus' => 'Papyrus,cursive,serif',
			'comicSans' => '"Comic Sans MS",cursive,serif',
			'handlee' => "'Handlee', cursive",

			'open-sans' => "'Open Sans', sans-serif",
			'open-sans-condensed' => "'Open Sans Condensed', sans-serif",
			'droid-sans' => "'Droid Sans', sans-serif",
			'droid-serif' => "'Droid Serif', serif",
			'exo-2' => "'Exo 2', sans-serif",
			'lato' => "'Lato', sans-serif",
			'lora' => "'Lora', serif",
			'arvo' => "'Arvo', serif",
			'archivo-black' => "'Archivo Black', sans-serif",
			'vollkorn' => "'Vollkorn', serif",
			'ultra' => "'Ultra', serif",
			'arimo' => "'Arimo', serif",
			'tinos' => "'Tinos', serif"
			);

		if ( isset($fonts[$val]) ) {
			$font = $fonts[$val];
		} else {
			$font = "Arial,'Helvetica Neue',Helvetica,sans-serif";   // fallback
			// scan Google Fonts
			$gfonts = weaverx_getopt_array('fonts_added');
			if ( !empty($gfonts) ) {
				foreach ($gfonts as $gfont) {
					$slug = sanitize_title($gfont['name']);
					if ( $slug == $val ) {
						$font = str_replace('font-family:','',$gfont['family']);//'Papyrus';
						break;
					}
				}
			}
		}
		$put .= ".mceContentBody,body,tr,td {font-family:" . $font . ";}\n";

	}

	/* need to handle bg color of content area - need to do the cascade yourself */

	if ( ($val = weaverx_getopt_default('editor_bgcolor','transparent')) && strcasecmp($val,'transparent') != 0) {	        /* alt bg color */
		$put .= ".mceContentBody,body{background:" . $val . ";padding:10px;}\n";
	} else if ( ($val = weaverx_getopt_default('content_bgcolor','transparent')) && strcasecmp($val,'transparent') != 0) {	/* #content */
		$put .= ".mceContentBody,body{background:" . $val . ";padding:10px;}\n";
	} else if ( ($val = weaverx_getopt_default('container_bgcolor','transparent') ) && strcasecmp($val,'transparent') != 0) { /* #container */
		$put .= ".mceContentBody,body{background:" . $val . ";padding:10px;}\n";
	} else if (($val = weaverx_getopt_default('wrapper_bgcolor','transparent')) && strcasecmp($val,'transparent') != 0) {    /* #wrapper */
		$put .= ".mceContentBody,body{background:" . $val . ";padding:10px;}\n";
	} else if (($val = weaverx_getopt_default('body_bgcolor','transparent')) && strcasecmp($val,'transparent') != 0) {    /* Outside BG */
		$put .= ".mceContentBody,body{background:" . $val . ";padding:10px;}\n";
	} else if (($name = weaverx_getopt('themename')) && strpos($name,'Transparent Dark') !== false) {	// fix in V3.0.5
		$put .= ".mceContentBody,body{background:#222;}\n";
	} else if (($name = weaverx_getopt('themename')) && strpos($name,'Transparent Light') !== false) {
		$put .= ".mceContentBody,body{background:#ccc;}\n";
	}

	if (($val = weaverx_getopt('content_color')) ) {	        // text color
		$put .= ".mceContentBody,body, tr, td {color:" . $val . ";}\n";
	} elseif (($val = weaverx_getopt('container_color')) ) {	// text color
		$put .= ".mceContentBody,body, tr, td {color:" . $val . ";}\n";
	} elseif (($val = weaverx_getopt('wrapper_color')) ) {	    // text color
		$put .= ".mceContentBody,body, tr, td {color:" . $val . ";}\n";
	}

	if (($val = weaverx_getopt('input_bgcolor')) ) {	// input area
		$put .= "input, textarea, ins, pre{background:" . $val . ";}\n";
	}

	if (($val = weaverx_getopt('input_color')) ) {
		$put .= "input, textarea, ins, del, pre{color:" . $val . ";}\n";
	}

	if (($val = weaverx_getopt('contentlink_color')) ) {	// link
		$put .= "a {color:" . $val . ";}\n";
	}

	if (($val = weaverx_getopt('contentlink_hover_color')) ) {
		$put .= "a:hover {color:" . $val . ";}\n";
	}

	/*  weaverx_tables  */
	$table = weaverx_getopt('weaverx_tables');

	if ($table == 'wide') {	// make backward compatible with 1.4 and before when Twenty Ten was default
		$put .= ".mceContentBody table {border: 1px solid #e7e7e7 !important;margin: 0 -1px 24px 0;text-align: left;width: 100%%;}
tr th, thead th {color: #888;font-size: 12px;font-weight: bold;line-height: 18px;padding: 9px 24px;}
.mceContentBody tr td {border-style:none !important; border-top: 1px solid #e7e7e7 !important; padding: 6px 24px;}
tr.odd td {background: rgba(0,0,0,0.1);}\n";
	} elseif ($table == 'bold') {
		$put .= ".mceContentBody table {border: 2px solid #888 !important;}
tr th, thead th {font-weight: bold;}
.mceContentBody tr td {border: 1px solid #888 !important;}\n";
	} elseif ($table == 'noborders') {
		$put .= ".mceContentBody table {border-style:none !important;}
.mceContentBody tr th, .mceContentBody thead th {font-weight: bold;border-bottom: 1px solid #888 !important;background-color:transparent;}
.mceContentBody tr td {border-style:none !important;}\n";
	} elseif ($table == 'fullwidth') {
		$put .= "table {width:100%%;}
tr th, thead th {font-weight:bold;}\n";
	} elseif ($table == 'plain') {
		$put .= ".mceContentBody table {border: 1px solid #888 !important;text-align:left;margin: 0 0 0 0;width:auto;}
tr th, thead th {color: inherit;background:none;font-weight:normal;line-height:normal;padding:4px;}
.mceContentBody tr td {border: 1px solid #888 !important; padding:4px;}\n";
	}

	if (($val = weaverx_getopt('contentlist_bullet')) ) {	// list bullet
		if ($val != '' && $val != 'disc') {
			if ($val != 'custom') {
				$put .= "ul {list-style-type:{$val};}\n";
			}
		}
	}

	// images
	if (($val = weaverx_getopt('caption_color')) ) {	// image caption, border color, width
		$put .= ".wp-caption p.wp-caption-text,.wp-caption-dd {color:{$val};}\n";
	}

	if (($val = weaverx_getopt('media_lib_border_color')) ) {
		$put .= ".wp-caption, img {background:{$val};}\n";
	}
	if (($val = weaverx_getopt('media_lib_border_int')) ) {
		$caplr = $val - 5;
		if ($caplr < 0)
			$caplr = 0;
		$put .=  "img {padding:{$val}px;}\n";
		$put .= sprintf(".wp-caption{padding: %dpx %dpx %dpx %dpx;}\n", $val, $caplr, $val, $caplr);
	}

	// <hr>

	if (($color = weaverx_getopt('hr_color')) && $color != 'inherit') {
		$put .= "hr{background-color:{$color};}\n";
	}
	if ( ($css = weaverx_getopt('hr_color_css')) ) {
		$put .= "hr{$css}\n";
	}

	// LINKS - link_color, link_strong, link_em, link_u, link_u_h, link_hover_color

	$put .= 'a{';

	if ( ($val = weaverx_getopt('link_color')) )
		$put .= "color:{$val};";

	$val = weaverx_getopt('link_strong');
	if ($val == 'on')
		$put .= "font-weight:bold;";
	else if ($val == 'off')
		$put .= "font-weight:normal;";

	$val = weaverx_getopt('link_em');
	if ($val == 'on')
		$put .= "font-style:italic;";
	else if ($val == 'off')
		$put .= "font-style:normal;";

	if ( ($val = weaverx_getopt('link_u')) )
		$put .= "text-decoration:underline;";

	$put .= '}a:hover{';

	if ( ($val = weaverx_getopt('link_hover_color')) )
		$put .= "color:{$val};";
	if ( ($val = weaverx_getopt('link_u_h')) )
		$put .= "text-decoration:underline;";
	$put .= "}\n";

	weaverx_f_write($handle, $put);
	return;
}

// and filter to use the generated file...

add_filter( 'weaverx_mce_css', 'weaverx_ts_mce_css');

function weaverx_ts_mce_css( $default_style ) {
	// build mce edit css path if we've generated the editor css
	$updir = wp_upload_dir();
	// make relative for https: - doesn't work right...
	// return parse_url(trailingslashit($updir['baseurl']) . 'weaverx-subthemes/style-weaverxt.css',PHP_URL_PATH);

	$dir = trailingslashit($updir['basedir']) . 'weaverx-subthemes/editor-style-wvrx.css';

	$path = trailingslashit($updir['baseurl']) . 'weaverx-subthemes/editor-style-wvrx.css';

	if (!@file_exists( $dir ))
		return '';

	if (is_ssl()) $path = str_replace('http:','https:',$path);
	return $default_style . ',' . $path;
}
?>
