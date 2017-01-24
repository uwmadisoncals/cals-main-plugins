<?php
/*
 Weaver X shortcodes
*/

function wvrx_ts_setup_shortcodes() {
    // we setup all of our shortcodes only after the theme has been loaded...

    $codes = array(						    // list of shortcodes
    array('bloginfo' => 'wvrx_ts_sc_bloginfo'),      // [bloginfo]
    array('box' => 'wvrx_ts_sc_box'),       // [box]
	array('div' => 'wvrx_ts_sc_div'),       // [div]
    array('header_image' => 'wvrx_ts_sc_header_image'),      // [header_image]
    array('hide_if' => 'wvrx_ts_sc_hide_if' ),	      		// [hide_if]
    array('html' => 'wvrx_ts_sc_html'),		// [html]
    array('iframe' => 'wvrx_ts_sc_iframe'),         // [iframe]
	array('login' => 'wvrx_ts_sc_login'),		// [login]
    array('show_if' => 'wvrx_ts_sc_show_if' ),	      		// [show_if]
	array('span' => 'wvrx_ts_sc_span'),	    // [span]
	array('site_tagline' => 'wvrx_ts_sc_site_tagline'),   // [site_tagline]
	array('site_title' => 'wvrx_ts_sc_site_title'), // [site_title]
	array('tab_group' => 'wvrx_ts_sc_tab_group',
          'tab' => 'wvrx_ts_sc_tab'),               // [tab_group], [tab]
	array('vimeo' => 'wvrx_ts_sc_vimeo'),           // [vimeo]
	array('youtube' => 'wvrx_ts_sc_youtube'),       // [youtube]
	array('weaverx_info' => 'wvrx_ts_weaverx_sc_info'),     // [weaverx_info]
    );

	$prefix = get_option('wvrx_toggle_shortcode_prefix');

	foreach ($codes as $code) {
		wvrx_ts_set_shortcodes($code, $prefix);
	}
}

add_action('init', 'wvrx_ts_setup_shortcodes');  // allow shortcodes to load after theme has loaded so we know which version to use

// ===============  [box] ===================
function wvrx_ts_sc_box( $args = '', $text ) {
    extract(shortcode_atts(array(
        'align'         =>  '',
        'border'        =>  true,
        'border_rule'   => '1px solid black',
        'border_radius' => '',
        'color'         => '',
        'background'    => '',
        'margin'        => '',
        'padding'       => '1',
        'shadow'        => '',
        'style'         => '',
        'width'         => ''
    ), $args));

    $sty = 'style="';

    if ( $align ) {
        $align = strtolower($align);
        switch ( $align ) {
            case 'center':
                $sty .= 'display:block;margin-left:auto;margin-right:auto;';
                break;
            case 'right':
                $sty .= 'float:right;';
                break;
            default:
                $sty .= 'float:left;';
                break;
        }
    }

    if ( $border )
        $sty .= "border:{$border_rule};";
    if ( $border_radius )
        $sty .= "border-radius:{$border_radius}px;";
    if ( $shadow ) {
        if ( $shadow < 1 ) $shadow = 1;
        if ( $shadow > 5 ) $shadow = 5;
        $sty .= "box-shadow:0 0 4px {$shadow}px rgba(0,0,0,0.25);";
    }
    if ( $color )
        $sty .= "color:{$color};";
    if ( $background )
        $sty .= "background-color:{$background};";
    if ( $margin )
        $sty .= "margin:{$margin}em;";
    if ( $padding )
        $sty .= "padding:{$padding}em;";
    if ( $width )
        $sty .= "width:{$width}%;";
    if ( $sty )
        $sty .= $style;
    $sty .= '"';    // finish it

    return "<div {$sty}><!--[box]-->" . do_shortcode( $text ) . '</div><!--[box]-->';
}

// ===============  [hide_if] ===================
function wvrx_ts_sc_hide_if($args = '', $text ) {

    return wvrx_ts_show_hide_if( $args, $text, false );
}

