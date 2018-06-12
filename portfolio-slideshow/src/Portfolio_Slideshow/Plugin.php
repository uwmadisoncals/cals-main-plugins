<?php

defined( 'WPINC' ) or die;

class Portfolio_Slideshow_Plugin {

	const VERSION = '1.12.1';

	public static $defaults;
	public static $dot_min;
	public static $instance;
	public static $options;
	public static $plugin_dir;
	public static $plugin_path;
	public static $plugin_url;

	public function __construct() {

		self::$plugin_path = trailingslashit( dirname( __PORTFOLIO_SLIDESHOW_PLUGIN_FILE__ ) );
		self::$plugin_dir  = trailingslashit( basename( self::$plugin_path ) );
		self::$plugin_url  = plugins_url( self::$plugin_dir );

		self::$dot_min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		self::$options  = $this->get_options();
		self::$defaults = $this->get_defaults();

		require_once self::$plugin_path . 'src/Portfolio_Slideshow/Settings.php';
		require_once self::$plugin_path . 'src/public/functions.php';
		require_once self::$plugin_path . 'src/Portfolio_Slideshow/Shortcode.php';
		require_once self::$plugin_path . 'src/Portfolio_Slideshow/Upgrader.php';
		require_once self::$plugin_path . 'src/Portfolio_Slideshow/Slideshow.php';

		add_action( 'add_meta_boxes',        array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_init',            array( 'Portfolio_Slideshow_Settings', 'admin_init' ) );
		add_action( 'admin_menu',            array( $this, 'admin_menu' ), 5 );
		add_action( 'edit_post',             array( $this, 'edit_post' ), 99 );
		add_action( 'media_row_actions',     array( $this, 'media_row_actions' ) );
		add_action( 'save_post',             array( $this, 'edit_post' ), 99 );
		add_action( 'wp_enqueue_scripts',    array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_head',               array( 'Portfolio_Slideshow_Slideshow', 'wp_head' ) );

		// Temporary for version 1.12.1 only.
		add_action( 'admin_notices', array( $this, 'temporary_survey_notice' ) );

		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_to_save' ), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __PORTFOLIO_SLIDESHOW_PLUGIN_FILE__ ) . 'plugin.php' ), array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

		add_shortcode( 'portfolio_slideshow', array( 'Portfolio_Slideshow_Shortcode', 'do_shortcode' ) );
	}

	/**
	 * Loads the admin view for the uploader metabox.
	 *
	 * @return array
	 */
	public function add_meta_box( $post ) {
		require_once( self::$plugin_path . 'src/admin/uploader.php' );
	}

	/**
	 * Register the uploader metabox.
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function add_meta_boxes( $post_type ) {
		if ( in_array( $post_type, $this->get_supported_types() ) ) {
			add_meta_box( 'portfolio_slideshow_uploader_meta_box', __( 'Portfolio Slideshow', 'portfolio-slideshow' ), array( $this, 'add_meta_box' ), $post_type, 'normal', 'high' );
		}
	}

	/**
	 * Loads the admin view for the options page.
	 *
	 * @return void
	 */
	public function add_options_page() {
		require_once( self::$plugin_path . 'src/admin/settings.php' );
	}

	/**
	 * Load admin-facing JS and CSS.
	 *
	 * @param string $hook
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {

		$js_deps  = array( 'jquery', 'underscore', 'jquery-ui-core', 'jquery-ui-tabs' );
		$css_deps = array();

		$slugs = array( 'post.php', 'post-new.php', 'edit.php' );

		if ( 'settings_page_portfolio_slideshow' == $hook ) {
			$js_deps[]  = 'jquery-ui-tooltip';
			$css_deps[] = 'portfolio-slideshow-tooltip-css';
			wp_register_style( 'portfolio-slideshow-tooltip-css', self::vendor_resource_url( 'jquery-ui/jquery-ui.css' ), array(), self::VERSION, 'all' );
		}

		if ( in_array( $hook, $slugs ) && in_array( get_post_type(), $this->get_supported_types() ) || 'settings_page_portfolio_slideshow' == $hook ) {
			wp_enqueue_style( 'portfolio-slideshow-admin-css', self::resource_url( 'admin.css' ), $css_deps, self::VERSION, 'all' );
			wp_enqueue_script( 'portfolio-slideshow-admin-js', self::resource_url( 'admin.js', true ), $js_deps, self::VERSION, true );

			wp_localize_script( 'portfolio-slideshow-admin-js', 'portfolio_slideshow_admin_i18n', array(
				'strings' => array(
					'slide_singular'  => esc_html__( 'Slide', 'portfolio-slideshow' ),
					'edit_singular'   => esc_html__( 'Edit slide', 'portfolio-slideshow' ),
					'delete_singular' => esc_html__( 'Delete slide', 'portfolio-slideshow' ),
					'add_plural'      => esc_html__( 'Add Slides', 'portfolio-slideshow' )
				)
			) );
	 	}
	}

	/**
	 * Register the Portfolio Slideshow options page.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_options_page( esc_html__( 'Portfolio Slideshow', 'portfolio-slideshow' ), esc_html__( 'Slideshows', 'portfolio-slideshow' ), 'manage_options', 'portfolio_slideshow', array( $this, 'add_options_page' ) );
	}

	/**
	 * Renders the input form for the "Slide URL" field on attachments.
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post ) {

		$form_fields['ps_image_link'] = array(
			'label' => __( 'Slide URL', 'portfolio-slideshow' ),
			'input' => 'text',
			'value' => sanitize_text_field( get_post_meta( $post->ID, '_ps_image_link', true ) )
		);

		return $form_fields;
	}

	/**
	 * Update the "Slide URL" field on attachments.
	 *
	 * @return array
	 */
	public function attachment_fields_to_save( $post, $attachment ) {

		if ( isset( $attachment['ps_image_link'] ) ) {
			update_post_meta( $post['ID'], '_ps_image_link', $attachment['ps_image_link'] );
		}

		return $post;
	}

	/**
	 * Save and update the Uploader metabox.
	 *
	 * @param int $post_id The WP_Post ID.
	 * @return void
	 */
	public function edit_post( $post_id ) {

		if ( ! isset( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ] ) )
			return $post_id;

		if ( empty( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ] ) || ! wp_verify_nonce( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ], 'portfolio_slideshow_save_metabox_slides' ) )
			wp_die( esc_html__( 'It doesn\'t seem like you have permission to create or edit slideshows.', 'portfolio-slideshow' ) );


		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) )
			return $post_id;

		$existing = get_post_meta( $post_id, '_portfolio_slideshow', true );

		if ( ! isset( $_POST['portfolio_slideshow_metabox_slides_order'] ) ) {
			if ( empty( $existing ) ) {
				return $post_id;
			}

			update_post_meta( $post_id, '_portfolio_slideshow', ' ' );
		}

		$to_save = array();

		$attachments = array_map( 'absint', $_POST['portfolio_slideshow_metabox_slides_order'] );

		foreach ( $attachments as $attachment_id ) {

			if ( 0 == $attachment_id ) continue;

			$attachment      = get_post( $attachment_id );
			$attachment_meta = get_post_meta( $attachment_id );

			$to_save[] = array(
				'image'   => $attachment_id,
				'caption' => isset( $attachment->post_excerpt ) && is_string( $attachment->post_excerpt ) ? sanitize_text_field( $attachment->post_excerpt ) : '',
				'url'     => isset( $attachment_meta['_ps_image_link'] ) && is_string( $attachment_meta['_ps_image_link'] ) ? sanitize_text_field( $attachment_meta['_ps_image_link'] ) : '',
			);
		}

		update_post_meta( $post_id, '_portfolio_slideshow', $to_save );
	}

	/**
	 * Gets the default values for plugin options.
	 *
	 * @return array
	 */
	public function get_defaults() {

		$defaults = array(
			'size'             => 400,
			'nowrap'           => false,
			'loop'             => false,
			'speed'            => 4000,
			'trans'            => 'fade',
			'timeout'          => 3000,
			'exclude_featured' => false,
			'autoplay'         => true,
			'pagerpos'         => 'bottom',
			'navpos'           => 'top',
			'showcaps'         => false,
			'showtitles'       => false,
			'showdesc'         => false,
			'click'            => 'advance',
			'thumbs'           => '', // @TODO – REMOVE this option too, possibly
			'slideheight'      => false,
			'target'           => '_self',
			'id'               => '', // per-shortcode, no default
			'exclude'          => '', // per-shortcode, no default
			'include'          => '', // per-shortcode, no default
		);

		return apply_filters( 'portfolio_slideshow_option_defaults', $defaults );
	}

	/**
	 * Gets a specified plugin option, with its default value as fallback.
	 *
	 * @return mixed|false
	 */
	public static function get_option( $option ) {

		if ( isset( self::$options[ $option ] ) ) {
			return self::$options[ $option ];
		}

		if ( isset( self::$defaults[ $option ] ) ) {
			return self::$defaults[ $option ];
		}

		return false;
	}

	/**
	 * Gets the plugin options via get_option(). Returns false array if empty.
	 *
	 * @return array
	 */
	public static function get_options() {
		$options = get_option( 'portfolio_slideshow_options' );

		return $options ? $options : array();
	}

	/**
	 * Gets post types where uploader metabox should load.
	 *
	 * @return array
	 */
	public function get_supported_types() {
		return apply_filters( 'portfolio_slideshow_get_supported_types', array( 'post', 'page' ) );
	}

	/**
	 * Returns the current instance if it exists, or a new one if not.
	 *
	 * @return Portfolio_Slideshow_Plugin
	 */
	public static function instance() {
		return ! self::$instance ? new Portfolio_Slideshow_Plugin : self::$instance;
	}

	/**
	 * A duplicate of WooCommerce's handy let_to_num function. Transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @param $size
	 * @return int
	 */
	public static function letter_to_number(  $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );

		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}

		return $ret;
	}

	/**
	 * Adds the Attachment ID to the admin list of media item actions.
	 *
	 * @param array $content
	 * @return array
	 */
	public function media_row_actions( $content ) {

		if ( ! is_array( $content ) || ! is_object( $post ) )
			return $content;

		$attachment_id = isset( $post->ID ) ? $post->ID : get_the_ID();
		$content[]     = sprintf( esc_html__( 'Attachment ID: %s', 'portfolio-slideshow' ), absint( $attachment_id ) );

		return $content;
	}

	/**
	 * Prepends a "Settings" link to "Deactivate" and "Edit" on the Plugins page.
	 *
	 * @param array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {

		$url = admin_url( 'options-general.php?page=portfolio_slideshow' );

  		array_unshift( $links, sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $url ), esc_html__( 'Settings', 'portfolio-slideshow' ) ) );

  		return $links;
	}

	/**
	 * Adds "Settings" link next to
	 *
	 * @param array $plugin_meta
	 * @return array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( isset( $plugin_data['slug'] ) && 'portfolio-slideshow' == $plugin_data['slug'] && is_array( $plugin_meta ) )
			$plugin_meta[] = sprintf( '<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/plugin/portfolio-slideshow/', esc_html__( 'Support', 'portfolio-slideshow' ) );

		return $plugin_meta;
	}

	/**
	 * Gets a URL to a plugin resource, perhaps a minified one.
	 *
	 * @param string $resource
	 * @param bool $has_min
	 * @return string
	 */
	public static function resource_url( $resource = '', $has_min = false ) {

		if ( '' == $resource )
			return self::$plugin_url . 'src/resources/';

		if ( ! $has_min )
			return esc_url_raw( self::$plugin_url . 'src/resources/' . $resource );

		$file    = pathinfo( $resource );
		$sub_dir = '.' !== $file['dirname'] ? $file['dirname'] . '/' : '';

		return esc_url_raw( self::$plugin_url . 'src/resources/' . $sub_dir . $file['filename'] . self::$dot_min .'.'. $file['extension'] );
	}

	/**
	 * Gets a URL to a plugin resource, perhaps a minified one.
	 *
	 * @param string $resource
	 * @param bool $has_min
	 * @return string
	 */
	public static function vendor_resource_url( $resource = '', $has_min = false ) {

		if ( '' == $resource )
			return self::$plugin_url . 'vendor/';

		if ( ! $has_min )
			return esc_url_raw( self::$plugin_url . 'vendor/' . $resource );

		$file    = pathinfo( $resource );
		$sub_dir = '.' !== $file['dirname'] ? $file['dirname'] . '/' : '';

		return esc_url_raw( self::$plugin_url . 'vendor/' . $sub_dir . $file['filename'] . self::$dot_min .'.'. $file['extension'] );
	}

	/**
	 * Register front-end JS and CSS so that the shortcode instance can load it.
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts() {

		$css_deps = array();
		$js_deps  = array();

		wp_register_style( 'psp-photoswipe-css', self::vendor_resource_url( 'photoswipe.css', true ), array(), self::VERSION, 'all' );

		wp_register_script( 'psp-scrollable', self::vendor_resource_url( 'scrollable.js', true ), array( 'jquery' ), '1.2.5', true );
		wp_register_script( 'psp-photoswipe-js', self::vendor_resource_url( 'code.photoswipe.jquery-3.0.4.js' ), array( 'jquery' ), self::VERSION, true );
		wp_register_script( 'psp-cycle', self::vendor_resource_url( 'jquery-cycle/jquery.cycle.all.min.js' ), array( 'jquery' ), '2.99', true );

		if ( 'true' == self::get_option( 'scrollable' ) ) {
			$js_deps[] = 'psp-scrollable';
		}

		if ( 'true' == self::get_option( 'photoswipe' ) ) {
			$css_deps[] = 'psp-photoswipe-css';
			$js_deps[]  = 'psp-photoswipe-js';
		}

		$js_deps[]  = 'psp-cycle';

		wp_register_style( 'ps-public-css', self::resource_url( 'public.css' ), $css_deps, self::VERSION, 'all' );
		wp_register_script( 'ps-public-js', self::resource_url( 'public.js' ), $js_deps, self::VERSION, true );
	}

	/**
	 * A one-time temporary survey.
	 *
	 * @since 1.12.1
	 */
	public function temporary_survey_notice() {

		if ( isset( $_GET['portfolioslideshow_dismiss'] ) && 'yes' === $_GET['portfolioslideshow_dismiss'] ) {
			return update_option( 'portfolio_slideshow_temp_survey_dismissed', 'yes' );
		}

		if ( 'yes' === get_option( 'portfolio_slideshow_temp_survey_dismissed' ) ) {
			return;
		}

		include_once( self::$plugin_path . 'src/views/survey-notice.php' );
	}
}