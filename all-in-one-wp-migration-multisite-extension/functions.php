<?php
/**
 * Copyright (C) 2014-2019 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

function ai1wmme_get_networks() {
	global $wpdb;

	// Get all networks
	$networks = $wpdb->get_results( "SELECT * FROM $wpdb->site ORDER BY id ASC", ARRAY_A );

	return $networks;
}

function ai1wmme_get_sites() {
	global $wpdb;

	// Get all sites
	$sites = $wpdb->get_results( "SELECT * FROM $wpdb->blogs ORDER BY blog_id ASC", ARRAY_A );

	return $sites;
}

function ai1wmme_exclude_sites( $params = array() ) {
	static $sites = array();

	// Add network
	if ( is_multisite() ) {
		if ( empty( $sites ) ) {
			foreach ( ai1wmme_get_sites() as $site ) {
				// Add site
				if ( in_array( $site['blog_id'], $params['options']['sites'] ) === false ) {
					switch_to_blog( $site['blog_id'] );

					// Add site meta
					$sites[] = array(
						'BlogID'     => (int) $site['blog_id'],
						'SiteID'     => (int) $site['site_id'],
						'LangID'     => (int) $site['lang_id'],
						'SiteURL'    => get_site_url( $site['blog_id'] ),
						'HomeURL'    => get_home_url( $site['blog_id'] ),
						'Domain'     => $site['domain'],
						'Path'       => $site['path'],
						'Plugins'    => array_values( array_diff( ai1wm_active_plugins(), ai1wm_active_servmask_plugins() ) ),
						'Template'   => ai1wm_active_template(),
						'Stylesheet' => ai1wm_active_stylesheet(),
					);

					restore_current_blog();
				}
			}
		}
	}

	return $sites;
}

function ai1wmme_include_sites( $params = array() ) {
	static $sites = array();

	// Add network
	if ( is_multisite() ) {
		if ( empty( $sites ) ) {
			foreach ( ai1wmme_get_sites() as $site ) {
				// Add site
				if ( in_array( $site['blog_id'], $params['options']['sites'] ) === true ) {
					switch_to_blog( $site['blog_id'] );

					// Add site meta
					$sites[] = array(
						'BlogID'     => (int) $site['blog_id'],
						'SiteID'     => (int) $site['site_id'],
						'LangID'     => (int) $site['lang_id'],
						'SiteURL'    => get_site_url( $site['blog_id'] ),
						'HomeURL'    => get_home_url( $site['blog_id'] ),
						'Domain'     => $site['domain'],
						'Path'       => $site['path'],
						'Plugins'    => array_values( array_diff( ai1wm_active_plugins(), ai1wm_active_servmask_plugins() ) ),
						'Template'   => ai1wm_active_template(),
						'Stylesheet' => ai1wm_active_stylesheet(),
					);

					restore_current_blog();
				}
			}
		}
	}

	return $sites;
}

function ai1wmme_sites( $params = array() ) {
	static $sites = array();

	// Add network
	if ( is_multisite() ) {
		if ( empty( $sites ) ) {
			foreach ( ai1wmme_get_sites() as $site ) {
				switch_to_blog( $site['blog_id'] );

				// Add site meta
				$sites[] = array(
					'BlogID'     => (int) $site['blog_id'],
					'SiteID'     => (int) $site['site_id'],
					'LangID'     => (int) $site['lang_id'],
					'SiteURL'    => get_site_url( $site['blog_id'] ),
					'HomeURL'    => get_home_url( $site['blog_id'] ),
					'Domain'     => $site['domain'],
					'Path'       => $site['path'],
					'Plugins'    => array_values( array_diff( ai1wm_active_plugins(), ai1wm_active_servmask_plugins() ) ),
					'Template'   => ai1wm_active_template(),
					'Stylesheet' => ai1wm_active_stylesheet(),
				);

				restore_current_blog();
			}
		}
	}

	return $sites;
}
