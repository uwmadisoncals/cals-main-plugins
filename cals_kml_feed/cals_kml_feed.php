<?php
/*
Plugin Name: CALS KML feed
Description: Enables WP to deliver feeds in KML format to use with Google Maps.
Reference: http://thepremiumpress.com/free/2010/02/10/adding-georss-functionality-to-wordpress/
Version: 1.0
Author: College of Agricultural and Life Sciences (CALS) at University of Wisconsin-Madison
Author URI: http://www.cals.wisc.edu
Last Updated: 09/28/11
*/

/*TODO
- Better set/reset default number of posts value
*/

if(!class_exists('CALSKMLFeed')){
	class CALSKMLFeed {
	
		/* The constructor. 
		 * 
		 * Sets initial values and keeps track of actions and filters based on context.
		 *
		*/
		
		function CALSKMLFeed(){
		
			//Get initial values
				// Get plugin URL and absolute path
				$this->plugin_dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
				$this->plugin_images_dir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));'images/';
				
				//get params
				if(isset($_GET['icon_style'])){
					$this->icon_style = $_GET['icon_style'];
				}
				
				if(isset($_GET['force_more_link'])){
					$this->force_more_link = 1 ;
				}
				
			//Add actions and filters

			
			//update posts_per_rss wp option to deliver more than the default 12 items via RSS feed
			add_action('wp_init', array($this, 'cals_kml_feed_set_posts_per_rss'));	

			add_action('kml_style', array($this, 'cals_kml_style'));
									
			add_action('the_excerpt', array($this, 'cals_kml_add_geo_address'),10);
			
			add_action('the_excerpt', array($this, 'cals_kml_more_link'),10);
			
			//add extra code to customize cals_kml_feed_template.php template 
			add_action('kml_placemark', array($this, 'cals_kml_feed_point_coordinates'));
			
			//load KML template
			load_template($this->plugin_dir.'cals_kml_feed_template.php');
		
			//reset posts_per_rss wp option to its original value
			add_action('shutdown', array($this, 'cals_kml_feed_reset_posts_per_rss'));
	
		
		}
		
		
		
		function cals_kml_feed_point_coordinates(){
		
			global $post;
			
			$geo_lat = get_post_meta($post->ID, '_geo_lat', true);
			$geo_long = get_post_meta($post->ID, '_geo_long', true);
			
			if($geo_lat!='' && $geo_long!=''){
			echo '<Point>
        			<coordinates>'.$geo_long.','.$geo_lat.'</coordinates>
      			  </Point>
				  ';
			}
		}
		
		/**
		 * Add actions to kml_style hook 
		 *
		 * @reference http://code.google.com/apis/kml/documentation/kmlreference.html#style
		*/
		function cals_kml_style(){
			
			//check if any styling info has been submitted before hooking to kml_style
			$print_style = 0;
			$style_options = array('iconStyle'); //find other styling options at @reference
			
			foreach($style_options as $style_option){
				
				if(!empty($_GET[$style_option])){
					
					//assing property to $this
					$this->style[$style_option] = $_GET[$style_option];
					
					//print output
						//print <Style>
						if($print_style==0) { echo '	<Style id="defaultStyle">'; }
						
						//call matching styling function					
						call_user_func(array($this, 'cals_kml_'.$style_option));
					
						//print </Style> and Add <styleURL> tag to <Placemark>
						if($print_style==0) { 
							echo '</Style>'; $print_style=1;
							add_action('kml_placemark', array($this, 'cals_kml_placemark_styleUrl'));
						}
				}	
			
			}
			
		} //EOF cals_kml_style
		
		
		/**
		 * Prints iconStyle data
 		 *
		*/
		function cals_kml_iconStyle(){
			
				$icon_style = $this->style['iconStyle'];
				
				//find matching style
				$icon_styles = array('blue'  => array('x' => '0'   , 'y' => '32'),
								    'red'    => array('x' => '32' , 'y' => '32'),
								    'green'  => array('x' => '64' , 'y' => '32'),
								    'aqua'   => array('x' => '96', 'y' => '32'),
								    'yellow' => array('x' => '130', 'y' => '32'),
								    'purple' => array('x' => '162', 'y' => '32'),
								    'fucsia' => array('x' => '192', 'y' => '32')					 
								   );
				
			echo '
					<IconStyle>
						<Icon>
							<href>'.$this->plugin_images_dir.'images/iconm.png</href>';
							if(array_key_exists($icon_style, $icon_styles)){

							echo '
							<gx:x>'.$icon_styles[$icon_style]['x'].'</gx:x>
							<gx:y>'.$icon_styles[$icon_style]['y'].'</gx:y>
							';
							
							}
			echo  			'
							<gx:w>32</gx:w>
							<gx:h>32</gx:h>
						</Icon>
					</IconStyle>
			';
		} //EOF cals_kml_iconStyle
				
		
		/**
		 * Add post thumbnail to <description> if it exists and is supported by theme
		 *
		 * @uses $post
		*/
		function cals_kml_thumbnail($excerpt){
			
			global $post;
			
			if(current_theme_supports('post-thumbnails') && $this->force_more_link ==1){
				if(has_post_thumbnail()){
					$excerpt = the_post_thumbnail(array(75,75), array('style' => 'float: left')).$excerpt;
				} 
			}

			return $excerpt;
		
		}
		
		function cals_kml_more_link($excerpt){
			
			global $post;
			
			if($post->post_content!=''){
				$excerpt = str_replace('</p>', ' <a href="'. get_permalink($post->ID) . '">Read more...</a></p>', $excerpt);
			}
			
			return $excerpt;
		}
		
		function cals_kml_add_geo_address($excerpt){
			
			global $post;
			$geo_address = get_post_meta($post->ID, '_geo_address', true);
			if($geo_address!=''){
				$excerpt = str_replace('<p>', '<p>'.strtoupper($geo_address).' - ', $excerpt);
			}
			
			return $excerpt;
		
		}
		
		/**
		 * Add styling tags to placemark.
		 *
		*/		
		function cals_kml_placemark_styleUrl(){
			echo '<styleUrl>#defaultStyle</styleUrl>';
		}
		
		/**
		 * Temporarily sets posts_per_rss option to 100
		 *
		*/
		function cals_kml_feed_set_posts_per_rss(){
			update_option('posts_per_rss', 100);
		}
	
		/**
		 * Sets posts_per_rss option back to 12
		 *
		*/
		function cals_kml_feed_reset_posts_per_rss(){
			update_option('posts_per_rss', 12);
		}
	
	} //END OF CALSKMLFeed class
	
} 


//run everything

function cals_kml_feed(){

	if(class_exists('CALSKMLFeed')){
		$cals_kml_feed = new CALSKMLFeed();
	}

}
add_action('do_feed_kml', 'cals_kml_feed');?>