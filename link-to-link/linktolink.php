<?php
// Changed for non-standard location of wp-config, suggested by Alun Salt
$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root.'/wp-load.php')) {
    // WP 2.6
    require_once($root.'/wp-load.php');
  } else {
    // Before 2.6
    require_once($root.'/wp-config.php');
}
global $wpdb;
$categories = get_categories(array('type' => 'link'));
$shortcode = get_option('ltl_shortcode');
$bFirstAndSelect = 0;

$validate = htmlspecialchars(sanitize_text_field($_REQUEST['validate']), ENT_QUOTES);
$tri = htmlspecialchars(sanitize_text_field($_REQUEST['tri']), ENT_QUOTES);
$where = htmlspecialchars(sanitize_text_field($_REQUEST['where']), ENT_QUOTES);
$category = htmlspecialchars(sanitize_text_field($_REQUEST['category']), ENT_QUOTES);

if(get_option('ltl_select') == 'on' && $validate == 1 && strlen($tri)>0)
	$bFirstAndSelect = 1;
	
function pages($nb,$nbpages,$page,$where = 'all',$tri = '',$category = -1){
	global $bFirstAndSelect;
	if(strlen(sanitize_text_field($_REQUEST['validate']))>1 || $bFirstAndSelect)
		$tri = $tri;
	else $tri = '';
	if(strlen($where)==0)
		$where = 'all';
	if(strlen($category)==0)
		$category = -1;
	echo '<p>';
	echo '<span class="results">';
	if($nb==1){
		echo '1 '; _e('result','link2link'); 
	}
	elseif($nb>1){
		echo $nb.' '; _e('results','link2link'); 
	}
	echo '</span>';
	if($nbpages > 1){
		for($i = 1;$i<=$nbpages;$i++){
			if($nbpages>=8){
				if($page > 4){
					if($i == 1){
						echo '<a href="linktolink.php?validate=validate&where='.$where.'&tri='.$tri.'&category='.$category.'&page='.$i.'">&lt;&lt;</a>&nbsp;&nbsp;';
						continue;
					}
					else if($i < $page -3){ continue;}
				}
				if($page < $nbpages - 3){
					if($i == $nbpages){
						echo '<a href="linktolink.php?validate=validate&where='.$where.'&tri='.$tri.'&category='.$category.'&page='.$i.'">&gt;&gt;</a>';
						continue;									
					}
					else if($i > $page +3){ continue; }
				}
			}
			if($i == $page){ $bold1 = '<strong>'; $bold2 = '</strong>'; }
			else { $bold1 = $bold2 = ''; }
			echo '<a href="linktolink.php?validate=validate&where='.$where.'&tri='.$tri.'&category='.$category.'&page='.$i.'">'.$bold1.$i.$bold2.'</a>';
			if($i != $nbpages) echo '&nbsp;&nbsp;';
		}
	}
	echo '</p>';
}	
	
?>
<html>
  <head>
    <title>Link to Link</title>
    <script type='text/javascript' src='<?php bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.js'></script>
    <script type='text/javascript' src='<?php bloginfo('wpurl'); ?>/wp-includes/js/quicktags.js'></script>
    <script type='text/javascript' src='<?php bloginfo('wpurl'); ?>/wp-includes/js/thickbox/thickbox.js'></script>
    <script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/link-to-link/link2link.js"></script>
    <link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-admin/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-admin/wp-admin.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-admin/css/colors-fresh.css" type="text/css">
    <link rel='stylesheet' href='<?php bloginfo('wpurl'); ?>/wp-content/plugins/link-to-link/tinymce/css/link2link.css' type='text/css' />
  </head>
  <body>
    <div class="wrapper_quicktag">
      <div class="panel_wrapper">
				<div id="link_panel" class="panel<?php echo ' current'; ?>">
					<?php include('tinymce/link.php'); ?>
			  </div>
      </div>
    </div>
  </body>
</html>