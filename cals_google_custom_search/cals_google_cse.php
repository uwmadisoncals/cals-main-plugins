<?php
/*
Plugin Name: CALS Google Custom Search Engine
Description: Replaces Wordpress default search with Google Custom Search Engine.
Version: 1.0
Author: Al Nemec
Author URI: http://cals.wisc.edu
*/

/*README

The CALS Google Custom Search Engine (CSE) plugin replaces the default Wordpress function with the Google CSE specified by the Search Engine Unique ID provide by the user. It requires the Google CSE account to be set up BEFORE it is activated in the site.


USES:
- Google Custom Search Engine (http://www.google.com/cse/)


INSTALLATION INSTUCTIONS: (last updated 10/19/10)

What you will need:
	1.- A valid Google CSE account
	2.- A WP page to display search results

1.- Google CSE
	a.- Register for a Google CSE account at http://www.google.com/cse/. Follow the 3 basic steps to set up the search engine.
	b.- Get the Search Engine's unique ID (Go to Google Custom Search home > Manage your existing search engines > [Your Engine's]
		Control Panel
	c.- Copy your Search Engine's Unique ID (e.g. '016039371683713681917:pyykxxxx-xx');
	
2.- Search Results page
	a.- Login to Wordpress
	b.- Go to Pages > Add New to add a new page for your search resuls. Give an identifiable title (e.g. "Search Results)
	c.- Publish the page

3.- Setting up the CALS Google CSE plugin
	a.- Download plugin to your local plugins/ directory and upload it to your server
	b.- Go to WP Admin > Plugins to activate the plugin
	c.- Go to Settings > CALS Google Custom Search
	d.- Paste the Search Engine Unique ID in the indicated field
	e.- Select a Search Results Page where search results will be displayed. 


NOTE ON CONFIGURING THE LOOK AND FEEL OF YOUR GOOGLE CSE IMPLEMENTATION
The look and feel of the Search Form can be altered by adding new CSS rules to your style.css file in your WP theme. The Search Results, however, are more easily customized by using the available tools in the "Look and Feel" section of the Google CSE's Control Panel


*/


/*TODO:
- Add option to enter own search form and search results page code
- Add option to enter width of search results page

*/


