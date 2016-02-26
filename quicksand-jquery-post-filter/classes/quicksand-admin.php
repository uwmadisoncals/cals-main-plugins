<?php

if(!class_exists('QuicksandView')) {
	require "quicksand-view.php";
}
class QuicksandAdmin {
	
	 function __construct() {
		// Wordpress Add Actions referencing functions/methods from this class		
		add_action('admin_menu', array($this, 'quicksand_admin_actions'));
		// Ajax Calls for Option Delete
		add_action("wp_ajax_quicksanddelete", array($this,"quicksand_admin_ajax"));
		add_action("wp_ajax_nopriv_quicksanddelete", array($this,"quicksand_admin_ajax"));		
		// Add styles
		add_action( 'admin_enqueue_scripts', array( $this, 'quicksand_admin_style' ) );		
	}
	
	public function quicksand_admin_ajax() {
		$quicksandAdminView = new QuicksandView();
		$quicksandAdminView->deleteQuicksandCategory();
	}
	
	public function quicksand_admin_actions() {
		// Add Admin Menu Options
		add_options_page('QuicksandFilter', 'QuicksandFilter', 'manage_options', 'QuicksandFilter', array($this,'quicksand_admin_view'));
	}
	
	public function quicksand_admin_style() {
		  // Add admin style sheet
		wp_register_style( 'quicksand_admin_css',plugins_url('quicksand-jquery-post-filter/css/quicksand_admin.css'));
		wp_enqueue_style( 'quicksand_admin_css' );
	}
	
	public function quicksand_admin_view() {
		// Render admin view html
		$quicksandAdminView = new QuicksandView();
		$quicksandAdminView->renderAdmin();
	}

}