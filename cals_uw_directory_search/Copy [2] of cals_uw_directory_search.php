<?php
/*
Plugin Name: CALS UW Directory Search
Description: This plugin enables an Ajax based-search form that retrieves results from UW-Madison's directory
Version: 0.5
Author: Vidal Quevedo
*/

/*
TODO:
- Eliminate use of proxy.php when users send request via regular form (not ajax)
- Remove '/contact/cals-directory/' relative path from form action!
- Create admin with options to:
	- Choose what UW division to search (i.e. college of and life sciences, l&s, etc)
	- Set default page to display directory search and results
-Change $sidebar parameter to $sidebar, so it's more meaningful to the context in which it is displayed

*/

if(!class_exists('CALSUWDirectorySearch')){

	class CALSUWDirectorySearch{
	
		/**
		 * The constructor
		 * 
		 * Runs all required Actions and Filters depending on context (admin or front end)
		*/
		
		function CALSUWDirectorySearch(){
			
			//Action and Filters
			
			if(is_admin()){
				
			} else {
				
				//register and enqueue stylesheet for plugin
				wp_register_style('cals_uwds-style', plugins_url('style.css', __FILE__), false, '1', 'all');
				wp_enqueue_style('cals_uwds-style', plugins_url('style.css', __FILE__), false, '1', 'all');
				
				//add plugin's javascript to footer
				add_action('wp_footer', array($this, 'cals_uwds_javascript'));
		
			}
			
		} //EOF CALSCustomPostTitleURL
		
		
		/**
		 * Creates CALS UW directoy search form
		 *
		*/
		public function cals_uwds_get_form($sidebar){ 
		
			global $post;
			
			if ($_GET['cals_uwds-q']!=''){ 
				//request was sent from directory search form (user either pressed "Enter" or JS is disabled in browser)
				$name = $_GET['cals_uwds-q'];
			
			} else if ($_GET['s']!=''){    
				//request was sent from site search form 
				$name = $_GET['s'];	
			
			} else if ($_GET['q']!=''){
				//request was sent from site Google CSE search form 
				$name = $_GET['q'];
			}
			
			?>
			<div id="cals_uwds" <?php if($sidebar){ echo 'class="cals_uwds_sidebar"'; }?>>		
                
                <?php if($sidebar){ echo '<h4 class="dept_title">CALS DIRECTORY MATCHES</h4><small>Refine directory search below:</small>'; }?>
                <form id="cals_uwds-f" name="cals_uwds-f" action="<?php if($sidebar){ echo '/contact/cals-directory/';} else { the_permalink(); }?>">
                    <fieldset >
                        <input type="text" id="cals_uwds-q" name="cals_uwds-q" autocomplete="off" value="<?php if($name!=''){ echo $name;} else {echo 'Enter a name...';}?>"/> 
                    </fieldset>
                </form>

                <div id="cals_uwds_search_results">      
                <?php
                    //if $_GET exists, data was sent via http. Display results
                    if($_GET['cals_uwds-q']!='' || $_GET['s']!='' || $_GET['q']!=''){
                        $this->uwds_get_results();
                    }
                ?>
                
                </div><br />
			 </div>
		<?php 
		} //EOF cals_uwds_get_form
		
		
		function my_action_callback(){
	
			$handle = @fopen("http://www.wisc.edu/directories/json/", "r");
	
			if ($handle) {
				while (!feof($handle)) {
					$buffer = fgets($handle, 4096);
					echo $buffer;
				}
				fclose($handle);
			}
		}
		
		function cals_uwds_javascript(){?>
			
			<script type="text/javascript" >
				
				//cals_uwds js
				jQuery(document).ready(function($){
					$('#cals_uwds-q').click(function(){
						if(this.value == 'Enter a name...') {
							this.value="";
						}
					});
					$('#cals_uwds-q').keyup(function(){
						$.ajax({
							<?php //if using proxy, plugin could print: 'url: '.'echo plugins_url('proxy.php', __FILE__).'','; ?>
							url: 'http://www.wisc.edu/directories/json/?jsonp=?',
							type: 'GET',
							dataType: 'json',
							data: {name: $(this).val(), division: 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES'},
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
												$.each(data["records"], function(index, record){
																			if(record["titles"][0]["division"]=="COLLEGE OF AGRICULTURAL & LIFE SCIENCES"){
																				
																				
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
	
																				if(record["titles"][0]["title"]){
																					output+='<div class="person_title"><strong>Title: </strong>'
																								+ record["titles"][0]["title"] +
																							'</div>';
																				}
																				
																				if(record["titles"][0]["department"]){
																					output+='<div class="person_department"><strong>Dept: </strong>'
																								+ record["titles"][0]["department"] +
																							'</div>';
																				}
																				
																				
																					output+='<div class="person_more">' +
																								'<a href="http://www.wisc.edu/directories/person.php?name=' + record['fullName'] + '" target="_blank">More &raquo;</a>' +
																							'</div>' +
																						'</li>';
																				}
																			
																				
																		});
												
												output+="</ul>";
													
										}
										

										}
										
										$("#cals_uwds_search_results").html(output);
										//console.log(XMLHttpRequest);	
									  
									  },
							error: function(){ 
								   	$("#cals_uwds_search_results").html('Data could not be retrieved.');
									
									}
						});	
					});	   
				}); //end of cals_uwds js
            </script>		
		<?php }


		/**
		 * Gets results when data is not requested via ajax but through regular form submission
		 *
		 *
		*/
		function uwds_get_results(){
			
			//echo plugin_dir_path(__FILE__).'proxy.php';
			$data = json_decode(include_once(plugin_dir_path(__FILE__).'proxy.php'));
			
			if(isset($data)){
								
				if($data->count==0){
					//get errors, if any
					if($data->errors[0]->code==4){
						$output = '<div class="error">Too many results. Please narrow your search.</div>';
					} else {
						$output = '<div class="error">No matches found.</div>';
					}

				} else {
					$output = '<div class = "num_matches">'.$data->count.' match';
					if ($data->count >1){ $output.='es';}
					$output.= '</div>';
					
					$output.='<ul>';
					
						foreach($data->records as $record){
						
							//make sure record belongs to specified division
							if ($record->titles[0]->division == 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES'){
							
								$output.='<li class="person">';
								
									//print full name
									$output.='<div class="person_name">
													<strong>'.$record->fullName.'</strong>
											  </div>';
								    
									//print email
									if($record->emails[0]!=''){
										$output.='<div class="person_email">
														<strong>Email: </strong><a href="mailto:'. $record->emails[0].'"> '. $record->emails[0].'</a>
											  	  </div>';
									}
									
									//print phone
									if($record->phones[0]!=''){
										$output.='<div class="person_phone">
														<strong>Phone: </strong>'. $record->phones[0].'
											  	  </div>';
									}									

									//print title
									if($record->titles[0]->title!=''){
										$output.='<div class="person_title">
														<strong>Title: </strong>'. $record->titles[0]->title. '
											  	  </div>';
									}

									//print department
									if($record->titles[0]->department!=''){
										$output.='<div class="person_department">
														<strong>Dept: </strong>'. $record->titles[0]->department. '
											  	  </div>';
									}
									
									//print 'more' link
									$output.='<div class="person_more">
												<a href="http://www.wisc.edu/directories/person.php?name='. $record->fullName.'" target="_blank">More &raquo;</a>
											  </div>';								

								$output.='</li>';
							}
						
						}
					
					$output.='</ul>';
					
				}
				
				echo $output;
			
			}
			
		} /* EOF uwds_get_results*/
		
	} //End of CALSCustomPostTitleURL class declaration

}

/**
 * Print list of custom related links on demand
 *
 * Checks whether the $cals_uwds object exists. If not, it creates it and prints out form.
*/

function cals_uw_directory_search($sidebar=false){
	global $cals_uwds;
	
	if(!isset($cals_uwds)){
		$cals_uwds = new CALSUWDirectorySearch();
	}
	
	global $current_user;
	get_currentuserinfo();
	//if($current_user->user_login=='vquevedo')
	$cals_uwds->cals_uwds_get_form($sidebar);
}


//Run everything: create $cals_uwds object
if(class_exists("CALSUWDirectorySearch")){	

	$cals_uwds = new CALSUWDirectorySearch();
}
?>