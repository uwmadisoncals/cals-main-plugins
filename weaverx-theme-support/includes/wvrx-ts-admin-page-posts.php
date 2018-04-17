<?php
/* Display per page and per post options.
 *  IMPORTANT - there are dependencies with the Weaver Xtreme Plugin
 */

if ( !defined('ABSPATH')) exit; // Exit if accessed directly
// Admin panel that gets added to the page edit page for per page options


function wvrx_ts_isp_true($val) {
	if ($val) return true;
	return false;
}

function wvrx_ts_page_extras() {				// ------------------------------- Display Per Page Options on WP Page editor -------------------
	global $post;
	$func_opt = WEAVER_GET_OPTION;
	$opts = $func_opt( apply_filters('weaverx_options','weaverx_settings') , array());	// need to fetch Weaver Xtreme options

	if ( !( current_user_can('edit_themes')
		|| (current_user_can('edit_theme_options') && !isset($opts['_hide_mu_admin_per']))	// multi-site regular admin
		|| (current_user_can('edit_pages') && !isset($opts['_hide_editor_per']))	// Editor
		|| (current_user_can('edit_posts') && !isset($opts['_hide_author_per'])))    // Author/Contributor
	) {
		if (isset($opts['_show_per_post_all']) && $opts['_show_per_post_all'])
			echo '<p>' .
__('You can enable Weaver Xtreme Per Page Options for Custom Post Types on the Weaver Xtreme:Advanced Options:Admin Options tab.','weaverx-theme-support' /*adm*/) .
		'</p>';
		else
			echo '<p>' . __('Weaver Xtreme Per Page Options not available for your User Role.','weaverx-theme-support' /*adm*/) . '</p>';

		return;	// don't show per post panel
	   }

	echo("<div style=\"line-height:150%;border: 1px solid $888;list-style-type: none;\"><p>\n");
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
the <em>&mdash; Select &mdash;</em> default value.','weaverx-theme-support' /*adm*/); ?>
</div><br />
<?php
		return;
	}

	$updir = wp_upload_dir();
	$dir = trailingslashit($updir['basedir']) . 'weaverx-subthemes/editor-style-wvrx.css';

	if (!@file_exists( $dir ))  { ?>
<div style="padding:2px; border:2px solid red; background:#FAA;">
<?php _e('<strong>Note!</strong>
Please open the <em>Appearance:Weaver Xtreme Admin:Main Options</em> page and <em>Save Settings</em> to enable full editor theme match styling.','weaverx-theme-support' /*adm*/); ?>
</div><br />
<?php
	}

	echo '<strong>' . __('Page Templates','weaverx-theme-support' /*adm*/) . '</strong>';
	weaverx_help_link('help.html#PageTemplates',__('Help for Weaver Xtreme Page Templates','weaverx-theme-support' /*adm*/));
	echo '<span style="float:right;">(' . __('This Page\'s ID: ','weaverx-theme-support' /*adm*/); the_ID() ; echo ')</span>';
	weaverx_html_br();
	_e('Please click the (?) for more information about all the Weaver Xtreme Page Templates.','weaverx-theme-support' /*adm*/);
	if ( WVRX_TS_PAGEBUILDERS && ! ! get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) )
	{
		weaverx_html_br();
		_e('<strong>NOTE:</strong> This page has been created with <em>Elementor</em>. You may want to consider using the <em>For Your Page Builder Plugin</em> Page Template to display this page.', 'weaver-core-theme-support');
	}

	$template = !empty($post->page_template) ? $post->page_template : "Default Template";
	$raw_template = in_array($template, array('paget-raw.php'));

	weaverx_html_br();

	wrvx_ts_showtabs($raw_template, $template);
?>
	<div style="clear:both;"></div>
	<input type='hidden' id='post_meta' name='post_meta' value='post_meta'/>
	</div>
<?php
}

