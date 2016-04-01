<?php 
/*
Plugin Name: UW CALS Google Analytics Most Viewed
Plugin URI: ???
Description: Shows a list of most viewed posts based on Google Analytics data.
Author: College of Agricultural and Life Sciences (CALS) at University of Wisconsin-Madison
Author URI: http://www.cals.wisc.edu
Last Updated: 09/07/11
Version: 1.0
*/

/*todo:

- 2.0
 - Add support for %post_id% permalink tag
 - Add pwd validation on admin page
 - Add options to select most viewed time range (week, months)
 - add option to exclude terms (including home page)
 - Enable widget controls?
*/

if(!class_exists('UWCALSGAMostViewed')){
	
	class UWCALSGAMostViewed {
		
		/**
		 * The constructor
		 * 
		 * Registers plugin on activation, and sets all of its options.
		 * Runs all required Actions and Filters depending on context (admin or front end)
		*/
		function UWCALSGAMostViewed(){

			//set up default options on activation
			register_activation_hook( __FILE__, array('UWCALSGAMostViewed', 'install') );

			//remove default options on deactivation
			register_deactivation_hook( __FILE__, array('UWCALSGAMostViewed', 'uninstall') );
			
			//Get option 
				//plugin options
				$this->usr = get_option('uwcals_gamv_usr');
				$this->pwd = get_option('uwcals_gamv_pwd');
				$this->profile_id = get_option('uwcals_gamv_profile_id');
				$this->title = get_option('uwcals_gamv_title');
				$this->limit = get_option('uwcals_gamv_limit');	
				$this->excerpt = get_option('uwcals_gamv_excerpt');
				//other options
				$this->permalink_structure = get_option('permalink_structure');


			
			if ( is_admin() ){
				//run admin stuff
				add_action('admin_menu', array($this,'uwcals_gamv_create_admin_menu'));
			
			} else {
				//run front end stuff
			}
			
			//register plugin's widget
			add_action('widgets_init', array($this, 'uwcals_gamv_register_widget'));
		
		} //EOF UWCALSGAMostViewed
		
		
		/**
		 * Run on activation to set up default option values for plugin
		 *
		 * @reference http://codex.wordpress.org/Function_Reference/register_activation_hook
		*/
		static function install(){
			
			//add default options
			add_option('uwcals_gamv_title', 'Most Viewed');
			add_option('uwcals_gamv_limit', 5);
			add_option('uwcals_gamv_excerpt', 1);
		
		} //OEF install
		
		/**
		 * Run on deactivation to remove option values for plugin
		 *
		*/
		static function uninstall(){
			
			//delete default options
			delete_option('uwcals_gamv_usr' );
			delete_option('uwcals_gamv_pwd' );
			delete_option('uwcals_gamv_profile_id' );
			delete_option('uwcals_gamv_title' );
			delete_option('uwcals_gamv_limit');		
			delete_option('uwcals_gamv_excerpt');		

		} //OEF uninstall




		/**
		 * Add plugin's admin menu
		 *
		 * @reference http://codex.wordpress.org/Creating_Options_Pages#Register_Settings
		*/
		function uwcals_gamv_create_admin_menu(){

			//call register settings function
			add_action( 'admin_init', array($this, 'uwcals_gamv_register_settings'));		
			
			//create new top-level menu
			add_options_page('UW CALS Google Analytics Most Viewed', 'Google Analytics Most Viewed', 'manage_options', __FILE__, array($this, 'uwcals_gamv_settings_page'));
	
		}


		/**
		 * Register plugin's settings
		 *
		 * @reference http://codex.wordpress.org/Creating_Options_Pages#Register_Settings
		 * @reference http://codex.wordpress.org/Function_Reference/wp_hash_pwd
		*/
		function uwcals_gamv_register_settings(){
		
		
			//update $pwd
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_pwd', array($this, 'uwcals_gamv_process_pwd'));
			
			
			//register plugin settings
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_usr' );
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_profile_id' );

			//register plugin's widget settings
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_title' );
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_limit');		
			register_setting( 'uwcals_gamv-settings-group', 'uwcals_gamv_excerpt');	
			
		}
		
		
		/**
		 * Add content to plugin's admin page
		 *
		 * @reference http://codex.wordpress.org/Creating_Options_Pages#Register_Settings
		*/
		function uwcals_gamv_settings_page(){ ?>
            <div class="wrap">
                <h2>UW CALS Google Analytics Most Viewed</h2>
                <form method="post" action="options.php">
                    <?php settings_fields( 'uwcals_gamv-settings-group' ); ?>
                    <?php //do_settings( __FILE__ ); ?>
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Google Analytics Username:</th>
                        <td><input type="text" name="uwcals_gamv_usr" value="<?php echo $this->usr; ?>" /> <span class="description">(e.g. me@gmail.com)</span></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row">Password:</th>
                        <td><input type="password" name="uwcals_gamv_pwd" value="<?php echo $this->pwd;?>"  />
                        </td>
                        </tr>                        
                        <tr valign="top">
                        <th scope="row">Profile ID:</th>
                        <td><input type="text" name="uwcals_gamv_profile_id" value="<?php echo $this->profile_id; ?>"/> <span class="description">(e.g. 11111111)</span></td>
                        </tr>
                    </table>
                    <br/><br/>
                    <h3>Format Results</h3>
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Results limit:</th>
                        <td><input type="text" name="uwcals_gamv_limit" maxlength="2" size="2" value="<?php echo $this->limit; ?>" /></td>
                        </tr>
                         
                        <tr valign="top">
                        <th scope="row">Display post excerpt on results:</th>
                        <td><input type="checkbox" name="uwcals_gamv_excerpt" value="1" <?php if($this->excerpt==1){ echo 'checked="checked"'; } ?>  />
                        </td>
                        </tr>
                    </table>                    
                    <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                    </p>
                </form>
            </div>
<?php 	}


		/**
		 * Register plugin's widget
		 *
		*/ 	
		function uwcals_gamv_register_widget(){
			
			//register widget
			wp_register_sidebar_widget('uwcals_gamv_widget', 'UW CALS Most Viewed Posts (Google Analytics)', array($this, 'uwcals_gamv_widget'),	array('description' => 'Displays a list of most viewed posts, based on Google Analytics data'));
		
			//register widget controls
			//wp_register_widget_control('uwcals_gamv_widget', 'UW CALS Most Viewed Posts (Google Analytics)', array($this, 'uwcals_gamv_widget_control'));
			
		}
		
		
		/**
		 * Display widget content
		 *
		 *
		*/ 	
		function uwcals_gamv_widget(){ 

								
				echo '<li id="ga_most_viewed" class="widget-container widget_ga_most_viewed">';
				
					echo '<h3 class="widget-title">'.$this->title.'</h3>';
					
					echo '<ul>';
					
					//get most viewed posts
					$this->uwcals_gamv_get_most_viewed_posts();				   
					echo '</ul>';
				
				echo '</li>';
				
		   } //EOF uwcals_gamv_widget

		
		/**
		 * Display widget controls
		 *
		 *
		*/		
		function uwcals_gamv_widget_control(){
						
				/*$option_fields = array('title', 'usr','pwd', 'profile_id','number');
				
				foreach ($option_fields as $field){
					
					$options['ga_most_viewed-'.$field] = $newoptions['ga_most_viewed-'.$field] = get_option('widget_uwcals_gamv-'.$field);
				
					if ( $_POST["ga_most_viewed-submit"] ) {
						$newoptions['ga_most_viewed-'.$field] = strip_tags(stripslashes($_POST['ga_most_viewed-'.$field]));
					}
					
					if ( $options['ga_most_viewed-'.$field] != $newoptions['ga_most_viewed-'.$field] ) {
						$options['ga_most_viewed-'.$field] = $newoptions['ga_most_viewed-'.$field];
						update_option('widget_uwcals_gamv-'.$field, $options['ga_most_viewed-'.$field]);
					}
					
				}*/
		/*?>
            
            <p>
                <label for="uwcals_gamv_widget_title"><?php _e('Title:'); ?><br /> <input id="uwcals_gamv_widget_title" name="uwcals_gamv_widget_title" type="text" value="<?php echo get_option('uwcals_gamv_widget_title');?>" /></label>
            </p>
            <p>
                <label for="uwcals_gamv_widget_limit"><?php _e('Number of posts to show:'); ?> <br /><input id="uwcals_gamv_widget_limit" name="uwcals_gamv_widget_limit" type="text" value="
				<?php 
				$limit = get_option('uwcals_gamv_widget_limit')!='' && get_option('uwcals_gamv_widget_limit') != 0 ? get_option('uwcals_gamv_widget_limit') : 5; 
				echo $limit;?>" size="3" /></label>
            </p>
            <input type="hidden" id="uwcals_gamv_widget_submit" name="uwcals_gamv_widget_submit" value="1" />
			<?php	
			
		*/} // EOF uwcals_gamv_widget_control

		
		/**
		 * Retrieves list of most viewed posts 
		 *
		*/	
		function uwcals_gamv_get_most_viewed_posts($echo = true, $excerpt = true, $before = '', $after = ''){
			
			//Check first: if any of these parameters are missing, don't display anything
			if($this->usr == '' || $this->pwd == '' || $this->profile_id ==''){
				return false;
			}
		
			//Also, check permalink structure to see if either %postname% or %post_id% are include, as they are the minimum required identifiers to match ga info with wp posts

			if(strstr($this->permalink_structure, '%post_id%')){
				$permalink_tag = 'post_id';
			} else if(strstr($this->permalink_structure, '%postname%')) {
				$permalink_tag = 'postname';
			} else {
				//stop the show, we can't go any further
				return false;
			}

			//Ok, now get data from Google Analytics
			$data = $this->uwcals_gamv_get_ga_data();
			//process the data
			if(count($data)>0){
				
				//sort data to go from most viewed to least viewed		
				arsort($data);

				//clean up data byt taking out undesirable patterns (i.e. home, pages and spec cats)
				
					//get $data keys into array to be matched
					$data_keys = array_keys($data);
					
					//unset items in $data whose keys match the undesired patterns
						
						//get rid of home page entry ('/')
						unset($data['/']);
						
						//get rid of other patterns
						
						$patterns = array('404', '\/category\/');
						foreach($data_keys as $data_key){
							//unset pages, up to page 10
							for($i=1; $i<=10; $i++){
								unset($data[$data_key.'/'.$i]);
							}
							
							foreach($patterns as $pattern){
								if (preg_match('/'.$pattern.'/', $data_key)>0){
									unset($data[$data_key]);
								}
							}
						}
					

				//based on $permalink_tag, process data by post_id or by postname
				$most_viewed = $this->uwcals_gamv_get_posts($data, $permalink_tag);
					
				if (count($most_viewed)>0){
					
					//reduce array to number of entries specified by $limit (default 5)
					$most_viewed = array_slice($most_viewed, 0, $this->limit);
						
					if ($echo==true){
						foreach($most_viewed as $mv){
							if($this->excerpt == 1){
								$excerpt_text = $mv->post_excerpt;
							}
							$output.= $before.'<li><a title="Permanent Link to '.$mv->post_title.'" href="'.get_permalink($mv->ID).'" rel="bookmark">'.$mv->post_title.'<br/><span class="uwcals_gamv_excerpt">'.$excerpt_text.'</span></a></li>'.$after;
						}
						
						echo $output;
						
					} else {
						return $most_viewed;
					}
				} else {
					echo "<li>No data available yet. Please check back soon.</li>";
				}
			
			}
		
		} //EOF get_uwcals_gamv*/
		
		
		/**
		 * Get data from Google Analytics
		 *
		 * @uses Analytics Class
		 * @reference http://www.swis.nl/ga/		
		*/	
		function uwcals_gamv_get_ga_data(){

			//get the class
			require_once('analytics.class.php');
			
			//sign in and grab profile
			$analytics = new analytics($this->usr, $this->uwcals_gamv_d5t($this->pwd));
			$analytics->setProfileById('ga:'.$this->profile_id);
						
			//set it up to use caching (default (10 min))
			$analytics->useCache();
			
			//set the date range for which I want stats for (could also be $analytics->setDateRange('YYYY-MM-DD', 'YYYY-MM-DD'))
			$date_ranges = array('week' => array(date('Y-m-d',time() - 86400*6), date('Y-m-d',time())));
			
			//go for it
			try{
				
				$analytics->setDateRange(date('Y-m-d',time() - 86400*6), date('Y-m-d',time()));	
				
				//get paths/views in current date range 
				$data = $analytics->getData(array('dimensions' => 'ga:pagePath',
												'metrics'    => 'ga:visits',
												'sort'       => 'ga:visits'));			
			} 
			
			catch (Exception $e) { 
				echo 'Caught exception: ' . $e->getMessage(); 
			}
			
			return $data;

		} //EOF 
		
		
		/**
		 * GEt posts by name or id
		 *
		 *
		*/
		function uwcals_gamv_get_posts($data, $permalink_tag){
			
			global $wpdb;
			
			
			//get posts by post name
			if($permalink_tag == 'postname'){
			
				//get post_paths and generate array with post_names 
				$post_names = array();
				$post_paths = array_keys($data);
								
													
				//find position of post_name in permalink structure.
				$permalink_tag_position = $this->uwcals_gamv_get_permalink_tag_position($permalink_tag);
					
				//get postname
				foreach($post_paths as $post_path){
					$pp = explode('/', $post_path);
					//echo $post_path.' - ';
					//echo count($pp).'<br>';
					$post_names[]= $pp[$permalink_tag_position];
				}
								
				foreach($post_names as $post_name){
					$the_slug = $post_name;
					$args=array(
					  'name' => $the_slug,
					  'post_type' => 'post',
					  'post_status' => 'publish',
					  'showposts' => 1,
					  'caller_get_posts'=> 1
					);
					
					$my_posts = get_posts($args);
					if( $my_posts ) {
						$most_viewed[] = $my_posts[0];
					}
				}
				
				return $most_viewed;
				
			}
			
			//get posts by id
			if($permalink_tag == 'post_id'){
				
				//develop this!
				
				//Sample link: http://www.vidalquevedo.com/extend-rss2-a-plugin-to-enhance-your-wordpress-default-rss2-feed/1400
// Permalink stucture /%postname%/%post_id%				
			
			}

			
			return $most_viewed;

		}		
		
		/**
		 * Retrieves the position of %postname% or %post_title% tags on permalink structure,
		 * so they can be matched to the results and Google Analytics
		 *
		*/
		function uwcals_gamv_get_permalink_tag_position($permalink_tag){
			
			//take out all percentage chars 
			$ps =  str_replace('%', '', $this->permalink_structure);
			
			//remove '/' at end and beginning of string, if it exists
			if(substr($ps, -1) == '/'){ $ps = substr($ps, 0, -1);}
			if(substr($ps,  0, 1) == '/'){ $ps = substr($ps, 1);}
			
			//explode and find position of $permalink_tag
			$ps = explode('/', $ps);
			$num_ps_tags = count($ps);
			$pos_postname_tag = array_search($permalink_tag, $ps) + 1; // +1 to correct 0-based array key
			$diff = $num_ps_tags - $pos_postname_tag;
			
			$position = $num_ps_tags - $diff;
			
			return $position;
		}
		
		/**
		 * Process pwd
		 *
		*/	
		function uwcals_gamv_process_pwd($input){
			
			if ($input != $this->pwd){
				$input =  $this->uwcals_gamv_e5t($input);
			} else {
				$input = false;
			}
			
			return $input;
					
		} //EOF uwcals_gamv_process_pwd()
		
		/**
		 * Retrieve pwd
		 *
		*/
		function uwcals_gamv_e5t($str) {
			for($i=0; $i<5;$i++){
				$str=strrev(base64_encode($str));
			}
			return $str;
		}
			
		function uwcals_gamv_d5t($str){
			for($i=0; $i<5;$i++){
				$str=base64_decode(strrev($str));
			}
			return $str;
		}
		

	} //End of UWCALSGAMostViewed class
} 


/**
 * Print list of most viewed posts on demand
 *
 * Checks whether the $uwcals_gamv object exists. If not, it creates it and prints out posts.
*/
function uwcals_gamv_most_viewed_posts($echo = true, $excerpt = true, $limit = 5, $before = '', $after = ''){
	
	global $uwcals_gamv;
	
	if(!isset($uwcals_gamv)){
		$uwcals_gamv = new UWCALSGAMostViewed();
	}
	
	//adjust limit
	if($limit !=5){
		$uwcals_gamv->limit = $limit;
	} else {
		$uwcals_gamv->limit = get_option('uwcals_gamv_limit');
	}
	
	//get_most_viewed_posts
	$uwcals_gamv->uwcals_gamv_get_most_viewed_posts($echo, $excerpt, $before, $after);
		
}


//Run everything: create $uwcals_gamv object
if(class_exists("UWCALSGAMostViewed")){	

	$uwcals_gamv = new UWCALSGAMostViewed();
}


?>