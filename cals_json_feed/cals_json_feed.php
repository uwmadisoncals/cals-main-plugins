<?php
/*
Plugin Name: CALS JSON feed
Description: Provides feeds in JSON form. Based on JSON Feed plugin developed by Chris Northwood  & modified by Dan Phiffer.
Version: 0.8
Author: Vidal Quevedo
*/

/*TODO
-
-"No posts found" message on fail  - line 102
-"Javascript needed" if JS disabled
-Add tag ID support? 
---getJSON not finding URL if a tag with a space is passed. MAybe I need to add the parameters in the data: function parameter and not as part of the URL
---query_posts is not processing tag_id or tag_slug__and. May have to only accept one tag
-Set transiwent to renew when new post has been added to system
*/

function cals_json_feed(){

	extract($_GET);
		
	
	//setup $transient_id ('cjf-' prefix plus the underscore-separated ids of requested categories). Example: 'cjf-3_4_-10'
	
	if($cat!=''){
		$transient_id = 'cjf-'.str_replace(',', '_', $cat);
	} else {
		$transient_id = 'cjf-all';
	}

	
	//CATEGORIES
		
		//Setup list of default excluded categories <-- this should be entered in a panel
		
		$cat_names = array('Cals in the media', 'Cals faces', 'Newsmakers', 'uncategorized');
		foreach($cat_names as $cn){
			$new_cat[] = '-'.get_cat_id($cn);
		}


		// Setup IDs of requested categories
		if (preg_match("/^[a-zA-Z]/", $cat, $matches)){
			$cat_names = explode(',',trim($cat));
			foreach($cat_names as $cn){
				$new_cat[] = get_cat_id($cn);
			}
		}
			

		$cat=implode(',', $new_cat);


	//TAGS <- takes them only by name
		
		if ($tag!=""){
			
			if (preg_match("/^[a-zA-Z]/", $tag, $matches)){
		
				$tag_names = explode(',',trim($tag));

				foreach($tag_names as $tg){
					$term = get_term_by('name', $tg, 'post_tag');
					$new_tag[] = $term->term_id;	
				}
				
				$tag=implode(',', $new_tag);
			}
		}		
		

	//set limit of posts per page to 10
	if ($posts_per_page>10){
		$posts_per_page = 10;
	}

	// Try to get data from transient. if not, read db and cache results
	if (false === ( $output = unserialize(base64_decode(get_transient($transient_id) ) ) ) ){

		$ga_tracking_vars = '?&utm_source='.$referer.'&utm_medium=feed_widget&utm_campaign=cals_news_feed_widget';
			
		$q = 'cat='.$cat.'&tag='.$tag.'&posts_per_page=10&order=DESC';
		query_posts($q);
		//echo $q;
		
		while(have_posts()){ the_post();
			$output[] = array('ID' => get_the_ID(),
							  'title' => get_the_title(),
							  'permalink' => get_permalink().$ga_tracking_vars, 
							  'date' => get_the_time('F j, Y H:i')
							  );			
		}
		
		
		
		//setup transient to cache results
		set_transient($transient_id, base64_encode(serialize($output)), 60);
		
		}
		
		delete_transient($transient_id);

		//only return number of posts specified by $post_per_page
		if(is_array($output) && count($output)>0){
			$output = array_slice($output, 0, $posts_per_page);
		} else {
			//nothing to return
			
		}
		
	if ($jsonp == '')
	{
		header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
		echo json_encode($output);
	}
	else
	{
		header('Content-Type: application/javascript; charset=' . get_option('blog_charset'), true);
		echo $_GET['jsonp'] . '(' . json_encode($output) . ')';
	}
}

//actions and filters
add_action('do_feed_json', 'cals_json_feed');
?>