<?php
if(!current_user_can('manage_options')) {
 die('Access Denied');
}

if($_GET["page"]=="outbound-link-manager/links-manager-options.php"){
    if(isset($_POST["action"])){
        $outbound_link_manager_options = get_option("outbound_link_manager_options");

        if($_POST["action"]=="Save"){
            if(isset($_POST["activate_whitelist"])){
                $whitelist_websites = explode("\r\n",$_POST["whitelist"]);
            }else{
                $whitelist_websites = array();
            }

            if(isset($_POST["activate_blacklist"])){
                $blacklist_websites = explode("\r\n",$_POST["blacklist"]);
            }else{
                $blacklist_websites = array();
            }

            foreach($whitelist_websites as $key => $whitelist_website){
                $whitelist_website = trim($whitelist_website);

                if($whitelist_website==""){
                    unset($whitelist_websites[$key]);
                }else{
                    $whitelist_websites[$key] = $whitelist_website;
                }
            }

            foreach($blacklist_websites as $key => $blacklist_website){
                $blacklist_website = trim($blacklist_website);

                if($blacklist_website==""){
                    unset($blacklist_websites[$key]);
                }else{
                    $blacklist_websites[$key] = $blacklist_website;
                }
            }

            $whitelist_websites = array_unique($whitelist_websites);
            $blacklist_websites = array_unique($blacklist_websites);

            if(isset($_POST["whitelist_action"]) && $_POST["whitelist_action"]=="remove_entirely"){
                $whitelist_action = "remove_entirely";
            }else{
                $whitelist_action = "add_nofollow";
            }

            if(isset($_POST["blacklist_action"]) && $_POST["blacklist_action"]=="remove_entirely"){
                $blacklist_action = "remove_entirely";
            }else{
                $blacklist_action = "add_nofollow";
            }

            $options = array("whitelist"=>$whitelist_websites,"whitelist_action"=>$whitelist_action,"blacklist"=>$blacklist_websites,"blacklist_action"=>$blacklist_action);

            if(isset($outbound_link_manager_options["ignored_list"])){
                $options["ignored_list"] = $outbound_link_manager_options["ignored_list"];
            }

            update_option("outbound_link_manager_options",$options);

            links_manager_check_posts_white_black_lists();

            echo('<div id="message" class="updated fade"><p><strong>Saved.</strong></p></div>');
        }elseif($_POST["action"]=="Empty ignored list"){
            unset($outbound_link_manager_options["ignored_list"]);

            update_option("outbound_link_manager_options",$outbound_link_manager_options);

            echo('<div id="message" class="updated fade"><p><strong>Ignored list is emptied.</strong></p></div>');
        }
    }
}

$outbound_link_manager_options = get_option("outbound_link_manager_options");
$whitelist_websites = "";
$blacklist_websites = "";

if(isset($outbound_link_manager_options["whitelist"]) && !empty($outbound_link_manager_options["whitelist"])){
    $whitelist_websites = implode("\n",$outbound_link_manager_options["whitelist"]);
}

if(isset($outbound_link_manager_options["blacklist"]) && !empty($outbound_link_manager_options["blacklist"])){
    $blacklist_websites = implode("\n",$outbound_link_manager_options["blacklist"]);
}
?>
<div class="wrap">
  <h2>Manage Outbound Options</h2>
  <style>
    .widefat td {
    	padding: 3px 7px;
    	vertical-align: middle;
    }

    .widefat tbody th.check-column {
    	padding: 7px 0;
        vertical-align: middle;
    }
    <?php
    if(empty($whitelist_websites)){
    ?>
    .whitelist_option{
        display: none;
    }
    <?php
    }

    if(empty($blacklist_websites)){
    ?>
    .blacklist_option{
        display: none;
    }
    <?php
    }
    ?>
  </style>
  <br />
  <form action="admin.php?page=outbound-link-manager/links-manager-options.php" method="post">
      <div>
            <div><label>Activate whitelist: <input type="checkbox" name="activate_whitelist" value="yes" onchange="if(jQuery(this).is(':checked')){ jQuery('.whitelist_option').show(); }else{ jQuery('.whitelist_option').hide(); }" <?php if(!empty($whitelist_websites)){ echo('checked="checked"'); } ?> /></label></div>
            <div><label>Activate blacklist: <input type="checkbox" name="activate_blacklist" value="yes" onchange="if(jQuery(this).is(':checked')){ jQuery('.blacklist_option').show(); }else{ jQuery('.blacklist_option').hide(); }" <?php if(!empty($blacklist_websites)){ echo('checked="checked"'); } ?> /></label></div>
      </div>
      <br />
      <hr />
      <br />
      <table width="100%" border="0">
          <tr class="whitelist_option">
            <td width="15%" rowspan="3" valign="middle"><strong>Whitelist</strong></td>
            <td width="85%">
                <textarea name="whitelist" style="width:300px; height: 200px"><?php echo($whitelist_websites); ?></textarea>
            </td>
          </tr>
          <tr class="whitelist_option">
            <td><small>Enter each domain in a new line (without http://).</small></td>
          </tr>
          <tr class="whitelist_option">
            <td>
                <label><input type="radio" name="whitelist_action" value="add_nofollow" <?php if(!isset($outbound_link_manager_options["whitelist_action"]) || $outbound_link_manager_options["whitelist_action"]=="add_nofollow"){ echo('checked="checked"'); } ?> /> Add rel="nofollow" to all links, except to domains on the whitelist.</label><br />
                <label><input type="radio" name="whitelist_action" value="remove_entirely" <?php if(isset($outbound_link_manager_options["whitelist_action"]) && $outbound_link_manager_options["whitelist_action"]=="remove_entirely"){ echo('checked="checked"'); } ?> /> Remove all links entirely, except to domains on the whitelist.</label>
            </td>
          </tr>
          <tr class="whitelist_option">
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr class="blacklist_option">
            <td width="15%" rowspan="3" valign="middle"><strong>Blacklist</strong></td>
            <td width="85%">
                <textarea name="blacklist" style="width:300px; height: 200px"><?php echo($blacklist_websites); ?></textarea>
            </td>
          </tr>
          <tr class="blacklist_option">
            <td><small>Enter each domain in a new line (without http://).</small></td>
          </tr>
          <tr class="blacklist_option">
            <td>
                <label><input type="radio" name="blacklist_action" value="add_nofollow" <?php if(!isset($outbound_link_manager_options["blacklist_action"]) || $outbound_link_manager_options["blacklist_action"]=="add_nofollow"){ echo('checked="checked"'); } ?> /> Add rel="nofollow" to all links to domains on the blacklist.</label><br />
                <label><input type="radio" name="blacklist_action" value="remove_entirely" <?php if(isset($outbound_link_manager_options["blacklist_action"]) && $outbound_link_manager_options["blacklist_action"]=="remove_entirely"){ echo('checked="checked"'); } ?> /> Remove all links entirely to domains on the blacklist.</label>
            </td>
          </tr>
      </table>
      <p class="submit">
            <input name="action" type="submit" value="Save" />
      </p>
  </form>
  <form action="admin.php?page=outbound-link-manager/links-manager-options.php" method="post">
      <p class="submit">
            <input name="action" type="submit" value="Empty ignored list" />
      </p>
  </form>
</div>