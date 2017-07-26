<?php
$ctl_html='';
$ctl_format_html='';
$display_s_date='';
$same_day_post='';
$dates_li='';

$ctl_slideshow ='';

//$ctl_title_pos = $ctl_title_pos ? $ctl_title_pos : 'left';
$ctl_content_length ? $ctl_content_length : 100;
$itcls='';
if(in_array($active_design,array("design-2","design-3","design-4"))){

    $items = $attribute['items'] ? $attribute['items'] : "3";
	$itcls='hori-items-'.$items;
	
}else{
    $items ='0';
	$itcls='hori-items-1';
}

$i=0;
$ctl_loop = new WP_Query($args);

if ($ctl_loop->have_posts()) {

    while ($ctl_loop->have_posts()) : $ctl_loop->the_post();
         global $post;
        $story_format = get_post_meta($post->ID, 'story_format', true);
        $posted_date='';
        $img_cont_size = get_post_meta($post->ID, 'img_cont_size', true);
        $ctl_story_date = get_post_meta($post->ID, 'ctl_story_date', true);
        $i++;
        $ctl_format_html='';
        $story_id="story-id-".$post->ID;
     
         require('loop-content.php');
       
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


        /*
        Dates navigations
        */
         if($based=="custom"){
         $ctl_story_lbl = get_post_meta($post->ID, 'ctl_story_lbl',true);
         $ctl_story_lbl2 = get_post_meta($post->ID, 'ctl_story_lbl_2',true);
        $lb1= '<span class="custom_story_lbl">'.__($ctl_story_lbl,'cool-timeline').'</span>';
        $lb2= '<span class="custom_story_lbl_2">'.__($ctl_story_lbl2,'cool-timeline'). '</span>';
          $dates_li .= ' <li class="ht-dates-'.esc_attr($design).'" data-date="' . esc_attr($story_id ). '">'.$clt_icon.'<span class="ctl-story-time ' . esc_attr($selected ). '"  data-date="' .esc_attr($story_id). '" >'. $lb1.$lb2.'</span></li>';
        
        }else{
           

     if($active_design=='design-3'||$active_design=='design-4') {
            $dates_li .= ' <li class="ht-dates-'.esc_attr($design).'" data-date="' . esc_attr($story_id ). '">'.$clt_icon.'<span class="ctl-story-time ' . esc_attr($selected ). '"  data-date="' .esc_attr($story_id). '" ><div class="ctl-tooltips"><span>'. $posted_date.'</span></div></li>';
            }else{
             $dates_li .= ' <li class="ht-dates-'.esc_attr($design).'" data-date="' . esc_attr($story_id ). '">'.$clt_icon.'<span class="ctl-story-time ' . esc_attr($selected ). '"  data-date="' .esc_attr($story_id). '" >'. $posted_date.'</li>';
            }
         }



     //if($posted_date_default !=$same_day_post) {
         //   $same_day_post = $posted_date_default;
            $ctl_html .= '<li  data-date="'.esc_attr($story_id).'" class="ht-'.esc_attr($design).'">';
       // }

       $ctl_html .= '<div class="timeline-post '.esc_attr($post_skin_cls).' ht-content-'.esc_attr($design).'">';
        if($active_design=="default" || $active_design=="design-2") {
            $ctl_html .= '<h2 class="content-title">'.$slink_s . get_the_title() .$slink_e.'</h2>';

        }
        $ctl_html .= '<div class="ctl_info event-description ' .esc_attr($cont_size_cls) . '">';
        $ctl_html .= $ctl_format_html;
        if($active_design=='design-3'|| $active_design=='design-4') {
            $ctl_html .= '<h2 class="content-title-simple">'.$slink_s. get_the_title() .$slink_e.'</h2>';
          //  $ctl_html .= '<h6>' . $posted_date. '</h6>';
        }
        if($active_design!='design-4') {
            $ctl_html .= '<div class="content-details">' . $post_content . '</div>';
        }
         $ctl_html .= '</div></div>';
     //if($posted_date_default !=$same_day_post) {

            $ctl_html .='</li>';
    //    }

        $post_content = '';

    endwhile;
    wp_reset_postdata();
}

$timeline_id=uniqid();
$category= $attribute['category'] ?$attribute['category']:'all-cats';
$timeline_wrp_id="ctl-horizontal-slider-".$timeline_id;
$clt_hori_view = '<div id="'.esc_attr($timeline_wrp_id).'" class="cool-timeline-horizontal  '.esc_attr($wrp_cls.' '.$category).' '.esc_attr($design_cls).'" date-slider="ctl-h-slider-'.esc_attr($timeline_id).'" data-nav="nav-slider-'.esc_attr($timeline_id).'" data-items="'.esc_attr($items).'">
<div class="timeline-wrapper '.esc_attr($wrapper_cls).' '.esc_attr($itcls).'" >';

if($active_design=="design-4") {
    $clt_hori_view .= '<div  class="wrp-desgin-4">';
}else{
    $clt_hori_view .= '<div class="clt_carousel_slider">';
}
if($active_design!='design-4') {
    $clt_hori_view .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
    $clt_hori_view .= $dates_li;
    $clt_hori_view .= '</ul></div>';
}

$clt_hori_view .= '<div  class="clt_caru_slider ">';
$clt_hori_view .= '<ul class="ctl_h_slides"  id="ctl-h-slider-'.$timeline_id.'">';
$clt_hori_view .=$ctl_html;
$clt_hori_view .= '</ul></div>';

if($active_design=='design-4') {
    $clt_hori_view .= '<ul class="ctl_h_nav" id="nav-slider-' . $timeline_id . '">';
    $clt_hori_view .= $dates_li;
    $clt_hori_view .= '</ul></div>';
}

$clt_hori_view .='</div></div>';