// ===============  [show_if] ===================
function wvrx_ts_sc_show_if($args = '', $text ) {
    return wvrx_ts_show_hide_if( $args, $text, true );
}

// ===============  [show_hide_if] ===================
function wvrx_ts_show_hide_if($args = '', $text, $show) {
    extract(shortcode_atts(array(
        'device'    => 'default',       // desktop, mobile, smalltablet, phone, all
	    'logged_in' => 'default',       // true or false
        'not_post_id' => 'default',     // comma separated list of post IDs (includes pages, too)
        'post_id'   => 'default',       // comma separated list
        'user_can'  => 'default'        // http://codex.wordpress.org/Function_Reference/current_user_can
    ), $args));

    $valid_device = array('default','desktop','mobile','smalltablet','phone','all');

    if ( !in_array( $device, $valid_device )) {
        return '<br /><strong>Error with [hide/show_if]: <u>' . $device . '</u> not valid for <em>device</em> parameter.</strong><br />';

    }
    if ( $logged_in == 'default' ) {            // **** logged_in
        $logged_in = true;
    } else {
        $is_true = is_user_logged_in();
        $logged_in = ( $logged_in == 'true' || $logged_in == '1' ) ? $is_true : !$is_true;
    }

    if ( $not_post_id == 'default') {                 // **** pages
        $not_post_id = true;
    } else {
        $list = explode(',', str_replace(' ', '', $not_post_id));
        $not_post_id = !in_array( get_the_ID(), $list );
    }

    if ( $post_id == 'default') {                 // **** pages
        $post_id = true;
    } else {
        $list = explode(',', str_replace(' ', '', $post_id));
        $post_id = in_array( get_the_ID(), $list );
    }

    if ( $user_can == 'default') {              // **** user_can
        $user_can = true;
    } else {
        $user_can = current_user_can( strtolower( $user_can) );
    }

    $x = true;
    if ( $x == 'default') {
        $x = true;
    } else {
        $x = $show;
    }

    $all_true = $logged_in && $not_post_id && $post_id && $user_can;    // all true except device

    if ( !$all_true ) {                         // device irrelevant
        // $text .= '* ALL TRUE FAILED *';
        if ( !$show )
            return do_shortcode( $text );       // hide fails, so show it
        else
            return '';                          // show fails, so hide it

    } elseif ( $device == 'default') {          // so all other conditions passed, see if specified device
        // $text .= '* ALL TRUE, DEVICE DEFAULT *';
        if ( $show )
            return do_shortcode( $text );
        else
            return '';
    } else {
        // $text .= '* ALL TRUE, DEPENDS ON DEVICE *';
        if ( $show ) {
            $GLOBALS['wvrx_sc_show_hide'] = strtolower('show-' . $device);  // for [extra_menu]
        } else {
            $GLOBALS['wvrx_sc_show_hide'] = strtolower('hide-' . $device);
        }
        $ret = '<div class="wvr-' . $GLOBALS['wvrx_sc_show_hide'] . '">' . do_shortcode($text) . '</div>';
        unset( $GLOBALS['wvrx_sc_show_hide'] );
        return $ret;
    }
    return '';
}


// ===============  [header_image style='customstyle'] ===================
function wvrx_ts_sc_header_image($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',	// STYLE
	    'h' => '',
	    'w' => ''
    ), $args));

	$hdr = get_header_image();
	if (!$hdr)
		return '';
	$hdr = str_replace(array('http://', 'https://'),'//', $hdr);

	$width = weaverx_getopt_default('theme_width_int',1100);
	$custom_header_sizes = apply_filters( 'weaverx_custom_header_sizes', "(max-width: {$width}px) 100vw, 1920px" );

    $width = $w ?  $w  : get_custom_header()->width;
    $height = $h ? $h : get_custom_header()->height;
    $st = $style ? ' style="' . $style . '"' : '';
	$srcset = esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id ) );
	$sizes = esc_attr( $custom_header_sizes );

	if ( stripos($hdr, '.gif') !== false ) {
		$hdrimg = '<img src="' .  $hdr . '" width="' . $width .'" height="' . $height . '"'
		. $st . ' alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />' ;
	} else {
		$hdrimg = '<img src="' .  $hdr . '" srcset="' . $srcset . '" sizes="' . $sizes . '" width="' . $width .'" height="' . $height . '"'
		. $st . ' alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />' ;
	}

    return $hdrimg;
}

