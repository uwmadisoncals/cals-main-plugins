<?php
/*
 *  Weaver X Widgets and shortcodes - widgets
 */


class WeaverX_Widget_Text extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'WeaverX_Widget_Text',
		 'description' => __('Text Widget with Two Columns - with HTML and shortcode support. Also adds shortcodes to standard Text widget.','weaverx-theme-support' /*adm*/));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('wvrx2_text', __('Weaver Text 2 Col','weaverx-theme-support' /*adm*/), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$text = apply_filters( 'weaverx_text', $instance['text'], $instance );
		$text2 = apply_filters( 'weaverx_text', $instance['text2'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="textwidget"><div style="float: left; width: 48%; padding-right: 2%;">
			<?php
			if ($instance['filter']) {
				echo(wpautop($text)); echo('</div><div style="float: left; width: 48%; padding-left: 2%;">');
				echo(wpautop($text2)); echo('</div><div style="clear: both;"></div>');
			} else {
			    echo($text); echo('</div><div style="float: left; width: 48%; padding-left: 2%;">');
			    echo($text2); echo('</div><div style="clear: both;"></div>');
			}
			?>
			</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') ) {
			$instance['text'] =  $new_instance['text'];
			$instance['text2'] =  $new_instance['text2'];
		}
		else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['text2'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text2']) ) );
		}
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'text2' => '',  'filter' => 0) );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
		$text2 = format_to_edit($instance['text2']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo('Title:' /*a*/ ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text2'); ?>" name="<?php echo $this->get_field_name('text2'); ?>"><?php echo $text2; ?></textarea>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />
			&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php echo 'Automatically add paragraphs'; ?></label></p>
<?php
	}
}

/**
 * Weaver X Per Page Text
 */
class WeaverX_Widget_PPText extends WP_Widget {

    function __construct() {
	$widget_ops = array('classname' => 'wvrx_widget_pptext', 'description' =>
	    __('Display text on a Per Page basis. Add to Widget area to see instructions.','weaverx-theme-support' /*adm*/) );
	parent::__construct('wvrx_pptext', __('Weaver Per Page Text','weaverx-theme-support' /*adm*/), $widget_ops);
    }

    function widget( $args, $instance ) {
	extract($args);
	$title = get_post_meta(get_the_ID(),'wvrx_ts_pp_title',true);
	$text = get_post_meta(get_the_ID(),'wvrx_ts_pp_text',true);

	if (empty($title) && empty($text))
	    return;

	echo $before_widget;
	if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
	    echo do_shortcode($text);
	echo $after_widget;
    }

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
?>
<p><?php _e('This widget will work like a text widget, but the title and content are defined by custom
fields set on a Per Page basis. For any page, define the Custom Field <em>wvrx_ts_pp_title</em>
if you want a title, and define Custom Field <em>wvrx_ts_pp_text</em> as the content. Content can include arbitrary text,
HTML, and shortcodes. The text will not automatically add paragraphs. The widget will display only if the custom
fields are defined when that page is displayed. (This widget won\'t display on the default blog or other archive-like pages.)','weaverx-theme-support' /*adm*/); ?><p>
<?php
	}
}

/**
 * Weaver X login
 */
class WeaverX_Widget_Login extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'wvrx_widget_login', 'description' => __( "Log in/out, admin", 'weaverx-theme-support' /*adm*/ ) );
		parent::__construct('wvrx_login', __('Weaver Login','weaverx-theme-support' /*adm*/), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Login', 'weaverx-theme-support' /*adm*/ ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		global $current_user;
		$current_user = wp_get_current_user();
		if (isset($current_user->display_name))
		echo '<span class="wvrx-welcome-user">' . __('Welcome','weaverx-theme-support' /*adm*/) . ' ' . $current_user->display_name . ".</span><br />\n";
?>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		</ul>
<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);

	    return $instance;
	}

	function form( $instance ) {
	    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	    $title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','weaverx-theme-support' /*adm*/); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}


add_action("widgets_init", "wvrx_ts_load_widgets");
add_filter('weaverx_text', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');		// add to standard text widget, too.

function wvrx_ts_load_widgets() {
	register_widget('WeaverX_Widget_Text');
	register_widget('WeaverX_Widget_PPText');
	register_widget('WeaverX_Widget_Login');
}

?>
