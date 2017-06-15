<?php
// files needed to be loaded before any ts lib sapi interface actions
//
function weaverx_process_options_themes() {

	if (weaverx_submitted('set_subtheme')) {	// invoked from Weaver Xtreme Subthemes tab (this file)
		if (isset($_POST['theme_picked'])) {
			$theme = weaverx_filter_textarea($_POST['theme_picked']);

			if (weaverx_activate_subtheme($theme))
				weaverx_save_msg(__("Subtheme Selected: ", 'weaver-xtreme' /*adm*/) . $theme );
			else
				weaverx_save_msg(__("Invalid Subtheme file detected. Your installation of Weaver Xtreme may be broken.", 'weaver-xtreme' /*adm*/));
		} else {
			weaverx_save_msg(__("Please select a subtheme.", 'weaver-xtreme' /*adm*/));
		}
		return true;
	}

	if (weaverx_submitted('save_mytheme')) {	// invoked from Save/Restore tab
		weaverx_save_msg(__("Current settings saved in WordPress database.", 'weaver-xtreme' /*adm*/));
		global $weaverx_opts_cache;
		if (!$weaverx_opts_cache)
			$weaverx_opts_cache = get_option( apply_filters('weaverx_options','weaverx_settings') ,array());
		if (current_user_can( 'manage_options' )) {
			$compressed = array_filter( $weaverx_opts_cache, 'strlen'); // filter out all null options (strlen == 0)
			update_option(apply_filters('weaverx_options','weaverx_settings_backup'),$compressed);
			if ( apply_filters('weaverx_xtra_type', '+backup' ) != 'inactive')
				delete_option('weaverx_plus_backup');
		}
		return true;
	}

	if (weaverx_submitted('restore_mytheme')) {	// invoked from Save/Restore tab
		global $weaverx_opts_cache;
		$saved = get_option( apply_filters('weaverx_options','weaverx_settings_backup') ,array());
		if (!empty($saved)) {
			$weaverx_opts_cache = $saved;
			weaverx_wpupdate_option('weaverx_settings',$weaverx_opts_cache);
		}
		weaverx_save_msg(__("Current settings restored from WordPress database.", 'weaver-xtreme' /*adm*/));
		return true;
	}

	if (weaverx_submitted('hide_thumbs')) {
		$hide = weaverx_getopt('_hide_theme_thumbs');
		weaverx_setopt('_hide_theme_thumbs', !$hide);
		return true;
	}

	// save/restore options
	if ( weaverx_submitted( 'weaverx_clear_messages' ) ) {
		return true;
	}
	if (weaverx_submitted('reset_weaverx')) {
		if (! current_user_can('manage_options'))
			wp_die(__('You do not have the capability to do that.', 'weaver-xtreme' /*adm*/));
		// delete everything!
		weaverx_save_msg(__('All Weaver Xtreme settings have been reset to the defaults.','weaver-xtreme'));
		delete_option( apply_filters('weaverx_options','weaverx_settings') );
		global $weaverx_opts_cache;
		$weaverx_opts_cache = false;	// clear the cache
		weaverx_init_opts('reset_weaverx');
		delete_option( apply_filters('weaverx_options','weaverx_settings_backup') );

		do_action('weaverxplus_admin','reset_weaverxplus');

		update_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice', 0 );     // reset the dismiss on the plugin loader
		return true;
	}

	if (weaverx_submitted('uploadtheme') && function_exists('weaverx_loadtheme')) {
		weaverx_loadtheme();
		return true;
	}

	return false;
}

