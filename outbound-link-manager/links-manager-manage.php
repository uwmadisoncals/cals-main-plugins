<?php
    if(!current_user_can('manage_options')) {
	    die('Access Denied');
    }

    if(isset($_POST["action"])){
        if(!empty($_POST["links-manager-checked-links"])){
            $links_manager_checked_links = array();

            foreach($_POST["links-manager-checked-links"] as $checked_link){
                $post_id = intval(substr($checked_link,5,strpos($checked_link,"_link_number_")-5));
                $link_number = intval(substr($checked_link,strpos($checked_link,"_link_number_")+13));

                $links_manager_checked_links[$post_id][] = $link_number;
            }

            switch($_POST["action"]){
                case "remove":

                foreach($links_manager_checked_links as $the_post_id => $link_numbers){
                    $post = get_post($the_post_id);
                    preg_match_all("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $the_post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($link_numbers as $the_link_number){
                        $new_post['post_content'] = str_replace($matches[$the_link_number][0],$matches[$the_link_number][2],$new_post['post_content']);
                    }

                    wp_update_post($new_post);
                }

                break;
                case "add_nofollow":

                foreach($links_manager_checked_links as $the_post_id => $link_numbers){
                    $post = get_post($the_post_id);
                    preg_match_all("/((<a)(.[^>]*>))(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $the_post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($link_numbers as $the_link_number){
                        if(!preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$matches[$the_link_number][1])){
                            $new_post['post_content'] = str_replace($matches[$the_link_number][0],$matches[$the_link_number][2].' rel="nofollow"'.$matches[$the_link_number][3].$matches[$the_link_number][4].$matches[$the_link_number][5],$new_post['post_content']);
                        }
                    }

                    wp_update_post($new_post);
                }

                break;
                case "remove_nofollow":

                foreach($links_manager_checked_links as $the_post_id => $link_numbers){
                    $post = get_post($the_post_id);
                    preg_match_all("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $the_post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($link_numbers as $the_link_number){
                        if(preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$matches[$the_link_number][0])){
                            $new_post['post_content'] = str_replace($matches[$the_link_number][0],preg_replace("/\s+rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i","",$matches[$the_link_number][0]),$new_post['post_content']);
                        }
                    }

                    wp_update_post($new_post);
                }

                break;
                case "save":

                foreach($links_manager_checked_links as $the_post_id => $link_numbers){
                    $post = get_post($the_post_id);
                    preg_match_all("/((<a.[^>]*>))(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $the_post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($link_numbers as $the_link_number){
                        $edited_link = stripslashes($_POST["post_".$the_post_id."_link_number_".$the_link_number."_edited_link"]);

                        if(preg_match("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$edited_link)){
                            $new_post['post_content'] = str_replace($matches[$the_link_number][0],$edited_link,$new_post['post_content']);
                        }
                    }

                    wp_update_post($new_post);
                }

                break;
            }
        }

        if(isset($_POST["posts"]) && !empty($_POST["posts"])){
            switch($_POST["action"]){
                case "ignore":

                $ignored_posts = get_option("olm_ignored_posts");

                if(!empty($ignored_posts)){
                    $ignored_posts = array_unique(array_merge($ignored_posts,$_POST["posts"]));
                }else{
                    $ignored_posts = $_POST["posts"];
                }

                update_option("olm_ignored_posts",$ignored_posts);

                break;
                case "remove_ignored":

                $ignored_posts = get_option("olm_ignored_posts");

                $ignored_posts = array_diff($ignored_posts,$_POST["posts"]);

                update_option("olm_ignored_posts",$ignored_posts);

                break;
                case "remove":

                foreach($_POST["posts"] as $post_id){
                    $post = get_post($post_id);
                    preg_match_all("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($matches as $match){
                        $new_post['post_content'] = str_replace($match[0],$match[2],$new_post['post_content']);
                    }

                    wp_update_post($new_post);
                }

                break;
                case "add_nofollow":

                foreach($_POST["posts"] as $post_id){
                    $post = get_post($post_id);
                    preg_match_all("/((<a)(.[^>]*>))(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($matches as $match){
                        if(!preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$match[1])){
                            $new_post['post_content'] = str_replace($match[0],$match[2].' rel="nofollow"'.$match[3].$match[4].$match[5],$new_post['post_content']);
                        }
                    }

                    wp_update_post($new_post);
                }

                break;
                case "remove_nofollow":

                foreach($_POST["posts"] as $post_id){
                    $post = get_post($post_id);
                    preg_match_all("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $post_id;
                    $new_post['post_content'] = $post->post_content;

                    foreach($matches as $match){
                        if(preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$match[0])){
                            $new_post['post_content'] = str_replace($match[0],preg_replace("/\s+rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i","",$match[0]),$new_post['post_content']);
                        }
                    }

                    wp_update_post($new_post);
                }

                break;
                case "save":

                foreach($_POST["posts"] as $post_id){
                    $post = get_post($post_id);
                    preg_match_all("/((<a.[^>]*>))(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

                    $new_post = array();
                    $new_post['ID'] = $post_id;
                    $new_post['post_content'] = $post->post_content;

                    $the_link_number = 0;

                    foreach($matches as $match){
                        if(!isset($_POST["post_".$post_id."_link_number_".$the_link_number."_edited_link"])){
                            $the_link_number++;

                            continue;
                        }

                        $edited_link = stripslashes($_POST["post_".$post_id."_link_number_".$the_link_number."_edited_link"]);

                        if(preg_match("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$edited_link)){
                            $new_post['post_content'] = str_replace($match[0],$edited_link,$new_post['post_content']);
                        }

                        $the_link_number++;
                    }

                    wp_update_post($new_post);
                }

                break;

            }
        }

    }
    ?>
    <div class="wrap">
      <h2>Manage Outbound Links</h2>
      <style>
        .widefat td {
        	padding: 3px 7px;
        	vertical-align: middle;
        }

        .widefat tbody th.check-column {
        	padding: 7px 0;
            vertical-align: middle;
        }
      </style>
      <script type="text/javascript">
      function links_manager_remove(post_id, link_number){
          if(window.XMLHttpRequest){
              request = new XMLHttpRequest();
          }else if(window.ActiveXObject){
              request = new ActiveXObject("Microsoft.XMLHTTP");
          }

          if(request){
              params = "action=remove&post_id="+post_id+"&link_number="+link_number+"&removed_links="+document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML;
              request.open("POST", "<?php echo($links_manager_plugin_url); ?>links-manager-actions.php", true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.setRequestHeader("Content-length", params.length);
              request.setRequestHeader("Connection", "close");
              request.onreadystatechange = function actionRequest(){
                    if(request.readyState==4){
                        if(request.status==200){
                            if(request.responseText=="error"){
                                alert("Error: Cannot remove the link.");
                            }else{
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link_div").innerHTML = "";
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = "";
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").style.height = 0;
                                if(document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML==""){
                                    document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML += link_number;
                                }else{
                                    document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML += "|"+link_number;
                                }
                            }
                        }else{
                            alert("Cannot remove the link.");
                        }
                    }else{
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link_div").innerHTML = "Removing...";
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = "Removing...";
                    }
              }
              request.send(params);
          }else{
              alert("The browser doesn't support AJAX.");
          }
      }

      function links_manager_add_nofollow(post_id, link_number){
          if(window.XMLHttpRequest){
              request = new XMLHttpRequest();
          }else if(window.ActiveXObject){
              request = new ActiveXObject("Microsoft.XMLHTTP");
          }

          if(request){
              request = new XMLHttpRequest();
              params = "action=add_nofollow&post_id="+post_id+"&link_number="+link_number+"&removed_links="+document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML;

              var blacklist_website = jQuery("#post_"+post_id+"_link_number_"+link_number+"_link_div a").attr("href").replace(/^http:\/\/|https:\/\//i, "");

              if(confirm("Would you like to add "+blacklist_website+" to the blacklist?")){
                  params += "&add_to_the_list="+blacklist_website;
              }

              request.open("POST", "<?php echo($links_manager_plugin_url); ?>links-manager-actions.php", true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.setRequestHeader("Content-length", params.length);
              request.setRequestHeader("Connection", "close");
              request.onreadystatechange = function actionRequest(){
                    if(request.readyState==4){
                        if(request.status==200){
                            if(request.responseText=="error"){
                                alert("Error: Cannot add nofollow.");
                            }else{
                                var separator = "[links-manager-separator]";
                                var the_link = request.responseText.substr(0,request.responseText.indexOf(separator));
                                var the_full_link = request.responseText.substr(request.responseText.indexOf(separator)+separator.length);
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = '<div id="post_'+post_id+'_link_number_'+link_number+'_the_link" style="display: inline">'+the_link+'</div> <span style="font-size: 10px; color: #999999">nofollow</span>';
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_full_link").innerHTML = the_full_link;
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = '<a style="cursor: pointer" onclick="links_manager_remove(\''+post_id+'\',\''+link_number+'\');">Remove</a> | <a style="cursor: pointer" onclick="links_manager_edit(\''+post_id+'\',\''+link_number+'\');">Edit</a> | <a style="cursor: pointer" onclick="links_manager_remove_nofollow(\''+post_id+'\',\''+link_number+'\');">Remove NoFollow</a>';
                            }
                        }else{
                            alert("Cannot add nofollow.");
                        }
                    }else{
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = "Adding nofollow...";
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = "Adding nofollow...";
                    }
              }
              request.send(params);
          }else{
              alert("The browser doesn't support AJAX.");
          }
      }

      function links_manager_remove_nofollow(post_id, link_number){
          if(window.XMLHttpRequest){
              request = new XMLHttpRequest();
          }else if(window.ActiveXObject){
              request = new ActiveXObject("Microsoft.XMLHTTP");
          }

          if(request){
              request = new XMLHttpRequest();
              params = "action=remove_nofollow&post_id="+post_id+"&link_number="+link_number+"&removed_links="+document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML;

              var whitelist_website = jQuery("#post_"+post_id+"_link_number_"+link_number+"_link_div a").attr("href").replace(/^http:\/\/|https:\/\//i, "");

              if(confirm("Would you like to add "+whitelist_website+" to the whitelist?")){
                  params += "&add_to_the_list="+whitelist_website;
              }

              request.open("POST", "<?php echo($links_manager_plugin_url); ?>links-manager-actions.php", true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.setRequestHeader("Content-length", params.length);
              request.setRequestHeader("Connection", "close");
              request.onreadystatechange = function actionRequest(){
                    if(request.readyState==4){
                        if(request.status==200){
                            if(request.responseText=="error"){
                                alert("Error: Cannot remove nofollow.");
                            }else{
                                var separator = "[links-manager-separator]";
                                var the_link = request.responseText.substr(0,request.responseText.indexOf(separator));
                                var the_full_link = request.responseText.substr(request.responseText.indexOf(separator)+separator.length);
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = '<div id="post_'+post_id+'_link_number_'+link_number+'_the_link" style="display: inline">'+the_link+'</div> <span style="font-size: 10px; color: #999999">follow</span>';
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_full_link").innerHTML = the_full_link;
                                document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = '<a style="cursor: pointer" onclick="links_manager_remove(\''+post_id+'\',\''+link_number+'\');">Remove</a> | <a style="cursor: pointer" onclick="links_manager_edit(\''+post_id+'\',\''+link_number+'\');">Edit</a> | <a style="cursor: pointer" onclick="links_manager_add_nofollow(\''+post_id+'\',\''+link_number+'\');">Add NoFollow</a>';
                            }
                        }else{
                            alert("Cannot remove nofollow.");
                        }
                    }else{
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = "Removing nofollow...";
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = "Removing nofollow...";
                    }
              }
              request.send(params);
          }else{
              alert("The browser doesn't support AJAX.");
          }
      }

      function links_manager_edit(post_id, link_number){
          var the_link = document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_full_link").innerHTML;

          document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_link").innerHTML = "";

          var the_link_input = document.createElement("input");
          the_link_input.type = "text";
          the_link_input.id = "post_"+post_id+"_link_number_"+link_number+"_edited_link";
          the_link_input.name = "post_"+post_id+"_link_number_"+link_number+"_edited_link";
          the_link_input.value = the_link;
          the_link_input.style.width = "70%";

          document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_link").appendChild(the_link_input);

          document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = '<a style="cursor: pointer" onclick="links_manager_save_edited(\''+post_id+'\',\''+link_number+'\');">Save</a>';
      }

      function links_manager_save_edited(post_id, link_number){
          if(window.XMLHttpRequest){
              request = new XMLHttpRequest();
          }else if(window.ActiveXObject){
              request = new ActiveXObject("Microsoft.XMLHTTP");
          }

          if(request){
              request = new XMLHttpRequest();
              params = "action=edit&post_id="+post_id+"&link_number="+link_number+"&edited_link="+document.getElementById("post_"+post_id+"_link_number_"+link_number+"_edited_link").value+"&removed_links="+document.getElementById("links_manager_post_"+post_id+"_removed_links").innerHTML;
              request.open("POST", "<?php echo($links_manager_plugin_url); ?>links-manager-actions.php", true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.setRequestHeader("Content-length", params.length);
              request.setRequestHeader("Connection", "close");
              request.onreadystatechange = function actionRequest(){
                    if(request.readyState==4){
                        if(request.status==200){
                            if(request.responseText=="error"){
                                alert("Error: Cannot edit the link.");
                            }else if(request.responseText=="invalid_link"){
                                alert("Error: Invalid link.");
                            }else{
                                var follow = request.responseText.substr(0,request.responseText.indexOf("|"));
                                var the_link = request.responseText.substr(request.responseText.indexOf("|")+1);
                                if(follow=="nofollow"){
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_full_link").innerHTML = document.getElementById("post_"+post_id+"_link_number_"+link_number+"_edited_link").value;
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = '<div id="post_'+post_id+'_link_number_'+link_number+'_the_link" style="display: inline">'+the_link+'</div> <span style="font-size: 10px; color: #999999">nofollow</span>';
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = '<a style="cursor: pointer" onclick="links_manager_remove(\''+post_id+'\',\''+link_number+'\');">Remove</a> | <a style="cursor: pointer" onclick="links_manager_edit(\''+post_id+'\',\''+link_number+'\');">Edit</a> | <a style="cursor: pointer" onclick="links_manager_remove_nofollow(\''+post_id+'\',\''+link_number+'\');">Remove NoFollow</a>';
                                }else if(follow=="follow"){
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_the_full_link").innerHTML = document.getElementById("post_"+post_id+"_link_number_"+link_number+"_edited_link").value;
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_link").innerHTML = '<div id="post_'+post_id+'_link_number_'+link_number+'_the_link" style="display: inline">'+the_link+'</div> <span style="font-size: 10px; color: #999999">follow</span>';
                                    document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = '<a style="cursor: pointer" onclick="links_manager_remove(\''+post_id+'\',\''+link_number+'\');">Remove</a> | <a style="cursor: pointer" onclick="links_manager_edit(\''+post_id+'\',\''+link_number+'\');">Edit</a> | <a style="cursor: pointer" onclick="links_manager_add_nofollow(\''+post_id+'\',\''+link_number+'\');">Add NoFollow</a>';
                                }
                            }
                        }else{
                            alert("Cannot edit the link.");
                        }
                    }else{
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_edited_link").disabled = "disabled";
                        document.getElementById("post_"+post_id+"_link_number_"+link_number+"_actions").innerHTML = "Saving...";
                    }
              }
              request.send(params);
          }else{
              alert("The browser doesn't support AJAX.");
          }
      }
      </script>
      <br />
      <?php
      $show_ignored = false;
      $ignored_posts = array();

      if(isset($_REQUEST["show_ignored"]) && $_REQUEST["show_ignored"]=="yes"){
            $show_ignored = true;
      }else{
            $ignored_posts = get_option("olm_ignored_posts");

            if($ignored_posts===false){
                $ignored_posts = array();
            }
      }

      switch($_REQUEST["sel_links_type"]){
            case "all":
            $meta_query = array("relation"=>"OR",array("key"=>"_outbound_link_exist","value"=>"yes"),array("key"=>"_internal_link_exist","value"=>"yes"));
            $sel_links_type = "all";
            break;
            case "internal":
            $meta_query = array(array("key"=>"_internal_link_exist","value"=>"yes"));
            $sel_links_type = "internal";
            break;
            default:
            $meta_query = array(array("key"=>"_outbound_link_exist","value"=>"yes"));
            $sel_links_type = "outbound";
      }
      ?>
      <form action="admin.php?page=outbound-link-manager/links-manager-manage.php" method="post">
        <div>
            <select name="sel_posts_type">
                <option value="posts">Posts</option>
                <option value="pages"<?php if($_REQUEST["sel_posts_type"]=="pages"){ echo(' selected="selected"'); }; ?>>Pages</option>
            </select>
            <select name="sel_links_type">
                <option value="all"<?php if($_REQUEST["sel_links_type"]=="all"){ echo(' selected="selected"'); }; ?>>All Links</option>
                <option value="outbound"<?php if(!isset($_REQUEST["sel_links_type"]) || $_REQUEST["sel_links_type"]=="outbound"){ echo(' selected="selected"'); }; ?>>Outbound Links</option>
                <option value="internal"<?php if($_REQUEST["sel_links_type"]=="internal"){ echo(' selected="selected"'); }; ?>>Internal Links</option>
            </select>
            <label style="margin: 0 15px;">Show ignored <input type="checkbox" name="show_ignored" value="yes" <?php if($show_ignored){ echo('checked="checked"'); } ?> /></label>
            <input type="submit" value="Apply" name="showtype" id="showtype" class="button-secondary action" />
        </div>
        <br />
        <?php
        $numberposts = 10;

        if(isset($_GET["paged"]) && is_numeric($_GET["paged"]) && intval($_GET["paged"])>0){
            $paged = intval($_GET["paged"])-1;
        }else{
            $paged = 0;
        }

        $offset = $paged*$numberposts;

        switch($_REQUEST["sel_posts_type"]){
            case "pages":
                $sel_posts_type = "pages";
                ?>
                <div class="alignleft actions">
                    <select name="action">
                        <option value="none">Bulk Actions</option>
                        <option value="remove">Remove</option>
                        <option value="save">Save</option>
                        <option value="add_nofollow">Add NoFollow</option>
                        <option value="remove_nofollow">Remove NoFollow</option>
                        <option value="ignore">Ignore Pages</option>
                        <?php if($show_ignored){ ?><option value="remove_ignored">Remove Ignored From Pages</option><?php } ?>
                    </select>
                    <input type="submit" value="Apply" name="doaction" id="doaction" class="button-secondary action" />
                </div>
                <?php
                $args =
                $posts = get_posts(array("meta_query"=>$meta_query,"post_type"=>"page","numberposts"=>$numberposts,"offset"=>$offset,"post__not_in"=>$ignored_posts));

                $all_posts = get_posts(array("meta_query"=>$meta_query,"post_type"=>"page","numberposts"=>-1,"post__not_in"=>$ignored_posts));
            break;
            default:
                $sel_posts_type = "posts";
                ?>
                <div class="alignleft actions">
                    <?php
                    $post_type = "post";

                    $arc_query = $wpdb->prepare("SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = %s ORDER BY post_date DESC", $post_type);

                    $arc_result = $wpdb->get_results($arc_query);

                    $month_count = count($arc_result);

                    if($month_count && !(1==$month_count && 0==$arc_result[0]->mmonth)){
                        $m = isset($_REQUEST['m']) ? (int)$_REQUEST['m'] : 0;
                        ?>
                        <select name='m'>
                        <option<?php selected($m, 0); ?> value='0'>Show all dates</option>
                        <?php
                        foreach($arc_result as $arc_row){
                        	if($arc_row->yyear==0)
                        		continue;
                        	$arc_row->mmonth = zeroise($arc_row->mmonth, 2);

                        	if($arc_row->yyear.$arc_row->mmonth==$m)
                        		$default = ' selected="selected"';
                        	else
                        		$default = '';

                        	echo "<option$default value='".esc_attr("$arc_row->yyear$arc_row->mmonth")."'>";
                        	echo $wp_locale->get_month($arc_row->mmonth)." $arc_row->yyear";
                        	echo "</option>\n";
                        }
                        ?>
                        </select>
                    <?php }

                    if(isset($_REQUEST["cat"]) && is_numeric($_REQUEST["cat"])){
                        $cat = intval($_REQUEST["cat"]);
                    }else{
                        $cat = 0;
                    }

                    if(is_object_in_taxonomy($post_type, 'category')){
                    	$dropdown_options = array('show_option_all' => 'View all categories', 'hide_empty' => 0, 'hierarchical' => 1, 'show_count' => 0, 'orderby' => 'name', 'selected' => $cat);
                    	wp_dropdown_categories($dropdown_options);
                    }

                    $posts = get_posts(array("cat"=>$cat,"m"=>$m,"meta_query"=>$meta_query,"numberposts"=>$numberposts,"offset"=>$offset,"post__not_in"=>$ignored_posts));

                    $all_posts = get_posts(array("cat"=>$cat,"m"=>$m,"meta_query"=>$meta_query,"numberposts"=>-1,"post__not_in"=>$ignored_posts));
                    ?>
                    <input type="submit" id="post-query-submit" value="<?php esc_attr_e('Filter'); ?>" class="button-secondary" />

                    <select name="action">
                        <option value="none">Bulk Actions</option>
                        <option value="remove">Remove</option>
                        <option value="save">Save</option>
                        <option value="add_nofollow">Add NoFollow</option>
                        <option value="remove_nofollow">Remove NoFollow</option>
                        <option value="ignore">Ignore Posts</option>
                        <?php if($show_ignored){ ?><option value="remove_ignored">Remove Ignored From Posts</option><?php } ?>
                    </select>
                    <input type="submit" value="Apply" name="doaction" id="doaction" class="button-secondary action" />
                </div>
        <?php
        }
        ?>
        <br /><br />
        <table class="widefat fixed" cellspacing="0">
        	<thead>
        	<tr>
            <th scope="col" id="cb" class="check-column" style=""><input type="checkbox" /></th>
        	<th scope="col" style="width: 15%">Post</th>
            <?php
            if($sel_posts_type=="pages"){
            ?>
            <th scope="col" style="width: 15%">Author</th>
            <th scope="col" style="width: 15%">Date</th>
            <?php
            }else{
            ?>
            <th scope="col" style="width: 10%">Author</th>
            <th scope="col" style="width: 10%">Categories</th>
            <th scope="col" style="width: 10%">Date</th>
            <?php
            }
            ?>
            <th scope="col" style="width: 35%">Links</th>
            <th scope="col" style="width: 20%">Actions</th>
        	</tr>
        	</thead>

        	<tfoot>
        	<tr>
            <th scope="col" class="check-column"><input type="checkbox" /></th>
        	<th scope="col">Post</th>
        	<th scope="col">Author</th>
            <?php
            if($sel_posts_type=="posts"){
            ?>
            <th scope="col">Categories</th>
            <?php
            }
            ?>
            <th scope="col">Date</th>
            <th scope="col">Links</th>
            <th scope="col">Actions</th>
        	</tr>
        	</tfoot>

        	<tbody>
            <?php
            foreach($posts as $post){
            !preg_match_all("/(<a.[^>]*>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER);

            foreach($matches as $key => $value){
                preg_match("/href\s*=\s*[\'|\"]\s*([^\"|\']*)\s*[\'|\"]/i",$value[1],$href);

                if($sel_links_type!="all"){
                    if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
                        if($sel_links_type=="outbound"){
                            unset($matches[$key]);
                        }
                    }else{
                        if($sel_links_type=="internal"){
                            unset($matches[$key]);
                        }
                    }
                }
            }
            ?>
        	<tr class='alternate' valign="top">
                <td align="center" scope="row" class="check-column" style="vertical-align: middle; padding: 0px;"><input type="checkbox" name="posts[]" value="<?php echo($post->ID); ?>" /></td>
        		<td>
                    <div>
                        <a href="post.php?post=<?php echo($post->ID); ?>&action=edit"><?php echo($post->post_title); ?></a>
                    </div>
        		</td>
                <td>
                    <div>
                        <?php
                        $user_info = get_userdata($post->post_author);
                        echo($user_info->user_login);
                        ?>
                    </div>
        		</td>
                <?php
                if($sel_posts_type=="posts"){
                ?>
                <td>
                    <div>
                        <?php
                        $the_categories = get_the_category($post->ID);
                        $categories = "";
                        $first_category = true;

                        foreach($the_categories as $the_category){
                            if(!$first_category){
                                $categories .= ", ";
                            }

                            $categories .= $the_category->name;

                            $first_category = false;
                        }

                        echo($categories);
                        ?>
                    </div>
        		</td>
                <?php
                }
                ?>
                <td>
                    <div>
                        <?php
                        echo(mysql2date("Y/m/d", $post->post_date));
                        ?>
                    </div>
                    <div>
                        <?php
                        if($post->post_status=="publish"){
                            echo("published");
                        }else{
                            echo($post->post_status);
                        }
                        ?>
                    </div>
        		</td>
                <td>
                    <div>
                        <?php
                        if(!empty($matches)){
                            foreach($matches as $link_number => $match){
                            ?>
                                <div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_link_div"><div style="display: inline;"><input type="checkbox" name="links-manager-checked-links[]" value="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>" /></div> <div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_link" style="display: inline"><div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_the_link" style="display: inline"><?php if(preg_match("/<img\s*.[^>]*src\s*=\s*[\"|\']([a-z0-9\.\-;:\/\?&=_|\r|\n]{1,})[\"|\']/isxmU",$match[0],$imgs)){ echo($match[1].substr($imgs[1],strrpos($imgs[1],"/")+1)."</a>"); }else{ echo($match[0]); } ?></div><?php if(preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$match[1])){ echo(' <span style="font-size: 10px; color: #999999">nofollow</span>'); }else{ echo(' <span style="font-size: 10px; color: #999999">follow</span>'); } ?></div><div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_the_full_link" style="display: none"><?php echo($match[0]); ?></div><div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_displaying_link_content" style="display: none"><?php if(preg_match("/<img\s*.[^>]*src\s*=\s*[\"|\']([a-z0-9\.\-;:\/\?&=_|\r|\n]{1,})[\"|\']/isxmU",$match[2],$imgs)){ echo(substr($imgs[1],strrpos($imgs[1],"/")+1)); }else{ echo($match[2]); } ?></div></div>
                            <?php
                            }
                        }else{
                            echo("There is no outbound link.");
                        }
                        ?>
                    </div>
        		</td>
                <td>
                    <?php
                    if(!empty($matches)){
                        foreach($matches as $link_number => $match){
                        ?>
                            <div id="post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_actions"><a style="cursor: pointer" onclick="links_manager_remove('<?php echo($post->ID); ?>','<?php echo($link_number) ?>');">Remove</a> | <a style="cursor: pointer" onclick="links_manager_edit('<?php echo($post->ID); ?>','<?php echo($link_number) ?>');">Edit</a> | <a style="cursor: pointer" onclick="<?php if(preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$match[1])){ echo('links_manager_remove_nofollow'); }else{ echo('links_manager_add_nofollow'); } ?>('<?php echo($post->ID); ?>','<?php echo($link_number) ?>');"><?php if(preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$match[1])){ echo('Remove'); }else{ echo('Add'); } ?> NoFollow</a></div>
                        <script type="text/javascript">
                            document.getElementById("post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_actions").style.height = document.getElementById("post_<?php echo($post->ID); ?>_link_number_<?php echo($link_number); ?>_link_div").offsetHeight+"px";
                        </script>
                            <div id="links_manager_post_<?php echo($post->ID); ?>_removed_links" style="display: none"></div>
                        <?php
                        }
                    }
                    ?>
        		</td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
      </form>
      <?php
      if(count($all_posts)>$numberposts){
      ?>
          <div class="tablenav">
            <div class="tablenav-pages">
                <span class="displaying-num">Displaying <?php echo($offset+1); ?>&#8211;<?php if(count($all_posts)>($offset+$numberposts)){ echo($offset+$numberposts); }else{ echo(count($all_posts)); } ?> of <?php echo(count($all_posts)); ?></span>
                <?php
                $vars_query = "";
                $vars_query .= (isset($sel_posts_type) && $sel_posts_type!="") ? "&sel_posts_type=".$sel_posts_type : "";
                $vars_query .= (isset($sel_links_type) && $sel_links_type!="") ? "&sel_links_type=".$sel_links_type : "";
                $vars_query .= ($show_ignored) ? "&show_ignored=yes" : "";
                $vars_query .= (isset($cat) && $cat!="") ? "&cat=".$cat : "";
                $vars_query .= (isset($m) && $m!="") ? "&m=".$m : "";

                if($offset!=0){
                    echo("<a class='prev page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$paged."'>&laquo;</a>");
                }

                $pages = count($all_posts)/$numberposts;

                if($pages<5){
                    for($i=0; $i<$pages; $i++){
                        if($paged==$i){
                            echo("<span class='page-numbers current'>".($i+1)."</span>");
                        }else{
                            echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".($i+1)."'>".($i+1)."</a>");
                        }
                    }
                }else{
                    if($paged<=2){
                        for($i=1; $i<=3; $i++){
                            if($paged==($i-1)){
                                echo("<span class='page-numbers current'>".$i."</span>");
                            }else{
                                echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$i."'>".$i."</a>");
                            }
                        }
                        echo(" ... <a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$pages."'>".$pages."</a>");
                    }elseif($paged>=$pages-3){
                        echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=1'>1</a> ... ");
                        for($i=$pages-2; $i<=$pages; $i++){
                            if($paged==($i-1)){
                                echo("<span class='page-numbers current'>".$i."</span>");
                            }else{
                                echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$i."'>".$i."</a>");
                            }
                        }
                    }else{
                        echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=1'>1</a> ... ");
                        for($i=$paged; $i<=$paged+2; $i++){
                            if($paged==($i-1)){
                                echo("<span class='page-numbers current'>".$i."</span>");
                            }else{
                                echo("<a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$i."'>".$i."</a>");
                            }
                        }
                        echo(" ... <a class='page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".$pages."'>".$pages."</a>");
                    }
                }

                if(count($all_posts)>$offset+$numberposts){
                    echo("<a class='next page-numbers' href='admin.php?page=outbound-link-manager/links-manager-manage.php".$vars_query."&paged=".($paged+2)."'>&raquo;</a>");
                }
                ?>
            </div>
          </div>
      <?php
      }
      ?>
</div>