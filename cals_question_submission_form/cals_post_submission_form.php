<?php
/*
Plugin Name: CALS Questions Submissions Form
Description: Creates a public form for non-registered users to submit posts for review and later publication.
Version: 0.2
Author: Al Nemec
Author URI: http://cals.wisc.edu
*/

/*README

The CALS Post Submissions Form creates a form for users to submit ocassional posts without having to register an get an account to access the WordPress admin section. The form provides the basic fields needed to send a post (title, content, category, tags and sumitter's name, department and email). If available, the plugin makes use of the WP_reCAPTCHA plugin to add an extra layer of security before the form is submitted.


USES:
- jQuery library (included with WordPress)
- jQuery Validate plugin (included in plugin's package, ref: http://bassistance.de/jquery-plugins/jquery-plugin-validation/)
- WP_reCAPTCHA (if installed)

INSTUCTIONS: (last updated 09/22/10)
1.- Download plugin to your local plugins/ directory
2.- Create dummy contributor user
3.- Open file cals_post_submission_form.php and enter the following conf values
	3a.- $this->dummy_user user_login and user_password
	3b.- $this->validation rules
4.- Upload plugin via FTP
5.- Go to Admin Panel > Plugins to activate the plugin
6.- Add [cals_questions_submissions_form] shortcode to body of page in which you want to display form 
6.- To configure with WP_reCAPTCHA plugin
	6a.- Install WP_reCAPTCHA plugin and follow instructions (get public keys, etc).
	6b.- In WP_reCAPTCHA conf panel, make sure to check "Enable reCAPTCHA on registration form," <-The reCAPTCHA
		 script won't work if you don't do this!
*/


/*TODO:
-In Admin > Users, highlight account being used by cals_psf and indicate that it shouldn't be erased
-Add dummy user automatically?
-$this->validation_rules should be created right from the form field definitions, and not by declaring it manually.
-In general, all options and configuration settings for the plugin should be declared in admin section and assigned as properties of the CALSPostSubmissionsForm class on construct
-Based on the above, the fields should be defined prior to declaring validation rules
-Make sure scripts and styles are only loaded IF shortcode is found! (this is not being done correctly at this point)
-check if a "gatekeeper" has been assigned to receive/approve notification of new submissions on send_email_alert() function
-figure out a way to make this plugin always load after the WP_reCAPTCHA plugins, if available, so it can work with it
-update instructions!

*/


