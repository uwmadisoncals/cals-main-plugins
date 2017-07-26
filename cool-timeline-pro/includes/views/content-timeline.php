<?php
$ctl_options_arr = get_option('cool_timeline_options');
$active_design=$attribute['designs']?$attribute['designs']:'default';
$timeline_skin = isset($attribute['skin']) ? $attribute['skin'] : 'default';
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
$output = '';
$ctl_html = '';
$ctl_format_html = '';
$dates_li ='';
$same_day_post='';
/*
 * Gerneral options
 */

//  $ctl_timeline_type = $ctl_options_arr['timeline_type'];
$ctl_title_text = $ctl_options_arr['title_text'];
$ctl_title_tag = $ctl_options_arr['title_tag'];


$stories_images_link = $ctl_options_arr['stories_images'];

if (isset($attribute['animations'])) {
    $ctl_animation=$attribute['animations'];
}else{
    $ctl_animation ='fadeIn';
}

if (isset($ctl_options_arr['user_avatar']['id'])) {
    $user_avatar = wp_get_attachment_image_src($ctl_options_arr['user_avatar']['id'], 'ctl_avatar');
}

/*
 * content settings
 */
$default_icon = isset($ctl_options_arr['default_icon'])?$ctl_options_arr['default_icon']:'';
$r_more= $ctl_options_arr['display_readmore']?$ctl_options_arr['display_readmore']:"yes";
$ctl_post_per_page = $ctl_options_arr['post_per_page'];
$story_desc_type = $ctl_options_arr['desc_type'];
// $ctl_no_posts = isset($ctl_options_arr['no_posts']) ? $ctl_options_arr['no_posts'] : "No timeline story found";
$ctl_content_length = $ctl_options_arr['content_length'];
$ctl_posts_orders = $ctl_options_arr['posts_orders'] ? $ctl_options_arr['posts_orders'] : "DESC";
$disable_months = $ctl_options_arr['disable_months'] ? $ctl_options_arr['disable_months'] : "no";
$title_alignment = $ctl_options_arr['title_alignment'] ? $ctl_options_arr['title_alignment'] : "center";

$title_visibilty = $ctl_options_arr['display_title'] ? $ctl_options_arr['display_title'] : "yes";

$slider_animation = $ctl_options_arr['slider_animation'] ? $ctl_options_arr['slider_animation'] : "slide";
$ctl_slideshow = $ctl_options_arr['ctl_slideshow'] ? $ctl_options_arr['ctl_slideshow'] : true;
$animation_speed = isset($ctl_options_arr['animation_speed'])? $ctl_options_arr['animation_speed'] : 7000;
//$ctl_posts_order='date';

$enable_navigation = $ctl_options_arr['enable_navigation'] ? $ctl_options_arr['enable_navigation'] : 'yes';
$navigation_position = $ctl_options_arr['navigation_position'] ? $ctl_options_arr['navigation_position'] : 'right';

$enable_pagination = $ctl_options_arr['enable_pagination'] ? $ctl_options_arr['enable_pagination'] : 'no';
$itcls='';
if($active_design=="design-2" || $active_design=="design-3"  || $active_design=="design-4") {
    $items = $attribute['items'] ? $attribute['items'] : "3";
    $itcls='hori-items-'.$items;
}else{
    $items ='0';
    $itcls='hori-items-1';
}
/*
 * images sizes
 */
$ctl_post_per_page = $ctl_post_per_page ? $ctl_post_per_page : 10;
$ctl_avtar_html = '';
$timeline_id = '';
$clt_icons='';
$story_content='';
$story_content=$attribute['story-content'];

if (isset($attribute['icons']) && $attribute['icons']=="YES"){
    $clt_icons='icons_yes';
}else{
    $clt_icons='icons_no';
}

$ctl_html_no_cont = '';

$ctl_title_tag = $ctl_title_tag ? $ctl_title_tag : 'H2';
//$ctl_title_pos = $ctl_title_pos ? $ctl_title_pos : 'left';
$ctl_content_length ? $ctl_content_length : 100;

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


