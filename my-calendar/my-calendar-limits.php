<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function mc_select_category( $category, $type = 'event', $group = 'events' ) {
	$category = urldecode( $category );
	global $wpdb;
	$mcdb = $wpdb;
	if ( get_option( 'mc_remote' ) == 'true' && function_exists( 'mc_remote_db' ) ) {
		$mcdb = mc_remote_db();
	}
	$select_category = '';
	$data            = ( $group == 'category' ) ? 'category_id' : 'event_category';
	if ( preg_match( '/^all$|^all,|,all$|,all,/i', $category ) > 0 ) {
		return '';
	} else {
		if ( strpos( $category, "|" ) || strpos( $category, "," ) ) {
			if ( strpos( $category, "|" ) ) {
				$categories = explode( "|", $category );
			} else {
				$categories = explode( ",", $category );
			}
			$numcat = count( $categories );
			$i      = 1;
			foreach ( $categories as $key ) {
				if ( is_numeric( $key ) ) {
					$key = (int) $key;
					if ( $i == 1 ) {
						$select_category .= ( $type == 'all' ) ? " WHERE (" : ' (';
					}
					$select_category .= " $data = $key";
					if ( $i < $numcat ) {
						$select_category .= " OR ";
					} else if ( $i == $numcat ) {
						$select_category .= ( $type == 'all' ) ? ") " : ' ) AND';
					}
					$i ++;
				} else {
					$key = esc_sql( trim( $key ) );
					$cat = $mcdb->get_row( "SELECT category_id FROM " . my_calendar_categories_table() . " WHERE category_name = '$key'" );
					if ( $cat ) {
						$category_id = $cat->category_id;
						if ( $i == 1 ) {
							$select_category .= ( $type == 'all' ) ? " WHERE (" : ' (';
						}
						$select_category .= " $data = $category_id";
						if ( $i < $numcat ) {
							$select_category .= " OR ";
						} else if ( $i == $numcat ) {
							$select_category .= ( $type == 'all' ) ? ") " : ' ) AND';
						}
						$i ++;
					} else {
						return '';
					}
				}
			}
		} else {
			if ( is_numeric( $category ) ) {
				$select_category = ( $type == 'all' ) ? " WHERE $data = $category" : " event_category = $category AND";
			} else {
				$cat = $mcdb->get_row( "SELECT category_id FROM " . my_calendar_categories_table() . " WHERE category_name = '$category'" );
				if ( is_object( $cat ) ) {
					$category_id     = $cat->category_id;
					$select_category = ( $type == 'all' ) ? " WHERE $data = $category_id" : " $data = $category_id AND";
				} else {
					$select_category = '';
				}
			}
		}

		return $select_category;
	}
}

function mc_prepare_search_query( $query ) {
	$query = esc_sql( $query );
	$db_type = mc_get_db_type();
	if ( $query != '' ) {
		if ( $db_type == 'MyISAM' ) {
			$search = " AND MATCH(" . apply_filters( 'mc_search_fields', 'event_title,event_desc,event_short,event_label,event_city,event_postcode,event_registration' ) . ") AGAINST ( '$query' IN BOOLEAN MODE ) ";
		} else {
			$search = " AND event_title LIKE '%$query%' OR
						event_desc LIKE '%$query%' OR
						event_short LIKE '%$query%' OR
						event_label LIKE '%$query%' OR
						event_city LIKE '%$query%' OR
						event_postcode LIKE '%$query%' OR
						event_registration LIKE '%$query%' ";
		}
	} else {
		$search = '';
	}	
	
	return $search;
}

