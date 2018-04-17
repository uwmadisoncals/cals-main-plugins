<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function my_calendar_add_feed() {
	add_feed( 'my-calendar-rss', 'my_calendar_rss' );
	add_feed( 'my-calendar-ics', 'my_calendar_ical' );
}

if ( ! function_exists( 'is_ssl' ) ) {
	function is_ssl() {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
				return true;
			}
			if ( '1' == $_SERVER['HTTPS'] ) {
				return true;
			}
		} elseif ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}

		return false;
	}
}

function my_calendar_getUsers( $group = 'authors' ) {
	return mc_get_users( $group );
}

// mod from Mike T
function mc_get_users( $group = 'authors' ) {
	global $blog_id;
	$count = count_users( 'time' );
	$args  = array(
		'blog_id' => $blog_id,
		'orderby' => 'display_name',
		'fields'  => array( 'ID', 'user_nicename', 'display_name' )
	);
	$args  = apply_filters( 'mc_filter_user_arguments', $args, $count, $group );
	$users = new WP_User_Query( $args );

	return $users->get_results();
}

function mc_selected_users( $selected = '', $group = 'authors' ) {
	$selected = explode( ',', $selected );
	$users    = mc_get_users( $group );
	$options  = '';
	foreach ( $users as $u ) {
		if ( in_array( $u->ID, $selected ) ) {
			$checked = ' selected="selected"';
		} else {
			$checked = '';
		}
		$display_name = ( $u->display_name == '' ) ? $u->user_nicename : $u->display_name;
		$options     .= '<option value="' . $u->ID . '"' . $checked . ">$display_name</option>\n";
	}

	return $options;
}

function mc_plugin_action( $links, $file ) {
	if ( $file == plugin_basename( dirname( __FILE__ ) . '/my-calendar.php' ) ) {
		$links[] = "<a href='admin.php?page=my-calendar-config'>" . __( 'Settings', 'my-calendar' ) . "</a>";
		$links[] = "<a href='admin.php?page=my-calendar-help'>" . __( 'Help', 'my-calendar' ) . "</a>";
	}

	return $links;
}

function mc_file_exists( $file ) {
	$dir    = plugin_dir_path( __FILE__ );
	$base   = basename( $dir );
	$return = apply_filters( 'mc_file_exists', false, $file );
	if ( $return ) {
		return true;
	}
	if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
		return true;
	}
	if ( file_exists( str_replace( $base, 'my-calendar-custom', $dir ) . $file ) ) {
		return true;
	}

	return false;
}

function mc_get_file( $file, $type = 'path' ) {
	$dir  = plugin_dir_path( __FILE__ );
	$url  = plugin_dir_url( __FILE__ );
	$base = basename( $dir );
	$path = ( $type == 'path' ) ? $dir . $file : $url . $file;
	if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
		$path = ( $type == 'path' ) ? get_stylesheet_directory() . '/' . $file : get_stylesheet_directory_uri() . '/' . $file;
	}
	if ( file_exists( str_replace( $base, 'my-calendar-custom', $dir ) . $file ) ) {
		$path = ( $type == 'path' ) ? str_replace( $base, 'my-calendar-custom', $dir ) . $file : str_replace( $base, 'my-calendar-custom', $url ) . $file;
	}
	$path = apply_filters( 'mc_get_file', $path, $file );

	return $path;
}

add_action( 'wp_enqueue_scripts', 'mc_register_styles' );
function mc_register_styles() {
	global $wp_query;
	$stylesheet = apply_filters( 'mc_registered_stylesheet', mc_get_style_path( get_option( 'mc_css_file' ), 'url' ) );
	wp_register_style( 'my-calendar-reset', plugins_url( 'css/reset.css', __FILE__ ) );
	wp_register_style( 'my-calendar-style', $stylesheet, array( 'dashicons', 'my-calendar-reset' ) );
	$admin_stylesheet = plugins_url( 'css/mc-admin.css', __FILE__ );
	wp_register_style( 'my-calendar-admin-style', $admin_stylesheet );
	if ( current_user_can( 'mc_manage_events' ) ) {
		wp_enqueue_style( 'my-calendar-admin-style' );
	}
	$this_post = $wp_query->get_queried_object();
	$id        = ( is_object( $this_post ) && isset( $this_post->ID ) ) ? $this_post->ID : false;
	$js_array  = ( get_option( 'mc_show_js' ) != '' ) ? explode( ",", get_option( 'mc_show_js' ) ) : array();
	$css_array = ( get_option( 'mc_show_css' ) != '' ) ? explode( ",", get_option( 'mc_show_css' ) ) : array();
	// check whether any scripts are actually enabled.
	if ( get_option( 'mc_calendar_javascript' ) != 1 || get_option( 'mc_list_javascript' ) != 1 || get_option( 'mc_mini_javascript' ) != 1 || get_option( 'mc_ajax_javascript' ) != 1 ) {
		if ( @in_array( $id, $js_array ) || get_option( 'mc_show_js' ) == '' || is_singular( 'mc-events' ) ) {
			wp_enqueue_script( 'jquery' );
			if ( get_option( 'mc_gmap' ) == 'true' ) {
				$api_key = get_option( 'mc_gmap_api_key' );
				if ( $api_key ) {
					wp_enqueue_script( 'gmaps', "https://maps.googleapis.com/maps/api/js?key=$api_key" );
					wp_enqueue_script( 'gmap3', plugins_url( 'js/gmap3.min.js', __FILE__ ), array( 'jquery' ) );
				}
			}		
		}
	}
	if ( get_option( 'mc_use_styles' ) != 'true' ) {
		if ( @in_array( $id, $css_array ) || get_option( 'mc_show_css' ) == '' ) {
			wp_enqueue_style( 'my-calendar-style' );
		}
	}
	if ( mc_is_tablet() && mc_file_exists( 'mc-tablet.css' ) ) {
		$tablet = mc_get_file( 'mc-tablet.css' );
		wp_register_style( 'my-calendar-tablet-style', $tablet );
		wp_enqueue_style( 'my-calendar-tablet-style' );
	}
	if ( mc_is_mobile() && mc_file_exists( 'mc-mobile.css' ) ) {
		$mobile = mc_get_file( 'mc-mobile.css' );
		wp_register_style( 'my-calendar-mobile-style', $mobile );
		wp_enqueue_style( 'my-calendar-mobile-style' );
	}
}

// Function to add the calendar style into the header
function my_calendar_wp_head() {
	global $wpdb, $wp_query;
	$mcdb  = $wpdb;
	$array = array();

	if ( get_option( 'mc_use_styles' ) != 'true' ) {
		$this_post = $wp_query->get_queried_object();
		$id        = ( is_object( $this_post ) && isset( $this_post->ID ) ) ? $this_post->ID : false;
		$array     = ( get_option( 'mc_show_css' ) != '' ) ? explode( ",", get_option( 'mc_show_css' ) ) : $array;
		if ( @in_array( $id, $array ) || get_option( 'mc_show_css' ) == '' ) {
			// generate category colors
			$category_styles = $inv = $type = $alt = '';
			$categories      = $mcdb->get_results( "SELECT * FROM " . my_calendar_categories_table() . " ORDER BY category_id ASC" );
			foreach ( $categories as $category ) {
				$class = mc_category_class( $category, 'mc_' );
				$hex   = ( strpos( $category->category_color, '#' ) !== 0 ) ? '#' : '';
				$color = $hex . $category->category_color;
				if ( $color != '#' ) {
					$hcolor = mc_shift_color( $category->category_color );
					if ( get_option( 'mc_apply_color' ) == 'font' ) {
						$type = 'color';
						$alt  = 'background';
					} else if ( get_option( 'mc_apply_color' ) == 'background' ) {
						$type = 'background';
						$alt  = 'color';
					}
					if ( get_option( 'mc_inverse_color' ) == 'true' ) {
						$inverse = mc_inverse_color( $color );
						$inv     = "$alt: $inverse;";
					}
					if ( get_option( 'mc_apply_color' ) == 'font' || get_option( 'mc_apply_color' ) == 'background' ) {
						// always an anchor as of 1.11.0, apply also to title
						$category_styles .= "\n.mc-main .$class .event-title, .mc-main .$class .event-title a { $type: $color; $inv }";
						$category_styles .= "\n.mc-main .$class .event-title a:hover, .mc-main .$class .event-title a:focus { $type: $hcolor;}";
					}
				}
			}
			$all_styles = "
<style type=\"text/css\">
<!--
/* Styles by My Calendar - Joseph C Dolson http://www.joedolson.com/ */
$category_styles
.mc-event-visible {
	display: block!important;
}
-->
</style>";
			echo $all_styles;
		}
	}
}

// Function to deal with events posted by a user when that user is deleted
function mc_deal_with_deleted_user( $id ) {
	global $wpdb;
	$mcdb = $wpdb;
	// Do the queries
	// This may not work quite right in multi-site. Need to explore further when I have time.
	$mcdb->get_results( "UPDATE " . my_calendar_table() . " SET event_author=" . esc_sql( apply_filters( 'mc_deleted_author', $mcdb->get_var( "SELECT MIN(ID) FROM " . $mcdb->prefix . "users", 0, 0 ) ) ) . " WHERE event_author=" . $id );
	$mcdb->get_results( "UPDATE " . my_calendar_table() . " SET event_host=" . esc_sql( apply_filters( 'mc_deleted_host', $mcdb->get_var( "SELECT MIN(ID) FROM " . $mcdb->prefix . "users", 0, 0 ) ) ) . " WHERE event_host=" . $id );
}

