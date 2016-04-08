<?php
/*
Plugin Name: Insert RSS Thumbnails
Plugin URI: http://bonplangratos.fr/
Description: This plugin will add the first image from the current post to your RSS feed. This way, your feed should have an image for every post, which will make your RSS easier & more fun to read (Mashable & LifeHacker for example use this technique).
Version: 1.0
Author: Bruno D
Author URI: http://bonplangratos.fr/
*/

$post_image_options = array(
		'insert_rss_thumbnails_metadata' => 'thumbnail',
		'insert_rss_thumbnails_url' => 'https://lh5.googleusercontent.com/_tEEtU45EJNQ/RnbYbFRqiCI/AAAAAAAABrY/-wpjJ9RJIvk/s128/Kirei_desu_by_tumb.jpg',
		'insert_rss_thumbnails_path' => '',
		'insert_rss_thumbnails_cb_path' => 'checked',
		'insert_rss_thumbnails_cb_autodetect' => 'checked',
		'insert_rss_thumbnails_cb_url' => 'checked',
	    	);

add_option('insert_rss_thumbnails_setting', $post_image_options);
add_action('admin_menu', 'insert_rss_thumbnails_menu');

function insert_rss_thumbnails_menu()
{
    add_submenu_page('options-general.php', 'Edit Insert RSS Thumbnails', 'Insert RSS Thumbnails', 'administrator', 'rss-post-edit', 'insert_rss_thumbnails_options');
    add_action('admin_init', 'register_options_insert_rss_thumbnails');
}

function register_options_insert_rss_thumbnails()
{
    register_setting('rss-post-image-setting', $post_image_options);
}

function insert_rss_thumbnails_options()
{
    if( isset($_POST['info_update']) )
    {
        $new_options = $_POST['options'];
        update_option('insert_rss_thumbnails_setting', $new_options);
        echo '<div id="message" class="updated fade"><p><strong>' . __('RSS Feed Settings SAVED...') . '</strong></p></div>';
    }
    $def_options = get_option('insert_rss_thumbnails_setting');
    ?>
	<div class="wrap">
	<h2>Insert RSS Thumbnails Options</h2>

	<form method='POST'>
 	   <?php settings_fields('rss-post-image-setting'); ?>
 	   <table class='form-table'>
	    <tr valign='top'>
	          <th scope='row'>Custom metadata field</th>
			  <td>
	          <input type='text' name='options[insert_rss_thumbnails_metadata]' size='50' value='<?php esc_attr_e($def_options['insert_rss_thumbnails_metadata']) ?>' /><br />
			  The name of the custom meta data field used to input the path of your thumbnail in each post (default value: 'thumbnail').<br />
			  </td>
 	    </tr>
	   <tr valign='top'>
	          <th scope='row'>Relative thumbs path</th>
			  <td>
			  <input type="checkbox" name='options[insert_rss_thumbnails_cb_path]' <?php echo ($def_options['insert_rss_thumbnails_cb_path']) ? "checked" : "" ;?>/> Insert relative path<br />
	          <input type='text' name='options[insert_rss_thumbnails_path]' size='50' value='<?php esc_attr_e($def_options['insert_rss_thumbnails_path']) ?>' /><br />
			  This option must be used if you are using <u>relative</u> thumbs URL in the custom metadata fields of your posts: if you input relative URL in the 'thumbnail' meta-data field (<i>eg: /content/media/image01.jpg</i>), then you must specify the path here (<i>eg: http://www.yourdomaine.com</i>).<br />
			  </td>
 	    </tr>
	    <tr valign='top'>
	          <th scope='row'>Auto-detect images</th>
			  <td>
			  <input type="checkbox" name='options[insert_rss_thumbnails_cb_autodetect]' <?php echo ($def_options['insert_rss_thumbnails_cb_autodetect']) ? "checked" : "" ;?>/> Select random image if no thumbs<br />	          
			  Try to find an image in the article if the meta-data field is blank.
			  </td>
 	    </tr>

	    <tr valign='top'>
	          <th scope='row'>Default thumb URL</th>
			  <td>
			  <input type="checkbox" name='options[insert_rss_thumbnails_cb_url]' <?php echo ($def_options['insert_rss_thumbnails_cb_url']) ? "checked" : "" ;?>/> Display the above default image if nothing found<br />	          
	          <input type='text' name='options[insert_rss_thumbnails_url]' size='100' value='<?php esc_attr_e($def_options['insert_rss_thumbnails_url']) ?>' /><br />
  			  Use default thumbnail, if both meta-data field and image in article could no be found.<br />
			  <br />
			  <img src="<? esc_attr_e($def_options['insert_rss_thumbnails_url']) ?>" border="1" width="120px" height="80px" />
			  
			  </td>
 	        </tr>

		</table>
           <input type='hidden' name='options[img_align]' value='left' />
       <p class='submit'>
       <input type='submit' class='button-primary' name='info_update' value='<?php _e('Update Options', 'options') ?>' />
       </p>
</form>
</div>
<?
}

if( !function_exists( 'insert_rss_thumbnails' ) ) {
	function insert_rss_thumbnails()
	{
		global $post;
		$options   = get_option('insert_rss_thumbnails_setting');
		
		// Get thumbnail
		$metadata = $options['insert_rss_thumbnails_metadata'];
		$values = get_post_custom_values($metadata, $post->id);
		$image_link = $values[0];

		// Get relative path
		if(!empty($options['insert_rss_thumbnails_cb_path'])) {
		$domain_link = $options['insert_rss_thumbnails_path'];
		} else {
			$domain_link = "";
		}
		/*$domain_link = $options['insert_rss_thumbnails_path'];*/
		$thumb = $domain_link.$image_link;
		
        // Detect image
		if(!empty($options['insert_rss_thumbnails_cb_autodetect']) and empty($image_link)) {
		$output    = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$thumb = $matches [1] [0];
		}

		if(!empty($options['insert_rss_thumbnails_cb_url']) and empty($image_link) and empty($output)) {
			// Sets a default image to display if no image found in post
			$thumb = $options['insert_rss_thumbnails_url'];
		}

		// Sets image properties for display purposes
		//$thumb = '<a href="'.get_permalink($post->id).'" alt="'.$post->post_title.'"><img src="'.$thumb.'" align="'.$options['img_align'].'" alt="'.$post->post_title.'" hspace="5" vspace="5" border="0" /></a>';

		// Must use print() for displaying in feed, substr to cut out a trailing character
		//$thumb = print $thumb;

		$enclosure = "<enclosure url='".$thumb."' length='2854' type='image/jpeg' />";

		echo $enclosure;
		return $enclosure;
	}

	add_action('atom_entry', 'insert_rss_thumbnails');
	add_action('rdf_item', 'insert_rss_thumbnails');
	add_action('rss_item', 'insert_rss_thumbnails');
	add_action('rss2_item', 'insert_rss_thumbnails');
}

?>