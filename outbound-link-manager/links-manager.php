<?php
/*
Plugin Name: Outbound Link Manager
Plugin URI: http://www.searchmatters.com.au/wp-plugins/outbound-link-manager/
Description: The Outbound Link Manager monitors outbound links in your posts and pages, easily allowing you to add or remove a nofollow tag, update anchor texts, or remove links altogether.
Version: 1.2
Author: Morris Bryant, Ruben Sargsyan
Author URI: http://www.searchmatters.com.au
*/

/*  Copyright 2011 Morris Bryant (email: business@searchmatters.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

$links_manager_plugin_url = WP_PLUGIN_URL."/".str_replace(basename(__FILE__),"",plugin_basename(__FILE__));
$links_manager_plugin_title = "Outbound Link Manager";
$links_manager_plugin_prefix = "outbound_link_manager_";
$links_manager_table_name = $wpdb->prefix."links_manager";

function links_manager_menu(){
    if(function_exists('add_menu_page')){
        add_menu_page("Manage", "Links Manager", "manage_options", "outbound-link-manager/links-manager-manage.php");
	}
	if(function_exists('add_submenu_page')){
	    add_submenu_page('outbound-link-manager/links-manager-manage.php', 'Manage', 'Manage',  'manage_options', 'outbound-link-manager/links-manager-manage.php');
	    add_submenu_page('outbound-link-manager/links-manager-manage.php', 'Options', 'Options',  'manage_options', 'outbound-link-manager/links-manager-options.php');
	}    
}

function links_manager_scan_posts(){
    $posts = get_posts(array("post_type"=>array("post","page"),"numberposts"=>-1));

    foreach($posts as $post){
        if(!preg_match_all("/(<a(.[^>]*)>)(.[^<]*)(<\/a>)/ismU",$post->post_content,$matches,PREG_SET_ORDER)){
            continue;
        }

        $does_outbound_link_exist = false;
        $does_internal_link_exist = false;

        foreach($matches as $key => $value){
            preg_match("/href\s*=\s*[\'|\"]\s*(.[^\'|\"]*)\s*[\'|\"]/i",$value[1],$href);

            if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
                $does_internal_link_exist = true;
            }else{
                $does_outbound_link_exist = true;
            }
        }

        if($does_outbound_link_exist){
            add_post_meta($post->ID, "_outbound_link_exist", "yes", true);
        }

        if($does_internal_link_exist){
            add_post_meta($post->ID, "_internal_link_exist", "yes", true);
        }
    }
}

function links_manager_delete_meta_keys(){
    $posts = get_posts('numberposts=-1');

    foreach($posts as $post){
        delete_post_meta($post->ID, "_outbound_link_exist");
        delete_post_meta($post->ID, "_internal_link_exist");
    }

    $pages = get_pages();

    foreach($pages as $page){
        delete_post_meta($page->ID, "_outbound_link_exist");
        delete_post_meta($page->ID, "_internal_link_exist");
    }
}

function links_manager_check_posts_white_black_lists(){
    $meta_query = array("relation"=>"OR",array("key"=>"_outbound_link_exist","value"=>"yes"),array("key"=>"_internal_link_exist","value"=>"yes"));

    $posts = get_posts(array("meta_query"=>$meta_query,"post_type"=>array("post","page"),"numberposts"=>-1));

    foreach($posts as $post){
        wp_update_post($post);
    }
}

function links_manager_check_post_white_black_lists($post){
    $post["post_content"] = stripslashes($post["post_content"]);

    $outbound_link_manager_options = get_option("outbound_link_manager_options");

    $whitelist_websites = array();

    if(isset($outbound_link_manager_options["whitelist"])){
        $whitelist_websites = $outbound_link_manager_options["whitelist"];
    }

    $blacklist_websites = array();

    if(isset($outbound_link_manager_options["blacklist"])){
        $blacklist_websites = $outbound_link_manager_options["blacklist"];
    }

    $ignored_list = array();

    if(isset($outbound_link_manager_options["ignored_list"])){
        $ignored_list = $outbound_link_manager_options["ignored_list"];
    }

    if(preg_match_all("/(<a(.[^>]*)>)(.[^<]*)(<\/a>)/ismU",$post["post_content"],$matches,PREG_SET_ORDER)){
        foreach($matches as $key => $value){
            preg_match("/href\s*=\s*[\'|\"]\s*(.[^\'|\"]*)\s*[\'|\"]/i",$value[1],$href);

            if(isset($ignored_list[$post["post_name"]]) && in_array(preg_replace("/^http(s*):\/\//i","",$href[1]),$ignored_list[$post["post_name"]])){
                continue;
            }

            if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
                continue;
            }

            if(!empty($whitelist_websites)){
                $whitelist_websites_pattern = array();

                foreach($whitelist_websites as $whitelist_website){
                    $whitelist_websites_pattern[] = "(^http(s*):\/\/".str_replace("/","\/",$whitelist_website).")";
                }

                if(!preg_match("/".implode("|",$whitelist_websites_pattern)."/ismU",$href[1])){
                    if(!isset($outbound_link_manager_options["whitelist_action"]) || $outbound_link_manager_options["whitelist_action"]=="add_nofollow"){
                        if(!preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$value[0])){
                            $post["post_content"] = str_replace($value[0],preg_replace('/<a/i','$0'.' rel="nofollow"',$value[0]),$post["post_content"]);
                        }
                    }else{
                        $post["post_content"] = str_replace($value[0],$value[3],$post["post_content"]);
                    }
                }
            }

            if(!empty($blacklist_websites)){
                foreach($blacklist_websites as $blacklist_website){
                    if(preg_match("/^http(s*):\/\/".str_replace("/","\/",$blacklist_website)."/ismU",$href[1])){
                        if(!isset($outbound_link_manager_options["blacklist_action"]) || $outbound_link_manager_options["blacklist_action"]=="add_nofollow"){
                            if(!preg_match("/rel\s*=\s*[\'|\"]\s*nofollow\s*[\'|\"]/i",$value[0])){
                                $post["post_content"] = str_replace($value[0],preg_replace('/<a/i','$0'.' rel="nofollow"',$value[0]),$post["post_content"]);
                            }
                        }else{
                            $post["post_content"] = str_replace($value[0],$value[3],$post["post_content"]);
                        }
                    }
                }
            }
        }
    }

    return $post;
}

function links_manager_scan_post($post){
    $the_post = get_post($post);
    $does_outbound_link_exist = false;
    $does_internal_link_exist = false;

    if(preg_match_all("/(<a(.[^>]*)>)(.[^<]*)(<\/a>)/ismU",$the_post->post_content,$matches,PREG_SET_ORDER)){
        foreach($matches as $key => $value){
            preg_match("/href\s*=\s*[\'|\"]\s*(.[^\'|\"]*)\s*[\'|\"]/i",$value[1],$href);

            if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
                $does_internal_link_exist = true;
            }else{
                $does_outbound_link_exist = true;
            }
        }
    }

    if($does_outbound_link_exist){
        add_post_meta($the_post->ID, "_outbound_link_exist", "yes", true);
    }else{
        delete_post_meta($the_post->ID, "_outbound_link_exist");
    }

    if($does_internal_link_exist){
        add_post_meta($the_post->ID, "_internal_link_exist", "yes", true);
    }else{
        delete_post_meta($the_post->ID, "_internal_link_exist");
    }
}

function links_manager_scan_page($page){
    $the_page = get_page($page);

    $does_outbound_link_exist = false;
    $does_internal_link_exist = false;

    if(preg_match_all("/(<a(.[^>]*)>)(.[^<]*)(<\/a>)/ismU",$the_page->post_content,$matches,PREG_SET_ORDER)){
        foreach($matches as $key => $value){
            preg_match("/href\s*=\s*[\'|\"]\s*(.[^\'|\"]*)\s*[\'|\"]/i",$value[1],$href);

            if((substr($href[1],0,7)!="http://" && substr($href[1],0,8)!="https://") || substr($href[1],0,strlen(get_bloginfo("url")))==get_bloginfo("url")){
                $does_internal_link_exist = true;
            }else{
                $does_outbound_link_exist = true;
            }
        }
    }

    if($does_outbound_link_exist){
        add_post_meta($the_page->ID, "_outbound_link_exist", "yes", true);
    }else{
        delete_post_meta($the_page->ID, "_outbound_link_exist");
    }

    if($does_internal_link_exist){
        add_post_meta($the_page->ID, "_internal_link_exist", "yes", true);
    }else{
        delete_post_meta($the_page->ID, "_internal_link_exist");
    }
}

wp_enqueue_script("jquery");
add_action("admin_menu", "links_manager_menu");
register_activation_hook(__FILE__, "links_manager_scan_posts");
register_deactivation_hook(__FILE__, "links_manager_delete_meta_keys");
add_filter("wp_insert_post_data","links_manager_check_post_white_black_lists",99);
add_action("publish_post","links_manager_scan_post");
add_action("edit_post","links_manager_scan_post");
add_action("publish_page","links_manager_scan_page");
add_action("edit_page_form","links_manager_scan_page");
?>