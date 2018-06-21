<?php
if ( !defined('ABSPATH')) exit; // Exit if accessed directly


function weaverx_form_textarea($value,$media = false) {
	$twide =  ($value['type'] == 'text') ? '60' : '140';
	$rows = ( isset($value['val'] ) ) ? $value['val'] : 1;
	$place = ( isset($value['placeholder'] ) ) ? $value['placeholder'] : ' ';
	if ( $rows < 1 )
		$rows = 1;
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td colspan=2>
		<?php weaverx_textarea(weaverx_getopt($value['id']), $value['id'], $rows , $place, 'width:350px;', $class='wvrx-edit'); ?>
<?php
	if ($media) {
	weaverx_media_lib_button($value['id']);
	}
?>
&nbsp;<small><?php echo $value['info']; ?></small>
	</td>

	</tr>
<?php
}

function weaverx_form_text($value,$media=false) {
	$twide =  ($value['type'] == 'text') ? '60' : '160';
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td>
		<input name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text" style="width:<?php echo $twide;?>px;" class="regular-text" value="<?php echo esc_textarea(weaverx_getopt( $value['id'] )); ?>" />
<?php
	if ($media) {
	   weaverx_media_lib_button($value['id']);
	}
?>
	</td>
<?php	weaverx_form_info($value);
?>
	</tr>
<?php
}

function weaverx_form_val($value, $unit = '') {
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td>
		<input name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>" type="text" style="width:50px;" class="regular-text" value="<?php echo esc_textarea(weaverx_getopt( $value['id'] )); ?>" /> <?php echo $unit; ?>
	</td>
<?php	weaverx_form_info($value);
?>
	</tr>
<?php
}

function weaverx_form_text_xy($value,$x='X', $y='Y', $units='px') {
	$xid = $value['id'] . '_' . $x;
	$yid = $value['id'] . '_' . $y;
	$colon = ($value['name']) ? ':' : '';
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); echo $colon;?>&nbsp;</th>
	<td>
		<?php echo '<span class="rtl-break">' . $x;?>:<input name="<?php weaverx_sapi_main_name($xid); ?>" id="<?php echo $xid; ?>" type="text" style="width:40px;" class="regular-text" value="<?php weaverx_esc_textarea(weaverx_getopt( $xid )); ?>" /> <?php echo $units; ?></span>
		&nbsp;<?php echo '<span class="rtl-break">' . $y;?>:<input name="<?php weaverx_sapi_main_name($yid); ?>" id="<?php echo $yid; ?>" type="text" style="width:40px;" class="regular-text" value="<?php weaverx_esc_textarea(weaverx_getopt( $yid )); ?>" /> <?php echo $units; ?></span>
	</td>
<?php	weaverx_form_info($value);
?>
	</tr>
<?php
}

function weaverx_form_checkbox($value) {
?>
	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td>
	<input type="checkbox" name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>"
<?php 		checked(weaverx_getopt_checked( $value['id'] )); ?> >
	</td>
<?php 	weaverx_form_info($value);
?>
	</tr>
<?php
}

function weaverx_form_radio( $value ) {
?>

	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td colspan="2">

	<?php
	$cur_val = weaverx_getopt_default( $value['id'], 'black' );
	foreach ($value['value'] as $option) {
		$desc = $option['val'];
		if ( $desc == 'none' ) {
			$desc = "None";
		} else {
			$icon = weaverx_relative_url('assets/css/icons/search-' . $desc . '.png');
			$desc = '<img style="background-color:#ccc;height:24px; width:24px;" src="' . $icon . '" />';
		}
	?>
		<input type="radio" name="<?php weaverx_sapi_main_name($value['id']); ?>" value="<?php echo $option['val']; ?>"
		<?php checked($cur_val,$option['val']); ?> > <?php echo $desc; ?>&nbsp;
	<?php } ?>
	<?php echo '<br /><small style="margin-left:5%;">' . $value['info'] . '</small>'; ?>
	</td>
	</tr>
<?php
}


function weaverx_form_select_id( $value, $show_row = true ) {
	if ( $show_row ) { ?>

	<tr>
	<th scope="row" align="right"><?php weaverx_echo_name($value); ?>:&nbsp;</th>
	<td>
	<?php } ?>

	<select name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>">
	<?php
	foreach ($value['value'] as $option) {
	?>
		<option value="<?php echo $option['val'] ?>" <?php selected( (weaverx_getopt( $value['id'] ) == $option['val']));?>><?php echo $option['desc']; ?></option>
	<?php } ?>
	</select>
	<?php if ( $show_row ) { ?>
	</td>
	<?php weaverx_form_info($value); ?>
	</tr>
	<?php }
}

function weaverx_form_select_alt_theme($value) {

	$themes = weaverx_pp_get_alt_themes();
	$list = array();
	$list[] = array( 'val' => '', 'desc' => '');
	foreach ( $themes as $subtheme ) {
		$list[] = array( 'val' => $subtheme, 'desc' => $subtheme);
	}


	$value['value'] = $list;
	weaverx_form_select_id($value);
}

