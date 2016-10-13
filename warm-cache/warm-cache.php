<?php
/*
Plugin Name: Warm cache
Plugin URI: http://www.mijnpress.nl
Description: Crawls your website-pages based on any XML sitemap plugin. If you have a caching plugin this wil keep your cache warm. Speeds up your site.
Version: 2.0.1
Author: Ramon Fincken
Author URI: http://www.mijnpress.nl
*/
if (!defined('ABSPATH')) 
{
	if(!isset($_GET['warm_cache']))
	{
		die("Aren't you supposed to come here via WP-Admin?");
	}
}

if(!class_exists('mijnpress_plugin_framework'))
{
	include('mijnpress_plugin_framework.php');
}

add_action( 'init', 'mp_warmcache_create_post_type' );
function mp_warmcache_create_post_type() {
	$labels = array(
        'name' => 'warmcache',
        'singular_name' => 'warmcache',
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'warmcache' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'warmcache', $args );
}

class warm_cache extends mijnpress_plugin_framework
{
	public $google_sitemap_generator_options;
	public $sitemap_url;
	public $keep_time;

	public static function admin_notices()
	{
		$warm_cache_admin	= new warm_cache();		
		$sitemap_url		= $warm_cache_admin->get_sitemap_url();

		if(!$sitemap_url) {
			// No override post?
			if(!isset($_POST['update_sitemap']) || !$_POST['update_sitemap']) {
				echo '<div class="error"><p>A notice from plugin Warm-cache: Your configured sitemap url is not configured, I cannot crawl your pages. <a href="'.admin_url('plugins.php?page=warm-cache%2Fwarm-cache.php').'">Update your sitemap url now</a>.</p></div>';
			}
		} else {
			// Check sitemap validity
			$key = 'warm-cache-sitemapcheck';

			if (false === ( $sitemapSyntaxOK = get_transient( $key ) ) ) {			     
				$sitemapSyntaxOK = true;
				$response = wp_remote_get($sitemap_url);
				if(! is_array($response)) {
					$errormsg = print_r($response, true);
					$sitemapSyntaxOK = false;
				} else {
					$xmldata = wp_remote_retrieve_body($response);
					if(substr_count($xmldata, '<?xml') == 0) {
						$sitemapSyntaxOK = false;
						$errormsg = 'No xml opening tag';
					} else {
						if(substr_count($xmldata, '<urlset') == 0 && substr_count($xmldata, '<sitemap') == 0) {
							$sitemapSyntaxOK = false;
							$errormsg = 'Urlset or sitemap tag';
						}
					}	
				}
				if($sitemapSyntaxOK) {
					// If it's OK, we will re-check in 12 hours
					set_transient( $key, $sitemapSyntaxOK, 12 * HOUR_IN_SECONDS );
				} else {
					// If it's NOT OK, we will re-check in 2 minutes (sooner could induce more server load)
					set_transient( $key, $sitemapSyntaxOK, 2 * MINUTE_IN_SECONDS );
				}
			}

			if(!$sitemapSyntaxOK) {
				echo '<div class="error"><p>A notice from plugin Warm-cache: Your configured sitemap url ( <a href="'.$sitemap_url.'">'.$sitemap_url.'</a> ) is configured, but does not appear to contain an xml opening tag, or a combination of urlset or sitemap, I cannot crawl your pages. Please check your sitemap plugin to fix your currupt sitemap.<br/>Note: this check will be cached for 2 minutes. So if you fix the problem, this notice might still be present for 2 minutes.</p>
<p>Error detail: '.$errormsg. '</p></div>';
			}
		}
	}	

