<?php
/**
* Part of WordPress Plugin: Warm cache
* Based on script from : http://blogs.tech-recipes.com/johnny/2006/09/17/handling-the-digg-effect-with-wordpress-caching/
*/
if(defined('PLUGIN_WARM_CACHE_CALLED'))
{	

	// Prevent any reverse proxy caching / browser caching
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$pid_lock = 'mp_warm_cache_pid_lock';
	$is_locked = get_transient( $pid_lock );

	if($is_locked) {
		echo 'Lock active, stopped processing. Wait 60 seconds';
		//die();
	}
	

	function mp_warm_cache_limit_filter( $limit ) {
		if(defined('MP_WARM_CACHE_FILTER_LIMIT')) {
			$newlimit = intval(MP_WARM_CACHE_FILTER_LIMIT);
			if($newlimit > 0) {
				return $newlimit;
			}
		}	    
		return $limit;
	}
	add_filter( 'mp_warm_cache_limit_filters', 'mp_warm_cache_limit_filter', 10, 1 );

	$warm_cache = new warm_cache();
	$warm_cache->google_sitemap_generator_options = get_option("sm_options");
	
	$useflush = get_option("plugin_warm_cache_lb_flush");

	$limit = apply_filters('mp_warm_cache_limit_filters', get_option('plugin_warm_cache_limit', 20));
	$start = get_option('plugin_warm_cache_start', 0);

	echo "Start at item $start $limit";
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;
	
	@set_time_limit(0);
	
	ob_start();

	if (file_exists('./wp-load.php')) 
	{
		require_once ('./wp-load.php');
	}

	// Get url
	$sitemap_url = $warm_cache->get_sitemap_url();
	// Override here if needed

	// For stats
	$statdata = get_option('plugin_warm_cache_statdata');
	if(!isset($statdata) || !is_array($statdata))
	{
		add_option('plugin_warm_cache_statdata', array(), NULL, 'no');
	}

	
	$newvalue = array();
	$totalcount = 0;
	$hits = 0;
	$newvalue['url'] = $sitemap_url;
	$newvalue['time_start'] = $newtime;
	$newvalue['pages'] = array();
	
	function mp_process_sitemap($sitemap_url)
	{
		global $newvalue, $totalcount, $limit, $start, $hits, $useflush, $pid_lock;

		if(substr_count($sitemap_url, 'warmcache-sitemap.xml') > 0 || substr_count($sitemap_url, 'warmcache') > 0) {
			// No need to crawl our own post type .. bail
			return;
		}
		$xmldata = wp_remote_retrieve_body(wp_remote_get($sitemap_url));

		$xml = simplexml_load_string($xmldata);

		$cnt = count($xml->url);
		if($cnt > 0)
		{
			for($i = -1; $i < $cnt; $i++){
				if($hits >= $limit) {
					return;
				}
				
				if($totalcount <= ($start+$limit) && $totalcount > $start) {
					$hits++;
					$page = (string)$xml->url[$i]->loc;
					echo '<br/>Busy with: '.$page;

					set_transient($pid_lock, 'Busy', MINUTE_IN_SECONDS );

					$newvalue['pages'][] = $page;
					$tmp = wp_remote_get($page);
					// 	https://wordpress.org/support/topic/needs-flush-to-write-buffers-to-prevent-timeouts
					if($useflush == 'yes' && function_exists('flush'))
					{
						flush(); // prevent timeout from the loadbalancer
					}
				}
				$totalcount++;
			}
		}
		else
		{
			// Sub sitemap?
			$cnt = count($xml->sitemap);
			if($cnt > 0)
			{
				for($i = 0;$i < $cnt; $i++){
					$sub_sitemap_url = (string)$xml->sitemap[$i]->loc;
					echo "<br/>Start with submap: ".$sub_sitemap_url;
					mp_process_sitemap($sub_sitemap_url);
				}				
			}
		}
	}
	// GOGOGO!
	mp_process_sitemap($sitemap_url);
	
	// Give it some time to post-process
	set_transient($pid_lock, 'Busy', 10 );

	// Increase counter
	$newstart = $start+$limit;
	if($hits == 0) {
		// None found, we crossed the border, reset to zero
		echo 'no hits, resetting the start to zero for next time';
		$newstart = 0;
	}
	echo "<br/>Updating to start (next time) at : $newstart";
	update_option('plugin_warm_cache_start', $newstart);
	
	if( !defined('MP_WARM_CACHE_NO_LOGGING_AT_ALL') ) {

		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		$cnt = count($newvalue['pages']);
		$returnstring = '<br/><br/>Crawled '.$cnt. ' pages in ' .$totaltime. ' seconds.';

		$post = array ();
		$post['post_title'] = date('l jS F Y h:i:s A', $starttime);
		$post['post_type'] = 'warmcache';
		$post['post_content'] = $newvalue['url']."\n<br/>".$returnstring."\n<br/>".implode("\n<br/>", $newvalue['pages']);
		$post['post_status'] = 'publish';
		$post['post_author'] = 0; // FIXME? author
		// GOGOGO
		try {
			if(!function_exists('is_user_logged_in')) {
				require_once( ABSPATH . "wp-includes/pluggable.php" );
			}
			$this_page_id = wp_insert_post($post);
			if($this_page_id) {
				add_post_meta($this_page_id, 'mytime', $totaltime);
				add_post_meta($this_page_id, 'mypages', $cnt);
				add_post_meta($this_page_id, 'totalpages', $totalcount);
			}	
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	
		// Cleanup, delete old data
		$period_php = '180 minutes';
		$myposts = get_posts('post_type=warmcache&numberposts=100&order=ASC&orderby=post_date');
	
		$now = strtotime("now");
		foreach ($myposts AS $post) {
			$post_date_plus_visibleperiod = strtotime($post->post_date . " +" . $period_php);
			if ($post_date_plus_visibleperiod < $now) {
				wp_delete_post($post->ID, false);
			}						
		}
	}
	
	echo $returnstring;
	echo '<br><br><strong>Done!</strong>';
	
	if($useflush == 'yes' && function_exists('flush'))
	{
		flush(); // prevent timeout from the loadbalancer
	}
	
	
	die();
}
?>