function weaverx_form_select_layout($value) {
	$list = array(array('val' => 'default', 'desc' => __('Use Default', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'right', 'desc' => __('Sidebars on Right', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'right-top', 'desc' => __('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'left', 'desc' => __('Sidebars on Left', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'left-top', 'desc' => __(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'split', 'desc' => __('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'split-top', 'desc' => __('Split (stack top)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'one-column', 'desc' => __('No sidebars, content only', 'weaver-xtreme' /*adm*/) )
	);


	$value['value'] = $list;
	weaverx_form_select_id($value);
}


function weaverx_form_link($value) {
	$id = $value['id'];

	$link = array ('name' =>  $value['name'] , 'id' => $id.'_color', 'type' => 'ctext', 'info' => $value['info']);
	$hover = array ('name' => '<small>' . __('Hover', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id.'_hover_color', 'type' => 'ctext', 'info' => __('Hover Color', 'weaver-xtreme' /*adm*/));

	weaverx_form_ctext($link);
	$id_strong = $id . '_strong';
	$id_em = $id . '_em';
	$id_u = $id . '_u';
	$id_uh = $id. '_u_h';
?>
	<tr><td><small style="float:right;"><?php _e('Link Attributes:', 'weaver-xtreme' /*adm*/); ?></small></td><td colspan="2">

	<small style="margin-left:5em;"><strong><?php _e('Bold', 'weaver-xtreme' /*adm*/); ?></strong></small>

<?php weaverx_form_font_bold_italic(array('id' => $id_strong)); ?>

	&nbsp;<small><em><?php _e('Italic', 'weaver-xtreme' /*adm*/); ?></em></small>
<?php weaverx_form_font_bold_italic(array('id' => $id_em)); ?>

	&nbsp;<small><u><?php _e('Link Underline', 'weaver-xtreme' /*adm*/); ?></u></small>
	<input type="checkbox" name="<?php weaverx_sapi_main_name($id_u); ?>" id="<?php echo $id_u; ?>"
<?php checked(weaverx_getopt_checked( $id_u )); ?> >

&nbsp;|&nbsp;&nbsp;<small><u><?php _e('Hover Underline', 'weaver-xtreme' /*adm*/); ?></u></small>
	<input type="checkbox" name="<?php weaverx_sapi_main_name($id_uh); ?>" id="<?php echo $id_uh; ?>"
<?php checked(weaverx_getopt_checked( $id_uh )); ?> >

<?php
	weaverx_form_ctext($hover, true);
?>

<?php
	echo '</td></tr>';
}


function weaverx_form_break($value) {
	$lim = isset( $value['value'] ) ? $value['value'] : 1 ;
	$label = isset( $value['name'] ) ? "<em style='color:blue;'><strong>{$value['name']}</strong></em>" : '&nbsp;' ;
	for ( $n = 1 ; $n <= $lim ; ++$n ) {
		echo "<tr><td style='text-align:right;'>{$label}</td></tr>";
		$label = '&nbsp;';
	}
}

function weaverx_form_note($value) {
?>
	<tr>
	<th scope="row" align="right">&nbsp;</th>
		<td style="float:right;font-weight:bold;"><?php weaverx_echo_name($value); ?>&nbsp;
<?php
	weaverx_form_help($value);
?>
		</td>
<?php
	weaverx_form_info($value);
?>
	</tr>
<?php
}


function weaverx_form_info($value) {
	if ($value['info'] != '') {
	echo('<td style="padding-left: 10px"><small>'); echo $value['info']; echo("</small></td>");
	}
}


function weaverx_form_widget_area( $value, $submit = false ) {
	/* build the rows for area settings
	 * Defined Areas:
	 *  'container' => '0', 'header' => '0', 'header_html' => '0', 'header_sb' => '0',
		'infobar' => '5px', 'content' => 'T:4px, B:8px', 'post' => '0', 'footer' => '0',
		'footer_sb' => '0', 'footer_html' => '0', 'widget' => '0', 'primary' => '0',
		'secondary' => '0', 'extra' => '0', 'top' => '0', 'bottom' => '0', 'wrapper' => '0'
	 */

	// defaults - these are determined by the =Padding section of style-weaverx.css
	$default_tb = array(
		'infobar' => '5px', 'content' => 'T:4px, B:8px', 'footer' => '8px',
		'footer_sb' => '8px', 'primary' => '8px',
		'secondary' => '8px', 'extra' => '8px', 'top' => '8px', 'bottom' => '8px'
	);

	$default_lr = array(
		'infobar' => '5px', 'content' => '2%', 'post' => '0', 'footer' => '8px',
		'footer_sb' => '8px', 'primary' => '8px',
		'secondary' => '8px', 'extra' => '8px', 'top' => '8px', 'bottom' => '8px'
	);

	$default_margins = array(
		'infobar' => '5px', 'content' => 'T:0, B:0', 'footer' => 'T:0, B:0',
		'footer_sb' => 'T:0, B:10',  'primary' => 'T:0, B:10', 'widget' => '0, Auto - First: T:0, Last: B:0',
		'secondary' => 'T:0, B:10', 'extra' => 'T:0, B:10', 'top' => 'T:10, B:10', 'bottom' => 'T:10, B:10',
		'wrapper' => 'T:0, B:0', 'post' => 'T:0, B:15',
	);

	$id = $value['id'];

	$def_tb = '0'; $def_lr = '0' ; $def_marg = '0';
	if ( isset( $default_tb[$id] ) ) $def_tb = $default_tb[$id];
	if ( isset( $default_lr[$id] ) ) $def_lr = $default_lr[$id];
	if ( isset( $default_margins[$id] ) ) $def_marg = $default_margins[$id];

	$use_percent = array('content', 'post');

	//echo '<table><tr><td>';
	$name = $value['name'];


	$lr_type = ( in_array($id, $use_percent) ) ? 'text_lr_percent' : 'text_lr';


	$opts = array (

		array( 'name' => $name,  'id' => '-welcome-widgets-menus', 'type' => 'header_area',
			  'info' => $value['info']),

		array(  'name' => $name, 'id' => $id, 'type' => 'titles_area',
			'info' => $name ),

		array(  'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . __('Padding', 'weaver-xtreme' /*adm*/) ,
			'id' => $id . '_padding', 'type' => 'text_tb',
			'info' => '<em>' . $name . '</em>' . __(': Top/Bottom Inner padding [Default: ', 'weaver-xtreme') . $def_tb . ']' ),

		array(  'name' => '', 'id' => $id . '_padding', 'type' => $lr_type,
			'info' => '<em>' . $name . '</em>' . __(': Left/Right Inner padding [Default: ', 'weaver-xtreme') . $def_lr . ']' ),

		array(  'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . __('Top/Bottom Margins', 'weaver-xtreme'),
			'id' => $id . '_margin', 'type' => 'text_tb',
			'info' => '<em>' . $name . '</em>' . __(': Top/Bottom margins. <em>Side margins auto-generated.</em> [Default: ', 'weaver-xtreme') . $def_marg . ']' )

	);

	weaverx_form_show_options($opts, false, false);


	$no_lr_margins = array(     // areas that can't allow left-right margin or width specifications
		'primary', 'secondary', 'content', 'post', 'widget'
	);
	$no_widgets = array(        // areas that don't have widgets
		'widget', 'content', 'post', 'wrapper', 'container', 'header', 'header_html', 'footer_html', 'footer', 'infobar'
	);

	$no_hide = array(
	   'wrapper', 'container', 'content','widget', 'post'
	);

	$default_auto = array(
		'top', 'bottom', 'footer_sb', 'header_sb'
	);


	if ( in_array( $id, $no_lr_margins )) {
		if ( $id != 'widget') {
			weaverx_form_checkbox(array(
				'name' => '<span class="i-left dashicons dashicons-align-none"></span>' . __('Add Side Margin(s)', 'weaver-xtreme' /*adm*/),
				'id' => $id . '_smartmargin',
				'type' => '',
				'info' => '<em>' . $name . '</em>' .
				__(': Automatically add left/right "smart" margins for separation of areas (sidebar/content).', 'weaver-xtreme' /*adm*/) ));
		}

		weaverx_form_note(array('name' => '<strong>' . __('Width', 'weaver-xtreme' /*adm*/) . '</strong>',
			'info' => __('The width of this area is automatically determined by the enclosing area', 'weaver-xtreme' /*adm*/)));
	} else if ( $id != 'wrapper' ) {

		if ( in_array($id, $default_auto)) {
			weaverx_form_val( array(
				'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . __('Width', 'weaver-xtreme' /*adm*/),
				'id' => $id . '_width_int', 'type' => '',
				'info' => '<em>' . $name . '</em>' . __(': Width of Area in % of enclosing area on desktop and small tablet. Hint: use with Center align. Use 0 to force auto width. (Default if blank: auto)', 'weaver-xtreme' /*adm*/),
				'value' => array() ), '%' );
		} else {
			weaverx_form_val( array(
				'name' => '<span class="i-left" style="font-size:150%;">&harr;</span> ' . __('Width', 'weaver-xtreme' /*adm*/),
				'id' => $id . '_width_int', 'type' => '',
				'info' => '<em>' . $name . '</em>' . __(': Width of Area in % of enclosing area on desktop and small tablet. Hint: use with Center align. Use 0 to force auto width. (Default if blank: 100%)', 'weaver-xtreme' /*adm*/),
				'value' => array() ), '%' );

		}

		weaverx_form_align(array(
			'name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('Align Area', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_align',
			'type' => '',
			'info' => '<em>' . $name . '</em>' . __(': How to align this area (Default: Left Align)', 'weaver-xtreme' /*adm*/) )

		);

		if ($id == 'header_html' || $id == 'footer_html') {
			weaverx_form_checkbox(array(
				'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Center Content', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => $id . '_center_content',
				'type' => '',
				'info' => '<em>' . $name . '</em>' .
				__(': Center Content within HTML Area content within the area.', 'weaver-xtreme' /*adm*/) ));
		}

	}


	if ( $id == 'wrapper' ) {       // setting #wrapper sets theme width.

		$info = __('<em>Change Theme Width.</em> Standard width is 1100px. Use the options on the "Full Width" tab for full width designs, but leave this value set. Widths less than 768px may give unexpected results on mobile devices. Weaver Xtreme can not create a fixed-width site.', 'weaver-xtreme' /*adm*/);

		weaverx_form_val( array(
			'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><em style="color:red;">' . __('Theme Width', 'weaver-xtreme' /*adm*/) . '</em>',
			'id' => 'theme_width_int', 'type' => '',
			'info' => $info,
			'value' => array() ), 'px' );
	}

	if ( in_array( $id, array( 'container', 'header', 'footer') ) ) {
		$opts_max = array(
		   array(
			'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Max Width', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_max_width_int', 'type' => '+val_px',
			'info' => '<em>' . $name . '</em>' . __(': Set Max Width of Area for Desktop View. Advanced Option. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
			'value' => array() ),
		);

		weaverx_form_show_options($opts_max, false, false);
	}


	if ( ! in_array( $id, $no_widgets) ) {

		$opts02 = array(
			array('name' => '<span class="i-left" style="font-size:120%;">&nbsp;&#9783;</span>' . __('Columns', 'weaver-xtreme' /*adm*/),
				'id' => $id . '_cols_int', 'type' => 'val_num',
				'info' => '<em>' . $name . '</em>' . __(': Equal width columns of widgets (Default: 1; max: 8)', 'weaver-xtreme' /*adm*/) ),

			array('name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('No Smart Widget Margins', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => $id . '_no_widget_margins', 'type' => 'checkbox',
				'info' => '<em>' . $name . '</em>' . __(': Do not use "smart margins" between widgets on rows.', 'weaver-xtreme' /*adm*/) ),

			array('name' => '<span class="i-left" style="font-size:140%;">&nbsp;=</span><small>' . __('Equal Height Widget Rows', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => $id . '_eq_widgets', 'type' => '+checkbox',
				'info' => '<em>' . $name . '</em>' . __(': Make widgets equal height rows if &gt; 1 column (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

		);

		weaverx_form_show_options($opts02, false, false);


		$custom_widths = array( 'header_sb', 'footer_sb', 'primary', 'secondary', 'top', 'bottom');
		if ( in_array( $id, $custom_widths) ) { /* if ( $id == 'header_sb' || $id == 'footer_sb' ) { */ ?>
	<tr><th scope="row" align="right"><span class="i-left" style="font-size:120%;">&nbsp;&#9783;</span><small><?php _e('Custom Widget Widths:', 'weaver-xtreme' /*adm*/); ?></small></th><td colspan="2" style="padding-left:20px;">
		<small><?php _e('You can optionally specify widget widths, including for specific devices. Please read the help entry!', 'weaver-xtreme' /*adm*/); ?>
		<?php weaverx_help_link('help.html#CustomWidgetWidth',__('Help on Custom Widget Widths', 'weaver-xtreme' /*adm*/)); ?>
		<?php _e('(&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/); ?></small></td>
	</tr>
		 <?php
		 $opts2 = array(
			array('name' => '<span class="i-left dashicons dashicons-desktop"></span><small>' . __('Desktop', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => '_' . $id . '_lw_cols_list', 'type' => '+textarea',
				'placeholder' => __('25,25,50; 60,40; - for example', 'weaver-xtreme' /*adm*/),
				'info' => __('List of widths separated by comma. Use semi-colon (;) for end of each row.  (&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/)),
			array('name' => '<span class="i-left dashicons dashicons-tablet"></span><small>' . __('Small Tablet', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => '_' . $id . '_mw_cols_list', 'type' => '+textarea',
				'info' => __('List of widget widths. (&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/)),
			array('name' => '<span class="i-left dashicons dashicons-smartphone"></span><small>' . __('Phone', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => '_' . $id . '_sw_cols_list', 'type' => '+textarea',
				'info' => __('List of widget widths. (&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/)),
		);

		weaverx_form_show_options($opts2, false, false);
		}
	}

	$opts3 = array (
		array( 'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . __('Add Border', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id . '_border', 'type' => 'checkbox',
			'info' => '<em>' . $name . '</em>' . __(': Add the "standard" border (as set on Custom tab)', 'weaver-xtreme' /*adm*/)),
		array( 'name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . __('Shadow', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id .'_shadow', 'type' => 'shadows',
			'info' => '<em>' . $name . '</em>' . __(': Wrap Area with Shadow.', 'weaver-xtreme' /*adm*/)),
		array( 'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . __('Rounded Corners', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id .'_rounded', 'type' => 'rounded',
			'info' => '<em>' . $name . '</em>' . __(': Rounded corners. Needs bg color or borders to show. <em>You might need to set overlapping corners for parent/child areas also!</em>', 'weaver-xtreme' /*adm*/) )
	);



	weaverx_form_show_options($opts3, false, false);

	if ( ! in_array( $id, $no_hide) ) {
		weaverx_form_select_hide(array(
			'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_hide',
			'info' => '<em>' . $name . '</em>' . __(': Hide area on different display devices', 'weaver-xtreme' /*adm*/),
			'value' => '' ) );
	}

	// class names
	$opts4 = array (
		array( 'name' => '<span class="i-left">{ }</span> <small>' . __('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => $id . '_add_class',  'type' => '+widetext',
			'info' => '<em>' . $name . '</em>' . __(': Space separated class names to add to this area (<em>Advanced option</em>) (&starf;Plus)', 'weaver-xtreme' /*adm*/)
		)
	);

	weaverx_form_show_options($opts4, false, false);

	if ( $submit )
		weaverx_form_submit('');
	//echo '</td></tr></table>';

}





function weaverx_form_menu_opts( $value, $submit = false ) {
	// build the rows for area
	$wp_logo = weaverx_get_wp_custom_logo_url();


	if ($wp_logo)
		$wp_logo_html = "<img src='{$wp_logo}' style='max-height:16px;margin-left:10px;' />";
	else
		$wp_logo_html = __('Not set', 'weaver-xtreme');

	//echo '<table><tr><td>';
	$name = $value['name'];
	$id = $value['id'];



	$opts = array (
		array( 'name' => $name,  'id' => '-menu', 'type' => 'header_area',
			  'info' => $value['info']),
		array( 'name' => __('Menu Bar Layout', 'weaver-xtreme'), 'type' => 'break'),

		array ('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span>' . __('Align Menu', 'weaver-xtreme' /*adm*/),
			'id' => $id . '_align', 'type' => 'select_id',
			'info' => __('Align this menu on desktop view. Mobile, accordion, and vertical menus always left aligned.', 'weaver-xtreme' /*adm*/),
			'value' => array(
				array('val' => 'left', 'desc' => 'Left'),
				array('val' => 'center', 'desc' => 'Center'),
				array('val' => 'right', 'desc' => 'Right'),
				array('val' => 'alignwide', 'desc' => __('Align Wide', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignwide left', 'desc' => __('Align Wide, Items Left', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignwide center', 'desc' => __('Align Wide, Items Center', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignwide right', 'desc' => __('Align Wide, Items Right', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignfull', 'desc' => __('Align Full', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignfull left', 'desc' => __('Align Full, Items Left', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignfull center', 'desc' => __('Align Full, Items Center', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'alignfull right', 'desc' => __('Align Full, Items Right', 'weaver-xtreme' /*adm*/) )
		)),

		array( 'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Menu', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_hide', 'type' => 'select_hide',
			'info' => '<em>' . $name . '</em>' . __(': Hide menu on different display devices', 'weaver-xtreme' /*adm*/) ),

	);

	if ( $id != 'm_extra' ) {
		$opts[] = array( 'name' => '<span class="i-left dashicons dashicons-editor-kitchensink"></span>' . __('Fixed-Top Menu', 'weaver-xtreme' /*adm*/),
			'id' => $id . '_fixedtop', 'type' => 'fixedtop',
			'info' => '<em>' . $name . '</em>' . __(': Fix menu to top of page. Note: the "Fix to Top on Scroll" does not play well with other "Fixed-Top" areas. Use the <em>Expand/Extend BG Attributes</em> on the Full Width tab to make a full width menu.', 'weaver-xtreme' /*adm*/));

	}

	if ( $id == 'm_primary' ) {
		$opts[] = array(
		'name' => '<small>' . __('Move Primary Menu to Top', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => $id . '_move', 'type' => 'checkbox',
		'info' => '<em>' . $name . '</em>' . __(': Move Primary Menu at Top of Header Area (Default: Bottom)', 'weaver-xtreme' /*adm*/),
		'value' => '' );


		$opts[] = array('name' => '<span class="i-left dashicons dashicons-heart"></span><small>' . __('Add Site Logo to Left', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'm_primary_logo_left', 'type' => 'checkbox',
		'info' => __('Add the Site Logo to the primary menu. Add custom CSS for <em>.custom-logo-on-menu</em> to style. (Use Customize &rarr; General Options &rarr; Site Identity to set Site Logo.) Logo: ', 'weaver-xtreme' /*adm*/) . $wp_logo_html);

		$opts[] = array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Height of Logo on Menu', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'm_primary_logo_height_dec', 'type' => 'val_em',
			'info' =>  __('Set height of Logo on Menu. Will interact with padding. (Default: 2.0em, the standard Menu Bar height.)', 'weaver-xtreme' /*adm*/) );

		$opts[] = array('name' => '<small>' . __('Logo Links to Home', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'm_primary_logo_home_link', 'type' => 'checkbox',
		'info' => __('Add a link to home page to logo on menu bar.', 'weaver-xtreme' /*adm*/));

		$opts[] = array('name' => '<small>' . __('Add Site Title to Left', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'm_primary_site_title_left', 'type' => 'checkbox',
		'info' => __('Add Site Title to primary menu left, with link to home page. (Uses Header Title font family, bold, and italic settings. Custom style with .site-title-on-menu.', 'weaver-xtreme' /*adm*/));

		$opts[] = array('name' => '<small>' . __("Add Search to Right", 'weaver-xtreme' /*adm*/) . '</small>',
					'id' => 'm_primary_search', 'type' => '+checkbox',
					'info' => __('Add slide open search icon to right end of primary menu. (&starf;Plus)', 'weaver-xtreme' /*adm*/) );

		$opts[] = array('name' => '<small>' . __('No Home Menu Item', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'menu_nohome', 'type' => 'checkbox',
		'info' => __('Don\'t automatically add Home menu item for home page (as defined in Settings->Reading)', 'weaver-xtreme' /*adm*/));


	} elseif ( $id == 'm_secondary' ) {
		$opts[] = array(
		'name' => '<small>' . __('Move Secondary Menu to Bottom', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => $id . '_move', 'type' => 'checkbox',
		'info' => '<em>' . $name . '</em>' . __(': Move Secondary Menu at Bottom of Header Area (Default: Top)', 'weaver-xtreme' /*adm*/),
		'value' => '' );
	}

	weaverx_form_show_options($opts, false, false);




	$opts = array(

		array( 'name' => __('Menu Bar Colors', 'weaver-xtreme'), 'type' => 'break','value' => 1),

		array( 'name' => __('Menu Bar', 'weaver-xtreme' /*adm*/),
			'id' => $id, 'type' => 'titles_menu',    // includes color, font size, font family
			'info' => __('Entire Menu Bar', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => __('Item BG', 'weaver-xtreme' /*adm*/),
			'id' => $id . '_link_bgcolor', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Background Color for Menu Bar Items (links)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => '<small>' . __('Dividers between menu items', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_dividers_color', 'type' => '+color',
			'info' => '<em>' . $name . '</em>' . __(': Add colored dividers between menu items. Leave blank for none. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => '<small>' . __('Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_hover_bgcolor', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Hover BG Color (Default: rgba(255,255,255,0.15))', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<small>' . __('Hover Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_hover_color', 'type' => 'color',
			'info' => '<em>' . $name . '</em>' . __(': Hover Text Color', 'weaver-xtreme' /*adm*/) ),


		array( 'name' => '<small>' . __('<em>Mobile</em> Open Submenu Arrow BG -<br /><em>Not used by SmarMenus</em>', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_clickable_bgcolor', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Clickable mobile open submenu arrow BG. Contrasting BG color required for proper user interface. <em>Not used by SmartMenus</em>. (Default: rgba(255,255,255,0.2))', 'weaver-xtreme' /*adm*/) ),



		array( 'name' => __('Submenu BG', 'weaver-xtreme' /*adm*/),
			'id' => $id . '_sub_bgcolor', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Background Color for submenus', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<small>' . __('Submenu Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_sub_color', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Text Color for submenus', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => '<small>' . __('Submenu Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_sub_hover_bgcolor', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Submenu Hover BG Color (Default: Inherit Top Level)', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<small>' . __('Submenu Hover Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_sub_hover_color', 'type' => 'color',
			'info' => '<em>' . $name . '</em>' . __(': Submenu Hover Text Color (Default: Inherit Top Level)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => __('Menu Bar Style', 'weaver-xtreme'), 'type' => 'break'),

		array( 'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . __('Add Border', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_border', 'type' => 'checkbox',
			'info' => '<em>' . $name . '</em>' . ': Add the "standard" border (as set on Custom tab)' ),

		array( 'name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . __('Add Border to Submenus', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_sub_border', 'type' => 'checkbox',
			'info' => '<em>' . $name . '</em>' . ': Add the "standard" border to Submenus' ),

		array( 'name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . __('Shadow', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_shadow', 'type' => 'shadows',
			'info' => '<em>' . $name . '</em>' . __(': Wrap Menu Bar with Shadow.', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . __('Rounded Corners', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_rounded', 'type' => 'rounded',
			'info' => '<em>' . $name . '</em>' . __(': Add rounded corners to menu. <em>You might need to set overlapping corners Header/Wrapper areas also!</em>', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<span class="i-left dashicons dashicons-marker"></span><small>' . __('Rounded Submenu Corners', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_sub_rounded', 'type' => 'checkbox',
			'info' => '<em>' . $name . '</em>' . ': Add rounded corners to Submenus' ),

	);

	weaverx_form_show_options($opts, false, false);



	if ( $id == 'm_primary' ) {
		$right_plus = '';
		$right_text = 'textarea';
		$right_hide = 'select_hide';
	} else {
		$right_plus = '(&starf;Plus)';
		$right_text = '+textarea';
		$right_hide = '+select_hide';
	}

	$opts2 = array(

		array( 'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Arrows', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_hide_arrows', 'type' => 'checkbox',
			'info' => '<em>' . $name . '</em>' . __(': Hide Arrows on Desktop Menu', 'weaver-xtreme' /*adm*/)),
		array( 'name' => '<span class="i-left">{ }</span> <small>' . __('Add Classes','weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_add_class', 'type' => '+widetext',
			'info' => '<em>' . $name . '</em>' . __(': Space separated class names to add to this area (<em>Advanced option</em>) (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => __('Menu Bar Spacing', 'weaver-xtreme'), 'type' => 'break'),

		array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Menu Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_top_margin_dec', 'type' => 'val_px',
			'info' => '<em>' . $name . '</em>' . __(': Top margin for menu bar.', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Menu Bottom Margin', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_bottom_margin_dec', 'type' => 'val_px',
			'info' => '<em>' . $name . '</em>' . __(': Bottom margin for menu bar.', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Desktop Item Vertical Padding', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_menu_pad_dec', 'type' => 'val_em',
			'info' => '<em>' . $name . '</em>' . __(': Add vertical padding to Desktop menu bar items and submenus. This option is NOT RECOMMENDED as it does not work with Left and Right HTML areas. (Default: 0.6em)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Desktop Menu Bar Padding', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_menu_bar_pad_dec', 'type' => 'val_em',
			'info' => '<em>' . $name . '</em>' . __(': Add padding to menu bar top and bottom for Desktop devices. (Default: 0 em)', 'weaver-xtreme' /*adm*/) ),



		array( 'name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Desktop Menu Spacing. (not on Smart Menus)', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_right_padding_dec' , 'type' => 'val_em',
			'info' => '<em>' . $name . '</em>' . __(': Add space between desktop menu bar items (Use value &gt; 1.0)', 'weaver-xtreme' /*adm*/) ),

		array( 'name' => __('Menu Bar Left/Right HTML', 'weaver-xtreme'), 'type' => 'break'),


		array('name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . __('Left HTML', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_html_left', 'type' => '+textarea',
			'placeholder' => __('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
			'info' => __('Add HTML Left (Works best with Centered Menu)(&starf;Plus)', 'weaver-xtreme' /*adm*/)),
		array( 'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_hide_left', 'type' => '+select_hide',
			'info' => '<em>' . $name . '</em>' . __(': Hide Left HTML', 'weaver-xtreme' /*adm*/) ),


		array('name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . __('Right HTML', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id . '_html_right', 'type' => $right_text,
			'placeholder' => __('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
			'info' => __('Add HTML to Menu on Right (Works best with Centered Menu)', 'weaver-xtreme' /*adm*/) . $right_plus),


		array( 'name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Area', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_hide_right', 'type' => $right_hide,
			'info' => '<em>' . $name . '</em>' . __(': Hide Right HTML', 'weaver-xtreme' /*adm*/) ),


		array( 'name' => '<small>' . __('HTML: Text Color', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_html_color', 'type' => 'ctext',
			'info' => '<em>' . $name . '</em>' . __(': Text Color for Left/Right Menu Bar HTML', 'weaver-xtreme' /*adm*/) ),
		array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('HTML: Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => $id .'_html_margin_dec', 'type' => 'val_em',
			'info' => '<em>' . $name . '</em>' . __(': Margin above Added Menu HTML (Used to adjust for Desktop menu. Negative values can help.)', 'weaver-xtreme' /*adm*/) )



	);

	weaverx_form_show_options($opts2, false, false);


	if ( $submit )
		weaverx_form_submit('');
}



function weaverx_form_text_props( $value, $type = 'titles') {
	// display text properties for an area or title

	$id = $value['id'];
	$name = $value['name'];
	$info = $value['info'];

	$id_colorbg = $id . '_bgcolor';

	$id_color = $id . '_color';
	$id_size = $id . '_font_size';
	$id_family = $id . '_font_family';
	$id_bold = $id . '_bold';
	$id_normal = $id . '_normal';
	$id_italic = $id . '_italic';

	// COLOR BG & COLOR BOX

	if ($id == 'wrapper') {
		echo '<tr><td></td><td colspan="2"><p>';
		_e('<strong>Important note:</strong> The Wrapper Area provides default
<em>background color, text color, and text font properties</em>
for most other areas, including Header, Container, Content, Widgets, and more.',
		   'weaver-xtreme' /*adm*/);
		echo "</p></td></tr>\n";
	}

	//echo "\n<!-- *************************** weaverx_form_text_props ID: {$id} ***************************** -->\n";

	weaverx_form_ctext( array(
		'name' => $name . ' BG',
		'id' => $id_colorbg,
		'info' => '<em>' . $info . __(':</em> Background Color (use CSS+ to specify custom CSS for area)', 'weaver-xtreme' /*adm*/)));


	if ( $type == 'menu' || $id == 'post_title' )
		weaverx_form_ctext( array(
			'name' =>  $name . ' ' . __('Text Color', 'weaver-xtreme' /*adm*/),
			'id' => $id_color,
			'info' => '<em>' . $info . __(':</em> Text properties', 'weaver-xtreme' /*adm*/)));
	else
		weaverx_form_color( array(
			'name' => $name . ' ' . __('Text Color', 'weaver-xtreme' /*adm*/),
			'id' => $id_color,
			'info' => '<em>' . $info . __(':</em> Text properties', 'weaver-xtreme' /*adm*/)));

	// FONT PROPERTIES
?>
	<tr>
	<th scope="row" align="right"><span class="i-left font-bold font-italic"><span style="font-size:16px;">a</span><span style="font-size:14px;">b</span><span style="font-size:12px;">c</span></span><small>
	<?php echo ($type == 'titles') ? __('Title', 'weaver-xtreme' /*adm*/) : __('Text', 'weaver-xtreme' /*adm*/);?>
	<?php _e('Font properties:', 'weaver-xtreme' /*adm*/); ?></small>&nbsp;</th>
	<td colspan="2">
		<?php
		if ( $type != 'content') {
			echo '&nbsp;<span class="rtl-break"><small><em>Size:</em></small>'; weaverx_form_select_font_size(array('id' => $id_size), false); echo '</span>';
		}
		echo '&nbsp;<span class="rtl-break"><small><em>Family:</em></small>'; weaverx_form_select_font_family(array('id' => $id_family), false); echo '</span>'; ?>

		<?php if ( $type == 'titles' ) { ?>
		&nbsp;<span class="rtl-break"><small><?php _e('Normal Weight', 'weaver-xtreme' /*adm*/); ?></small>
		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_normal); ?>" id="<?php echo $id_normal; ?>"
<?php checked(weaverx_getopt_checked( $id_normal )); ?> ></span>

		<?php } else { ?>
		&nbsp;<span class="rtl-break"><small><strong><?php _e('Bold', 'weaver-xtreme' /*adm*/); ?></strong></small>
<?php
	weaverx_form_font_bold_italic(array('id' => $id_bold));

/*		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_bold); ?>" id="<?php echo $id_bold; ?>"
<?php checked(weaverx_getopt_checked( $id_bold )); ?> >
*/
?>
		</span>
		<?php } ?>
		&nbsp;<span class="rtl-break">
		<small><em><?php _e('Italic', 'weaver-xtreme' /*adm*/); ?></em></small>
<?php
	weaverx_form_font_bold_italic(array('id' => $id_italic));
/*		<input type="checkbox" name="<?php weaverx_sapi_main_name($id_italic); ?>" id="<?php echo $id_italic; ?>"
/<?php checked(weaverx_getopt_checked( $id_italic )); ?> >
*/
?>
		</span>
<?php   if ( apply_filters('weaverx_xtra_type', '+plus_fonts' ) == 'inactive' )
			echo '<small>&nbsp;&nbsp; ' . __('(Add new fonts with <em>Weaver Xtreme Plus</em>)', 'weaver-xtreme' /*adm*/) . '</small>';
		else
			echo '<small>&nbsp;&nbsp; ' . __('(Add new fonts from Custom &amp; Fonts tab.)', 'weaver-xtreme' /*adm*/) . '</small>';?>
	</td>
	</tr>
<?php

}

function weaverx_from_fi_location( $value, $is_post = false ) {
	$value['value'] = array(
		array('val' => 'content-top', 'desc' => __('With Content - top', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'content-bottom', 'desc' => __('With Content - bottom', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'title-before', 'desc' => __('With Title', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'title-banner' , 'desc' =>  __('Banner above Title', 'weaver-xtreme') ),
		array('val' => 'header-image', 'desc' => $is_post ? __('Hide on Blog View', 'weaver-xtreme' /*adm*/) :
			  __('Header Image Replacement', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'post-before', 'desc' => __('Beside Page/Post, no wrap', 'weaver-xtreme' /*adm*/) ),

		array('val' => 'post-bg', 'desc' => __('As BG Image, Tile', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'post-bg-cover', 'desc' => __('As BG Image, Cover', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'post-bg-parallax', 'desc' => __('As BG Image, Parallax', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'post-bg-parallax-full', 'desc' => __('As BG Image, Parallax Full', 'weaver-xtreme' /*adm*/) ),
	);

	weaverx_form_select_id($value);
}


function weaverx_form_align( $value ) {
	$value['value'] = array(
		array('val' => 'float-left', 'desc' => __('Align Left', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'align-center', 'desc' => __('Center', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'float-right', 'desc' => __('Align Right', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'alignnone', 'desc' => __('No Alignment', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'alignwide', 'desc' => __('Align Wide', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'alignfull', 'desc' => __('Align Full', 'weaver-xtreme' /*adm*/) ),
	);

	weaverx_form_select_id($value);
}

function weaverx_form_fixedtop( $value ) {
	$value['value'] = array(
		array('val' => 'none', 'desc' => __('Standard Position : Not Fixed', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'fixed-top', 'desc' => __('Fixed to Top', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'scroll-fix', 'desc' => __('Fix to Top on Scroll', 'weaver-xtreme' /*adm*/) )
	);

	weaverx_form_select_id($value);
}

function weaverx_form_fi_align( $value ) {
	$value['value'] = array(
		array('val' => 'fi-alignleft', 'desc' => __('Align Left', 'weaver-xtreme' /*adm*/) ),
		 array('val' => 'fi-aligncenter', 'desc' => __('Center', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'fi-alignright', 'desc' => __('Align Right', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'fi-alignnone', 'desc' => __('No Align', 'weaver-xtreme' /*adm*/) )
	);

	weaverx_form_select_id($value);
}

function weaverx_form_select_hide($value) {
	$value['value'] = array(array('val' => 'hide-none', 'desc' => __('Do Not Hide', 'weaver-xtreme' /*adm*/) ),
		array('val' => 's-hide', 'desc' => __('Hide: Phones', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'm-hide', 'desc' => __('Hide: Small Tablets', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'm-hide s-hide', 'desc' => __('Hide: Phones+Tablets', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'l-hide', 'desc' => __('Hide: Desktop', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'l-hide m-hide', 'desc' => __('Hide: Desktop+Tablets', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'hide', 'desc' => __('Hide on All Devices', 'weaver-xtreme' /*adm*/) )
	);

	weaverx_form_select_id($value);
}

function weaverx_form_select_font_size( $value, $show_row = true ) {
	$value['value'] = array(array('val' => 'default', 'desc' => __('Inherit', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'm-font-size', 'desc' => __('Medium Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'xxs-font-size', 'desc' => __('XX-Small Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'xs-font-size', 'desc' => __('X-Small Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 's-font-size', 'desc' => __('Small Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'l-font-size', 'desc' => __('Large Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'xl-font-size', 'desc' => __('X-Large Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'xxl-font-size', 'desc' => __('XX-Large Font', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'customA-font-size', 'desc' => __('Custom Size A', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'customB-font-size', 'desc' => __('Custom Size B', 'weaver-xtreme' /*adm*/) )
	);
	$value['value'] = apply_filters('weaverx_add_font_size', $value['value']);
	weaverx_form_select_id( $value, $show_row);
}


function weaverx_form_select_font_family( $value, $show_row = true ) {
	$value['value'] = array(array('val' => 'default', 'desc' => __('Inherit', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'sans-serif', 'desc' => __('Arial (Sans Serif)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'arialBlack', 'desc' => __('Arial Black', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'arialNarrow', 'desc' => __('Arial Narrow', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'lucidaSans', 'desc' => __('Lucida Sans', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'trebuchetMS', 'desc' => __('Trebuchet MS', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'verdana', 'desc' => __('Verdana', 'weaver-xtreme' /*adm*/) ),

		array('val' => 'serif', 'desc' => __('Times (Serif)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'cambria', 'desc' => __('Cambria', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'garamond', 'desc' => __('Garamond', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'georgia', 'desc' => __('Georgia', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'lucidaBright', 'desc' => __('Lucida Bright', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'palatino', 'desc' => __('Palatino', 'weaver-xtreme' /*adm*/) ),

		array('val' => 'monospace', 'desc' => __('Courier (Monospace)', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'consolas', 'desc' => __('Consolas', 'weaver-xtreme' /*adm*/) ),

		array('val' => 'papyrus', 'desc' => __('Papyrus', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'comicSans', 'desc' => __('Comic Sans MS', 'weaver-xtreme' /*adm*/) )
	);
	$value['value'] = apply_filters('weaverx_add_font_family', $value['value']);
	?>
	<select name="<?php weaverx_sapi_main_name($value['id']); ?>" id="<?php echo $value['id']; ?>">
	<?php
	foreach ($value['value'] as $option) {
	?>
		<option class="font-<?php echo $option['val'];?>" value="<?php echo $option['val'] ?>"<?php selected( (weaverx_getopt( $value['id'] ) == $option['val']));?>><?php echo $option['desc']; ?></option>
	<?php } ?>
	</select>
<?php
}

function weaverx_form_rounded($value) {
	$value['value'] = array(array('val' => 'none', 'desc' => __('None', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-all', 'desc' => __('All Corners', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-left', 'desc' => __('Left Corners', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-right', 'desc' => __('Right Corners', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-top', 'desc' => __('Top Corners', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-bottom', 'desc' => __('Bottom Corners', 'weaver-xtreme' /*adm*/) ),
	);

	weaverx_form_select_id($value);
}

function weaverx_form_font_bold_italic($value) {
	$value['value'] = array(array('val' => '', 'desc' => __('Inherit', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'on', 'desc' => __('On', 'weaver-xtreme' /*adm*/) ),
		array('val' => 'off', 'desc' => __('Off', 'weaver-xtreme' /*adm*/) )
	);

	weaverx_form_select_id($value, false);
}

function weaverx_form_shadows($value) {
	$value['value'] = array(array('val' => '-0', 'desc' => __('No Shadow', 'weaver-xtreme' /*adm*/) ), // as in .shadow-0
		array('val' => '-1', 'desc' => __('All Sides, 1px', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-2', 'desc' => __('All Sides, 2px', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-3', 'desc' => __('All Sides, 3px', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-4', 'desc' => __('All Sides, 4px', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-rb', 'desc' => __('Right + Bottom', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-lb', 'desc' => __('Left + Bottom', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-tr', 'desc' => __('Top + Right', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-tl', 'desc' => __('Top + Left', 'weaver-xtreme' /*adm*/) ),
		array('val' => '-custom', 'desc' => __('Custom Shadow', 'weaver-xtreme' /*adm*/) )
	);
	$value['value'] = apply_filters('weaverx_add_shadows', $value['value']);

	weaverx_form_select_id($value);
}

// custom forms

function weaverx_custom_css( $value='' ) {

	$css = weaverx_getopt('add_css');

	if (isset($value['id']))
		$icon = $value['id'];
	if ( !isset($icon) || !$icon )
		$icon = ' ';

	$dash = '';
	if ( $icon[0] == '-' ) {                      // add a leading icon
		$dash = '<span style="padding:.2em;" class="dashicons dashicons-' . substr( $icon, 1) . '"></span>';
	}
?>
<tr class="atw-row-header"><td colspan="3">
<a id="custom-css-rules"></a>
	<span style="color:black;padding:.2em;" class="dashicons dashicons-screenoptions"></span>
	<span style="font-weight:bold; font-size: larger;"><em>
		<?php _e('Custom CSS Rules', 'weaver-xtreme' /*adm*/); ?> <?php weaverx_help_link('help.html#CustomCSS', __('Custom CSS Rules', 'weaver-xtreme' /*adm*/));?></em></span>
</td></tr>
<tr><td colspan="3">

	<!-- ======== -->
<p>
<?php _e('Rules you add here will be the <em>last</em> CSS Rules included by Weaver Xtreme, and thus override all other Weaver Xtreme generated CSS rules.
Specify complete CSS rules, but don\'t add the &lt;style&gt; HTML element. You can prefix your selectors with <code>.is-desktop, .is-mobile, .is-smalltablet, or .is-phone</code>
to create rules for specific devices.
<strong>NOTE:</strong> Because Weaver Xtreme uses classes on many of its elements, you may to need to use
<em>!important</em> with your rules to force the style override.
It is possible that other plugins might generate CSS that comes after these rules.', 'weaver-xtreme' /*adm*/); ?>
</p>
<?php weaverx_textarea(weaverx_getopt('add_css'), 'add_css', 12, '' , 'width:95%;', 'wvrx-edit wvrx-edit-dir'); ?>

</td></tr>
<?php
}


?>
