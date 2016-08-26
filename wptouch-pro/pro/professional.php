<?php
add_action( 'wptouch_admin_ajax_intercept', 'wptouch_pro_admin_ajax_intercept' );
add_filter( 'wptouch_theme_directories', 'wptouch_pro_theme_directories' );
add_filter( 'wptouch_addon_directories', 'wptouch_pro_addon_directories' );
add_filter( 'wptouch_settings_compat', 'wptouch_pro_add_compat_settings' );

function wptouch_pro_add_compat_settings( $page_options ) {
	if ( function_exists( 'icl_get_languages' ) ) {
		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_COMPAT,
			'WPML',
			'compat-wpml',
			array(
				wptouch_add_setting(
					'checkbox',
					'show_wpml_lang_switcher',
					__( 'Show WPML language switcher in theme footer', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'3.2.1'
				)
			),
			$page_options
		);
	}

	return $page_options;
}

function wptouch_pro_addon_directories( $addon_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$addon_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/extensions', WPTOUCH_BASE_CONTENT_MS_URL . '/extensions' );
	}

	$addon_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/extensions', WPTOUCH_BASE_CONTENT_URL . '/extensions' );

	return $addon_directories;
}

function wptouch_pro_theme_directories( $theme_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$theme_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/themes', WPTOUCH_BASE_CONTENT_MS_URL . '/themes' );
	}

	$theme_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/themes', WPTOUCH_BASE_CONTENT_URL . '/themes' );

	return $theme_directories;
}

// Functions only available in the pro version
function wptouch_can_show_license_menu() {
	$should_show_license_menu = true;

	if ( !wptouch_is_multisite_enabled( ) ) {
		$settings = wptouch_get_settings( 'bncid' );
		$should_show_license_menu = ( !$settings->license_accepted );
	} else {
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			// Plugin is network activated
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted ) && is_network_admin();
		} else {
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted );
		}
	}

	return $should_show_license_menu;
}

function wptouch_should_show_license_nag() {
	if ( wptouch_is_multisite_enabled() ) {
		$settings = wptouch_get_settings( 'bncid' );
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			return ( !$settings->license_accepted ) && current_user_can( 'manage_network_options' );
		} else {
			return ( !$settings->license_accepted );
		}
	} else {
		return wptouch_can_show_license_menu();
	}
}

function wptouch_show_renewal_notice() {
	$settings = wptouch_get_settings( 'bncid' );

	if ( isset( $settings->license_expired ) ) {
		return $settings->license_expired;
	} else {
		return false;
	}
}

function wptouch_is_update_available() {
	global $wptouch_pro;

	return $wptouch_pro->check_for_update();
}

function wptouch_get_license_activation_url() {
	if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
		return network_admin_url( 'admin.php?page=wptouch-admin-license' );
	} else {
		return admin_url( 'admin.php?page=wptouch-admin-license' );
	}
}

