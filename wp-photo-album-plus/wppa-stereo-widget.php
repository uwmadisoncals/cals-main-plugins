<?php
/* wppa-stereo-widget.php
* Package: wp-photo-album-plus
*
* display the top rated photos
* Version 6.9.12
*/

class wppaStereoWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_stereo_widget', 'description' => __( 'Display stereo photo settings dialog', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_stereo_widget', __( 'WPPA+ Stereo Photo Settings', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {
		global $wpdb;

		wppa_initialize_runtime();

        wppa( 'in_widget', 'stereo' );
		wppa_bump_mocc();

		extract( $args );

		$instance 		= wp_parse_args( (array) $instance, array( 'title' => __('Stereo Photo Settings', 'wp-photo-album-plus'), 'logonly' => 'no' ) );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

 		$widget_title 	= apply_filters('widget_title', $instance['title'] );

		$widget_content = "\n".'<!-- WPPA+ stereo Widget start -->';

		$widget_content .= wppa_get_stereo_html();

		$widget_content .= '<div style="clear:both;" data-wppa="yes" ></div>';
		$widget_content .= "\n".'<!-- WPPA+ stereo Widget end -->';

		echo "\n" . $before_widget;
		if ( !empty( $widget_title ) ) { echo $before_title . $widget_title . $after_title; }
		echo $widget_content . $after_widget;

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;

		//Defaults
		$instance 		= wp_parse_args( (array) $instance, array( 'title' => __( 'Stereo Photo Settings', 'wp-photo-album-plus ' ), 'logonly' => 'no' ) );

		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['logonly'] = $new_instance['logonly'];

        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {

		//Defaults
		$instance 		= wp_parse_args( (array) $instance, array( 'title' => __( 'Stereo Photo Settings', 'wp-photo-album-plus ' ), 'logonly' => 'no' ) );

		// Title
		echo
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Loggedin only
		echo
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
    }

} // class wppaStereoWidget

// register wppaStereoWidget widget
add_action('widgets_init', 'wppa_register_wppaStereoWidget' );

function wppa_register_wppaStereoWidget() {
	register_widget("wppaStereoWidget");
}