	public static function init()
	{
		$labels = array(
			'name'               => _x( 'Books', 'post type general name', 'plugin_warm_cache' ),
			'singular_name'      => _x( 'Book', 'post type singular name', 'plugin_warm_cache' ),
			'menu_name'          => _x( 'Books', 'admin menu', 'plugin_warm_cache' ),
			'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'plugin_warm_cache' ),
			'add_new'            => _x( 'Add New', 'book', 'plugin_warm_cache' ),
			'add_new_item'       => __( 'Add New Book', 'plugin_warm_cache' ),
			'new_item'           => __( 'New Book', 'plugin_warm_cache' ),
			'edit_item'          => __( 'Edit Book', 'plugin_warm_cache' ),
			'view_item'          => __( 'View Book', 'plugin_warm_cache' ),
			'all_items'          => __( 'All Books', 'plugin_warm_cache' ),
			'search_items'       => __( 'Search Books', 'plugin_warm_cache' ),
			'parent_item_colon'  => __( 'Parent Books:', 'plugin_warm_cache' ),
			'not_found'          => __( 'No books found.', 'plugin_warm_cache' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'plugin_warm_cache' )
		);

		$args = array(
			//'labels'             => $labels,
		        'description'        => __( 'Description.', 'plugin_warm_cache' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false, // hides: edit.php?post_type=warmcache
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'warmcache' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'custom-fields' )
		);

		register_post_type( 'warmcache', $args );		
	}

	function warm_cache()
	{
		$this->keep_time = 60*60*24*7; // 7 days for now (TODO: admin setting)
		$this->flush_loadbalancer = get_option("plugin_warm_cache_lb_flush");
	}

	function addPluginSubMenu($title = '',$function = '', $file = '', $capability = 10, $where = '')
	{
		parent::addPluginSubMenu('Warm cache', array('warm_cache', 'admin_menu'), __FILE__);
	}

	/**
	 * Additional links on the plugin page
	 */
	function addPluginContent($links, $file, $void1 = '', $void2 = '') {
		$links = parent::addPluginContent('warm_cache/warm-cache.php', $links, $file);
		return $links;
	}

	public function admin_menu()
	{
		load_plugin_textdomain('plugin_warm_cache','/wp-content/plugins/warm-cache/language/');		
		$warm_cache_admin = new warm_cache();
		$warm_cache_admin->plugin_title = 'Warm cache';
		if(!$warm_cache_admin->configuration_check())
		{

		}
		else
		{
			$warm_cache_admin->content_start();
			$stats = $warm_cache_admin->get_stats();

			if(!$stats['crawl'])
			{
				$msg = 'Ok, we have detected your sitemap url but it has not been visited by the plugin\'s crawler.<br/>';
				$warm_cache_api_url = trailingslashit(get_bloginfo('url')).'?warm_cache='.get_option('plugin_warm_cache_api');
				$msg .= 'The url you should call from a cronjob is: '.$warm_cache_api_url.'<br/>';
				$msg .= 'To re-set the key, visit this url: '.admin_url('plugins.php?page=warm-cache/warm-cache.php&resetkey=true').'<br/>';
				$msg .= 'If you are in need of an external cronjob service, you might like to use Easycron.com (affiliate link) <a href="http://www.easycron.com/?ref=12201">http://www.easycron.com/?ref=12201</a> or <a href="https://webshop.mijnpress.nl/shop/cronjob-for-warm-cache">buy our cronjob service</a>';
				$msg .= '<br/><a href="https://www.managedwphosting.nl/contact/offerte-op-maat/">I offer paid WordPress speed-optimisation consultancy and Varnish & memcached WordPress webhosting & servers.</a>';
				$warm_cache_admin->show_message($msg);
				echo '<br/><br/>';
			}
			else
			{
				$msg = 'Crawled in total '.$stats['stats_pages'].' pages in a total of '.$stats['stats_times']. ' seconds (based on the last 75 crawls)<br/>';
				if($stats['stats_pages'])
				{
					$msg .= 'Average page to load a page in seconds: '. $stats['stats_times']/$stats['stats_pages'];
				}
				$msg .= '<br/><a href="https://www.managedwphosting.nl/contact/offerte-op-maat/">I offer paid WordPress speed-optimisation consultancy and Varnish & memcached WordPress webhosting & servers.</a>';				
				$warm_cache_admin->show_message($msg);
			}
			echo '<table class="widefat">';
			echo '<tr><th class="manage-column" style="width: 150px;">Crawled at</th><th class="manage-column">Time needed</th><th class="manage-column" style="width: 120px;">Number of pages</th><th class="manage-column">Average load time per page</th><th class="manage-column">Pages</th></tr>';
			echo $stats['table_string'];
			echo '</table>';
			$warm_cache_admin->content_end();
		}
	}
	
