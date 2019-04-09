<?php
/*
Plugin Name: UW Export Users to CSV
Plugin URI:
Description: A WordPress plugin for exporting user data to CSV format
Author: UW-Madison
Author URI: https://git.doit.wisc.edu/
Version: 0.0.2
License:
License URI:
Text Domain: uw-export-users-to-csv
Tags: uwmadison
*/

/**
 *
 *	Query site and user data and return as string
 *
 *	$sites: array of site IDs - Integer
 *	$roles: array of role names - String
 *
 */
function uw_export_users_to_csv_get_data( $sites = [], $roles = [] ) {

	// String to store CSV data prior to export
	$data = "Site ID,Site Name,User ID,User Roles,User Login,User Email\n";
	
	// Retrieve individual site data from multisite
	$blogs = get_sites( ['site__in' => $sites, 'number' => 1000000] );
	
	// Loop through sites
	foreach ( $blogs as $blog ) {
		
		// Switch to target site
		switch_to_blog( $blog->blog_id );
		
		// Query User Data
		$users = get_users( [
			'blog_id' => $blog->blog_id, 	// Users from this site only
			'role__in' => $roles 					// Users from only this blog
		] );
		
		// Loop through users and append to CSV data
		foreach ( $users as $user ) {
			
			// Site ID
			$data .= ( !empty( $blog->blog_id ) ) ? $blog->blog_id . "," : ",";
			
			// Site Name
			$data .= ( !empty( get_bloginfo( 'name' ) ) ) ? get_bloginfo( 'name' ) . "," : ",";
			
			// User ID
			$data .= ( !empty( $user->data->ID ) ) ? $user->data->ID . "," : ",";
			
			// User Roles
			$data .= ( !empty( $user->roles ) ) ? implode( ", ", $user->roles ). "," : ",";			
			
			// User Login 
			$data .= ( !empty( $user->data->user_login ) ) ? $user->data->user_login . "," : ",";
			
			// User Email
			$data .= ( !empty( $user->data->user_email ) ) ? $user->data->user_email . "," : ",";
			
			// New Line
			$data .= "\n";
		}
		
		// Switch back to original site
		restore_current_blog();
	}
	
	// Export data to CSV
	return html_entity_decode( $data, ENT_QUOTES );
}

/**
 *
 *	Add plugin settings page to network admin menu
 *
 */
function uw_export_users_to_csv_plugin_menu() {
	if ( is_super_admin() ) {
		add_menu_page(
			'Export Users to CSV', 									// Page Title
			'Export Users to CSV',									// Menu Title
			'list_users', 													// Capability
			'uw-export-users-to-csv', 							// Menu Slug
			'uw_export_users_to_csv_admin_display',	// Callback
			'dashicons-download'	 									// Dashicons
		);
	}
}
add_action('admin_menu', 'uw_export_users_to_csv_plugin_menu');
add_action('network_admin_menu', 'uw_export_users_to_csv_plugin_menu');

/**
 *
 *	Initiate CSV creation on parameter match
 *
 */
add_action('admin_init', function(){
		
	// Validate that the user is a network admin
	if ( !is_super_admin() ) return false;
	
	// Validate that user type parameter exists
	if( isset( $_GET["uw-export-users-to-csv"] ) ) {
		
		// Identify user type by parameter 
		switch ( $_GET["uw-export-users-to-csv"] ) {

			// Generate CSV of all users
			case "all":
					uw_export_users_to_csv_generate_csv();
					break;
			
			// Generate CSV of admin users
			case "admins":
					uw_export_users_to_csv_generate_csv( [], ['administrator'] );
					break;
		}
	}
	
	
	
	if( isset( $_POST ) ) {
		if ( isset( $_POST['userrole'] ) && isset( $_POST['userblog'] ) ) {
			uw_export_users_to_csv_generate_csv( $_POST['userblog'], $_POST['userrole'] );
		}
	}		
	
	
});

/**
 *
 *	Generate CSV and trigger download
 *
 */
function uw_export_users_to_csv_generate_csv( $sites = [], $roles = []  ) {

	// Export data to CSV
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header("Content-disposition: filename=UW_Export_Users_To_CSV_" . date("Y-m-d") . ".csv");
	print uw_export_users_to_csv_get_data( $sites, $roles );
	exit;	
}

/**
 *
 *	Display admin settings page
 *
 */