function mc_select_author( $author, $type = 'event' ) {
	$author = urldecode( $author );
	if ( $author == '' || $author == 'all' || $author == 'default' || $author == null ) {
		return '';
	}
	$select_author = '';
	$data          = 'event_author';
	if ( isset( $_GET['mc_auth'] ) ) {
		$author = $_GET['mc_auth'];
	}
	if ( preg_match( '/^all$|^all,|,all$|,all,/i', $author ) > 0 ) {
		return '';
	} else {
		if ( strpos( $author, "|" ) || strpos( $author, "," ) ) {
			if ( strpos( $author, "|" ) ) {
				$authors = explode( "|", $author );
			} else {
				$authors = explode( ",", $author );
			}
			$numauth = count( $authors );
			$i       = 1;
			foreach ( $authors as $key ) {
				if ( is_numeric( $key ) ) {
					$key = (int) $key;
					if ( $i == 1 ) {
						$select_author .= ( $type == 'all' ) ? " WHERE (" : ' (';
					}
					$select_author .= " $data = $key";
					if ( $i < $numauth ) {
						$select_author .= " OR ";
					} else if ( $i == $numauth ) {
						$select_author .= ( $type == 'all' ) ? ") " : ' ) AND';
					}
					$i ++;
				} else {
					$key       = trim( $key );
					$author    = get_user_by( 'login', $key ); // get author by username
					$author_id = $author->ID;
					if ( $i == 1 ) {
						$select_author .= ( $type == 'all' ) ? " WHERE (" : ' (';
					}
					$select_author .= " $data = $author_id";
					if ( $i < $numauth ) {
						$select_author .= " OR ";
					} else if ( $i == $numauth ) {
						$select_author .= ( $type == 'all' ) ? ") " : ' ) AND';
					}
					$i ++;
				}
			}
		} else {
			if ( is_numeric( $author ) ) {
				$select_author = ( $type == 'all' ) ? " WHERE $data = $author" : " event_author = $author AND";
			} else {
				$author = trim( $author );
				$author = get_user_by( 'login', $author ); // get author by username

				if ( is_object( $author ) ) {
					$author_id     = $author->ID;
					$select_author = ( $type == 'all' ) ? " WHERE $data = $author_id" : " $data = $author_id AND";
				} else {
					$select_author = '';
				}
			}
		}

		return $select_author;
	}
}

function mc_select_host( $host, $type = 'event' ) {
	$host = urldecode( $host );
	if ( $host == '' || $host == 'all' || $host == 'default' || $host == null ) {
		return '';
	}
	$data        = 'event_host';
	$select_host = '';
	if ( isset( $_GET['mc_auth'] ) ) {
		$host = $_GET['mc_host'];
	}
	if ( preg_match( '/^all$|^all,|,all$|,all,/i', $host ) > 0 ) {
		return '';
	} else {
		if ( strpos( $host, "|" ) || strpos( $host, "," ) ) {
			if ( strpos( $host, "|" ) ) {
				$hosts = explode( "|", $host );
			} else {
				$hosts = explode( ",", $host );
			}
			$numhost = count( $hosts );
			$i       = 1;
			foreach ( $hosts as $key ) {
				if ( is_numeric( $key ) ) {
					$key = (int) $key;
					if ( $i == 1 ) {
						$select_host .= ( $type == 'all' ) ? " WHERE (" : ' (';
					}
					$select_host .= " $data = $key";
					if ( $i < $numhost ) {
						$select_host .= " OR ";
					} else if ( $i == $numhost ) {
						$select_host .= ( $type == 'all' ) ? ") " : ' ) AND';
					}
					$i ++;
				} else {
					$key     = trim( $key );
					$host    = get_user_by( 'login', $key ); // get host by username
					$host_id = $host->ID;
					if ( $i == 1 ) {
						$select_host .= ( $type == 'all' ) ? " WHERE (" : ' (';
					}
					$select_host .= " $data = $host_id";
					if ( $i < $numhost ) {
						$select_host .= " OR ";
					} else if ( $i == $numhost ) {
						$select_host .= ( $type == 'all' ) ? ") " : ' ) AND';
					}
					$i ++;
				}
			}
		} else {
			if ( is_numeric( $host ) ) {
				$select_host = ( $type == 'all' ) ? " WHERE $data = $host" : " event_host = $host AND";
			} else {
				$host = trim( $host );
				$host = get_user_by( 'login', $host ); // get author by username

				if ( is_object( $host ) ) {
					$host_id     = $host->ID;
					$select_host = ( $type == 'all' ) ? " WHERE $data = $host_id" : " $data = $host_id AND";
				} else {
					$select_host = '';
				}
			}
		}

		return $select_host;
	}
}


