		<p class="showFilter"><a href="javascript:showFilter()"><?php _e('Show filters','link2link'); ?></a></p>
		<fieldset class="filter">
			<legend><?php _e('Filter links','link2link'); ?></legend>
			<form action="" method="GET" id="fc">
				<p>
					<label for="tri"><?php _e('Content','link2link'); ?></label>
					<input type="text" name="tri" id="tri" value="<?php if(strlen($validate)>1 || $bFirstAndSelect) echo stripslashes($tri); ?>"/>
					<select name="where" id="where">
						<option value="all" <?php if($where == 'all' || strlen($where) == 0) echo 'selected="selected"'; ?>><?php _e('in all','link2link'); ?></option>
						<option value="name" <?php if($where == 'name') echo 'selected="selected"'; ?>><?php _e('in name','link2link'); ?></option>
						<option value="description" <?php if($where == 'description') echo 'selected="selected"'; ?>><?php _e('in description','link2link'); ?></option>
						<option value="notes" <?php if($where == 'notes') echo 'selected="selected"'; ?>><?php _e('in notes','link2link'); ?></option>
					</select>
					<select name="category" id="category">
					<?php foreach($categories as $cat){ if($cat->category_count == 0) continue; ?>
						<option value="<?php echo $cat->term_id; ?>" <?php if($category == $cat->term_id) echo 'selected="selected"'; ?>><?php echo $cat->name; ?></option>
					<?php } ?>
						<option value="-1" <?php if($category == -1 || strlen($category) == 0) echo 'selected="selected"'; ?>><?php _e('all categories','link2link'); ?></option>
					</select>
				</p>
				<p id="validate">
					<input type="submit" class="mceButton" name="validate" id="validate" value="<?php _e('Search','link2link'); ?>" />
					<?php if(strlen($tri)>0 && (strlen($validate)>1 || $bFirstAndSelect)){ ?><a href="linktolink.php?"><?php _e('Cancel','link2link'); ?></a><?php } ?>
					<a href="javascript:hideFilter()"><?php _e('Hide filters','link2link'); ?></a>
				</p>
			</form>
		</fieldset>
		<p>
		<?php if (get_option('ltl_cito') == 'on'): ?>
			<fieldset class="cito" >
				<legend><?php _e('Relationship','link2link'); ?></legend>
				<p>This text
		
				<select name="rel" id="rel">
					<option value="cito:cites">cites</option>
					<option value="cito:citesAsSourceDocument">cites as source document</option>
					<option value="cito:confirms">confirms</option>
					<option value="cito:discusses">discusses</option>
					<option value="cito:extends">extends</option>
					<option value="cito:obtainsBackgroundFrom">obtains background from</option>
					<option value="cito:reviews">reviews</option>
					<option value="cito:supports">supports</option>
					<option value="cito:usesDataFrom">uses data from</option>
					<option value="cito:usesMethodIn">uses method in</option>
					<option value="cito:disagreesWith">disagrees with</option>
				</select>
			
				the linked text.</p>
			</fieldset>
		<?php endif; ?>
		
<?php
$sql = $tables = '';
if(strlen($validate)>1 || $bFirstAndSelect){
	if(strlen($tri)>0){
		$mots = explode(' ',trim($tri));
		switch($where){
			case 'name':
				if(count($mots)>1){
					$sql = ' AND ';
					foreach($mots as $key=>$mot){
						if($key == 0) $sql .= ' ( ';
						else $sql .= ' AND ';
						$sql .= ' link_name LIKE "%'.WPLinkToLink::secure_sql($mot).'%" ';
						if($key == count($mots) - 1) $sql .= ' ) ';
					}
				}
				else
					$sql = ' AND link_name LIKE "%'.WPLinkToLink::secure_sql($tri).'%" ';
				
			break;
			case 'description':
				if(count($mots)>1){
					$sql = ' AND ';
					foreach($mots as $key=>$mot){
						if($key == 0) $sql .= ' ( ';
						else $sql .= ' AND ';
						$sql .= ' link_description LIKE "%'.WPLinkToLink::secure_sql($mot).'%" ';
						if($key == count($mots) - 1) $sql .= ' ) ';
					}
				}
				else
				$sql = ' AND link_description LIKE "%'.WPLinkToLink::secure_sql($tri).'%" ';
			break;
			case 'notes':
				if(count($mots)>1){
					$sql = ' AND ';
					foreach($mots as $key=>$mot){
						if($key == 0) $sql .= ' ( ';
						else $sql .= ' AND ';
						$sql .= ' link_notes LIKE "%'.WPLinkToLink::secure_sql($mot).'%" ';
						if($key == count($mots) - 1) $sql .= ' ) ';
					}
				}
				else
				$sql = ' AND link_notes LIKE "%'.WPLinkToLink::secure_sql($tri).'%" ';
			break;
			case 'all':
				if(count($mots)>1){
					$sql = ' AND ';
					foreach($mots as $key=>$mot){
						if($key == 0) $sql .= ' ( ';
						else $sql .= ' AND ';
						$sql .= ' ( link_name LIKE "%'.WPLinkToLink::secure_sql($mot).'%" OR link_description LIKE "%'.WPLinkToLink::secure_sql($mot).'%" OR link_notes LIKE "%'.WPLinkToLink::secure_sql($tri).'%") ';
						if($key == count($mots) - 1) $sql .= ' ) ';
					}
				}
				else
				$sql = ' AND ( link_name LIKE "%'.WPLinkToLink::secure_sql($tri).'%" OR link_description LIKE "%'.WPLinkToLink::secure_sql($tri).'%" OR link_notes LIKE "%'.WPLinkToLink::secure_sql($tri).'%" ) ';
			break;
		}
	}
	switch($_REQUEST['category']){
		case -1:
			
		break;
		default:
			$tables = ', '.$wpdb->terms.' as t, '.$wpdb->term_taxonomy.' as tt,'.$wpdb->term_relationships.' as tr ';
			$sql .= ' AND link_id = object_id AND t.term_id = tt.term_id AND t.term_id = "'.WPLinkToLink::secure_sql($category).'" AND tt.term_taxonomy_id = tr.term_taxonomy_id ';
	}
}
$result = $wpdb->get_results('SELECT COUNT( * ) AS num_links FROM '.$wpdb->links.$tables.' WHERE link_visible = "Y" '.$sql);
$nb = $result[0]->num_links;
$number = 10;
if(!isset($_GET['page'])){ $page = 1; }
else{ $page = $_GET['page']; }
$offset = $number * ($page-1);
$nbpages = ceil($nb/$number);
$cito = get_option('ltl_cito');
$links = $wpdb->get_results('SELECT * FROM '.$wpdb->links.$tables.' WHERE link_visible = "Y" '.$sql.' ORDER BY link_updated DESC LIMIT '.$offset.','.$number.'');
			if(count($links)>0){
				pages($nb,$nbpages,$page,$where,$tri,$category);
				echo '<ul id="links">';
				foreach($links as $link){
					$GLOBALS['link'] = $link;
					$local_link_id = $link->ID;
					$local_bookmark = get_bookmark($local_link_id);				
					echo '<li><a href="'.$local_bookmark->link_url.'" id="'.$local_link_id.'" onclick="return insertLinkLink(this,\''.$shortcode.'\',\''.$cito.'\')" >'.$local_bookmark->link_name.'</a></li>';
				}
				echo '</ul>';
			}
			else{
				?><p><span class="results"><?php _e('No link','link2link'); ?></span></p><?php	
			}
?>