<?php
/*
Plugin Name: CALS WordPress MU Stats
Plugin URI: http://ocaoimh.ie/wordpress-mu-domain-mapping/
Description: Site title, owner, and contact information.
Version: 1.0
Author: Al Nemec
Author URI: http://cals.wisc.edu
*/
/*  Copyright Al Nemec
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/



/*echo get_site_option( 'site_owner' );



					if ( add_site_option( 'site_owner', 'new_value' ) ) {
					echo get_site_option( 'site_owner' );
					} else {
					echo 'Already exists';
					}*/



// show mapping on site admin blogs screen
function ra_stat_columns( $columns ) {
	$columns[ 'title' ] = __( 'Title' );
	$columns[ 'owner' ] = __( 'Owner' );
	$columns[ 'primarycontact' ] = __( 'Primary Contact' );
	return $columns;
}
add_filter( 'wpmu_blogs_columns', 'ra_stat_columns' );

function ra_stat_field( $column, $blog_id ) {
	global $wpdb;
	//static $maps = false;
	//static $maps2 = false;

	$wpdb->dmtable = $wpdb->base_prefix . 'blogs';
	$work = $wpdb->get_results( "SELECT blog_id, domain FROM {$wpdb->dmtable} ORDER BY blog_id" );
	$maps = array();

	if ( $column == 'title' ) {



			if($work) {
				foreach( $work as $blog ) {

                    $tablename = $wpdb->base_prefix.$blog->blog_id.'_options';
                    $sitetitle = $wpdb->get_results( "SELECT option_value FROM {$tablename} WHERE option_name='blogname'" );

                    $size = sizeof($sitetitle);
					$sitename = "<em>No Name Value</em>";


                    if($size > 0) {
                        $sitename = $sitetitle[0]->option_value;
                    }

					$maps[ $blog->blog_id ][] = $sitename;

				}
			}



		if( !empty( $maps[ $blog_id ] ) && is_array( $maps[ $blog_id ] ) ) {
			foreach( $maps[ $blog_id ] as $blog ) {
				echo $blog . '<br />';
			}
		}
	}

	if ( $column == 'owner' ) {

			if($work) {
				foreach( $work as $blog ) {


					$owner = get_blog_option($blog->blog_id, 'site_owner');

					if(!$owner) {
						add_blog_option( $blog->blog_id, 'site_owner', '' );
					}

					if($owner != "") {
						$maps[ $blog->blog_id ][] = $owner;
					}

				}
			}

			if( !empty( $maps[ $blog_id ] ) && is_array( $maps[ $blog_id ] ) ) {
				foreach( $maps[ $blog_id ] as $blog ) {
					echo $blog . '<br />';
				}
			}

	}


	if ( $column == 'primarycontact' ) {

		if($work) {
			foreach( $work as $blog ) {


				$contact = get_blog_option($blog->blog_id, 'primary_contact');

				if(!$contact) {
					add_blog_option( $blog->blog_id, 'primary_contact', '' );
				}

				if($contact != "") {
					$maps[ $blog->blog_id ][] = $contact;
				}

			}
		}

		if( !empty( $maps[ $blog_id ] ) && is_array( $maps[ $blog_id ] ) ) {
			foreach( $maps[ $blog_id ] as $blog ) {
				echo $blog . '<br />';
			}
		}

}
}
add_action( 'manage_blogs_custom_column', 'ra_stat_field', 1, 3 );
add_action( 'manage_sites_custom_column', 'ra_stat_field', 1, 3 );

