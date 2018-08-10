<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since 1.0.0
 * @package ipanorama
 * @subpackage ipanorama/admin
 */
class iPanorama_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $version;
	
	/**
	 * The post type of this plugin.
	 *
	 * @since 1.0.0
	 */
	private $post_type;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param      string    $plugin_name   the name of this plugin.
	 * @param      string    $version   the version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $post_type ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->post_type = $post_type;
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		$plugin_url = plugin_dir_url( dirname(__FILE__) );
		
		wp_enqueue_style( $this->plugin_name . '_effectless',  $plugin_url . 'lib/effect.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_ipanorama',   $plugin_url . 'lib/ipanorama.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_prettify',    $plugin_url . 'admin/lib/google-code-prettify/prettify.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_fontawesome', $plugin_url . 'admin/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_admin',       $plugin_url . 'admin/css/admin.css', array(), $this->version, 'all' );
		
		// include all theme css files
		$plugin_path = plugin_dir_path( dirname(__FILE__) );
		$path = $plugin_path . 'lib/ipanorama.theme.*.css';
		$files = glob( $path );
		foreach($files as $file) {
			$file = strtolower(basename($file));
			$matches = array();
			
			if(preg_match('/^ipanorama.theme.(.*).css?/', $file, $matches)) {
				$theme = $matches[1];
				wp_enqueue_style( $this->plugin_name . '_' . $theme, $plugin_url . 'lib/' . $file, array(), $this->version, 'all' );
			}
		}
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		$plugin_url = plugin_dir_url( dirname(__FILE__) );
		
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name . '-three',     $plugin_url . 'lib/three.min.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-ipanorama', $plugin_url . 'lib/jquery.ipanorama.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-prettify',  $plugin_url . 'admin/lib/google-code-prettify/prettify.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-admin',     $plugin_url . 'admin/js/admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-app',       $plugin_url . 'admin/js/app.min.js', array( 'jquery' ), $this->version, false );
	}
	
	/**
	 * Init the edit screen of the plugin post type item
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {
		/*
		 *  Documentation : https://developer.wordpress.org/reference/functions/add_meta_box/
		 */
		add_meta_box(
			$this->post_type . '_builder_box', 
			__('Builder', $this->plugin_name),
			array($this, 'display_meta_box_builder'),
			$this->post_type,
			'normal'
		);
		add_meta_box(
			$this->post_type . '_shortcode_box', 
			__('Using this iPanorama', $this->plugin_name),
			array($this, 'display_meta_box_shortcode'),
			$this->post_type,
			'side'
		);
		add_meta_box(
			$this->post_type . '_useful_links_box', 
			__('Useful Links', $this->plugin_name),
			array($this, 'display_meta_box_useful_links'), 
			$this->post_type,
			'side'
		);
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*/
		add_submenu_page('edit.php?post_type=' . $this->post_type, __('Settings', $this->plugin_name), __('Settings', $this->plugin_name), 'manage_options', $this->plugin_name . '_cfg', array($this, 'display_plugin_settings_page'));
		add_submenu_page('edit.php?post_type=' . $this->post_type, __('Documentation', $this->plugin_name), __('Documentation', $this->plugin_name), 'manage_options', $this->plugin_name . '_doc', array($this, 'display_plugin_documentation_page'));
	}
	
	/**
	 * Register the custom post type
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_custom_post_type() {
		$labels = array(
			'name'              => __( 'iPanorama Items', $this->plugin_name ),
			'singular_name'     => __( 'iPanorama Item', $this->plugin_name ),
			'add_new'           => __( 'Add New', $this->plugin_name ),
			'add_new_item'      => __( 'Add New Item', $this->plugin_name ),
			'edit_item'         => __( 'Edit Item', $this->plugin_name ),
			'new_item'          => __( 'New Item', $this->plugin_name ),
			'view_item'         => __( 'View Item', $this->plugin_name ),
			'search_items'      => __( 'Search iPanorama Item', $this->plugin_name ),
			'not_found'         => __( 'No iPanorama Item found', $this->plugin_name ),
			'not_found_in_trash'=> __( 'No iPanorama Item found in Trash', $this->plugin_name ),
			'parent_item_colon' => '',
			'all_items'         => __( 'All Items', $this->plugin_name ),
			'menu_name'         => __( 'iPanorama 360', $this->plugin_name ),
		);
		
		$args = array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'menu_position'   => 100,
			'supports'        => array( 'title' ),
			'menu_icon'       => 'dashicons-welcome-view-site'
		);
		
		/**
		 * Documentation : https://codex.wordpress.org/Function_Reference/register_post_type
		 */
		register_post_type( $this->post_type, $args );
	}
	
	/**
	 * Adds the custom columns to the post type admin screen
	 *
	 * @since 1.0.0
	 */
	public function manage_post_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Title', $this->plugin_name),
			'shortcode' => __( 'Shortcodes', $this->plugin_name),
			'scenes'    => __( 'Total Scenes', $this->plugin_name),
			'author'    => __( 'Author', $this->plugin_name),
			'date'      => __( 'Date', $this->plugin_name)
		);
		return $columns;
	}
	
	/**
	 * Populates the data in the custom columns
	 *
	 * @since 1.0.0
	 */
	function manage_posts_custom_column( $column_name ){
		$post = get_post();
		
		switch( $column_name ) {
			case 'shortcode' :
				echo '<code>[ipanorama id="' . $post->ID . '"]</code>';
				echo '<br>';
				echo '<code>[ipanorama slug="' . $post->post_name . '"]</code>';
				break;
			case 'scenes':
				$scenes = get_post_meta( $post->ID, 'ipnrm-meta-total-scenes', true );
				if($scenes) {
					echo $scenes . ' ' . __( 'scene(s)', $this->plugin_name);
				}
				break;
			default:
				break;
		}
	}
	
	/**
	 * Populates the view edit screen
	 *
	 * @since 1.3.0
	 */
	function view_edit($views) {
		$views['import'] = '<div class="updated"><p>Note: This is the lite version of the iPanorama WordPress plugin. You can create and use only one item, also the final image will have a watermark.<br/><a href="https://codecanyon.net/item/ipanorama-360-virtual-tour-builder-for-wordpress/17028820?ref=avirtum" target="_blank">Click here to purchase pro version</a></p></div>';
		return $views;
	}
	
	/**
	 * Insert post data
	 *
	 * @since 1.3.0
	 */
	function insert_post_data($data, $postarr) {
		if($data['post_type'] == $this->post_type) {
			$postCount = wp_count_posts( $this->post_type )->publish;
			if($postCount > 0 && $data['post_type'] !== 'revision' && $data['post_status'] == 'publish' && $data['post_excerpt'] !== 'ipanorama') {
				$data['post_status'] = 'trash';
			} else if($postCount == 0) {
				$data['post_excerpt'] = 'ipanorama';
			} else if($data['post_status'] == 'trash') {
				$data['post_excerpt'] = '';
			}
		}
		return $data;
	}
	
	/**
	 * Triggered whenever a post or page is created or updated
	 *
	 * @since 1.0.0
	 */
	public function save_post($post_id) {
		if ( isset( $_REQUEST['ipnrm-meta-total-scenes'] ) ) { // $_REQUEST can be used to reference both GET and POST data
			$meta_key = 'ipnrm-meta-total-scenes';
			$meta_value = $_REQUEST['ipnrm-meta-total-scenes'];
			if ( !add_post_meta($post_id, $meta_key, $meta_value, true) ) { 
				update_post_meta($post_id, $meta_key, $meta_value);
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-ui-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-ui-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-ui-cfg'],ENT_QUOTES,'UTF-8');
			
			if ( !add_post_meta($post_id, $meta_key, $meta_value, true) ) { 
				update_post_meta($post_id, $meta_key, $meta_value);
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-panorama-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-panorama-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-panorama-cfg'],ENT_QUOTES,'UTF-8');
			
			if ( !add_post_meta($post_id, $meta_key, $meta_value, true) ) { 
				update_post_meta($post_id, $meta_key, $meta_value);
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-panorama-theme'] ) ) { 
			$meta_key = 'ipnrm-meta-panorama-theme';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-panorama-theme'],ENT_QUOTES,'UTF-8');
			
			if ( !add_post_meta($post_id, $meta_key, $meta_value, true) ) { 
				update_post_meta($post_id, $meta_key, $meta_value);
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-ui-global-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-ui-global-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-ui-global-cfg'],ENT_QUOTES,'UTF-8');
			
			if ( get_option( $meta_key ) == false ) {
				$deprecated = null;
				$autoload = 'no';
				add_option( $meta_key, $meta_value, $deprecated, $autoload );
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-global-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-global-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-global-cfg'],ENT_QUOTES,'UTF-8');
			
			if ( get_option( $meta_key ) == false ) {
				$deprecated = null;
				$autoload = 'no';
				add_option( $meta_key, $meta_value, $deprecated, $autoload );
			}
		}
	}
	
	/**
	 * Triggered whenever a global settings are created or updated
	 *
	 * @since 1.3.3
	 */
	public function update_global_settings() {
		if ( isset( $_REQUEST['ipnrm-meta-ui-global-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-ui-global-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-ui-global-cfg'],ENT_QUOTES,'UTF-8');
			$result = false;
			
			if ( get_option( $meta_key ) == false ) {
				$deprecated = null;
				$autoload = 'no';
				$result = add_option( $meta_key, $meta_value, $deprecated, $autoload );
			} else {
				$result = update_option( $meta_key, $meta_value );
			}
		}
		
		if ( isset( $_REQUEST['ipnrm-meta-global-cfg'] ) ) { 
			$meta_key = 'ipnrm-meta-global-cfg';
			$meta_value = htmlentities($_REQUEST['ipnrm-meta-global-cfg'],ENT_QUOTES,'UTF-8');
			$result = false;
			
			if ( get_option( $meta_key ) == false ) {
				$deprecated = null;
				$autoload = 'no';
				$result = add_option( $meta_key, $meta_value, $deprecated, $autoload );
			} else {
				$result = update_option( $meta_key, $meta_value );
			}
		}
		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$data['result'] = $result;
			echo json_encode($data);
		} else {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}
		
		die();
	}
	
	/**
	 * Sets the messages for the custom post type
	 *
	 * @since 1.0.0
	 */
	public function post_updated_messages( $messages ) {
		$messages[$this->post_type][1] = __( 'iPanorama item updated.', $this->plugin_name);
		$messages[$this->post_type][4] = __( 'iPanorama item updated.', $this->plugin_name);
		
		return $messages;
	}
	
	/**
	 * Remove quick edit for the custom post type
	 *
	 * @since 1.0.0
	 */
	public function remove_quick_edit( $actions ) {
		$post = get_post();
		if( $post->post_type == $this->post_type ) {
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
	}
	
	/**
	 * Add settings action link to the plugins page
	 *
	 * @since 1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		 *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 */
		//[depricated] since 1.3.3
		//$settings_link[] = '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Documentation', $this->plugin_name) . '</a>';
		//return array_merge(  $settings_link, $links );
		return $links;
	}
	
	/**
	 * Hides with CSS the publishing options for the plugin
	 *
	 * @since 1.0.0
	 */
	function hide_minor_publishing() {
		$screen = get_current_screen();
		if( in_array( $screen->id, array( $this->post_type ) ) ) {
			echo '<style>#minor-publishing { display: none; }</style>';
		}
	}
	
	/**
	 * Sets the post status to published
	 *
	 * @since 1.0.0
	 */
	function force_published( $post ) {
		if( 'trash' !== $post[ 'post_status' ] ) { // we still want to use the trash
			if( in_array( $post[ 'post_type' ], array( $this->post_type ) ) ) {
				$post['post_status'] = 'publish';
			}
			return $post;
		}
	}
	
	/**
	 * Render the documentation page for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_documentation_page() {
		include_once( 'partials/ipanorama-documentation-display.php' );
	}
	
	/**
	 * Render the documentation page for this plugin.
	 *
	 * @since 1.3.3
	 */
	public function display_plugin_settings_page() {
		include_once( 'partials/ipanorama-settings-display.php' );
	}
	
	/**
	 * Render the builder box for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_meta_box_builder() {
		include_once( 'partials/ipanorama-meta-box-builder-display.php' );
	}
	
	/**
	 * Render the shortcode box for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_meta_box_shortcode() {
		include_once( 'partials/ipanorama-meta-box-shortcode-display.php' );
	}
	
	/**
	 * Render the useful links box for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_meta_box_useful_links() {
		include_once( 'partials/ipanorama-meta-box-useful-links-display.php' );
	}
}