function wptouch_pro_admin_ajax_intercept( $ajax_action ) {
	global $wptouch_pro;

	switch( $ajax_action) {
		case 'reset-license-info':
			$bnc_settings = wptouch_get_settings( 'bncid' );
			$bnc_settings->bncid = '';
			$bnc_settings->wptouch_license_key = '';

			$bnc_settings->license_accepted = false;
			$bnc_settings->license_accepted_time = 0;
			$bnc_settings->next_update_check_time = 0;
			$bnc_settings->license_expired = false;
			$bnc_settings->license_expiry_date = 0;

			$bnc_settings->save();
			break;
		case 'download-addon':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$destination_directory = wptouch_get_multsite_aware_install_path( 'extensions' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install_anywhere( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], $destination_directory );

			$result = array();

			if ( file_exists( $destination_directory . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;
		case 'download-theme':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$destination_directory = wptouch_get_multsite_aware_install_path( 'themes' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install_anywhere( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], $destination_directory );

			$result = array();

			if ( file_exists( $destination_directory . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;
	}
}

function wptouch_pro_install_theme( $theme_to_install ) {
	require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

	$destination_directory = wptouch_get_multsite_aware_install_path( 'themes' );

	$theme_installer = new WPtouchAddonThemeInstaller;
	$theme_installer->install_anywhere( $theme_to_install->base , $theme_to_install->download_url, $destination_directory );

	$result = array();

	if ( file_exists( $destination_directory . '/' . $theme_to_install->base ) ) {
		return str_replace( WP_CONTENT_DIR, '', $destination_directory ) . '/' . $theme_to_install->base;
	} else {
		if ( $theme_installer->had_error() ) {
			return $theme_installer;
		}
	}
}

function wptouch_pro_install_addon( $addon_to_install ) {
	require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

	$destination_directory = wptouch_get_multsite_aware_install_path( 'extensions' );

	$addon_installer = new WPtouchAddonThemeInstaller;
	$addon_installer->install_anywhere( $addon_to_install->base , $addon_to_install->download_url, $destination_directory );

	$result = array();

	if ( file_exists( $destination_directory . '/' . $addon_to_install->base ) ) {
		return str_replace( WP_CONTENT_DIR, '', $destination_directory ) . '/' . $addon_to_install->base;
	} else {
		if ( $addon_installer->had_error() ) {
			return $addon_installer;
		}
	}
}

function wptouch_pro_handle_admin_command() {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->get['admin_command'] ) ) {
		$admin_menu_nonce = $wptouch_pro->get['admin_menu_nonce'];
		if ( wptouch_admin_menu_nonce_is_valid( $admin_menu_nonce ) ) {
			// check user permissions
			if ( current_user_can( 'switch_themes' ) ) {
				switch( $wptouch_pro->get['admin_command'] ) {
					case 'activate_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating theme [' . $wptouch_pro->get['theme_name'] . ']' );
						wptouch_activate_theme( $wptouch_pro->get[ 'theme_name' ] );
						delete_transient( 'wptouch_customizer_settings' );
						break;
					case 'activate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_activate = $wptouch_pro->get['addon_name'];
						wptouch_activate_addon( $addon_to_activate );
						delete_transient( 'wptouch_customizer_settings' );
						break;
					case 'deactivate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deactivating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_deactivate = $wptouch_pro->get['addon_name'];
						wptouch_deactivate_addon( $addon_to_deactivate );
						break;
					case 'copy_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Copying theme [' . $wptouch_pro->get['theme_name'] . ']' );
						wptouch_pro_copy_theme( $wptouch_pro->get[ 'theme_name' ], $wptouch_pro->get['theme_location'] );
						break;
					case 'delete_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deleting theme [' . $wptouch_pro->get['theme_location'] . ']' );
						require_once( WPTOUCH_DIR . '/core/file-operations.php' );

						$theme_location = WP_CONTENT_DIR . $wptouch_pro->get['theme_location'];

						$wptouch_pro->recursive_delete( $theme_location );
						break;
				}
			}
		}

		$used_query_args = array( 'admin_menu_nonce', 'admin_command', 'theme_name', 'theme_location', 'addon_name', 'addon_location' );

		header( 'Location: ' . esc_url( remove_query_arg( $used_query_args ) ) );
		die;
	}
}

function wptouch_activate_theme( $theme_to_activate = false, $ignored = false ) {
	global $wptouch_pro;

	if ( $theme_to_activate && $theme_to_activate != '' ) {
		$settings = $wptouch_pro->get_settings();
		$continue = true;

		$available_themes = $wptouch_pro->get_available_themes( true );
		if ( isset( $available_themes[ $theme_to_activate ] ) && $available_themes[ $theme_to_activate ]->location == 'cloud' ) {
			$theme_location = wptouch_pro_install_theme( $available_themes[ $theme_to_activate ] );

			if ( is_object( $theme_location ) ) {
				$continue = false;
			}
		} else {
			$theme_location = $available_themes[ $theme_to_activate ]->location;
		}

		if ( wptouch_is_controlled_network() && is_network_admin() ) {
			$continue = false;
		}

		if ( $continue ) {
			$paths = explode( DIRECTORY_SEPARATOR, ltrim( rtrim( $theme_location, DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR ) );

			$settings->current_theme_name = $paths[ count( $paths ) - 1 ];
			unset( $paths[ count( $paths ) - 1 ] );

			$settings->current_theme_location = DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $paths );
			$settings->current_theme_friendly_name = $theme_to_activate;

			$settings->save();
		}
	}
}