// Function to add the javascript to the admin header
function my_calendar_admin_js() {
	if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'my-calendar' ) !== false ) {
		wp_enqueue_script( 'mc.admin', plugins_url( 'js/jquery.admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ) );
		wp_localize_script( 'mc.admin', 'thumbHeight', get_option( 'thumbnail_size_h' ) );		
	}
	if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'my-calendar' || $_GET['page'] == 'my-calendar-groups' || $_GET['page'] == 'my-calendar-locations' ) ) {
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'pickadate', plugins_url( 'js/pickadate/picker.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'pickadate.date', plugins_url( 'js/pickadate/picker.date.js', __FILE__ ), array( 'pickadate' ) );
		wp_enqueue_script( 'pickadate.time', plugins_url( 'js/pickadate/picker.time.js', __FILE__ ), array( 'pickadate' ) );
		wp_localize_script( 'pickadate.date', 'mc_months', array(
			date_i18n( 'F', strtotime( 'January 1' ) ),
			date_i18n( 'F', strtotime( 'February 1' ) ),
			date_i18n( 'F', strtotime( 'March 1' ) ),
			date_i18n( 'F', strtotime( 'April 1' ) ),
			date_i18n( 'F', strtotime( 'May 1' ) ),
			date_i18n( 'F', strtotime( 'June 1' ) ),
			date_i18n( 'F', strtotime( 'July 1' ) ),
			date_i18n( 'F', strtotime( 'August 1' ) ),
			date_i18n( 'F', strtotime( 'September 1' ) ),
			date_i18n( 'F', strtotime( 'October 1' ) ),
			date_i18n( 'F', strtotime( 'November 1' ) ),
			date_i18n( 'F', strtotime( 'December 1' ) )
		) );
		wp_localize_script( 'pickadate.date', 'mc_days', array(
			date_i18n( 'D', strtotime( 'Sunday' ) ),
			date_i18n( 'D', strtotime( 'Monday' ) ),
			date_i18n( 'D', strtotime( 'Tuesday' ) ),
			date_i18n( 'D', strtotime( 'Wednesday' ) ),
			date_i18n( 'D', strtotime( 'Thursday' ) ),
			date_i18n( 'D', strtotime( 'Friday' ) ),
			date_i18n( 'D', strtotime( 'Saturday' ) )
		) );
		wp_localize_script( 'pickadate.time', 'mc_time_format', apply_filters( 'mc_time_format', 'h:i A' ) );
		wp_localize_script( 'pickadate.time', 'mc_interval', apply_filters( 'mc_interval', '15' ) );
		
		if ( function_exists( 'wp_enqueue_media' ) && ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
	}
}

function my_calendar_write_js() {
	if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'my-calendar' || $_GET['page'] == 'my-calendar-locations' ) ) {
		?>
		<script>
			//<![CDATA[
			if ( typeof(mc_months) !== "undefined" ) {
			jQuery(document).ready(function ($) {
				$( '.mc-datepicker' ).pickadate({
					monthsFull: mc_months,
					weekdaysShort: mc_days,
					format: 'yyyy-mm-dd',
					selectYears: true,
					selectMonths: true,
					editable: true
				});
				$( '.mc-timepicker' ).pickatime({
					interval: parseInt( mc_interval ),
					format: mc_time_format,
					editable: true		
				});
			});
			} else {
				jQuery(document).ready(function ($) {	
					var datepicked = $( '.mc-datepicker' ).attr( 'data-value' );
					$( '.mc-datepicker' ).val( datepicked );
				});
			}
			//]]>			
		</script>
		<script>
			//<![CDATA[		
			jQuery(document).ready(function ($) {		
				$('#mc-accordion').accordion( { collapsible: true, active: false, heightStyle: 'content' } );
				<?php
				if ( function_exists( 'jd_doTwitterAPIPost' ) && isset( $_GET['page'] ) && $_GET['page'] == 'my-calendar' ) { 
				?>
				$('#mc_twitter').charCount({
					allowed: 140,
					counterText: '<?php _e('Characters left: ','my-calendar') ?>'
				});
				<?php 
				} 
				?>
			});
			//]]>
		</script><?php
	}
}


add_action( 'in_plugin_update_message-my-calendar/my-calendar.php', 'mc_plugin_update_message' );
/**
 * Display notices from  WordPress.org about updated versions.
 */
function mc_plugin_update_message() {
	global $mc_version;
	define( 'MC_PLUGIN_README_URL', 'http://svn.wp-plugins.org/my-calendar/trunk/readme.txt' );
	$response = wp_remote_get(
		MC_PLUGIN_README_URL,
		array(
			'user-agent' => 'WordPress/My Calendar' . $mc_version . '; ' . get_bloginfo( 'url' ),
		)
	);
	if ( ! is_wp_error( $response ) || is_array( $response ) ) {
		$data = $response['body'];
		$bits = explode( '== Upgrade Notice ==', $data );
		echo '</div><div id="mc-upgrade" class="notice inline notice-warning"><ul><li><strong style="color:#c22;">Upgrade Notes:</strong> ' . str_replace( '* ', '', nl2br( trim( $bits[1] ) ) ) . '</li></ul>';
	}
}