function weaverx_activate_subtheme($theme) {
	/* load settings for specified theme */
	global $weaverx_opts_cache;

	/* build the filename - theme files stored in /wp-content/themes/weaver-xtreme/subthemes/

	Important: the following code assumes that any of the pre-defined theme files won't have
	and end-of-line character in them, which should be true. A user could muck about with the
	files, and possibly break this assumption. This assumption is necessary because the WP
	theme rules allow 'file', but not 'file get contents'. Other than that, the following code
	is really the same as the 'theme' section of weaverx_upload_theme() in the pro library
	*/

	$ext = '.wxt';

	$filename = get_template_directory() . '/subthemes/' . $theme . $ext;

	if ( ! weaverx_f_exists( $filename ) ) {
		$filename = str_replace('.wxt', '.wxb', $filename);
		if ( ! weaverx_f_exists( $filename ) )
			return false;
		else
			$ext = '.wxb';
	}

	$contents = weaverx_f_get_contents($filename);	// use either real (pro) or file (standard) version of function

	if (empty($contents)) return false;

	if ( substr($contents,0,10) != 'WXT-V01.00' ) {
		if ($ext == '.wxb' && substr($contents,0,10) != 'WXB-V01.00' )
			return false;
	}

	$restore = array();
	$restore = unserialize(substr($contents,10));

	if (!$restore) return false;
	$version = weaverx_getopt('weaverx_version_id');	// get something to force load

	// need to clear some settings
	// first, pickup the per-site settings that aren't theme related...
	$new_cache = array();
	if ( $ext == '.wxt' ) {
		foreach ($weaverx_opts_cache as $key => $val) {
			if ($key[0] == '_') {	// these are non-theme specific settings
				$new_cache[$key] = $weaverx_opts_cache[$key];	// clear
			}
		}
	}

	$opts = $restore['weaverx_base'];	// fetch base opts
	weaverx_delete_all_options();

	foreach ($new_cache as $key => $val) {	// set the values we need to keep
		weaverx_setopt($key,$new_cache[$key],false);
	}
	foreach ($opts as $key => $val) {
		if ($key[0] == '_' && $ext != '.wxb' )
			continue;	// should be here
		weaverx_setopt($key, $val, false);	// overwrite with saved theme values
	}

	weaverx_setopt('theme_filename',$theme);
	weaverx_setopt('last_option','Weaver Xtreme');

	weaverx_save_opts('set subtheme');	// OK, now we've saved the options, update them in the DB
	return true;
}

//================= moved from old lib-admin ================
//============================================ form builder ====================================

