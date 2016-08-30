
 
  <div class="wrap">
        <h1>Team Members Settings</h1>
        <form action="" method="post">
        	<h2>Team Page label</h2>
            <p>This is the text that appears as the title on the <a href="<?php echo get_bloginfo('wpurl') . '/team'; ?>">page displaying all team members</a></p>
        	<input type="text" name="archive-team-title" value="<?php archive_team_title(); echo ct_settings_options_get(); ?>">
        	<input type="submit" value="Submit">
        </form>

        <?php 
        		function archive_team_title(){

	        		if(isset($_POST['archive-team-title'])){

	        			$safe_str_title = sanitize_text_field( $_POST['archive-team-title']);

		        		if(!empty($safe_str_title)){

		        			ct_settings_options_update($safe_str_title); 

		        		}else{
		        			echo "<br/> The field is empty. Please enter a value and resubmit. ";
		        		}
	        		}  	
        		}
           ?>
</div>