function mc_footer_js() {
	global $wp_query;
	$mcjs = "<script>(function ($) { 'use strict'; $(function () { $( '.mc-main' ).removeClass( 'mcjs' ); });}(jQuery));</script>";

	if ( mc_is_mobile() && apply_filters( 'mc_disable_mobile_js', false ) ) {
		return;
	} else {
		$pages = array();
		if ( get_option( 'mc_show_js' ) != '' ) {
			$pages = explode( ",", get_option( 'mc_show_js' ) );
		}
		if ( is_object( $wp_query ) && isset( $wp_query->post ) ) {
			$id = $wp_query->post->ID;
		} else {
			$id = false;
		}
		if ( get_option( 'mc_use_custom_js' ) == 1 ) {
			$top     = $bottom = $inner = '';
			$list_js = stripcslashes( get_option( 'mc_listjs' ) );
			$cal_js  = stripcslashes( get_option( 'mc_caljs' ) );
			if ( get_option( 'mc_open_uri' ) == 'true' ) { // remove sections of javascript if necessary.
				$replacements = array(
					'$(this).parent().children().not(".event-title").toggle();',
					'e.preventDefault();'
				);
				$cal_js       = str_replace( $replacements, '', $cal_js );
			}
			$mini_js = stripcslashes( get_option( 'mc_minijs' ) );
			if ( get_option( 'mc_open_day_uri' ) == 'true' || get_option( 'mc_open_day_uri' ) == 'listanchor' || get_option( 'mc_open_day_uri' ) == 'calendaranchor' ) {
				$mini_js = str_replace( 'e.preventDefault();', '', $mini_js );
			}
			$ajax_js = stripcslashes( get_option( 'mc_ajaxjs' ) );

			if ( @in_array( $id, $pages ) || get_option( 'mc_show_js' ) == '' ) {
				$inner = '';
				if ( get_option( 'mc_calendar_javascript' ) != 1 ) {
					$inner .= "\n" . $cal_js;
				}
				if ( get_option( 'mc_list_javascript' ) != 1 ) {
					$inner .= "\n" . $list_js;
				}
				if ( get_option( 'mc_mini_javascript' ) != 1 ) {
					$inner .= "\n" . $mini_js;
				}
				if ( get_option( 'mc_ajax_javascript' ) != 1 ) {
					$inner .= "\n" . $ajax_js;
				}
$script = '
<script type="text/javascript">
(function( $ ) { \'use strict\';'.
	$inner
.'}(jQuery));
</script>';
			}
			$inner = apply_filters( 'mc_filter_javascript_footer', $inner );
			echo ( $inner != '' ) ? $script . $mcjs : '';
		} else {
			$enqueue_mcjs = false;
			if ( @in_array( $id, $pages ) || get_option( 'mc_show_js' ) == '' ) {
				if ( get_option( 'mc_calendar_javascript' ) != 1 && get_option( 'mc_open_uri' ) != 'true' ) {
					$url = apply_filters( 'mc_grid_js', plugins_url( 'js/mc-grid.js', __FILE__ ) );
					wp_enqueue_script( 'mc.grid', $url, array( 'jquery' ) );
					wp_localize_script( 'mc.grid', 'mcgrid', 'true' );
					$enqueue_mcjs = true;
				}
				if ( get_option( 'mc_list_javascript' ) != 1 ) {
					$url = apply_filters( 'mc_list_js', plugins_url( 'js/mc-list.js', __FILE__ ) );
					wp_enqueue_script( 'mc.list', $url, array( 'jquery' ) );
					wp_localize_script( 'mc.list', 'mclist', 'true' );					
					$enqueue_mcjs = true;
				}
				if ( get_option( 'mc_mini_javascript' ) != 1 && get_option( 'mc_open_day_uri' ) != 'true' ) {
					$url = apply_filters( 'mc_mini_js', plugins_url( 'js/mc-mini.js', __FILE__ ) );
					wp_enqueue_script( 'mc.mini', $url, array( 'jquery' ) );
					wp_localize_script( 'mc.mini', 'mcmini', 'true' );
					$enqueue_mcjs = true;
				}
				if ( get_option( 'mc_ajax_javascript' ) != 1 ) {
					$url = apply_filters( 'mc_ajax_js', plugins_url( 'js/mc-ajax.js', __FILE__ ) );
					wp_enqueue_script( 'mc.ajax', $url, array( 'jquery' ) );
					wp_localize_script( 'mc.ajax', 'mcAjax', 'true' );					
					$enqueue_mcjs = true;
				}
				if ( $enqueue_mcjs ) {
					wp_enqueue_script( 'mc.mcjs', plugins_url( 'js/mcjs.js', __FILE__ ), array( 'jquery' ) );
				}
			}
		}
	}
}



function my_calendar_add_styles() {
	if ( isset( $_GET['page'] ) ) {
		$pages = array(
			'my-calendar',
			'my-calendar-manage',
			'my-calendar-groups',
			'my-calendar-categories',
			'my-calendar-locations',
			'my-calendar-config',
			'my-calendar-styles',
			'my-calendar-help',
			'my-calendar-behaviors',
			'my-calendar-templates'
		);
		if ( in_array( $_GET['page'], $pages ) ) {
			wp_enqueue_style( 'mc-styles', plugins_url( 'css/mc-styles.css', __FILE__ ) );
		}
		if ( $_GET['page'] == 'my-calendar' ) {
			wp_enqueue_style( 'mc-pickadate-default', plugins_url( 'js/pickadate/themes/default.css', __FILE__ ) );
			wp_enqueue_style( 'mc-pickadate-date', plugins_url( 'js/pickadate/themes/default.date.css', __FILE__ ) );
			wp_enqueue_style( 'mc-pickadate-time', plugins_url( 'js/pickadate/themes/default.time.css', __FILE__ ) );
		}
	}
}

function mc_get_current_url() {
	global $wp, $wp_rewrite;
	$args = array();
	if ( isset( $_GET['page_id'] ) ) {
		$args = array( 'page_id' => $_GET['page_id'] );
	}
	$current_url = home_url( add_query_arg( $args, $wp->request ) );
		
	if ( $wp_rewrite->using_index_permalinks() && strpos( $current_url, 'index.php' ) === false ) {
		$current_url = str_replace( home_url(), home_url( '/' ) . 'index.php', $current_url );
	}
	if ( $wp_rewrite->using_permalinks() ) {
		$current_url = trailingslashit( $current_url );
	}
	
	return esc_url( $current_url );
}

function mc_csv_to_array( $csv, $delimiter = ',', $enclosure = '"', $escape = '\\', $terminator = "\n" ) {
	$r    = array();
	$rows = explode( $terminator, trim( $csv ) );
	foreach ( $rows as $row ) {
		if ( trim( $row ) ) {
			$values          = explode( $delimiter, $row );
			$r[ $values[0] ] = ( isset( $values[1] ) ) ? str_replace( array( $enclosure, $escape ), '', $values[1] ) : $values[0] ;
		}
	}

	return $r;
}

function mc_if_needs_permissions() {
	// prevent administrators from losing privileges to edit my calendar
	$role = get_role( 'administrator' );
	if ( is_object( $role ) ) {
		$caps = $role->capabilities;
		if ( isset( $caps['mc_add_events'] ) ) {
			return;
		} else {
			$role->add_cap( 'mc_add_events' );
			$role->add_cap( 'mc_approve_events' );
			$role->add_cap( 'mc_manage_events' );
			$role->add_cap( 'mc_edit_cats' );
			$role->add_cap( 'mc_edit_styles' );
			$role->add_cap( 'mc_edit_behaviors' );
			$role->add_cap( 'mc_edit_templates' );
			$role->add_cap( 'mc_edit_settings' );
			$role->add_cap( 'mc_edit_locations' );
			$role->add_cap( 'mc_view_help' );
		}
	} else {
		return;
	}
}

function mc_add_roles( $add = false, $manage = false, $approve = false ) {
	// grant administrator role all event permissions
	$role = get_role( 'administrator' );
	$role->add_cap( 'mc_add_events' );
	$role->add_cap( 'mc_approve_events' );
	$role->add_cap( 'mc_manage_events' );
	$role->add_cap( 'mc_edit_cats' );
	$role->add_cap( 'mc_edit_styles' );
	$role->add_cap( 'mc_edit_behaviors' );
	$role->add_cap( 'mc_edit_templates' );
	$role->add_cap( 'mc_edit_settings' );
	$role->add_cap( 'mc_edit_locations' );
	$role->add_cap( 'mc_view_help' );

	// depending on permissions settings, grant other permissions
	if ( $add && $manage && $approve ) {
		// this is an upgrade;
		// Get Roles
		$subscriber  = get_role( 'subscriber' );
		$contributor = get_role( 'contributor' );
		$author      = get_role( 'author' );
		$editor      = get_role( 'editor' );
		$subscriber->add_cap( 'mc_view_help' );
		$contributor->add_cap( 'mc_view_help' );
		$author->add_cap( 'mc_view_help' );
		$editor->add_cap( 'mc_view_help' );
		switch ( $add ) {
			case 'read':
				$subscriber->add_cap( 'mc_add_events' );
				$contributor->add_cap( 'mc_add_events' );
				$author->add_cap( 'mc_add_events' );
				$editor->add_cap( 'mc_add_events' );
				break;
			case 'edit_posts':
				$contributor->add_cap( 'mc_add_events' );
				$author->add_cap( 'mc_add_events' );
				$editor->add_cap( 'mc_add_events' );
				break;
			case 'publish_posts':
				$author->add_cap( 'mc_add_events' );
				$editor->add_cap( 'mc_add_events' );
				break;
			case 'moderate_comments':
				$editor->add_cap( 'mc_add_events' );
				break;
		}
		switch ( $approve ) {
			case 'read':
				$subscriber->add_cap( 'mc_approve_events' );
				$contributor->add_cap( 'mc_approve_events' );
				$author->add_cap( 'mc_approve_events' );
				$editor->add_cap( 'mc_approve_events' );
				break;
			case 'edit_posts':
				$contributor->add_cap( 'mc_approve_events' );
				$author->add_cap( 'mc_approve_events' );
				$editor->add_cap( 'mc_approve_events' );
				break;
			case 'publish_posts':
				$author->add_cap( 'mc_approve_events' );
				$editor->add_cap( 'mc_approve_events' );
				break;
			case 'moderate_comments':
				$editor->add_cap( 'mc_approve_events' );
				break;
		}
		switch ( $manage ) {
			case 'read':
				$subscriber->add_cap( 'mc_manage_events' );
				$contributor->add_cap( 'mc_manage_events' );
				$author->add_cap( 'mc_manage_events' );
				$editor->add_cap( 'mc_manage_events' );
				break;
			case 'edit_posts':
				$contributor->add_cap( 'mc_manage_events' );
				$author->add_cap( 'mc_manage_events' );
				$editor->add_cap( 'mc_manage_events' );
				break;
			case 'publish_posts':
				$author->add_cap( 'mc_manage_events' );
				$editor->add_cap( 'mc_manage_events' );
				break;
			case 'moderate_comments':
				$editor->add_cap( 'mc_manage_events' );
				break;
		}
	}
}

function my_calendar_exists() {
	global $wpdb;
	$tables = $wpdb->get_results( "show tables;" );
	foreach ( $tables as $table ) {
		foreach ( $table as $value ) {
			if ( $value == my_calendar_table() ) {
				// if the table exists, then My Calendar was already installed.
				return true;
			}
		}
	}
	return false;
}

// Function to check what version of My Calendar is installed and install or upgrade if needed
function check_my_calendar() {
	global $wpdb, $mc_version;
	$mcdb = $wpdb;
	mc_if_needs_permissions();
	$current_version = ( get_option( 'mc_version' ) == '' ) ? get_option( 'my_calendar_version' ) : get_option( 'mc_version' );
	if ( version_compare( $current_version, '2.3.12', '>=' ) ) {
		// if current is a version higher than 2.3.11, they've already seen this notice and handled it.
		update_option( 'mc_update_notice', 1 );
	}
	// If current version matches, don't bother running this.
	if ( $current_version == $mc_version ) {
		return true;
	}
	// Assume this is not a new install until we prove otherwise
	$new_install        = false;
	$upgrade_path       = array();

	if ( my_calendar_exists() && $current_version == '' ) {
		// If the table exists, but I don't know what version it is, I have to run the full cycle of upgrades. 
		$current_version = '1.10.7';
	}

	if ( !my_calendar_exists() ) {
		$new_install = true;
	} else {
		// for each release requiring an upgrade path, add a version compare. 
		// Loop will run every relevant upgrade cycle.
		$valid_upgrades = array(
			'1.11.0',
			'1.11.1',
			'2.0.0',
			'2.0.4',
			'2.1.0',
			'2.2.0',
			'2.2.6',
			'2.2.10',
			'2.3.0',
			'2.3.11',
			'2.3.15',
			'2.5.0',
		);
		foreach ( $valid_upgrades as $upgrade ) {
			if ( version_compare( $current_version, $upgrade, "<" ) ) {
				$upgrade_path[] = $upgrade;
			}
		}
	}
	// having determined upgrade path, assign new version number
	update_option( 'mc_version', $mc_version );
	// Now we've determined what the current install is or isn't 
	if ( $new_install == true ) {
		//add default settings
		mc_default_settings();
		$sql = "INSERT INTO " . my_calendar_categories_table() . " SET category_id=1, category_name='General', category_color='#ffffcc', category_icon='event.png'";
		$mcdb->query( $sql );
		$term = wp_insert_term( 'General', 'mc-event-category' );
		
	} else {
		// clear cache so updates are immediately available
		mc_delete_cache();
	}
	mc_do_upgrades( $upgrade_path );
	/*
	if the user has fully uninstalled the plugin but kept the database of events, this will restore default 
	settings and upgrade db if needed.
	*/
	if ( get_option( 'mc_uninstalled' ) == 'true' ) {
		mc_default_settings();
		update_option( 'mc_db_version', $mc_version );
		delete_option( 'mc_uninstalled' );
	}
}

function mc_do_upgrades( $upgrade_path ) {
	global $mc_version;
	
	foreach ( $upgrade_path as $upgrade ) {
		switch ( $upgrade ) {
			case '2.5.0':
				update_option( 'mc_db_version', $mc_version );
				mc_upgrade_db();
				break;
			// only upgrade db on most recent version
			case '2.4.4':
				add_option( 'mc_display_more', 'true' );
				$input_options = get_option( 'mc_input_options' );
				$input_options['event_host'] = 'on';
				update_option( 'mc_input_options', $input_options );
				add_option( 'mc_default_direction', 'DESC' );
				break;
			case '2.3.15':
				delete_option( 'mc_event_groups' );
				delete_option( 'mc_details' );
				break;
			case '2.3.11':
				add_option( 'mc_use_custom_js', 0 );
				add_option( 'mc_update_notice', 0 );
				break;
			case '2.3.0':
				delete_option( 'mc_location_control' );
				$user_data              = get_option( 'mc_user_settings' );
				$loc_type               = ( get_option( 'mc_location_type' ) == '' ) ? 'event_state' : get_option( 'mc_location_type' );
				$locations[ $loc_type ] = $user_data['my_calendar_location_default']['values'];
				add_option( 'mc_use_permalinks', false );
				delete_option( 'mc_modified_feeds' );
				add_option( 'mc_location_controls', $locations );
				$mc_input_options                 = get_option( 'mc_input_options' );
				$mc_input_options['event_access'] = 'on';
				update_option( 'mc_input_options', $mc_input_options );
				mc_transition_db();
				break;
			case '2.2.10':
				delete_option( 'mc_show_print' );
				delete_option( 'mc_show_ical' );
				delete_option( 'mc_show_rss' );
				break;
			case '2.2.8':
				delete_option( 'mc_draggable' );
				break;
			case '2.2.6':
				delete_option( 'mc_caching_enabled' ); // remove caching support via options. Filter only.
				break;
			case '2.2.0':
				add_option( 'mc_inverse_color', 'true' );
				break;
			case '2.1.0':
				$templates = get_option( 'mc_templates' );
				global $rss_template;
				$templates['rss'] = $rss_template;
				update_option( 'mc_templates', $templates );
				break;
			case '2.0.4':
				update_option( 'mc_ical_utc', 'true' );
				break;
			case '2.0.0':
				mc_migrate_db();
				$mc_input = get_option( 'mc_input_options' );
				if ( ! isset( $mc_input['event_specials'] ) ) {
					$mc_input['event_specials'] = 'on';
					update_option( 'mc_input_options', $mc_input );
				}
				break;
			case '1.11.1':
				add_option( 'mc_event_link', 'true' );
				break;
			case '1.11.0':
				add_option( 'mc_convert', 'true' );
				add_option( 'mc_process_shortcodes', 'false' );
				$add     = get_option( 'mc_can_manage_events' ); // yes, this is correct.
				$manage  = get_option( 'mc_event_edit_perms' );
				$approve = get_option( 'mc_event_approve_perms' );
				mc_add_roles( $add, $manage, $approve );
				delete_option( 'mc_can_manage_events' );
				delete_option( 'mc_event_edit_perms' );
				delete_option( 'mc_event_approve_perms' );
				break;
			default:
				break;
		}
	}
}

// @data object with event_category value
function mc_category_select( $data = false, $option = true, $multiple = false ) {
	global $wpdb;
	$mcdb = $wpdb;
	if ( get_option( 'mc_remote' ) == 'true' && function_exists( 'mc_remote_db' ) ) {
		$mcdb = mc_remote_db();
	}
	// Grab all the categories and list them
	$list = $default = '';
	$sql  = "SELECT * FROM " . my_calendar_categories_table() . " ORDER BY category_name ASC";
	$cats = $mcdb->get_results( $sql );
	if ( empty( $cats ) ) {
		// need to have categories. Try to create again.
		$insert = "INSERT INTO " . my_calendar_categories_table() . " SET category_id=1, category_name='General', category_color='#ffffcc', category_icon='event.png'";
		$term = wp_insert_term( 'General', 'mc-event-category' );		
		$mcdb->query( $insert );
		$cats = $mcdb->get_results( $sql );
	}
	if ( ! empty( $cats ) ) {
		$cats = apply_filters( 'mc_category_list', $cats, $data, $option );
		foreach ( $cats as $cat ) {
			$c = '<option value="' . $cat->category_id . '"';
			if ( ! empty( $data ) ) {
				if ( ! is_object( $data ) ) {
					$category = $data;
				} else if ( is_array( $data ) && $multiple ) {
					$category = $data;
				} else {
					$category = $data->event_category;
				}
				if ( $multiple ) {
					if ( in_array( $cat->category_id, $category ) ) {
						$c .= ' selected="selected"';
					}
				} else {
					if ( $category == $cat->category_id ) {
						$c .= ' selected="selected"';
					}
				}
			}
			$c .= '>' . mc_kses_post( stripslashes( $cat->category_name ) ) . '</option>';
			if ( $cat->category_id != get_option( 'mc_default_category' ) ) {
				$list .= $c;
			} else {
				$default = $c;
			}
		}
	} else {
		$category_url = admin_url( 'admin.php?page=my-calendar-categories' );
		echo "<div class='updated error'><p>" . sprintf( __( 'You do not have any categories created. Please <a href="%s">create at least one category!</a>', 'my-calendar' ), $category_url ) . "</p></div>";
	}
	if ( ! $option ) {
		$default = ( get_option( 'mc_default_category' ) ) ? get_option( 'mc_default_category' ) : 1;

		return ( is_object( $data ) ) ? $data->event_category : $default;
	}

	return $default . $list;
}

// @data object with event_location value
function mc_location_select( $location = false ) {
	global $wpdb;
	$mcdb = $wpdb;
	if ( get_option( 'mc_remote' ) == 'true' && function_exists( 'mc_remote_db' ) ) {
		$mcdb = mc_remote_db();
	}
	// Grab all locations and list them
	$list = '';
	$sql  = "SELECT location_id, location_label FROM " . my_calendar_locations_table() . " ORDER BY location_label ASC";
	$locs = $mcdb->get_results( $sql );
	foreach ( $locs as $loc ) {
		$l = '<option value="' . $loc->location_id . '"';
		if ( $location ) {
			if ( $location == $loc->location_id ) {
				$l .= ' selected="selected"';
			}
		}
		$l .= '>' . mc_kses_post( stripslashes( $loc->location_label ) ) . '</option>';
		$list .= $l;
	}

	return $list;
}

function mc_is_checked( $theFieldname, $theValue, $theArray = '', $return = false ) {
	if ( ! is_array( get_option( $theFieldname ) ) ) {
		if ( get_option( $theFieldname ) == $theValue ) {
			if ( $return ) {
				return 'checked="checked"';
			} else {
				echo 'checked="checked"';
			}
		}
	} else {
		$theSetting = get_option( $theFieldname );
		if ( ! empty( $theSetting[ $theArray ]['enabled'] ) && $theSetting[ $theArray ]['enabled'] == $theValue ) {
			if ( $return ) {
				return 'checked="checked"';
			} else {
				echo 'checked="checked"';
			}
		}
	}
}

function mc_is_selected( $theFieldname, $theValue, $theArray = '' ) {
	if ( ! is_array( get_option( $theFieldname ) ) ) {
		if ( get_option( $theFieldname ) == $theValue ) {
			return 'selected="selected"';
		}
	} else {
		$theSetting = get_option( $theFieldname );
		if ( $theSetting[ $theArray ]['enabled'] == $theValue ) {
			return 'selected="selected"';
		}
	}

	return '';
}

function mc_event_repeats_forever( $recur, $repeats ) {
	if ( $recur != 'S' && $repeats == 0 ) {
		return true;
	}
	switch ( $recur ) {
		case "S": // single
			return false;
			break;
		case "D": // daily
			return ( $repeats == 500 ) ? true : false;
			break;
		case "W": // weekly
			return ( $repeats == 240 ) ? true : false;
			break;
		case "B": // biweekly
			return ( $repeats == 120 ) ? true : false;
			break;
		case "M": // monthly
		case "U":
			return ( $repeats == 60 ) ? true : false;
			break;
		case "Y":
			return ( $repeats == 5 ) ? true : false;
			break;
		default:
			return false;
	}
}

function my_calendar_is_odd( $int ) {
	return ( $int & 1 );
}

/* Unless an admin, authors can only edit their own events if they don't have mc_manage_events capabilities. */
function mc_can_edit_event( $event = false ) {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	
	if ( !$event ) {
		return false;
	}
	
	if ( is_object( $event ) ) {
		$event_id = $event->event_id;
		$event_author = $event->event_author;
	} else {
		$event_id = $event;
		$event = mc_get_event_core( $event );
		$event_author = $event->event_author;
	}
	
	
	$current_user = wp_get_current_user();
	$user = $current_user->ID;
	$has_permissions = mc_can_edit_category( $event->event_category, $user );
	
	if ( !is_object( $event ) ) {
		$event_author = 0;
	} else {
		$event_author = $event->event_author;
	}
	
	if ( current_user_can( 'mc_manage_events' ) && $has_permissions ) {
		$return = true;
	} else if ( $user == $event_author ) {
		$return = true;
	} else {
		$return = false;
	}

	return apply_filters( 'mc_can_edit_event', $return, $event_id );
}

function mc_can_edit_category( $category, $user ) {
	$permissions = get_user_meta( $user, 'mc_user_permissions', true );
	
	if ( empty( $permissions ) || in_array( 'all', $permissions ) || in_array( $category, $permissions ) ) {
		return true;
	}
	
	return false;
}

function jd_option_selected( $field, $value, $type = 'checkbox' ) {
	switch ( $type ) {
		case 'radio':
		case 'checkbox':
			$result = ' checked="checked"';
			break;
		case 'option':
			$result = ' selected="selected"';
			break;
		default:
			$result = '';
			break;
	}
	if ( $field == $value ) {
		$output = $result;
	} else {
		$output = '';
	}

	return $output;
}

add_action( 'admin_bar_menu', 'my_calendar_admin_bar', 200 );
function my_calendar_admin_bar() {
	global $wp_admin_bar;
	if ( current_user_can( 'mc_add_events' ) && get_option( 'mc_remote' ) != 'true' ) {
		$url  = apply_filters( 'mc_add_events_url', admin_url( 'admin.php?page=my-calendar' ) );
		$args = array( 'id' => 'mc-add-event', 'title' => __( 'Add Event', 'my-calendar' ), 'href' => $url );
		$wp_admin_bar->add_node( $args );
	}
	if ( _mc_is_url( get_option( 'mc_uri' ) ) ) {
		$url  = esc_url( apply_filters( 'mc_adminbar_uri', get_option( 'mc_uri' ) ) );
		$args = array( 'id' => 'mc-my-calendar', 'title' => __( 'View Calendar', 'my-calendar' ), 'href' => $url );
		$wp_admin_bar->add_node( $args );
	} else {
		$url  = admin_url( 'admin.php?page=my-calendar-config#mc-output' );
		$args = array( 'id' => 'mc-my-calendar', 'title' => __( 'Set Calendar URL', 'my-calendar' ), 'href' => $url );
		$wp_admin_bar->add_node( $args );		
	}
	if ( current_user_can( 'mc_manage_events' ) && current_user_can( 'mc_add_events' ) ) {
		$url  = admin_url( 'admin.php?page=my-calendar-manage' );
		$args = array(
			'id'     => 'mc-manage-events',
			'title'  => __( 'Events', 'my-calendar' ),
			'href'   => $url,
			'parent' => 'mc-add-event'
		);
		$wp_admin_bar->add_node( $args );
	}
	if ( current_user_can( 'mc_edit_cats' ) && current_user_can( 'mc_add_events' ) ) {
		$url  = admin_url( 'admin.php?page=my-calendar-categories' );
		$args = array(
			'id'     => 'mc-manage-categories',
			'title'  => __( 'Categories', 'my-calendar' ),
			'href'   => $url,
			'parent' => 'mc-add-event'
		);
		$wp_admin_bar->add_node( $args );
	}
	if ( current_user_can( 'mc_edit_locations' ) && current_user_can( 'mc_add_events' ) ) {
		$url  = admin_url( 'admin.php?page=my-calendar-locations' );
		$args = array(
			'id'     => 'mc-manage-locations',
			'title'  => __( 'Locations', 'my-calendar' ),
			'href'   => $url,
			'parent' => 'mc-add-event'
		);
		$wp_admin_bar->add_node( $args );
	}
}

// Mail functions (originally by Roland)
function my_calendar_send_email( $event ) {
	$details = mc_create_tags( $event );
	$headers = array();
	// shift to boolean
	$send_email_option = ( get_option( 'mc_event_mail' ) == 'true' ) ? true : false;
	$send_email        = apply_filters( 'mc_send_notification', $send_email_option, $details );
	if ( $send_email == true ) {
		add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
	}
	if ( get_option( 'mc_event_mail' ) == 'true' ) {
		$to        = apply_filters( 'mc_event_mail_to', get_option( 'mc_event_mail_to' ), $details );
		$from      = ( get_option( 'mc_event_mail_from' ) == '' ) ? get_bloginfo( 'admin_email' ) : get_option( 'mc_event_mail_from' );
		$from      = apply_filters( 'mc_event_mail_from', $from, $details );
		$headers[] = "From: " . __( 'Event Notifications', 'my-calendar' ) . " <$from>";
		$bcc       = apply_filters( 'mc_event_mail_bcc', get_option( 'mc_event_mail_bcc' ), $details );
		if ( $bcc ) {
			$bcc = explode( PHP_EOL, $bcc );
			foreach ( $bcc as $b ) {
				$b = trim( $b );
				if ( is_email( $b ) ) {
					$headers[] = "Bcc: $b";
				}
			}
		}
		$headers = apply_filters( 'mc_customize_email_headers', $headers, $event );
		$subject = jd_draw_template( $details, get_option( 'mc_event_mail_subject' ) );
		$message = jd_draw_template( $details, get_option( 'mc_event_mail_message' ) );
		wp_mail( $to, $subject, $message, $headers );
	}
	if ( get_option( 'mc_html_email' ) == 'true' ) {
		remove_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
	}
}

// checks submitted events against akismet or botsmasher, if available, otherwise just returns false 
function mc_spam( $event_url = '', $description = '', $post = array() ) {
	global $akismet_api_host, $akismet_api_port, $current_user;
	$wpcom_api_key = defined( 'WPCOM_API_KEY' ) ? WPCOM_API_KEY : false;
	$current_user = wp_get_current_user();
	if ( current_user_can( 'mc_manage_events' ) ) { // is a privileged user
		return 0;
	}
	$bs = $akismet = false;
	$c  = array();
	// check for Akismet
	if ( ! function_exists( 'akismet_http_post' ) || ! ( get_option( 'wordpress_api_key' ) || $wpcom_api_key ) ) {
		// check for BotSmasher
		$bs = get_option( 'bs_options' );
		if ( is_array( $bs ) ) {
			$bskey = $bs['bs_api_key'];
		} else {
			$bskey = '';
		}
		if ( ! function_exists( 'bs_checker' ) || $bskey == '' ) {
			return 0; // if neither exist
		} else {
			$bs = true;
		}
	} else {
		$akismet = true;
	}
	if ( $akismet ) {
		$c['blog']         = get_option( 'home' );
		$c['user_ip']      = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
		$c['user_agent']   = $_SERVER['HTTP_USER_AGENT'];
		$c['referrer']     = $_SERVER['HTTP_REFERER'];
		$c['comment_type'] = 'my_calendar_event';
		if ( $permalink = get_permalink() ) {
			$c['permalink'] = $permalink;
		}
		if ( '' != $event_url ) {
			$c['comment_author_url'] = $event_url;
		}
		if ( '' != $description ) {
			$c['comment_content'] = $description;
		}
		$ignore = array( 'HTTP_COOKIE' );

		foreach ( $_SERVER as $key => $value ) {
			if ( ! in_array( $key, (array) $ignore ) ) {
				$c["$key"] = $value;
			}
		}
		$query_string = '';
		foreach ( $c as $key => $data ) {
			$query_string .= $key . '=' . urlencode( stripslashes( (string) $data ) ) . '&';
		}
		$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
		if ( 'true' == $response[1] ) {
			return 1;
		} else {
			return 0;
		}
	}
	if ( $bs ) {
		if ( is_user_logged_in() ) {
			$name  = $current_user->user_login;
			$email = $current_user->user_email;
		} else {
			$name  = $post['mcs_name'];
			$email = $post['mcs_email'];
		}
		$args       = array(
			'ip'     => preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] ),
			'email'  => $email,
			'name'   => $name,
			'action' => 'check'
		);
		$args['ip'] = "216.152.251.41";
		$response   = bs_checker( $args );
		if ( $response ) {
			return 1;
		} else {
			return 0;
		}
	}

	return 0;
}