/**
 * Function to limit event query by location. 
 *
 * @string $type {deprecated}
 * @string $ltype {location type}
 * @mixed (string/integer) $lvalue {location value}
*/
function mc_limit_string( $type = '', $ltype = '', $lvalue = '' ) {
	global $user_ID;
	$limit_string  = $location = $current_location = "";
	if ( isset( $_GET['loc'] ) && isset( $_GET['ltype'] ) || ( $ltype != '' && $lvalue != '' ) ) {
		if ( ! isset( $_GET['loc'] ) && ! isset( $_GET['ltype'] ) ) {
			if ( $ltype != '' && $lvalue != '' ) {
				$location         = $ltype;
				$current_location = $lvalue;
			}
		} else {
			$location         = isset( $_GET['ltype'] ) ? urldecode( $_GET['ltype'] ) : $location;
			if ( isset( $_GET['loc'] ) ) {
				$current_location = isset( $_GET['loc'] ) ? urldecode( $_GET['loc'] ) : $current_location;
			} else if ( isset( $_GET['lvalue'] ) ) {
				$current_location = isset( $_GET['lvalue'] ) ? urldecode( $_GET['lvalue'] ) : $current_location;				
			}
		}
		switch ( $location ) {
			case "name" :
				$location_type = "event_label";
				break;
			case "city" :
				$location_type = "event_city";
				break;
			case "state" :
				$location_type = "event_state";
				break;
			case "zip" :
				$location_type = "event_postcode";
				break;
			case "country" :
				$location_type = "event_country";
				break;
			case "region" :
				$location_type = "event_region";
				break;
			default :
				$location_type = $location;
		}
		if ( in_array( $location_type, array(
				'event_label',
				'event_city',
				'event_state',
				'event_postcode',
				'event_country',
				'event_region',
				'event_location', 
				'event_street',
				'event_street2', 
				'event_url',
				'event_longitude',
				'event_latitude',
				'event_zoom',
				'event_phone',
				'event_phone2'
			) ) ) {
			if ( $current_location != 'all' && $current_location != '' ) {
				if ( is_numeric( $current_location ) ) {
					$limit_string = $location_type . ' = ' . intval( $current_location ) . ' AND';				
				} else {
					$limit_string = $location_type . " = '" . esc_sql( $current_location ) . "' AND";				
				}
			}
		}
	}
	if ( $limit_string != '' ) {
		if ( isset( $_GET['loc2'] ) && isset( $_GET['ltype2'] ) ) {
			$limit_string .= mc_secondary_limit( $_GET['ltype2'], $_GET['loc2'] );
		}
	}
	if ( isset( $_GET['access'] ) ) {
		$limit_string .= mc_access_limit( $_GET['access'] );
	}

	return apply_filters( 'mc_location_limit_sql', $limit_string, $ltype, $lvalue );
}

function mc_access_limit( $access ) {
	$options      = mc_event_access();
	$format       = ( isset( $options[ $access ] ) ) ? esc_sql( $options[ $access ] ) : false;
	$limit_string = ( $format ) ? " event_access LIKE '%$format%' AND" : '';

	return $limit_string;
}

// set up a secondary limit on location
function mc_secondary_limit( $ltype = '', $lvalue = '' ) {
	$limit_string     = "";
	$current_location = urldecode( $lvalue );
	$location         = urldecode( $ltype );
	switch ( $location ) {
		case "name":
			$location_type = "event_label";
			break;
		case "city":
			$location_type = "event_city";
			break;
		case "state":
			$location_type = "event_state";
			break;
		case "zip":
			$location_type = "event_postcode";
			break;
		case "country":
			$location_type = "event_country";
			break;
		case "region":
			$location_type = "event_region";
			break;
		default:
			$location_type = "event_label";
	}
	if ( $current_location != 'all' && $current_location != '' ) {
		$limit_string = " $location_type='$current_location' AND ";
		// $limit_string .= ($type=='all')?' AND':"";
	}

	return $limit_string;
}