if (!class_exists(CALSGoogleCSE)){
	
	class CALSGoogleCSE {
	
		function CALSGoogleCSE(){
		
			//Define default properties (these should be retrieved from plugin's configuration for current site
				
				// Search engine unique ID (e.g. '016039371683713681917:pyykxxxx-xx')			   
				$this->cx = get_option('cals_google_cse_unique_id');	

				// ID of page where search results will be displayed 
				$this->action = get_option('cals_google_cse_search_results_page_id'); 			
			
			//Load actions and filters according to context
			if(is_admin()){
				//run admin stuff
				add_action('admin_menu', array($this, 'create_cals_google_cse_admin_panel'));				

				//call register settings function
				add_action('admin_init', array($this, 'register_settings'));

			} else {
									
				//If CALS Google CSE parameters exist, add filter to replace WP default search 
				//with Google CSE form
				if($this->cx!='' && $this->action!=''){
					add_filter('get_search_form', array($this,'create_search_form'),1);
				
					//Add filter to replace content of search resuls page with Google CSE search results code
					add_filter('the_content', array($this, 'create_search_results_page'),100);						
				}
				
				//load plugin's javascript
				wp_enqueue_script('jquery', '','','',true); //try to enqueue jquery and print it in footer
				add_action('wp_footer', array($this, 'print_scripts'));
				
			}
		}
		
		
		/* Creates the HTML code for the Google CSE search form displayed on
		 *
		 *
		*/  
		function create_search_form($form){
	
			$form = '
				<form role="search" action="'.get_permalink($this->action).'" id="cse-search-box">
				  <div>
					<label class="screen-reader-text" for="q">Search for:</label>
					<input type="hidden" name="cx" value="'.$this->cx.'" />
					<input type="hidden" name="cof" value="FORID:10" />
					<input type="hidden" name="ie" value="UTF-8" />
					<input type="hidden" name="filter" value="0" />
					<input type="text" name="q" id="q" value="Enter keywords or name here..." />
					<input type="image" src="'.get_bloginfo('template_url').'/images/cals_searchbutton.png" name="sa" id="sa" title="Search" alt="Search"/>
				  </div>
				</form>
				<script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box%26lang=en"></script>';
	
			return $form;
		}
		
		/* Creates the HTML code for the Google CSE search results page
		 *
		 *
		*/  
		function create_search_results_page($content){
			if (is_page($this->action)){
				$content = '<div id="cse-search-results"></div>
								<script type="text/javascript">
								  var googleSearchIframeName = "cse-search-results";
								  var googleSearchFormName = "cse-search-box";
								  var googleSearchFrameWidth = 600;
								  var googleSearchDomain = "www.google.com";
								  var googleSearchPath = "/cse";
								</script>
								<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>';
			}
			
			return $content;

		}
		
		/* Creates the CALS Google CSE plugin's admin page
		 *
		 *
		*/  
		function create_cals_google_cse_admin_panel(){
			add_options_page(__('CALS Google Custom Search'), __('CALS Google Custom Search'), 'administrator', 'cals_google_cse_conf_page', array('CALSGoogleCSE', 'cals_google_cse_conf_page') );
		
			//call register settings function
			add_action( 'admin_init', array($this, 'register_settings'));
		}
		
		/* Registers the plugin's setting options. Options registered this way are automatically handled by WP 
		 *
		 * @link http://codex.wordpress.org/Creating_Options_Pages#Register_Settings
		*/
		function register_settings(){
			//register plugin settings
			register_setting('cals_google_cse-settings-group', 'cals_google_cse_unique_id');
			register_setting('cals_google_cse-settings-group', 'cals_google_cse_search_results_page_id');			
		}
		
		
		/* Creates configuration page in Google CSE plugin's admin page
		 *
		 * @link How to save options automatically in WP plugins: http://codex.wordpress.org/Creating_Options_Pages
		*/  
		function cals_google_cse_conf_page(){ ?>
					
		<div class="wrap">
			<h2>CALS Google Custom Search Engine Settings</h2>
			<form method="post" action="options.php">
                <?php 
					//Automatically create hidden fields 'option_page', 'action' and '_wpnonce', which 
					//are required to automatically handle the form's option's data
					settings_fields( 'cals_google_cse-settings-group' ); 
				?>
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><label for="cals_google_cse_unique_id">Search engine unique ID: </label></th>
                            <td><input type="text" id="cals_google_cse_unique_id" name="cals_google_cse_unique_id" size="32" maxlength="40" value="<?php echo get_option('cals_google_cse_unique_id');?>"> <span class="description">e.g. '016039371683713681917:pyykxxxx-xx'</span></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="cals_google_cse_search_results_page_id">Search Results Page: </label></th>
                            <td><?php wp_dropdown_pages('name=cals_google_cse_search_results_page_id&selected='.get_option('cals_google_cse_search_results_page_id'));?> <span class="description">Page where search results will be displayed. You can <a href="post-new.php?post_type=page" target="_blank">create a new page</a> if none of these work.</span></td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                	<input type="submit" name="submit" value="<?php echo _e('Save Changes'); ?>" class="button-primary" />
                </p>
			</form>
		</div>
		<?php }	
		
		/* Saves plugin's configuration options to database
		 *
		 *
		*/
		
		function cals_google_cse_save_options(){
			
			update_option('cals_google_cse_unique_id', $_POST['cals_google_cse_conf_page']);
			update_option('cals_google_cse_search_results_page_id', $_POST['cals_google_cse_search_results_page_id']);
		
		}
		
		function print_scripts(){?>
			
		<script type="text/javascript">
			//disabled due to improved script
            /*jQuery(document).ready(function($){
            	$('#q').click(function(){
                	if(this.value!='') { this.value="";}
				});
			});*/
			
			
            		        
	        
        
        </script>
		
		<?php }
	
	} //end of CALSGoogleCSE class definition
} 

//Create $cals_google_cse object
if(class_exists("CALSGoogleCSE")){	
	$cals_google_cse = new CALSGoogleCSE();
}