// duplicate of mc_is_url, which really should have been in this file. Bugger.
function _mc_is_url( $url ) {
	return preg_match( '|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url );
}

function mc_external_link( $link ) {
	if ( ! _mc_is_url( $link ) ) {
		return "class='error-link'";
	}

	$url   = parse_url( $link );
	$host  = $url['host'];
	$site  = parse_url( get_option( 'siteurl' ) );
	$known = $site['host'];
	if ( strpos( $host, $known ) === false ) {
		return true;
	}

	return false;
}

add_action( 'admin_enqueue_scripts', 'mc_scripts' );
function mc_scripts() {
	global $current_screen;
	if ( $current_screen->id == 'toplevel_page_my-calendar' && function_exists( 'jd_doTwitterAPIPost' ) ) {
		wp_enqueue_script( 'charCount', plugins_url( 'wp-to-twitter/js/jquery.charcount.js' ), array( 'jquery' ) );
	}
	if ( $current_screen->id == 'toplevel_page_my-calendar' ) {
		if ( current_user_can( 'mc_manage_events' ) ) {
			wp_enqueue_script( 'mc.ajax', plugins_url( 'js/ajax.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'mc.ajax', 'mc_data', array(
				'action'   => 'delete_occurrence',
				'recur'    => 'add_date',
				'security' => wp_create_nonce( 'mc-delete-nonce' )
			) );
		}
	}
	
	if ( $current_screen->id == 'my-calendar_page_my-calendar-categories' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mc-color-picker', plugins_url( 'js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
}

add_action( 'wp_ajax_delete_occurrence', 'mc_ajax_delete_occurrence' );
/**
 * Delete a single occurrence of an event from the event manager.
 *
 * @return string Confirmation message indicating success or failure.
 */
function mc_ajax_delete_occurrence() {
	if ( ! check_ajax_referer( 'mc-delete-nonce', 'security', false ) ) {
		wp_send_json( array( 'success'=>0, 'response' => __( "Invalid Security Check", 'my-calendar' ) ) );
	}

	if ( current_user_can( 'mc_manage_events' ) ) {
		
		global $wpdb;
		$mcdb    = $wpdb;		
		$occur_id = (int) $_REQUEST['occur_id'];
				
		$delete = "DELETE FROM " . my_calendar_event_table() . " WHERE occur_id = $occur_id";
		$result = $mcdb->query( $delete );
				
		if ( $result ) {
			wp_send_json( array( 'success'=>1, 'response' => __( 'Event instance has been deleted.', 'my-calendar' ) ) );
		} else {
			wp_send_json( array( 'success'=>0, 'response' => __( 'Event instance was not deleted.', 'my-calendar' ) ) );
		}

	} else {
		wp_send_json( array( 'success'=>0, 'response' => __( 'You are not authorized to perform this action', 'my-calendar' ) ) );
	}
}


add_action( 'wp_ajax_add_date', 'mc_ajax_add_date' );
/**
 * Add a single additional date for an event from the event manager.
 *
 * @return string Confirmation message indicating success or failure.
 */
function mc_ajax_add_date() {
	if ( ! check_ajax_referer( 'mc-delete-nonce', 'security', false ) ) {
		wp_send_json( array( 'success'=>0, 'response' => __( "Invalid Security Check", 'my-calendar' ) ) );
	}

	if ( current_user_can( 'mc_manage_events' ) ) {
		
		global $wpdb;
		$mcdb     = $wpdb;		
		$event_id = (int) $_REQUEST['event_id'];
		
		if ( $event_id === 0 ) {
			wp_send_json( array( 'success' => 0, 'response' => __( 'No event ID in that request.', 'my-calendar' ) ) );
		}
		
		$event_date = $_REQUEST['event_date'];
		$event_end   = isset( $_REQUEST['event_end'] ) ? $_REQUEST['event_end'] : $event_date;
		$event_time  = $_REQUEST['event_time'];
		$event_endtime = isset( $_REQUEST['event_endtime'] ) ? $_REQUEST['event_endtime'] : '';
		$group_id = (int) $_REQUEST['group_id'];
		
		// event end can not be earlier than event start
		if ( ! $event_end || strtotime( $event_end ) < strtotime( $event_date ) ) {
			$event_end = $event_date;
		}
		
		$begin = strtotime( $event_date . ' ' . $event_time );
		$end   = ( $event_endtime != '' ) ? strtotime( $event_end . ' ' . $event_endtime ) : strtotime( $event_end . ' ' . $event_time ) + HOUR_IN_SECONDS;
		
		$format   = array( '%d', '%s', '%s', '%d' );
		$data = array(
				'occur_event_id' => $event_id,
				'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
				'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
				'occur_group_id' => $group_id
			);
		$result = $wpdb->insert( my_calendar_event_table(), $data, $format );
		
		if ( $result ) {
			wp_send_json( array( 'success'=>1, 'response' => __( 'Thanks! I added your new date.', 'my-calendar' ) ) );
		} else {
			wp_send_json( array( 'success'=>0, 'response' => __( 'Sorry! I failed to add that date.', 'my-calendar' ) ) );
		}

	} else {
		wp_send_json( array( 'success'=>0, 'response' => __( 'You are not authorized to perform this action', 'my-calendar' ) ) );
	}
}

function mc_newline_replace( $string ) {
	return (string) str_replace( array( "\r", "\r\n", "\n" ), '', $string );
}

function reverse_array( $array, $boolean, $order ) {
	if ( $order == 'desc' ) {
		return array_reverse( $array, $boolean );
	} else {
		return $array;
	}
}

// in multi-site, wp_is_mobile() won't be defined yet if plug-in is network activated. 
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( ! function_exists( 'wp_is_mobile' ) ) {
	if ( ! is_plugin_active_for_network( 'my-calendar/my-calendar.php' ) ) {
		function wp_is_mobile() {
			return false;
		}
	}
}

function mc_is_mobile() {
	return apply_filters( 'mc_is_mobile', wp_is_mobile() );
}

/* this function only provides a filter for custom dev. */
function mc_is_tablet() {
	return apply_filters( 'mc_is_tablet', false );
}

function mc_guess_calendar() {
	global $wpdb;
	$mcdb = $wpdb;
	/* If you're looking at this, and have suggestions for other slugs I could be looking at, feel  to let me know. I didn't feel a need to be overly thorough. */
	$my_guesses = array(
		'calendar',
		'events',
		'activities',
		'classes',
		'courses',
		'rehearsals',
		'schedule',
		'calendario',
		'actividades',
		'eventos',
		'kalender',
		'veranstaltungen',
		'unterrichten',
		'eventi',
		'classi'
	);
	$current_uri = get_option( 'mc_uri' );
	// check whether calendar page is a valid URL.
	if ( $current_uri ) {
		$response = wp_remote_head( $current_uri );
		if ( !is_wp_error( $response ) ) {
			$http = $response['response']['code'];
			// Only modify the value if it's explicitly missing. Redirects or secured pages are fine.
			if ( $http == 404 ) {
				$current_uri = '';
			}
		}
	}
	if ( $current_uri == '' ) {
		foreach ( $my_guesses as $guess ) {
			$post_ID = $mcdb->get_var( "SELECT id FROM $mcdb->posts WHERE post_title LIKE '%$guess%' AND post_status = 'publish'" );
			if ( $post_ID ) {
				$link = get_permalink( $post_ID );
				update_option( 'mc_uri', $link );
				update_option( 'mc_uri_id', $post_ID );
				$return = array( 'response' => true, 'message'=> __( 'Is this your calendar page?', 'my-calendar' ) . ' <code>' . $link . '</code>' );

				return $return;
			} else {
				update_option( 'mc_uri', '' );
				update_option( 'mc_uri_id', '' );
				$return = array( 'response' => false, 'message' => __( 'No valid calendar detected. Please provide a URL!', 'my-calendar' ) );
				return $return;
			}
		}
	}
	return;
}

function mc_get_support_form() {
	global $current_user;
	$current_user = wp_get_current_user();
	// send fields for My Calendar
	$version       = get_option( 'mc_version' );
	$mc_db_version = get_option( 'mc_db_version' );
	$mc_uri        = get_option( 'mc_uri' );
	$mc_css        = get_option( 'mc_css_file' );

	$license         = ( get_option( 'mcs_license_key' ) != '' ) ? get_option( 'mcs_license_key' ) : 'none';
	$tickets_license = ( get_option( 'mt_license_key' ) != '' ) ? get_option( 'mt_license_key' ) : 'none';
	// send fields for all plugins
	$wp_version = get_bloginfo( 'version' );
	$home_url   = home_url();
	$wp_url     = site_url();
	$language   = get_bloginfo( 'language' );
	$charset    = get_bloginfo( 'charset' );
	// server
	$php_version = phpversion();

	$admin_email = get_option( 'admin_email' );
	// theme data
	$theme         = wp_get_theme();
	$theme_name    = $theme->Name;
	$theme_uri     = $theme->ThemeURI;
	$theme_parent  = $theme->Template;
	$theme_version = $theme->Version;

	// plugin data
	$plugins        = get_plugins();
	$plugins_string = '';

	foreach ( array_keys( $plugins ) as $key ) {
		if ( is_plugin_active( $key ) ) {
			$plugin         =& $plugins[ $key ];
			$plugin_name    = $plugin['Name'];
			$plugin_uri     = $plugin['PluginURI'];
			$plugin_version = $plugin['Version'];
			$plugins_string .= "$plugin_name: $plugin_version; $plugin_uri\n";
		}
	}
	$data    = "
================ Installation Data ====================
==My Calendar:==
Version: $version
DB Version: $mc_db_version
URI: $mc_uri
CSS: $mc_css
Licenses: Pro - $license; Ticketing - $tickets_license
Requester Email: $current_user->user_email
Admin Email: $admin_email

==WordPress:==
Version: $wp_version
URL: $home_url
Install: $wp_url
Language: $language
Charset: $charset

==Extra info:==
PHP Version: $php_version
Server Software: $_SERVER[SERVER_SOFTWARE]
User Agent: $_SERVER[HTTP_USER_AGENT]

==Theme:==
Name: $theme_name
URI: $theme_uri
Parent: $theme_parent
Version: $theme_version

==Active Plugins:==
$plugins_string
";
	$request = '';
	if ( isset( $_POST['mc_support'] ) ) {
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-calendar-nonce' ) ) {
			die( "Security check failed" );
		}
		$request       = ( ! empty( $_POST['support_request'] ) ) ? stripslashes( $_POST['support_request'] ) : false;
		$has_donated   = ( $_POST['has_donated'] == 'on' ) ? "Donor" : "No donation";
		$has_purchased = ( $_POST['has_purchased'] == 'on' ) ? "Purchaser" : "No purchase";
		$has_read_faq  = ( $_POST['has_read_faq'] == 'on' ) ? "Read FAQ" : false;
		$subject       = "My Calendar support request. $has_donated; $has_purchased";
		$message       = $request . "\n\n" . $data;
		// Get the site domain and get rid of www. from pluggable.php
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;
		$from       = "From: \"$current_user->display_name\" <$from_email>\r\nReply-to: \"$current_user->display_name\" <$current_user->user_email>\r\n";

		if ( ! $has_read_faq ) {
			echo "<div class='message error'><p>" . __( 'Please read the FAQ and other Help documents before making a support request.', 'my-calendar' ) . "</p></div>";
		} else if ( ! $request ) {
			echo "<div class='message error'><p>" . __( 'Please describe your problem in detail. I\'m not psychic.', 'my-calendar' ) . "</p></div>";
		} else {
			$sent = wp_mail( "plugins@joedolson.com", $subject, $message, $from );
			if ( $sent ) {
				if ( $has_donated == 'Donor' || $has_purchased == 'Purchaser' ) {
					echo "<div class='message updated'><p>" . __( 'Thank you for supporting the continuing development of this plug-in! I\'ll get back to you as soon as I can.', 'my-calendar' ) . "</p></div>";
				} else {
					echo "<div class='message updated'><p>" . __( 'I\'ll get back to you as soon as I can, after dealing with any support requests from plug-in supporters.', 'my-calendar' ) . "</p></div>";
				}
			} else {
				echo "<div class='message error'><p>" . __( "Sorry! I couldn't send that message. Here's the text of your request:", 'my-calendar' ) . "</p><p>" . sprintf( __( '<a href="%s">Contact me here</a>, instead</p>', 'my-calendar' ), 'https://www.joedolson.com/contact/' ) . "<pre>$request</pre></div>";
			}
		}
	}

	echo "
	<form method='post' action='" . admin_url( 'admin.php?page=my-calendar-help' ) . "'>
		<div><input type='hidden' name='_wpnonce' value='" . wp_create_nonce( 'my-calendar-nonce' ) . "' /></div>
		<div>
		<p>" .
	     __( '<strong>Note</strong>: I keep records of donations, but if your donation came from <em>somebody other than your account at this web site</em>, please note this in your message.', 'my-calendar' )
	     . "<p>
		<code>" . __( 'From:', 'my-calendar' ) . " \"$current_user->display_name\" &lt;$current_user->user_email&gt;</code>
		</p>
		<p>
			<input type='checkbox' name='has_read_faq' id='has_read_faq' value='on' required='required' aria-required='true' /> <label for='has_read_faq'>" . __( 'I have read <a href="http://www.joedolson.com/my-calendar/faq/">the FAQ for this plug-in</a>.', 'my-calendar' ) . " <span>(required)</span></label>
		</p>
		<p>
			<input type='checkbox' name='has_donated' id='has_donated' value='on' /> <label for='has_donated'>" . sprintf( __( 'I <a href="%s">made a donation to help support this plug-in</a>.', 'my-calendar' ), 'https://www.joedolson.com/donate/' ) . "</label>
		</p>
		<p>
			<input type='checkbox' name='has_purchased' id='has_purchased' value='on' /> <label for='has_purchased'>" . __( 'I have <a href="http://www.joedolson.com/my-calendar/users-guide/">purchased the User\'s Guide</a>, but could not find an answer to this question.', 'my-calendar' ) . "</label>
		</p>
		<p>
			<label for='support_request'>Support Request:</label><br /><textarea name='support_request' id='support_request' required aria-required='true' cols='80' rows='10' class='widefat'>" . stripslashes( $request ) . "</textarea>
		</p>
		<p>
			<input type='submit' value='" . __( 'Send Support Request', 'my-calendar' ) . "' name='mc_support' class='button-primary' />
		</p>
		<p>" .
	     __( 'The following additional information will be sent with your support request:', 'my-calendar' )
	     . "</p>
		<div class='mc_support'>
		" . wpautop( $data ) . "
		</div>
		</div>
	</form>";
}


function mc_recur_options( $value ) {
	$s = ( $value == 'S' ) ? " selected='selected'" : '';
	$d = ( $value == 'D' ) ? " selected='selected'" : '';
	$e = ( $value == 'E' ) ? " selected='selected'" : '';
	$w = ( $value == 'W' || $value == 'B' ) ? " selected='selected'" : '';
	$m = ( $value == 'M' ) ? " selected='selected'" : '';
	$u = ( $value == 'U' ) ? " selected='selected'" : '';
	$y = ( $value == 'Y' ) ? " selected='selected'" : '';

	$return = "
				<option class='input' value='S' $s>" . __( 'Does not recur', 'my-calendar' ) . "</option>
				<option class='input' value='D' $d>" . __( 'Days', 'my-calendar' ) . "</option>
				<option class='input' value='E' $e>" . __( 'Days, weekdays only', 'my-calendar' ) . "</option>
				<option class='input' value='W' $w>" . __( 'Weeks', 'my-calendar' ) . "</option>
				<option class='input' value='M' $m>" . __( 'Months by date (e.g., the 24th of each month)', 'my-calendar' ) . "</option>
				<option class='input' value='U' $u>" . __( 'Month by day (e.g., the 3rd Monday of each month)', 'my-calendar' ) . "</option>
				<option class='input' value='Y' $y>" . __( 'Year', 'my-calendar' ) . "</option>
	";

	return $return;
}

//".$select = ( $value == 'D' )?$selected:''."

function _mc_increment_values( $recur ) {
	switch ( $recur ) {
		case "S": // single
			return 0;
			break;
		case "D": // daily
			return 500;
			break;
		case "E": // weekdays
			return 400;
			break;
		case "W": // weekly
			return 240;
			break;
		case "B": // biweekly
			return 120;
			break;
		case "M": // monthly
		case "U":
			return 60;
			break;
		case "Y":
			return 10;
			break;
		default:
			false;
	}
}

/*
* @param event_id, number of repetitions
* @return true/false
*/
function mc_change_instances( $id, $repeats, $begin = false ) {
	global $wpdb;
	$mcdb   = $wpdb;
	$events = $mcdb->get_results( $mcdb->prepare( "SELECT * FROM " . my_calendar_event_table() . " WHERE occur_event_id = %d ORDER BY occur_begin DESC", $id ) );
	$count  = count( $events );
	$last   = $count - 1;
	if ( $begin == false ) {
		if ( $count > $repeats ) {
			// if higher than previous: delete
			$diff = $count - $repeats;
			for ( $i = 0; $i < $diff; $i ++ ) {
				$oid = $events[ $i ]->occur_id;
				$sql = "DELETE FROM " . my_calendar_event_table() . " WHERE occur_id = $oid";
				$mcdb->query( $sql );
			}
		} else if ( $count < $repeats ) {
			// if lower: add more by incrementing from the last date available.
			$dates = array(
				'event_begin'   => date( 'Y-m-d', strtotime( $events[0]->occur_begin ) ),
				'event_time'    => date( 'H:i:s', strtotime( $events[0]->occur_begin ) ),
				'event_end'     => date( 'Y-m-d', strtotime( $events[0]->occur_end ) ),
				'event_endtime' => date( 'H:i:s', strtotime( $events[0]->occur_end ) )
			);
			mc_increment_event( $id, $dates );
		} else {
			return false;
		}
	} else {
		$sql = "DELETE FROM " . my_calendar_event_table() . " WHERE occur_event_id = $id";
		$mcdb->query( $sql );
		$dates = array(
			'event_begin'   => date( 'Y-m-d', strtotime( $events[ $last ]->occur_begin ) ),
			'event_time'    => date( 'H:i:s', strtotime( $events[ $last ]->occur_begin ) ),
			'event_end'     => date( 'Y-m-d', strtotime( $events[ $last ]->occur_end ) ),
			'event_endtime' => date( 'H:i:s', strtotime( $events[ $last ]->occur_end ) )
		);
		mc_increment_event( $id, $dates );
	}

	return true;
}

/* deletes all instances of an event without deleting the event details. Sets stage for rebuilding event instances. */
function mc_delete_instances( $id ) {
	global $wpdb;
	$id  = (int) $id;
	$sql = "DELETE FROM " . my_calendar_event_table() . " WHERE occur_event_id = $id";
	$wpdb->query( $sql );

	return;
}

/* 
 * @param integer $id Event ID in my_calendar db
 * @param array $post an array of POST data (or array containing dates)
 * @param boolean $test true if testing
 *
 * @return null by default; data array if testing
*/
function mc_increment_event( $id, $post = array(), $test = false ) {
	global $wpdb;
	$event  = mc_get_event_core( $id, true );
	
	$data   = array();
	$return = array();
	if ( empty( $post ) ) {
		$orig_begin = $event->event_begin . ' ' . $event->event_time;
		$orig_end   = $event->event_end . ' ' . $event->event_endtime;
	} else {
		$orig_begin = @$post['event_begin'] . ' ' . @$post['event_time'];
		$orig_end   = @$post['event_end'] . ' ' . @$post['event_endtime'];
	}
	
	$group_id = $event->event_group_id;
	$format   = array( '%d', '%s', '%s', '%d' );
	$recurs   = str_split( $event->event_recur, 1 );
	$recur    = $recurs[0];
	// can't use 2nd value directly if it's two digits
	$every    = ( isset( $recurs[1] ) ) ? str_replace( $recurs[0], '', $event->event_recur ) : 1;
	if ( $recur != "S" ) {
		// if this event had a rep of 0, translate that.
		$event_repetition = ( $event->event_repeats != 0 ) ? $event->event_repeats : _mc_increment_values( $recur );
		$numforward       = (int) $event_repetition;
		if ( $recur != 'S' ) {
			switch ( $recur ) {
				case "D":
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$begin = my_calendar_add_date( $orig_begin, $i * $every, 0, 0 );
						$end   = my_calendar_add_date( $orig_end, $i * $every, 0, 0 );

						$data = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( $test == true ) {
							$return[] = $data;
						}
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'daily' );
							if ( ! $insert ) {
								$wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
					}
				break;
				case "E":
					// This doesn't work for weekdays unless the period is less than one week, as it doesn't account for day repetitions.
					// probably need to set up two nested for loops two identify the number of days forward required to go through x week days.
					// $every = e.g. every 14 weekdays
					// $numforward = e.g. 7 times
					if ( $every < 7 ) {
						for ( $i = 0; $i <= $numforward; $i ++ ) {
							$begin = my_calendar_add_date( $orig_begin, $i * $every, 0, 0 );
							$end   = my_calendar_add_date( $orig_end, $i * $every, 0, 0 );
							if ( ( date( 'w', $begin ) != 0 && date( 'w', $begin ) != 6 ) ) {
								$data = array(
									'occur_event_id' => $id,
									'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
									'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
									'occur_group_id' => $group_id
								);
								if ( $test == 'test' && $i > 0 ) {
									return $data;
								}
								if ( $test == true ) {
									$return[] = $data;
								}
								if ( ! $test ) {
									$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'daily' );
									if ( ! $insert ) {
										$wpdb->insert( my_calendar_event_table(), $data, $format );
									}
								}
							} else {
								$numforward ++;
							}
						}
					} else {
						// get number of weeks included in data'
						for( $i = 0; $i <= $event_repetition; $i ++ ) {
							$begin = strtotime( $orig_begin . ' ' . ( $every *  $i ) . ' weekdays' );
							$end   = strtotime( $orig_end . ' ' . ( $every * $i ) . ' weekdays' );
							$data = array(
								'occur_event_id' => $id,
								'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
								'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
								'occur_group_id' => $group_id
							);
							if ( $test == 'test' && $i > 0 ) {
								return $data;
							}
							if ( $test == true ) {
								$return[] = $data;
							}
							if ( ! $test ) {
								$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'daily' );
								if ( ! $insert ) {
									$wpdb->insert( my_calendar_event_table(), $data, $format );
								}
							}
						}
					}
					break;
				case "W":
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$begin = my_calendar_add_date( $orig_begin, ( $i * 7 ) * $every, 0, 0 );
						$end   = my_calendar_add_date( $orig_end, ( $i * 7 ) * $every, 0, 0 );
						$data  = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( $test == true ) {
							$return[] = $data;
						}						
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'weekly' );
							if ( ! $insert ) {							
								$sql = $wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
					}
					break;
				case "B":
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$begin = my_calendar_add_date( $orig_begin, ( $i * 14 ), 0, 0 );
						$end   = my_calendar_add_date( $orig_end, ( $i * 14 ), 0, 0 );
						$data  = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( $test == true ) {
							$return[] = $data;
						}
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'biweekly' );
							if ( ! $insert ) {							
								$wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
					}
					break;
				case "M":
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$begin = my_calendar_add_date( $orig_begin, 0, $i * $every, 0 );
						$end   = my_calendar_add_date( $orig_end, 0, $i * $every, 0 );
						$data  = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( $test == true ) {
							$return[] = $data;
						}
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'monthly' );
							if ( ! $insert ) {	
								$wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
					}
					break;
				case "U": 
					// important to keep track of which date variables are strings and which are timestamps
					$week_of_event = week_of_month( date( 'd', strtotime( $event->event_begin ) ) );
					$newbegin      = my_calendar_add_date( $orig_begin, 28, 0, 0 );
					$newend        = my_calendar_add_date( $orig_end, 28, 0, 0 );
					$fifth_week    = $event->event_fifth_week;
					$data          = array(
						'occur_event_id' => $id,
						'occur_begin'    => date( 'Y-m-d  H:i:s', strtotime( $orig_begin ) ),
						'occur_end'      => date( 'Y-m-d  H:i:s', strtotime( $orig_end ) ),
						'occur_group_id' => $group_id
					);

					if ( ! $test ) {
						$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'month-by-day' );
						if ( ! $insert ) {						
							$wpdb->insert( my_calendar_event_table(), $data, $format );
						}
					}
					$numforward = $numforward - 1;
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$next_week_diff = ( date( 'm', $newbegin ) == date( 'm', my_calendar_add_date( date( 'Y-m-d', $newbegin ), 7, 0, 0 ) ) ) ? false : true;
						$move_event     = ( ( $fifth_week == 1 ) && ( $week_of_event == ( week_of_month( date( 'd', $newbegin ) ) + 1 ) ) && $next_week_diff == true ) ? true : false;
						if ( $week_of_event == week_of_month( date( 'd', $newbegin ) ) || $move_event == true ) {
							// continue;
						} else {
							$newbegin   = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newbegin ), 7, 0, 0 );
							$newend     = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newend ), 7, 0, 0 );
							$move_event = ( $fifth_week == 1 && $week_of_event == week_of_month( date( 'd', $newbegin ) ) + 1 ) ? true : false;
							if ( $week_of_event == week_of_month( date( 'd', $newbegin ) ) || $move_event == true ) {
								// continue;
							} else {
								$newbegin = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newbegin ), 14, 0, 0 );
								$newend   = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newend ), 14, 0, 0 );
							}
						}
						$data = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $newbegin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $newend ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'month-by-day' );
							if ( ! $insert ) {								
								$wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
						$newbegin = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newbegin ), 28, 0, 0 );
						$newend   = my_calendar_add_date( date( 'Y-m-d  H:i:s', $newend ), 28, 0, 0 );
					}
					break;
				case "Y":
					for ( $i = 0; $i <= $numforward; $i ++ ) {
						$begin = my_calendar_add_date( $orig_begin, 0, 0, $i * $every );
						$end   = my_calendar_add_date( $orig_end, 0, 0, $i * $every );
						$data  = array(
							'occur_event_id' => $id,
							'occur_begin'    => date( 'Y-m-d  H:i:s', $begin ),
							'occur_end'      => date( 'Y-m-d  H:i:s', $end ),
							'occur_group_id' => $group_id
						);
						if ( $test == 'test' && $i > 0 ) {
							return $data;
						}
						if ( $test == true ) {
							$return[] = $data;
						}
						if ( ! $test ) {
							$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'annual' );
							if ( ! $insert ) {								
								$wpdb->insert( my_calendar_event_table(), $data, $format );
							}
						}
					}
					break;
			}
		}
	} else {
		$begin = strtotime( $orig_begin );
		$end   = strtotime( $orig_end );
		$data  = array(
			'occur_event_id' => $id,
			'occur_begin'    => date( 'Y-m-d H:i:s', $begin ),
			'occur_end'      => date( 'Y-m-d H:i:s', $end ),
			'occur_group_id' => $group_id
		);
		if ( ! $test ) {
			$insert = apply_filters( 'mc_insert_recurring', false, $data, $format, $id, 'single' );
			if ( ! $insert ) {				
				$wpdb->insert( my_calendar_event_table(), $data, $format );
			}
		}
	}

	if ( $test == true ) {
		return $return;
	}
	
	return $data;
}

