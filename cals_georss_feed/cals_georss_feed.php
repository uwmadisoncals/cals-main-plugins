<?php
/*
Plugin Name: CALS GeoRSS feed
Description: Provides feeds in GeoRSS form. Based on JSON Feed plugin developed by Chris Northwood  & modified by Dan Phiffer.
Reference: http://thepremiumpress.com/free/2010/02/10/adding-georss-functionality-to-wordpress/
Version: 0.1.1 (03/16/11)
Author: Vidal Quevedo
*/

/*TODO


*/


/* Enables GeoRSS feed when URL feed parameter equals "georss"
 *
 *
 * @reference http://www.seodenver.com/custom-rss-feed-in-wordpress/
 * @reference http://thepremiumpress.com/free/2010/02/10/adding-georss-functionality-to-wordpress/
*/


function cals_georss_feed(){
	//now that we know it's a georss feed, add extra code to customize feed-rss2.php template 
	add_action('rss2_ns', 'cals_georss_feed_header');
	add_action('rss2_item', 'cals_georss_feed_lat_long');
	
	//add_filter('the_content', 'cals_georss_feed_content');
	
	//load RSS2 template to piggyback ride on it
	load_template( WPINC . '/feed-rss2.php');
	//load_template(WP_PLUGIN_DIR.'/cals_georss_feed/cals_georss_feed_template.php');
}

function cals_georss_feed_header(){
	echo '
	xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
	xmlns:georss="http://www.georss.org/georss"';
}

function cals_georss_feed_content($content){
	global $post_id;
	if(has_post_thumbnail($post_id)){
		$content = get_the_post_thumbnail( $post_id, $size, $attr ). " ". $content;
	}
	return $content;
}

function cals_georss_feed_lat_long(){
	
	global $post;
	
	$geo_lat = get_post_meta($post->ID, '_geo_lat', true);
	$geo_long = get_post_meta($post->ID, '_geo_long', true);
	
	if($geo_lat!='' && $geo_long!=''){
	
	echo '<georss:point>'.$geo_lat.' '.$geo_long.'</georss:point>
		  <geo:lat>'.$geo_lat.'</geo:lat>
		  <geo:long>'.$geo_long.'</geo:long>';
	}

}


//actions and filters
add_action('do_feed_georss', 'cals_georss_feed');


?>