function weaverx_form_show_options($weaverx_olist, $begin_table = true, $end_table = true) {
	/* output a list of options - this really does the layout for the options defined in an array */
	if ($begin_table) {
?>
<div>
<table class="optiontable" style="margin-top:6px;">
<?php
	}
	foreach ($weaverx_olist as $value) {
		$value['type'] = weaverx_fix_type($value['type']);
		switch ($value['type']) {
			case 'align':
				weaverx_form_align($value);
				break;
			case 'break':
				weaverx_form_break($value);
				break;
			case 'checkbox':
				weaverx_form_checkbox($value);
				break;
			case 'ctext':
				weaverx_form_ctext($value);
				break;
			case 'color':
				weaverx_form_color($value);
				break;
			case 'custom_css':
				weaverx_custom_css($value);
				break;
			case 'endheader':
				echo '<!-- end header -->';
				break;
			case 'fi_align':
				weaverx_form_fi_align($value);
				break;
			case 'fi_location':
				weaverx_from_fi_location($value);
				break;
			case 'fi_location_post':
				weaverx_from_fi_location($value, true);
				break;
			case 'fixedtop':
				weaverx_form_fixedtop($value);
				break;
			case 'header':
				weaverx_form_header($value);
				break;
			case 'header_area':
				weaverx_form_header_area($value);
				break;
			case 'header0':
				weaverx_form_header($value,true);
				break;
			case 'inactive':
				weaverx_form_inactive($value);
				break;
			case 'link':
				weaverx_form_link($value);
				break;
			case 'menu_opts':
				weaverx_form_menu_opts($value, false);
				break;
			case 'menu_opts_submit':
				weaverx_form_menu_opts($value, true);
				break;
			case 'note':
				weaverx_form_note($value);
				break;
			case 'radio':
				weaverx_form_radio($value);
				break;
			case 'rounded':
				weaverx_form_rounded($value);
				break;
			case 'select_hide':
				weaverx_form_select_hide($value);
				break;
			case 'select_id':
				weaverx_form_select_id($value);
				break;
			case 'select_layout':
				weaverx_form_select_layout($value);
				break;
			case 'shadows':
				weaverx_form_shadows($value);
				break;
			case 'subheader':
				weaverx_form_subheader($value);
				break;
			case 'subheader_alt':
				weaverx_form_subheader_alt($value);
				break;
			case 'submit':
				weaverx_form_submit($value);
				break;
			case 'text':
			case 'widetext':
				weaverx_form_text($value);
				break;
			case 'text_xy':
				weaverx_form_text_xy($value);
				break;
			case 'text_xy_em':
				weaverx_form_text_xy($value,'X','Y','em');
				break;
			case 'text_xy_percent':
				weaverx_form_text_xy($value,'X','Y','%');
				break;
			case 'text_tb':
				weaverx_form_text_xy($value,'T','B');
				break;
			case 'text_lr':
				weaverx_form_text_xy($value,'L','R');
				break;
			case 'text_lr_em':
				weaverx_form_text_xy($value,'L','R','em');
				break;
			case 'text_lr_percent':
				weaverx_form_text_xy($value,'L','R','%');
				break;
			case 'textarea':
				weaverx_form_textarea($value);
				break;
			case 'titles':
				weaverx_form_text_props($value, 'titles');
				break;
			case 'titles_area':
				weaverx_form_text_props($value, 'area');
				break;
			case 'titles_content':
				weaverx_form_text_props($value, 'content');
				break;
			case 'titles_menu':
				weaverx_form_text_props($value, 'menu');
				break;
			case 'titles_text':
				weaverx_form_text_props($value, 'text');
				break;
			case 'val_num':
				weaverx_form_val($value,'');
				break;
			case 'val_percent':
				weaverx_form_val($value,'%');
				break;
			case 'val_px':
				weaverx_form_val($value,'px');
				break;
			case 'val_em':
				weaverx_form_val($value,'em');
				break;
			case 'widget_area':
				weaverx_form_widget_area($value, false);
				break;
			case 'widget_area_submit':
				weaverx_form_widget_area($value, true);
				break;
			default:
				weaverx_form_subheader_alt($value);
				break;
		}

	}
	if ($end_table) {
?>
</table></div> <!-- close previous tab div -->
	<br />
<?php
	}
}

function weaverx_fix_type($type) {
	return apply_filters('weaverx_xtra_type', $type );
}

function weaverx_form_inactive($value, $reason= '') {
	if ( $reason == '' )
		$reason = '<small>' . __('Weaver Xtreme Plus Options', 'weaver-xtreme' /*adm*/) . '&nbsp;</small>';
	if (!isset($value['name']) || !isset($value['id']) || !isset($value['info'])) {     // probably an '=submit'
		return;
	}
	$title = $value['name'];
	if (strlen($title) < 1) $title = ' ';        // make code work for invisibles
	if ($title[0] == '#')
		$title = substr($title,4);    // strip color
	echo '  <tr>' . "\n";
?>
	<th scope="row" style="width:200px;"><?php      /* NO SAPI SETTING */
	echo '<span style="color:#777;float:right;">'.$title.':&nbsp;</span>';
	if (!empty($value['help'])) {
		weaverx_help_link($value['help'], __('Help for ', 'weaver-xtreme' /*adm*/) . $title);
	}
?>
		</th>
		<td style="color:#777;"><?php echo $reason; ?>
		<input type="hidden" name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>"  value="<?php if ( weaverx_getopt( $value['id'] ) != "") { weaverx_esc_textarea(weaverx_getopt( $value['id'] )); } else { echo ''; } ?>" />
		</td>
<?php
		if ($value['info'] != '') {
			echo('<td style="padding-left:10px;color:#777;font-size:x-small;">'); echo $value['info'];
			echo("</td>\n");
		}
?>
	</tr>
<?php
}


