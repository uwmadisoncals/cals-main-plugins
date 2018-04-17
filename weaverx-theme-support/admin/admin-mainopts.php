<?php
if ( !defined('ABSPATH')) exit; // Exit if accessed directly
/* Weaver Xtreme - admin Main Options
 *
 *  __ added: 12/9/14
 * This function will start the main sapi form, which will be closed in admin-adminopts
 */

// ======================== Main Options > Top Level ========================
function weaverx_admin_mainopts() {
	if (!function_exists('weaverx_get_wp_custom_logo_url')) {
		weaverx_alert(__('    ****  WARNING!  ****\r\n\r\nYou are using a new Version 3 of the Weaver Xtreme Theme Support Plugin with an older version of the Weaver Xtreme Theme. Please update to the latest Version 3 of the Weaver Xtreme Theme.\r\n\r\nTHIS VERSION DOES NOT WORK WITH OLD VERSIONS OF WEAVER XTREME!'));
	}
?>
<div id="tabwrap_main" style="padding-left:4px;">

<div id="tab-container-main" class='yetiisub'>
	<ul id="tab-container-main-nav" class='yetiisub'>
	<?php
	weaverx_elink('#asp_genappear' , __('Wrapping background colors, rounded corners, borders, fade, shadow', 'weaver-xtreme' /*adm*/), __('Wrapping Areas', 'weaver-xtreme' /*adm*/),'<li>','</li>');
	weaverx_elink('#asp_widgets' , __('Settings for Sidebars and Sidebar Layout', 'weaver-xtreme' /*adm*/), __('Sidebars &amp; Layout', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_full' , __('Settings to create full width sites', 'weaver-xtreme' /*adm*/), __('Full Width', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_headeropts' , __('Site Title/Tagline properties, Header Image', 'weaver-xtreme' /*adm*/), __('Header', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_menus' , __('Menu text and bg colors and other properties; Info Bar properties', 'weaver-xtreme' /*adm*/), __('Menus','weaver-xtreme'  /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_content' , __('Text colors and bg, image borders, featured image, other properties related to all content', 'weaver-xtreme' /*adm*/), __('Content Areas', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_postspecific' , __('Properties related to posts: titles, meta info, navigation, excerpts, featured images, and more', 'weaver-xtreme' /*adm*/), __('Post Specifics', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_footer' , __('Footer options: bg color, borders, more. Site Copyright', 'weaver-xtreme' /*adm*/), __('Footer', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	weaverx_elink('#asp_custom' , __('Font settings &amp; Custom Settings', 'weaver-xtreme' /*adm*/), __('Fonts &amp; Custom', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	?>
	</ul>

	<?php weaverx_tab_title(__('Main Options', 'weaver-xtreme' /*adm*/), 'help.html#MainOptions', __('Help for Main Options', 'weaver-xtreme' /*adm*/)); ?>

	<div id="asp_genappear" class="tab_mainopt" >
		<?php weaverx_mainopts_general(); ?>
	</div>

	<div id="asp_widgets" class="tab_mainopt" >
		<?php
		weaverx_mainopts_layout();
		weaverx_mainopts_widgets();
		?>
	</div>

	<div id="asp_full" class="tab_mainopt" >
		<?php
		weaverx_mainopts_fullwidth();
	?>
	</div>

	<div id="asp_headeropts" class="tab_mainopt" >
	<?php weaverx_mainopts_header(); ?>
	</div>

	<div id="asp_menus" class="tab_mainopt" >
	<?php weaverx_mainopts_menus(); ?>
	</div>

	<div id="asp_content" class="tab_mainopt" >
	<?php weaverx_mainopts_content(); ?>
	</div>

	<div id="asp_postspecific" class="tab_mainopt" >
	<?php weaverx_mainopts_posts(); ?>
	</div>

	<div id="asp_footer" class="tab_mainopt" >
		<?php weaverx_mainopts_footer(); ?>
	</div>


	<div id="asp_links" class="tab_mainopt" >
	<?php weaverx_mainopts_custom(); ?>
	</div>

</div> <!-- #tab-container-main -->
<?php weaverx_sapi_submit(); ?>
</div>	<!-- #tabwrap_main -->
   <script type="text/javascript">
	var tabberMainOpts = new Yetii({
	id: 'tab-container-main',
	tabclass: 'tab_mainopt',
	persist: true
	});
</script>
<?php
}

// ======================== Main Options > Wrapping Areas ========================
function weaverx_mainopts_general() {

	$font_size = weaverx_getopt_default('site_fontsize_int', 16);

	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Wrapping Areas', 'weaver-xtreme' /*adm*/), 'id' => '-admin-generic', 'type' => 'header',
		'info' => __('Settings for wrapping areas','weaver-xtreme' /*adm*/),
		'help' => 'help.html#GenApp'),
	array('name' => __('GLOBAL SETTINGS', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		  'info' => __('These settings control site outer background and the standard link colors.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Outside BG', 'weaver-xtreme' /*adm*/), 'id' => 'body_bgcolor', 'type' => 'ctext',
		'info' => __('Background color that wraps entire page. (&lt;body&gt;) Using <em>Appearance->Background</em> will override this value, or allow a background image instead.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Fade Outside BG', 'weaver-xtreme' /*adm*/), 'id' => 'fadebody_bg', 'type' => 'checkbox',
		'info' => __('Will fade the Outside BG color, darker at top to lighter at bottom.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Full Browser Height', 'weaver-xtreme' /*adm*/), 'id' => 'full_browser_height', 'type' => 'checkbox',
		'info' => __('For short pages, add extra padding to bottom of content to force full browser height.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Standard Links', 'weaver-xtreme' /*adm*/), 'id' => 'link', 'type' => 'link',
		'info' => __('Global default for link colors (not including menus and titles). Set Bold, Italic, and Underline by setting those options for specific areas rather than globally to have more control.', 'weaver-xtreme' /*adm*/)),

	// array('name' => '#070' . __('No Auto-Underline Links', 'weaver-xtreme' /*adm*/), 'id' => 'mobile_nounderline', 'type' => 'checkbox',
	//	'info' => __('Underlined links are easier to use on most mobile devices. This will disable auto-underlined links.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Current Base Font Size:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => '<span style="font-size:' . $font_size . 'px;">' . $font_size . __('px.', 'weaver-xtreme' /*adm*/) . '</span> ' . __('Change on Custom Tab', 'weaver-xtreme' /*adm*/)),
	array( 'type' => 'submit'),


	array('name' => __('Wrapper Area', 'weaver-xtreme' /*adm*/), 'id' => 'wrapper', 'type' => 'widget_area_submit',
		'info' => __('Wrapper wraps entire site (CSS id: #wrapper). Colors and font settings will be the default values for all other areas.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Container Area', 'weaver-xtreme' /*adm*/), 'id' => 'container', 'type' => 'widget_area_submit',
		'info' => __('Container (#container div) wraps content and sidebars.', 'weaver-xtreme' /*adm*/)),

	);

?>

   <div class="options-intro"><?php _e('<strong>Wrapping Areas:</strong>
The options on this tab affect the overall site appearance.
The main <strong>Wrapper Area</strong> wraps the entire site, and is used to specify default text and background colors, site width, font families, and more.
With <em>Weaver Xtreme Plus</em>, you can also specify background images for various areas of your site.', 'weaver-xtreme' /*adm*/); ?>
<div class="options-intro-menu"> <a href="#wrapping-areas"><?php _e('Wrapping Areas', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#wrapper-area"><?php _e('Wrapper Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#container-area"><?php _e('Container Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#background-images"><?php _e('Background Image (X-Plus)', 'weaver-xtreme' /*adm*/); ?></a>
</div>
</div>
<?php
	weaverx_form_show_options($opts);
	do_action('weaverxplus_admin','general_appearance');
}

function wvrx_ts_new_xp_opt($vers, $opt) {
	// don't support new xp opts in old xp
	if ( function_exists('weaverxplus_plugin_installed') && version_compare( WEAVER_XPLUS_VERSION, $vers, '>=') )
		return $opt;
	return array('name' => $opt['name'], 'info' => __('This option requires X-Plus Version greater or equal to ','weaver-xtreme') . $vers , 'type' => 'note' );
}

// ======================== Main Options > Custom ========================

function weaverx_mainopts_custom() {
	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Custom Options', 'weaver-xtreme' /*adm*/), 'id' => '-admin-generic', 'type' => 'header',
		'info' => __('Set various global custom values.', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#Custom'),

	array('name' => __('Various Custom Values', 'weaver-xtreme' /*adm*/), 'id' => '-admin-settings', 'type' => 'subheader',
		'info' => __('Adjust various global settings', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-align-none"></span>' . __('Smart Margin Width', 'weaver-xtreme' /*adm*/),
		'id' => 'smart_margin_int', 'type' => '+val_percent',
		'info' => __('Width used for smart column margins for Sidebars and Content Area. (Default: 1%) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Border Color', 'weaver-xtreme' /*adm*/), 'id' => 'border_color', 'type' => 'color',
		'info' => __('Global color of borders. (Default: #222)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Border Width', 'weaver-xtreme' /*adm*/) . '</small>' , 'id' => 'border_width_int', 'type' => 'val_px',
		'info' => __('Global Width of borders. (Default: 1px)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . __('Border Style', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'border_style', 'type' => '+select_id',
		'info' => __('Style of borders - width needs to be > 1 for some styles to work correctly (&starf;Plus)', 'weaver-xtreme' /*adm*/),
			'value' => array(
				array('val' => 'solid', 'desc' => __('Solid', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'dotted', 'desc' => __('Dotted', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'dashed', 'desc' => __('Dashed', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'double', 'desc' => __('Double', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'groove', 'desc' => __('Groove', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'ridge', 'desc' => __('Ridge', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'inset', 'desc' => __('Inset', 'weaver-xtreme' /*adm*/) ),
				array('val' => 'outset', 'desc' => __('Outset', 'weaver-xtreme' /*adm*/) )
				)),

	array('name' => __('Corner Radius', 'weaver-xtreme' /*adm*/), 'id' => 'rounded_corners_radius', 'type' => '+val_px',
		'info' => __('Controls how "round" corners are. Specify a value (5 to 15 look best) for corner radius. (Default: 8) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Hide Menu/Link Tool Tips', 'weaver-xtreme' /*adm*/), 'id' => 'hide_tooltip', 'type' => '+checkbox',
		  'info' => __('Hide the tool tip pop up over all menus and links. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Custom Shadow', 'weaver-xtreme' /*adm*/), 'id' => 'custom_shadow', 'type' => '+widetext',
		'info' => __('Specify full <em>box-shadow</em> CSS rule, e.g., <em>{box-shadow: 0 0 3px 1px rgba(0,0,0,0.25);}</em> (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),

	array('name' => __('Custom CSS', 'weaver-xtreme' /*adm*/), 'id' => 'custom_css', 'type' => 'custom_css',
		'info' => __('Create Custom CSS Rules', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),


	array('name' => __('Fonts', 'weaver-xtreme' /*adm*/), 'id' => '-editor-textcolor', 'type' => 'header',
		'info' => __('Font Base Sizes', 'weaver-xtreme' /*adm*/),
		'help' => 'font-demo.html'
		),

	array('name' => __('Site Base Font Size', 'weaver-xtreme' /*adm*/), 'id' => 'site_fontsize_int', 'type' => 'val_px',
		'info' => __('Base font size of standard text. This value determines the default medium font size. Note that visitors can change their browser\'s font size, so final font size can vary, as expected. (Default: 16px)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Site Base Line Height', 'weaver-xtreme' /*adm*/), 'id' => 'site_line_height_dec', 'type' => '+val_num',
		'info' => __('Set the Base line-height. Most other line heights based on this multiplier. (Default: 1.5 - no units) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Site Base Font Size - Small Tablets', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'site_fontsize_tablet_int', 'type' => '+val_px',
		'info' => __('Small Tablet base font size of standard text. (Default medium font size: 16px) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Site Base Font Size - Phones', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'site_fontsize_phone_int', 'type' => '+val_px',
		'info' => __('Phone base font size of standard text. (Default medium font size: 16px)  (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Custom Font Size A', 'weaver-xtreme' /*adm*/), 'id' => 'custom_fontsize_a', 'type' => '+val_em',
		'info' => __('Specify font size in em for Custom Size A (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Custom Font Size B', 'weaver-xtreme' /*adm*/), 'id' => 'custom_fontsize_b', 'type' => '+val_em',
		'info' => __('Specify font size in em for Custom Size B (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Disable Google Font Integration', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'disable_google_fonts', 'type' => '+checkbox',
		  'info' => __('<strong>ADVANCED OPTION!</strong> <em>Be sure you understand the consequences of this option.</em> By disabling Google Font Integration, the Google Fonts definitions will <strong>not</strong> be loaded for your site. <strong style="color:red;font-weight:bold;">Please note:</strong> Any previously selected Google Font Families will revert to generic serif, sans, mono, and script fonts.', 'weaver-xtreme')),

	array( 'type' => 'submit')

	);
	?>
	<div class="options-intro"><strong><?php _e('Custom &amp; Fonts:', 'weaver-xtreme' /*adm*/); ?> </strong>
<?php _e('Set values for Custom options and Fonts: Smart Margin, Borders, Corners, Shadows, Custom CSS, and Fonts', 'weaver-xtreme' /*adm*/); ?>
<br />
	<div class="options-intro-menu">
<a href="#various-custom-values"><?php _e('Various Custom Values', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#custom-css-rules"><?php _e('Custom CSS Rules', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#fonts">Fonts</a>
	</div>
	</div>
<?php
	weaverx_form_show_options($opts);

	do_action('weaverxplus_admin','fonts');
}

// ======================== Main Options > Full Width ========================

function weaverx_mainopts_fullwidth() {

	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Full Width Site', 'weaver-xtreme' /*adm*/), 'id' => '-editor-justify', 'type' => 'header',
		'info' => __('Options to easily create full width site designs', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#FullWidth'),

	array('name' => __('Expand Areas', 'weaver-xtreme' /*adm*/), 'id' => '-editor-expand', 'type' => 'header_area',
		'info' => __('This section has options that let you expand selected content areas of your site to the full browser width. The content will be responsively displayed - and fully occupy the browser window.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-editor-expand"></span>' . __('Entire Site Full Width', 'weaver-xtreme' /*adm*/), 'id' => 'wrapper_fullwidth', 'type' => 'checkbox',
		'info' => __('Checking this option will display the <strong>ENTIRE SITE</strong> in the full width of the browser. This option overrides the <em>Theme Width</em> option on the <em>Wrapping Areas : Wrapper Area</em> menu.', 'weaver-xtreme' /*adm*/)),
	);

	$expand = array (
		'header' => array( __('Header Area Expand', 'weaver-xtreme'), __('Expand Header Area to full width. This will include all other Header Area sub-areas as well.','weaver-xtreme' )),
		'header-image' => array( __('Header Image Expand', 'weaver-xtreme'), __('Expand Header Image to full width.','weaver-xtreme' )),
		'site_title' => array( __('Site Title/Tagline Expand', 'weaver-xtreme'), __('This option includes the Site Title, Tagline, Search Button, and MiniMenu.','weaver-xtreme' )),
		'header-widget-area' => array( __('Header Widget Area Expand', 'weaver-xtreme'), __('Expand Header Widget Area to full width.','weaver-xtreme' )),
		'header-html' => array( __('Header HTML Area Expand', 'weaver-xtreme'), __('Expand Header HTML Area to full width.','weaver-xtreme' )),
		'm_primary' => array( __('Primary Menu Expand', 'weaver-xtreme'), __('Expand Primary Menu to full width.','weaver-xtreme' )),
		'm_secondary' => array( __('Secondary Menu Expand', 'weaver-xtreme'), __('Expand Secondary Menu to full width.','weaver-xtreme' )),
		'container' => array( __('Container Area Expand', 'weaver-xtreme'), __('Expand Container Area to full width.','weaver-xtreme' )),
		'infobar' => array( __('Info Bar Expand', 'weaver-xtreme'), __('Expand Info Bar to full width.','weaver-xtreme' )),
		'post' => array( __('Post Area Expand', 'weaver-xtreme'), __('Expand Info Bar to full width.','weaver-xtreme' )),
		'footer' => array( __('Footer Area Expand', 'weaver-xtreme'), __('Checking this option will automatically include the other Footer Area Expand options as well.','weaver-xtreme' )),
		'footer_sb' => array( __('Footer Widget Area Expand', 'weaver-xtreme'), __('Expand Footer Widget Area to full width.','weaver-xtreme' )),
		'footer_html' => array( __('Footer HTML Area Expand', 'weaver-xtreme'), __('Expand Footer HTML Area to full width.','weaver-xtreme' )),
		'site-ig-wrap' => array( __('Footer Copyright Area Expand', 'weaver-xtreme'), __('Expand Footer Copyright Area to full width.','weaver-xtreme' )),

	);

	foreach ($expand as $id => $vals) {
		$opts[] = array('name' => '<span class="i-left dashicons dashicons-editor-expand"></span>' . $vals[0], 'id' => 'expand_' . $id, 'type' => 'checkbox',
		'info' => $vals[1]);
	}



	$opts[] = array('name' => __('Extend BG Attributes', 'weaver-xtreme' /*adm*/), 'id' => '-editor-code', 'type' => 'header_area',
		'info' => __('The Extend BG Attributes options in this section allow you to retain the original content width, while extending its Background attributes to full width. These includes BG color, BG image, and borders, for example.', 'weaver-xtreme' /*adm*/));

	$extend = array (
		'container' => array( __('Container Area Extend BG', 'weaver-xtreme'), __('Extend Container Area BG Attributes to full width.','weaver-xtreme' )),
		'header' => array( __('Header Area Extend BG', 'weaver-xtreme'), __('Extend Header Area BG Attributes to full width.','weaver-xtreme' )),
		'header_sb' => array( __('Header Widget Area Extend BG', 'weaver-xtreme'), __('Extend Header Widget Area BG Attributes to full width.','weaver-xtreme' )),
		'header_html' => array( __('Header HTML Area Extend BG', 'weaver-xtreme'), __('Extend Header HTML Area BG Attributes to full width.','weaver-xtreme' )),
		'm_primary' => array( __('Primary Menu Extend BG', 'weaver-xtreme'), __('Extend Primary Menu BG Attributes to full width.','weaver-xtreme' )),
		'm_secondary' => array( __('Secondary Menu Extend BG', 'weaver-xtreme'), __('Extend Secondary Menu BG Attributes to full width.','weaver-xtreme' )),
		'infobar' => array( __('Info Bar Extend BG', 'weaver-xtreme'), __('Extend Info Bar BG Attributes to full width.','weaver-xtreme' )),
		//'content' => array( __('Content Area Extend BG', 'weaver-xtreme'), __('Extend Content Area BG Attributes to full width.','weaver-xtreme' )),
		'post' => array( __('Post Area Extend BG', 'weaver-xtreme'), __('Extend each Post Area BG Attributes to full width.','weaver-xtreme' )),
		'footer' => array( __('Footer Area Extend BG', 'weaver-xtreme'), __('Extend Footer Area BG Attributes to full width.','weaver-xtreme' )),
		'footer_sb' => array( __('Footer Widget Area Extend BG', 'weaver-xtreme'), __('Extend Footer Widget Area BG Attributes to full width.','weaver-xtreme' )),
		'footer_html' => array( __('Footer HTML Area Extend BG', 'weaver-xtreme'), __('Extend Footer HTML Area BG Attributes to full width.','weaver-xtreme' )),

	);

	foreach ($extend as $id => $vals) {
		$type = 'checkbox';
		if ($id == 'm_extra')
			$type = '+checkbox';
		$opts[] = array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . $vals[0], 'id' => $id . '_extend_width', 'type' => $type,
		'info' => $vals[1]);
	}




	$opts[] = array('name' => __('Extend BG Color', 'weaver-xtreme' /*adm*/), 'id' => '-admin-appearance', 'type' => 'header_area',
		'info' => __('These options, available with Weaver Xtreme Plus, allow you to stretch the BG color of various area to full width. This is different than the Extend BG Attributes in that only the color is extended, and that color can be different than the content. (&starf;Plus)', 'weaver-xtreme' /*adm*/));



$extend = array (

		'header' => array( __('Header Area Extend BG Color', 'weaver-xtreme'), __('Extend Header Area BG Color to full width.','weaver-xtreme' )),
		'm_primary' => array( __('Primary Menu Extend BG', 'weaver-xtreme'), __('Extend Primary Menu BG Color to full width.','weaver-xtreme' )),
		'm_secondary' => array( __('Secondary Menu Extend BG', 'weaver-xtreme'), __('Extend Secondary Menu BG Color to full width.','weaver-xtreme' )),
		'm_extra' => array( __('Extra Menu Extend BG', 'weaver-xtreme'), __('Extend Extra Menu BG Color to full width.','weaver-xtreme' )),
		'container' => array( __('Container Extend BG', 'weaver-xtreme'), __('Extend Container Area BG Color to full width.','weaver-xtreme' )),
		'content' => array( __('Content Extend BG', 'weaver-xtreme'), __('Extend Content Area BG Color to full width.','weaver-xtreme' )),
		'footer' => array( __('Footer Extend BG', 'weaver-xtreme'), __('Extend Footer Area BG Color to full width.','weaver-xtreme' )),
	);

	foreach ($extend as $id => $vals) {
		$opts[] = array('name' =>  $vals[0], 'id' => $id . '_extend_bgcolor', 'type' => '+color',
		'info' => $vals[1] . ' (&starf;Plus)');
	}


?>
<div class="options-intro">
<?php _e('<strong>Full Width:</strong> Options to create full width sites.', 'weaver-xtreme' /*adm*/); ?><p>
<?php _e('','weaver-xtreme'); ?>
</p></div>
<?php
	weaverx_form_show_options($opts);


}

// ======================== Main Options > Header ========================
function weaverx_mainopts_header() {

	$wp_logo = weaverx_get_wp_custom_logo_url();

	if ($wp_logo)
		$wp_logo_html = "<img src='{$wp_logo}' style='max-height:16px;margin-left:10px;' />";
	else
		$wp_logo_html = __('Not set', 'weaver-xtreme');


	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Header Options', 'weaver-xtreme' /*adm*/), 'id' => '-admin-generic', 'type' => 'header',
		'info' => __('Options affecting site Header', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#HeaderOpt'),


	array('name' => __('Header Area', 'weaver-xtreme' /*adm*/), 'id' => 'header', 'type' => 'widget_area',
		'info' => __('The Header Area includes: menu bars, standard header image, title, tagline, header widget area, header HTML area', 'weaver-xtreme' /*adm*/)),

array( 'name' => __('Header Other options', 'weaver-xtreme'), 'type' => 'break'),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Hide Search on Header', 'weaver-xtreme' /*adm*/),
		'id' => 'header_search_hide', 'type' => 'select_hide',
		'info' => __('Selectively hide the Search Box Button on top right of header', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Search Area Options:', 'weaver-xtreme' /*adm*/) . '</small>', 'type' => 'note',
		'info' => __('Specify search icon, text and background colors Search section of Content Areas tab.', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),

	array('name' => __('Header Image', 'weaver-xtreme' /*adm*/), 'id' => '-format-image', 'type' =>'subheader',
		  'info' => __('Settings related to standard header image (Set on Appearance&rarr;Header)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Hide Header Image', 'weaver-xtreme' /*adm*/),
		'id' => 'hide_header_image', 'type' => 'select_hide',
		'info' => __('Check to selectively hide standard header image', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Suggested Header Image Height', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_image_height_int', 'type' => 'val_px',
		'info' => __('Change the suggested height of the Header Image. This only affects the clipping window on the Appearance:Header page. Header images will be responsively sized. If used with <em>Header Image Rendering</em>, this value will be used to set the minimum height of the BG image. (Default: 188px)', 'weaver-xtreme' /*adm*/)),

		wvrx_ts_new_xp_opt( '3.0',		// >= 3.0
		array('name' => __('Header Image Rendering', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_image_render', 'type' => '+select_id',	//code
		'info' => __('How to render header image: as img in header or as header area bg image. When rendered as a BG image, other options such as moving Title/Tagline or having image link to home page are not meaningful. (Default: &lt;img&gt; in header div) (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'header-as-img', 'desc' => __('As img in header', 'weaver-xtreme' /*adm*/)),
			array('val' => 'header-as-bg', 'desc' => __('As static BG image', 'weaver-xtreme' /*adm*/)),
			array('val' => 'header-as-bg-responsive', 'desc' => __('As responsive BG image', 'weaver-xtreme' /*adm*/)),
			array('val' => 'header-as-bg-parallax', 'desc' => __('As parallax BG image', 'weaver-xtreme' /*adm*/))

			)) ),

	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . __('Maximum Image Width', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_image_max_width_dec', 'type' => '+val_percent',
		'info' => __('Maximum width of Image (Default: 100%) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Use Actual Image Size', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_actual_size', 'type' => '+checkbox',
		'info' => __('Check to use actual header image size. (Default: theme width) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('Align Header Image', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_image_align', 'type' => '+align',
		'info' => __('How to align header image - meaningful only when Max Width or Actual Size set. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Header Image Front Page', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_header_image_front', 'type' => 'checkbox',
		'info' => __('Check to hide display of standard header image on front page only.', 'weaver-xtreme' /*adm*/)),

	array( 'name' => '<span class="i-left">{ }</span> <small>' . __('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'header_image_add_class',  'type' => '+widetext',
			'info' => '<em>' . __('Header Image', 'weaver-xtreme' /*adm*/) . '</em>' . __(': Space separated class names to add to this area (<em>Advanced option</em>) (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

	array('name' => '<small>' . __('Header Image Links to Site', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'link_site_image', 'type' => 'checkbox',
		'info' => __('Check to add a link to site home page for Header Image. Note: If used with <em>Move Title/Tagline over Image</em>, parts of the header image will not be clickable.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Alternate Header Images:', 'weaver-xtreme' /*adm*/) . '</small>', 'type' => 'note',
		'info' => __('Specify alternate header images using the <em>Featured Image Location</em> options on the <em>Content Areas</em> tab for pages, or the <em>Post Specifics</em> tab for single post views.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . __('Image HTML Replacement', 'weaver-xtreme' /*adm*/),
		'id' => 'header_image_html_text', 'type' => 'textarea',
		'placeholder' => __('Any HTML, including shortcodes', 'weaver-xtreme' /*adm*/),
		'info' => __('Replace Header image with arbitrary HTML. Useful for slider shortcodes in place of image. FI as Header Image has priority over HTML replacement. Extreme Plus also supports this option on a Per Page/Post basis.', 'weaver-xtreme' /*adm*/), 'val' => 1 ),

	array('name' => '<small>' . __('Show On Home Page Only', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'header_image_html_home_only', 'type' => 'checkbox',
		'info' => __('Check to use the Image HTML Replacement only on your Front/Home page.', 'weaver-xtreme' /*adm*/)),

		wvrx_ts_new_xp_opt( '3.0', // >= 3.0
	array('name' => '<small>' . __('Also show BG Header Image', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'header_image_html_plus_bg', 'type' => '+checkbox',
		'info' => __('If you have Image HTML Replacement defined - including Per Page/Post - and also have have set the standard Header Image to display as a BG image, then show <em>both</em> the BG image and the replacement HTML. (&starf;Plus)', 'weaver-xtreme' /*adm*/)) ),



	array('name' => __('Header Video', 'weaver-xtreme' /*adm*/), 'id' => '-format-video', 'type' =>'subheader',
		  'info' => __('Settings related to Header Video (Set on Appearance&rarr;Header or on the Customize&rarr;Images&rarr;Header Media menu.)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Header Video Rendering', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_video_render', 'type' => 'select_id',	//code
		'info' => __('How to render Header Video: as image substitute in header or as full browser background cover image will parallax effect. <em style="color:red;">Note that the Header Image options above do not apply to the Header Video media.</em>', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'has-header-video', 'desc' => __('As video in header only', 'weaver-xtreme' /*adm*/)),
			array('val' => 'has-header-video-cover', 'desc' => __('As full cover Parallax BG Video', 'weaver-xtreme' /*adm*/)),
			array('val' => 'has-header-video-none', 'desc' => __('Disable Header Video', 'weaver-xtreme' /*adm*/))
		)),

		array('name' => __('Header Video Aspect Ratio', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_video_aspect', 'type' => 'select_id',	//code
		'info' => __('It is critical to select aspect ratio of your video. If you see letterboxing black bars, you have the wrong aspect ratio selected.' , 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => '16:9', 'desc' => __('16:9 HDTV', 'weaver-xtreme' /*adm*/)),
			array('val' => '4:3', 'desc' => __('4:3 Std TV', 'weaver-xtreme' /*adm*/)),
			array('val' => '3:2', 'desc' => __('3:2 35mm Photo', 'weaver-xtreme' /*adm*/)),
			array('val' => '5:3', 'desc' => __('5:3 Alternate Photo', 'weaver-xtreme' /*adm*/)),
			array('val' => '64:27', 'desc' => __('2.37:1 Cinemascope', 'weaver-xtreme' /*adm*/)),
			array('val' => '37:20', 'desc' => __('1.85:1 VistaVision', 'weaver-xtreme' /*adm*/)),
			array('val' => '3:1', 'desc' => __('3:1 Banner', 'weaver-xtreme' /*adm*/)),
			array('val' => '4:1', 'desc' => __('4:1 Banner', 'weaver-xtreme' /*adm*/)),
			array('val' => '9:16', 'desc' => __('9:16 Vertical HD (Please avoid!)', 'weaver-xtreme' /*adm*/))
		)),


	array('name' => __('Custom Logo', 'weaver-xtreme' /*adm*/), 'id' => '-menu', 'type' =>'subheader',
		'info' => __('The native WP Custom Logo, set on the Site Identity Customizer menu.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Replace Title with Site Logo', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'wplogo_for_title', 'type' => 'checkbox',
		'info' => __('Replace the Site Title text with the WP Custom Logo Image. Logo: ', 'weaver-xtreme' /*adm*/) . $wp_logo_html),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide WP Custom Logo', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_wp_site_logo', 'type' => 'select_hide',
		'info' => __('Hide native WP Custom Site Logo in Header, by device. (This is not the Weaver Logo/HTML!)', 'weaver-xtreme' /*adm*/)),

	array( 'name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Logo for Title Height', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'header_logo_height_dec', 'type' => 'val_px',
			'info' =>  __('Set maximum height of Logo when used to replace Site Title. Default 0 uses the actual image size. This is the maximum height. If the actual image height is smaller, the smaller value is used.', 'weaver-xtreme' /*adm*/) ),


	array( 'type' => 'submit'),


	array('name' => __('Site Title/Tagline', 'weaver-xtreme' /*adm*/), 'id' => '-text', 'type' =>'subheader',
		'info' => __('Settings related to the Site Title and Tagline (Tagline sometimes called Site Description)', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Site Title', 'weaver-xtreme' /*adm*/), 'id' => 'site_title', 'type' => 'titles',
		'info' => __("The site's main title in the header (blog title)", 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . __('Title Position', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'site_title_position_xy', 'type' => 'text_xy_percent',
		'info' => __('Adjust left and top margins for Title. Decimal and negative values allowed. (Default: X: 7%, Y:0.25%)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Title Max Width', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'site_title_max_w', 'type' => 'val_percent',
		'info' => __("Maximum width of title in header area (Default: 90%)", 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Site Title', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_site_title', 'type' => 'select_hide',
		'info' => __('Hide Site Title (Uses "display:none;" : SEO friendly.)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Move Title/Tagline over Image', 'weaver-xtreme' /*adm*/), 'id' => 'title_over_image', 'type' => 'checkbox',
		'info' => __('Move the Title, Tagline, Search, Logo/HTML and Mini-Menu over the Header Image. This can make a very attractive header,', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Site Tagline', 'weaver-xtreme' /*adm*/), 'id' => 'tagline', 'type' => 'titles',
		'info' => __("The site's tagline (blog description)", 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . __('Tagline Position', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'tagline_xy', 'type' => 'text_xy_percent',
		'info' => __('Adjust default left and top margins for Tagline. (Default: X: 10% Y:0%)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Tagline Max Width', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'tagline_max_w', 'type' => 'val_percent',
		'info' => __("Maximum width of Tagline in header area (Default: 90%)", 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Site Tagline', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_site_tagline', 'type' => 'select_hide',
		'info' => __('Hide Site Tagline (Uses "display:none;" : SEO friendly.)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Title/Tagline Area BG', 'weaver-xtreme' /*adm*/), 'id' => 'title_tagline_bgcolor', 'type' => 'ctext',
		'info' => __('BG Color for the Title, Tagline, Search, Logo/HTML and Mini-Menu area.', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left font-bold" style="font-size:120%;">&#x21cc;</span><small>' . __('Title/Tagline Padding', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'title_tagline_xy', 'type' => 'text_tb',
		'info' => __('Add Top/Bottom Padding to the Site Title/Tagline block. This option is especially useful if the Header Image is a BG image. (Default: 0,0)', 'weaver-xtreme' /*adm*/)),



	array('name' => '<span class="i-left dashicons dashicons-editor-code"></span><small>' . __('Weaver Site Logo/HTML', 'weaver-xtreme' /*adm*/) . '</small>',
				'id' => '_site_logo', 'type' => '+textarea',
				'info' => __('HTML for Site Title area. (example: &lt;img src="url" style="position:absolute;top:20px;left:20px;"&nbsp;/&gt; + Custom CSS: #site-logo{min-height:123px;} (This is not the WP Custom Logo!) (&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Site Logo/HTML', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => '_hide_site_logo', 'type' => '+select_hide',
		'info' => __('Hide Weaver Site Logo/HTML by device. (This is not the WP Custom Logo!) (&starf;Plus) (&diams;)', 'weaver-xtreme' /*adm*/)),

	array( 'name' => '<span class="i-left">{ }</span> <small>' . __('Add Classes', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'site_title_add_class',  'type' => '+widetext',
			'info' => '<em>' . __('Title/Tagline', 'weaver-xtreme' /*adm*/) . '</em>' . __(': Space separated class names to add to this area (<em>Advanced option</em>) (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),



	array( 'type' => 'submit'),


	array('name' => __('The Header Mini-Menu', 'weaver-xtreme' /*adm*/), 'id' => '-menu', 'type' =>'subheader',
		'info' => __('Horizontal "Mini-Menu" displayed right-aligned of Site Tagline', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Note:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => __('The Header Mini-Menu options are on the Menu Tab.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Header Widget Area', 'weaver-xtreme' /*adm*/), 'id' => 'header_sb', 'type' => 'widget_area',
		'info' => __('Horizontal Header Widget Area', 'weaver-xtreme' /*adm*/)),

	array( 'name' => __('Other Widget Area Options', 'weaver-xtreme'), 'type' => 'break'),

	array('name' => '<small>' . __('Header Widget Area Position', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'header_sb_position', 'type' => '+select_id',	//code
		'info' => __('Change where Header Widget Area is displayed. (Default: Top) (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'top', 'desc' => __('Top of Header', 'weaver-xtreme' /*adm*/)),
			array('val' => 'before_header', 'desc' => __('Before Header Image', 'weaver-xtreme' /*adm*/)),
			array('val' => 'after_header', 'desc' => __('After Header Image', 'weaver-xtreme' /*adm*/)),
			array('val' => 'after_html', 'desc' => __('After HTML Block', 'weaver-xtreme' /*adm*/)),
			array('val' => 'after_menu', 'desc' => __('After Lower Menu', 'weaver-xtreme' /*adm*/)),
			array('val' => 'pre_header', 'desc' => __('Pre-#header &lt;div&gt;', 'weaver-xtreme' /*adm*/)),
			array('val' => 'post_header', 'desc' => __('Post-#header &lt;div&gt;', 'weaver-xtreme' /*adm*/)),
			)),

	array('name' => '<span class="i-left dashicons dashicons-editor-kitchensink"></span>' . __('Fixed-Top Header Widget Area', 'weaver-xtreme' /*adm*/) ,
			'id' => 'header_sb_fixedtop', 'type' => 'checkbox',
			'info' => __('Fix the Header Widget Area to top of page. If primary/secondary menus also fixed-top, header widget area will always be after secondary and before primary. Use the <em>Expand/Extend BG Attributes</em> on the "Full Width" tab to make a full width Header Widget Area.', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),

	array('name' => __('Header HTML', 'weaver-xtreme' /*adm*/), 'id' => 'header_html', 'type' => 'widget_area', __('Header Widget Area', 'weaver-xtreme' /*adm*/),
		'info' => __('Add arbitrary HTML to Header Area (in &lt;div id="header-html"&gt;)', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . __('Header HTML content', 'weaver-xtreme' /*adm*/),
		'id' => 'header_html_text', 'type' => 'textarea',
		'placeholder' => __('Any HTML, including shortcodes', 'weaver-xtreme' /*adm*/),
		'info' => __('Add arbitrary HTML to Header Area (in &lt;div id="header-html"&gt;)', 'weaver-xtreme' /*adm*/), 'val' => 4 ),

	array( 'type' => 'submit'),

	array('name' => __('Note:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => __('There are more standard WordPress Header options available on the Dashboard Appearance->Header panel.', 'weaver-xtreme' /*adm*/)),
	);

?>
   <div class="options-intro">
<?php _e('<strong>Header:</strong> Options affecting the Header Area at the top of your site.', 'weaver-xtreme' /*adm*/); ?>
<br />
<div class="options-intro-menu"> <a href="#header-area"><?php _e('Header Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#header-image"><?php _e('Header Image', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#header-video"><?php _e('Header Video', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#site-title-tagline"><?php _e('Site Title/Tagline', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#header-widget-area"><?php _e('Header Widget Area', 'weaver-xtreme' /*adm*/); ?></a>|
<a href="#header-html"><?php _e('Header HTML', 'weaver-xtreme' /*adm*/); ?></a>
</div>
   </div>
<?php
	weaverx_form_show_options($opts);

	do_action('weaverxplus_admin','header_opts');
}

// ======================== Main Options > Menus ========================
function weaverx_mainopts_menus() {


	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Menu &amp; Info Bars', 'weaver-xtreme' /*adm*/), 'id' => '-menu', 'type' => 'header',
		'info' => __('Options affecting site Menus and the Info Bar', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#MenuBar'),


##### SmartMenu
		array('name' => '<span class="i-left dashicons dashicons-menu"></span>' . __('Use SmartMenus', 'weaver-xtreme' /*adm*/),
			  'id' => 'use_smartmenus', 'type' => '+checkbox',
			'info' => __('Use <em>SmartMenus</em> rather than default Weaver Xtreme Menus. <em>SmartMenus</em> provide enhanced menu support, including auto-visibility, and transition effects. Applies to all menus. Additional options for SmartMenus on the <em>Xtreme Plus:SmartMenus</em> tab. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

		array( 'name' =>  '<small>' . __('Menu Mobile/Desktop Switch Point', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'mobile_alt_switch', 'type' => '+val_px',
			'info' => __('<em>SmartMenus Only:</em> Set when menu bars switch from desktop to mobile. (Default: 767px. Hint: use 768 to force mobile menu on iPad portrait.) (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

		array('name' => __('Mega Menus:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => __('Weaver Xtreme Plus allows you to define Mega Menu style dropdown menu items with arbitrary HTML content. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Primary Menu Bar', 'weaver-xtreme' /*adm*/), 'id' => 'm_primary', 'type' => 'menu_opts',
		'info' => __('Attributes for the Primary Menu Bar (Default Location: Bottom of Header)', 'weaver-xtreme' /*adm*/)),


//	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small> ' . __('Mobile Menu Trigger', 'weaver-xtreme' /*adm*/). '</small>',
//		'id' => 'menu_primary_trigger_int', 'type' => 'val_px',
//		'info' => __('Set trigger width where Primary Menu changes to/from Mobile Menu (Default: 768px, value must be &lt; 768)', 'weaver-xtreme' /*adm*/)),


	array( 'type' => 'submit'),

	array('name' => __('Secondary Menu Bar', 'weaver-xtreme' /*adm*/), 'id' => 'm_secondary', 'type' => 'menu_opts',
		'info' => __('Attributes for the Secondary Menu Bar (Default Location: Top of Header)', 'weaver-xtreme' /*adm*/)),

//	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small> ' . __('Mobile Menu Trigger', 'weaver-xtreme' /*adm*/). '</small>',
//		'id' => 'menu_secondary_trigger_int', 'type' => 'val_px',
//		'info' => __('Set trigger width where Secondary Menu changes to/from Mobile Menu (Default: 768px, value must be &lt; 768)', 'weaver-xtreme' /*adm*/)),


	array( 'type' => 'submit'),


	array('name' => __('Options: All Menus', 'weaver-xtreme' /*adm*/), 'id' => '-forms', 'type' => 'subheader_alt',
		'info' => __('Menu Bar enhancements and features', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Current Page BG', 'weaver-xtreme' /*adm*/), 'id' => 'menubar_curpage_bgcolor', 'type' => 'ctext',
		'info' => __('BG Color for the currently displayed page and its ancestors.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Current Page Text', 'weaver-xtreme' /*adm*/), 'id' => 'menubar_curpage_color', 'type' => 'color',
		'info' => __('Color for the currently displayed page and its ancestors.', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-editor-bold"></span><small>' . __('Bold Current Page', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'menubar_curpage_bold', 'type' => 'checkbox',
		'info' => __('Bold Face Current Page and ancestors', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-editor-italic"></span><small>' . __('Italic Current Page', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'menubar_curpage_em', 'type' => 'checkbox',
		'info' => __('Italic Current Page and ancestors', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Do Not Highlight Ancestors', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'menubar_curpage_noancestors', 'type' => 'checkbox',
		'info' => __('Highlight Current Page only - do not also highlight ancestor items', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Retain Menu Bar Hover BG', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'm_retain_hover', 'type' => 'checkbox',
		'info' => __('Retain the menu bar hover BG color when sub-menus are opened.', 'weaver-xtreme' /*adm*/)),


	array('name' => '<small>' . __('Placeholder Hover Cursor', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'placeholder_cursor', 'type' => 'select_id',	//code
		'info' => __('CSS cursor :hover attribute for placeholder menus (e.g., Custom Menus with URL==#). (Default: pointer)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'pointer', 'desc' => __('Pointer (indicates link)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'context-menu', 'desc' => __('Context Menu available', 'weaver-xtreme' /*adm*/)),
			array('val' => 'text', 'desc' => __('Text', 'weaver-xtreme' /*adm*/)),
			array('val' => 'none', 'desc' => __('No pointer', 'weaver-xtreme' /*adm*/)),
			array('val' => 'not-allowed', 'desc' => __('Action not allowed', 'weaver-xtreme' /*adm*/)),
			array('val' => 'default', 'desc' => __('The default cursor', 'weaver-xtreme' /*adm*/))
			)),


	array( 'name' => '<small>' . __('Mobile Menu "Hamburger" Label', 'weaver-xtreme' /*adm*/) . '</small>',
			'id' => 'mobile_alt_label',  'type' => 'widetext',
		'info' => __('Alternative label for the default mobile "Hamburger" icon. HTML allowed: &lt;span&gt; or &lt;img&gt; suggested.', 'weaver-xtreme' /*adm*/)),


	array( 'type' => 'submit'),

	array('name' => __('Header Mini-Menu', 'weaver-xtreme' /*adm*/), 'id' => '-menu', 'type' =>'subheader_alt',
		'info' => __('Horizontal "Mini-Menu" displayed right-aligned of Site Tagline', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Mini-Menu', 'weaver-xtreme' /*adm*/), 'id' => 'm_header_mini', 'type' => 'titles_text',
		'info' => __('Color of Mini-Menu Link Items', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Mini Menu Hover', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'm_header_mini_hover_color', 'type' => 'ctext',
		'info' => __('Hover Color for Mini-Menu Links', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-align-none"></span><small>' . __('Mini Menu Top Margin', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'm_header_mini_top_margin_dec', 'type' => 'val_em',
		'info' => __('Top margin for Mini-Menu. Negative value moves it up. (Default: 0em)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Mini Menu', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'm_header_mini_hide', 'type' => 'select_hide',
		'info' => __('Hide Mini Menu', 'weaver-xtreme' /*adm*/)),


	array( 'type' => 'submit'),


	array('name' => __('Info Bar', 'weaver-xtreme' /*adm*/), 'id' => 'infobar', 'type' => 'widget_area',
		'info' => __('Info Bar : Breadcrumbs & Page Nav below primary menu', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Hide Breadcrumbs', 'weaver-xtreme' /*adm*/),
		'id' => 'info_hide_breadcrumbs', 'type' => 'checkbox',
		'info' => __('Do not display the Breadcrumbs', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Hide Page Navigation', 'weaver-xtreme' /*adm*/),
		'id' => 'info_hide_pagenav', 'type' => 'checkbox',
		'info' => __('Do not display the numbered Page navigation', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Show Search box', 'weaver-xtreme' /*adm*/),
		'id' => 'info_search', 'type' => 'checkbox',
		'info' => __('Include a Search box on the right', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Show Log In', 'weaver-xtreme' /*adm*/), 'id' => 'info_addlogin', 'type' => 'checkbox',
		'info' => __('Include a simple Log In link on the right', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Breadcrumb for Home', 'weaver-xtreme' /*adm*/), 'id' =>'info_home_label' , 'type' => 'widetext', //code - option done in code
		'info' => __('This lets you change the breadcrumb label for your home page. (Default: Home)', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Info Bar Links', 'weaver-xtreme' /*adm*/), 'id' => 'ibarlink', 'type' => 'link',
		'info' => __('Color for links in Info Bar (uses Standard Link colors if left blank)', 'weaver-xtreme' /*adm*/))
	);

?>
<div class="options-intro">
<?php _e('<strong>Menus:</strong> Options to control how your menus look.', 'weaver-xtreme' /*adm*/); ?><br />
<div class="options-intro-menu">
<a href="#primary-menu-bar"><?php _e('Primary Menu Bar', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#secondary-menu-bar"><?php _e('Secondary Menu Bar', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#options-all-menus"><?php _e('Options: All Menus', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#header-mini-menu"><?php _e('Header Mini-Menu', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#info-bar"><?php _e('Info Bar', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#extra-menu"><?php _e('Extra Menu (X-Plus)', 'weaver-xtreme' /*adm*/); ?></a>
</div>
</div>
<?php

	$all_opts = apply_filters('weaverxplus_menu_inject', $opts);

	weaverx_form_show_options($all_opts);

}


// ======================== Main Options > Content Areas ========================
function weaverx_mainopts_content() {
	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Content Areas', 'weaver-xtreme' /*adm*/), 'id' => '-admin-page', 'type' => 'header',
		'info' => __('Settings for the content areas (posts and pages)', 'weaver-xtreme' /*adm*/),
		'toggle' => 'content-areas',
		'help' => 'help.html#ContentAreas'),

	array('name' => __('Content Area', 'weaver-xtreme' /*adm*/), 'id' => 'content', 'type' => 'widget_area',
		'info' => __('Area properties for page and post content', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Page Title', 'weaver-xtreme' /*adm*/), 'id' => 'page_title', 'type' => 'titles',
		'info' => __('Page titles, including pages, post single pages, and archive-like pages.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Bar under Title', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'page_title_underline_int', 'type' => 'val_px',
		'info' => __('Enter size in px if you want a bar under page title. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Space Between Title and Content', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'space_after_title_dec', 'type' => 'val_em',
		'info' => __('Space between Page or Post title and beginning of content (Default: 1.0em)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Archive Pages Title Text', 'weaver-xtreme' /*adm*/), 'id' => 'archive_title', 'type' => 'titles',
		'info' => __('Archive-like page titles: archives, categories, tags, searches.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Content Links', 'weaver-xtreme' /*adm*/), 'id' => 'contentlink', 'type' => 'link',
		'info' => __('Color for links in Content', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Content Headings', 'weaver-xtreme' /*adm*/), 'id' => 'content_h', 'type' => '+titles',
		'info' => __('Headings (&lt;h1&gt;-&lt;h6&gt;) in page and post content (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),

	array('name' => __('Text', 'weaver-xtreme' /*adm*/), 'id' => '-text', 'type'=>'subheader_alt',
		'info' => __('Text related options', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Space after paragraphs and lists', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'content_p_list_dec', 'type' => 'val_em',
		'info' => __('Space after paragraphs and lists (Recommended: 1.5 em)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Page/Post Editor BG', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'editor_bgcolor', 'type' => 'ctext',
		'info' => __('Alternative Background Color to use for Page/Post editor if you\'re using transparent or image backgrounds.','weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Input Area BG', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'input_bgcolor', 'type' => 'ctext',
		'info' => __('Background color for text input (textareas) boxes.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Input Area Text', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'input_color', 'type' => 'color',
		'info' => __('Text color for text input (textareas) boxes.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Auto Hyphenation', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'hyphenate', 'type' => 'checkbox',
		'info' => __('Allow browsers to automatically hyphenate text for appearance.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . __('Columns', 'weaver-xtreme' /*adm*/), 'id' => 'page_cols', 'type' => 'select_id',	//code
		'info' => __('Automatically split all page content into columns using CSS column rules. Also can use Per Page option. (Always 1 column on IE&lt;=9.)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => '1', 'desc' => __('1 Column', 'weaver-xtreme' /*adm*/)),
			array('val' => '2', 'desc' => __('2 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '3', 'desc' => __('3 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '4', 'desc' => __('4 Columns', 'weaver-xtreme' /*adm*/)))
	  ),


	array('name' => __('Search Boxes', 'weaver-xtreme' /*adm*/), 'id' => '-search', 'type'=>'subheader_alt',
		'info' => __('Search box related options', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Search Input BG', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'search_bgcolor', 'type' => 'ctext',
		'info' => __('Background color for search input boxes.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Search Input Text', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'search_color', 'type' => 'color',
		'info' => __('Text color for search input boxes.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Search Icon', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'search_icon', 'type' => 'radio',	//code
		'info' => __('Search Icon - used for both Header and Search Widgets.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'black'),
			array('val' => 'gray'),
			array('val' => 'light'),
			array('val' => 'white'),
			array('val' => 'black-bg'),
			array('val' => 'gray-bg'),
			array('val' => 'white-bg'),
			array('val' => 'blue-bg'),
			array('val' => 'green-bg'),
			array('val' => 'orange-bg'),
			array('val' => 'red-bg'),
			array('val' => 'yellow-bg'),
			)),


	array( 'type' => 'submit'),
	array('name' => __('Images', 'weaver-xtreme' /*adm*/), 'id' => '-format-image', 'type'=>'subheader_alt',
		'info' => __('Image related options', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Image Border Color', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'media_lib_border_color', 'type' => 'ctext',
		'info' => __('Border color for images in Container and Footer.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Image Border Width', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'media_lib_border_int', 'type' => 'val_px',
		'info' => __('Border width for images in Container and Footer. (Leave blank or set to 0 for no image borders.)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-admin-page"></span><small>' . __('Show Image Shadows', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'show_img_shadows', 'type' => 'checkbox',
		'info' => __('Add a shadow to images  in Container and Footer. Add CSS+ to Border Color for custom shadow.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Restrict Borders to Media Library', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'restrict_img_border', 'type' => 'checkbox',
		'info' => __('For Container and Footer, restrict border and shadows to images from Media Library. Manually entered &lt;img&gt; HTML without Media Library classes will not have borders.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Caption text color', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'caption_color', 'type' => 'ctext',
		'info' => __('Color of captions - e.g., below media images.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Featured Image - Pages', 'weaver-xtreme' /*adm*/), 'id' => '-id', 'type'=>'subheader_alt',
		'info' => __('Display of Page Featured Images', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . __('Featured Image Location', 'weaver-xtreme' /*adm*/),
		'id' => 'page_fi_location', 'type' => 'fi_location',
		'info' => __('Where to display Featured Image for Pages','weaver-xtreme' /*adm*/)),
	array('name' => __('Full Width FI BG Image:', 'weaver-xtreme' /*adm*/),
		'info' => __('To create full width Page BG images from the FI, check the <em>Container Area Extend BG Attributes</em> box on the <em>Full Width</em> tab.', 'weaver-xtreme' /*adm*/),
		'type' => 'note'), // ,'help' => 'help.html#PhotoBlog'),
	array('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('Featured Image Alignment<small>', 'weaver-xtreme' /*adm*/), 'id' => 'page_fi_align', 'type' => 'fi_align',
		'info' => __('How to align the Featured Image', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Featured Image on Pages', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'page_fi_hide', 'type' => 'select_hide',
		'info' => __('Where to hide Featured Images on Pages (Posts have their own setting.)', 'weaver-xtreme' /*adm*/)),

	array ('name' => '<small>' . __('Page Featured Image Size', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'page_fi_size', 'type' => 'select_id',
		'info' => __('Media Library Image Size for Featured Image on pages. (Header uses full size).', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'thumbnail', 'desc' => __('Thumbnail (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'medium', 'desc' => __('Medium', 'weaver-xtreme' /*adm*/)),
			array('val' => 'large', 'desc' => __('Large', 'weaver-xtreme' /*adm*/)),
			array('val' => 'full', 'desc' => __('Full', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('Featured Image Width, Pages', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'page_fi_width', 'type' => '+val_percent',
		'info' => __('Width of Featured Image on Pages. Max Width in %, overrides FI Size selection. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),
	array('name' => '<small>' . __("Don't add link to FI", 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'page_fi_nolink', 'type' => '+checkbox',
		'info' => __('Do not add link to Featured Image. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),



	array('name' => __('Lists - &lt;HR&gt; - Tables', 'weaver-xtreme' /*adm*/), 'id' => '-list-view', 'type'=>'subheader_alt',
		'info' => __('Other options related to content', 'weaver-xtreme' /*adm*/)),
	array ('name' => __('Content List Bullet', 'weaver-xtreme' /*adm*/),
		'id' => 'contentlist_bullet', 'type' => 'select_id',
		'info' => __('Bullet used for Unordered Lists in Content areas', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'disc', 'desc' => __('Filled Disc (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'circle', 'desc' => __('Circle', 'weaver-xtreme' /*adm*/)),
			array('val' => 'square', 'desc' => __('Square', 'weaver-xtreme' /*adm*/)),
			array('val' => 'none', 'desc' => __('None', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => __('&lt;HR&gt; color', 'weaver-xtreme' /*adm*/), 'id' => 'hr_color', 'type' => 'ctext',
		'info' => __('Color of horizontal (&lt;hr&gt;) lines in posts and pages.', 'weaver-xtreme' /*adm*/)),

	array ('name' => __('Table Style', 'weaver-xtreme' /*adm*/), 'id' => 'weaverx_tables', 'type' => 'select_id',
		'info' => __('Style used for tables in content.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'default', 'desc' => __('Theme Default', 'weaver-xtreme' /*adm*/)),
			array('val' => 'bold', 'desc' => __('Bold Headings', 'weaver-xtreme' /*adm*/)),
			array('val' => 'noborders', 'desc' => __('No Borders', 'weaver-xtreme' /*adm*/)),
			array('val' => 'fullwidth', 'desc' => __('Wide', 'weaver-xtreme' /*adm*/)),
			array('val' => 'wide', 'desc' => __('Wide 2', 'weaver-xtreme' /*adm*/)),
			array('val' => 'plain', 'desc' => __('Minimal', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => __('Comments', 'weaver-xtreme' /*adm*/), 'id' => '-admin-comments', 'type' => 'subheader',
		'info' => __('Settings for displaying comments', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Comment Headings', 'weaver-xtreme' /*adm*/), 'id' => 'comment_headings_color', 'type' => 'ctext',
		'info' => __('Color for various headings in comment form', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Comment Content BG', 'weaver-xtreme' /*adm*/), 'id' => 'comment_content_bgcolor', 'type' => 'ctext',
		'info' => __('BG Color of Comment Content area', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Comment Submit Button BG', 'weaver-xtreme' /*adm*/), 'id' => 'comment_submit_bgcolor', 'type' => 'ctext',
		'info' => __('BG Color of "Post Comment" submit button', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style="font-size:200%;margin-left:4px;">&#x25a1;</span><small>' . __('Show Borders on Comments', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'show_comment_borders', 'type' => 'checkbox',
		'info' => __('Show Borders around comment sections - improves visual look of comments.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Old Comments When Closed', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_old_comments', 'type' => '+checkbox',
		'info' => __('Hide previous comments after closing comments for page or post. (Default: show old comments after closing.) (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>'. '<small>' . __('Show Allowed HTML', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'form_allowed_tags', 'type' => '+checkbox',
		'info' => __('Show the allowed HTML tags below comment input box (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><span class="dashicons dashicons-admin-comments"></span>' .
		  '<small>' . __('Hide Comment Title Icon', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_comment_bubble', 'type' => '+checkbox',
		'info' => __('Hide the comment icon before the Comments title (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Separator Above Comments', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_comment_hr', 'type' => '+checkbox',
		'info' => __('Hide the (&lt;hr&gt;) separator line above the Comments area (&starf;Plus)', 'weaver-xtreme' /*adm*/))
	);

?>
   <div class="options-intro">
<?php _e('<strong>Content Areas:</strong> Includes options common to both <em>Pages</em> and <em>Posts</em>. Options for <strong>Text</strong>,
<strong>Padding</strong>, <strong>Images</strong>, <strong>Lists &amp; Tables</strong>, and user <strong>Comments</strong>.', 'weaver-xtreme' /*adm*/); ?><br />
<div class="options-intro-menu">
<a href="#content-area"><?php _e('Content Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#text"><?php _e('Text', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#search-boxes"><?php _e('Search Boxes', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#images"><?php _e('Images', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#featured-image-pages"><?php _e('Featured Image - Pages', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#lists-hr-tables"><?php _e('Lists - &lt;HR&gt; - Tables', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#comments"><?php _e('Comments', 'weaver-xtreme' /*adm*/); ?></a>
</div>
   </div>
<?php
	weaverx_form_show_options($opts);
	do_action('weaverxplus_admin','content_areas');
?>
	<span style="color:green;"><b><?php _e('Hiding/Enabling Page and Post Comments', 'weaver-xtreme' /*adm*/); ?></b></span>
<?php
	weaverx_help_link('help.html#LeavingComments',__('Help for Leaving Comments', 'weaver-xtreme' /*adm*/));
?>
<p>
<?php _e('Controlling "Reply/Leave a Comment" visibility for pages and posts is <strong>not</strong> a theme function.
It is controlled by WordPress settings.
Please click the ? just above to see the help file entry!', 'weaver-xtreme' /*adm*/); ?>
</p>
<?php
}

// ======================== Main Options > Post Specifics ========================
function weaverx_mainopts_posts() {
	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Post Specifics', 'weaver-xtreme' /*adm*/), 'id' => '-admin-post', 'type' => 'header',
		'info' => __('Settings affecting Posts', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#PPSpecifics'),

	array('name' => __('Post Area', 'weaver-xtreme' /*adm*/), 'id' => 'post', 'type' => 'widget_area',
		'info' => __('Use these settings to override Content Area settings for Posts (blog entries).', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Sticky Post BG', 'weaver-xtreme' /*adm*/), 'id' => 'stickypost_bgcolor', 'type' => 'ctext',
		'info' => __('BG color for sticky posts, author info. (Add {border:none;padding:0;} to CSS to make sticky posts same as regular posts.)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Reset Major Content Options', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'reset_content_opts', 'type' => 'checkbox',
		'info' => __('Clear wrapping Content Area bg, borders, padding, and top/bottom margins for views with posts. Allows more flexible post settings.', 'weaver-xtreme' /*adm*/)),


	array( 'type' => 'submit'),


	array('name' => __('Post Title', 'weaver-xtreme' /*adm*/), 'id' => '-text', 'type' => 'subheader_alt',
		'info' => __('Options for the Post Title', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Post Title', 'weaver-xtreme' /*adm*/), 'id' => 'post_title', 'type' => 'titles',
		'info' => __("Post title (Blog Views)", 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Bar under Post Titles', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_title_underline_int', 'type' => 'val_px',
		'info' => __('Enter size in px if you want a bar under page title. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Post Title Hover', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_title_hover_color', 'type' => 'ctext',
		'info' => __('Color if you want the Post Title to show alternate color for hover', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __('Space After Post Title', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_title_bottom_margin_dec', 'type' => 'val_em',
		'info' => __('Space between Post Title and Post Info Line or content. (Default: 0.15em)', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-admin-comments"></span><small>' . __('Show Comment Bubble', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'show_post_bubble', 'type' => 'checkbox',
		'info' => __("Show comment bubble with link to comments on the post info line.", 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide <em>Post Format</em> Icons', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_post_format_icon', 'type' => '+checkbox',
		'info' => __('Hide the icons for posts with Post Format specified. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),




	array('name' => __('Post Layout', 'weaver-xtreme' /*adm*/), 'id' => '-schedule', 'type' => 'subheader_alt',
		'info' => __('Layout of Posts', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . __('Post Content Columns', 'weaver-xtreme' /*adm*/), 'id' => 'post_cols', 'type' => 'select_id',	//code
		'info' => __('Automatically split all post content into columns for both blog and single page views. <em>This is post content only.</em> This is not the same as "Columns of Posts". (IE&lt;=9 will display 1 col.)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => '1', 'desc' => __('1 Column', 'weaver-xtreme' /*adm*/)),
			array('val' => '2', 'desc' => __('2 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '3', 'desc' => __('3 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '4', 'desc' => __('4 Columns', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span>' . __('Columns of Posts', 'weaver-xtreme' /*adm*/), 'id' => 'blog_cols', 'type' => 'select_id',	//code
		'info' => __('Display posts on blog page with this many columns. (You should adjust "Display posts on blog page with this many columns" on Settings:Reading to be a multiple of this value.)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => '1', 'desc' => __('1 Column', 'weaver-xtreme' /*adm*/)),
			array('val' => '2', 'desc' => __('2 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '3', 'desc' => __('3 Columns', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span><small>' . __('Use Columns on Archive Pages', 'weaver-xtreme' /*adm*/) . '</small>' , 'id' => 'archive_cols', 'type' => 'checkbox',	//code
		'info' => __('Display posts on archive-like pages using columns. (Archive, Author, Category, Tag)', 'weaver-xtreme' /*adm*/)
	  ),

	array('name' => '<small>' . __('First Post One Column', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'blog_first_one', 'type' => 'checkbox',
		'info' => __('Always display the first post in one column.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Sticky Posts One Column', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'blog_sticky_one', 'type' => 'checkbox',
		'info' => __("Display opening Sticky Posts in one column. If First Post One Column also checked, then first non-sticky post will be one column.", 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#9783;</span><small>' . __('Use <em>Masonry</em> for Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'masonry_cols', 'type' => 'select_id',	//code
		'info' => __('Use the <em>Masonry</em> blog layout option to show dynamically packed posts on blog and archive-like pages. Overrides "Columns of Posts" setting. <em>Not compatible with full width FI BG images.</em>', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => '0', 'desc' => ''),
			array('val' => '2', 'desc' => __('2 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '3', 'desc' => __('3 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '4', 'desc' => __('4 Columns', 'weaver-xtreme' /*adm*/)),
			array('val' => '5', 'desc' => __('5 Columns', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => '<small>' . __('Compact <em>Post Format</em> Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'compact_post_formats', 'type' => 'checkbox',
		'info' => __('Use compact layout for <em>Post Format</em> posts (Image, Gallery, Video, etc.). Useful for photo blogs and multi-column layouts. Looks great with <em>Masonry</em>.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Photo Bloging', 'weaver-xtreme' /*adm*/),
		'info' => __('Read the Help entry for information on creating a Photo Blog page', 'weaver-xtreme' /*adm*/),
		'type' => 'note','help' => 'help.html#PhotoBlog'),


	array( 'type' => 'submit'),

	array('name' => __('Excerpts / Full Posts', 'weaver-xtreme' /*adm*/), 'id' => '-exerpt-view', 'type' => 'subheader_alt',
		'info' => __('How to display posts in  Blog / Archive Views', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Show Full Blog Posts', 'weaver-xtreme' /*adm*/), 'id' => 'fullpost_blog', 'type' => 'checkbox',
			'info' => __('Will display full blog post instead of excerpts on <em>blog pages</em>.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Full Post for Archives', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'fullpost_archive', 'type' => 'checkbox',
			'info' => __('Display the full posts instead of excerpts on <em>special post pages</em>. (Archives, Categories, etc.) Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Full Post for Searches', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'fullpost_search', 'type' => 'checkbox',
			'info' => __('Display the full posts instead of excerpts for Search results. Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Full text for 1st <em>"n"</em> Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'fullpost_first', 'type' => 'val_num',
		'info' => __('Display the full post for the first "n" posts on Blog pages. Does not override manually added &lt;--more--> breaks.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Excerpt length', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'excerpt_length', 'type' => 'val_num',
			'info' => __('Change post excerpt length. (Default: 40 words)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('<em>Continue reading</em> Message', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'excerpt_more_msg', 'type' => 'widetext',
			'info' => __('Change default <em>Continue reading &rarr;</em> message for excerpts. Can include HTML (e.g., &lt;img>).', 'weaver-xtreme' /*adm*/)),
	array('type' => 'endheader'),




	array('name' => __('Post Navigation', 'weaver-xtreme' /*adm*/), 'id' => '-leftright', 'type' => 'subheader_alt',
		'info' => __('Navigation for moving between posts', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Blog Navigation Style', 'weaver-xtreme' /*adm*/), 'id' => 'nav_style', 'type' => 'select_id',
		'info' => __('Style of navigation links on blog pages: "Older/Newer posts", "Previous/Next Post", or by page numbers', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'old_new', 'desc' => __('Older/Newer', 'weaver-xtreme' /*adm*/)),
			array('val' => 'prev_next', 'desc' => __('Previous/Next', 'weaver-xtreme' /*adm*/)),
			array('val' => 'paged_left', 'desc' => __('Paged - Left', 'weaver-xtreme' /*adm*/)),
			array('val' => 'paged_right', 'desc' => __('Paged - Right', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Top Links', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'nav_hide_above', 'type' => '+checkbox',
		'info' => __('Hide the blog navigation links at the top (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Bottom Links', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'nav_hide_below', 'type' => '+checkbox',
		'info' => __('Hide the blog navigation links at the bottom (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Show Top on First Page', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'nav_show_first', 'type' => '+checkbox',
		'info' => __('Show navigation at top even on the first page (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Single Page Navigation Style', 'weaver-xtreme' /*adm*/), 'id' => 'single_nav_style', 'type' => 'select_id',
		'info' => __('Style of navigation links on post Single pages: Previous/Next, by title, or none', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'title', 'desc' => __('Post Titles', 'weaver-xtreme' /*adm*/)),
			array('val' => 'prev_next', 'desc' => __('Previous/Next', 'weaver-xtreme' /*adm*/)),
			array('val' => 'hide', 'desc' => __('None - no display', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<small>' . __('Link to Same Categories', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'single_nav_link_cats', 'type' => '+checkbox',
		'info' => __('Single Page navigation links point to posts with same categories. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Top Links', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'single_nav_hide_above', 'type' => '+checkbox',
		'info' => __('Hide the single page navigation links at the top (&starf;Plus)', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Bottom Links', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'single_nav_hide_below', 'type' => '+checkbox',
		'info' => __('Hide the single page navigation links at the bottom (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),
	array('name' => __('Post Meta Info Areas', 'weaver-xtreme' /*adm*/), 'id' => '-info', 'type' => 'subheader_alt',
		'info' => __('Top and Bottom Post Meta Information areas', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Top Post Info', 'weaver-xtreme' /*adm*/), 'id' => 'post_info_top', 'type' => 'titles_text',
		'info' => __("Top Post info line", 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide top post info', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_info_hide_top', 'type' => 'checkbox',	//code
		'info' => __('Hide entire top info line (posted on, by) of post.','weaver-xtreme' /*adm*/)),

	array('name' => __('Bottom Post Info', 'weaver-xtreme' /*adm*/), 'id' => 'post_info_bottom', 'type' => 'titles_text',
		'info' => __('The bottom post info line', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide bottom post info', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_info_hide_bottom', 'type' => 'checkbox',	//code
		'info' => __('Hide entire bottom info line (posted in, comments) of post.', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-visibility"></span>' . __('Show Author Avatar', 'weaver-xtreme' /*adm*/),
		'id' => 'show_post_avatar', 'type' => 'select_id',	//code
		'info' => __('Show author avatar on the post info line (also can be set per post with post editor)', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'hide', 'desc' => __('Do Not Show', 'weaver-xtreme' /*adm*/)),
			array('val' => 'start', 'desc' => __('Start of Info Line', 'weaver-xtreme' /*adm*/)),
			array('val' => 'end', 'desc' => __('End of Info Line', 'weaver-xtreme' /*adm*/)))),

	array('name' => '<small>' . __('Avatar size', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_avatar_int', 'type' => 'val_px',
			'info' => __('Size of Avatar in px. (Default: 28px)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Use Icons in Post Info', 'weaver-xtreme' /*adm*/), 'id' => 'post_icons', 'type' => 'select_id',
		'info' => __('Use Icons instead of Text descriptions in Post Meta Info', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'text', 'desc' => __('Text Descriptions', 'weaver-xtreme' /*adm*/)),
			array('val' => 'fonticons', 'desc' => __('Font Icons', 'weaver-xtreme' /*adm*/)),
			array('val' => 'graphics', 'desc' => __('Graphic Icons', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<small>' . __('Font Icons Color', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_icons_color', 'type' => 'color',
		'info' => __('Color for Font Icons (Default: Post Info text color)', 'weaver-xtreme' /*adm*/)),


	array('name' => '<span style="color:red">' . __('Note:', 'weaver-xtreme' /*adm*/) . '</span>',
		  'type' => 'note', 'info' => __('Hiding any meta info item automatically uses Icons instead of text descriptions.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Post Date', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_hide_date', 'type' => 'checkbox',
		'info' => __('Hide the post date everywhere it is normally displayed.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Post Author', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_hide_author', 'type' => 'checkbox',
		'info' => __('Hide the post author everywhere it is normally displayed.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Post Categories', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_hide_categories', 'type' => 'checkbox',
		'info' => __('Hide the post categories wherever they are normally displayed.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Post Tags', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'post_hide_tags', 'type' => 'checkbox',
			'info' => __('Hide the post tags wherever they are normally displayed.','weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Permalink', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_permalink', 'type' => 'checkbox',
		'info' => __('Hide the permalink.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Category if Only One', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_singleton_category', 'type' => 'checkbox',
		'info' => __('If there is only one overall category defined (Uncategorized), don\'t show Category of post.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Author for Single Author Site', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_hide_single_author', 'type' => 'checkbox',
		'info' => __('Hide author information if site has only a single author.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Post Info Links', 'weaver-xtreme' /*adm*/), 'id' => 'ilink', 'type' => 'link',
		'info' => __('Links in post information top and bottom lines.', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),


	array('name' => __('Featured Image - Posts', 'weaver-xtreme' /*adm*/), 'id' => '-id', 'type' => 'subheader_alt',
		'info' => __('Display of Post Featured Images', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Full Width FI BG Image:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => __('To create full width Post BG images from the FI, check the <em>Post Area Extend BG Attributes</em> box at <em>Full Width</em> tab.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<small>' . __("Don't add link to FI", 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_fi_nolink', 'type' => '+checkbox',
		'info' => __('Do not add link to Featured Image for any post layout. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),

	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . __('FI Location - Full Post', 'weaver-xtreme' /*adm*/),
		'id' => 'post_full_fi_location', 'type' => 'fi_location_post',
		'info' => __('Where to display Featured Image for full blog posts.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('FI Alignment - Full post', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_full_fi_align', 'type' => 'fi_align',
		'info' => __('Featured Image alignment','weaver-xtreme' /*adm*/)),


	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide FI - Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_full_fi_hide', 'type' => 'select_hide',
		'info' => __('Hide Featured Images on full blog posts.', 'weaver-xtreme' /*adm*/)),
	array ('name' => '<small>' . __('FI Size - Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_full_fi_size', 'type' => 'select_id',
		'info' => __('Media Library Image Size for Featured Image on full posts.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'thumbnail', 'desc' => __('Thumbnail (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'medium', 'desc' => __('Medium', 'weaver-xtreme' /*adm*/)),
			array('val' => 'large', 'desc' => __('Large', 'weaver-xtreme' /*adm*/)),
			array('val' => 'full', 'desc' => __('Full', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('FI Width, Full Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_full_fi_width', 'type' => '+val_percent',
		'info' => __('Width of Featured Image on Full Posts.  Max Width in %, overrides FI Size selection. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),



	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>'. __('FI Location - Excerpts', 'weaver-xtreme' /*adm*/),
		'id' => 'post_excerpt_fi_location', 'type' => 'fi_location_post',
		'info' => __('Where to display Featured Image for posts displayed as excerpt.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('FI Alignment - Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_excerpt_fi_align', 'type' => 'fi_align',
		'info' => __('How to align the Featured Image', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide FI - Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_excerpt_fi_hide', 'type' => 'select_hide',
		'info' => __('Where to hide Featured Images on full blog posts.', 'weaver-xtreme' /*adm*/)),
	array ('name' => '<small>FI Size - Excerpts</small>',
		'id' => 'post_excerpt_fi_size', 'type' => 'select_id',
		'info' => __('Media Library Image Size for Featured Image on excerpts.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'thumbnail', 'desc' => __('Thumbnail (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'medium', 'desc' => __('Medium', 'weaver-xtreme' /*adm*/)),
			array('val' => 'large', 'desc' => __('Large', 'weaver-xtreme' /*adm*/)),
			array('val' => 'full', 'desc' => __('Full', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('FI Width, Excerpts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_excerpt_fi_width', 'type' => '+val_percent',
		'info' => __('Width of Featured Image on excerpts.  Max Width in %, overrides FI Size selection. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),


	array('name' => '<span class="i-left" style=font-size:120%;">&nbsp;&#10538;</span>' . __('FI Location - Single Page', 'weaver-xtreme' /*adm*/),
		'id' => 'post_fi_location', 'type' => 'fi_location',
		'info' => __('Where to display Featured Image for posts on single page view.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-editor-alignleft"></span><small>' . __('FI Alignment - Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_fi_align', 'type' => 'fi_align',
		'info' => __('How to align the Featured Image on Single Page View.', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide FI - Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_fi_hide', 'type' => 'select_hide',
		'info' => __('Where to hide Featured Images on single page view.', 'weaver-xtreme' /*adm*/)),
	array ('name' => '<small>' . __('FI Size - Single Posts', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_fi_size', 'type' => 'select_id',
		'info' => __('Media Library Image Size for Featured Image on single page view.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'thumbnail', 'desc' => __('Thumbnail (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'medium', 'desc' => __('Medium', 'weaver-xtreme' /*adm*/)),
			array('val' => 'large', 'desc' => __('Large', 'weaver-xtreme' /*adm*/)),
			array('val' => 'full', 'desc' => __('Full', 'weaver-xtreme' /*adm*/)))
	  ),
	array('name' => '<span class="i-left" style="font-size:150%;">&harr;</span><small>' . __('FI Width, Single Page', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'post_fi_width', 'type' => '+val_percent',
		'info' => __('Width of Featured Image on single page view. Max Width in %, overrides FI Size selection. (&starf;Plus)', 'weaver-xtreme' /*adm*/) ),



	array( 'type' => 'submit'),


	array('name' => __('More Post Related Options', 'weaver-xtreme' /*adm*/), 'id' => '-forms', 'type' => 'subheader_alt',
		'info' => __('Other options related to post display, including single pages.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Show <em>Comments are closed.</em>', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'show_comments_closed', 'type' => 'checkbox',
		'info' => __('If comments are off, and no comments have been made, show the <em>Comments are closed.</em> message.', 'weaver-xtreme' /*adm*/) ),
	array('name' => __('Author Info BG', 'weaver-xtreme' /*adm*/), 'id' => 'post_author_bgcolor', 'type' => 'ctext',
			'info' => __('Background color used for Author Bio.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<span class="i-left dashicons dashicons-visibility"></span><small>' . __('Hide Author Bio', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'hide_author_bio', 'type' => 'checkbox',
		'info' => __('Hide display of author bio box on Author Archive and Single Post page views.', 'weaver-xtreme' /*adm*/)),
	array('name' => '<small>' . __('Allow comments for attachments', 'weaver-xtreme' /*adm*/) . '</small>',
		'id' => 'allow_attachment_comments', 'type' => 'checkbox',
		'info' => __('Allow visitors to leave comments for attachments (usually full size media image - only if comments allowed).', 'weaver-xtreme' /*adm*/))
	);

?>
   <div class="options-intro">
<?php _e('<strong>Post Specifics: </strong>
Options related to <strong>Posts</strong>, including <strong>Background</strong> color, <strong>Columns</strong> displayed
on blog pages, <strong>Title</strong> options, <strong>Navigation</strong> to earlier and later posts, the post
<strong>Info Lines</strong>, <strong>Excerpts</strong>, and <strong>Featured Image</strong> handling.', 'weaver-xtreme' /*adm*/); ?>
<br />
<div class="options-intro-menu">
<a href="#post-area"><?php _e('Post Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#post-title"><?php _e('Post Title', 'weaver-xtreme' /*adm*/);?></a> |
<a href="#post-layout"><?php _e('Post Layout', 'weaver-xtreme' /*adm*/);?></a> |
<a href="#excerpts-full-posts"><?php _e('Excerpts / Full Posts', 'weaver-xtreme' /*adm*/);?></a> |
<a href="#post-navigation"><?php _e('Post Navigation', 'weaver-xtreme' /*adm*/);?></a> |
<a href="#post-meta-info-areas"><?php _e('Post Meta Info Areas', 'weaver-xtreme' /*adm*/);?></a> |
<a href="#featured-image-posts"><?php _e('Featured Image - Posts', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#more-post-related-options"><?php _e('More Post Related Options', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#custom-post-info-lines"><?php _e('Custom Post Info Lines', 'weaver-xtreme' /*adm*/); ?></a>
</div>
   </div>
<?php
	weaverx_form_show_options($opts);
	do_action('weaverxplus_admin','post_specifics');
?>
	<span style="color:green;"><b><?php _e('Hiding/Enabling Page and Post Comments', 'weaver-xtreme' /*adm*/); ?></b></span>
<?php
	weaverx_help_link('help.html#LeavingComments',__('Help for Leaving Comments', 'weaver-xtreme' /*adm*/));
?>
<p>
<?php _e('Controlling "Reply/Leave a Comment" visibility for pages and posts is <strong>not</strong> a theme function.
It is controlled by WordPress settings.
Please click the ? just above to see the help file entry!
(Additional options for comment <em>styling</em> are found on the Content Areas tab.)', 'weaver-xtreme' /*adm*/); ?>
</p>
<?php
}


// ======================== Main Options > Footer ========================
function weaverx_mainopts_footer() {
	$opts = array(
	array( 'type' => 'submit'),

	array('name' => __('Footer Options', 'weaver-xtreme' /*adm*/), 'id' => '-admin-generic', 'type' => 'header',
		'info' => __('Settings for the footer', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#FooterOpt'),


	array('name' => __('Footer Area', 'weaver-xtreme' /*adm*/), 'id' => 'footer', 'type' => 'widget_area',
		'info' => __('Properties for the footer area.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Footer Links', 'weaver-xtreme' /*adm*/), 'id' => 'footerlink', 'type' => 'link',
		'info' => __('Color for links in Footer (Uses Standard Link colors if left blank).', 'weaver-xtreme' /*adm*/)),
	array( 'type' => 'submit'),

	array('name' => __('Footer Widget Area', 'weaver-xtreme' /*adm*/), 'id' => 'footer_sb', 'type' => 'widget_area_submit',
		'info' => __('Properties for the Footer Widget Area.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Footer HTML', 'weaver-xtreme' /*adm*/), 'id' => 'footer_html', 'type' => 'widget_area',
		'info' => __('Add arbitrary HTML to Footer Area (in &lt;div id=\"footer-html\"&gt;)', 'weaver-xtreme' /*adm*/)),

	array('name' => '<span class="i-left dashicons dashicons-editor-code"></span>' . __('Footer HTML content', 'weaver-xtreme' /*adm*/),
		'id' => 'footer_html_text', 'type' => 'textarea',
		'placeholder' => __('Any HTML, including shortcodes.', 'weaver-xtreme' /*adm*/),
		'info' => __("Add arbitrary HTML", 'weaver-xtreme' /*adm*/), 'val' => 4),
	array( 'type' => 'submit'),
	);

?>
<div class="options-intro">
<?php _e('<strong>Footer: </strong> 	Options affecting the <strong>Footer</strong> area, including <strong>Background</strong>
color, <strong>Borders</strong>, and the <strong>Copyright</strong> message.', 'weaver-xtreme' /*adm*/); ?>
<br />
<div class="options-intro-menu">
<a href="#footer-area"><?php _e('Footer Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#footer-widget-area"><?php _e('Footer Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#footer-html"><?php _e('Footer HTML', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#site-copyright"><?php _e('Site Copyright', 'weaver-xtreme' /*adm*/); ?></a>
</div>
</div>
<?php
	weaverx_form_show_options($opts);
	do_action('weaverxplus_admin','footer_opts');
?>
	<a id="site-copyright"></a>
<strong>&copy;</strong>&nbsp;<span style="color:blue;"><b><?php _e('Site Copyright', 'weaver-xtreme' /*adm*/); ?></b></span>
<br/>
<small>
<?php _e('If you fill this in, the default copyright notice in the footer will be replaced with the text here.
It will not automatically update from year to year.
Use &amp;copy; to display &copy;.
You can use other HTML as well.
Use <span class="style4">&amp;nbsp;</span> to hide the copyright notice. &diams;', 'weaver-xtreme' /*adm*/); ?>
</small>
	<br />

	<span class="dashicons dashicons-editor-code"></span>
	<?php weaverx_textarea(weaverx_getopt('copyright'), 'copyright', 1, ' ', 'width:85%;'); ?>
	<br>
		<label><span class="dashicons dashicons-visibility"></span> <?php _e('Hide Powered By tag:', 'weaver-xtreme' /*adm*/); ?>
		<input type="checkbox" name="<?php weaverx_sapi_main_name('_hide_poweredby'); ?>" id="_hide_poweredby" <?php checked(weaverx_getopt_checked( '_hide_poweredby' )); ?> />
		</label>
		<small><?php _e('Check this to hide the "Proudly powered by" notice in the footer.', 'weaver-xtreme' /*adm*/); ?></small>
		<br /><br />
	<?php _e('You can add other content to the Footer from the Advanced Options:HTML Insertion tab.', 'weaver-xtreme' /*adm*/); ?>
<?php
}

// ======================== Main Options > Widget Areas ========================
function weaverx_mainopts_widgets() {
	$opts = array(
	array( 'type' => 'submit'),
	array('name' => __('Sidebar Options', 'weaver-xtreme' /*adm*/), 'id' => '-screenoptions', 'type' => 'header',
		'info' => __('Settings affecting main Sidebars and individual widgets', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#WidgetAreas'),

	array('name' => __('Individual Widgets', 'weaver-xtreme' /*adm*/), 'id' => 'widget', 'type' => 'widget_area',
		'info' => __('Properties for individual widgets (e.g., Text, Recent Posts, etc.)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Widget Title', 'weaver-xtreme' /*adm*/), 'id' => 'widget_title', 'type' => 'titles',
		'info' => __('Color for Widget Titles.', 'weaver-xtreme' /*adm*/)),
	array('name' => __('Bar under Widget Titles', 'weaver-xtreme' /*adm*/), 'id' => 'widget_title_underline_int', 'type' => 'val_px',
		'info' => __('Enter size in px if you want a bar under Widget Titles. Leave blank or 0 for no bar.', 'weaver-xtreme' /*adm*/)),

	array ('name' => __('Widget List Bullet', 'weaver-xtreme' /*adm*/),
		'id' => 'widgetlist_bullet', 'type' => 'select_id',
		'info' => __('Bullet used for Unordered Lists in Widget areas.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'disc', 'desc' => __('Filled Disc (default)', 'weaver-xtreme' /*adm*/)),
			array('val' => 'circle', 'desc' => __('Circle', 'weaver-xtreme' /*adm*/)),
			array('val' => 'square', 'desc' => __('Square', 'weaver-xtreme' /*adm*/)),
			array('val' => 'none', 'desc' => __('None', 'weaver-xtreme' /*adm*/)))
	  ),

	array('name' => __('Widget Links', 'weaver-xtreme' /*adm*/), 'id' => 'wlink', 'type' => 'link',
		'info' => __('Color for links in widgets (uses Standard Link colors if left blank).', 'weaver-xtreme' /*adm*/)),

	array( 'type' => 'submit'),



	array('name' => __('Primary Widget Area', 'weaver-xtreme' /*adm*/), 'id' => 'primary', 'type' => 'widget_area_submit',
		'info' => __('Properties for the Primary (Upper/Left) Sidebar Widget Area.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Secondary Widget Area', 'weaver-xtreme' /*adm*/), 'id' => 'secondary', 'type' => 'widget_area_submit',
		'info' => __('Properties for the Secondary (Lower/Right) Sidebar Widget Area.', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Top Widget Areas', 'weaver-xtreme' /*adm*/), 'id' => 'top', 'type' => 'widget_area_submit',
		'info' => __('Properties for all Top Widget areas (Sitewide, Pages, Blog, Archive).', 'weaver-xtreme' /*adm*/)),


	array('name' => __('Bottom Widget Areas', 'weaver-xtreme' /*adm*/), 'id' => 'bottom', 'type' => 'widget_area',
		'info' => __('Properties for all Bottom Widget areas (Sitewide, Pages, Blog, Archive).', 'weaver-xtreme' /*adm*/)),

	);

	weaverx_form_show_options($opts);
?>
<hr />
	<span style="color:blue;"><b>Define Per Page Extra Widget Areas</b></span>
<?php
	weaverx_help_link('help.html#PPWidgets','Help for Per Page Widget Areas');
?>
<br/>
<small>
<?php _e('You may define extra widget areas that can then be used in the <em>Per Page</em> settings, or in the <em>Weaver Xtreme Plus</em> [widget_area] shortcode.
Enter a list of one or more widget area names separated by commas.
Your names should include only letters, numbers, or underscores - no spaces or other special characters.
The widgets areas will then appear on the Appearance->Widgets menus.
They can be included on individual pages by adding the name you define here to the "Weaver Xtreme Options For This Page" box on the Edit Page screen. (&diams;)', 'weaver-xtreme' /*adm*/); ?>
</small>
<br />
<?php weaverx_textarea(weaverx_getopt('_perpagewidgets'), '_perpagewidgets', 1, ' ', $style = 'width:60%;', $class='wvrx-edit'); ?>
<?php
	do_action('weaverxplus_admin','widget_areas');
}

// ======================== Main Options > Layout ========================
function weaverx_mainopts_layout() {
	$opts = array( array( 'type' => 'submit'),
	array('name' => __('Sidebar Layout', 'weaver-xtreme' /*adm*/), 'id' => '-welcome-widgets-menus', 'type' => 'header',
		'info' => __('Sidebar Layout for each type of page ("stack top" used for mobile view)', 'weaver-xtreme' /*adm*/),
		'help' => 'help.html#layout'),

	array('name' => __('Blog, Post, Page Default', 'weaver-xtreme' /*adm*/), 'id' => 'layout_default', 'type' => 'select_id',
		'info' => __('Select the default theme layout for blog, single post, attachments, and pages.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'right', 'desc' => __('Sidebars on Right', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'right-top', 'desc' => __('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'left', 'desc' => __(' Sidebars on Left', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'left-top', 'desc' => __(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'split', 'desc' => __('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'split-top', 'desc' => __('Split (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'one-column', 'desc' => __('No sidebars, content only', 'weaver-xtreme' /*adm*/) )
	)),

	array('name' => __('Archive-like Default', 'weaver-xtreme' /*adm*/), 'id' => 'layout_default_archive', 'type' => 'select_id',
		'info' => __('Select the default theme layout for all other pages - archives, search, etc.', 'weaver-xtreme' /*adm*/),
		'value' => array(
			array('val' => 'right', 'desc' => __('Sidebars on Right', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'right-top', 'desc' => __('Sidebars on Right (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'left', 'desc' => __(' Sidebars on Left', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'left-top', 'desc' => __(' Sidebars on Left (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'split', 'desc' => __('Split - Sidebars on Right and Left', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'split-top', 'desc' => __('Split (stack top)', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'one-column', 'desc' => __('No sidebars, content only', 'weaver-xtreme' /*adm*/) )
	)),

	array('name' => __('Page', 'weaver-xtreme' /*adm*/), 'id' => 'layout_page', 'type' => 'select_layout',
		'info' => __('Layout for normal Pages on your site.', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => __('Blog', 'weaver-xtreme' /*adm*/), 'id' => 'layout_blog', 'type' => 'select_layout',
		'info' => __('Layout for main blog page. Includes "Page with Posts" Page templates.', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => __('Post Single Page', 'weaver-xtreme' /*adm*/), 'id' => 'layout_single', 'type' => 'select_layout',
		'info' => __('Layout for Posts displayed as a single page.', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),

	array('name' => __('Attachments', 'weaver-xtreme' /*adm*/), 'id' => 'layout_image', 'type' => '+select_layout',
		'info' => __('Layout for attachment pages such as images. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),

	array('name' => __('Date Archive', 'weaver-xtreme' /*adm*/), 'id' => 'layout_archive', 'type' => '+select_layout',
		'info' => __('Layout for archive by date pages. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),

	array('name' => __('Category Archive', 'weaver-xtreme' /*adm*/), 'id' => 'layout_category', 'type' => '+select_layout',
		'info' => __('Layout for category archive pages. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => __('Tags Archive', 'weaver-xtreme' /*adm*/), 'id' => 'layout_tag', 'type' => '+select_layout',
		'info' => __('Layout for tag archive pages. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),

	array('name' => __('Author Archive</small>', 'weaver-xtreme' /*adm*/), 'id' => 'layout_author', 'type' => '+select_layout',
		'info' => __('Layout for author archive pages. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => __('Search Results, 404</small>', 'weaver-xtreme' /*adm*/), 'id' => 'layout_search', 'type' => '+select_layout',
		'info' => __('Layout for search results and 404 pages. (&starf;Plus)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),

	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . __('Left Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'left_sb_width_int', 'type' => 'val_percent',
		'info' => __('Width for Left Sidebar (Default: 25%)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . __('Right Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'right_sb_width_int', 'type' => 'val_percent',
		'info' => __('Width for Right Sidebar (Default: 25%)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . __('Split Left Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'left_split_sb_width_int', 'type' => 'val_percent',
		'info' => __('Width for Split Sidebar, Left Side (Default: 25%)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span><small>' . __('Split Right Sidebar Width', 'weaver-xtreme' /*adm*/) . '</small>', 'id' => 'right_split_sb_width_int', 'type' => 'val_percent',
		'info' => __('Width for Split Sidebar, Right Side (Default: 25%)', 'weaver-xtreme' /*adm*/),
		'value' => ''
	  ),
	array('name' => '<span class="i-left" style="font-size:120%;">&harr;</span> ' . __('Content Width:', 'weaver-xtreme' /*adm*/), 'type' => 'note',
		'info' => __('The width of content area automatically determined by sidebar layout and width', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Flow color to bottom', 'weaver-xtreme' /*adm*/), 'id' => 'flow_color', 'type' => '+checkbox',
		'info' => __('If checked, Content and Sidebar bg colors will flow to bottom of the Container (that is, equal heights). You must provide background colors for the Content and Sidebars or the default bg color will be used. (&starf;Plus)', 'weaver-xtreme' /*adm*/)),

	array('name' => __('Alt Page Themes', 'weaver-xtreme' /*adm*/), 'id' => '-editor-codex', 'type' => 'header_area',
		'info' => __('&starf; Weaver Xtreme Plus (V 3.1.1 or later) allows you to set Alternative Themes for the blog, single, and other archive-like pages.', 'weaver-xtreme' /*adm*/)),



	);
	?>
<div class="options-intro">
<strong>Sidebars &amp; Layout: </strong>
<?php _e('Options affecting <strong>Sidebar Layout</strong> and the main <strong>Sidebar Areas</strong>.
This includes properties of individual <strong>Widgets</strong>, as well as properties of various <strong>Sidebars</strong>.', 'weaver-xtreme' /*adm*/); ?>
<br />
<div class="options-intro-menu">
<a href="#sidebar-layout"><?php _e('Sidebar Layout', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#individual-widgets"><?php _e('Individual Widgets', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#primary-widget-area"><?php _e('Primary Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#secondary-widget-area"><?php _e('Secondary Widget Area', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#top-widget-areas"><?php _e('Top Widget Areas', 'weaver-xtreme' /*adm*/); ?></a> |
<a href="#bottom-widget-areas"><?php _e('Bottom Widget Areas', 'weaver-xtreme' /*adm*/); ?></a>
</div>
</div>
<?php

	weaverx_form_show_options($opts);
	do_action('weaverxplus_admin','layout');   // add new layout option?
}
?>