	/**
	 * Add or update the API key
	 */
	private function change_apikey()
	{
		$special_chars = false;
		delete_option('plugin_warm_cache_api');
		add_option('plugin_warm_cache_api', wp_generate_password(9, $special_chars));		
	}

	/**
	* Gets table and stats
	*/
	private function get_stats()
	{
		$myposts = get_posts('post_type=warmcache&numberposts=75&order=DESC&orderby=post_date');
		
		$statdata = get_option('plugin_warm_cache_start', false);
		if($statdata === false && !get_option('plugin_warm_cache_api'))
		{
			$this->change_apikey();
		}

		$table_string = '';
		if(!count($myposts))
		{
			$table_string .= '<tr><td valign="top" colspan="5">';
			$table_string .= __('Your site has not been crawled by the plugin','plugin_warm_cache');
			$table_string .= '</td></tr>';
			return array('crawl' => false, 'table_string' => $table_string);
		}
		$stats_pages = 0;
		$stats_times = 0;
		
		foreach($myposts as $post) 
		{

			$mytime = get_post_meta($post->ID, 'mytime', true);
			$mypages = get_post_meta($post->ID, 'mypages', true);
			
			$stats_pages += $mypages; 
			$stats_times += $mytime;
			
			$table_string .= '<tr><td valign="top">';
			// Crawled at
			$table_string .= $post->post_title.'</td>';
			// Time needed
			$table_string .= '<td>'.$mytime.'</td>';
			// Number of pages
			$table_string .= '<td>'.$mypages.'</td>';
			// Average load time per page
			$table_string .= '<td>'.($mytime/$mypages).'</td>';
			// Pages
			$table_string .= '<td><a href="'.admin_url('post.php?post='.$post->ID.'&action=edit').'">View</a></td>';
			$table_string .= '</td></tr>';
		}

		return array('crawl' => true, 'stats_pages' => $stats_pages, 'stats_times' => $stats_times, 'table_string' => $table_string);
	}

	/**
	 * Updates sitemap url override
	 * @param unknown_type $url
	 */
	private function update_sitemap_overide_url($url)
	{
		delete_option('plugin_warm_cache_sitemap_override');
		add_option('plugin_warm_cache_sitemap_override',htmlspecialchars($url));			
	}
	
	/**
	 * Updates flush yes/no
	 * @param	string	$flush

	 */
	private function update_flush($flush)
	{
		delete_option('plugin_warm_cache_lb_flush');
		if($flush != 'yes') { $flush = 'no'; } // Sanity check
		add_option('plugin_warm_cache_lb_flush', htmlspecialchars($flush));
		// Update local
		$this->flush_loadbalancer = get_option("plugin_warm_cache_lb_flush");			
	}	
	
