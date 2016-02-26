<?php
/*
Plugin Name: Quicksand
Plugin URI: http://www.developingtheweb.co.uk/plugins/quicksand-wordpress-plugin/
Description: Use the Jquery Quicksand to filter posts by categories.
Author: Mark Stockton
Version: 3.1.1
Tags: jQuery post filter, posts filter, quicksand filter, category filter
Author URI: http://www.developingtheweb.co.uk
*/
?>
<?php
		  if(is_admin()) {
			require plugin_dir_path( __FILE__ ) . '/classes/quicksand-admin.php';
			new QuicksandAdmin();
		  } else {
			require plugin_dir_path( __FILE__ ) . '/classes/quicksand.php';          
			new quicksand();
		  }