if(!class_exists("CALSPostsSubmissionsForm")){
	class CALSPostsSubmissionsForm{
	
		function CALSPostsSubmissionsForm(){		
			
			//define dummy user login info (note: //this should be entered as part of plugin configuration in admin)
			$this->dummy_user = array('user_login' => 'test_cals1948',
									  'user_password' => 'leaJg9UK47yMJYG3xStN'); 
			
			//define form fields <--we would get these from the database
				//message fields
				
				
				//user information fields
			
			
						
			//define validation rules for form <-- these should come from each form field definition
			$this->validation_rules = array('post_title' => 'required'
											
										   );
			
			//define categories to include or exclude
			$this->include_categories = array('your questions'); //this could be accepted as a parameter
					
					
			$this->exclude_categories = array(); //this could be accepted as a parameter	
			
			//define list of possible messages
			$this->define_messages();
		
		}
		
		function get_form(){
			global $current_user;
			
			
			
			if (!is_user_logged_in()){
				$this->create_form();
			} else {
				if(!is_admin()){
					$this->log_message('user_already_logged_in');
			    	$this->print_message_log();
				}
			}
		}
		
		
		/**
		 * Generates code needed to replace the [cals_posts_submission_form] shortcode
		 * 
		 * Since CALS 1.0
		*/
		function create_form(){
			global $post;
			//require template.php so we can generate a nonce field
			require_once('./wp-admin/includes/template.php');
			$nonce= wp_create_nonce('cals_posts_submission_form_nonce');

			//display messages (success, error, warning, etc)
			$stop_the_show = $this->print_message_log();
			if(!$stop_the_show){
			
			$form_html = '
			<style type="text/css">
				textarea {
					width: 100%;
				}
			</style>
			<div id="cals_posts_submission_form" name="cals_posts_submission_form">
			
				<h2>Ask a Question</h2>
				<p>If you have a question for the College of Agricultural and Life Sciences Strategic Planning Committee, send us your question and we will review it.  <a href="http://cals.wisc.edu/category/your-questions">Any answered questions will be posted here</a>.  All submissions are anonymous.</p>
				<form id="cals_psf" name="cals_psf" method = "post" action="'.get_bloginfo('url').'/'.$post->post_name.'/">
					
						<ul style="margin: 0px;">';
							//print input field "post_title"
							$params = array('field_name'=>'post_title',
											'field_label'=>'What is your question?',
											'field_type'=>'text',
											'cols'=>56,
											'rows'=>8,
											'validation_rules'=>array('required' => true)); 
							
							$form_html.= '<li>'.$this->create_form_field($params).'</li>';
							

							//print input field "submitter's name"
							$params = array('field_name'=>'post_content',
											'field_label'=>'Content',
											'field_type'=>'text',
											'cols'=>56,
											'rows'=>8,											
											'validation_rules'=>array('required' => false));
							
							$form_html.= '<li style="display: none;">'.$this->create_form_field($params).'</li>';						
					
					//get categories list
					
						$form_html.= $this->get_form_categories();
						
					
					$form_html.='<li>';
							//print input field "submitter's name"
							$params = array('field_name'=>'post_tags',
											'field_label'=>'Tags',
											'size'=>48,
											'maxlength'=>64,											
											'field_type'=>'input');
							
							$form_html.= '<li style="display: none;">'.$this->create_form_field($params).'</li>';
					$form_html.='</li>
						</ul>
					
						<ul>';
								
							//print input field "submitter's name"
							$params = array('field_name'=>'submitter_name',
											'field_label'=>'Name',
											'field_type'=>'input',
											'size'=>32,
											'maxlength'=>64,											
											'validation_rules'=>array('required' => false));
							
							$form_html.= '<li style="display: none;">'.$this->create_form_field($params).'</li>';
								
							//print input field "submitter's email"
							$params = array('field_name'=>'submitter_email',
											'field_label'=>'Email',
											'field_type'=>'input',
											'size'=>32,
											'maxlength'=>64,											
											'validation_rules'=>array('required' => false,
																	   'email' => true));
							
							$form_html.= '<li style="display: none;">'.$this->create_form_field($params).'</li>';
							
						
						
						$params = array('field_name'=>'submitter_dept',
										'field_label'=>'CALS Department',
										'field_type'=>'list',										
										'list_options'=> array( array('option_name'=>'Agricultural and Applied Economics','option_value'=>''),															array('option_name'=>'Agronomy','option_value'=>''),
																array('option_name'=>'Animal Sciences','option_value'=>''),
																array('option_name'=>'Bacteriology','option_value'=>''),
																array('option_name'=>'Biochemistry','option_value'=>''),
																array('option_name'=>'Biological Systems Engineering','option_value'=>''),																array('option_name'=>'Community and Environmental Sociology','option_value'=>''),
																array('option_name'=>'Dairy Science', 'option_value'=>''),
																array('option_name'=>'Entomology','option_value'=>''),
																array('option_name'=>'Farm and Industry Short Course (FISC)','option_value'=>''),
																array('option_name'=>'Food Science','option_value'=>''),
																array('option_name'=>'Forest and Wildlife Ecology','option_value'=>''),
																array('option_name'=>'Genetics','option_value'=>''),
																array('option_name'=>'Horticulture','option_value'=>''),
																array('option_name'=>'Landscape Architecture','option_value'=>''),
																array('option_name'=>'Life Sciences Communication','option_value'=>''),
																array('option_name'=>'Nutritional Sciences','option_value'=>''),
																array('option_name'=>'Plant Pathology','option_value'=>''),
																array('option_name'=>'Soil Science','option_value'=>''),
																array('option_name'=>'Urban and Regional Planning','option_value'=>''),
																array('option_name'=>'Other', 'option_value')),
										'list_default_option' => '',
										'validation_rules'=>array('required' => false)
										);
										
						
						$form_html.= '<li style="display: none;">'.$this->create_form_field($params).'</li>';
						
						
					$form_html.='</ul>
					'.wp_nonce_field();
				
				echo $form_html;
				
					//display recaptcha, if WP_recaptcha plugin is activated
					do_action('cals_psf_recaptcha', 'display_recaptcha');
				
				$form_html =' 	
					<br/>
					<br/>
					<input style="font-size: 18px; padding: 8px;" type="submit" value="Send Question" />					
				</form>
			</div>';
		
			echo $form_html;
			
			} 
		}
		
		function create_form_field($params){
			//params: type, name/id, length, max size, validation parameters (required, email, numeric, etc)
			//extract($params);
			extract($params);
			
			//setup field labeling
			$field_html = '<label for="'.$field_name.'" class="cals_psf_field_label">'.$field_label.': ';
							if ($validation_rules['required']==true){
									$field_html.='<span class="cals_psf_required_field">*</span>';
							}
			$field_html.= '</label><br />';
			
			
			//generate rest of field's html
			switch($params['field_type']){
				case 'list':
					$field_html.= $this->create_form_field_list($params);			
				break;
				
				case 'input':
					$field_html.= $this->create_form_field_input($params);
				break;
				
				case 'text':
					$field_html.= $this->create_form_field_text($params);
				break;
			}
			
			
			//add field error message, if any
			//print_r($this->form_errors);
			
			
			for($j=0; $j<count($this->form_errors); $j++){
				if($this->form_errors[$j]['field_name']==$field_name){
					$field_html.='<label class="error" generated="true" for="'.$field_name.'">'.$this->form_errors[$j]['field_error_message'].'</label>';
				}
			}
			
			
			return $field_html;
		
		}
		
		function create_form_field_input($params){
						
			extract($params);
					
			$field_html = '<input name="'.$field_name.'" id="'.$field_name.'" value="'.$_POST[$field_name].'" size="'.$size.'" maxlength="'.$maxlength.'"/>';
			
			return $field_html;
		}
		
		function create_form_field_list($params){
			extract($params);
							

			$field_html.= '<select name="'.$field_name.'" id="'.$field_name.'">here';
								//print options
									//check if default option is empty. If so, print first
									if($list_default_option==""){
										$field_html.='<option></option>';
									} 
									
									//print options
									foreach($list_options as $option){
										
										//if option value is empty, use option name as value
										if($option['option_value']==""){
											$option['option_value'] = $option['option_name'];
										}
										
										//if $_POST is set (after a failed submission that returned a validation error), check if current option has been selected
										if(isset($_POST[$field_name]) && $_POST[$field_name]==$option['option_value']){
											$selected = 'selected="selected"';
										} else {
											$selected="";
										}
										
										$field_html.='<option value="'.$option['option_value'].'" '.$selected.'>'.$option['option_name'].'</option>';						
									}
									
			
			$field_html.= '</select>';
			
			return $field_html;
		}
		
		function create_form_field_text($params){
			
			extract($params);
			
			$field_html = '<textarea id='.$field_name.' name="'.$field_name.'" cols="'.$cols.'" rows="'.$rows.'">'.$_POST[$field_name].'</textarea>';
			
			return $field_html;
		}
		
			
		function define_messages(){
			
			global $post;
			
			$this->messages = array('user_already_logged_in' => '<strong>Hey there!</strong><br/><br/>
														
															It seems like you have a registered account and are currently logged in. 
															Please use the <a href="/wp-admin/post-new.php">post editor</a> 
															to submit your post instead.
														 <br/><br/>
														 Thank you,<br/><br/>
														 - The eCALS team',
							  		'nonce_test_failed' => 'Invalid form submission.',
									'failed_login' => 'Could not connect to server.',
									'failed_save_data' => 'Failed to save data.',
									'success_save_data' => 'Thank you! Your question has been submitted successfully. <br/><br/>
									<a href="'.get_bloginfo('url').'/your-questions"><strong>Click here to submit a new question &raquo;</strong></a>',
									'form_validation_error' => 'There was an error in your form.');
		}
		
		
		function create_form_field_list_wp_categories(){
			
		}
		
		
		function log_message($message, $stop_the_show=false){
			$this->message_log[] = array('text'=>$this->messages[$message], 'stop_the_show'=>$stop_the_show);
		}
		
		function print_message_log(){
			$stop_scripts = 0;
			if(count($this->message_log)>0){
				foreach($this->message_log as $message){
					echo '<p class="cals_message">'.$message['text'].'</p>';
					if($message['stop_the_show']==true){
						$stop_scripts=1;
					}
				} 			
			} 
			
			//if any of the errors required the script to stop, do so now that all
			//messages should've been printed
			if ($stop_scripts == 1){
				return true;
			}
		}
		
		
		function submit_form(){
			if (wp_verify_nonce($_POST['_wpnonce'])){
				$this->save_form_data();
			} else {
				//Nonce could not be verified, so add message to $this->messages
				$this->log_message('nonce_test_failed');
			}
		}
		
		
		/*
		 *
		 * @link: http://michaeldaw.org/papers/securing_wp_plugins
		 * @link: http://codex.wordpress.org/Function_Reference/esc_attr for a newer version of attribute_escape
		*/
		function save_form_data(){
								
				global $wpdb;
				extract ($_POST);
				
				//validate data
				if ($this->validate_form_data()){
					
					//login using dummy user first
					if(!is_wp_error($this->login_dummy_user())){
					
					//Insert new post
						
						// Create post object
						$my_post = array();
						$my_post['post_title'] = esc_attr($post_title);
						$my_post['post_content'] = esc_attr($post_content);
						$my_post['post_status'] = 'pending';
						
						$user = get_userdatabylogin($this->dummy_user['user_login']); //test_cals1948
						$my_post['post_author'] = $user->ID; 
						
						$my_post['post_category'] = $post_category;
						
						// Insert the post into the database
						$post_id = wp_insert_post( $my_post );
								
						// Add tags
						$newtagarray = explode(",", $post_tags);
						wp_set_object_terms( $post_id, $newtagarray, 'post_tag', true );	
				
						// Add custom fields (name, email, department)
							//name
							add_post_meta($post_id, 'submitter_name', $submitter_name, true); 
				
							//email
							add_post_meta($post_id, 'submitter_email', $submitter_email, true); 
				
							//department
							add_post_meta($post_id, 'submitter_dept', $submitter_dept, true); 
				
							//return message
							if (is_wp_error($post_id) || $post_id=='0'){ //if it's a WP_error object, display message
								$this->log_message('failed_save_data');
								
							} else {
								//alert admin a new message has been posted via public form
								$submitter_info= array('submitter_name'=>$submitter_name,
														'submitter_email'=>$submitter_email,
														'submitter_dept'=>$submitter_dept);
								
								$post_info = $my_post;								
								
								$this->send_email_alert($post_info, $submitter_info);
								
								$this->log_message('success_save_data', true);
								
							}
						
					} else {
						//there was an error trying to log in
						$this->log_message('failed_login');
					}
					
				//destroy session
				wp_logout();
				
				} 
		}
		
		
		function validate_form_data(){
			
			//submit each post field for evaluation
			foreach($_POST as $key => $value){
				
				$field_name = $key;
				$field_value = $value;
			

				//find whether there is a set of rules defined for field
				if (array_key_exists($field_name, $this->validation_rules)){
					
					//Put all rules in array, set non-arrayed items to 'true'
					if(is_array($this->validation_rules[$field_name])){
						$rules = $this->validation_rules[$field_name];
					} else {
						$rules[$this->validation_rules[$field_name]]= true;
					}
										
					
					//check all rules setup for current field
					$added=0;
					foreach($rules as $key => $value){
					
						//echo $field_name.$key;
						switch($key){
							case 'required':
								if($field_value==""){
									$this->form_errors[] = array('field_name' => $field_name,
																 'field_error_message' => 'This field is required.');
								$added++;
								}
							break;
							
							case 'email':
								if (!preg_match('/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i', $field_value)){
									$this->form_errors[] = array('field_name' => $field_name,
																 'field_error_message' => 'Please enter a valid email address.');
								$added++;
								} 							
							break;
						
						}
						
						if($added>0) {
							break;
							
						}
					} 
					unset($rules);
					
					
				}
			}
			
			//if there were errors in the form, log error message and return false to stop submission
			if(count($this->form_errors)>0){
				$this->log_message('form_validation_error');
				return false;
			} else {
				return true;
			}
		}
		
		
		/*
		 * @link: http://codex.wordpress.org/Function_Reference/wp_signon
		 */
		function login_dummy_user(){
					
			if(!is_user_logged_in()){
				$creds = array();
				$creds['user_login'] = $this->dummy_user['user_login']; 
				$creds['user_password'] = $this->dummy_user['user_password']; 
				$creds['remember'] = false;
				$user = wp_signon( $creds, false );
				
				return($user);
			}
		
		}
		
		function get_form_categories(){
					
			//get the id's of categories to include
			if(count($this->include_categories)>0){
				foreach($this->include_categories as $ic){
					$include[] =  get_cat_id($ic);
				}				
				$include = implode(',', $include);
			}

//vq		//get the id's of categories to exclude
			if(count($this->exclude_categories)>0){
				foreach($this->exclude_categories as $ec){
					$exclude[] =  get_cat_id($ec);
				}				
				$exclude = implode(',', $exclude);
			}


			$categories = get_categories('get=all&hierarchical=1&include='.$include.'&exclude='.$exclude);
			
			$cat_content =  '<ul style="display: none;" id="post_cat_list" name="post_cat_list" class="cals_psf_cat_list">';
			foreach ($categories as $cat){
				
				//if $_POST is set, check if values have been assigned to categories
				
				if(isset($_POST['post_category'])){
					
					$checked_values = $_POST['post_category'];
					
					foreach ($checked_values as $cv){
						if ($cv == $cat->cat_ID){
							$checked = 'checked="checked"';
							break;
						} else {
							$checked = '';
						}
					}
				}
				
				$cat_content.= '<li id="'.$cat->cat_ID.'">
					<label class="selectit">
						<input value="'.$cat->cat_ID.'" name="post_category[]" id="in-category-'.$cat->cat_ID.'" type="checkbox" checked>  '.ucwords($cat->name).'
					</label>
				</li>'; //vq
			}
			$cat_content.= '</ul>';
	
			return $cat_content;	
		}
		
		
		/*
		 *Sends email alerts to specified users
		 *
		 *@uses: getUsersByRole()
		*/
		
		function send_email_alert($post_info, $submitter_info){
			$sitename = get_bloginfo('name');
			extract ($submitter_info);
			extract ($post_info);
			//get list of editors in site or whoever has been selected as gatekeeper
			
				//get gatekeeper <-TODO
				
				//get editors (see http://www.wprecipes.com/how-to-get-all-users-having-a-specific-role)
				$user_ids = $this->getUsersByRole('editor');
				foreach($user_ids as $user_id){
					$user_info = get_userdata($user_id);
					$user_emails[] = $user_info->user_email;
				}
				
			//send email to specified addresses
			
			
			$subject = $sitename .' - New post submitted by '. $submitter_name .'.';
$message = '
Hi,

A new post titled "'.$post_title.'" has been submitted by '.$submitter_name.' ('.$submitter_email.').

Please login to review/approve it.

Thank you.


*** 
  Note: 
  You have received this message because you have an editor account in '.$sitename.'. 
  This is an automatic massage. Please do not reply to this address. 
  
***';

			
			
			//foreach($user_emails as $user_email){
				wp_mail('quevedo@wisc.edu', $subject, $message, $headers, $attachments);
			//}
   		}
		
		/*
		 *Gets users by specified role
		 *
		 *@reference: http://sltaylor.co.uk/blog/get-wordpress-users-by-role/
		*/
		function getUsersByRole($role){
			
			if ( class_exists( 'WP_User_Search' ) ) {
				$wp_user_search = new WP_User_Search( '', '', $role );
				$userIDs = $wp_user_search->get_results();
			} else {
				global $wpdb;
				$userIDs = $wpdb->get_col('
					SELECT ID
					FROM '.$wpdb->users.' INNER JOIN '.$wpdb->usermeta.'
					ON '.$wpdb->users.'.ID = '.$wpdb->usermeta.'.user_id
					WHERE '.$wpdb->usermeta.'.meta_key = \''.$wpdb->prefix.'capabilities\'
					AND '.$wpdb->usermeta.'.meta_value LIKE \'%"'.$role.'"%\'
				');				
			}
			return $userIDs;
		}
		
		function register_scripts(){
			wp_register_script('form_validate', plugins_url('/library/scripts/js/jquery.validate.min.js', __FILE__), array('jquery'));
			wp_enqueue_script('form_validate', plugins_url('/library/scripts/js/jquery.validate.min.js', __FILE__), '', '', true);
		}
		
		function print_scripts(){?>
    	<script>    
			/*jQuery(document).ready(function($){
			// validate the comment form when it is submitted
				$("#cals_psf").validate({ rules:{
											post_title: "required"
											
										  }
										});
			});*/
			/*// validate signup form on keyup and submit
			$("#signupForm").validate({
				rules: {
					firstname: "required",
					lastname: "required",
					username: {
						required: true,
						minlength: 2
					},
					password: {
						required: true,
						minlength: 5
					},
					confirm_password: {
						required: true,
						minlength: 5,
						equalTo: "#password"
					},
					email: {
						required: true,
						email: true
					},
					topic: {
						required: "#newsletter:checked",
						minlength: 2
					},
					agree: "required"
				},
				messages: {
					firstname: "Please enter your firstname",
					lastname: "Please enter your lastname",
					username: {
						required: "Please enter a username",
						minlength: "Your username must consist of at least 2 characters"
					},
					password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long"
					},
					confirm_password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long",
						equalTo: "Please enter the same password as above"
					},
					email: "Please enter a valid email address",
					agree: "Please accept our policy"
				}
			});*/
		</script>
		
		<?php }
		
		function print_admin_scripts(){?>
					
			<script type="text/javascript">
 			// [Code from CALS Posts Submission Form plugin]//
				jQuery(document).ready(function($){
					$('tr.status-pending:contains(test_cals1948)').removeClass('alternate').addClass('cals_psf_post');
					$('tr.status-pending:contains(test_cals1948) a.row-title').prepend('<span class="cals_psf_title_msg">New public post! &rarr; </span>');
					
				});			
			// [End of Code from CALS Posts Submission Form plugin]//

            </script>
        
		<?php }
		
		function print_admin_styles(){
			wp_register_style('cals_post_submissions_form_style', WP_PLUGIN_URL . '/cals_posts_submission_form/cals_post_submissions_form.css');
			wp_enqueue_style('cals_post_submissions_form_style');

		}

		function print_styles(){
			wp_register_style('cals_post_submissions_form_style', WP_PLUGIN_URL . '/cals_posts_submission_form/cals_post_submissions_form.css');
			wp_enqueue_style('cals_post_submissions_form_style');

		}


	} //End Class CALSPostsSubmissionsForm

}

//Create $cals_psf object
if(class_exists("CALSPostsSubmissionsForm")){	
	$cals_psf = new CALSPostsSubmissionsForm();
}

//Actions and Filters	
if (isset($cals_psf)) {
	
	if(is_admin()){ //what the cals_psf plugin should run only in admin section
		
		//Actions
		add_action('admin_init', array(&$cals_psf, 'print_admin_styles'));
		add_action('admin_footer', array(&$cals_psf, 'print_admin_scripts'));


	} else { //what the cals_psf plugin should run in rest of site
		
		//Actions
			
			//If post information has been sent from form and a nonce exists, attempt to process it
			if($_POST && (isset($_POST['_wpnonce']) && $_POST['_wpnonce']!='')){ 
				add_action('init', array($cals_psf, 'submit_form'));
			}
		
			//Default plugin actions
			add_action('init', array($cals_psf, 'register_scripts'));
			add_action('init', array($cals_psf, 'print_styles'));
			add_action('wp_footer', array($cals_psf, 'print_scripts'));
			//add_action('cals_psf_print_messages', array(&$cals_psf, 'print_message_log'));
			
			//check if WP_reCAPTCHA plugin is available. If so, add it to form 
			if(function_exists('display_recaptcha')){
				add_action('cals_psf_recaptcha', 'display_recaptcha');
			}
			
			
		//Shortcode
		add_shortcode('cals_questions_submissions_form', array(&$cals_psf, 'get_form'));
	}
}
?>