// Actions -- these are action hooks attached to My Calendar events, usable to add additional actions during those events.
add_action( 'init', 'mc_register_actions' );
function mc_register_actions() {
	add_filter( 'mc_event_registration', 'mc_standard_event_registration', 10, 4 );
	add_filter( 'mc_datetime_inputs', 'mc_standard_datetime_input', 10, 4 );
	add_action( 'mc_transition_event', 'mc_tweet_approval', 10, 2 );
	add_action( 'mc_save_event', 'mc_event_post', 10, 3 );
	add_action( 'mc_delete_event', 'mc_event_delete_post', 10, 2 );
	add_action( 'mc_mass_delete_events', 'mc_event_delete_posts', 10, 1 );
	add_action( 'parse_request', 'my_calendar_api' );
}

// Filters
add_filter( 'post_updated_messages', 'mc_posttypes_messages' );
add_filter( 'tmp_grunion_allow_editor_view', '__return_false' );

// Actions
add_action( 'init', 'mc_taxonomies', 0 );
add_action( 'init', 'mc_posttypes' );

function mc_event_delete_posts( $deleted ) {
	foreach ( $deleted as $delete ) {
		$posts = get_posts( array(
				'post_type'  => 'mc-events',
				'meta_key'   => '_mc_event_id',
				'meta_value' => $delete
		) );
		if ( isset( $posts[0] ) && is_object( $posts[0] ) ) {	
			$post_id = $posts[0]->ID;
			wp_delete_post( $post_id, true );
		}
	}
}