function wptouch_pro_copy_theme( $theme_name, $theme_location ) {
	global $wptouch_pro;

	require_once( WPTOUCH_DIR . '/core/file-operations.php' );
	$theme_location = WP_CONTENT_DIR . $theme_location;
	$theme_name = wptouch_convert_to_class_name( $theme_name );

	$num = $wptouch_pro->get_theme_copy_num( $theme_name );
	$copy_dest = WPTOUCH_CUSTOM_THEME_DIRECTORY . '/' . $theme_name . '-copy-' . $num;
	wptouch_create_directory_if_not_exist( $copy_dest );

	$wptouch_pro->recursive_copy( $theme_location, $copy_dest );

	$readme_file = $copy_dest . '/readme.txt';
	$readme_info = $wptouch_pro->load_file( $readme_file );
	if ( $readme_info ) {
		if ( preg_match( '#Theme Name: (.*)#', $readme_info, $matches ) ) {
			$new_name = $matches[1] . ' Copy #' . $num;
			$readme_info = str_replace( $matches[0], 'Theme Name: ' . $new_name, $readme_info );
			$f = fopen( $readme_file, "w+t" );
			if ( $f ) {
				fwrite( $f, $readme_info );
				fclose( $f );
			}
		}
		return array( 'name' => $new_name, 'location' => $copy_dest );
	} else {
		WPTOUCH_DEBUG( WPTOUCH_ERROR, "Unable to modify readme.txt file after copy" );
		return false;
	}
}

function wptouch_activate_addon( $addon_to_activate ) {
	global $wptouch_pro;
	if ( $addon_to_activate ) {
		$settings = $wptouch_pro->get_settings();

		$is_multisite = ( $addon_to_activate == 'Multisite' );
		$extension_active = false;

		if ( $is_multisite ) {
			$multisite_info = get_site_option( 'wptouch_multisite_active', false, false );
			if ( $multisite_info ) {
				$extension_active = true;
			}
		} else {
			$extension_active = isset( $settings->active_addons[ $addon_to_activate ] );
		}

		if ( !$extension_active ) {
			$available_addons = $wptouch_pro->get_available_addons( true );
			if ( isset( $available_addons[ $addon_to_activate ] ) && $available_addons[ $addon_to_activate ]->location == 'cloud' ) {
				$addon_location = wptouch_pro_install_addon( $available_addons[ $addon_to_activate ] );

				if ( is_object( $addon_location ) ) {
					$continue = false;
				}
			} else {
				$addon_location = $available_addons[ $addon_to_activate ]->location;
			}

			if ( wptouch_is_controlled_network() && ( is_network_admin() && !$is_multisite ) ) {
				$continue = false;
			} else {
				$continue = true;
			}

			if ( $continue ) {
				$paths = explode( DIRECTORY_SEPARATOR, ltrim( rtrim( $addon_location, DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR ) );

				$addon_info = new stdClass;

				$addon_info->addon_name = $paths[ count( $paths ) - 1 ];
				unset( $paths[ count( $paths ) - 1 ] );
				$addon_info->location = DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $paths );

				if ( $is_multisite ) {
					$multisite_info = $addon_info;
					update_site_option( 'wptouch_multisite_active', $multisite_info );
				} else {
					$settings->active_addons[ $addon_to_activate ] = $addon_info;
					$settings->save();
				}

				do_action( 'wptouch_activated_addon_' . $addon_info->addon_name );
			}
		}
	}
}

function wptouch_deactivate_addon( $addon_to_deactivate ) {
	if ( $addon_to_deactivate ) {
		global $wptouch_pro;

		$is_multisite = ( $addon_to_deactivate == 'Multisite' );

		if ( $is_multisite ) {
			delete_site_option( 'wptouch_multisite_active' );
		} else {
			$settings = $wptouch_pro->get_settings();
			$addon_name = false;
			if ( isset( $settings->active_addons[ $addon_to_deactivate ] ) ) {
				$addon_name = $settings->active_addons[ $addon_to_deactivate ]->addon_name;
				unset( $settings->active_addons[ $addon_to_deactivate ] );
				$settings->save();
			}
		}

		do_action( 'wptouch_deactivated_addon_' . $addon_name );
	}
	delete_transient( 'wptouch_customizer_settings' );
}

