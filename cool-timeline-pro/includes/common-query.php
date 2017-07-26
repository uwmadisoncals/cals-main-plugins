<?php
 /*
   Common code for both vertical and horizontal
  */
               $args = array();
               $cat_timeline = array();
               $based=$attribute['based'];
  if( $attribute['post-type']==='' && empty( $attribute['post-type']))
   {
                 $ctl_options_arr = get_option('cool_timeline_options');
                $default_icon = isset($ctl_options_arr['default_icon'])?$ctl_options_arr['default_icon']:'';
                $ctl_post_per_page = $ctl_options_arr['post_per_page'];
                $story_desc_type = $ctl_options_arr['desc_type'];
                // $ctl_no_posts = isset($ctl_options_arr['no_posts']) ? $ctl_options_arr['no_posts'] : "No timeline story found";
                $ctl_content_length = $ctl_options_arr['content_length'];
                $ctl_posts_orders = $ctl_options_arr['posts_orders'] ? $ctl_options_arr['posts_orders'] : "DESC";
                $disable_months = isset($ctl_options_arr['disable_months']) ? $ctl_options_arr['disable_months'] : "no";
                $title_alignment = $ctl_options_arr['title_alignment'] ? $ctl_options_arr['title_alignment'] : "center";

                $title_visibilty = $ctl_options_arr['display_title'] ? $ctl_options_arr['display_title'] : "yes";

                $slider_animation = $ctl_options_arr['slider_animation'] ? $ctl_options_arr['slider_animation'] : "slide";
                $ctl_slideshow = $ctl_options_arr['ctl_slideshow'] ? $ctl_options_arr['ctl_slideshow'] : true;
                $animation_speed = isset($ctl_options_arr['animation_speed']) ? $ctl_options_arr['animation_speed'] : 7000;
                //$ctl_posts_order='date';

                $enable_navigation = $ctl_options_arr['enable_navigation'] ? $ctl_options_arr['enable_navigation'] : 'yes';
                $navigation_position = $ctl_options_arr['navigation_position'] ? $ctl_options_arr['navigation_position'] : 'right';

                $enable_pagination = $ctl_options_arr['enable_pagination'] ? $ctl_options_arr['enable_pagination'] : 'no';      
                $timeline_skin = isset($attribute['skin']) ? $attribute['skin'] : 'default';
            $active_design=$attribute['designs']?$attribute['designs']:'default';
            $wrp_cls = '';
            $wrapper_cls = '';
            $post_skin_cls = '';
            if ($timeline_skin == "light") {
              $wrp_cls = 'light-timeline';
              $wrapper_cls = 'light-timeline-wrapper';
                $post_skin_cls = 'white-post';
            } else if ($timeline_skin == "dark") {
              $wrp_cls = 'dark-timeline';
                $wrapper_cls = 'dark-timeline-wrapper';
                $post_skin_cls = 'black-post';
            } else {
               $wrp_cls = 'white-timeline';
                $post_skin_cls = 'light-grey-post';
                $wrapper_cls = 'white-timeline-wrapper';
            }
            $story_content=$attribute['story-content'];
          $stories_images_link = $ctl_options_arr['stories_images'];

             if(!empty($attribute['date-format'])){
              
              if($attribute['date-format']=="default"){
                  $date_formats = $ctl_options_arr['ctl_date_formats'] ? $ctl_options_arr['ctl_date_formats'] : "M d";
                }else if($attribute['date-format']=="custom"){
                 
                 if ($ctl_options_arr['custom_date_style']=="yes" && !empty($ctl_options_arr['custom_date_formats'])) {
                        $date_formats =$ctl_options_arr['custom_date_formats'];
                    }else{
                       $date_formats = "M d";
                    }
                }
                else{
                     $df=$attribute['date-format'];
                     $date_formats =__("$df",'cool_timeline');     

                }  
            }else{
                  $defaut_df = $ctl_options_arr['ctl_date_formats'] ? $ctl_options_arr['ctl_date_formats'] : "M d";
                 $date_formats =__("$defaut_df",'cool_timeline'); 
            }


            if ($attribute['category']) {
            $category = $attribute['category'];
            if(is_numeric($attribute['category'])){
               $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'ctl-stories',
                        'field' => 'term_id',
                        'terms' => $attribute['category'],
                    ));  
            }else{
             $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'ctl-stories',
                        'field' => 'slug',
                        'terms' => $attribute['category'],
                    ));
                }
        }

          $display_year = '';
            $format = __('d/m/Y', 'cool-timeline');
            $output = '';
            $year_position = 2;

            $args['post_type'] = 'cool_timeline';

            if ($attribute['show-posts']) {
                $args['posts_per_page'] = $attribute['show-posts'];
            } else {
                $args['posts_per_page'] = $ctl_post_per_page;
            }
       
            $args['post_status'] = array('publish','future');
            $args['post_type'] = 'cool_timeline';
           
          if ($enable_pagination == "yes") {
             if ( get_query_var('paged') ) { 
                $paged = get_query_var('paged'); 
                } elseif ( get_query_var('page') ) { 
                $paged = get_query_var('page'); 
                } else { 
                $paged = 1; 
                }
                     $args['paged'] = $paged;
              }

            $stories_order = '';
            if ($attribute['order']) {
                $args['order'] = $attribute['order'];
                $stories_order = $attribute['order'];
            } else {
                $args['order'] = $ctl_posts_orders;
                $stories_order = $ctl_posts_orders;
            }

            if($based=="custom"){
                $args['meta_query'] = array(
                     'ctl_story_order' => array(
                        'key' => 'ctl_story_order',
                        'compare' => 'EXISTS',
                        'type'    => 'NUMERIC'
                        ),
                     array(
                        'key'     => 'story_based_on',
                        'value'   => 'custom',
                        'compare' => '=',
                    ));
                $args['orderby'] = array(
                    'ctl_story_order' => $stories_order);
            }else{
             

            $args['meta_query']= array(
                    'relation' => 'OR',
               array('key'     => 'story_based_on',
                        'value'   => 'default',
                        'compare' => '=',
                        'type'    => 'CHAR'
                         ), 
              array('relation'    => 'AND',
              'ctl_story_year' => array(
                  'key'     => 'ctl_story_year',
                  'compare' => 'EXISTS',
                ),
                'ctl_story_date'    => array(
                'key'     => 'ctl_story_date',
                'compare' => 'EXISTS',
                )));

            $args['orderby'] = array(
                'ctl_story_year' => $stories_order,
                'ctl_story_date' => $stories_order );
                }
   
  }

 /*---------------end common --------------*/
