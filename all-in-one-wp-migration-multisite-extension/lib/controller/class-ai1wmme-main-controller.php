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

class Ai1wmme_Main_Controller {

	/**
	 * Main Application Controller
	 *
	 * @return Ai1wmme_Main_Controller
	 */
	public function __construct() {
		register_activation_hook( AI1WMME_PLUGIN_BASENAME, array( $this, 'activation_hook' ) );

		// Activate hooks
		$this->activate_actions();
		$this->activate_filters();
		$this->activate_textdomain();
	}

	/**
	 * Activation hook callback
	 *
	 * @return void
	 */
	public function activation_hook() {

	}

	/**
	 * Initializes language domain for the plugin
	 *
	 * @return void
	 */
	private function activate_textdomain() {
		load_plugin_textdomain( AI1WMME_PLUGIN_NAME, false, false );
	}

	/**
	 * Register plugin menus
	 *
	 * @return void
	 */
	public function admin_menu() {

	}

	/**
	 * Enqueue scripts and styles for Export Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_export_scripts_and_styles( $hook ) {
		if ( stripos( 'toplevel_page_ai1wm_export', $hook ) === false ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style(
				'ai1wmme_export',
				Ai1wm_Template::asset_link( 'css/export.min.rtl.css', 'AI1WMME' ),
				array( 'ai1wm_export' )
			);
		} else {
			wp_enqueue_style(
				'ai1wmme_export',
				Ai1wm_Template::asset_link( 'css/export.min.css', 'AI1WMME' ),
				array( 'ai1wm_export' )
			);
		}

		wp_enqueue_script(
			'ai1wmme_export',
			Ai1wm_Template::asset_link( 'javascript/export.min.js', 'AI1WMME' ),
			array( 'ai1wm_export' )
		);
	}

	/**
	 * Enqueue scripts and styles for Import Controller
	 *
	 * @param  string $hook Hook suffix
	 * @return void
	 */
	public function enqueue_import_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_ai1wm_import', $hook ) === false ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style(
				'ai1wmme_import',
				Ai1wm_Template::asset_link( 'css/import.min.rtl.css', 'AI1WMME' ),
				array( 'ai1wm_import' )
			);
		} else {
			wp_enqueue_style(
				'ai1wmme_import',
				Ai1wm_Template::asset_link( 'css/import.min.css', 'AI1WMME' ),
				array( 'ai1wm_import' )
			);
		}

		wp_enqueue_script(
			'ai1wmme_import',
			Ai1wm_Template::asset_link( 'javascript/import.min.js', 'AI1WMME' ),
			array( 'ai1wm_import' )
		);
	}

	/**
	 * Outputs menu icon between head tags
	 *
	 * @return void
	 */
	public function admin_head() {
		?>
		<style type="text/css" media="all">
			.ai1wm-label {
				border: 1px solid #5cb85c;
				background-color: transparent;
				color: #5cb85c;
				cursor: pointer;
				text-transform: uppercase;
				font-weight: 600;
				outline: none;
				transition: background-color 0.2s ease-out;
				padding: .2em .6em;
				font-size: 0.8em;
				border-radius: 5px;
				text-decoration: none !important;
			}

			.ai1wm-label:hover {
				background-color: #5cb85c;
				color: #fff;
			}
		</style>
		<?php
	}

	/**
	 * Register listeners for actions
	 *
	 * @return void
	 */
	private function activate_actions() {
		// Init
		add_action( 'admin_init', array( $this, 'init' ) );

		// Admin header
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		// All in One WP Migration
		add_action( 'plugins_loaded', array( $this, 'ai1wm_loaded' ), 20 );

		// Export and import commands
		add_action( 'plugins_loaded', array( $this, 'ai1wm_commands' ), 20 );

		// Enqueue export scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_export_scripts_and_styles' ), 20 );

		// Enqueue import scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_import_scripts_and_styles' ), 20 );
	}

	/**
	 * Register listeners for filters
	 *
	 * @return void
	 */
	private function activate_filters() {
		// Add links to plugin list page
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 5, 2 );
	}

	/**
	 * Export and import commands
	 *
	 * @return void
	 */
	public function ai1wm_commands() {
		if ( is_multisite() ) {
			// Add export commands
			add_filter( 'ai1wm_export', 'Ai1wmme_Export_Config::execute', 70 );
			add_filter( 'ai1wm_export', 'Ai1wmme_Export_Config_File::execute', 80 );
			add_filter( 'ai1wm_export', 'Ai1wmme_Export_Enumerate::execute', 100 );
			add_filter( 'ai1wm_export', 'Ai1wmme_Export_Database::execute', 200 );

			// Add import commands
			add_filter( 'ai1wm_import', 'Ai1wmme_Import_Confirm::execute', 100 );
			add_filter( 'ai1wm_import', 'Ai1wmme_Import_Blogs::execute', 150 );
			add_filter( 'ai1wm_import', 'Ai1wmme_Import_Database::execute', 300 );
			add_filter( 'ai1wm_import', 'Ai1wmme_Import_Users::execute', 310 );
			add_filter( 'ai1wm_import', 'Ai1wmme_Import_Done::execute', 350 );

			// Remove export commands
			remove_filter( 'ai1wm_export', 'Ai1wm_Export_Enumerate::execute', 100 );
			remove_filter( 'ai1wm_export', 'Ai1wm_Export_Database::execute', 200 );

			// Remove import commands
			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Confirm::execute', 100 );
			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Blogs::execute', 150 );
			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Database::execute', 300 );
			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Done::execute', 350 );
		}
	}

	/**
	 * All in One WP Migration loaded
	 *
	 * @return void
	 */
	public function ai1wm_loaded() {
		if ( ! defined( 'AI1WM_PLUGIN_NAME' ) ) {
			if ( is_multisite() ) {
				add_action( 'network_admin_notices', array( $this, 'ai1wm_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'ai1wm_notice' ) );
			}
		} else {
			if ( is_multisite() ) {
				add_action( 'network_admin_menu', array( $this, 'admin_menu' ), 20 );
			} else {
				add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
			}

			// Add export options
			add_action( 'ai1wm_export_left_options', 'Ai1wmme_Export_Controller::sites' );

			// Add import unlimited
			add_filter( 'ai1wm_max_file_size', array( $this, 'max_file_size' ) );
		}
	}

	/**
	 * Display All in One WP Migration notice
	 *
	 * @return void
	 */
	public function ai1wm_notice() {
		?>
		<div class="error">
			<p>
				<?php
				_e(
					'Multisite extension requires <a href="https://wordpress.org/plugins/all-in-one-wp-migration/" target="_blank">All-in-One WP Migration plugin</a> to be activated. ' .
					'<a href="https://help.servmask.com/knowledgebase/install-instructions-for-multisite-extension/" target="_blank">Multisite Extension install instructions</a>',
					AI1WMME_PLUGIN_NAME
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add links to plugin list page
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( $file === AI1WMME_PLUGIN_BASENAME ) {
			$links[] = __( '<a href="https://help.servmask.com/knowledgebase/multisite-extension-user-guide/" target="_blank">User Guide</a>', AI1WMME_PLUGIN_NAME );
		}

		return $links;
	}

	/**
	 * Max file size callback
	 *
	 * @return integer
	 */
	public function max_file_size() {
		return AI1WMME_MAX_FILE_SIZE;
	}

	/**
	 * Register initial parameters
	 *
	 * @return void
	 */
	public function init() {
		if ( AI1WMME_PURCHASE_ID ) {
			update_option( 'ai1wmme_plugin_key', AI1WMME_PURCHASE_ID );
		}
	}
}