add_action( 'load-options-permalink.php', 'mc_load_permalinks' );
function mc_load_permalinks() {
	if( isset( $_POST['mc_cpt_base'] ) )	{
		update_option( 'mc_cpt_base', sanitize_text_field( $_POST['mc_cpt_base'] ) );
	}
	
	// Add a settings field to the permalink page
	add_settings_field( 'mc_cpt_base', __( 'My Calendar Events base' ), 'mc_field_callback', 'permalink', 'optional', array( 'label_for'=>'mc_cpt_base' ) );
}

function mc_field_callback() {
	$value = ( get_option( 'mc_cpt_base' ) != '' ) ? get_option( 'mc_cpt_base' ) : 'mc-events';	
	echo '<input type="text" value="' . esc_attr( $value ) . '" name="mc_cpt_base" id="mc_cpt_base" class="regular-text" />';
}

function mc_posttypes() {
	$arguments = array(
		'public'              => apply_filters( 'mc_event_posts_public', true ),
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_menu'        => apply_filters( 'mc_show_custom_posts_in_menu', false ),
		'menu_icon'           => null,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' )
	);

	$types   = array(
		'mc-events' => array(
			__( 'event', 'my-calendar' ),
			__( 'events', 'my-calendar' ),
			__( 'Event', 'my-calendar' ),
			__( 'Events', 'my-calendar' ),
			$arguments
		),
	);
	$enabled = array( 'mc-events' );
	$slug = ( get_option( 'mc_cpt_base' ) != '' ) ? get_option( 'mc_cpt_base' ) : 'mc-events';
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value  =& $types[ $key ];
			$labels = array(
				'name'               => _x( $value[3], 'post type general name' ),
				'singular_name'      => _x( $value[2], 'post type singular name' ),
				'add_new'            => _x( 'Add New', $key, 'my-calendar' ),
				'add_new_item'       => sprintf( __( 'Create New %s', 'my-calendar' ), $value[2] ),
				'edit_item'          => sprintf( __( 'Modify %s', 'my-calendar' ), $value[2] ),
				'new_item'           => sprintf( __( 'New %s', 'my-calendar' ), $value[2] ),
				'view_item'          => sprintf( __( 'View %s', 'my-calendar' ), $value[2] ),
				'search_items'       => sprintf( __( 'Search %s', 'my-calendar' ), $value[3] ),
				'not_found'          => sprintf( __( 'No %s found', 'my-calendar' ), $value[1] ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'my-calendar' ), $value[1] ),
				'parent_item_colon'  => ''
			);
			$raw    = $value[4];
			$args   = array(
				'labels'              => $labels,
				'public'              => $raw['public'],
				'publicly_queryable'  => $raw['publicly_queryable'],
				'exclude_from_search' => $raw['exclude_from_search'],
				'show_ui'             => $raw['show_ui'],
				'show_in_menu'        => $raw['show_in_menu'],
				'menu_icon'           => ( $raw['menu_icon'] == null ) ? plugins_url( 'images', __FILE__ ) . "/icon.png" : $raw['menu_icon'],
				'query_var'           => true,
				'rewrite'             => array(
					'with_front' => false,
					'slug'       => apply_filters( 'mc_event_slug', $slug )
				),
				'hierarchical'        => false,
				'menu_position'       => 20,
				'supports'            => $raw['supports']
			);
			register_post_type( $key, $args );
		}
	}
}

