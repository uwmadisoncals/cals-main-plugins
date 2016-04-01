<?php
/*
Plugin Name: UW CALS Custom Post Title URL
Plugin URI: http://wordpress.org/extend/plugins/custom-post-title-url/
Description: Adds a custom URL field for post or page titles, allowing you to replace their default permalinks.
Version: 1.0
Author: College of Agricultural and Life Sciences (CALS) at University of Wisconsin-Madison
Author URI: http://www.cals.wisc.edu
Last Updated: 08/30/11
*/

if(!class_exists('CALSCustomPostTitleURL')){

	class CALSCustomPostTitleURL{
	
		/**
		 * The constructor
		 * 
		 * Runs all required Actions and Filters depending on context (whether it's admin or otherwise)
		*/
		
		function CALSCustomPostTitleURL(){
			
			//Action and Filters
			
			if(is_admin()){
				
				//Add Page Copycat meta box to page editor
				add_action('add_meta_boxes', array($this, 'calscustomtitleurl_add_custom_box'));

				//On post save, save plugin's data
				add_action('save_post', array($this, 'calscustomtitleurl_save_postdata'));				

			} else {
				
				//redirect pages with custom title urls
				add_action('wp_head', array($this, 'calscustomtitleurl_redirect_to_customm_url'),1);
				
				//replace permalinks with custom title url
				add_filter('the_permalink', array($this, 'calscustomtitleurl_replace_permalink'),1);
			
			}
			
		} //EOF CALSCustomPostTitleURL
		
		
		/** 
		 * Adds Custom Title URL meta box to page/post editor
		 *
		 *
		 * @uses object $post
		*/
		function calscustomtitleurl_add_custom_box(){
			global $post;
			add_meta_box('calscustomtitleurl', __('Custom Title URL'), array($this,'calscustomtitleurl_inner_custom_box'), $post->post_type, 'advanced', 'core');
		} //EOF calscustomtitleurl_add_custom_box
		
		

		/* Populates Custom Title URL meta box in page editor
		 *
		 *
		 * @uses object $post
		*/
		function calscustomtitleurl_inner_custom_box(){
	
			global $post;
			
			//get custom field value
			$custom_title_url = get_post_meta($post->ID, '_cals_custom_title_url',true);
			
			// Use nonce for verification
			wp_nonce_field( plugin_basename(__FILE__), 'cals_custom_title_url_noncename' ); ?>
			
			<label class="screen-reader-text" for="_cals_custom_title_url"><?php _e('Custom Title URL:') ?></label>
            <p>Enter a URL below to replace the default permalink:<br/><br/>
			<strong>URL: </strong> <input type="text" id="_cals_custom_title_url" name="_cals_custom_title_url" value="<?php echo $custom_title_url;?>" size="48"/> <span class="description"> ( ex: http://www.website.com/ )</span><br/></p>
	<?php
    	} //EOF calscustomtitleurl_inner_custom_box


		/* Saves the plugin's custom data when the post is saved 
		 *
		 *
		 * @param int $post_id
		 * @reference http://codex.wordpress.org/Function_Reference/add_meta_box#Example
		*/
		function calscustomtitleurl_save_postdata($post_id){
		  
		  // Verify this came from the our screen and with proper authorization,
		  // because save_post can be triggered at other times
		
		  if ( !wp_verify_nonce( $_POST['cals_custom_title_url_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		  }
		
		  // Verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		  // to do anything
		  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
		
		  
		  // Check permissions to edit pages and/or posts
		  if ( 'page' == $_POST['post_type'] ||  'post' == $_POST['post_type']) {
			if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ))
			  return $post_id;
		  } 
		
		  // OK, we're authenticated: we need to find and save the data
		  $cals_custom_title_url = $_POST['_cals_custom_title_url'];
		
		  // save original page id
		  update_post_meta($post_id, '_cals_custom_title_url', $cals_custom_title_url); 
		
		}

		
		function calscustomtitleurl_replace_permalink($post_link){
			
			global $post;
			
			//get custom field value
			$custom_title_url = get_post_meta($post->ID, '_cals_custom_title_url',true);
			
			if ($custom_title_url!=""){
				$post_link = $custom_title_url;
			}
		
			return $post_link;
		
		}
		
		/**
		 * Redirects pages to their assigned custom URL
		 * when accessed via their original URL
		 *
		 *
		*/
		
		function calscustomtitleurl_redirect_to_customm_url(){
			global $post;
			
			//run only if not home
			if(!is_home()){
				$custom_title_url = get_post_meta($post->ID, '_cals_custom_title_url',true);
				
				if ($custom_title_url!=""){
					//print_r($post);echo $custom_title_url;
					wp_redirect($custom_title_url);
					exit();
				}
			}		
		} //EOF calscustomtitleurl_redirect_to_customm_url

	
	} //End of CALSCustomPostTitleURL class declaration

}

//Run everything: create $cals_custom_post_title_url object
if(class_exists("CALSCustomPostTitleURL")){	
	$cals_custom_post_title_url = new CALSCustomPostTitleURL();
}

?>