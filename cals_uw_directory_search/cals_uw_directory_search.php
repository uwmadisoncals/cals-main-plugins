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
-Unify search process (right now there is one for ajax, one for form request and one for shortcode)
*/

/*LOG

- 03/23/11 - Added "phone" parameter support for uw_dir_search shortcode, so users can specify which phone number to use, if the contact info contains more than one (e.g. [uw_dir_search name="SARAH  K A PFATTEICHER" phone="2"] )


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
				
				//add shortcode support
				add_shortcode('uw_dir_search', array($this, 'cals_uwds_shortcode'));
						
			}
			
		} //EOF CALSCustomPostTitleURL
		
		
		/**
		 * Creates CALS UW directoy search form
		 *
		*/
		public function cals_uwds_get_form($sidebar, $add_class){ 
		
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
			<div id="cals_uwds" <?php if($sidebar){ echo 'class="cals_uwds_sidebar '.$add_class.'"'; }?>>		
                
                <?php
                
				if($sidebar){ 
						
						//if a name has been sent, display "matches" message
						if ($name!=''){ 
							echo '<h4 class="dept_title">CALS DIRECTORY MATCHES</h4>
								  <small>Refine directory search below:</small>'; 
						} else if($name==''){
							echo '<h4 class="dept_title">CALS DIRECTORY SEARCH</h4>';
						}
				}
				?>
                <form id="cals_uwds-f" name="cals_uwds-f" action="<?php if($sidebar){ echo '/contact/cals-directory/';} else { the_permalink(); }?>">
                    <fieldset >
                        <input type="text" id="cals_uwds-q" name="cals_uwds-q" autocomplete="off" value="<?php if($name!=''){ echo $name;} else {echo 'Enter a name...';}?>"/>
                        <input type="submit" value="Search"/> 
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
        
        //[cals_uwds js}
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
                                                                    for(j=0;j<record['titles'].length;j++){
                                                                        if(record["titles"][j]["division"]=="COLLEGE OF AGRICULTURAL & LIFE SCIENCES"){
                                                                        
                                                                        
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
                                                                    }
                                                                        
                                                                });
                                        
                                        output+="</ul>";
                                            
                                }
                                
    
                                }
                                
                                $("#cals_uwds_search_results").html(output);
                                //console.log(XMLHttpRequest);	
                               $(".mainResults").html(output);
                              
                              },
                    error: function(){ 
                            $("#cals_uwds_search_results").html('Data could not be retrieved.');
                            
                            }
                });	
            });	   
        }); // [End of cals_uwds js]
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
						
							//make sure record belongs to specified division (run through all possible divisions)
							for($j=0; $j<count($record->titles); $j++){
								if ($record->titles[$j]->division == 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES'){
							
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
									if($record->titles[$j]->title!=''){
										$output.='<div class="person_title">
														<strong>Title: </strong>'. $record->titles[$j]->title. '
											  	  </div>';
									}

									//print department
									if($record->titles[$j]->department!=''){
										$output.='<div class="person_department">
														<strong>Dept: </strong>'. $record->titles[$j]->department. '
											  	  </div>';
									}
									
									//print 'more' link
									$output.='<div class="person_more">
												<a href="http://www.wisc.edu/directories/person.php?name='. $record->fullName.'" target="_blank">More &raquo;</a>
											  </div>';								

								$output.='</li>';
							}
							}
						}
					
					$output.='</ul>';
					
				}
				
				echo $output;
			
			}
			
		} /* EOF uwds_get_results*/
		
		
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
						if ($record->titles[$j]->division == 'COLLEGE OF AGRICULTURAL & LIFE SCIENCES'){
							$output = '<small style="color:#333">'. $record->phones[$phone_position].' - <a href="mailto:'. $record->emails[0].'"> '. $record->emails[0].'</a></small>';
					 	}
					 }
				}
			unset($_GET['name']);
			return $output;
			}

		}
	
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