<?php
/**
 * Copyright (C) 2014-2018 ServMask Inc.
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

class Ai1wmme_Export_Enumerate {

	public static function execute( $params ) {

		// Set progress
		Ai1wm_Status::info( __( 'Retrieving a list of all WordPress files...', AI1WMME_PLUGIN_NAME ) );

		// Set include filters
		$include_filters = array();

		// Set exclude filters
		$exclude_filters = ai1wm_content_filters();

		// Exclude cache
		if ( isset( $params['options']['no_cache'] ) ) {
			$exclude_filters[] = 'cache';
		}

		// Exclude themes
		if ( isset( $params['options']['no_themes'] ) ) {
			$exclude_filters[] = 'themes';
		} else {
			$inactive_themes = array();

			// Exclude inactive themes
			if ( isset( $params['options']['no_inactive_themes'] ) ) {
				$all_themes    = array();
				$active_themes = array();

				// Get all themes
				foreach ( wp_get_themes() as $theme => $info ) {
					$all_themes[ $theme ] = 'themes' . DIRECTORY_SEPARATOR . $theme;
				}

				// Network or sites
				if ( isset( $params['options']['sites'] ) ) {
					foreach ( ai1wmme_include_sites( $params ) as $site ) {
						switch_to_blog( $site['BlogID'] );

						// Add active theme
						foreach ( array( get_template(), get_stylesheet() ) as $theme ) {
							$active_themes[ $theme ] = 'themes' . DIRECTORY_SEPARATOR . $theme;
						}

						restore_current_blog();
					}
				} else {
					foreach ( ai1wmme_sites( $params ) as $site ) {
						switch_to_blog( $site['BlogID'] );

						// Add active theme
						foreach ( array( get_template(), get_stylesheet() ) as $theme ) {
							$active_themes[ $theme ] = 'themes' . DIRECTORY_SEPARATOR . $theme;
						}

						restore_current_blog();
					}
				}

				// Set inactive themes
				$inactive_themes = array_values( array_diff( $all_themes, $active_themes ) );
			}

			// Set exclude filters
			$exclude_filters = array_merge( $exclude_filters, $inactive_themes );
		}

		// Exclude must-use plugins
		if ( isset( $params['options']['no_muplugins'] ) ) {
			$exclude_filters = array_merge( $exclude_filters, array( 'mu-plugins' ) );
		}

		// Exclude plugins
		if ( isset( $params['options']['no_plugins'] ) ) {
			$exclude_filters = array_merge( $exclude_filters, array( 'plugins' ) );
		} else {
			$inactive_plugins = array();

			// Exclude inactive plugins
			if ( isset( $params['options']['no_inactive_plugins'] ) ) {
				$all_plugins    = array();
				$active_plugins = array();

				// Get all plugins
				foreach ( get_plugins() as $plugin => $info ) {
					$all_plugins[ $plugin ] = 'plugins' . DIRECTORY_SEPARATOR .
						( ( dirname( $plugin ) === '.' ) ? basename( $plugin ) : dirname( $plugin ) );
				}

				// Network or sites
				if ( isset( $params['options']['sites'] ) ) {
					foreach ( ai1wmme_include_sites( $params ) as $site ) {
						switch_to_blog( $site['BlogID'] );

						// Add active plugin
						foreach ( get_plugins() as $plugin => $info ) {
							if ( is_plugin_active( $plugin ) ) {
								$active_plugins[ $plugin ] = 'plugins' . DIRECTORY_SEPARATOR .
									( ( dirname( $plugin ) === '.' ) ? basename( $plugin ) : dirname( $plugin ) );
							}
						}

						restore_current_blog();
					}
				} else {
					foreach ( ai1wmme_sites( $params ) as $site ) {
						switch_to_blog( $site['BlogID'] );

						// Add active plugin
						foreach ( get_plugins() as $plugin => $info ) {
							if ( is_plugin_active( $plugin ) ) {
								$active_plugins[ $plugin ] = 'plugins' . DIRECTORY_SEPARATOR .
									( ( dirname( $plugin ) === '.' ) ? basename( $plugin ) : dirname( $plugin ) );
							}
						}

						restore_current_blog();
					}
				}

				// Set inactive plugins
				$inactive_plugins = array_values( array_diff( $all_plugins, $active_plugins ) );
			}

			// Set exclude filters
			$exclude_filters = array_merge( $exclude_filters, ai1wm_plugin_filters( $inactive_plugins ) );
		}

		// Exclude media
		if ( isset( $params['options']['no_media'] ) ) {
			$exclude_filters = array_merge( $exclude_filters, array( 'uploads', 'blogs.dir' ) );
		} elseif ( isset( $params['options']['sites'] ) ) {
			$mainsite = false;

			// Set main site
			foreach ( ai1wmme_include_sites( $params ) as $site ) {
				if ( ai1wm_main_site( $site['BlogID'] ) ) {
					$mainsite = true;
					break;
				}
			}

			// Set exclude sites
			if ( $mainsite ) {
				foreach ( ai1wmme_exclude_sites( $params ) as $site ) {
					if ( ai1wm_main_site( $site['BlogID'] ) === false ) {
						$exclude_filters[] = ai1wm_files_path( $site['BlogID'] );
						$exclude_filters[] = ai1wm_sites_path( $site['BlogID'] );
					}
				}
			} else {
				// Set include sites
				foreach ( ai1wmme_include_sites( $params ) as $site ) {
					$include_filters[] = ai1wm_files_path( $site['BlogID'] );
					$include_filters[] = ai1wm_sites_path( $site['BlogID'] );
				}

				// Set exclude uploads
				$exclude_filters = array_merge( $exclude_filters, array( 'uploads', 'blogs.dir' ) );
			}
		}

		// Get total files count
		if ( isset( $params['total_files_count'] ) ) {
			$total_files_count = (int) $params['total_files_count'];
		} else {
			$total_files_count = 0;
		}

		// Get total files size
		if ( isset( $params['total_files_size'] ) ) {
			$total_files_size = (int) $params['total_files_size'];
		} else {
			$total_files_size = 0;
		}

		// Create map file
		$filemap = ai1wm_open( ai1wm_filemap_path( $params ), 'w' );

		// Iterate over sites directory
		foreach ( $include_filters as $path ) {
			try {

				// Iterate over content directory
				$iterator = new Ai1wm_Recursive_Directory_Iterator( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path );

				// Exclude new line file names
				$iterator = new Ai1wm_Recursive_Newline_Filter( $iterator );

				// Recursively iterate over content directory
				$iterator = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD );

				// Write path line
				foreach ( $iterator as $item ) {
					if ( $item->isFile() ) {
						if ( ai1wm_write( $filemap, $path . DIRECTORY_SEPARATOR . $iterator->getSubPathName() . PHP_EOL ) ) {
							$total_files_count++;

							// Add current file size
							$total_files_size += $iterator->getSize();
						}
					}
				}
			} catch ( Ai1wm_Quota_Exceeded_Exception $e ) {
				throw new Exception( 'Out of disk space.' );
			} catch ( Exception $e ) {
				// Skip bad file permissions
			}
		}

		try {

			// Iterate over content directory
			$iterator = new Ai1wm_Recursive_Directory_Iterator( WP_CONTENT_DIR );

			// Exclude new line file names
			$iterator = new Ai1wm_Recursive_Newline_Filter( $iterator );

			// Exclude uploads, plugins or themes
			$iterator = new Ai1wm_Recursive_Exclude_Filter( $iterator, apply_filters( 'ai1wm_exclude_content_from_export', $exclude_filters ) );

			// Recursively iterate over content directory
			$iterator = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD );

			// Write path line
			foreach ( $iterator as $item ) {
				if ( $item->isFile() ) {
					if ( ai1wm_write( $filemap, $iterator->getSubPathName() . PHP_EOL ) ) {
						$total_files_count++;

						// Add current file size
						$total_files_size += filesize( $iterator->getPathname() );
					}
				}
			}
		} catch ( Ai1wm_Quota_Exceeded_Exception $e ) {
			throw new Exception( 'Out of disk space.' );
		} catch ( Exception $e ) {
			// Skip bad file permissions
		}

		// Set progress
		Ai1wm_Status::info( __( 'Done retrieving a list of all WordPress files.', AI1WMME_PLUGIN_NAME ) );

		// Set total files count
		$params['total_files_count'] = $total_files_count;

		// Set total files size
		$params['total_files_size'] = $total_files_size;

		// Close the filemap file
		ai1wm_close( $filemap );

		return $params;
	}
}