$layout_wrp = '';


$format = __('d/M/Y', 'cool-timeline');
$output = '';
$year_position = 2;
$display_year ='';
$args = array();
$cat_timeline = array();

$args['post_type'] =$attribute['post-type'];

if ($attribute['show-posts']) {
    $args['posts_per_page'] = $attribute['show-posts'];
} else {
    $args['posts_per_page'] = $ctl_post_per_page;
}

$args['post_status'] = array('publish');


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
if(!empty($attribute['taxonomy'])&& !empty($attribute['post-category'])) {

     if ( strpos( $attribute['post-category'], "," ) !== false ) {
        $attribute['post-category'] = explode( ",", $attribute['post-category'] );
        $attribute['post-category'] = array_map( 'trim',$attribute['post-category'] );
      } else {
        $attribute['post-category'] = $attribute['post-category'];
        }

        
    $args['tax_query'] = array(array(
        'taxonomy' =>$attribute['taxonomy'],
        'field' => 'slug',
        'terms' => $attribute['post-category']));
}

if(!empty($attribute['tags'])) {
    $args['tag'] =$attribute['tags'];
}


$spy_ele = '';
$i = 0;
$row = 1;
$ctl_html_no_cont = '';

if ($attribute['layout'] == "one-side") {
    $layout_cls = 'one-sided';
    $layout_wrp = 'one-sided-wrapper';
} 
elseif ($attribute['layout'] == "compact"){
             $layout_cls = 'compact';
            $layout_wrp = 'compact-wrapper';
 } 
else if ($attribute['layout'] == "horizontal") {
    $layout_cls = 'horizontal';
    $layout_wrp = 'ctl-horizontal-wrapper';
}
else {
    $layout_cls = '';
    $layout_wrp = 'both-sided-wrapper';
}

if($attribute['layout']=="compact"){
    $compact_id="ctl-compact-pro-".rand(1,20);
    $ctl_html .= '<div class="clt-compact-preloader"></div>';
    $ctl_html .='<div id="'.$compact_id.'" class="clt-compact-cont"><div class="center-line"></div>';

}


$ctl_loop = new WP_Query($args);

