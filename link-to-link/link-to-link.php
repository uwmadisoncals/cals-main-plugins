<?php
/*
Plugin Name: Link to link
Plugin URI: http://wordpress.org/extend/plugins/link-to-link/
Author: Martin Fenner
Author URI: http://blogs.plos.org/mfenner
Version: 1.1.2
Description: This plugin allows you to easily create a link to an existing link from the Links Manager.

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

This code is based on the Link to Post Plugin by Julien Appert: http://wordpress.org/extend/plugins/link-to-post/
*/

class WPLinkToLink{

	function WPLinkToLink(){$this->__construct();}
		
	function __construct(){
		add_action('init', array(&$this,'init'));
		add_action('admin_menu', array(&$this,'adminpage'));
		add_action('admin_print_scripts',array(&$this,'adminjavascript'));
		add_action( 'edit_form_advanced', array(&$this,'quicktags'));
		add_action( 'edit_page_form', array(&$this,'quicktags'));	
		add_shortcode('link2link', array($this,'shortcode_func'));
	}
	
	function secure_sql($sql){
		return mysql_real_escape_string($sql);
	}	

	function shortcode_func($atts, $content = null) {
		$permalink = get_permalink($atts['id']);
		$title = get_the_title($atts['id']);
		return '<a href="'.$permalink.'" title="'.$title.'">'.$content.'</a>';
	}
	
	function option($name,$value){
		if(strlen($value)==0) $value = 'off';
		if(get_option($name) == FALSE)
			add_option($name,$value);
		else
			update_option($name,$value);
	}

	function init(){
		$locale = get_locale ();
		if ( empty($locale) )
			$locale = 'en_US';

		$mofile = dirname (__FILE__)."/locale/$locale.mo";
		load_textdomain ('link2link', $mofile);
		$this->addbuttons();
	}

	function addbuttons() {
	   // Don't aller doing this stuff if the current user lacks permissions
	   if ( ! current_user_can('edit_links') && ! current_user_can('edit_pages') )
		 return;
	 
	   // Add only in Rich Editor mode
	   if ( get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", array(&$this,"add_tinymce_plugin"));
			add_filter('mce_buttons', array(&$this,'register_button'));
	   }
	}
 
	function register_button($buttons) {
	   array_push($buttons, "separator", "link_link");
	   return $buttons;
	}
 
	function add_tinymce_plugin($plugin_array) {
	   $plugin_array['link2link'] = get_bloginfo('wpurl').'/wp-content/plugins/link-to-link/tinymce/editor_plugin.js';
	   return $plugin_array;
	}

	function adminjavascript(){
		?>
		<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/link-to-link/link2link.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		function edlinktolink() {
			var content = getContentSelection(window);
		   tb_show("<?php _e('Link a link','link2link'); ?>","<?php bloginfo('wpurl'); ?>/wp-content/plugins/link-to-link/linktolink.php?tri="+content+"&amp;validate=1&amp;where=all&amp;category=-1&amp;TB_iframe=true",false);
		}
		//]]>
		</script>	
		<?php
	}

	function quicktags(){
		$buttonshtml = '<input type="button" class="ed_button" onclick="edlinktolink(); return false;" title="' . __('Link a link','link2link') . '" value="' . __('Link a link','link2link') . '" />';
		?>
		<script type="text/javascript" charset="utf-8">
		// <![CDATA[
		   (function(){
			  if (typeof jQuery === 'undefined') {
				 return;
			  }
			  jQuery(document).ready(function(){
				 jQuery("#ed_toolbar").append('<?php echo $buttonshtml; ?>');
			  });
		   }());
		// ]]>
		</script>
		<?php
	}

	function adminpage() {	
		add_links_page('Link to Link', 'Link to Link', 8, __FILE__, array(&$this,'optionpage'));	
	}

	function optionpage(){
		if(isset($_POST['Submit'])){
			$this->option('ltl_select',$_POST['select']);
			$this->option('ltl_shortcode',$_POST['shortcode']);
			$this->option('ltl_cito',$_POST['cito']);
		}
		$select = get_option('ltl_select');
		$shortcode = get_option('ltl_shortcode');
		$cito = get_option('ltl_cito');
		?>
		<div class="wrap">
		  <div id="icon-link-manager" class="icon32" ><br/></div>
			<h2><?php echo _e('Link to Link Options','link2link'); ?></h2>
			
				<form action="" method="post">
					<p>
						<input type="checkbox" name="select" id="select" <?php if($select == 'on') echo 'checked="checked"'; ?>/>
						<label for="select"><?php _e('Search with selected text','link2link'); ?></label>
					</p>
					<p>
						<input type="checkbox" name="shortcode" id="shortcode" <?php if($shortcode == 'on') echo 'checked="checked"'; ?>/>
						<label for="shortcode"><?php _e('Use shortcode for kcite plugin','link2link'); ?> (<a href="http://wordpress.org/extend/plugins/kcite/">kcite</a>)</label>
					</p>
					<p>
						<input type="checkbox" name="cito" id="cito" <?php if($cito == 'on') echo 'checked="checked"'; ?>/>
						<label for="cito"><?php _e('Describe the nature of links using the Citation Typing Ontology','link2link'); ?> (<a href="http://imageweb.zoo.ox.ac.uk/pub/2009/citobase/cito-20091124-1.4/cito-content/owldoc/">CiTO</a>)</label>
					</p>					
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php _e('Save changes','link2link'); ?>" />
					</p>
				</form>
		</div>
		<?php
	}
 
}

new WPLinkToLink();

?>