function wptouch_pro_check_for_update() {
	global $wptouch_pro;

	$upgrade_available = WPTOUCH_VERSION;

	$wptouch_pro->setup_bncapi();

	$bnc_api = $wptouch_pro->get_bnc_api();

	$plugin_name = WPTOUCH_ROOT_NAME . '/wptouch-pro.php';

	WPTOUCH_DEBUG( WPTOUCH_INFO, 'Checking BNC server for a new product update' );

    // Check for WordPress 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		$option = get_site_transient( 'update_plugins' );
	} else {
		$option = function_exists( 'get_transient' ) ? get_transient( 'update_plugins' ) : get_option( 'update_plugins' );
	}

	$version_available = false;

	if ( false === ( $latest_info = get_site_transient( '_wptouch_bncid_latest_version' ) ) ) {
		$latest_info = $bnc_api->get_product_version();

		set_site_transient( '_wptouch_bncid_latest_version', $latest_info, WPTOUCH_API_GENERAL_CACHE_TIME );
	}

	if ( $latest_info && $latest_info[ 'version' ] != WPTOUCH_VERSION ) {
		WPTOUCH_DEBUG( WPTOUCH_INFO, 'A new product update is available [' . $latest_info['version'] . ']' );

		if ( isset( $latest_info[ 'upgrade_url' ] ) && wptouch_has_license() ) {
			if ( !isset( $option->response[ $plugin_name ] ) ) {
				$option->response[ $plugin_name ] = new stdClass();
			}

			// Update upgrade options
			$option->response[ $plugin_name ]->url = 'http://www.wptouch.com/';
			$option->response[ $plugin_name ]->package = $latest_info[ 'upgrade_url' ];
			$option->response[ $plugin_name ]->new_version = $latest_info['version'];
			$option->response[ $plugin_name ]->id = '0';
			$option->response[ $plugin_name ]->slug = WPTOUCH_ROOT_NAME;
		} else {
			if ( is_object( $option ) && isset( $option->response ) ) {
				unset( $option->response[ $plugin_name ] );
			}
		}

		$wptouch_pro->latest_version_info = $latest_info;
		$upgrade_available = $latest_info[ 'version' ];
	} else {
		if ( is_object( $option ) && isset( $option->response ) ) {
			unset( $option->response[ $plugin_name ] );
		}
	}

	// WordPress 3.0 changed some stuff, so we check for a WP 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		set_site_transient( 'update_plugins', $option );
	} else if ( function_exists( 'set_transient' ) ) {
		set_transient( 'update_plugins', $option );
	}

	return $upgrade_available;
}

function wptouch_pro_update_site_info() {
	global $wptouch_pro;

	$wptouch_pro->setup_bncapi();
	$bnc_api = $wptouch_pro->get_bnc_api();

	$settings = wptouch_get_settings();
	$active_addons = array();
	foreach( $settings->active_addons as $name => $info ) {
		$active_addons[] = $info->addon_name;
	}

	$settings_diff = array();

	$all_domains = $wptouch_pro->get_active_setting_domains();
	foreach( $all_domains as $domain ) {
		if ( $domain == 'bncid' ) {
			continue;
		}

		$this_diff = new stdClass;
		$default_settings = $wptouch_pro->get_setting_defaults( $domain );

		$settings = wptouch_get_settings( $domain );
		foreach( $settings as $key => $value ) {
			if ( !isset( $default_settings->$key ) || $settings->$key != $default_settings->$key ) {
				$this_diff->$key = $settings->$key;
			}
		}

		unset( $this_diff->domain );

		if ( count( (array)$this_diff ) ) {
			$settings_diff[ $domain ] = $this_diff;
		}
	}

	$wptouch_pro->bnc_api->user_update_info( WPTOUCH_VERSION, 'bauhaus', $active_addons, $settings_diff );
}


function mwp_wptouch_pro_get_latest_info() {
	global $wptouch_pro;

	$latest_info = false;

	// Do some basic caching
	$mwp_info = get_option( 'wptouch_pro_mwp', false );
	if ( !$mwp_info || !is_object( $mwp_info ) ) {
		$mwp_info = new stdClass;
		$mwp_info->last_check = 0;
		$mwp_info->last_result = false;
	}

	$time_since_last_check = time() - $mwp_info->last_check;
	if ( $time_since_last_check > 300 ) {
		$wptouch_pro->setup_bncapi();
    	$bnc_api = $wptouch_pro->get_bnc_api();
    	if ( $bnc_api ) {
    		$latest_info = $bnc_api->get_product_version();
    		if ( $latest_info ) {
    			$mwp_info->last_result = $latest_info;
    			$mwp_info->last_check = time();

    			// Save the result
    			update_option( 'wptouch_pro_mwp', $mwp_info );
    		}
    	}
	} else {
		// Use the cached copy
		$latest_info = $mwp_info->last_result;
	}

	return $latest_info;
}