function uw_export_users_to_csv_admin_display() { ?>
<style>
	.button-wrap {
		background: #fdfdfd;
    border: 1px solid #dadada;
    padding: 0 10px;
		max-height: 500px;
		margin-top: 4px;
		text-align: center;
	}
	.field-group {
    max-width: 500px;
		display: inline-block;
		vertical-align: top;
	}
	.fieldset-header {
    min-width: 150px;
	}
	.fieldset-header h2 {
    background: #0085ba;
    color: white;
    padding: 11px;
		margin-bottom: 0;
	}
	.fieldset-header p {
    padding: 10px 10px 0;
    border-left: 1px solid #dadfe1;
    border-right: 1px solid #dadfe1;
    margin: 0;
		background: #fdfdfd;
	}
	fieldset {
    overflow: scroll;
		background: #fdfdfd;
    border: 1px solid #dadfe1;
    border-top-color: transparent;
    padding: 10px;
		max-height: 500px;
	}

</style>
<script>
	jQuery(function ($) {

		/**
		* Role Checkboxes
		*/

		// Toggle All Roles
		$('#userrole-toggle-all').change(function() {
			if( this.checked ) {
				// Check all
				$('.userrole-checkbox').prop('checked', true);
			} else {
				// Uncheck all
				$('.userrole-checkbox').prop('checked', false);
			}
		});

		// Toggle Individual Roles
		$('.userrole-checkbox').change(function() {
			if( !this.checked ) {
				// If some checkboxes are unchecked, also uncheck the Toggle All checkbox
				$('#userrole-toggle-all').prop('checked', false);
			} else if ( $('.userrole-checkbox').length == $('.userrole-checkbox:checked').length ) {
				// If all checkboxes are checked, also check the Toggle All checkbox
				$('#userrole-toggle-all').prop('checked', true);
			}
		});

		/**
		* Blog Checkboxes
		*/

		// Toggle All Blogs
		$('#userblog-toggle-all').change(function() {
			if( this.checked ) {
				// Check all
				$('.userblog-checkbox').prop('checked', true);
			} else {
				// Uncheck all
				$('.userblog-checkbox').prop('checked', false);
			}
		});

		// Toggle Individual Blogs
		$('.userblog-checkbox').change(function() {
			if( !this.checked ) {
				// If some checkboxes are unchecked, also uncheck the Toggle All checkbox
				$('#userblog-toggle-all').prop('checked', false);
			} else if ( $('.userblog-checkbox').length == $('.userblog-checkbox:checked').length ) {
				// If all checkboxes are checked, also check the Toggle All checkbox
				$('#userblog-toggle-all').prop('checked', true);
			}
		});

	});
</script>
<h1>Export Users to CSV</h1>
<form method="POST">
	<div class="field-group">
		<div class="fieldset-header">
			<h2>Sites</h2>
			<p>
				<input type="checkbox" value="all" id="userblog-toggle-all" name="userblog-toggle-all" checked />
				<label for="userblog-toggle-all"><b>Toggle All Sites</b></label>
			</p>
		</div>
		<fieldset>
			<?php
				$user_id = get_current_user_id();		
				$user_blogs = array();
				$blogs = ( is_super_admin() ) ?  get_sites( ['number' => 1000000] ) : get_blogs_of_user( $user_id );
				// Convert site object to array
				if ( is_super_admin() ) {
					foreach( $blogs as $blog ) {
						$user_blogs[$blog->blog_id] = get_blog_details( $blog->blog_id )->blogname;
					}
				} else {
					foreach( $blogs as $blog ) {
						// Ensure user is admin of each site
						if ( current_user_can_for_blog( $blog->userblog_id, 'administrator' ) ) {
							$user_blogs[$blog->userblog_id] = $blog->blogname;
						}
					}
				}
				asort( $user_blogs );
				foreach ( $user_blogs as $k => $v ) : ?>
					<input type="checkbox" class="userblog-checkbox" value="<?php echo $k; ?>" id="userblog-<?php echo $k; ?>" name="userblog[]" checked />
					<label for="userblog-<?php echo $k; ?>">
						<?php echo $v; ?>
					</label>
					<br />
			<?php endforeach;	?>
		</fieldset>
	</div>
	<div class="field-group">
		<div class="fieldset-header">
		<h2>Roles</h2>
		<p>
			<input type="checkbox" value="all" id="userrole-toggle-all" name="userrole-toggle-all" checked />
			<label for="userrole-toggle-all"><b>Toggle All Roles</b></label>
		</p>
		</div>
		<fieldset>
			<?php $roles = get_editable_roles();
				foreach ( $roles as $role ) : 
				?>
					<input type="checkbox" class="userrole-checkbox" value="<?php echo $role['name']; ?>" id="userrole-<?php echo $role['name']; ?>" name="userrole[]" checked />
						<label for="userrole-<?php echo $role['name']; ?>">
							<?php echo $role['name']; ?>
						</label>
				<br />
				<?php
				endforeach;
			?>
		</fieldset>
		<div class="button-wrap">
			<p>
				<input type="submit" value="Download" class="button button-primary" />
			</p>
		</div>
	</div>
</form>
<?php }