function weaverx_echo_name($value, $add_icon = '') {
	$l = $add_icon . $value['name'];
	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';
	if (strlen($l) > 4 && $l[0] == '#') {
		echo '<span style="color:' . substr($l,0,4) .
			';">' . substr($l,4) . '</span>';
	} else if ( $icon[0] == '-' ) {                      // add a leading icon
		echo '<span class="dashicons dashicons-' . substr( $icon, 1) . '">' . $l . '</span>';
	} else {
		echo $l;
	}
}

function weaverx_form_ctext( $value, $val_only = false ) {

	$pclass = 'color {hash:true, adjust:false}';    // starting with V 1.3, allow text in color pickers
	$img_css = '<img src="'. esc_url(get_template_directory_uri() . '/assets/images/theme/css.png') . '" alt="css" />' ;
	$img_hide = esc_url(get_template_directory_uri() . '/assets/images/theme/hide.png') ;
	$img_show = esc_url(get_template_directory_uri() . '/assets/images/theme/show.png') ;
	$help_file = esc_url(get_template_directory_uri() . '/help/css-help.html');
	$css_id = $value['id'] . '_css';
	$css_id_text = weaverx_getopt($css_id);
	if ($css_id_text && !weaverx_getopt( '_hide_auto_css_rules' )) {
		$img_toggle = $img_hide;
	} else {
		$img_toggle = $img_show;
	}
	$add_icon = '<span class="i-left-bg dashicons dashicons-admin-appearance"></span>';
	if (strpos($value['name'], ' BG') === false)
		$add_icon = '<span class="i-left-fg dashicons dashicons-admin-appearance"></span>';
	if ( ! $val_only ) { ?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value, $add_icon ); ?>:&nbsp;</th>
	<td> <?php
	} else {
		echo '&nbsp;<small>' . $value['info'] . '</small>&nbsp;';
	} ?>
	<input class="<?php echo $pclass; ?>" name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text" style="width:90px" value="<?php if ( weaverx_getopt( $value['id'] ) != "") { weaverx_esc_textarea(weaverx_getopt( $value['id'] )); } else { echo ''; } ?>" />
<?php
echo $img_css; ?><a href="javascript:void(null);" onclick="weaverx_ToggleRowCSS(document.getElementById('<?php echo $css_id . '_js'; ?>'), this, '<?php echo $img_show; ?>', '<?php echo $img_hide; ?>')"><?php echo '<img src="' . esc_url($img_toggle) . '" alt="toggle css" />'; ?></a>
	<?php if (  ! $val_only ) { ?>
	</td>
<?php 	weaverx_form_info($value);
?>
	</tr>
<?php }
	$css_rows = weaverx_getopt('_css_rows');
	if ($css_rows < 1 || $css_rows > 25)
		$css_rows = 1;
	if ($css_id_text && !weaverx_getopt( '_hide_auto_css_rules' )) { ?>
	<tr id="<?php echo $css_id . '_js'; ?>">
	<th scope="row" align="right"><span style="color:#22a;"><small><?php _e('Custom CSS styling:', 'weaver-xtreme' /*adm*/); ?></small></span></th>
	<td align="right"><small>&nbsp;</small></td>
	<td>
		<small>
<?php _e('You can enter CSS rules, enclosed in {}\'s, and separated by <strong>;</strong>. See ', 'weaver-xtreme' /*adm*/); ?>
<a href="<?php echo $help_file; ?>" target="_blank"><?php _e('CSS Help', 'weaver-xtreme' /*adm*/); ?></a> <?php _e('for more details.', 'weaver-xtreme' /*adm*/); ?></small><br />
	<?php weaverx_textarea( $css_id_text, $css_id, $css_rows,'{ font-size:150%; font-weight:bold; } /* for example */' ); ?>
	</td>
	</tr>
<?php
	} else {
?>
	<tr id="<?php echo $css_id . '_js'; ?>" style="display:none;">
	<th scope="row" align="right"><span style="color:green;"><small><?php _e('Custom CSS styling:', 'weaver-xtreme' /*adm*/); ?></small></span></th>
	<td align="right"><small>&nbsp;</small></td>
	<td>
		<small>
<?php _e('You can enter CSS rules, enclosed in {}\'s, and separated by <strong>;</strong>. See', 'weaver-xtreme' /*adm*/); ?>
<a href="<?php echo $help_file; ?>" target="_blank"><?php _e('CSS Help', 'weaver-xtreme' /*adm*/); ?></a> for more details.</small><br />
		<?php weaverx_textarea( $css_id_text, $css_id, $css_rows,'{ font-size:150%; font-weight:bold; } /* for example */' ); ?>
	</td>
	</tr>
<?php
	}
}

