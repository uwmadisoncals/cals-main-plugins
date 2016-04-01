<?php
/*
Plugin Name: CALS Custom Related Links
Description: This plugin enables a "Related Links" box on page editor to maually add related links to pages. It's based on WP Links (formerly known as "Blogroll"). Use cals_custom_related_links() function to display list of links.
Version: 0.5
Author: Vidal Quevedo
*/

if(!class_exists('CALSCustomRelatedLinksBox')){

	class CALSCustomRelatedLinksBox{
	
		/**
		 * The constructor
		 * 
		 * Runs all required Actions and Filters depending on context (admin or front end)
		*/
		
		function CALSCustomRelatedLinksBox(){
			
			//Action and Filters
			
			if(is_admin()){
				
				//Add Related Links meta box to page editor
				add_action('add_meta_boxes', array($this, 'cals_crlb_add_custom_box'));

				//On post save, save plugin's data
				add_action('save_post', array($this, 'cals_crlb_save_postdata'));				

			} else {
				
				//replace permalinks with custom title url
				//add_filter('the_permalink', array($this, 'calscustomtitleurl_replace_permalink'),1);
			
			}
			
		} //EOF CALSCustomPostTitleURL
		
		
		/**
		 * Adds support for custom wp link selection on page editor (for "Related offices" box)
		 * 
		 * Since CALS Home 1.0
		*/
		function cals_crlb_add_custom_box(){
			add_meta_box('cals_custom_related_links_box', __('Related Links'), array($this,'cals_crlb_meta_box'), 'page', 'advanced', 'core');
		} //EOF cals_rlb_add_custom_box
		
		
		
		/**
		 * Populates cals_home_wp_links_meta box
		 *
		 * Based on code from wp-admin/link-manager.php
		*/
		
		function cals_crlb_meta_box(){
			
			global $post;
			
			//get custom field values
			$custom_related_links = get_post_meta($post->ID, '_cals_custom_related_links',true);
			$custom_related_links_title = get_post_meta($post->ID, '_cals_custom_related_links_title',true);

			// Use nonce for verification
			wp_nonce_field( plugin_basename(__FILE__), 'cals_custom_related_links_noncename' ); 

			
			//get links
			$args = array( 'hide_invisible' => 0, 'orderby' => 'name', 'hide_empty' => 0 );
			$links = get_bookmarks( $args );
			if ( $links ) {
				
				echo '<p>Fill our these options to add a custom "Related Links" box to this page.</p>';
				echo '<p><strong>Title </strong></p>';
				echo '<input type="text" name="_cals_custom_related_links_title" value="'.$custom_related_links_title.'" maxlength="128" width="60"/> <span class="description">The title of the Related Links box (e.g. "Related Offices")</span><br /><br />';
				
				echo '<p><strong>Links </strong></p>';
				echo '<p class="meta-options related_links">';
		
				foreach ($links as $link) {
					$link = sanitize_bookmark($link);
					$link->link_name = esc_attr($link->link_name);
					$edit_link = get_edit_bookmark_link($link);
					$short_url = str_replace('http://', '', $link->link_url);
					$short_url = preg_replace('/^www\./i', '', $short_url);
					if ('/' == substr($short_url, -1))
						$short_url = substr($short_url, 0, -1);
					if (strlen($short_url) > 35)
						$short_url = substr($short_url, 0, 32).'...';
		
				
				echo '<input type="checkbox" name="_cals_custom_related_links[]" value="'.$link->link_id.'"';
				
				if($custom_related_links!=''){
					foreach($custom_related_links as $crl){
						if($crl == $link->link_id){
							echo 'checked = "checked"';
						}
					}
				}
				
				echo '/> <label class="selectit">'.$link->link_name.' ('.$short_url.')  <a href="'.$edit_link.'" title="'. esc_attr(sprintf(__('Edit &#8220;%s&#8221;'), $link->link_name)) .'">Edit</a></label><br />';
				}
				echo '</p>';
				echo '<p><br /><a href="link-add.php">+ Add New Link</a></p>';
				
		
			}
		} //EOF cals_crlb_meta_box


		/* Saves the plugin's custom data when the post is saved 
		 *
		 *
		 * @param int $post_id
		 * @reference http://codex.wordpress.org/Function_Reference/add_meta_box#Example
		*/
		function cals_crlb_save_postdata($post_id){
		  
		  //print_r($_POST);
		  
		  // Verify this came from the our screen and with proper authorization,
		  // because save_post can be triggered at other times
		
		  if ( !wp_verify_nonce( $_POST['cals_custom_related_links_noncename'], plugin_basename(__FILE__) )) {
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
		  $cals_custom_related_links = $_POST['_cals_custom_related_links'];
		  $cals_custom_related_links_title = $_POST['_cals_custom_related_links_title'];
		
		  // save original page id
		  update_post_meta($post_id, '_cals_custom_related_links', $cals_custom_related_links); 
		  update_post_meta($post_id, '_cals_custom_related_links_title', $cals_custom_related_links_title); 
		
		}

		/**
		 * Prints list of custom related links
		 *
		 *
		*/
		
		public function cals_clrb_get_custom_related_links(){
			
			global $post;
			
			//get custom field value
			$custom_related_links = get_post_meta($post->ID, '_cals_custom_related_links',true);
			$custom_related_links_title = get_post_meta($post->ID, '_cals_custom_related_links_title',true);

			//print_r($custom_related_links);
			
			if($custom_related_links!=''){
				$link_ids = implode(',',$custom_related_links);
			
				//get links
				$args = array( 'include' => $link_ids, 'hide_invisible' => 1, 'orderby' => 'name', 'hide_empty' => 1 );
				$links = get_bookmarks( $args );
				
				if ($links) {
					
					//if there is no title, use "Related Links" by default
					if ($custom_related_links_title==''){
						$custom_related_links_title = 'Related Links';
					}
					
					$output = '<div id="related_links" class="box tan">
									<h4 class="dept_title">'.strtoupper($custom_related_links_title).'</h4>
									<ul class="box_list">';

					//print_r($links);
					
					foreach ($links as $link) {
						$link = sanitize_bookmark($link);
						$link->link_name = esc_attr($link->link_name);
					
						$output.= '<li><a href="'.$link->link_url.'"';
						
						//use link_name as title if no description was provided
						if(!empty($link->link_description)){
							$output.= ' title="'.$link->link_description.'"';
						} else {
							$output.= ' title="Link to '.$link->link_name.'"';
						}
						
						//make target="_blank" the default value for now 
						$link->link_target = '_blank';
						
						if(!empty($link->link_target)){
							$output.=' target="'.$link->link_target.'"';
						}
						
						$output.= '>'.$link->link_name.'</a></li>';
					}
				}
				
					$output.='</ul>
					</div>';
				
				echo $output;
			}
		
		} //EOF cals_crlb_get_custom_related_links


	} //End of CALSCustomPostTitleURL class declaration

}

/**
 * Print list of custom related links on demand
 *
 * Checks whether the $cals_crlb object exists. If not, it creates it and prints out list of links.
*/

function cals_custom_related_links(){
	global $cals_crlb;
	
	if(!isset($cals_crlb)){
		$cals_crlb = new CALSCustomRelatedLinksBox();
	}
	
	$cals_crlb->cals_clrb_get_custom_related_links();
}

//Run everything: create $cals_crlb object
if(class_exists("CALSCustomRelatedLinksBox")){	
	$cals_crlb = new CALSCustomRelatedLinksBox();
}
?>