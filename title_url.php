<?php
/*
Plugin Name: Custom Post Title URL
Plugin URI: http://www.vq20.com
Description: This plugin allows you to define add a custom URL for your post titles, replacing their permalinks. Just add a custom field to your post/page named "title_url" and enter the new URL. Plugin based on <a href="http://www.istudioweb.com/hacking-wordpress-theme-external-url-for-post-title-2008-01-12/" target="_blank">Vlad Grubman's hack to create custom title URL's</a> for wordpress posts. 
Version: 0.1
Author: Vidal Quevedo
Author URI: http://www.vq20.com
*/

/*To do:
-Make "title_url" the default name for custom url (automatically add it to custom fiel key dropdown)
-Look ONLY for title_url as the custom metadata, to avoid confusion with any other custom urls


*/
/*
*		This function outputs post title and
*		links it either to posts's permalink 
*		(default WordPress behavior) or to
*		external link supplied in custom field
*		that should have any of the following names:
*		url1, title_url, url_title. The value of
*		the custom key should be the target URL.
*		Example: 'url1' = 'http://www.istudioweb.com/'
*
*		(C) 2008 by Vlad Grubman, www.istudioweb.com
*
*/

add_filter('the_permalink','print_post_title');


function print_post_title() { 
	global $post;
	$thePostID = $post->ID;
	$post_id = get_post($thePostID);
	$title = $post_id->post_title;
	//$perm  = $post_id->guid;
	$perm  = get_permalink($post_id);	
	
	$post_keys = array(); $post_val  = array();
	$post_keys = get_post_custom_keys($thePostID);

	if (!empty($post_keys)) {
  	foreach ($post_keys as $pkey) {
    	if ($pkey=='url1' || $pkey=='title_url' || $pkey=='url_title') {
      	$post_val = get_post_custom_values($pkey);
    	}
  	}
  	if (empty($post_val)) {
    	$link = $perm;
  	} else {
    	$link = $post_val[0];
  	}
	} else {
  	$link = $perm;
	}
	//echo '<h2><a href="'.$link.'" rel="bookmark" title="'.$title.'">'.$title.'</a></h2>';
	echo $link;
}








?>