add_action( 'wptouch_admin_ajax_update-themes-addons', 'wptouch_update_all_themes_addons' );
function wptouch_update_all_themes_addons() {
	global $wptouch_pro;
	if ( current_user_can( 'manage_options' ) ) {
		$current_theme = $wptouch_pro->get_current_theme_info();

		$available_themes = $wptouch_pro->get_available_themes( true );
		$available_addons = $wptouch_pro->get_available_addons( true );
		$updates = 0;
		$errors = array();

		if ( wptouch_is_update_available() ) {
			include_once( ABSPATH . 'wp-admin/includes/admin.php' );
			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
			$upgrader->upgrade( 'wptouch-pro/wptouch-pro.php' );
			if ( is_array( $upgrader->skin->result ) ) {
				$new_plugin_identifier = 'wptouch-pro/wptouch-pro.php';

				$active_plugins = get_option( 'active_plugins', array() );
				if ( !in_array( $new_plugin_identifier, $active_plugins ) ) {
					$active_plugins[] = $new_plugin_identifier;
					update_option( 'active_plugins', $active_plugins );
				}

				wptouch_pro_handle_activation();
			}
			$updates++;
		}

		if ( count( $available_themes ) > 0 || count( $available_addons ) > 0 ) {

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			foreach( $available_themes as $name => $theme ) {
				$skip_upgrade = false;
				if ( isset( $theme->theme_upgrade_available ) && $theme->theme_upgrade_available && version_compare( $theme->cloud_version, $theme->version, '>' ) ) {
					$installer = new WPtouchAddonThemeInstaller;
					$installer->install( $theme->base , $theme->download_url, 'themes' );
					if ( $installer->had_error() ) {
						$errors[] = $installer->error_text();
					} else {
						$updates++;
					}
				}
			}

			foreach( $available_addons as $name => $addon ) {
				if ( isset( $addon->extension_upgrade_available ) && $addon->extension_upgrade_available && isset( $addon->download_url ) ) {
					$installer = new WPtouchAddonThemeInstaller;
					$installer->install( $addon->base , $addon->download_url, 'extensions' );
					if ( $installer->had_error() ) {
						$errors[] = $installer->error_text();
					} else {
						$updates++;
					}
				}
			}

			if ( $updates && count( $errors ) > 0 ) {
				echo json_encode( array( 'status' => '0', 'error' => __( 'Some themes or extensions could not be updated.', 'wptouch-pro' ) ) );
			} elseif ( $updates ) {
				echo json_encode( array( 'status' => '1' ) );
			}
		} else {
			echo json_encode( array( 'status' => '1' ) );
		}
	}
}

function wptouch_update_all_addons() {
	global $wptouch_pro;
	if ( current_user_can( 'manage_options' ) ) {
		$available_addons = $wptouch_pro->get_available_addons( true );
		$updates = 0;
		$errors = array();

		if ( count( $available_addons ) > 0 ) {

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			foreach( $available_addons as $name => $addon ) {
				if ( isset( $addon->extension_upgrade_available ) && $addon->extension_upgrade_available && isset( $addon->download_url ) ) {
					$installer = new WPtouchAddonThemeInstaller;
					$installer->install( $addon->base , $addon->download_url, 'extensions' );
					if ( $installer->had_error() ) {
						$errors[] = $installer->error_text();
					} else {
						$updates++;
					}
				}
			}

			if ( $updates && count( $errors ) > 0 ) {
				echo json_encode( array( 'status' => '0', 'error' => __( 'Some extensions could not be updated.', 'wptouch-pro' ) ) );
			} elseif ( $updates ) {
				echo json_encode( array( 'status' => '1' ) );
			}
		} else {
			echo json_encode( array( 'status' => '1' ) );
		}
	}
}
