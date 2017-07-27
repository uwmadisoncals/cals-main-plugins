<?php
/**
 * Copyright (C) 2014-2017 ServMask Inc.
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

function ai1wmme_create_blog( $domain, $path, $site_id = 1 ) {
	if ( empty( $path ) ) {
		$path = '/';
	}

	// Check if the domain has been used already. We should return an error message.
	if ( domain_exists( $domain, $path, $site_id ) ) {
		return __( '<strong>ERROR</strong>: Site URL already taken.' );
	}

	// Need to back up wpdb table names, and create a new wp_blogs entry for new blog.
	// Need to get blog_id from wp_blogs, and create new table names.
	// Must restore table names at the end of function.
	if ( ! $blog_id = insert_blog( $domain, $path, $site_id ) ) {
		return __( '<strong>ERROR</strong>: problem creating site entry.' );
	}

	switch_to_blog( $blog_id );
	install_blog( $blog_id );
	restore_current_blog();

	return $blog_id;
}

function ai1wmme_exclude_sites( $params = array() ) {
	$sites = array();

	// Add network
	if ( is_multisite() ) {
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

	return $sites;
}

function ai1wmme_include_sites( $params = array() ) {
	$sites = array();

	// Add network
	if ( is_multisite() ) {
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

	return $sites;
}

function ai1wmme_sites( $params = array() ) {
	$sites = array();

	// Add network
	if ( is_multisite() ) {
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

	return $sites;
}
