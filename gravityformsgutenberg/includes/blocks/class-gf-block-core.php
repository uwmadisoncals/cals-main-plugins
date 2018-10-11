<?php

class GF_Block_Core extends GF_Block {

	/**
	 * Contains an instance of this block, if available.
	 *
	 * @since  1.0
	 * @access private
	 * @var    GF_Block $_instance If available, contains an instance of this block.
	 */
	private static $_instance = null;

	/**
	 * Block type.
	 *
	 * @var string
	 */
	public $type = 'gravityforms/block';

	/**
	 * Get instance of this class.
	 *
	 * @since  1.0
	 * @access public
	 * @static
	 *
	 * @return GF_Block
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}





	// # SCRIPT / STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Register scripts for block.
	 *
	 * @since  1.0-beta-3
	 * @access public
	 *
	 * @uses   GFAddOn::get_base_path()
	 * @uses   GFAddOn::get_base_url()
	 *
	 * @return array
	 */
	public function scripts() {

		return array(
			array(
				'handle'   => 'gform_editor_block_core',
				'src'      => gf_gutenberg()->get_base_url() . '/js/blocks/core.min.js',
				'deps'     => array( 'wp-blocks', 'wp-element', 'wp-date', 'wp-components', 'wp-i18n' ),
				'version'  => filemtime( gf_gutenberg()->get_base_path() . '/js/blocks/core.min.js' ),
				'callback' => array( $this, 'localize_script' ),
			),
		);

	}

	/**
	 * Localize core block script.
	 *
	 * @since  1.0-beta-3
	 * @access public
	 *
	 * @param array $script Script arguments.
	 */
	public function localize_script( $script = array() ) {

		wp_localize_script(
			$script['handle'],
			'gform',
			array(
				'forms'              => gf_gutenberg()->get_forms(),
				'conditionalOptions' => gf_gutenberg()->get_conditional_options(),
				'icon'               => gf_gutenberg()->get_base_url() . '/images/blocks/core/icon.svg',
			)
		);

	}

	/**
	 * Register styles for block.
	 *
	 * @since  1.0-beta-3
	 * @access public
	 *
	 * @uses   GFAddOn::get_base_path()
	 * @uses   GFAddOn::get_base_url()
	 *
	 * @return array
	 */
	public function styles() {

		return array(
			array(
				'handle'  => 'gform_editor_block_core',
				'src'     => gf_gutenberg()->get_base_url() . '/css/block.css',
				'deps'    => array( 'wp-edit-blocks' ),
				'version' => filemtime( gf_gutenberg()->get_base_path() . '/css/block.css' ),
			),
		);

	}




	// # BLOCK RENDER -------------------------------------------------------------------------------------------------

	/**
	 * Display block contents on frontend.
	 *
	 * @since  1.0-beta-3
	 * @access public
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return string
	 */
	public function render_block( $attributes = array() ) {

		// Prepare variables.
		$form_id     = rgar( $attributes, 'formId' ) ? $attributes['formId'] : false;
		$title       = isset( $attributes['title'] ) ? $attributes['title'] : true;
		$description = isset( $attributes['description'] ) ? $attributes['description'] : true;
		$ajax        = isset( $attributes['ajax'] ) ? $attributes['ajax'] : false;
		$tabindex    = isset( $attributes['tabindex'] ) ? $attributes['tabindex'] : 0;
		$logic       = isset( $attributes['conditionalLogic'] ) ? $attributes['conditionalLogic'] : array();

		// If form ID was not provided or form does not exist, return.
		if ( ! $form_id || ( $form_id && ! GFAPI::get_form( $form_id ) ) || ! $this->can_view_block( $logic ) ) {
			return '';
		}

		return gravity_form( $form_id, $title, $description, false, null, $ajax, $tabindex, false );

	}




	// # BLOCK PREVIEW -------------------------------------------------------------------------------------------------

	/**
	 * Display block contents in block editor.
	 *
	 * @since  1.0-beta-3
	 * @access public
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return string
	 */
	public function preview_block( $attributes = array() ) {

		ob_start();
		include_once gf_gutenberg()->get_base_path() . '/includes/preview.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

}

try {

	// Register block.
	GF_Blocks::register( GF_Block_Core::get_instance() );

} catch ( Exception $e ) {

	// Log that block could not be registered.
	GFCommon::log_debug( 'Unable to register block; ' . $e->getMessage() );

}