if ($ctl_loop->have_posts()) {

    while ($ctl_loop->have_posts()) : $ctl_loop->the_post();

        global $post;
        $container_cls = 'full';
        $compact_year=''; 
        $posted_date=get_the_date(__("$date_formats",'cool-timeline'));
        $post_date = explode('/',get_the_date(__("D/M/Y",'cool-timeline')));
        $post_date_def =get_the_date(__("d/m/Y,H:i",'cool-timeline'));
        $post_year = (int)$post_date[$year_position];
        $timeline_post_id='timeline-post-id-'.$post->ID;
           $p_id="post-".$post->ID;
        if ($story_content == 'full') {

            $story_cont = apply_filters('the_content', $post->post_content);

        } else {
            $read_m_btn='';
             if ($r_more == 'yes') {
                    $read_m_btn= '..<a class="read_more ctl_read_more" href="' . get_permalink(get_the_ID()) . '">' . __('Read more', 'cool-timeline') . '</a>';
                }
            $format = get_post_format() ? : 'standard';

            if ($format=="standard") {
               $gte= preg_replace( '/<a [^>]+>Continue reading*?< \/a>/i', '', get_the_excerpt() );
             $story_cont = "<p>" .$gte. $read_m_btn. "</p>";  
            }else{
            $story_cont = apply_filters('the_content', $post->post_content);
            }
            
          }

        if ('' != $story_cont) {

            $post_content = $story_cont;

        }



//$posted_date=get_the_date(__("$date_formats",'cool-timeline'));
         $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');
        $ctl_thumb_url = $ctl_thumb['0'];
        $ctl_thumb_width = $ctl_thumb['1'];
        $ctl_thumb_height = $ctl_thumb['2'];


        $s_l_close='';
         if ($stories_images_link =="popup") {

            $img_f_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

            $story_img_link = '<a title="' . esc_attr(get_the_title()). '"  href="' . $img_f_url . '" class="ctl_prettyPhoto">';
            $s_l_close='</a>';

        } else if ($stories_images_link == "single") {

            $story_img_link = '<a title="' . esc_attr(get_the_title()) . '"  href="' . get_the_permalink() . '" class="single-page-link">';
            $s_l_close='</a>';

        } else if ($stories_images_link == "disable_links") {
             $story_img_link = '';
              $s_l_close='';
        }
        else {
            $s_l_close='';
            $story_img_link = '<a title="' . esc_attr(get_the_title()) . '"  href="' . get_the_permalink() . '" class="">';
         }


            if (isset($ctl_thumb_url) && !empty($ctl_thumb_url)) {
                $ctl_format_html .= '<div class="full-width">' . $story_img_link . '<img  class="story-img" src="' . $ctl_thumb_url . '">'.$s_l_close.'</div>';
              }



        if ($i % 2 == 0) {
        $even_odd = "even";
         } else {
         $even_odd = "odd";
            }
        $selected='';

        if($i==1){
            $selected='selected';
        }
        if (function_exists('get_fa')) {
            $post_icon = get_fa(true);
        }

        if(isset($post_icon)){
            $icon=$post_icon;
        }else{
            if(isset($default_icon)&& !empty($default_icon)){
                $icon='<i class="fa '.$default_icon.'" aria-hidden="true"></i>';
            }else {
                $icon = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
            }
        }
        $clt_icon='';
        if (isset($attribute['icons']) && $attribute['icons'] == "YES") {

            $clt_icon .='<span class="icon-placeholder">'.$icon.'</span> ';

        }
        if ($attribute['layout'] == "horizontal") {
         
             if($active_design=='design-3'||$active_design=='design-4') {
            $dates_li .= ' <li class="ht-dates-'.esc_attr($design).'" data-date="' . esc_attr($p_id ). '">'.$clt_icon.'<span class="ctl-story-time ' . esc_attr($selected ). '"  data-date="' .esc_attr($p_id). '" ><div class="ctl-tooltips"><span>'. $posted_date.'</span></div></li>';
            }else{
             $dates_li .= ' <li class="ht-dates-'.esc_attr($design).'" data-date="' . esc_attr($p_id ). '">'.$clt_icon.'<span class="ctl-story-time ' . esc_attr($selected ). '"  data-date="' .esc_attr($p_id). '" >'. $posted_date.'</li>';
            }
        }
        else {

       if ($post_year != $display_year) {
         $display_year = $post_year;
                 $ctle_year_lbl = sprintf('<span class="ctl-timeline-date">%s</span>', $post_year);

        if(in_array($layout,array("compact"),TRUE)!=true){
             $ctl_html .= '<div data-cls="sc-nv-'.esc_attr($design).' '.esc_attr($wrp_cls).'"  class="timeline-year  scrollable-section '.esc_attr($design).'-year" data-section-title="' . esc_attr($post_year) . '" id="clt-' . esc_attr($post_year) . '"><div class="icon-placeholder">' . $ctle_year_lbl . '</div>
                <div class="timeline-bar"></div>
                 </div>';
             } else{
             $ctl_html .= '<span data-cls="sc-nv-'.esc_attr($design).' '.esc_attr($wrp_cls).'" class="compact-year scrollable-section '.esc_attr($design).'-year"data-section-title="' . esc_attr($post_year) . '" id="year-'.esc_attr($post_year).'"></span>'; 
          }    
        } 
      }

  $categories = get_the_category($post->ID);
        if(isset($categories) && !empty($categories)){
        foreach($categories as $category) {
            $category_id = $category->term_id;
        }
         }
        $ctl_html .= '<!-- .timeline-post-start-->';
        if($attribute['layout'] == "horizontal") {
            $ctl_html .='<li data-date="'.esc_attr($timeline_post_id).'" class="ht-'.esc_attr($design).'">';
         }


          $compt_cls=$layout=="compact"?"timeline-mansory":'';
        
        $p_cls=array();
        $p_cls[]="timeline-post";
        $p_cls[]=esc_attr($even_odd);
        $p_cls[]=esc_attr($post_skin_cls);
        $p_cls[]=esc_attr($clt_icons);
        $p_cls[]='post-'.esc_attr($post->ID);
        $p_cls[]='cat-id-'.esc_attr($category_id);
        $p_cls[]=$layout=="compact"?"timeline-mansory":'';
        $p_cls[]= esc_attr($design).'-meta';

        $ctl_html .= '<div id="post-'.esc_attr($post->ID).'" class="'.implode(" ",$p_cls).'"><div class="timeline-meta">';

        if ($disable_months == "no") {
            if ($attribute['layout'] != "horizontal" && $layout!="compact") {
             $ctl_html .= '<div class="meta-details">' . $posted_date . '</div>';
                   
            }
        }
        $ctl_html .= '</div>';
      if(function_exists('get_fa')){
        $post_icon=get_fa(true);
        }

        if(isset($post_icon)){
            $icon=$post_icon;
        }else{
            if(isset($default_icon)&& !empty($default_icon)){
                $icon='<i class="fa '.$default_icon.'" aria-hidden="true"></i>';
            }else {
                $icon = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
            }
        }
        if (isset($attribute['icons']) && $attribute['icons'] == "YES") {
            if($attribute['layout']!= "horizontal") {
                $ctl_html .= '<div class="timeline-icon icon-larger iconbg-turqoise icon-color-white main-icon-' .esc_attr($design). '">
                        <div class="icon-placeholder">' . $icon . '</div>
                        <div class="timeline-bar"></div>
                    </div>';
            }

        }else {
                if($attribute['layout']!= "horizontal") {
                    $ctl_html .= '<div class="timeline-icon icon-dot-full main-dot-' .esc_attr($design) . '">
                      <div class="timeline-bar"></div>
                      </div>';
                }
         }


        if($attribute['layout']!= "horizontal") {
            $ctl_html .= '<div  id="' . esc_attr($row). '" class="timeline-content  clearfix ' .esc_attr($even_odd) . '  ' . esc_attr($container_cls) . '  ht-content-'.esc_attr($design).'">';
        }else {
            $ctl_html .= '<div ' .esc_attr($ctl_animation) . '  " id="' . esc_attr($row). '" class="timeline-content  clearfix ' . esc_attr($even_odd) . '  ' . esc_attr($container_cls) . ' ' . $design . '-content">';
          }

       if($attribute['layout']== "horizontal" && $active_design=="design-4"){
            $ctl_html .='';
         }
        else if($active_design!="design-3") {
            $ctl_html .= '<h2 class="content-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
         }

        $ctl_html .= '<div class="ctl_info event-description ">';
       $ctl_html .= $ctl_format_html;

        if($attribute['layout']== "horizontal" && in_array($active_design,array('design-3','design-4'))) {
            $ctl_html .= '<h2 class="content-title-simple"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
          }

        if ($attribute['layout']!= "horizontal" && $active_design == 'design-3') {
          $ctl_html .= '<h2 class="content-title-simple"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
         }

   if($attribute['layout']== "horizontal" && $active_design=='design-4') {
        $ctl_html .='';
         }else{
            $ctl_html .='<div class="content-details">'; 
             if($layout=="compact"){
                $ctl_html .= '<div class="compact-meta-details"><strong><i class="fa fa-clock-o" aria-hidden="true"></i> ' . $posted_date . '</strong></div>';
              }
         $ctl_html .=$post_content;
         $ctl_html .= '<div class="post_meta_details">';
        if(!empty($attribute['taxonomy'])&& $attribute['taxonomy']=='category') {
            $ctl_html .= ctl_entry_taxonomies();
            $ctl_html .= ctl_post_tags();
        }
       $ctl_html .= '</div></div>';
       }

      $ctl_html .= '</div></div><!-- timeline content -->
                      </div><!-- .timeline-post-end -->';

        if($attribute['layout'] == "horizontal") {
             $ctl_html .= '</li>';
         }
        if ($row >= 3) {
            $row = 0;
        }
        $row++;
        $i++;
        $ctl_format_html = '';
        $post_content = '';
    endwhile;
    wp_reset_postdata();
} else {
    $ctl_html_no_cont .= '<div class="no-content"><h4>';
    //$ctl_html_no_cont.=$ctl_no_posts;
    $ctl_html_no_cont .= __('Sorry,You have not added any story yet', 'cool-timeline');
    $ctl_html_no_cont .= '</h4></div>';
}
if ($attribute['layout'] != "horizontal") {
$ctl_html .= '<div class="clearfix"></div>';
}
if($attribute['layout']=="compact"){
    $ctl_html .='</div>';
}

