<?php
/* wppa-stats-widget.php
* Package: wp-photo-album-plus
*
* display the stats widget
* Version 6.9.14
*
*/
class WppaStatsWidget extends WP_Widget {

	// Default settings
	var $defaults = array( 	'title' 			=> '',
							'ip' 				=> 'no',
							'browser' 			=> 'no',
							'dayno' 			=> 'no',
							'sessions-active' 	=> 'no',
							'sessions-day' 		=> 'no',
							'reg-users' 		=> 'no',
							'albums'  			=> 'no',
							'mediaitems' 		=> 'no',
							'photos'  			=> 'no',
							'videos' 			=> 'no',
							'audios' 			=> 'no',
							'pdfs' 				=> 'no',
							'comments' 			=> 'no',
							'rating' 			=> 'no',
							'logonly' 			=> 'no',
							);

    // Constructor
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_stats_widget', 'description' => __( 'WPPA Statistics', 'your-ip-widget' ) );
		parent::__construct( 'your_ip', __( 'WPPA Stats', 'wppa_stats_widget' ), $widget_ops );
    }

	// Widget
    function widget( $args, $instance ) {

		global $wpdb;
		global $widget_content;

        extract( $args );

		$this->defaults['title'] = __( 'Statistics', 'wp-photo-album-plus' );
		$instance = wp_parse_args( (array) 	$instance, $this->defaults );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}


		$widget_content = '';

		if ( wppa_checked( $instance['ip'] ) ) {
			$widget_content .= __('Your ip is', 'wp-photo-album-plus') . ': <b>'.$_SERVER['REMOTE_ADDR'].'</b><br />';
		}
		if ( wppa_checked( $instance['browser'] ) ) {
			$browser = isset( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : 'unknown';
			$widget_content .= __('Your browser is', 'wp-photo-album-plus') . ': <b>' . $browser .'</b><br />';
		}
		if ( wppa_checked( $instance['dayno'] ) ) {
			$widget_content .= __('Today is day no', 'wp-photo-album-plus') . ': <b>' . ( date_i18n( 'z', time() ) + 1 ) . '</b><br />';
		}
		if ( wppa_checked( $instance['sessions-active'] ) ) {
			$sescount = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_session WHERE `timestamp` > '" . ( time() - 3600 ) . "'" );
			$widget_content .= __('Number of active sessions', 'wp-photo-album-plus') . ': <b>' . $sescount . '</b><br />';
		}
		if ( wppa_checked( $instance['sessions-day'] ) ) {
			$t = time() - 3600*24;
			$daysescount = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_session WHERE `timestamp` > '" . $t . "'" );
			$robots 	 = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_session WHERE `timestamp` > '" . $t . "' AND `data` LIKE '%\"isrobot\";b:1;%'" );
			$widget_content .= 	__('Number of sessions last 24 hours', 'wp-photo-album-plus') . ': <b>' . $daysescount . '</b><br />' .
								__('Of which robots', 'wp-photo-album-plus') . ': <b>' . $robots . '</b><br />';
		}
		if ( wppa_checked( $instance['reg-users'] ) ) {
			$users = $wpdb->get_var( "SELECT COUNT(*) FROM `" . $wpdb->prefix . 'users' . "`" );
			$widget_content .= __('Number of registered users', 'wp-photo-album-plus') . ': <b>' . $users . '</b><br />';
		}
		if ( wppa_checked( $instance['albums'] ) ) {
			$albums = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums" );
			if ( $albums ) {
				$widget_content .= __('Number of albums', 'wp-photo-album-plus') . ': <b>' . $albums . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['mediaitems'] ) ) {
			$items = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos" );
			if ( $items ) {
				$widget_content .= __('Number of media items', 'wp-photo-album-plus') . ': <b>' . $items . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['photos'] ) ) {
			$photos = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE `ext` <> 'xxx' AND `filename` NOT LIKE '%.pdf'" );
			if ( $photos ) {
				$widget_content .= __('Number of photos', 'wp-photo-album-plus') . ': <b>' . $photos . '</b><br />';
			}
		}
		$multi = false;
		if ( wppa_checked( $instance['videos'] ) && wppa_switch( 'enable_video' ) ) {
			$multi = $wpdb->get_col( "SELECT `id` FROM $wpdb->wppa_photos WHERE `ext` = 'xxx'" );
			$cnt = 0;
			foreach( $multi as $item ) {
				if ( wppa_is_video( $item ) ) $cnt++;
			}
			if ( $cnt ) {
				$widget_content .= __('Number of videos', 'wp-photo-album-plus') . ': <b>' . $cnt . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['audios'] ) && wppa_switch( 'enable_audio' ) ) {
			if ( ! $multi ) {
				$multi = $wpdb->get_col( "SELECT `id` FROM $wpdb->wppa_photos WHERE `ext` = 'xxx'" );
			}
			$cnt = 0;
			foreach( $multi as $item ) {
				if ( wppa_has_audio( $item ) ) $cnt++;
			}
			if ( $cnt ) {
				$widget_content .= __('Number of audios', 'wp-photo-album-plus') . ': <b>' . $cnt . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['pdfs'] ) && wppa_can_pdf() ) {
			$pdfs = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE `filename` LIKE '%.pdf'" );
			if ( $pdfs ) {
				$widget_content .= __('Number of pdfs', 'wp-photo-album-plus') . ': <b>' . $pdfs . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['comments'] ) && wppa_switch( 'show_comments' ) ) {
			$comments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments" );
			if ( $comments ) {
				$widget_content .= __('Number of comments', 'wp-photo-album-plus') . ': <b>' . $comments . '</b><br />';
			}
		}
		if ( wppa_checked( $instance['rating'] ) && wppa_switch( 'rating_on' ) ) {
			$ratings = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_rating" );
			if ( $ratings ) {
				$widget_content .= __('Number of ratings', 'wp-photo-album-plus') . ': <b>' . $ratings . '</b><br />';
			}
		}

		if ( substr( $widget_content, -6 ) == '<br />' ) {
			$widget_content = substr( $widget_content, 0, strlen( $widget_content ) - 6 );
		}

		echo $before_widget . $before_title . $instance['title'] . $after_title . $widget_content . $after_widget;
    }

    // Update settings
    function update( $new_instance, $old_instance ) {
		$instance = $new_instance;

		// Defaults
		$this->defaults['title'] = __( 'Statistics', 'wp-photo-album-plus' );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    // Settings dialog
    function form( $instance ) {

		// Defaults
		$this->defaults['title'] = __( 'Statistics', 'wp-photo-album-plus' );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Title
		echo
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

 		// Ip
		echo
		wppa_widget_checkbox( 	$this,
								'ip',
								$instance['ip'],
								__( 'Show IP address', 'wp-photo-album-plus' ),
								__( 'Tick this box when the widget is used on non-cached pages only', 'wp-photo-album-plus' )
								);

 		// Ip
		echo
		wppa_widget_checkbox( 	$this,
								'browser',
								$instance['browser'],
								__( 'Show browser', 'wp-photo-album-plus' ),
								__( 'Tick this box when the widget is used on non-cached pages only', 'wp-photo-album-plus' )
								);

		// Dayno
		echo
		wppa_widget_checkbox( 	$this,
								'dayno',
								$instance['dayno'],
								__( 'Show day of the year', 'wp-photo-album-plus' )
								);

		// Active sessions
		echo
		wppa_widget_checkbox( 	$this,
								'sessions-active',
								$instance['sessions-active'],
								__( 'Show active sessions', 'wp-photo-album-plus' )
								);

		// 24 hrs sessions
		echo
		wppa_widget_checkbox( 	$this,
								'sessions-day',
								$instance['sessions-day'],
								__( 'Show 24hr sessions', 'wp-photo-album-plus' )
								);

		echo
		wppa_widget_checkbox( 	$this,
								'reg-users',
								$instance['reg-users'],
								__( 'Show number of registered users', 'wp-photo-album-plus' )
								);

		echo
		wppa_widget_checkbox( 	$this,
								'albums',
								$instance['albums'],
								__( 'Show number of albums', 'wp-photo-album-plus' )
								);

		echo
		wppa_widget_checkbox( 	$this,
								'mediaitems',
								$instance['mediaitems'],
								__( 'Show number of media items', 'wp-photo-album-plus' )
								);

		echo
		wppa_widget_checkbox( 	$this,
								'photos',
								$instance['photos'],
								__( 'Show number of photos', 'wp-photo-album-plus' )
								);

		if ( wppa_switch( 'enable_video' ) ) {
			echo
			wppa_widget_checkbox( 	$this,
									'videos',
									$instance['videos'],
									__( 'Show number of videos', 'wp-photo-album-plus' )
									);
		}

		if ( wppa_switch( 'enable_audio' ) ) {
			echo
			wppa_widget_checkbox( 	$this,
									'audios',
									$instance['videos'],
									__( 'Show number of audios', 'wp-photo-album-plus' )
									);
		}

		if ( wppa_can_pdf() ) {
			echo
			wppa_widget_checkbox( 	$this,
									'pdfs',
									$instance['pdfs'],
									__( 'Show number of pdfs', 'wp-photo-album-plus' )
									);
		}

		if ( wppa_switch( 'show_comments' ) ) {
			echo
			wppa_widget_checkbox( 	$this,
									'comments',
									$instance['comments'],
									__( 'Show number of comments', 'wp-photo-album-plus' )
									);

		}

		if ( wppa_switch( 'rating_on' ) ) {
			echo
			wppa_widget_checkbox( 	$this,
									'rating',
									$instance['rating'],
									__( 'Show number of ratings', 'wp-photo-album-plus' )
									);

		}

		// Loggedin only
		echo
		wppa_widget_checkbox( 	$this,
								'logonly',
								$instance['logonly'],
								__( 'Show to logged in visitors only', 'wp-photo-album-plus' )
								);

   }

} // class WppaStatsWidget

// register WppaStatsWidget widget
add_action('widgets_init', 'wppa_stats_register_widget');
function wppa_stats_register_widget() {
	register_widget("WppaStatsWidget");
}
