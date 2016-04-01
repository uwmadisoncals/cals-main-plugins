<?php 
/*
Plugin Name: CALS Post Geotagging
Description: This plugin enables post/page geotagging and GEoRSS capabilities in WordPress.
Version: 0.1
Author: Vidal Quevedo
*/

class CALSPostGeoTagging{
	
	/**
	* The constructor. Adds actions/filters to WP depending on context.
	*
	*/
	
	function CALSPostGeoTagging(){
		if(is_admin()){
			
			//add geotagging meta box to post editor
			add_action('add_meta_boxes', array($this, 'cals_pgt_add_custom_box'));

			
			//save geotagging data
		
		} else {
			
			//enable GeoRSS when feed=geo_rss is requested via GET
		
		}
	} //EOF CALSPostGeotagging
	
	
	/**
	* Adds meta box to post editor
	* 
	*
	* @uses object $post
	*/
	
	function cals_pgt_add_custom_box(){
		if(cals_run_if_admin('vquevedo')){	//<--remove 	
		global $post;
		add_meta_box('cals_pgt', __('Post GeoTagging'), array($this,'cals_pgt_populate_custom_box'), 'post', 'side');
		} // <-- remove
	} //EOF cals_pgt_add_custom_box

	
	/**
	* Populates meta box
	*
	*
	* @uses object $post
	*/
	
	function cals_pgt_populate_custom_box(){
		global $post;
		
		//get custom field value
		$geo_address = get_post_meta($post->ID, '_geo_address',true);
		$geo_lat = get_post_meta($post->ID, '_geo_lat',true);
		$geo_long = get_post_meta($post->ID, '_geo_long',true);
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename(__FILE__), 'cals_pgt_noncename' ); ?>
		
		<label class="screen-reader-text" for="_geo_address"><?php _e('Post Address / Location:') ?></label>
		<p>Enter an address or location for post:<br/>
		<input type="text" id="_geo_address" name="_geo_address" value="<?php echo $geo_address;?>"  width="32" maxlength="512"/> <br/><span class="description"> ( ex: "Barrow, AK" )</span><br/></p>
<?php
	} //EOF cals_pgt_populate_custom_box



} //End of CALSPostGeoTagging class



if (class_exists(CALSPostGeoTagging)){
	//create CALSPostGeoTagging instance
	$cals_pgt = new CALSPostGeoTagging();
}
?>