function weaverx_textarea($text, $id, $rows = 0, $place = '', $style = 'width:85%;', $class='wvrx-edit', $filter = true) {
	$name = weaverx_sapi_main_name($id, false);
	/* if ($text) {
		$newrows = count((explode("\n",$text)))+1;
		if ($newrows > $rows)
			$rows = $newrows;
	} else { */
	if ( $rows < 2 ) {
		$rows = 1;
	}
	if ($rows > 25) $rows = 25;
	if ( $filter )
		$text = weaverx_esc_textarea($text, false);	// don't echo
	echo "<textarea class='{$class}' placeholder='{$place}' name='{$name}' rows='$rows' style='{$style}'>{$text}</textarea>\n";
}


function weaverx_form_color($value, $val_only = false) {

	$pclass = 'color {hash:true, adjust:false}';    // starting with V 1.3, allow text in color pickers
	if ( ! $val_only ) {
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value, '<span class="i-left-fg dashicons dashicons-admin-appearance"></span>'); ?>:&nbsp;</th>
	<td>
	<?php } else { echo '&nbsp;<small>' . $value['info'] . '</small>&nbsp;'; } ?>
	<input class="<?php echo $pclass; ?>" name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text" style="width:90px" value="<?php if ( weaverx_getopt( $value['id'] ) != "") { weaverx_esc_textarea(weaverx_getopt( $value['id'] )); } else { echo ' '; } ?>" />
	<?php if (! $val_only ) { ?>
	</td>
<?php 	weaverx_form_info($value);
?>
	</tr>
<?php
	}
}
function weaverx_form_header($value, $narrow=false) {
?>
	<tr class="atw-row-header">
	<th scope="row" align="left" style="width:200px;"><?php	/* NO SAPI SETTING */

	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';

	$dash = '';
	if ( $icon[0] == '-' ) {                      // add a leading icon
		$dash = '<span style="padding: .1em .5em 0 .2em" class="dashicons dashicons-' . substr( $icon, 1) . '"></span>';
	}
	echo weaverx_anchor($value['name']) . $dash . '<span style="font-weight:bold; font-size: larger;"><em>'. $value['name'] .'</em></span>';
		weaverx_form_help($value);
?>
	</th>
<?php
	if ($narrow) echo ('<td  style="width:80px;">&nbsp;</td>'. "\n");
	else echo ('<td style="width:170px;">&nbsp;</td>'. "\n");

	if ($value['info'] != '') {
		echo('<td style="padding-left: 10px"><u><em><strong>'); echo $value['info'];
		echo("</strong></em></u></td>\n");
	}
?>
	</tr>
<?php
}

function weaverx_anchor( $title ) {
	if ( $title )
		return '<a class="anchorx" id="' . sanitize_title( $title ) . '"></a>';
	return '';
}