/**
 * Most people don't want comments open on events. This will automatically close them. 
 */
function mc_close_comments( $posts ) {
	if ( !is_single() || empty( $posts ) ) { return $posts; }
	
	if ( 'mc-events' == get_post_type($posts[0]->ID) ) {
		if ( apply_filters( 'mc_autoclose_comments', true ) && $posts[0]->comment_status != 'closed' ) {
			$posts[0]->comment_status = 'closed';
			$posts[0]->ping_status    = 'closed';
			wp_update_post( $posts[0] );
		}
	}
	
	return $posts;
}
add_filter( 'the_posts', 'mc_close_comments' );


function mc_taxonomies() {
	global $mc_types;
	$types   = $mc_types;
	$enabled = array( 'mc-events' );
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value = $types[ $key ];
			register_taxonomy(
				"mc-event-category",    // internal name = machine-readable taxonomy name
				array( $key ),    // object type = post, page, link, or custom post-type
				array(
					'hierarchical' => true,
					'label'        => sprintf( __( '%s Categories', 'my-calendar' ), $value[2] ),
					// the human-readable taxonomy name
					'query_var'    => true,
					// enable taxonomy-specific querying
					'rewrite'      => array( 'slug' => apply_filters( 'mc_event_category_slug', 'mc-event-category' ) ),
					// pretty permalinks for your taxonomy?
				)
			);
		}
	}
}