if ($attribute['layout'] != "horizontal" && $enable_pagination == "yes") {
    if (function_exists('ctl_pagination')) {
        $ctl_html .= ctl_pagination($ctl_loop->max_num_pages, "", $paged);
    }
}

if ($enable_pagination == "yes" && $attribute['layout']!= "horizontal") {
    if (function_exists('custom_pagination')) {
        $ctl_html .= custom_pagination($ctl_loop->max_num_pages, "", $paged);
    }
}


if ($attribute['layout'] == "horizontal") {
    $timeline_id=uniqid();
    $timeline_wrp_id="ctl-horizontal-slider-".$timeline_id;
    $output = '<div id="'.$timeline_wrp_id.'" class="cool-timeline-horizontal  '.$wrp_cls.' '.$design_cls.'" date-slider="ctl-h-slider-'.$timeline_id.'" data-nav="nav-slider-'.$timeline_id.'" data-items="'.$items.'">
<div class="timeline-wrapper '.$wrapper_cls.' '.$itcls.'" >';

    if($active_design=="design-4") {
        $output .= '<div  class="wrp-desgin-4">';
    }else{
        $output .= '<div class="clt_carousel_slider">';
    }
    if($active_design!='design-4') {
        $output .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
        $output .= $dates_li;
        $output .= '</ul></div>';
    }

    $output .= '<div  class="clt_caru_slider ">';
    $output .= '<ul class="ctl_h_slides"  id="ctl-h-slider-'.$timeline_id.'">';
    $output .=$ctl_html;
    $output .= '</ul></div>';

    if($active_design=='design-4') {
        $output .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
        $output .= $dates_li;
        $output .= '</ul></div>';
    }

    $output .='</div></div>';


}else {
    $timeline_id=uniqid();
    $main_wrp_id='content-'.$attribute['layout'].'-'.$attribute['designs'].'-'.rand(1,20);
    $output .= '
  <!-- Cool content timeline
  ================================================== -->
    <div id="'.$main_wrp_id.'" class="cool_timeline cool-timeline-wrapper  ' . $layout_wrp . ' ' . $wrapper_cls .' '.$design_cls.'"  data-pagination="' . $enable_navigation . '"  data-pagination-position="' . $navigation_position . '">';
    $output .= $ctl_avtar_html;
    if ($title_visibilty == "yes") {
        $output .= sprintf(__('<%s class="timeline-main-title center-block">%s</%s>', 'cool-timeline'), $ctl_title_tag, $ctl_title_text, $ctl_title_tag);
    }
    $output .= '<div class="cool-timeline ultimate-style ' . $layout_cls . ' ' . $wrp_cls . '">';
    $output .= '<div data-animations="'.$ctl_animation.'" id="timeline-' . $timeline_id . '" class="timeline cooltimeline_cont ' . $clt_icons . '">';
    $output .= $ctl_html;
    $output .= $ctl_html_no_cont;
    $output .= '</div>
            </div>

    </div>  <!-- end
 ================================================== -->';

}