function weaverx_form_help($value) {
	if (!empty($value['help'])) {
		weaverx_help_link($value['help'], 'Help for ' . $value['name']);
	}
}

function weaverx_form_subheader($value) {
?>
	<tr class="atw-row-subheader">
	<th scope="row" align="left" style="width:200px;line-height:2em;"><?php	/* NO SAPI SETTING */

	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';

	$dash = '';
	if ( $icon[0] == '-' ) {                      // add a leading icon
		$dash = '<span style="padding:.2em;" class="dashicons dashicons-' . substr( $icon, 1) . '"></span>';
	}

	echo weaverx_anchor($value['name']) . $dash . '<span style="color:blue; font-weight:bold; "><em><u>'.$value['name'].'</u></em></span>';
	weaverx_form_help($value);
?>
	</th>
	<td style="width:170px;">&nbsp;</td>
<?php
	if ($value['info'] != '') {
		echo('<td style="padding-left: 10px"><u><em>'); echo $value['info'];
		echo("</em></u></td>\n");
	}
?>
	</tr>
<?php
}

function weaverx_form_subheader_alt($value) {
?>
	<tr><td>&nbsp;</td></tr>
	<tr class="atw-row-subheader-alt" >
	<th scope="row" align="left" style="width:200px;line-height:2em;"><?php	/* NO SAPI SETTING */

	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';

	$dash = '';
	if ( $icon[0] == '-' ) {                      // add a leading icon
		$dash = '<span style="padding:.2em;" class="dashicons dashicons-' . substr( $icon, 1) . '"></span>';
	}
	echo weaverx_anchor($value['name']) . $dash . '<span style="color:blue; font-weight:bold;padding-left:5px;"><em>'.$value['name'].'</em></span>';
	weaverx_form_help($value);
?>
	</th>
	<td style="width:170px;">&nbsp;</td>
<?php
	if (isset($value['info']) &&  $value['info'] != '') {
		echo('<td style="padding-left: 10px;color:blue;">'); echo $value['info'];
		echo("</td>\n");
	}
?>
	</tr>
<?php
}

function weaverx_form_header_area($value) {
?>
	<tr><td>&nbsp;</td></tr>
	<tr class="atw-row-subheader-area" >
	<th scope="row" align="left" style="width:200px;line-height:2em;"><?php	/* NO SAPI SETTING */

	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';

	$dash = '';
	if ( $icon[0] == '-' ) {                      // add a leading icon
		$dash = '<span style="padding:.2em;" class="dashicons dashicons-' . substr( $icon, 1) . '"></span>';
	}

	echo weaverx_anchor($value['name']) . $dash . '<span style="color:blue; font-weight:bold;padding-left:5px;font-size:small;"><em>'.$value['name'].'</em></span>';
	weaverx_form_help($value);
?>
	</th>
	<td style="width:170px;">&nbsp;</td>
<?php
	if ($value['info'] != '') {
		echo('<td style="padding-left: 10px;color:blue;">'); echo $value['info'];
		echo("</td>\n");
	}
?>
	</tr>
<?php
}

//-- load theme settings needed here