function wrvx_ts_showtabs( $raw_template = false, $template = 'default' ) {
?>
<div id="tabwrap-per-page" style="padding-left:4px;">
<div id="tab-container-page" style="border:1px solid #888;padding: 2px 1% 2px 1%; width:97.9%;" class='yetiisub'>
	<ul id="tab-container-page-nav" style="border-bottom:2px solid #999;" class='yetiisub'>
	<?php
	wvrx_ts_elink('#pp-visibility' , __('Per Page Options affecting element visibility.', 'weaver-xtreme' /*adm*/), __('Visibility', 'weaver-xtreme' /*adm*/),'<li>','</li>');
	wvrx_ts_elink('#pp-layout' , __('Settings for Per Page Layout', 'weaver-xtreme' /*adm*/), __('Layout', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-content' , __('Settings to control content display.', 'weaver-xtreme' /*adm*/), __('Content', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-misc' , __('Misc per page options', 'weaver-xtreme' /*adm*/), __('Misc Options', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-pwp' , __('Settings for Page with Posts.', 'weaver-xtreme' /*adm*/), __('Page with Posts','weaver-xtreme'  /*adm*/),'<li>', '</li>');
	 if (WVRX_TS_PAGEBUILDERS) wvrx_ts_elink('#pp-builder' , __('Settings for Page Builders.', 'weaver-xtreme' /*adm*/), __('Page Builders','weaver-xtreme'  /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-xplus' , __('Per Page settings from Weaver Xtreme Plus.', 'weaver-xtreme' /*adm*/), __('Xtreme Plus', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	?>
	</ul>

	<div id="pp-visibility" class="tab_mainopt" >  <!-- ******************* visibility ********************* -->
		<?php
		wvrx_ts_page_visibility( $raw_template );
		?>
	</div>											<!-- ******************* /visibility ********************* -->

	<div id="pp-layout" class="tab_mainopt" >		<!-- ******************* layout ********************* -->
		<?php
		wvrx_ts_pg_layout( $raw_template );
		?>
	</div>											<!-- ******************* /layout ********************* -->


	<div id="pp-content" class="tab_mainopt" >		<!-- ******************* content ********************* -->
		<?php
		wvrx_ts_page_content( $raw_template );
		?>
	</div>											<!-- ******************* /content ********************* -->

	<div id="pp-misc" class="tab_mainopt" >			<!-- ******************* misc ********************* -->
	<?php wvrx_ts_page_misc_opts( $raw_template ); ?>
	</div>											<!-- ******************* /misc ********************* -->


	<div id="pp-pwp" class="tab_mainopt" > 			<!-- ******************* PWP ********************* -->
	<?php wvrx_ts_page_with_posts_opts( $raw_template, $template ); ?>
	</div>											<!-- ******************* /PWP ********************* -->
<?php if (WVRX_TS_PAGEBUILDERS) : ?>
	<div id="pp-builder" class="tab_mainopt" > 			<!-- ******************* PWP ********************* -->
	<?php wvrx_ts_page_builder_opts( $raw_template ); ?>
	</div>											<!-- ******************* /PWP ********************* -->
<?php endif; ?>

	<div id="pp-xplus" class="tab_mainopt" >		<!-- ******************* X-Plus ********************* -->
	<?php wvrx_ts_page_xtreme_plus( $raw_template );?>
	</div>											<!-- ******************* X-Plus ********************* -->


</div> <!-- #tab-container-page -->
</div>	<!-- #tabwrap-per-page -->

<script type="text/javascript">
	var tabberMainOpts = new Yetii({
	id: 'tab-container-page',
	tabclass: 'tab_mainopt',
	persist: true
	});
</script>
<?php
}

function wvrx_ts_elink( $href, $title, $label, $before='', $after='') {
	echo $before . '<a href="' . esc_url($href) . '" title="' . $title . '">' . $label . '</a>' . $after;
}

function wvrx_ts_page_visibility( $raw_template = false ) {
	global $post;

	echo '<span style="font-weight:bold;font-size:140%;">' . __('Visibility','weaverx-theme-support' /*adm*/) . '</span>';
	weaverx_help_link('help.html#optsperpage', __('Help for Per Page Options','weaverx-theme-support' /*adm*/)) ;

	weaverx_html_br();
	if ( $raw_template) {
		_e('Not options when using the RAW Page Template', 'weaverx-theme-support');
		return;
	}

	_e('These settings let you hide various elements on a per page basis.','weaverx-theme-support' /*adm*/);

	wvrx_ts_page_title( __('Header Area Visibility','weaverx-theme-support' /*adm*/),80,1);

	wvrx_ts_page_checkbox('_pp_hide_header',__('Hide Entire Header','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_site_title',__('Hide Site Title/Tagline','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_header_image',__('Hide Standard Header Image','weaverx-theme-support' /*adm*/), 33, 1);

	wvrx_ts_page_checkbox('_pp_hide_header_html',__('Hide Header HTML Area','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_customlogo',__('Hide Custom Logo','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_headersearch',__('Hide Header Search','weaverx-theme-support' /*adm*/), 33,1);

	wvrx_ts_page_checkbox('_pp_hide_menus',__('Hide Header Menus','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_mini_menu',__('Hide Header Mini Menu','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_header-widget-area',__('Hide Header Widget Area','weaverx-theme-support' /*adm*/),33,2);


	wvrx_ts_page_title( __('Footer Area Visibility','weaverx-theme-support' /*adm*/),80,1);

	wvrx_ts_page_checkbox('_pp_hide_footer',__('Hide Entire Footer','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_footer_html',__('Hide Footer HTML Area','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_footer-widget-area',__('Hide Footer Widget Area','weaverx-theme-support' /*adm*/),33,2);


	wvrx_ts_page_title( __('Content Area Visibility','weaverx-theme-support' /*adm*/),80,1);

	wvrx_ts_page_checkbox('_pp_hide_page_infobar',__('Hide Info Bar on this page','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_hide_page_title',__('Hide Page Title','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_full_browser_height',__('Force full browser height','weaverx-theme-support' /*adm*/),33,2);


	wvrx_ts_page_title( __('Page Widget Areas Visibility','weaverx-theme-support' /*adm*/),80,1);

	wvrx_ts_page_checkbox('_pp_primary-widget-area',__('Hide Primary Sidebar','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_secondary-widget-area',__('Hide Secondary Sidebar','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_sitewide-top-widget-area',__('Hide Sitewide Top Area','weaverx-theme-support' /*adm*/),33,1);

	wvrx_ts_page_checkbox('_pp_sitewide-bottom-widget-area',__('Hide Sitewide Bottom Area','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_top-widget-area',__('Hide Pages Top Area','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_bottom-widget-area',__('Hide Pages Bottom Area','weaverx-theme-support' /*adm*/),33,2);

}


function wvrx_ts_pg_layout( $raw_template = false ) {		// ------------------------------- Page Layout -------------------
	global $post;

	echo '<span style="font-weight:bold;font-size:140%;">' . __('Layout','weaverx-theme-support' /*adm*/) . '</span><br/>';
	_e('These settings let you control layout on a per page basis.','weaverx-theme-support' /*adm*/);

	weaverx_html_br(2);
	if ( $raw_template) {
		_e('Not options when using the RAW Page Template', 'weaverx-theme-support');
		return;
	}


	wvrx_ts_page_title( __('Featured Image','weaverx-theme-support' /*adm*/),80,1);

	$opts3 = array(  'id' => '_pp_fi_location',
		'info' => __('How to display Page FI on this page','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => __('Default Blog FI','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-top', 'desc' => __('With Content - top','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-bottom', 'desc' => __('With Content - bottom','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-before', 'desc' => __('With Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-banner', 'desc' => __('Banner above Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'header-image', 'desc' => __('Header Image Replacement','weaverx-theme-support' /*adm*/) ),
			array('val' => 'post-before', 'desc' => __('Beside Page, no wrap', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg', 'desc' => __('As BG Image, Tile', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-cover', 'desc' => __('As BG Image, Cover', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-parallax', 'desc' => __('As BG Image, Parallax', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'hide', 'desc' => __('Hide FI for this Post','weaverx-theme-support' /*adm*/) )
			)
		);
	wvrx_ts_pp_select_id($opts3);

?>
<br />
<input type="text" size="30" id='_pp_fi_link' name='_pp_fi_link'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_fi_link', true)); ?>" />
<?php _e('<em>Featured Image Link</em> - Full URL to override default link target from FI','weaverx-theme-support' /*adm*/); ?>
	<br style="clear:both;" />
<br />

<?php
	wvrx_ts_page_title( __('Full Width - Extend Areas to Full Width Display','weaverx-theme-support' /*adm*/),80,1);
	wvrx_ts_page_checkbox('_pp_header_full',__('Full Width Header','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_container_full',__('Full Width Container','weaverx-theme-support' /*adm*/));
	wvrx_ts_page_checkbox('_pp_footer_full',__('Full Width Footer','weaverx-theme-support' /*adm*/),33,2);


	wvrx_ts_page_title( __('Content Columns','weaverx-theme-support' /*adm*/),80,1);
	wvrx_ts_page_cols();
	wvrx_ts_page_title( __('Sidebars &amp; Widgets','weaverx-theme-support' /*adm*/),80,1);
	wvrx_ts_page_layout();
?>
<br />
	<input type="text" size="4" id="_pp_sidebar_width" name="_pp_sidebar_width"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_sidebar_width", true)); ?>" />
	<?php _e('% &nbsp;- <em>Sidebar Width</em> - Per Page Sidebar width (applies to all layouts)','weaverx-theme-support' /*adm*/); ?> <br /><br />
<?php
}


function wvrx_ts_page_content( $raw_template = false ) {		// ------------------------------- Page Content -------------------
	global $post;

	echo '<span style="font-weight:bold;font-size:140%;">' . __('Per Page Content Replacemant','weaverx-theme-support' /*adm*/) . '</span><br/>';
	_e('These settings let you replace widget areas, as well as HTML content in the header and footer.','weaverx-theme-support' /*adm*/);
	weaverx_html_br(2);
	if ( $raw_template) {
		_e('Not options when using the RAW Page Template', 'weaverx-theme-support');
		return;
	}
	if (WVRX_TS_PAGEBUILDERS) :
	wvrx_ts_page_title( __('Header / Footer HTML Content','weaverx-theme-support' /*adm*/),80,2);
	_e('Specify Per Page Header / Footer HTML content. If you provide just a page or post id number, the HTML area will be filled with the content of the page or post. This is especially usefule for content created with a page builder plugin.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();
	_e('Header HTML: ','weaverx-theme-support' /*adm*/);
?>
	&nbsp;<textarea class="wvrx-edit" placeholder=" " name="_pp_header_html" rows=1 style="width: 75%"><?php echo(get_post_meta($post->ID, "_pp_header_html", true)); ?></textarea>

<?php
	weaverx_html_br();
	_e('Footer HTML: ','weaverx-theme-support' /*adm*/);
?>
	&nbsp;&nbsp;<textarea class="wvrx-edit" placeholder=" " name="_pp_footer_html" rows=1 style="width: 75%"><?php echo(get_post_meta($post->ID, "_pp_footer_html", true)); ?></textarea>

<?php
endif;
	weaverx_html_br(2);
	wvrx_ts_page_title( __('Widget Area Replacements','weaverx-theme-support' /*adm*/),80,1);
?>
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
<div style="clear:both;"></div>
<?php
}

function wvrx_ts_page_misc_opts( $raw_template = false ) {		// ------------------------------- Misc Options -------------------
	global $post;
	echo '<span style="font-weight:bold;font-size:140%;">' . __('Misc Menu, Editor, and CSS Options','weaverx-theme-support' /*adm*/) . '</span><br/>';

	//wvrx_ts_page_title( __('Misc Menu, Editor, and CSS Options','weaverx-theme-support' /*adm*/),80,1);

	wvrx_ts_page_checkbox('_pp_hide_on_menu',__('Hide Page on the default Primary Menu','weaverx-theme-support' /*adm*/),90,1);

	wvrx_ts_page_checkbox('_pp_stay_on_page',__('Menu "Placeholder" page. Useful for top-level menu item - don\'t go anywhere when menu item is clicked.','weaverx-theme-support' /*adm*/),90,1);

	wvrx_ts_page_checkbox('_pp_hide_visual_editor',__('Disable Visual Editor for this page. Useful if you enter simple HTML or other code.','weaverx-theme-support' /*adm*/),90,1);

	if (weaverx_allow_multisite()) {
		wvrx_ts_page_checkbox('_pp_raw_html',__('Allow Raw HTML and scripts. Disables auto paragraph, texturize, and other processing.','weaverx-theme-support' /*adm*/),90,1);
	}
?>
<p>
	<input type="text" size="25" id="bodyclass" name="_pp_bodyclass"
		value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_bodyclass", true)); ?>" />

	<?php _e('<em>Per Page body Class</em> - CSS class name to add to HTML &lt;body&gt; block. Allows Per Page custom styling.','weaverx-theme-support' /*adm*/); ?>
</p>
<?php
	if ( $raw_template) {
		echo '<p>';
				_e('<strong>You are using the RAW page template.</strong><br /><ol>
		<li>Check the "Allow Raw HTML" option above to prevent WP processing of your content for this page. If you leave it
		unchecked, you will get the WP paragraph and texturize processing.</li>
		<li>You can add custom HTML code to include in the &lt;head&gt; block by defining a Custom Field named <em>page-head-code</em>
		and including that HTML code in the Value for that field.</li></ol>', 'weaverx-theme-support');
				echo '</p>';
	}
}



function wvrx_ts_page_with_posts_opts( $raw_template, $template ) {		// ------------------------------- Page with Post Options -------------------
	global $post;
?>
<span style="font-weight:bold;font-size:120%;">
<?php
	_e('Settings for "Page with Posts" Template','weaverx-theme-support' /*adm*/); echo "</span>";
	weaverx_help_link('help.html#PerPostTemplate',__('Help for Page with Posts Template','weaverx-theme-support' /*adm*/) );

	$template = !empty($post->page_template) ? $post->page_template : "Default Template";
	if (in_array($template, apply_filters('weaverx_paget_posts', array('paget-posts.php'))) ) {
	?>
	<p>
<?php _e('These settings are optional, and can filter which posts are displayed when you use the "Page with Posts" template.
Use commas to separate items in lists.
The settings will be combined for the final filtered list of posts displayed.
(If you make mistakes in your settings, it won\'t be apparent until you display the page.)','weaverx-theme-support' /*adm*/); ?>
</p>

	<input type="text" size="30" id="_pp_category" name="_pp_category"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_category", true)); ?>" />
	<?php _e('<em>Category</em> - Enter list of category slugs of posts to include. (-slug will exclude specified category)','weaverx-theme-support' /*adm*/); ?>
	<br />

	<input type="text" size="30" id="_pp_tag" name="_pp_tag"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_tag", true)); ?>" />
	<?php _e("<em>Tags</em> - Enter list of tag slugs of posts to include.",'weaverx-theme-support' /*adm*/); ?> <br />

	<input type="text" size="30" id="_pp_onepost" name="_pp_onepost"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_onepost", true)); ?>" />
	<?php _e("<em>Single Post</em> - Enter post slug of a single post to display. (Use [show_posts] filter to include specific list of posts.)",'weaverx-theme-support' /*adm*/); ?> <br />

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

	<div style="clear:both;"></div>

<?php
	} else {	// NOT a page with posts
?>	<p>
<?php _e('<strong>Note:</strong> After you choose the "Page with Posts" template from the <em>Template</em>
option in the <em>Page Attributes</em> box, <strong>and</strong> <em>Publish</em> or <em>Save Draft</em>,
settings for "Page with Posts" will be displayed here. Current page template:','weaverx-theme-support' /*adm*/);
echo $template; ?>
	</p>


<?php
	}
}

if (WVRX_TS_PAGEBUILDERS) :
function wvrx_ts_page_builder_opts() {

	global $post;

	echo '<span style="font-weight:bold;font-size:140%;">' . __('Page Builder Header/Footer Replacemant','weaverx-theme-support' /*adm*/) . '</span><br/>';
	_e('These settings let you replace the header or footer areas with content from a page builder page. They override the global replacement settings.','weaverx-theme-support' /*adm*/);
	weaverx_html_br(2);

	// HEADER ----------------

	wvrx_ts_page_title( __('Header Area Replacement','weaverx-theme-support' /*adm*/),80,1);
?>
	<p>
<?php
		$cur = get_post_meta(get_the_ID(), '_pp_pb_header_hide_menus', true);
?>

		<select id="_pp_pb_header_hide_menus" name="_pp_pb_header_hide_menus">
			<option value='' <?php echo $cur == '' ? ' selected="selected"' : '';?>><?php _e('Global Visibility','weaverx-theme-support'); ?></option>
			<option value='hide' <?php echo $cur == 'hide' ? ' selected="selected"' : '';?>><?php _e('Hide Menus','weaverx-theme-support'); ?></option>
			<option value='show' <?php echo $cur == 'show' ? ' selected="selected"' : '';?>><?php _e('Show Menus','weaverx-theme-support'); ?></option>
		</select>
		<?php _e('<em>Hide or Show Menus</em> - Select how to display menus with Header Replacement.','weaverx-theme-support'); ?>
		</p>

<input type="text" size="10" id='_pp_fi_link' name='_pp_fi_link'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_pb_header_replace_page_id', true)); ?>" />
	<?php _e("Specify any page or post ID to serve as header replacement. Overrides list selection below.",'weaverx-theme-support' /*adm*/); ?>
	<br style="clear:both;" />

<?php
if ( defined( 'ELEMENTOR_VERSION' ) ) :				// only provide if elementor is active
	// Elementor selection
	$pargs = array (
			'post_type' => 'page'
		);
	$posts = get_pages($pargs);
?>
	<p>
		<?php _e('<em>Select an Elementor Page.</em> (Override this selection in Page/Post field above to select Page OR Post by ID.)','weaverx-theme-support'); ?><br />
		<select id="_pp_elementor_header_replacement" name="_pp_elementor_header_replacement">
			<option value=''>&nbsp;</option>
	<?php
			$cur = get_post_meta(get_the_ID(), '_pp_elementor_header_replacement', true);
			foreach ( $posts as $cur_post) {
				if ( ! ! get_post_meta( $cur_post->ID, '_elementor_edit_mode', true ) ) {
					$selected = $cur == $cur_post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $cur_post->ID .'">'. substr( $cur_post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
endif;
	$posts = '';
if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) :		// only provide if siteorigins is active

	// SiteOrigin selection
	$pargs = array(
			'post_type' => 'page'
		);
	$posts = get_pages($pargs);
?>
		<p>
		<?php _e('<em>Select a SiteOrigin Page Builder Page.</em> (Override this selection in Page/Post field above to select Page OR Post by ID.)','weaverx-theme-support'); ?><br />
		<select id="_pp_siteorigin_header_replacement" name="_pp_siteorigin_header_replacement">
			<option value=''>&nbsp;</option>
	<?php
			$cur = $cur = get_post_meta(get_the_ID(), '_pp_siteorigin_header_replacement', true);;
			foreach ( $posts as $cur_post) {
				if ( ! ! get_post_meta( $cur_post->ID, 'panels_data', true ) ) {
					$selected = $cur == $cur_post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $cur_post->ID .'">'. substr( $cur_post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
endif; // SiteOrigin

	if ( !defined( 'ELEMENTOR_VERSION' ) && !defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		echo '<p>';
		_e('Sorry, Weaver can only list page builder pages from Elementor or SiteOrigin Page Builder. Please install and activate one of those plugins.','weaver-xtreme');
		echo '</p>';
	}

	// FOOTER -----------------

	weaverx_html_br(1);
	wvrx_ts_page_title( __('Footer Area Replacement','weaverx-theme-support' /*adm*/),80,1);


if ( defined( 'ELEMENTOR_VERSION' ) ) :				// only provide if elementor is active
	// Elementor selection
	$pargs = array(
			'post_type' => 'page'
		);
	$posts = get_pages($pargs);
?>
	<p>
		<?php _e('<em>Select an Elementor Page.</em> (Override this selection in Page/Post field above to select Page OR Post by ID.)','weaverx-theme-support'); ?><br />
		<select id="_pp_elementor_footer_replacement" name="_pp_elementor_footer_replacement">
			<option value=''>&nbsp;</option>
	<?php
			$cur = get_post_meta(get_the_ID(), '_pp_elementor_footer_replacement', true);
			foreach ( $posts as $cur_post) {
				if ( ! ! get_post_meta( $cur_post->ID, '_elementor_edit_mode', true ) ) {
					$selected = $cur == $cur_post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $cur_post->ID .'">'. substr( $cur_post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
endif;
	$posts = '';
if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) :		// only provide if siteorigins is active

	// SiteOrigin selection
	$pargs = array(
			'post_type' => 'page'
		);
	$posts = get_pages($pargs);
?>
		<p>
		<?php _e('<em>Select a SiteOrigin Page Builder Page.</em> (Override this selection in Page/Post field above to select Page OR Post by ID.)','weaverx-theme-support'); ?><br />
		<select id="_pp_siteorigin_footer_replacement" name="_pp_siteorigin_footer_replacement">
			<option value=''>&nbsp;</option>
	<?php
			foreach ( $posts as $cur_post) {
				$cur = get_post_meta(get_the_ID(), '_pp_siteorigin_footer_replacement', true);
				if ( ! ! get_post_meta( $cur_post->ID, 'panels_data', true ) ) {
					$selected = $cur == $cur_post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $cur_post->ID .'">'. substr( $cur_post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
endif; // SiteOrigin
	if ( !defined( 'ELEMENTOR_VERSION' ) && !defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		echo '<p>';
		_e('Sorry, Weaver can only list page builder pages from Elementor or SiteOrigin Page Builder. Please install and activate one of those plugins.','weaver-xtreme');
		echo '</p>';
	}
	echo '<div style="clear:both;"></div>';
}
endif;


function wvrx_ts_page_xtreme_plus( $raw_template ) {		// ------------------------------- Weaver Xtreme Plus -------------------
	global $post;
	echo '<h3>Weaver Xtreme Plus Per Page Options  (&starf;Plus)</h3>';
	echo '<strong>' . __('Per Page Style','weaver-xtreme-plus') . '</strong> (&starf;Plus)' /*a*/ ;

	do_action('wvrx_ts_xp_perpage_style', $raw_template);
}



function wvrx_ts_emit_script_style() {
	// emit script and style for tabs
?>
<script>
// TABS - put in front to make show faster

jQuery(document).ready(function($) {		// self-defining function - for tabs shortcode
    // Tabs
	$('.wvr-tabs-nav').delegate('span:not(.wvr-tabs-current)', 'click', function() {
		$(this).addClass('wvr-tabs-current').siblings().removeClass('wvr-tabs-current')
		.parents('.wvr-tabs').find('.wvr-tabs-pane').hide().eq($(this).index()).show();
	});
	$('.wvr-tabs-pane').hide();
	$('.wvr-tabs-nav span:first-child').addClass('wvr-tabs-current');
	$('.wvr-tabs-panes .wvr-tabs-pane:first-child').show();

});
</script>
<style type="text/css">
	/* Tabs */
.wvr-tabs,
.wvr-tabs2,
.wvr-tabs3 {
    box-sizing:         border-box;
    margin: 0 0 1.5em 0;
    background:transparent;
}

.wvr-tabs-style .wvr-tabs-nav,
.wvr-tabs-style .wvr-tabs-nav2
.wvr-tabs-style .wvr-tabs-nav3 {    /* top "bar" behind tabs */
	padding: 0px 20px 0px 0px;
	margin: 0;
	height: 24px;
	background-color: transparent;
}

.wvr-tabs-style .wvr-tabs-nav span,
.wvr-tabs-style .wvr-tabs-nav span2,
.wvr-tabs-style .wvr-tabs-nav span3 {   /* bg color of a tab */
	display: block;
	float: left;
	padding: 0 8px;
	height: 24px;
	line-height: 24px;
	margin-right: 1px;
	cursor: pointer;
	border-top: 1px solid #888;         /* border-color */
	border-right: 1px solid #888;
	border-left: 1px solid #888;
	background: #ccc;                   /* tab-bg */
	position:relative;
	z-index:		1;
}

.wvr-tabs-style .wvr-tabs-nav span.wvr-tabs-current,
.wvr-tabs-style2 .wvr-tabs-nav2 span.wvr-tabs-current2,
.wvr-tabs-style3 .wvr-tabs-nav3 span.wvr-tabs-current3 {
	position:relative;
	z-index:		3;
}

.wvr-tabs-style .wvr-tabs-nav span.wvr-tabs-current,
.wvr-tabs-style .wvr-tabs-nav span:hover,
.wvr-tabs-style2 .wvr-tabs-nav2 span.wvr-tabs-current2,
.wvr-tabs-style2 .wvr-tabs-nav2 span:hover,
.wvr-tabs-style3 .wvr-tabs-nav3 span.wvr-tabs-current3,
.wvr-tabs-style3 .wvr-tabs-nav3 span:hover {
    background: #eee;                               /* tab-current-color */
}

.wvr-tabs-nav span,
.wvr-tabs-nav span2,
.wvr-tabs-nav span3 {
	box-sizing:			border-box;
}

span.wvr-tabs-current,
span.wvr-tabs-current2,
span.wvr-tabs-current3 {
	box-sizing:			content-box;
}

.wvr-tabs-style .wvr-tabs-pane,
.wvr-tabs-style3 .wvr-tabs-pane3,
.wvr-tabs-style3 .wvr-tabs-pane3 {
	padding: 			15px;
    border: 			1px solid #888;        /* border-color */
    min-height:			50px;               /* pane-min-height */
    background-color:	transparent;        /* pane-bg */
	position:			relative;
	z-index:			2;
}

.wvr-tabs-panes,
.wvr-tabs-panes2,
.wvr-tabs-panes3 {
	position:			relative;
	z-index:			2;
}		/* add line to tab */

.wvr-tabs-panes .wvr-tabs-hide,
.wvr-tabs-panes2 .wvr-tabs-hide2,
.wvr-tabs-panes3 .wvr-tabs-hide3 {
	display: none;

}

.wvr-tabs-nav,
.wvr-tabs-nav2,
.wvr-tabs-nav3 {
	position:			relative;
	z-index:			3;
}
</style>
<?php
}




function wvrx_ts_page_color($opt, $msg) {		// used by XPlus
	global $post;

	$pclass = 'color {hash:true, adjust:false}';    // starting with V 1.3, allow text in color pickers
	//echo '<span class="dashicons dashicons-admin-appearance"></span>&nbsp';

?>
	<label><input class="<?php echo $pclass; ?>" id="<?php echo($opt); ?>" name="<?php echo($opt); ?>" type="text" style="width:90px"
	value="<?php echo esc_textarea(get_post_meta($post->ID, $opt, true)); ?>"/>
<?php
	echo '</label>&nbsp;' . $msg ;
}

function wvrx_ts_simple_checkbox($opt, $msg) {
	global $post;
?>
<label><input type="checkbox" id="<?php echo($opt); ?>" name="<?php echo($opt); ?>"
<?php checked(wvrx_ts_isp_true(get_post_meta($post->ID, $opt, true))); ?> /><?php echo $msg;?></label>
<?php
}

function wvrx_ts_page_checkbox($opt, $msg, $width = 33, $br = 0) {
	if ( $width != 'auto')
		$width = "{$width}%";
?>
	<div style="float:left;width:<?php echo $width; ?>"><?php wvrx_ts_simple_checkbox($opt,$msg);
	echo '</div>';
	for ($i = 0 ; $i < $br ; $i++)
		echo '<br class="page_checkbox" style="clear:both;" />';

}

function wvrx_ts_page_title($msg, $width = 33, $br = 0) {
	if ( $width != 'auto')
		$width = "{$width}%";
?>
	<div style="float:left;font-weight:bold;font-style:italic;width:<?php echo $width; ?>">
	<?php
	echo $msg . '</div>';
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
__('(Note: Weaver Show Posts <em>Post Display</em> options and <em>Use Paging</em> option <strong>not</strong> used for posts when using this filter.)','weaverx-theme-support' /*adm*/) .
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
		'info' => __('Use <em>Masonry</em> for multi-column display. <em>Not compatible with FI BG images.</em>','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => '&nbsp;' ),
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


//================================================================ Per Post Options ========================================================



function wvrx_ts_post_extras() {
	global $post;
	$func_opt = WEAVER_GET_OPTION;
	$opts = get_option( apply_filters('weaverx_options',WEAVER_SETTINGS_NAME) , array());	// need to fetch Weaver Xtreme options
	if ( !( current_user_can('edit_themes')
		|| (current_user_can('edit_theme_options') && !isset($opts['_hide_mu_admin_per']))	// multi-site regular admin
		|| (current_user_can('edit_pages') && !isset($opts['_hide_editor_per']))	// Editor
		|| (current_user_can('edit_posts') && !isset($opts['_hide_author_per']))) // Author/Contributor
	   ) {
		echo '<p>' . __('Weaver Xtreme Per Post Options not available for your User Role.','weaverx-theme-support' /*adm*/) . '</p>';
		return;	// don't show per post panel
	   }
?>
<div style="line-height:150%;border: 1px solid $888;list-style-type: none;">
<p>
<?php
	$updir = wp_upload_dir();
	$dir = trailingslashit($updir['basedir']) . 'weaverx-subthemes/editor-style-wvrx.css';

	if (!@file_exists( $dir ))  { ?>
<div style="padding:2px; border:2px solid red; background:#FAA;">
<?php _e('<strong>Note!</strong>
Please open the <em>Appearance:Weaver Xtreme Admin:Main Options</em> page and <em>Save Settings</em> to enable full editor theme match styling.','weaverx-theme-support' /*adm*/); ?>
</div><br />
<?php
	}

	echo '<strong>' . __('Per Post Options','weaverx-theme-support' /*adm*/) . '</strong>';
	weaverx_help_link('help.html#PerPage', __('Help for Per Post Options','weaverx-theme-support' /*adm*/));
	echo '<span style="float:right;">(' . __('This Post\'s ID: ','weaverx-theme-support' /*adm*/); the_ID() ; echo ')</span>';
	weaverx_html_br();
	_e('These settings let you control display of this individual post. Many of these options override global options set on the Weaver Xtreme admin tabs.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();

	wrvx_ts_show_post_tabs();

?>
<p>
	<?php echo('<strong>Post Format</strong>');
	weaverx_help_link('help.html#gallerypost', __('Help for Per Post Format','weaverx-theme-support' /*adm*/));
	weaverx_html_br();
	_e('Weaver Xtreme supports Post Formats. Click the ? for more info. Post Formats are set in the "Formats" option box.','weaverx-theme-support' /*adm*/);
	weaverx_html_br();
?>

</p>
	<input type='hidden' id='post_meta' name='post_meta' value='post_meta'/>
	<div style="clear:both;"></div>
</div>
<?php
}

function wrvx_ts_show_post_tabs( ) {
?>
<div id="tabwrap-per-post" style="padding-left:4px;">
<div id="tab-container-post" style="border:1px solid #888;padding: 2px 1% 2px 1%; width:97.9%;" class='yetiisub'>
	<ul id="tab-container-post-nav" style="border-bottom:2px solid #999;" class='yetiisub'>
	<?php
	wvrx_ts_elink('#pp-post-blog' , __('Per Post options when displayed in blog or archive.', 'weaver-xtreme' /*adm*/), __('Post Blog/Archive Options', 'weaver-xtreme' /*adm*/),'<li>','</li>');
	wvrx_ts_elink('#pp-post-single' , __('Per Post options for single page view.', 'weaver-xtreme' /*adm*/), __('Post Single Page Options', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-post-misc' , __('Misc per post options', 'weaver-xtreme' /*adm*/), __('Misc Options', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	wvrx_ts_elink('#pp-post-xplus' , __('Per Post settings from Weaver Xtreme Plus.', 'weaver-xtreme' /*adm*/), __('Xtreme Plus', 'weaver-xtreme' /*adm*/),'<li>', '</li>');
	?>
	</ul>

	<div id="pp-post-blog" class="tab_mainopt" >  <!-- ******************* blog/archive ********************* -->
		<?php
		weaverx_ts_post_blog_opts( );
		?>
	</div>											<!-- ******************* /blog-archive ********************* -->

	<div id="pp-post-single" class="tab_mainopt" >		<!-- ******************* single page ********************* -->
		<?php
		weaverx_ts_post_single_opts( );
		?>
	</div>											<!-- ******************* /single page ********************* -->

	<div id="pp-misc" class="tab_mainopt" >			<!-- ******************* misc ********************* -->
	<?php weaverx_ts_post_misc_opts(  ); ?>
	</div>											<!-- ******************* /misc ********************* -->


	<div id="pp-post-xplus" class="tab_mainopt" >		<!-- ******************* X-Plus ********************* -->
	<?php weaverx_ts_post_xplus_opts(  );?>
	</div>											<!-- ******************* X-Plus ********************* -->


</div> <!-- #tab-container-post -->
</div>	<!-- #tabwrap-per-post -->

<script type="text/javascript">
	var tabberMainOpts = new Yetii({
	id: 'tab-container-post',
	tabclass: 'tab_mainopt',
	persist: true
	});
</script>
<?php
}


function weaverx_ts_post_blog_opts() {
?>
<strong><?php _e('Per Post Options when displayed on Blog or Archive-like view','weaverx-theme-support' /*adm*/); ?></strong><br /><br />

<?php

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
	?>
<br style="clear:both;" /><strong><p>
<?php _e('<em>Post Blog/Archive View:</em> Featured Image','weaverx-theme-support' /*adm*/); ?></strong></p>
<?php
	$opts3 = array(  'id' => '_pp_post_fi_location',
		'info' => __('Override <em>Post</em> setting for where to display FI (for both excerpt and full content)','weaverx-theme-support' /*adm*/),
		'value' => array(
			array('val' => '', 'desc' => __('Default Blog FI','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-top', 'desc' => __('With Content - top','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-bottom', 'desc' => __('With Content - bottom','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-before', 'desc' => __('With Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-banner', 'desc' => __('Banner above Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'header-image', 'desc' => __('Header Image Replacement','weaverx-theme-support' /*adm*/) ),
			array('val' => 'post-before', 'desc' => __('Beside Post, no wrap', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg', 'desc' => __('As BG Image, Tile', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-cover', 'desc' => __('As BG Image, Cover', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-parallax', 'desc' => __('As BG Image, Parallax', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'hide', 'desc' => __('Hide FI for this Post','weaverx-theme-support' /*adm*/) )
			)
		);
	wvrx_ts_pp_select_id($opts3);

	echo '<div style="clear:both;" /></div>';

}

function weaverx_ts_post_single_opts() {
	global $post;
	?>
<strong><?php _e('<em>Single Page View:</em> Sidebars','weaverx-theme-support' /*adm*/); ?></strong><br /><br />

<?php
	wvrx_ts_page_layout('post');
?>
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
			array('val' => '', 'desc' => __('Default Blog FI','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-top', 'desc' => __('With Content - top','weaverx-theme-support' /*adm*/) ),
			array('val' => 'content-bottom', 'desc' => __('With Content - bottom','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-before', 'desc' => __('With Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'title-banner', 'desc' => __('Banner above Title','weaverx-theme-support' /*adm*/) ),
			array('val' => 'header-image', 'desc' => __('Header Image Replacement','weaverx-theme-support' /*adm*/) ),
			array('val' => 'post-before', 'desc' => __('Beside Page, no wrap', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg', 'desc' => __('As BG Image, Tile', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-cover', 'desc' => __('As BG Image, Cover', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'post-bg-parallax', 'desc' => __('As BG Image, Parallax', 'weaver-xtreme' /*adm*/) ),
			array('val' => 'hide', 'desc' => __('Hide FI for this Post','weaverx-theme-support' /*adm*/) )
			)
		);
	wvrx_ts_pp_select_id($opts3);
?>
<br />
<input type="text" size="30" id='_pp_fi_link' name='_pp_fi_link'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_fi_link', true)); ?>" />
	<?php _e("<em>Featured Image Link</em> - Full URL to override default link target from FI",'weaverx-theme-support' /*adm*/); ?>
	<br style="clear:both;" />
	<br />

<?php
	echo '<div style="clear:both;"/></div>';
}


function weaverx_ts_post_misc_opts() {
	?>

<p>
	<strong><?php _e('Post Editor Options','weaverx-theme-support' /*adm*/); ?></strong>

<?php
	wvrx_ts_page_checkbox('_pp_hide_visual_editor',__('Disable Visual Editor for this page. Useful if you enter simple HTML or other code.','weaverx-theme-support' /*adm*/),90,  1);

	if (weaverx_allow_multisite()) {
		wvrx_ts_page_checkbox('_pp_raw_html',__('Allow Raw HTML and scripts. Disables auto paragraph, texturize, and other processing.','weaverx-theme-support' /*adm*/),90, 1);
	}
	?>
</p>
<?php

}


function weaverx_ts_post_xplus_opts() {

	echo '<div style="clear:both;">';
	echo('<h3>Weaver Xtreme Plus Per Post Options  (&starf;Plus)</h3><strong>Per Post Style</strong>' /*a*/ );
	weaverx_help_link('help.html#perpoststyle', __('Help for Per Post Style','weaverx-theme-support' /*adm*/ ));
	echo '<br />' .
__('Weaver Xtreme Plus supports optional per post CSS style rules.','weaverx-theme-support' /*adm*/);
	echo '<br />';

	do_action('wvrx_ts_xp_perpoststyle');
	weaverx_html_br();

	do_action('weaverxplus_add_per_post');
	echo '</div>';

}




function wvrx_ts_save_post_fields($post_id) {

	// NOTE - Update weaverx-ts.php when this changes...

	$default_post_fields = array('_pp_category', '_pp_tag', '_pp_onepost', '_pp_orderby', '_pp_sort_order',
	'_pp_author', '_pp_posts_per_page', '_pp_primary-widget-area', '_pp_secondary-widget-area', '_pp_sidebar_width',
	'_pp_top-widget-area','_pp_bottom-widget-area','_pp_sitewide-top-widget-area', '_pp_sitewide-bottom-widget-area',
	'_pp_post_type', '_pp_hide_page_title','_pp_hide_site_title','_pp_hide_menus','_pp_hide_header_image',
	'_pp_hide_footer','_pp_hide_header','_pp_hide_sticky', '_pp_force_post_full','_pp_force_post_excerpt',
	'_pp_show_post_avatar', '_pp_bodyclass', '_pp_fi_link', '_pp_fi_location', '_pp_post_fi_location', '_pp_post_styles',
	'_pp_hide_top_post_meta','_pp_hide_bottom_post_meta', '_pp_stay_on_page', '_pp_hide_on_menu', '_pp_show_featured_img',
	'_pp_hide_infotop','_pp_hide_infobottom', '_pp_hide_visual_editor', '_pp_masonry_span2', '_show_post_bubble',
	'_pp_hide_post_title', '_pp_post_add_link', '_pp_hide_post_format_label', '_pp_page_layout', '_pp_wvrx_pwp_type',
	'_pp_wvrx_pwp_cols', '_pp_post_filter', '_pp_header-widget-area' ,'_pp_footer-widget-area',
	'_pp_hide_page_infobar', '_pp_hide_n_posts','_pp_fullposts', '_pp_pwp_masonry','_pp_pwp_compact','_pp_pwp_compact_posts',
	'_primary-widget-area', '_secondary-widget-area', '_header-widget-area', '_footer-widget-area', '_sitewide-top-widget-area',
	'_sitewide-bottom-widget-area', '_page-top-widget-area', '_page-bottom-widget-area', '_pp_full_browser_height',
	'_pp_page_cols', '_pp_header_full', '_pp_container_full', '_pp_footer_full','_pp_hide_footer_html','_pp_hide_header_html',
	'_pp_hide_customlogo', '_pp_hide_mini_menu' , '_pp_hide_headersearch', '_pp_header_html', '_pp_footer_html',
	'_pp_elementor_header_replacement', '_pp_elementor_footer_replacement', '_pp_siteorigin_header_replacement', '_pp_elementor_footer_replacement',
	'_pp_pb_header_hide_menus','_pp_pb_header_replace_page_id','_pp_pb_footer_replace_page_id',


	// Plus options
	'_pp_bgcolor','_pp_color','_pp_bg_fullwidth', '_pp_lr_padding', '_pp_tb_padding', '_pp_margin', '_pp_post_class',
	'_pp_bgimg', '_pp_mobile_bgimg', '_pp_parallax_height', '_pp_use_parallax', '_pp_parallax_not_wide',
	'_pp_footer_add_class', '_pp_container_add_class', '_pp_content_add_class', '_pp_post_add_class',
	'_pp_infobar_add_class', '_pp_wrapper_add_class', '_pp_header_add_class', '_pp_header_image_html_text',
	'_pp_alt_primary_menu', '_pp_alt_secondary_menu', '_pp_alt_mini_menu', '_pp_video_active', '_pp_video_url',
	'_pp_video_aspect', '_pp_video_render', '_pp_alt_theme'
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

// } end of former XP check

// XP

if (function_exists('weaverxplus_plugin_installed') ) :

add_action('wvrx_ts_xp_perpage_style','wvrx_ts_xp_perpage_style_action');

function wvrx_ts_xp_perpage_style_action($raw_template) {

	global $post;


	weaverx_help_link('help.html#perpoststyle', __('Help for Per Page Style','weaver-xtreme-plus' /*adm*/ ));
	if (!$raw_template) {
	echo '<p><small>' .
__('Enter optional per page CSS style rules. <strong>Do not</strong> include the &lt;style> and &lt;/style> tags.
Include the complete "selector {}" for each rule you define.
Custom styles will not be displayed by the Page Editor.
Example - full width page with centered, indented header, container, and footer:<br />
<code>#wrapper{max-width:100%;}
#header{width:80%;margin-left:auto;margin-right:auto;}
#container{width:80%;margin-left:auto;margin-right:auto;}
#colophon{width:80%;margin-left:auto;margin-right:auto;}</code>','weaver-xtreme-plus' /*adm*/);
	} else {
		echo '<p><br /><small>' .
__('Enter optional per page CSS style rules. <strong>Do not</strong> include the &lt;style> and &lt;/style> tags.
Include the complete "selector {}" for each rule you define. Rules with no selector apply to entire page.
Custom styles will not be displayed by the Page Editor.'
,'weaver-xtreme-plus' /*adm*/);
	}
?>
<br /><br />
	<textarea class="wvrx-edit" placeholder=" " name="_pp_post_styles" rows=2 style="width: 95%"><?php echo(get_post_meta($post->ID, "_pp_post_styles", true)); ?></textarea>
</small><br /><br />
	<?php

	if (!$raw_template) {
	_e('<strong>Per Page Area Added Classes</strong><br />Add classes to selected wrapping areas for this page only. Useful for full width layouts - e.g. Parallax.','weaver-xtreme-plus' /*adm*/);
	// 'footer', 'container', 'content', 'post', 'infobar', 'wrapper', 'header' as "_pp_{$area}_add_class"
	$areas = array ('wrapper', 'header', 'infobar', 'container', 'content', 'post', 'footer');

	$afters = array ('<br />', '<span style="margin-left:4em;"></span>');
	$count = 0;

	weaverx_html_br();

	foreach ( $areas as $area ) {
		if ( ($count++) % 2 == 0)
			$after = $afters[1];
		else
			$after = $afters[0];

		wvrx_ts_xp_text_option( $post->ID, "_pp_{$area}_add_class",
			" <strong>{$area}</strong>", '', $after);
	}
	weaverx_html_br();

	// Weaver X Plus options for making horizontal bar layouts
	wvrx_ts_page_color('_pp_bgcolor',__('Page BG Color','weaver-xtreme-plus'));


	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

	// If no menus exists, direct the user to go and create some.
	if ( !$menus ) {
		echo '<p>'. sprintf( 'No menus have been created yet. <a href="%s">Create some</a>.', admin_url('nav-menus.php') ) .'</p>';
	} else {
	?>
	<hr /><p>
	<?php
	_e("<strong>Replacement Menus</strong><br />You may replace one of the standard Header Menus (Primary, Secondary, or Header Mini) on this page. If there the menu hasn't been	set in the Menus options, then you can set that menu for just this page.",'weaver-xtreme-plus' /*adm*/);
?>
	<br />
	<label for="_pp_alt_primary_menu"><strong><?php echo('Alternate Primary Menu' /*a*/ ); ?></strong></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<select id="_pp_alt_primary_menu" name="_pp_alt_primary_menu">
		<option value=''> </option>
<?php
		foreach ( $menus as $menu ) {
			$selected = get_post_meta($post->ID, "_pp_alt_primary_menu", true) == $menu->term_id ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
		}
?>
	</select>

	<br /><label for="_pp_alt_secondary_menu"><strong><?php echo('Alternate Secondary Menu' /*a*/ ); ?></strong></label> &nbsp;&nbsp;&nbsp;
	<select id="_pp_alt_secondary_menu" name="_pp_alt_secondary_menu">
		<option value=''> </option>
<?php
		foreach ( $menus as $menu ) {
			$selected = get_post_meta($post->ID, "_pp_alt_secondary_menu", true) == $menu->term_id ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
		}
?>
	</select>
	<br /><label for="_pp_alt_mini_menu"><strong><?php echo('Alternate Header Mini Menu' /*a*/ ); ?></strong></label> &nbsp;
	<select id="_pp_alt_mini_menu" name="_pp_alt_mini_menu">
		<option value=''> </option>
<?php
		foreach ( $menus as $menu ) {
			$selected = get_post_meta($post->ID, "_pp_alt_mini_menu", true) == $menu->term_id ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
		}
?>
	</select>
	</p>
<?php
	}
	echo '<p>';
	_e("<strong>Alternate Subtheme</strong><br />You may specify an alternate Weaver Xtreme subtheme that you have saved on your host filesystem for this page. See Legacy Weaver Xtreme Admin : Save/Restore tab to save a subtheme.",'weaver-xtreme' /*adm*/);

	echo '<br /><br />';
	if (version_compare( WEAVER_XPLUS_VERSION, '3.1', '>=')) {
		_e('<em>Please select a previously saved theme settings file (either .wxt or .wxb).</em>','weaver-xtreme');
		_e('Note: This page will still use the default site subtheme settings to display in the Page Editor (i.e., on this screen).','weaver-xtreme');

	    // build the theme file list
		$themes = wvrx_ts_get_alt_themes();
?>
	<br />

	<label for="_pp_alt_theme"><strong><?php echo('Alternate Subtheme for this Page: ' /*a*/ ); ?></strong></label> &nbsp;
	<select id="_pp_alt_theme" name="_pp_alt_theme">
		<option value=''> </option>
<?php
		foreach ( $themes as $subtheme ) {
			$selected = get_post_meta($post->ID, "_pp_alt_theme", true) == $subtheme ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $subtheme .'">'. $subtheme .'</option>';
		}
?>
	</select>
<?php


	} else {
		_e('<em>This option requires Weaver Xtreme Plus version 3.1 or greater.</em>','weaver-xtreme');
	}


	echo '<br /><hr /><span style="clear:both;"/></span><strong>' . __('Header Image Replacement HTML','weaver-xtreme-plus') . '</strong> (&starf;Plus)' ;
?>
	</p>
	<p>
<?php
if (version_compare( WEAVER_XPLUS_VERSION, '2.90', '>=')) :
	_e('Replace Header image with arbitrary HTML for this page only. Useful for slider shortcodes in place of image. FI as Header Image has priority over HTML replacement. This will work with [show_slider] or almost any other slider that supports a shortcode.', 'weaver-xtreme' /*adm*/)
?>
	<textarea class="wvrx-edit" placeholder=" " name="_pp_header_image_html_text" rows=1 style="width: 95%"><?php echo(get_post_meta($post->ID, '_pp_header_image_html_text', true)); ?></textarea>
<?php endif; // new version ?>
</p>
<?php
	echo '<span style="clear:both;"/></span><strong>' . __('Header Video Options','weaver-xtreme-plus') . '</strong> (&starf;Plus)';

	$showopts = array(
		array('val' => '', 'desc' => __('Default: only if this page is the front page','weaver-xtreme-plus')),
		array('val' => 'yes', 'desc' => __('Yes','weaver-xtreme-plus')),
		array('val'=> 'no', 'desc' => __('Never','weaver-xtreme-plus')));
	$aspectopts = array(
		array('val' => '', 'desc' => __('Global Aspect Ratio', 'weaver-xtreme')),
		array('val' => '16:9', 'desc' => __('16:9 HDTV', 'weaver-xtreme')),
		array('val' => '4:3', 'desc' => __('4:3 Std TV', 'weaver-xtreme')),
		array('val' => '3:2', 'desc' => __('3:2 35mm Photo', 'weaver-xtreme')),
		array('val' => '5:3', 'desc' => __('5:3 Alternate Photo', 'weaver-xtreme')),
		array('val' => '64:27', 'desc' => __('2.37:1 Cinemascope', 'weaver-xtreme')),
		array('val' => '37:20', 'desc' => __('1.85:1 VistaVision', 'weaver-xtreme')),
		array('val' => '3:1', 'desc' => __('3:1 Banner', 'weaver-xtreme')),
		array('val' => '4:1', 'desc' => __('4:1 Banner', 'weaver-xtreme')),
		array('val' => '9:16', 'desc' => __('9:16 Vertical HD (Please avoid!)', 'weaver-xtreme')));
	$renderopts = array(
		array('val' => '', 'desc' => __('Global Rendering Setting', 'weaver-xtreme')),
		array('val' => 'has-header-video', 'desc' => __('As video in header only', 'weaver-xtreme')),
		array('val' => 'has-header-video-cover', 'desc' => __('As full cover Parallax BG Video', 'weaver-xtreme')),
		array('val' => 'has-header-video-none', 'desc' => __('Disable Header Video', 'weaver-xtreme' /*adm*/))

	);
?>
	<p><label for='_pp_video_active'><strong><?php echo('Display Video on This Page:' /*a*/ ); ?></strong></label> &nbsp;
	<select id='_pp_video_active' name='_pp_video_active'>
<?php
		foreach ( $showopts as $opt ) {
			$selected = get_post_meta($post->ID, '_pp_video_active', true) == $opt['val'] ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $opt['val'] .'">'. $opt['desc'] .'</option>';
		}
?>
	</select>
	</p>
	<p><label for='_pp_video_url'><strong><?php echo('Video URL for This Page:' /*a*/ ); ?></strong></label> &nbsp;
	<textarea class="wvrx-edit" placeholder=" " name='_pp_video_url' rows=1 style="width: 45%"><?php echo(get_post_meta($post->ID, '_pp_video_url', true)); ?></textarea>
<?php
	if (!has_header_video() && get_post_meta($post->ID, '_pp_video_url', true)) { ?>
	<br /><strong>
	<em style="color:red;">
		IMPORTANT: There is NO Header Video set on the <u>Customizer : Images : Header Media (Content)</u> menu. Please set one.</em>
	Due to current restrictions with the WordPress Header Media video implementation,
	you MUST always define some Header Video selection in that menu, even if you are specifying an alternative Per Page Video URL here.
	We think this is a flaw in the WP core, and have requested that the restriction be removed in the future.</strong>
<?php	} ?>
	</p>
	<p>
	<label for='_pp_video_aspect'><strong><?php echo('Aspect Ratio:' /*a*/ ); ?></strong></label> &nbsp;
	<select id='_pp_video_aspect' name='_pp_video_aspect'>
<?php
		foreach ( $aspectopts as $opt ) {
			$selected = get_post_meta($post->ID, '_pp_video_aspect', true) == $opt['val'] ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $opt['val'] .'">'. $opt['desc'] .'</option>';
		}
?>
	</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label for='_pp_video_render'><strong><?php echo('Header Video Rendering:' /*a*/ ); ?></strong></label> &nbsp;
	<select id='_pp_video_render' name='_pp_video_render'>
<?php
		foreach ( $renderopts as $opt ) {
			$selected = get_post_meta($post->ID, '_pp_video_render', true) == $opt['val'] ? ' selected="selected"' : '';
			echo '<option'. $selected .' value="'. $opt['val'] .'">'. $opt['desc'] .'</option>';
		}
?>
	</select>
	</p>
<div style="clear:both"></div>
<hr />
<p style="line-height:1.3em;">
<?php	echo('<strong>Per Page Code Insertion</strong>' /*a*/ );
	weaverx_help_link('help.html#ExtraPP', 'Help for Extra Per Page Options');
?>
Weaver Xtreme Plus supports code and HTML insertion for some areas. To add code, manually define the specified
<em>Custom Field Name</em> and <em>Value</em>. Click the help ? for more details.
</p>

<?php
}
}	// end of action function

function wvrx_ts_get_alt_themes() {
	$themes = array();
	$upload_dir = wp_upload_dir();
	$theme_dir = trailingslashit( $upload_dir['basedir'] ) . 'weaverx-subthemes/';
	if( $media_dir = opendir($theme_dir) ) {
		while ($m_file = readdir($media_dir)) {
			$len = strlen($m_file);
			$ext = $len > 4 ? substr($m_file,$len-4,4) : '';
			if($ext == '.wxt' || $ext == '.wxb' ) {
				$themes[] = $m_file;
			}
		}
	}
	return $themes;
}

add_action( 'wvrx_ts_xp_perpoststyle', 'wvrx_ts_xp_perpoststyle_action' );

function wvrx_ts_xp_perpoststyle_action() {
	global $post;

	echo '<br />' .
__('Enter optional per post CSS style rules. <strong>Do not</strong> include the &lt;style> and &lt;/style> tags.
Include the {}\'s. Don\'t use class names if rules apply to whole post, but do include class names
(e.g., <em>.entry-title a</em>) for specific elements. Custom styles will not be displayed by the Post Editor.','weaver-xtreme-plus' /*adm*/); ?>
<br />
	<textarea placeholder=" " class="wvrx-edit" name="_pp_post_styles" rows=2 style="width: 95%"><?php echo(get_post_meta($post->ID, "_pp_post_styles", true)); ?></textarea>

<br /><input type="text" size="24" id="_pp_post_class" name="_pp_post_class"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_post_class", true)); ?>" />&nbsp;
	<?php _e('Add Classes to this post','weaver-xtreme-plus' /*adm*/); ?>
<br />
<input type="text" size="24" id="bodyclass" name="_pp_bodyclass"
		value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_bodyclass", true)); ?>" />

	<?php _e('<em>Per Post body Class</em> - CSS class name to add to HTML &lt;body&gt; block when this post is displayed on Single Page.','weaverx-theme-support' /*adm*/); ?>
	<br />
<?php
	// Weaver X Plus options for making horizontal bar layouts
	echo '<small>';
	_e('<em>Note:</em> The following options are especially useful for creating full-width stacked posts with different BG colors using the RAW page template and the Weaver Show Posts plugin.', 'weaver-xtreme-plus' /*adm*/); echo '</small><br />';
	wvrx_ts_page_color('_pp_bgcolor',__('Post BG Color','weaver-xtreme-plus')); echo '<span style="margin-left:40px;"></span>';
	wvrx_ts_page_color('_pp_bg_fullwidth',__('Extend BG color to full theme width on Desktop View','weaver-xtreme-plus' /*adm*/));
	echo '<br />';
	wvrx_ts_page_color('_pp_color',__('Post Text Color','weaver-xtreme-plus'));
?>
<br />
<input type="text" size="4" id="_pp_lr_padding" name="_pp_lr_padding"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_lr_padding", true)); ?>" />
	<?php _e('em &nbsp;- Left and Right Padding for post','weaver-xtreme-plus' /*adm*/); ?>

<input style="margin-left:3em;" type="text" size="4" id="_pp_tb_padding" name="_pp_tb_padding"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_tb_padding", true)); ?>" />
	<?php _e('em &nbsp;- Top and Bottom Padding for post','weaver-xtreme-plus' /*adm*/); ?>
<br /><input type="text" size="11" id="_pp_margin" name="_pp_margin"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_margin", true)); ?>" />&nbsp;
	<?php _e('Margins - Use CSS "margin:t r b l" notation. Recommended 0 for stacked posts.','weaver-xtreme-plus' /*adm*/); ?>
	<br /><br />


	<?php _e('<em><strong>Background Image</strong></em> - Full URL for BG image - add <code>background: position/size repeat origin clip attachment</code> options to Per Post Style above in class-less {} rule. Example: <code>{background-repeat: no-repeat;}</code>','weaver-xtreme-plus' /*adm*/); ?><br />
<input type="text" size="70" id='_pp_bgimg' name='_pp_bgimg'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_bgimg', true)); ?>" />
&nbsp;: <?php _e('<strong>Desktop BG Image URL</strong>','weaver-xtreme-plus' /*adm*/); ?><br />
<input type="text" size="70" id='_pp_mobile_bgimg' name='_pp_mobile_bgimg'
	value="<?php echo esc_textarea(get_post_meta($post->ID, '_pp_mobile_bgimg', true)); ?>" />
&nbsp;: <?php _e('<strong>Mobile BG Image URL</strong> - optional','weaver-xtreme-plus' /*adm*/); ?><br />

<p><strong><?php _e('<em>Parallax</em> ','weaver-xtreme-plus' /*adm*/); ?></strong>
<?php weaverxplus_help_link('plus-help.html#parallax', __('Help for Parallax','weaver-xtreme-plus' /*adm*/ )); ?><br />
<?php _e('You can make a set of posts into a great looking Parallax page using a common tag/category,
custom BG images, and Page with Posts or [show_posts]. Create custom content to be displayed
over the BG image by creating a Manual Excerpt for this post. Do <em>not</em> specify any background
CSS options for the Parallax BG image. See Parallax Help!','weaver-xtreme-plus' /*adm*/) ?>
<br />
<?php
	wvrx_ts_page_checkbox('_pp_use_parallax',__('Make this Post a Parallax post','weaver-xtreme-plus' /*adm*/));
	wvrx_ts_page_checkbox('_pp_parallax_not_wide',__('Do <em>not</em> make BG image full width of enclosing area.','weaver-xtreme-plus' /*adm*/),'auto',1);

?>

<input type="text" size="4" id="_pp_parallax_height" name="_pp_parallax_height"
	value="<?php echo esc_textarea(get_post_meta($post->ID, "_pp_parallax_height", true)); ?>" />
	<?php _e('px &nbsp;- Specify visible height of Parallax image. (Default:400px)','weaver-xtreme-plus' /*adm*/); ?>
</p>
<?php

if (version_compare( WEAVER_XPLUS_VERSION, '2.90', '>=')) :

echo '<hr /><br style="clear:both;"/><strong>' . __('Header Image Replacement HTML','weaver-xtreme-plus') . '</strong> (&starf;Plus)' /*a*/ ;
?>
	</p><p>
<?php
	_e('Replace Header image with arbitrary HTML for this post single page view only. Useful for slider shortcodes in place of image. FI as Header Image has priority over HTML replacement. This will work with [show_slider] or almost any other slider that supports a shortcode.', 'weaver-xtreme' /*adm*/)
?>
	<textarea class="wvrx-edit" placeholder=" " name="_pp_header_image_html_text" rows=1 style="width: 95%"><?php echo(get_post_meta($post->ID, '_pp_header_image_html_text', true)); ?></textarea>
</p>
<?php endif; // new version ?>
<hr />
<p style="line-height:1.3em;">
<?php	echo('<strong>Per Page Code Insertion for Single Page View</strong>' /*a*/ );
	weaverx_help_link('help.html#ExtraPP', 'Help for Extra Per Page Options');
?>
Weaver Xtreme Plus supports code and HTML insertion for some areas of the Post Single View page. To add code, manually define the specified
<em>Custom Field Name</em> and <em>Value</em>. Click the help ? for more details.
</p>

<?php
}



//-----------------------------------



// ---- functions for XPlus only

function wvrx_ts_xp_text_option( $postid, $optid, $msg, $before = '', $after = '' ) {
	$val = esc_textarea(get_post_meta($postid, $optid, true));
	echo "{$before}<input type='text' size='20' id='{$optid}' name='{$optid}' value='{$val}' />&nbsp; {$msg}{$after}";
}
endif;

?>
