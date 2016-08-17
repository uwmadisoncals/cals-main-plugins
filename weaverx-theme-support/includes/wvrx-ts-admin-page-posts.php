<?php
/* Display per page and per post options.
 *
 *  __ added - 12/10/14
 *
 *  IMPORTANT! - this code and the Weaver Plus plugin need to be maintained in parallel!
 */

if ( !defined('ABSPATH')) exit; // Exit if accessed directly
// Admin panel that gets added to the page edit page for per page options


if ( ! function_exists( 'weaverxplus_plugin_installed' ) ) {

function wvrx_ts_isp_true($val) {
	if ($val) return true;
	return false;
}

function wvrx_ts_page_checkbox($opt, $msg, $width = 33, $br = 0) {
	global $post;
?>
	<div style="float:left;width:<?php echo $width; ?>%"><label><input type="checkbox" id="<?php echo($opt); ?>" name="<?php echo($opt); ?>"
<?php checked(wvrx_ts_isp_true(get_post_meta($post->ID, $opt, true))); ?> />
<?php 	echo($msg . '</label></div>');
	for ($i = 0 ; $i < $br ; $i++)
		echo '<br class="page_checkbox" style="clear:both;" />';
}

function wvrx_ts_page_layout( $page = 'page' ) {

	if ( $page == 'page')
		$msg = __('Select <em>Sidebar Layout</em> for this page - overrides default Page layout.','weaverx-theme-support' /*adm*/);
	else
		$msg = __('Select Single Page View <em>Sidebar Layout</em> for this post - overrides default Single View layout.','weaverx-theme-support' /*adm*/);

	$opts = array( 'id' => '_pp_page_layout',
		'info' => $msg,
		'value' => array(
			array('val' => '', 'desc' => __('Use Default','weaverx-theme-support' /*adm*/) ),
			array('val' => 'right', 'desc' => __('Sidebars on Right','weaverx-theme-support' /*adm*/) ),
			array('val' => 'right-top', 'desc' => __('Sidebars on Right (stack top)','weaverx-theme-support' /*adm*/) ),
			array('val' => 'left', 'desc' => __('Sidebars on Left','weaverx-theme-support' /*adm*/) ),
			array('val' => 'left-top', 'desc' => __('Sidebars on Left (stack top)','weaverx-theme-support' /*adm*/) ),
			array('val' => 'split', 'desc' => __('Split - Sidebars on Right and Left','weaverx-theme-support' /*adm*/) ),
			array('val' => 'split-top', 'desc' => __('Split (stack top)','weaverx-theme-support' /*adm*/) ),
			array('val' => 'one-column', 'desc' => __('No sidebars, content only','weaverx-theme-support' /*adm*/) )
	));
	wvrx_ts_pp_select_id($opts);
}
//--



function wvrx_ts_pp_replacement( $desc, $id ) {
	global $post;
	global $wp_registered_sidebars;

	$id = '_' . $id;

	echo "\n<div style='float:left;width:40%;'><select name='{$id}' id='{$id}'> <option value=''>&nbsp;</option>\n";


	foreach ( (array) $wp_registered_sidebars as $key => $value ) {
		$area_name = $value['id']; //sanitize_title($value['name']);
		if ( strpos( $area_name, 'per-page-' ) !== false ) {
			echo ' <option value="' . $area_name . '"';
			selected( wvrx_ts_isp_true( get_post_meta($post->ID, $id, true) == $area_name ));
			echo '>' . substr($area_name,9) . "</option>\n";

		}
	}
	echo '</select>&nbsp;&nbsp;' . $desc . "</div>\n";
}
//--


function wvrx_ts_pp_select_id( $value ) {
	global $post;

	if ( isset( $value['name'] ) && $value['name'] != '' )
		echo "\n{$value['name']}&nbsp;&nbsp;&nbsp;\n";

	echo "\n<select name=\"" . $value['id'] . '" id="' . $value['id'] . "\">\n";

	foreach ($value['value'] as $option) {
		if ( $option['val'] == '' ) {
			echo '<option value="">';
		} else {
			echo ' <option value="' . $option['val'] . '"';
			selected( wvrx_ts_isp_true( get_post_meta($post->ID, $value['id'], true) == $option['val'] ));
			echo ">";
		}
		echo $option['desc'] . "</option>\n";
	}
	echo '</select>&nbsp;' . $value['info'] . "\n";
}
//--



function wvrx_ts_pwp_atw_show_post_filter() {
	// use plugin options...
	global $post;

if ( function_exists( 'atw_showposts_installed' ) ) {
	$filters = atw_posts_getopt('filters');

	$first = true;
	echo '<select id="_pp_post_filter" name="_pp_post_filter" >';
	foreach ($filters as $filter => $val) {     // display dropdown of available filters
		if ( $first ) {
			$first = false;
			echo '<option value="" ' . selected(get_post_meta($post->ID, '_pp_post_filter', true) == '') . '>Use above post filtering options</option>';
		} else {
			echo '<option value="' . $filter .'" ' . selected(get_post_meta($post->ID, '_pp_post_filter', true) == $filter) . '>' . $val['name'] . '</option>';
		}
	}
	echo '</select>&nbsp;' .
__('Use a Filter from <em>Weaver Show Posts Plugin</em> <strong>instead</strong> of above post selection options.','weaverx-theme-support' /*adm*/) .
'<br /> <span style="margin-left:8em;"><span>' .
__('(Note: Weaver Show Posts <em>Post Display</em> options and <em>Use Paging</em> option <strong>not</strong> used for posts using this filter.)','weaverx-theme-support' /*adm*/) .
'<br />' . '<br />';
} else {
_e('<strong>Want More Post Filtering Options?</strong> Install the <em>Aspen Themeworks Show Posts</em> plugin for more filtering options.','weaverx-theme-support' /*adm*/); ?>
<br /><br />
<?php }
}
//--



function wvrx_ts_pwp_type() {
	$opts = array( 'name' => __('Display posts as:','weaverx-theme-support' /*adm*/), 'id' => '_pp_wvrx_pwp_type',
		'info' => __('How to display posts on this Page with Posts (Default: global Full Post/Excerpt setting)','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => '&nbsp;' ),
			array('val' => 'full', 'desc' => __('Full post','weaverx-theme-support' /*adm*/) ),
			array('val' => 'excerpt', 'desc' => __('Excerpt','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title', 'desc' => __('Title only','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title_featured', 'desc' => __('Title + Featured Image','weaverx-theme-support' /*adm*/) )
	));
	wvrx_ts_pp_select_id($opts);
}


function wvrx_ts_page_cols() {

	$opts = array( 'name' => '', 'id' => '_pp_page_cols',
		'info' => __('Display page content in this many columns using CSS column rules.','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => '&nbsp;'),
			array('val' => '1', 'desc' => __('1 Column','weaverx-theme-support' /*adm*/) ),
			array('val' => '2', 'desc' => __('2 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '3', 'desc' => __('3 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '4', 'desc' => __('4 Columns','weaverx-theme-support' /*adm*/) ))
		);
	wvrx_ts_pp_select_id($opts);

	weaverx_html_br();
	weaverx_html_br();
}


function wvrx_ts_pwp_cols() {

	$opts = array( 'name' => __('Display post columns:','weaverx-theme-support' /*adm*/), 'id' => '_pp_wvrx_pwp_cols',
		'info' => __('Display posts in this many columns - left to right, then top to bottom','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => '&nbsp;'),
			array('val' => '1', 'desc' => __('1 Column','weaverx-theme-support' /*adm*/) ),
			array('val' => '2', 'desc' => __('2 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '3', 'desc' => __('3 Columns','weaverx-theme-support' /*adm*/) ) )
		);
	wvrx_ts_pp_select_id($opts);

	weaverx_html_br();

	$opts2 = array( 'name' => __('Use <em>Masonry</em> columns:','weaverx-theme-support' /*adm*/), 'id' => '_pp_pwp_masonry',
		'info' => __('Use <em>Masonry</em> for multi-column display','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => '&nbsp;' ),
			array('val' => '1', 'desc' => __('1 Column','weaverx-theme-support' /*adm*/) ),
			array('val' => '2', 'desc' => __('2 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '3', 'desc' => __('3 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '4', 'desc' => __('4 Columns','weaverx-theme-support' /*adm*/) ),
			array('val' => '5', 'desc' => __('5 Columns','weaverx-theme-support' /*adm*/) ) )
		);
	wvrx_ts_pp_select_id($opts2);

?>
	<br />
<?php
	wvrx_ts_page_checkbox('_pp_pwp_compact', __('For posts with <em>Post Format</em> specified, use compact layout on blog/archive pages.','weaverx-theme-support' /*adm*/),90,1);
	wvrx_ts_page_checkbox('_pp_pwp_compact_posts', __('For regular, <em>non-PostFormats</em> posts, show <em>title + first image</em> on blog pages.','weaverx-theme-support' /*adm*/),90,1);
}

function wvrx_ts_page_extras() {
	global $post;
	$opts = get_option( apply_filters('weaverx_options','weaverx_settings') , array());	// need to fetch Weaver Xtreme options

	if ( !( current_user_can('edit_themes')
		|| (current_user_can('edit_theme_options') && !isset($opts['_hide_mu_admin_per']))	// multi-site regular admin
		|| (current_user_can('edit_pages') && !isset($opts['_hide_editor_per']))	// Editor
		|| (current_user_can('edit_posts') && !isset($opts['_hide_author_per'])))    // Author/Contributor
	) {
		echo '<p>' . __('Weaver Xtreme Per Page Options not available for your User Role.','weaverx-theme-support' /*adm*/) . '</p>';
		return;	// don't show per post panel
	   }

	echo("<div style=\"line-height:150%;\"><p>\n");
	if (get_the_ID() == get_option( 'page_on_front' ) ) { ?>
<div style="padding:2px; border:2px solid yellow; background:#FF8;">
<?php _e('Information: This page has been set to serve as your front page in the <em>Dashboard:Settings:Reading</em> \'Front page:\' option.','weaverx-theme-support' /*adm*/); ?>
</div><br />
<?php
	}

	if (get_the_ID() == get_option( 'page_for_posts' ) ) { ?>
<div style="padding:2px; border:2px solid red; background:#FAA;">
<?php _e('<strong>WARNING!</strong>
You have the <em>Dashboard:Settings:Reading Posts page:</em> option set to this page.
You may intend to do this, but note this means that <em>only</em> this page\'s Title will be used
on the default WordPress blog page, and any content you may have entered above is <em>not</em> used.
If you want this page to serve as your blog page, and enable Weaver Xtreme Per Page options,
including the option of using the Page with Posts page template,
then the <em>Settings:Reading:Posts page</em> selection <strong>must</strong> be set to
the <em></em>&mdash; Select &mdash;</em> default value.','weaverx-theme-support' /*adm*/); ?>
</div><br />
<?php
		return;
	}
	echo '<strong>' . __('Page Templates','weaverx-theme-support' /*adm*/) . '</strong>';
	weaverx_help_link('help.html#PageTemplates',__('Help for Weaver Xtreme Page Templates','weaverx-theme-support' /*adm*/));
	echo '<span style="float:right;">(' . __('This Page\'s ID: ','weaverx-theme-support' /*adm*/); the_ID() ; echo ')</span>';
	weaverx_html_br();
	_e('Please click the (?) for more information about all the Weaver Xtreme Page Templates.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();

	$template = !empty($post->page_template) ? $post->page_template : "Default Template";
	$raw_template = in_array($template, array('paget-raw.php'));

	echo '<strong>' . __('Per Page Options','weaverx-theme-support' /*adm*/) . '</strong>';
	weaverx_help_link('help.html#optsperpage', __('Help for Per Page Options','weaverx-theme-support' /*adm*/));
	weaverx_html_br();


	if (!$raw_template) {
	_e('These settings let you hide various elements on a per page basis.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();


	wvrx_ts_page_checkbox('_pp_hide_site_title',__('Hide Site Title/Tagline','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_header_image',__('Hide Standard Header Image','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_header',__('Hide Entire Header','weaverx-theme-support' /*adm*/), 33, 1);

	wvrx_ts_page_checkbox('_pp_hide_menus',__('Hide Menus','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_page_infobar',__('Hide Info Bar on this page','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_footer',__('Hide Entire Footer','weaverx-theme-support' /*adm*/),33,1);

	wvrx_ts_page_checkbox('_pp_hide_page_title',__('Hide Page Title','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_full_browser_height',__('Force full browser height','weaverx-theme-support' /*adm*/),33,2);

	wvrx_ts_page_cols();
	} // not raw

	_e('<em>Note:</em> the following options work with the default menu - not custom menus.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();
	wvrx_ts_page_checkbox('_pp_hide_on_menu',__('Hide Page on the default Primary Menu','weaverx-theme-support' /*adm*/),90,1);



	wvrx_ts_page_checkbox('_pp_stay_on_page',__('Menu "Placeholder" page. Useful for top-level menu item - don\'t go anywhere when menu item is clicked.','weaverx-theme-support' /*adm*/),90,2);

	wvrx_ts_page_checkbox('_pp_hide_visual_editor',__('Disable Visual Editor for this page. Useful if you enter simple HTML or other code.','weaverx-theme-support' /*adm*/),90,1);

	if (weaverx_allow_multisite()) {
		wvrx_ts_page_checkbox('_pp_raw_html',__('Allow Raw HTML and scripts. Disables auto paragraph, texturize, and other processing.','weaverx-theme-support' /*adm*/),90,1);
	}

	if (!$raw_template) {
?>
	<p><strong><?php _e('Sidebars &amp; Widgets','weaverx-theme-support' /*adm*/); ?></strong></p>

<?php
	wvrx_ts_page_layout();
?>
<br />
	<input type="text" size="4" id="_pp_category" name="_pp_sidebar_width"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_sidebar_width", true)); ?>" />
	<?php _e('% &nbsp;- <em>Sidebar Width</em> - Per Page Sidebar width (applies to all layouts)','weaverx-theme-support' /*adm*/); ?> <br /><br />
<?php

	wvrx_ts_page_checkbox('_pp_primary-widget-area',__('Hide Primary Sidebar','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_secondary-widget-area',__('Hide Secondary Sidebar','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_sitewide-top-widget-area',__('Hide Sitewide Top Area','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_sitewide-bottom-widget-area',__('Hide Sitewide Bottom Area','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_top-widget-area',__('Hide Pages Top Area','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_bottom-widget-area',__('Hide Pages Bottom Area','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_header-widget-area',__('Hide Header Area','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_footer-widget-area',__('Hide Footer Area','weaverx-theme-support' /*adm*/),40,1);
?>

	<p><strong><?php _e('Widget Area Replacements','weaverx-theme-support' /*adm*/); ?></strong></p>
	<p>
<?php _e('Select extra widget areas to replace the default widget areas for this page.
To add areas to the widget area lists below, you <strong>must</strong> first define extra widget areas on the bottom of the <em>Main Options &rarr; Sidebars &amp; Layout</em> tab.','weaverx-theme-support' /*adm*/); ?>
	</p>
<?php
	wvrx_ts_pp_replacement( __('Primary Sidebar','weaverx-theme-support' /*adm*/) , 'primary-widget-area' );
	wvrx_ts_pp_replacement( __('Secondary Sidebar','weaverx-theme-support' /*adm*/) , 'secondary-widget-area' );

	wvrx_ts_pp_replacement( __('Header Widget Area','weaverx-theme-support' /*adm*/) , 'header-widget-area' );
	wvrx_ts_pp_replacement( __('Footer Widget Area','weaverx-theme-support' /*adm*/) , 'footer-widget-area' );

	wvrx_ts_pp_replacement( __('Sitewide Top Widget Area','weaverx-theme-support' /*adm*/) , 'sitewide-top-widget-area' );
	wvrx_ts_pp_replacement( __('Sitewide Bottom Widget Area','weaverx-theme-support' /*adm*/) , 'sitewide-bottom-widget-area' );

	wvrx_ts_pp_replacement( __('Pages Top Widget Area','weaverx-theme-support' /*adm*/) , 'page-top-widget-area' );
	wvrx_ts_pp_replacement( __('Pages Bottom Widget Area','weaverx-theme-support' /*adm*/) , 'page-bottom-widget-area' );
?>
	<br style="clear:both;" /><p><strong><?php _e('Featured Image','weaverx-theme-support' /*adm*/); ?></strong></p>
<?php
	$opts3 = array(  'id' => '_pp_fi_location',
		'info' => __('How to display Page FI on this page','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => __('Default Page FI','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-top', 'desc' => __('With Content - top','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-bottom', 'desc' => __('With Content - bottom','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-before', 'desc' => __('Before Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'header-image', 'desc' => __('Header Image Replacement','weaverx-theme-support' /*adm*/) ),
			array('val' => 'hide', 'desc' => __('Hide FI on this Page','weaverx-theme-support' /*adm*/) )
			)
		);
	wvrx_ts_pp_select_id($opts3);
?>
<br />
<input type="text" size="30" id='_pp_fi_link' name='_pp_fi_link'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_fi_link', true)); ?>" />
<?php _e('<em>Featured Image Link</em> - Full URL to override default link target from FI','weaverx-theme-support' /*adm*/); ?>
	<br style="clear:both;" />
	<hr />
	<input type="text" size="15" id="bodyclass" name="_pp_bodyclass"
		value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_bodyclass", true)); ?>" />

	<?php _e('<em>Per Page body Class</em> - CSS class name to add to HTML &lt;body&gt; block. Allows Per Page custom styling.','weaverx-theme-support' /*adm*/); ?>
	<br />
</p>
<p>
<?php

	_e('<strong>Settings for "Page with Posts" Template</strong>','weaverx-theme-support' /*adm*/);
	weaverx_help_link('help.html#PerPostTemplate',__('Help for Page with Posts Template','weaverx-theme-support' /*adm*/) );

	$template = !empty($post->page_template) ? $post->page_template : "Default Template";
	if (in_array($template, apply_filters('weaverx_paget_posts', array('paget-posts.php'))) ) {
	?>
	<br />
<?php _e('These settings are optional, and can filter which posts are displayed when you use the "Page with Posts" template.
Use commas to separate items in lists.
The settings will be combined for the final filtered list of posts displayed.
(If you make mistakes in your settings, it won\'t be apparent until you display the page.)','weaverx-theme-support' /*adm*/); ?>
<br />

	<input type="text" size="30" id="_pp_category" name="_pp_category"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_category", true)); ?>" />
	<?php _e('<em>Category</em> - Enter list of category slugs of posts to include. (-slug will exclude specified category)','weaverx-theme-support' /*adm*/); ?>
	<br />

	<input type="text" size="30" id="_pp_tag" name="_pp_tag"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_tag", true)); ?>" />
	<?php _e("<em>Tags</em> - Enter list of tag slugs of posts to include.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_onepost" name="_pp_onepost"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_onepost", true)); ?>" />
	<?php _e("<em>Single Post</em> - Enter post slug of a single post to display.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_orderby" name="_pp_orderby"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_orderby", true)); ?>" />
	<?php _e("<em>Order by</em> - Enter method to order posts by: author, date, title, or rand.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_sort_order" name="_pp_sort_order"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_sort_order", true)); ?>" />
	<?php _e("<em>Sort order</em> - Enter ASC or DESC for sort order.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_posts_per_page" name="_pp_posts_per_page"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_posts_per_page", true)); ?>" />
	<?php _e("<em>Posts per Page</em> - Enter maximum number of posts per page.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_author" name="_pp_author"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_author", true)); ?>" />
	<?php _e('<em>Author</em> - Enter author (use username, including spaces), or list of author IDs','weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_post_type" name="_pp_post_type"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_post_type", true)); ?>" />
	<?php _e('<em>Custom Post Type</em> - Enter slug of one custom post type to display','weaverx-theme-support' /*adm*/); ?> <br />

	<?php wvrx_ts_pwp_atw_show_post_filter(); ?>

	<?php wvrx_ts_pwp_type(); ?><br />
	<?php wvrx_ts_pwp_cols(); ?><br />
	<input type="text" size="5" id="_pp_fullposts" name="_pp_fullposts"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_fullposts", true)); ?>" />
	<?php _e("<em>Don't excerpt 1st <em>\"n\"</em> Posts</em> - Display the non-excerpted post for the first \"n\" posts.",'weaverx-theme-support' /*adm*/); ?>
	<br />

	<input type="text" size="5" id="_pp_hide_n_posts" name="_pp_hide_n_posts"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_hide_n_posts", true)); ?>" />
	<?php echo "<em><span class=\"dashicons dashicons-visibility\"></span>" .
__("Hide first \"n\" posts</em> - Start with post n+1.
Useful with plugin that will display first n posts using a shortcode. (e.g., Post slider)",'weaverx-theme-support' /*adm*/) ; ?>

	<br /><br />

	<?php wvrx_ts_page_checkbox('_pp_hide_infotop',__('Hide top info line','weaverx-theme-support' /*adm*/), 40); ?>
	<?php wvrx_ts_page_checkbox('_pp_hide_infobottom',__('Hide bottom info line','weaverx-theme-support' /*adm*/), 40, 1); ?>
	<?php wvrx_ts_page_checkbox('_pp_hide_sticky',__('No special treatment for Sticky Posts','weaverx-theme-support' /*adm*/), 40); ?>
</p>
<?php
	} else {	// NOT a page with posts
?>	<p>
<?php _e('<strong>Note:</strong> After you choose the "Page with Posts" template from the <em>Template</em>
option in the <em>Page Attributes</em> box, <strong>and</strong> <em>Publish</em> or <em>Save Draft</em>,
settings for "Page with Posts" will be displayed here. Current page template:','weaverx-theme-support' /*adm*/); ?>
<?php echo $template; ?>
	</p>
<?php
	}
	} else { // raw page template handling
		echo '<p>';
		_e('<strong>You are using the RAW page template.</strong><br /><ol>
<li>Check the "Allow Raw HTML" option above to prevent WP processing of your content for this page. If you leave it
unchecked, you will get the WP paragraph and texturize processing.</li>
<li>You can add custom HTML code to include in the &lt;head&gt; block by defining a Custom Field named <em>page-head-code</em>
and including that HTML code in the Value for that field.</li></ol>', 'weaverx-theme-support');
		echo '<p>';
	}
		do_action('weaverxplus_add_per_page');
?>
	<input type='hidden' id='post_meta' name='post_meta' value='post_meta'/>
	</div>
<?php
}

function wvrx_ts_post_extras_pt() {
	// special handling for non-Weaver Custom Post Types
	$opts = get_option( apply_filters('weaverx_options','weaverx_settings') , array());
	if ((isset($opts['_show_per_post_all']) && $opts['_show_per_post_all']) || function_exists('atw_slider_plugins_loaded') )
		wvrx_ts_post_extras();
	else {
		echo '<p>' .
__('You can enable Weaver Xtreme Per Post Options for Custom Post Types on the Weaver Xtreme:Advanced Options:Admin Options tab.','weaverx-theme-support' /*adm*/) .
		'</p>';
	}
}

function wvrx_ts_post_extras() {
	global $post;
	$opts = get_option( apply_filters('weaverx_options','weaverx_settings') , array());	// need to fetch Weaver Xtreme options
	if ( !( current_user_can('edit_themes')
		|| (current_user_can('edit_theme_options') && !isset($opts['_hide_mu_admin_per']))	// multi-site regular admin
		|| (current_user_can('edit_pages') && !isset($opts['_hide_editor_per']))	// Editor
		|| (current_user_can('edit_posts') && !isset($opts['_hide_author_per']))) // Author/Contributor
	   ) {
		echo '<p>' . __('Weaver Xtreme Per Post Options not available for your User Role.','weaverx-theme-support' /*adm*/) . '</p>';
		return;	// don't show per post panel
	   }
?>
<div style="line-height:150%;">
<p>
	<?php
	echo '<strong>' . __('Per Post Options','weaverx-theme-support' /*adm*/) . '</strong>';
	weaverx_help_link('help.html#PerPage', __('Help for Per Post Options','weaverx-theme-support' /*adm*/));
	echo '<span style="float:right;">(' . __('This Post\'s ID: ','weaverx-theme-support' /*adm*/); the_ID() ; echo ')</span>';
	weaverx_html_br();
	_e('These settings let you control display of this individual post. Many of these options override global options set on the Weaver Xtreme admin tabs.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();

	wvrx_ts_page_checkbox('_pp_force_post_excerpt',__('Display post as excerpt','weaverx-theme-support' /*adm*/), 40);
	wvrx_ts_page_checkbox('_pp_force_post_full',__('Display as full post where normally excerpted','weaverx-theme-support' /*adm*/),55,1);


	wvrx_ts_page_checkbox('_pp_show_post_avatar',__('Show author avatar with post','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_show_post_bubble',__('Show the comment bubble','weaverx-theme-support' /*adm*/), 40, 1);

	wvrx_ts_page_checkbox('_pp_hide_post_format_label',__('Hide <em>Post Format</em> label','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_hide_post_title',__('Hide post title','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_hide_top_post_meta',__('Hide top post info line','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_hide_bottom_post_meta',__('Hide bottom post info line','weaverx-theme-support' /*adm*/),40,1);
	wvrx_ts_page_checkbox('_pp_masonry_span2',__('For <em>Masonry</em> multi-columns: make this post span two columns.','weaverx-theme-support' /*adm*/),90,1);

	wvrx_ts_page_checkbox('_pp_post_add_link',__('Show a "link to single page" icon at bottom of post - useful with compact posts','weaverx-theme-support' /*adm*/),90);


	echo('<br style="clear:both;"/><br /><strong>Per Post Style</strong> (&starf;Plus)' /*a*/ );
	weaverx_help_link('help.html#perpoststyle', __('Help for Per Post Style','weaverx-theme-support' /*adm*/ ));
	echo '<br />' .
__('Weaver Xtreme Plus supports optional per post CSS style rules.','weaverx-theme-support' /*adm*/); ?>
<br />
<br />
<p><strong><?php _e('<em>Single Page View:</em> Sidebars','weaverx-theme-support' /*adm*/); ?></strong></p>

<?php
	wvrx_ts_page_layout('post');
?>
<br />
	<input type="text" size="4" id="_pp_category" name="_pp_sidebar_width"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_sidebar_width", true)); ?>" />
	<?php _e("% &nbsp;- <em>Sidebar Width</em> - Post Single View Sidebar width (applies to all layouts)",'weaverx-theme-support' /*adm*/); ?> <br /><br />
<?php

	wvrx_ts_page_checkbox('_pp_primary-widget-area',__('Hide Primary Sidebar, Single View','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_secondary-widget-area',__('Hide Secondary Sidebar, Single View','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_sitewide-top-widget-area',__('Hide Sitewide Top Area, Single View','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_sitewide-bottom-widget-area',__('Hide Sitewide Bottom Area, Single View','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_top-widget-area',__('Hide Blog Top Area, Single View','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_bottom-widget-area',__('Hide Blog Bottom Area, Single View','weaverx-theme-support' /*adm*/),40,1);

	wvrx_ts_page_checkbox('_pp_header-widget-area',__('Hide Header Area, Single View','weaverx-theme-support' /*adm*/),40);
	wvrx_ts_page_checkbox('_pp_footer-widget-area',__('Hide Footer Area, Single View','weaverx-theme-support' /*adm*/),40,1);
?>
</p>
<p><strong><?php _e('<em>Single Page View:</em> Widget Area Replacements','weaverx-theme-support' /*adm*/); ?></strong></p>
<p>
<?php _e('Select extra widget areas to replace the default widget areas for <em>Single Page</em> view of this post.
To add areas to the widget area lists below, you <strong>must</strong> first define extra widget areas on the bottom of the <em>Main Options &rarr; Sidebars &amp; Layout</em> tab.','weaverx-theme-support' /*adm*/); ?>
</p>
<?php
	wvrx_ts_pp_replacement( __('Primary Sidebar','weaverx-theme-support' /*adm*/) , 'primary-widget-area' );
	wvrx_ts_pp_replacement( __('Secondary Sidebar','weaverx-theme-support' /*adm*/) , 'secondary-widget-area' );

	wvrx_ts_pp_replacement( __('Header Widget Area','weaverx-theme-support' /*adm*/) , 'header-widget-area' );
	wvrx_ts_pp_replacement( __('Footer Widget Area','weaverx-theme-support' /*adm*/) , 'footer-widget-area' );

	wvrx_ts_pp_replacement( 'Sitewide Top Widget Area' , 'sitewide-top-widget-area' );
	wvrx_ts_pp_replacement( 'Sitewide Bottom Widget Area' , 'sitewide-bottom-widget-area' );
?>
<br style="clear:both;" /><p><strong><?php _e('<em>Single Page View:</em> Featured Image','weaverx-theme-support' /*adm*/); ?></strong></p>
<?php
	$opts3 = array(  'id' => '_pp_fi_location',
		'info' => __('Override <em>Single Page</em> setting for where to display FI','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => __('Default Single Page FI','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-top', 'desc' => __('With Content - top','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-bottom', 'desc' => __('With Content - bottom','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-before', 'desc' => __('Before Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'header-image', 'desc' => __('Header Image Replacement','weaverx-theme-support' /*adm*/) ),
			array('val' => 'post-before', 'desc' => __('Outside of Post','weaverx-theme-support' /*adm*/) ),
			array('val' => 'hide', 'desc' => __('Hide FI on Single Page','weaverx-theme-support' /*adm*/) )
			)
		);
	wvrx_ts_pp_select_id($opts3);
?>
<br />
<input type="text" size="30" id='_pp_fi_link' name='_pp_fi_link'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_fi_link', true)); ?>" />
	<?php _e("<em>Featured Image Link</em> - Full URL to override default link target from FI",'weaverx-theme-support' /*adm*/); ?>
	<br style="clear:both;" />
	</p><p>
	<strong><?php _e('Post Editor Options','weaverx-theme-support' /*adm*/); ?></strong>

<?php
	wvrx_ts_page_checkbox('_pp_hide_visual_editor',__('Disable Visual Editor for this page. Useful if you enter simple HTML or other code.','weaverx-theme-support' /*adm*/),90,  1);

	if (weaverx_allow_multisite()) {
		wvrx_ts_page_checkbox('_pp_raw_html',__('Allow Raw HTML and scripts. Disables auto paragraph, texturize, and other processing.','weaverx-theme-support' /*adm*/),90, 1);
	}
	?>
</p>
<p>
	<?php echo('<strong>Post Format</strong>');
	weaverx_help_link('help.html#gallerypost', __('Help for Per Post Format','weaverx-theme-support' /*adm*/));
	weaverx_html_br();
	_e('Weaver Xtreme supports Post Formats. Click the ? for more info.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();
	weaverx_html_br();

	do_action('weaverxplus_add_per_post'); ?>
</p>
	<input type='hidden' id='post_meta' name='post_meta' value='post_meta'/>
</div>
<?php
}


function wvrx_ts_save_post_fields($post_id) {
	$default_post_fields = array('_pp_category', '_pp_tag', '_pp_onepost', '_pp_orderby', '_pp_sort_order',
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
	'_sitewide-bottom-widget-area', '_page-top-widget-area', '_page-bottom-widget-area', '_pp_full_browser_height',
	'_pp_page_cols'
	);

if (weaverx_allow_multisite()) {
	array_push($default_post_fields, '_pp_raw_html');
}

	$all_post_fields = $default_post_fields;

	if (isset($_POST['post_meta'])) {
		foreach ($all_post_fields as $post_field) {
			if (isset($_POST[$post_field])) {
				$data = $_POST[$post_field];
				if ( $post_field != '_pp_post_styles')
					$data = stripslashes($data);		// passed via post, so strip slashes

				if (get_post_meta($post_id, $post_field) == '') {
					add_post_meta($post_id, $post_field, weaverx_filter_textarea($data), true);
				}
				else if ($data != get_post_meta($post_id, $post_field, true)) {
					update_post_meta($post_id, $post_field, weaverx_filter_textarea($data));
				} else if ($data == '') {
					delete_post_meta($post_id, $post_field, get_post_meta($post_id, $post_field, true));
				}
			} else {
				delete_post_meta($post_id, $post_field, get_post_meta($post_id, $post_field, true));
			}
		}
	}
}

add_action("save_post", "wvrx_ts_save_post_fields");
add_action("publish_post", "wvrx_ts_save_post_fields");
}
?>