function weaverx_loadtheme() {
	if (!(isset($_POST['uploadit']) && $_POST['uploadit'] == 'yes')) return;

   // upload theme from users computer
	// they've supplied and uploaded a file

	$ok = true;     // no errors so far

	if (isset($_FILES['uploaded']['name']))
		$filename = $_FILES['uploaded']['name'];
	else
		$filename = "";

	if (isset($_FILES['uploaded']['tmp_name'])) {
		$openname = $_FILES['uploaded']['tmp_name'];
	} else {
		$openname = "";
	}

	//Check the file extension
	$check_file = strtolower($filename);
	$pat = '.';				// PHP version strict checking bug...
	$end = explode($pat, $check_file);
	$ext_check = end($end);


	if ($filename == "") {
		$errors[] = __('You didn\'t select a file to upload.', 'weaver-xtreme' /*adm*/) . "<br />";
		$ok = false;
	}

	if ($ok && $ext_check != 'wxt' && $ext_check != 'wxb'){
		$errors[] = __('Theme files must have <em>.wxt</em> or <em>.wxb</em> extension.', 'weaver-xtreme' /*adm*/) . '<br />';
		$ok = false;
	}

		if ($ok) {
			if (!weaverx_f_exists($openname)) {
				$errors[] = '<strong><em style="color:red;">' .
__('Sorry, there was a problem uploading your file.
You may need to check your folder permissions or other server settings.', 'weaver-xtreme' /*adm*/) .
'</em></strong><br />(' . __('Trying to use file', 'weaver-xtreme' /*adm*/) . ' <em>' . $openname . '</em>)';
				$ok = false;
			}
		}
	if (!$ok) {
		echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">' .
		__('ERROR', 'weaver-xtreme' /*adm*/) . '</em></strong></p><p>';
		foreach($errors as $error){
			echo $error.'<br />';
		}
		echo '</p></div>';
	} else {    // OK - read file and save to My Saved Theme
		// $handle has file handle to temp file.
		$contents = weaverx_f_get_contents($openname);

		if ( ! weaverx_ex_set_current_to_serialized_values($contents,'weaverx_uploadit:'.$openname ) ) {
				echo '<div id="message" class="updated fade"><p><strong><em style="color:red;">' .
__('Sorry, there was a problem uploading your file.
The file you picked was not a valid Weaver Xtreme theme file.', 'weaver-xtreme' /*adm*/) .
'</em></strong></p></div>';
		} else {
				weaverx_save_msg( __('Weaver Xtreme theme options reset to uploaded theme.', 'weaver-xtreme' /*adm*/) );
		}
	}
}

function weaverx_ex_set_current_to_serialized_values($contents)  {
	global $weaverx_opts_cache;	// need to mess with the cache

	if (substr($contents,0,10) == 'WXT-V01.00')
		$type = 'theme';
	else if (substr($contents,0,10) == 'WXB-V01.00')
		$type = 'backup';
	else {
		$val = substr($contents,0,10);
		return weaverx_f_fail(__("Wrong theme file format version", 'weaver-xtreme' /*adm*/) . ':' . $val); 	/* simple check for one of ours */
	}
	$restore = array();
	$restore = unserialize(substr($contents,10));

	if (!$restore) return weaverx_f_fail(__("Unserialize failed", 'weaver-xtreme' /*adm*/));

	$version = weaverx_getopt('weaverx_version_id');	// get something to force load

	if ($type == 'theme') {
		// need to clear some settings
		// first, pickup the per-site settings that aren't theme related...
		$new_cache = array();
		foreach ($weaverx_opts_cache as $key => $val) {
			if (isset($key[0]) && $key[0] == '_')	// these are non-theme specific settings
				$new_cache[$key] = $val;	// keep
		}
		$opts = $restore['weaverx_base'];	// fetch base opts

		weaverx_delete_all_options();

		foreach ($opts as $key => $val) {
			if (isset($key[0]) && $key[0] != '_')
				weaverx_setopt($key, $val, false);	// overwrite with saved theme values
		}

		foreach ($new_cache as $key => $val) {	// set the values we need to keep
			weaverx_setopt($key,$val,false);
		}
	} else if ($type == 'backup') {
		weaverx_delete_all_options( );

		$opts = $restore['weaverx_base'];	// fetch base opts
		foreach ($opts as $key => $val) {
			weaverx_setopt($key, $val, false);	// overwrite with saved values
		}
	}

	weaverx_setopt('weaverx_version_id',$version, false); // keep version, force save of db
	weaverx_setopt('wvrx_css_saved','', false);


	weaverx_setopt('last_option','Weaver Xtreme');
	weaverx_save_opts('loading theme');	// OK, now we've saved the options, update them in the DB
	
	return true;
}

?>
