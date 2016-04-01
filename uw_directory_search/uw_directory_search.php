<?php
/*
Plugin Name: UW Directory Search
Plugin URI: ???
Description: Adds a customizable University of Wisconsin-Madison Directory Search widget to your site.
Author: College of Agricultural and Life Sciences (CALS) at University of Wisconsin-Madison
Author URI: http://www.cals.wisc.edu
Last Updated: 09/06/11
Version: 1.5
*/

/*
TODO

2.0
- Division list
	- Add subdivisions
	- Add multiple division selection
	
- Options page:
	- Add option to set max num of results before displayin 'more' link (NTH)
*/


/*LOG
09/05/11
- Added option to select UWDS page on admin panel

08/15/11 
- Removed '/contact/cals-directory/' relative path from #cals_uwds-f form action
- Removed 'submit' button from #cals_uwds-f form

07/25/11 
- Eliminated use of proxy.php when users send request via regular form (not ajax)

03/23/11 
- Added "phone" parameter support for uw_dir_search shortcode, so users can specify which phone number to use, if the contact info contains more than one (e.g. [uw_dir_search name="SARAH  K A PFATTEICHER" phone="2"] )


*/

if(!class_exists('CALSUWDirectorySearch')){

	class CALSUWDirectorySearch{
	
		/**
		 * The constructor
		 * 
		 * Registers plugin on activation, and sets all of its options.
		 * Runs all required Actions and Filters depending on context (admin or front end)
		*/
		
		function CALSUWDirectorySearch(){

			//set up default options on activation
			register_activation_hook( __FILE__, array('CALSUWDirectorySearch', 'install') );

			//remove default options on deactivation
			register_deactivation_hook( __FILE__, array('CALSUWDirectorySearch', 'uninstall') );

			
			//Get option values
			$this->division = get_option('cals_uwds-division');
			$this->inc_site_search = get_option('cals_uwds-inc_site_search');
			$this->enable_widget = get_option('cals_uwds-enable_widget');
			$this->directory_page_id = get_option('cals_uwds-directory_page_id');
			$this->form_displayed = false;
	
			
			//Run actions and filters by context
			
			if(is_admin()){
				
				//create menu option
				add_action('admin_menu', array($this, 'cals_uwds_plugin_menu'));

				
			} else {
				
				//register and enqueue stylesheet for plugin
				wp_register_style('cals_uwds-style', plugins_url('style.css', __FILE__), false, '1', 'all');
				wp_enqueue_style('cals_uwds-style', plugins_url('style.css', __FILE__), false, '1', 'all');
				
				//add shortcode support
				add_shortcode('uw_dir_search', array($this, 'cals_uwds_shortcode'));
										
			}
			
			//ENABLE PLUGIN OPTIONS			
			
			//display in dedicated page
			if($this->directory_page_id !=''){
				add_action('the_content', array($this, 'cals_uwds_page'));
			}

			//Add widget support
			if($this->enable_widget == 1){
				add_action('widgets_init', array($this, 'cals_uwds_widget_register'));
			}
				
			//Include UW Directory Search results in site search
			if($this->inc_site_search == 1){
				add_action('widgets_init', array($this, 'cals_uwds_inc_site_search'));
			}	
			

		} //EOF CALSCustomPostTitleURL
		
		function cals_uwds_temp_activate_widget(){
				
				//if widget is not active, temporarily activate and display it on sidebar
				if(is_search() && $this->form_displayed==false){
					$this->cals_uwds_widget();
					$this->form_displayed =  true; //keeps this from displaying the widget as WP loops thorugh all active widgets
				}
						
		}
		
		
		/**
		 * Run on activation to set up default option values for plugin
		 *
		 * @reference http://codex.wordpress.org/Function_Reference/register_activation_hook
		*/
		static function install(){
			
			//add default options
			add_option('cals_uwds-division', '');
			add_option('cals_uwds-inc_site_search', 1);
			add_option('cals_uwds-enable_widget', 1);
			add_option('cals_uwds-directory_page_id', '');
		} //OEF install
		
		/**
		 * Run on deactivation to remove option values for plugin
		 *
		*/
		static function uninstall(){
			
			//delete default options
			delete_option('cals_uwds-division');
			delete_option('cals_uwds-inc_site_search');
			delete_option('cals_uwds-enable_widget');
			delete_option('cals_uwds-directory_page_id');
			
		} //OEF uninstall
		
		
		
		/**
		 * Creates UW directoy search form
		 *
		*/
		public function cals_uwds_get_form($sidebar, $add_class){ 
		
			global $post;
			
			if ($_GET['s']!=''){    
				//request was sent from site search form 
				$name = $_GET['s'];	
			
			} else if ($_GET['q']!=''){
				//request was sent from site Google CSE search form 
				$name = $_GET['q'];
			}
			
			?>
			 <?php 
			 if($sidebar){ 
			 	echo '<li id="cals_uwds" class="widget-container cals_uwds_sidebar '.$add_class.'">';
					if ($name!=''){ 
						echo '<h4 class="widget-title">UW DIRECTORY MATCHES</h4>
						  <small>Refine directory search below:</small>'; 
					} else if($name==''){
						echo '<h4 class="widget-title">UW DIRECTORY SEARCH</h4>';
					}
				} else {
					echo '<div id="cals_uwds">';
				}
				?>
                 <form id="cals_uwds-f" name="cals_uwds-f" >
                    <fieldset >
                        <input type="text" id="s" name="s" autocomplete="off" value="<?php if($name!=''){ echo $name;} else {echo 'Enter a name...';}?>"/>
                    </fieldset>
                </form>

                <div id="cals_uwds_search_results"></div>
                
			<?php 
			if($sidebar){
				echo  '</li>';
			} else {
				echo '</div>';
			}
			?>
		<?php 
		} //EOF cals_uwds_get_form
		
		
		function cals_uwds_javascript(){?>
		<script type="text/javascript" >
            
            //[cals_uwds js}
            jQuery(document).ready(function($){
                
                $('#cals_uwds-f').submit(function(event){
                    event.preventDefault();
                });
                
                
                $('#cals_uwds #s').click(function(){
                    if(this.value == 'Enter a name...') {
                        this.value="";
                    }
                });
                
                //run ajax request on keyup
                $('#cals_uwds #s').keyup(function(){
                    get_records();
                });	   
                
                //if $_GET, run ajax request in on load if cals_uwds-inc_site_search = 1
                <?php 
				
				
				if($_GET['s']!='' || $_GET['q']!='' && $this->inc_site_search==1 && !is_active_widget(false, 'cals_uwds_widget')){?>
                    get_records();
                <?php } ?>         
           }); 
		
	   function get_records(){
	   		jQuery.ajax({
                    <?php //if using proxy, plugin could print: 'url: '.'echo plugins_url('proxy.php', __FILE__).'','; ?>
                    url: 'http://www.wisc.edu/directories/json/?jsonp=?',
                    type: 'GET',
                    dataType: 'json',
                    data: {name: jQuery('#cals_uwds #s').val(), division: '<?php echo $this->division;?>'},
                    success:  function(data, textStatus, XMLHttpRequest) {
                                var output ='';
                                if (data !== null){
                                    if(data["count"]==0){
                                        if(data["errors"][0]){
                                            if(data["errors"][0]['code']==4){
                                                //"Results >
                                                output = '<div class="error">Too many results. Please narrow your search.</div>';									
                                            }
                                        } else {
                                            output = '<div class="error">No matches found.</div>';
                                        }
                                    } else {
                                        output = '<div class = "num_matches">' + data["count"] + ' match';
                                        if (data['count']>1){ output+='es'};
                                        output+= '</div>';
                                        output+='<ul>';
                                        jQuery.each(data["records"], function(index, record){
                                                                    for(j=0;j<record['titles'].length;j++){
                                                                        //if(record["titles"][j]["division"]=="<?php echo $this->division;?>"){
                                                                        
                                                                        
                                                                        output+=
                                                                                '<li class="person">' +
                                                                                    '<div class="person_name"><strong>'
                                                                                        + record['fullName'] + 
                                                                                    '</strong></div>';
                                                                                    
                                                                        if (record['emails'][0]!=""){
                                                                            output+='<div class="person_email"><strong>Email: </strong>' +
                                                                                        '<a href="mailto:' + record['emails'][0] + '"> ' + record['emails'][0] + ' </a>' +
                                                                                    '</div>';
                                                                        }
                                                                        
                                                                        if(record['phones'][0]!=""){
                                                                            output+='<div class="person_phone"><strong>Phone: </strong>' 
                                                                                        + record['phones'][0] +
                                                                                    '</div>';
                                                                        }
    
                                                                        if(record["titles"][j]["title"]){
                                                                            output+='<div class="person_title"><strong>Title: </strong>'
                                                                                        + record["titles"][j]["title"] +
                                                                                    '</div>';
                                                                        }
                                                                        
                                                                        if(record["titles"][j]["department"]){
                                                                            output+='<div class="person_department"><strong>Dept: </strong>'
                                                                                        + record["titles"][j]["department"] +
                                                                                    '</div>';
                                                                        }
                                                                        
                                                                        
                                                                            output+='<div class="person_more">' +
                                                                                        '<a href="http://www.wisc.edu/directories/person.php?name=' + record['fullName'] + '" target="_blank">More &raquo;</a>' +
                                                                                    '</div>' +
                                                                                '</li>';
                                                                        }
                                                                    //}
                                                                        
                                                                });
                                        
                                        output+="</ul>";
                                            
                                }
                                
    
                                }
                                
                                jQuery("#cals_uwds_search_results").html(output);
                                //console.log(XMLHttpRequest);	
                              
                              },
                    error: function(){ 
                            jQuery("#cals_uwds_search_results").html('Data could not be retrieved.');
                            
                            }
                });	
	   }
		
		// [End of cals_uwds js]
    </script>		
		<?php }		
		
		
		/**
		 * Processes shortcode
		 *
		 * @param $attr array containing all parameters sent via shortcode
		 * @reference http://codex.wordpress.org/Shortcode_API
		*/		
		function cals_uwds_shortcode($attr){
			$_GET['name'] = urlencode($attr['name']);
			
			//get which phone to display, if more than one is found
			if ($attr['phone']!='' && $attr['phone']>0){
				$phone_position = $attr['phone'] - 1;
			} else {
				$phone_position = 0;
			}
			
			$data = json_decode(include(plugin_dir_path(__FILE__).'proxy.php'));
			if(isset($data) && $data->count>0){
				foreach($data->records as $record){
						//make sure record belongs to specified division (run through all possible divisions)
					for($j=0; $j<count($record->titles); $j++){
						if ($record->titles[$j]->division == $this->division){
							$output = '<small style="color:#333">'. $record->phones[$phone_position].' - <a href="mailto:'. $record->emails[0].'"> '. $record->emails[0].'</a></small>';
					 	}
					 }
				}
			unset($_GET['name']);
			return $output;
			}

		} //EOF cals_uwds_shortcode
	

		/**
		 * Displays the UW Directory Search box on the sidebar
		 *
		*/
		function cals_uwds_widget(){
			
			if ($this->form_displayed==false){
				//print form in sidebar mode
				$this->cals_uwds_get_form($sidebar = true, $add_class);
				
	
				//add required js to footer
				add_action('wp_footer', array($this, 'cals_uwds_javascript'));
				
				//HACK: keeps this from displaying the widget as WP twice, as is_active_widget doesn't seem to work in cals_uwds_temp_activate_widget
				$this->form_displayed =  true; 
			}
			
		}

		/**
		 * Displays the UW Directory Search box on dedicated page
		 *
		*/
		function cals_uwds_page($output){
			
			if(is_page($this->directory_page_id)){
			
				//print form in sidebar mode
				$this->cals_uwds_get_form($sidebar = false, $add_class);
				
				//prevent from printing on sidebar (if widget is enabled)
				$this->form_displayed =  true; 
	
				//add required js to footer
				add_action('wp_footer', array($this, 'cals_uwds_javascript'));
			
			} else {
				
				return $output;
			
			}
		}
		
		/**
		 * Register UW Directory Search widget
		 *
		*/
		function cals_uwds_widget_register(){
			
			//register sidebar widget
			wp_register_sidebar_widget('cals_uwds_widget', 'UW Directory Search', array($this, 'cals_uwds_widget'), array('description' => 'Displays the UW Directory Search box on the sidebar'));			
		
		}//EOF cals_uwds_widget_register
		

		/**
		 * Register plugin's admin page
		 *
		*/
		function cals_uwds_plugin_menu() {
			add_options_page('UW Directory Search Options', 'UW Directory Search', 'manage_options', 'cals-uwds', array($this, 'cals_uwds_plugin_options'));
		} //EOF cals_uwds_plugin_menu
		
		/**
		 * Populate plugin's admin page
		 *
		*/ 
		function cals_uwds_plugin_options() {
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			
			//Process / save data
				
				$option_names = array('cals_uwds-inc_site_search', 'cals_uwds-enable_widget', 'cals_uwds-division', 'cals_uwds-directory_page_id');
				
				foreach ($option_names as $opt_name){
					
					// Read in existing option value from database
					$opt_val[$opt_name] = get_option( $opt_name );
					
					// Verify sumission and nonce 
					if( isset($_POST) && wp_verify_nonce( $_POST['cals_uwds-noncename'], plugin_basename(__FILE__) )){
						// Read their posted value
						$opt_val[$opt_name] = $_POST[ $opt_name ];
				
						// Save the posted value in the database
						update_option( $opt_name, $opt_val[$opt_name] );
					}
					
				}
				
			
			//Print form
			?>			
			<div class="wrap">
            	<h2>UW Directory Search Options</h2>
                <form name="cals_uwds_plugin-form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<table class="form-table">
					<tbody>
                            <tr valign="top">
                                <th scope="row"><label for="cals_uwds-division">Select UW Division to search.</label></th>
                                <td>
                                    <select name="cals_uwds-division" id="cals_uwds-division">
                                        <?php 						

											 $divisions = array('All' => '',
																'Agricultural and Life Sciences' => 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES',
																'Business' => 'SCHOOL OF BUSINESS',
																'Continuing Studies' => 'DIVISION OF CONTINUING STUDIES',
																'Education' => 'SCHOOL OF EDUCATION',
																'Engineering' => 'COLLEGE OF ENGINEERING',
																'Environmental Studies, Nelson Institute for' => 'GAYLORD NELSON INST ENVIRONMENTAL STUDY',
																'Graduate School' => 'GRADUATE SCHOOL',
																'Human Ecology' => 'SCHOOL OF HUMAN ECOLOGY',
																'International Studies' => 'DIVISION OF INTERNATIONAL STUDIES',
																/*'Journalism and Mass Communication' => '',*/
																'Law School' => 'LAW SCHOOL',
																'Letters & Science' => 'COLLEGE OF LETTERS AND SCIENCE',
																/*'Library and Information Studies' => '',*/
																'Medicine and Public Health' => 'SCHOOL OF MEDICINE AND PUBLIC HEALTH',
																/*'Music' => '',*/
																'Nursing' => 'SCHOOL OF NURSING',
																'Pharmacy' => 'SCHOOL OF PHARMACY',
																/*'Public Affairs' => '',*/
																/*'Social Work' => '',*/
																'Veterinary Medicine' => 'SCHOOL OF VETERINARY MEDICINE');

											 //For 2.0
											 /*$divisions2 = array(
																 array('div_name' => 'All', 
																	   'div_id'   => 'all',  
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Agricultural and Life Sciences', 
																	   'div_id'   => 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES',
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Business', 
																	   'div_id'   => 'SCHOOL OF BUSINESS', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Continuing Studies', 
																	   'div_id'   => 'DIVISION OF CONTINUING STUDIES', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Education', 
																	   'div_id'   => 'SCHOOL OF EDUCATION', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Engineering', 
																	   'div_id'   => 'COLLEGE OF ENGINEERING', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Environmental Studies, Nelson Institute for',
																	   'div_id'   => 'GAYLORD NELSON INST ENVIRONMENTAL STUDY',
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Graduate School', 
																	   'div_id'   => 'GRADUATE SCHOOL', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Human Ecology', 
																	   'div_id'   => 'SCHOOL OF HUMAN ECOLOGY', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'International Studies', 
																	   'div_id'   => 'DIVISION OF INTERNATIONAL STUDIES', 
																	   'sub_divs' => array()),
																 
																 //array('div_name' => 'Journalism and Mass Communication', 
																	//	 'div_id'   => '', 
																		// 'sub_divs' => array()),
																 
																 array('div_name' => 'Law School', 
																	   'div_id'   => 'LAW SCHOOL', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Letters & Science', 
																	   'div_id'   => 'COLLEGE OF LETTERS AND SCIENCE',
																	   'sub_divs' => array()),
																 																 
																 //array('div_name' => 'Library and Information Studies', 
																	//	 'div_id'   => '',
																	//	 'sub_divs' => array()),
																 
																 array('div_name' => 'Medicine and Public Health', 
																	   'div_id'   => 'SCHOOL OF MEDICINE AND PUBLIC HEALTH',
																	   'sub_divs' => array()),
																 
																 //array('div_name' => 'Music', 
																	//	 'div_id'   => '', 
																		// 'sub_divs' => array()),
																 
																 array('div_name' => 'Nursing',
																	   'div_id'   => 'SCHOOL OF NURSING', 
																	   'sub_divs' => array()),
																 
																 array('div_name' => 'Pharmacy', 
																	   'div_id'   => 'SCHOOL OF PHARMACY',
																	   'sub_divs' => array()),
																 
																 //array('div_name' => 'Public Affairs', 
																	//	 'div_id'   => '', 
																		// 'sub_divs' => array()),
																 
																 //array('div_name' => 'Social Work',
																	//	 'div_id'   => '', 
																		// 'sub_divs' => array()),
																 
																 array('div_name' => 'Veterinary Medicine',
																	   'div_id'   => 'SCHOOL OF VETERINARY MEDICINE',
																	   'sub_divs' => array())
																 );*/



											foreach($divisions as $key => $value){?>
												<option value="<?php echo $value; ?>"
                                                	<?php 
														if($value == $opt_val['cals_uwds-division']){
															echo 'selected="selected"';
														}?>
                                                ><?php echo $key; ?>
                                                </option> 
											<?php }									
										?>
                                     </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="cals_uwds-inc_site_search">Include UW Directory matches in site search results (matches are displayed on sidebar).</label></th>
                                <td>
                                    <input type="checkbox" id="cals_uwds-inc_site_search" name="cals_uwds-inc_site_search" value="1" <?php if($opt_val['cals_uwds-inc_site_search'] == 1){ echo 'checked="checked"';}?> />
                                </td>
                            </tr>                                      
                            <tr valign="top">
                                <th scope="row"><label for="cals_uwds-enable_widget">Enable UW Directory sidebar widget (allow to permanently display UW Directory search box on sidebar.</label></th>
                                <td>
                                    <input type="checkbox" id="cals_uwds-enable_widget" name="cals_uwds-enable_widget" value="1" <?php if($opt_val['cals_uwds-enable_widget'] == 1){ echo 'checked="checked"';}?> />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label for="cals_google_cse_search_results_page_id">Dedicated "UW Directory Search" page: </label></th>
                                <td>
								<?php 
									$page_list = wp_dropdown_pages('name=cals_uwds-directory_page_id&echo=0&selected='.get_option('cals_uwds-directory_page_id'));
									
									$pattern = '/(<select\b[^>]*>)/';
									$replace = '$1'.'<option value="" class="level-0">--- Select a page ---</option>';
									
									echo preg_replace($pattern, $replace, $page_list);
									
								?><span class="description">Page where search results will be displayed. You can <a href="post-new.php?post_type=page" target="_blank">create a new page</a> if none of these work.</span></td>
                        	</tr>                          
						</tbody>			
					</table>	                            
				<?php 
					// Use nonce for verification
					wp_nonce_field( plugin_basename(__FILE__), 'cals_uwds-noncename' ); 
              	?>
				<?php submit_button(); ?>
              </form>
        </div>
            
            <?php 
		} // EOF cals_uwds_plugin_options
		
		/**
		 * Integrates UW CALS Directory search with default WP search by:
		 * - Replacing default seach text in WP search box to include "name"
 		 * 
		 *
		*/
		function cals_uwds_inc_site_search(){
			
			// Replace search inbox default title
			add_action('get_search_form', array($this, 'cals_uwds_update_searchform_text'));
			
			//temporarily activate widget to display results in sidebar
			add_action('dynamic_sidebar', array($this, 'cals_uwds_temp_activate_widget'), 100);
			
		} //EOF cals_uwds_inc_site_search
		
	
		/**
		 * Customizes the header search box default text 
		 * 
		 * @param string $form HTML code for search form
		 * @return string $form modified HTML code for seach form
		 * @link: http://codex.wordpress.org/Function_Reference/get_search_form
		*/
		function cals_uwds_update_searchform_text($form){
			
				$form = str_replace(array('value=""', 'value="Enter your search keywords here..."'), 'value="Enter keywords or name here..."', $form);
				return $form;
		} //EOF cals_uwds_update_searchform_text
		
	
	} //End of CALSCustomPostTitleURL class declaration

}

/**
 * Print list of custom related links on demand
 *
 * Checks whether the $cals_uwds object exists. If not, it creates it and prints out form.
*/
function cals_uw_directory_search($sidebar=false, $add_class = ''){
	global $cals_uwds;
	
	if(!isset($cals_uwds)){
		$cals_uwds = new CALSUWDirectorySearch();
	}
	
	$cals_uwds->cals_uwds_get_form($sidebar, $add_class);
	
	//add required js to footer
	add_action('wp_footer', array($cals_uwds, 'cals_uwds_javascript'));
}


//Run everything: create $cals_uwds object
if(class_exists("CALSUWDirectorySearch")){	

	$cals_uwds = new CALSUWDirectorySearch();
}
?>