	private function configuration_check()
	{
		$this->google_sitemap_generator_options = get_option("sm_options");
		$msg = '';
		if(isset($_GET['resetkey']))
		{
			$this->change_apikey();
			$msg .= __('API key has changed, please update your cronjobs right now!','plugin_warm_cache');
			$this->show_message('<strong>'.$msg.'</strong>');
			$msg = ''; // Reset msg
		}
		
		if(isset($_POST['update_sitemap']) && $_POST['update_sitemap'])
		{
			$this->update_sitemap_overide_url($_POST['update_sitemap']);
		}
		if(isset($_POST['flush']) && $_POST['flush'])
		{
			$this->update_flush($_POST['flush']);
		}		
		// Init config
		$this->get_sitemap_url(); // FIXME: Remove?

		$msg .= '<form method="post" action="'.admin_url('plugins.php?page=warm-cache/warm-cache.php'). '">Please enter your full sitemap url if we cannot detect it automatically (do not forget the http:// up front): ';
		$msg .= '<br/><input type="text" value="'.get_option('plugin_warm_cache_sitemap_override').'" name="update_sitemap" size="60" /><input type="submit" value="Use this sitemap" /></form>';
		
		$msg .= '<form method="post" action="'.admin_url('plugins.php?page=warm-cache/warm-cache.php'). '">If you have a loadbalancer you might need to set flush to Yes to prevent timeouts.';
		$msg .= '<br/>Toggle setting if you have a crawled 0 pages when calling the cronjob url.<br/> ';
		$msg .= '<input '.(($this->flush_loadbalancer != 'yes') ? 'checked="checked"' : '') . ' id="plugin_wc_flush_no" name="flush" type="radio" value="no"><label for="plugin_wc_flush_no">No, do not flush</label> ';
		$msg .= '<input '.(($this->flush_loadbalancer == 'yes') ? 'checked="checked"' : '') . ' id="plugin_wc_flush_yes" name="flush" type="radio" value="yes"><label for="plugin_wc_flush_yes">Yes, flush</label> ';
		$msg .= '<input type="submit" value="Update flush settings" /></form></br>';		

		if(!($this->google_sitemap_generator_options && is_array($this->google_sitemap_generator_options)) && !$this->sitemap_url) {
			$msg .= __('Could not find sitemap options, please enter your sitemap url','plugin_warm_cache');
			$returnvar = false;
		}
		else
		{
			$msg .= 'Sitemap url: <a target="_blank" href="'.$this->sitemap_url.'">'.$this->sitemap_url.'</a><br/>';
			$warm_cache_api_url = trailingslashit(get_bloginfo('url')).'?warm_cache='.get_option('plugin_warm_cache_api');
			$msg .= 'The url you should call from a cronjob is: '.$warm_cache_api_url.'<br/>';
			$msg .= 'To re-set the key, visit this url: '.admin_url('plugins.php?page=warm-cache/warm-cache.php&resetkey=true').'<br/>';
			$msg .= 'If you are in need of an external cronjob service, you might like to use Easycron.com (affiliate link) <a href="http://www.easycron.com/?ref=12201">http://www.easycron.com/?ref=12201</a> or <a href="https://webshop.mijnpress.nl/shop/cronjob-for-warm-cache">buy our cronjob service</a>';
			
			$returnvar = true;
		}
		$this->show_message('<strong>'.$msg.'</strong>');

		return $returnvar;
	}

	/**
	* Retrieves public front-end sitemap URL
	* @return	string|false
	*/
	public function get_sitemap_url()
	{
		// Guess sitemap url from Google XML sitemap generator
		if($this->google_sitemap_generator_options["sm_b_location_mode"]=="manual") {
			$sitemap_url = $this->google_sitemap_generator_options["sm_b_fileurl_manual"];
		} elseif($this->google_sitemap_generator_options["sm_b_filename"] != '') {
			$sitemap_url =  trailingslashit(get_bloginfo('url')). $this->google_sitemap_generator_options["sm_b_filename"];
		}
		
		$override = get_option('plugin_warm_cache_sitemap_override');
		if($override && !empty($override) && $override != 'http://')
		{
			$sitemap_url = $override;
		}
		// Final check
		if(isset($sitemap_url) && $sitemap_url && !empty($sitemap_url) && $sitemap_url != 'http://' && $sitemap_url != trailingslashit(get_bloginfo('url')))
		{
			$this->sitemap_url = $sitemap_url;
			return $this->sitemap_url;
		}
		return false;
	}
}

add_action('init', array('warm_cache', 'init'));

if(isset($_GET['warm_cache']) && !empty($_GET['warm_cache']) && $_GET['warm_cache'] == get_option('plugin_warm_cache_api'))
{
	define('PLUGIN_WARM_CACHE_CALLED', true);
	include('warm_cache_crawl.php');
}
else
{
	if(is_admin())
	{
		add_action('admin_menu', array('warm_cache', 'addPluginSubMenu'));
		add_filter('plugin_row_meta', array('warm_cache', 'addPluginContent'), 10, 2);
		add_action('admin_notices', array('warm_cache', 'admin_notices'));
	}
}
?>