function mc_posttypes_messages( $messages ) {
	global $post, $post_ID, $mc_types;
	$types   = $mc_types;
	$enabled = array( 'mc-events' );
	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key ) {
			$value            = $types[ $key ];
			$messages[ $key ] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => sprintf( __( '%1$s updated. <a href="%2$s">View %1$s</a>' ), $value[2], esc_url( get_permalink( $post_ID ) ) ),
				2  => __( 'Custom field updated.' ),
				3  => __( 'Custom field deleted.' ),
				4  => sprintf( __( '%s updated.' ), $value[2] ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( '%1$s restored to revision from %2$ss' ), $value[2], wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => sprintf( __( '%1$s published. <a href="%2$s">View %3$s</a>' ), $value[2], esc_url( get_permalink( $post_ID ) ), $value[0] ),
				7  => sprintf( __( '%s saved.' ), $value[2] ),
				8  => sprintf( __( '%1$s submitted. <a target="_blank" href="%2$s">Preview %3$s</a>' ), $value[2], esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $value[0] ),
				9  => sprintf( __( '%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %4$s</a>' ),
					$value[2], date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ), $value[0] ),
				10 => sprintf( __( '%1$s draft updated. <a target="_blank" href="%2$s">Preview %3$s</a>' ), $value[2], esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ), $value[0] ),
			);
		}
	}

	return $messages;
}

/* By default, disable comments on event posts */
add_filter( 'default_content', 'mc_posttypes_defaults', 10, 2 );
function mc_posttypes_defaults( $post_content, $post ) {
	if ( $post->post_type ) {
		switch ( $post->post_type ) {
			case 'mc-events':
				$post->comment_status = 'closed';
				break;
		}
	}

	return $post_content;
}

function mc_dismiss_notice() {
	if ( isset( $_GET['dismiss'] ) && $_GET['dismiss'] == 'update' ) {
		update_option( 'mc_update_notice', 1 );
	}
}

mc_dismiss_notice();

add_action( 'admin_notices', 'mc_update_notice' );
function mc_update_notice() {
	if ( current_user_can( 'activate_plugins' ) && get_option( 'mc_update_notice' ) == 0 || ! get_option( 'mc_update_notice' ) ) {
		$dismiss = admin_url( 'admin.php?page=my-calendar-behaviors&dismiss=update' );
		echo "<div class='updated fade'><p>" . sprintf( __( "<strong>Update notice:</strong> if you use custom JS with My Calendar, you need to activate your custom scripts following this update. <a href='%s'>Dismiss Notice</a>", 'wp-to-twitter' ), $dismiss ) . "</p></div>";
	}
}

// Actions are only performed after their respective My Calendar events have been successfully completed.
// If there are errors in the My Calendar event, the action hook will not fire.
/*
mc_save_event
Performed when an event is added, updated, or copied. Arguments are the action taken ('edit','copy','add') and 
and an array of the processed event data

mc_delete_event
Performed when an event is deleted. Argument is the event_id.

mc_mass_delete_events
Performed when events are deleted en masse. Argument is an array of event_ids deleted.

*/

// Filters -- these are filters applied on My Calendar elements, which you can use to modify output. 
// Base values are empty unless otherwise specified.
// The actual filters are in the places they belong, but these are here for documentation.
/*
mc_before_calendar
	- inserts information before the calendar is output to the page. 
	- received arguments: calendar setup variables
	
mc_after_calendar
	- inserts information after the calendar is output to the page.
	- received arguments: calendar setup variables
	
mc_before_event_title
	- insert information at beginning of event title.
	- received arguments: event object
	
mc_after_event_title
	- insert information after event title.
	- received arguments: event object
	
mc_before_event
	- insert information before event details
	- received arguments: event object
	
mc_after_event
	- insert information after event details
	- received arguments: event object
	
mc_event_content
	- base value: event content output.
	- received arguments: event details as string, event object
	- runs for all event output formats.
	
	mc_event_content_mini
		- same as above, only runs in mini output
	mc_event_content_list
		- same as above, only runs in list output
	mc_event_content_single
		- same as above, only runs in single output
	mc_event_content_grid
		- same as above, only runs in grid output

mc_event_upcoming
	- base value: upcoming event output
	- received arguments: event object
	
mc_event_today
	- base value: today's event output
	- received arguments: event object

mc_category_selector
	- base value: category selector output
	- received arguments: categories object

mc_location_selector
	- base value: location selector output
	- received arguments: locations object

mc_location_list
	-base value: location list output
	-received arguments: locations object
	
mc_category_key
	- base value: category key output
	- received arguments: categories object

mc_previous_link
	- base value: previous link output
	- received arguments: array of previous link parameters

mc_next_link
	- base value: next link output
	- received arguments: array of previous link parameters

mc_jumpbox
	- base value: jumpbox output
	- received arguments: none
	
mc_filter_styles
	- base value: styles head block (string)
	- received arguments: URL for your selected My Calendar stylesheet
	
mc_filter_javascript_footer
	- base value: javascript footer block
	- received arguments: none
	
mc_filter_shortcodes
	- base value: array of shortcodes and values
	- received arguments: event object
	
mc_search_template
	- base value: default search template (<strong>{date}</strong> {title} {details})
	- no arguments
	
mc_event_mail_to
	- base value: stored "to" email address from options
	- arguments: event template tag array
	
apply_filters( 'mc_display_format', $format, $args )

*/