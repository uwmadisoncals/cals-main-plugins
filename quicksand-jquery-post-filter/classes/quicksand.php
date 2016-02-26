<?php
if(!class_exists('QuicksandView')) {
	require "quicksand-view.php";
}
class quicksand {	
	// Class for frontend methods	
	public function __construct() {
		// Add actions and hooks for the frontend plugin from this class
		add_action('wp_enqueue_scripts', array($this, 'quicksandfiles'));		
		add_shortcode('quicksand', array($this,'quicksandView'));
	}	
	// Quicksand File Includes	
	public function quicksandfiles() {	 
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'easing', plugins_url('quicksand-jquery-post-filter/js/jquery.easing.1.3.js'));	
		wp_register_script( 'quicksand', plugins_url('quicksand-jquery-post-filter/js/jquery.quicksand.js'));
		wp_register_script( 'main', plugins_url('quicksand-jquery-post-filter/js/main.js'));
		wp_register_style('layout', plugins_url('quicksand-jquery-post-filter/css/layout.css'));
		wp_enqueue_script( 'easing' );		
		wp_enqueue_script( 'quicksand' );	
		wp_enqueue_script( 'main' );		
		wp_enqueue_style('layout');	
	 }
	 public function quicksandView() {
		 // Render Frontend Quicksand HTML
		 $quicksandView = new QuicksandView();
		 $quicksandView->render();
	 }	
}