// ===============  [bloginfo arg='name'] ======================
function wvrx_ts_sc_bloginfo($args = '') {
    extract(shortcode_atts(array(
	    'arg' => 'name',		// a WP bloginfo name
	    'style' => ''		// wrap with style
    ), $args));

    $code = '';
    if ($style != '') $code = '<span style="' . $style . '">';
    $code .= esc_attr( get_bloginfo( $arg ));
    if ($style != '') $code .= '</span>';
    return $code;
}

// ===============  [site_title style='customstyle'] ======================
function wvrx_ts_sc_site_title($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',		/* styling for the header */
		'matchtheme' => false
    ), $args));

	$title = esc_html( get_bloginfo( 'name', 'display' ));

	$before = '';
	$after = '';

	if ( $matchtheme == 'true' || $matchtheme == 1 ) {
		$before = '<h1' . weaverx_title_class( 'site_title', false, 'site-title' ) . '><a href="' .  esc_url( home_url( '/' ) ) . '" title="' . $title . '" rel="home">';
		$after = '</a></h1>';
	}

    if ($style) {
        return $before . '<span style="' . $style . '">' . $title . '</span>' . $after;
    }
    return $before . $title . $after;

}

// ===============  [site_tagline style='customstyle'] ======================
function wvrx_ts_sc_site_tagline($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',		/* styling for the header */
		'matchtheme' => false
    ), $args));

    $title = get_bloginfo( 'description' );

	$before = '';
	$after = '';

	if ( $matchtheme == 'true' || $matchtheme == 1 ) {
		$before = '<h2' . weaverx_title_class( 'tagline', false, 'site-tagline' ) . '>';
		$after = '</h2>';
	}

    if ($style) {
        return $before . '<span style="' . $style . '">' . $title . '</span>' . $after;
    }
    return $before . $title . $after;
}

// ===============  [iframe src='address' height=nnn] ======================
function wvrx_ts_sc_iframe($args = '') {
    extract(shortcode_atts(array(
	    'src' => '',
	    'height' => '600', /* styling for the header */
	    'percent' => 100,
	    'style' => 'border:1px;'
    ), $args));

    $sty = $style ? ' style="' . $style . '"' : '';

    if (!$src) return __('<h4>No src address provided to [iframe].</h4>','weaverx-theme-support' /*adm*/);
    return "\n" . '<iframe src="' . $src . '" height="' .  $height . 'px" width="' . $percent . '%"' . $sty . '></iframe>' . "\n";
}

// ===============  [iframe src='address' height=nnn] ======================
function wvrx_ts_sc_login($args = '') {
    extract(shortcode_atts(array(
		'style' => ''
    ), $args));

	if ($style != '')
		return '<span class="wvrx-loginout" style="' . esc_attr($style) . '">' . wp_loginout( '', false ) . '</span>';
	else
		return '<span class="wvrx-loginout">' . wp_loginout( '', false ) . '</span>';
}

