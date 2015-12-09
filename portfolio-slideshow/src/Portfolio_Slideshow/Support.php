<?php

defined( 'WPINC' ) or die;

class Portfolio_Slideshow_Support {

	static $systeminfo;

	/**
	 * Lists any "Must-use Plugins".
	 *
	 * @return array
	 */
	static function get_mu_plugins() {
		$mu_plugins = array();

		if ( function_exists( 'get_mu_plugins' ) ) {
			$mu_plugins_raw = get_mu_plugins();
			
			foreach ( $mu_plugins_raw as $k => $v ) {
				$plugin = $v['Name'];
				
				if ( ! empty( $v['Version'] ) ) {
					$plugin .= sprintf( ' version %s', $v['Version'] );
				}
				if ( ! empty( $v['Author'] ) ) {
					$plugin .= sprintf( ' by %s', $v['Author'] );
				}
				if ( ! empty( $v['AuthorURI'] ) ) {
					$plugin .= sprintf( '(%s)', $v['AuthorURI'] );
				}

				$mu_plugins[] = $plugin;
			}
		}

		return $mu_plugins;
	}

	/**
	 * Lists any multisite network plugins.
	 *
	 * @return array
	 */
	static function get_network_plugins() {
		$network_plugins = array();

		if ( is_multisite() && function_exists( 'get_plugin_data' ) ) {
			$plugins_raw = wp_get_active_network_plugins();
			
			foreach ( $plugins_raw as $k => $v ) {
				$plugin_details = get_plugin_data( $v );
				$plugin         = $plugin_details['Name'];
				
				if ( ! empty( $plugin_details['Version'] ) ) {
					$plugin .= sprintf( ' version %s', $plugin_details['Version'] );
				}
				if ( ! empty( $plugin_details['Author'] ) ) {
					$plugin .= sprintf( ' by %s', $plugin_details['Author'] );
				}
				if ( ! empty( $plugin_details['AuthorURI'] ) ) {
					$plugin .= sprintf( '(%s)', $plugin_details['AuthorURI'] );
				}
				
				$network_plugins[] = $plugin;
			}
		}

		return $network_plugins;
	}

	/**
	 * Lists any non-multisite plugins.
	 *
	 * @return array
	 */
	static function get_plugins() {
		$plugins = array();

		if ( function_exists( 'get_plugin_data' ) ) {
			$plugins_raw = wp_get_active_and_valid_plugins();

			foreach ( $plugins_raw as $k => $v ) {
				$plugin_details = get_plugin_data( $v );
				$plugin         = $plugin_details['Name'];

				if ( ! empty( $plugin_details['Version'] ) ) {
					$plugin .= sprintf( ' version %s', $plugin_details['Version'] );
				}
				if ( ! empty( $plugin_details['Author'] ) ) {
					$plugin .= sprintf( ' by %s', $plugin_details['Author'] );
				}
				if ( ! empty( $plugin_details['AuthorURI'] ) ) {
					$plugin .= sprintf( '(%s)', $plugin_details['AuthorURI'] );
				}

				$plugins[] = $plugin;
			}
		}

		return $plugins;
	}

	/**
	 * Populates this class's $systeminfo property with the system info for output.
	 *
	 * @return void
	 */
	static function load_system_info() {
		$user = wp_get_current_user();
		
		self::$systeminfo = array(
			'URL'                => 'http://' . $_SERVER['HTTP_HOST'],
			'Name'               => $user->display_name,
			'Email'              => $user->user_email,
			'WordPress version'  => get_bloginfo( 'version' ),
			'PHP version'        => function_exists( 'phpversion' ) ? phpversion() : __( 'Could not determine PHP version because phpversion() does not exist.', 'portfolio-slideshow' ),
			'Plugins'            => self::get_plugins(),
			'Network Plugins'    => self::get_network_plugins(),
			'Must-Use Plugins'   => self::get_mu_plugins(),
			'Theme'              => wp_get_theme()->get( 'Name' ),
			'Multisite'          => is_multisite(),
			'Settings'           => Portfolio_Slideshow_Plugin::get_options(),
			'Max Upload Size'    => size_format( wp_max_upload_size() ),
			'WordPress Timezone' => get_option( 'timezone_string', __( 'Unknown or not set', 'portfolio-slideshow' ) ),
			'Server Timezone'    => date_default_timezone_get(),
			'Server Info'        => esc_html( $_SERVER['SERVER_SOFTWARE'] )
		);

		if ( function_exists( 'ini_get' ) ) {
			self::$systeminfo['PHP Post Max Size']      = size_format( ini_get( 'post_max_size' ) );
			self::$systeminfo['PHP Max Execution Time'] = ini_get( 'max_execution_time' );
			self::$systeminfo['PHP Max Input Vars']     = ini_get( 'max_input_vars' );
		}

		self::$systeminfo = apply_filters( 'portfolio_slideshow_load_system_info', self::$systeminfo );
	}

	/**
	 * Echose the system information into the wp-admin page.
	 *
	 * @return void
	 */
	static function render_system_info() {
		self::load_system_info();

		if ( ! is_array( self::$systeminfo ) ) return;

		?><dl class="support-stats"><?php

		foreach ( self::$systeminfo as $k => $v ) :
			
			switch ( $k ) {
				case 'name'  :
				case 'email' :
					continue 2;
					break;
				case 'url' :
					$v = sprintf( '<a href="%s">%s</a>', $v, $v );
					break;
			}

			if ( is_array( $v ) ) {
				$keys             = array_keys( $v );
				$key              = array_shift( $keys );
				$is_numeric_array = is_numeric( $key );
				unset( $keys );
				unset( $key );
			}

			printf( '<dt>%s</dt>', $k );

			if ( empty( $v ) ) {
				echo '<dd class="support-stats-null">-</dd>';
			} elseif ( is_bool( $v ) ) {
				printf( '<dd class="support-stats-bool">%s</dd>', $v );
			} elseif ( is_string( $v ) ) {
				printf( '<dd class="support-stats-string">%s</dd>', $v );
			} elseif ( is_array( $v ) && $is_numeric_array ) {
				printf( '<dd class="support-stats-array"><ul><li>%s</li></ul></dd>', join( '</li><li>', $v ) );
			} else {
				
				$formatted_v = array();

				foreach ( $v as $obj_key => $obj_val ) {
					if ( is_array( $obj_val ) ) {
						$formatted_v[] = sprintf( '<li>%s = <pre>%s</pre></li>', $obj_key, print_r( $obj_val, true ) );
					} else {
						$formatted_v[] = sprintf( '<li>%s = %s</li>', $obj_key, $obj_val );
					}
				}

				$v = join( "\n", $formatted_v );

				printf( '<dd class="support-stats-object"><ul>%s</ul></dd>', print_r( $v, true ) );
			}
		
		endforeach;

		?></dl><?php
	}

}