// ===============  [tab_group ] ======================
function wvrx_ts_sc_tab_group( $args, $content ) {
    extract( shortcode_atts( array(
	'border_color' => '',		// tab and pane bodder color - default #888
	'tab_bg' => '',			// normal bg color of tab (default #CCC)
	'tab_selected_color' => '',	// color of tab when selected (default #EEE)
	'pane_min_height' => '',	// min height of a pane to help make all even if needed
	'pane_bg' => ''			// bg color of pane
    ), $args ) );

    if (isset($GLOBALS['wvrx_ts_in_tab_container']) && $GLOBALS['wvrx_ts_in_tab_container']) {
	return __('<strong>Sorry, you cannot nest tab_containers.</strong>','weaverx-theme-support' /*adm*/);
    }

    // enqueue the theme support jslib only now when it will actually be needed!

    wp_enqueue_script('wvrxtsJSLib', wvrx_ts_plugins_url('/js/wvrx-ts-jslib', WVRX_TS_MINIFY . '.js'),array('jquery'),WVRX_TS_VERSION,true);

    if ( !isset( $GLOBALS['wvrx_ts_tab_id'] ) )
        $GLOBALS['wvrx_ts_tab_id'] = 1;
    else
        ++$GLOBALS['wvrx_ts_tab_id'];

    $group_id = 'wvr-tab-group-' . $GLOBALS['wvrx_ts_tab_id'];

    $css = '';	// default styles
    $add_style = '';
    if ($border_color != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane,#' .
            $group_id . '.wvr-tabs-style .wvr-tabs-nav span {border-color:' . $border_color . ";}\n";

    if ($pane_min_height != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane {min-height:' . $pane_min_height . ";}\n";

    if ($pane_bg != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-pane {background-color:' . $pane_bg . ";}\n";

    if ($tab_bg != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-nav span {background-color:' . $tab_bg . ";}\n";

    if ($tab_selected_color != '')
        $css .= '#' . $group_id . '.wvr-tabs-style .wvr-tabs-nav span.wvr-tabs-current,#' .
            $group_id . '.wvr-tabs-style .wvr-tabs-nav span:hover {background-color:' . $tab_selected_color . ";}\n";

    if ($css != '') {	// specified some style...
        $add_style = "<style type=\"text/css\">\n" . $css . "</style>\n";
    }

    $GLOBALS['wvrx_ts_in_tab_container'] = true;
    $GLOBALS['wvrx_ts_num_tabs'] = 0;

    do_shortcode( $content );	// process the tabs on this

    $out = '*** Unclosed or mismatched [tab_group] shortcodes ***';

    if ( isset( $GLOBALS['wvrx_ts_tabs'] ) && is_array( $GLOBALS['wvrx_ts_tabs'] ) ) {
        foreach ( $GLOBALS['wvrx_ts_tabs'] as $tab ) {
            $tabs[] = '<span>' . $tab['title'] . '</span>'. "\n";
            $panes[] = "\n" .'<div class="wvr-tabs-pane">' . $tab['content'] . '</div>';
        }
        $out = '<div id="' . $group_id . '" class="wvr-tabs wvr-tabs-style"> <!-- tab_group -->' . "\n"
            . '<div class="wvr-tabs-nav">' . "\n"
            . implode( '', $tabs ) . '</div>' . "\n"
            . '<div class="wvr-tabs-panes">'
            . implode( '', $panes ) . "\n"
            . '</div><div class="wvr-tabs-clear"></div>' . "\n"
            . '</div> <!-- end tab_group -->' . "\n";
    }

    // Forget globals we generated
    unset( $GLOBALS['wvrx_ts_in_tab_container'],$GLOBALS['wvrx_ts_tabs'],$GLOBALS['wvrx_ts_num_tabs']);

    return $add_style . $out;
}

function wvrx_ts_sc_tab( $args, $content ) {
    extract( shortcode_atts( array(
	'title' => 'Tab %d'
    ), $args ) );

    if ( ! isset( $GLOBALS['wvrx_ts_num_tabs'] ) ) {
        $GLOBALS['wvrx_ts_num_tabs'] = 0;
    }
    $cur = $GLOBALS['wvrx_ts_num_tabs'];
    $GLOBALS['wvrx_ts_tabs'][$cur] = array(
        'title' => sprintf( $title, $GLOBALS['wvrx_ts_num_tabs'] ),		// the title with number
        'content' => do_shortcode( $content ) );
    $GLOBALS['wvrx_ts_num_tabs']++;
}


// ===============  [youtube id=videoid sd=0 hd=0 related=0 https=0 privacy=0 w=0 h=0] ======================
function wvrx_ts_sc_youtube($args = '') {
    $share = '';
    if ( isset ( $args[0] ) )
	$share = trim($args[0]);


    // http://code.google.com/apis/youtube/player_parameters.html
    // not including: enablejsapi, fs,playerapiid,

    extract(shortcode_atts(array(
        'autohide' => '~!',
		'autoplay' => '0',
		'id' => '',
        'sd' => false,
        'related' => '0',
        'privacy' => false,
        'ratio' => false,
        'center' => '1',
        'border' => '0',
        'color' => false,
        'color1' => false,
        'color2' => false,
        'controls' => '1',
        'disablekb' => '0',
        'egm' => '0',
        'fs' => '1',
        'fullscreen' => 1,
        'hd' => '0',
        'iv_load_policy' => '1',
        'loop' => '0',
        'modestbranding' => '0',
        'origin' => false,
        'percent' => 100,
        'playlist' => false,
        'rel' => '0',
        'showinfo' => '1',
        'showsearch' => '1',
        'start' => false,
        'theme' => 'dark',
        'wmode' => 'transparent'

    ), $args));

    if (!$share && !$id)
        return __('<strong>No share or id values provided for youtube shortcode.</strong>','weaverx-theme-support' /*adm*/);

    if ($share)	{	// let the share override any id
        $share = str_replace('youtu.be/','',$share);
        if (strpos($share,'youtube.com/watch') !== false) {
            $share = str_replace('www.youtube.com/watch?v=', '', $share);
            $share = str_replace('&amp;','+',$share);
            $share = str_replace('&','+',$share);
        }
        $share = str_replace('http://','',$share);
        $share = str_replace('https://','',$share);
        if ($share)
            $id = $share;
    }

    $opts = $id . '%%';

    $opts = wvrx_ts_add_url_opt($opts, $hd != '0', 'hd=1');
    $opts = wvrx_ts_add_url_opt($opts, $autohide != '~!', 'autohide='.$autohide);
    $opts = wvrx_ts_add_url_opt($opts, $autoplay != '0', 'autoplay=1');
    $opts = wvrx_ts_add_url_opt($opts, $border != '0', 'border=1');
    $opts = wvrx_ts_add_url_opt($opts, $color, 'color='.$color);
    $opts = wvrx_ts_add_url_opt($opts, $color1, 'color1='.$color1);
    $opts = wvrx_ts_add_url_opt($opts, $color2, 'color2='.$color2);
    $opts = wvrx_ts_add_url_opt($opts, $controls != '1', 'controls=0');
    $opts = wvrx_ts_add_url_opt($opts, $disablekb != '0', 'disablekb=1');
    $opts = wvrx_ts_add_url_opt($opts, $egm != '0', 'egm=1');
    $opts = wvrx_ts_add_url_opt($opts, true, 'fs='.$fs);
    $opts = wvrx_ts_add_url_opt($opts, true, 'iv_load_policy='.$iv_load_policy);
    $opts = wvrx_ts_add_url_opt($opts, $loop != '0', 'loop=1');
    $opts = wvrx_ts_add_url_opt($opts, $modestbranding != '0', 'modestbranding=1');
    $opts = wvrx_ts_add_url_opt($opts, $origin, 'origin='.$origin);
    $opts = wvrx_ts_add_url_opt($opts, $playlist, 'playlist='.$playlist);
    $opts = wvrx_ts_add_url_opt($opts, true, 'rel='.$rel);
    $opts = wvrx_ts_add_url_opt($opts, true, 'showinfo=' . $showinfo);
    $opts = wvrx_ts_add_url_opt($opts, $showsearch != '1', 'showsearch=0');
    $opts = wvrx_ts_add_url_opt($opts, $start, 'start='.$start);
    $opts = wvrx_ts_add_url_opt($opts, $theme != 'dark', 'theme=light');
    $opts = wvrx_ts_add_url_opt($opts, $wmode, 'wmode='.$wmode);

    $url = '//';

    if ($privacy) $url .= 'www.youtube-nocookie.com';
    else $url .= 'www.youtube.com';

    $opts = str_replace('%%+','%%?', $opts);
    $opts = str_replace('%%','', $opts);
    $opts = str_replace('+','&amp;', $opts);

    $url .= '/embed/' . $opts;


    $allowfull = $fullscreen ? ' allowfullscreen="allowfullscreen"' : '';

    $cntr1 = $center ? "<div class=\"wvrx-video wvrx-youtube\" style=\"margin-left:auto;margin-right:auto;max-width:{$percent}%;\">" :
                       "<div class=\"wvrx-video wvrx-youtube\" style=\"max-width:{$percent}%;\">";
    $cntr2 = '</div>';
    $h = 9; $w = 16;
    if ( $sd ) {
        $h = 3; $w = 4;
    }

	$ret ="\n" . $cntr1 . '<iframe src="' . $url
     . '" frameborder="0" width="'.$w.'" height="' . $h . '" frameborder="0" ' . $allowfull . '></iframe>'
     . $cntr2 . "\n";

    return $ret;
}

// ===============  [vimeo id=videoid sd=0 w=0 h=0 color=#hex autoplay=0 loop=0 portrait=1 title=1 byline=1] ======================
function wvrx_ts_sc_vimeo($args = '') {
    $share = '';
    if ( isset ( $args[0] ) )
        $share = trim($args[0]);

    extract(shortcode_atts(array(
        'id' => '',
        'sd' => false,
        'color' => '',
        'autoplay' => false,
        'loop' => false,
        'portrait' => true,
        'title' => true,
        'byline' => true,
        'percent' => 100,
        'center' => '1'
        ), $args));

    if (!$share && !$id) return __('<strong>No share or id values provided for vimeo shortcode.</strong>','weaverx-theme-support' /*adm*/);

    if ($share)	{	// let the share override any id
        $share = str_replace('http://vimeo.com/','',$share);
        if ($share) $id = $share;
    }

    $opts = $id . '##';

    $opts = wvrx_ts_add_url_opt($opts, $autoplay, 'autoplay=1');
    $opts = wvrx_ts_add_url_opt($opts, $loop, 'loop=1');
    $opts = wvrx_ts_add_url_opt($opts, $color, 'color=' . $color);
    $opts = wvrx_ts_add_url_opt($opts, !$portrait, 'portrait=0');
    $opts = wvrx_ts_add_url_opt($opts, !$title, 'title=0');
    $opts = wvrx_ts_add_url_opt($opts, !$byline, 'byline=0');

    $url = '//player.vimeo.com/video/';

    $opts = str_replace('##+','##?', $opts);
    $opts = str_replace('##','', $opts);
    $opts = str_replace('+','&amp;', $opts);

    $url .= $opts;

    if (function_exists('weaverii_use_mobile'))
        if (weaverii_use_mobile('mobile')) $percent = 100;


    $cntr1 = $center ? "<div class=\"wvrx-video wvrx-vimeo\" style=\"margin-left:auto;margin-right:auto;max-width:{$percent}%;\">" :
                       "<div class=\"wvrx-video wvrx-vimeo\" style=\"max-width:{$percent}%;\">";
    $cntr2 = '</div>';
    $h = 9; $w = 16;
    if ( $sd ) {
        $h = 3; $w = 4;
    }

    $ret = "\n" . $cntr1 . '<iframe src="' . $url
     . '" width="' . $w . '" height="' . $h . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
     . $cntr2 . "\n";

    return $ret;
}

// ===== video utils =====

function wvrx_ts_add_url_opt($opts, $add, $add_val) {
    if ($add) {
	$opts = $opts . '+' . $add_val;
    }
    return $opts;
}



function wvrx_ts_sc_html($vals = '') {           //  [html style='customstyle'] - all ======================
    $tag = 'span';
    if ( isset ( $vals[0] ) )
	$tag = trim( $vals[0]);

    extract(shortcode_atts(array(
	'args' => ''
    ), $vals));
    if ($args) $args = ' ' . $args;
    return '<' . $tag . $args .  '>';
}

function wvrx_ts_sc_div($vals = '',$text) {              // [div] - all  ===================
    extract(shortcode_atts(array(
	'id' => '',
	'class' => '',
	'style' => ''
    ), $vals));

    $args = '';
    if ($id) $args .= ' id="' . $id . '"';
    if ($class) $args .= ' class="' . $class . '"';
    if ($style) $args .= ' style="' . $style . '"';

    return '<div' . $args . '>' . do_shortcode($text) . '</div>';
}

function wvrx_ts_sc_span($vals = '',$text) {     // [span] - all ==================
    extract(shortcode_atts(array(
	'id' => '',
	'class' => '',
	'style' => ''
    ), $vals));

    $args = '';
    if ($id) $args .= ' id="' . $id . '"';
    if ($class) $args .= ' class="' . $class . '"';
    if ($style) $args .= ' style="' . $style . '"';

    return '<span' . $args . '>' . do_shortcode($text) . '</span>';
}

function wvrx_ts_weaverx_sc_info() {           // [info]  ======================
    //global $current_user;
    $out = __('<strong>Theme/User Info</strong><hr />','weaverx-theme-support' /*adm*/);

    $current_user = wp_get_current_user();
    if (isset($current_user->display_name)) {
	$out .= __('<em>User:</em> ','weaverx-theme-support' /*adm*/) . $current_user->display_name . '<br />';
    }
    $out .= '&nbsp;&nbsp;' . wp_register('','<br />',false);
    $out .= '&nbsp;&nbsp;' . wp_loginout('',false) . '<br />';

    $agent = __('Not Available','weaverx-theme-support' /*adm*/);
    if (isset($_SERVER["HTTP_USER_AGENT"]) )
	$agent = $_SERVER['HTTP_USER_AGENT'];
    $out .= __('<em>User Agent</em>:','weaverx-theme-support' /*adm*/) . ' <small>' . $agent . '</small>';
    $out .= '<div id="example"></div>
<script type="text/javascript">
var txt = "";
var myWidth;
if( typeof( window.innerWidth ) == "number" ) {
//Non-IE
myWidth = window.innerWidth;
} else if( document.documentElement &&
( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
//IE 6+ in "standards compliant mode"
myWidth = document.documentElement.clientWidth;
} else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
//IE 4 compatible
myWidth = document.body.clientWidth;
}
txt+= "<em>Browser Width: </em>" + myWidth + " px</br>";
document.getElementById("example").innerHTML=txt;
</script>';

    $out .= __('<em>Feed title:</em> ','weaverx-theme-support' /*adm*/) . get_bloginfo_rss('name') . '<br />' . get_wp_title_rss();

    $out .= __('<br /><em>You are using</em> WordPress ','weaverx-theme-support' /*adm*/) . $GLOBALS['wp_version'] . '<br /><em>PHP Version:</em> ' . phpversion();
    $out .= __('<br /><em>Memory:</em> ','weaverx-theme-support' /*adm*/) . round(memory_get_usage()/1024/1024,2) . 'M of ' .  (int)ini_get('memory_limit') . 'M <hr />';
    return $out;
}


function wvrx_ts_set_shortcodes($sc_list, $prefix) {
    foreach ($sc_list as $sc_name => $sc_func) {
        remove_shortcode($prefix . $sc_name);
        add_shortcode($prefix . $sc_name,$sc_func);
    }
}

// ===============  Utilities ======================


?>
