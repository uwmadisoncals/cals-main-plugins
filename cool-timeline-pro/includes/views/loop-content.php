<?php
  switch ($img_cont_size) {
             case'full':
             $cont_size_cls = 'full';
                break;
            case'small':
                $cont_size_cls = 'small';
                break;
            default;
                $cont_size_cls = 'full';
                break;
             }
      if (isset($cont_size_cls) && !empty($cont_size_cls)) {
         $container_cls = $cont_size_cls;
         } else {
            $container_cls = 'full';
             }
      
            $custom_link = get_post_meta($post->ID, 'story_custom_link', true);
          $slink_s='';
          $slink_e='';
          if($r_more=="yes"){
            if(isset($custom_link)&& !empty($custom_link)){
            $slink_s='<a target="_blank" title="'.esc_attr(get_the_title()).'" href="'.esc_url($custom_link).'">';
             $slink_e='</a>';
            }else{
            $slink_s='<a title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">';
             $slink_e='</a>';
                }
          }  
  
         if ($story_content=="full") {
         $story_cont = apply_filters('the_content', $post->post_content);
        } else {
            $story_cont = "<p>" . get_the_excerpt() . "</p>";
             }

         if ('' != $story_cont) {
         $post_content = $story_cont;
         }

        if (preg_match("/\d{4}/", $ctl_story_date, $match)) {

            $year = intval($match[0]); //converting the year to integer

            if ($year >= 1970) {

                $posted_date = date_i18n(__("$date_formats", 'cool-timeline'), strtotime("$ctl_story_date"));

            } else {

                $posted_date = $this->safe_strtotime($ctl_story_date, "$date_formats");

            }

        }


//$posted_date=get_the_date(__("$date_formats",'cool-timeline'));


        if ($cont_size_cls == "full") {

            $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');

        } else {

            $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');

        }


        $ctl_thumb_url = $ctl_thumb['0'];

        $ctl_thumb_width = $ctl_thumb['1'];

        $ctl_thumb_height = $ctl_thumb['2'];

        $s_l_close='';
        if ($stories_images_link =="popup") {

            $img_f_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

            $story_img_link = '<a title="' . esc_attr(get_the_title()). '"  href="' . $img_f_url . '" class="ctl_prettyPhoto">';
            $s_l_close='</a>';

        } else if ($stories_images_link == "single") {

             if(isset($custom_link)&& !empty($custom_link)){
                $story_img_link = '<a target="_blank" title="' . esc_attr(get_the_title()) . '"  href="' . $custom_link. '" class="single-page-link">';
            $s_l_close='</a>';
              }else{
          $story_img_link = '<a title="' . esc_attr(get_the_title()) . '"  href="' . get_the_permalink() . '" class="single-page-link">';
            $s_l_close='</a>';
             }

        } else if ($stories_images_link == "disable_links") {
             $story_img_link = '';
              $s_l_close='';
        }
        else {
            $s_l_close='';
            $story_img_link = '<a title="' . esc_attr(get_the_title()) . '"  href="' . get_the_permalink() . '" class="">';

        }


//video format

        if ($story_format == "video") {

            //$ctl_video=rwmb_meta('ctl_video' ,'type=oembed' );

            $ctl_video = get_post_meta($post->ID, 'ctl_video', true);

            if ($ctl_video) {

                // $url = 'https://www.youtube.com/watch?v=u9-kU7gfuFA'

                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $ctl_video, $matches);

                $id = $matches[1];

                if ($id) {

                    $width = '100%';

                    $height = '100%';

                    $iframe = '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe>';

                    $ctl_format_html .= '<div class="full-width">' . $iframe . '</div>';

                }

            }

        } else if ($story_format == "slideshow") {

            //$ctl_slides=rwmb_meta('ctl_slides');

            $d = get_post_meta($post->ID, 're_', false);

            $ctl_slides = array();

            if ($d && is_array($d[0])) {
             foreach ($d[0] as $key => $images) {
                $ctl_slides[] = $images['ctl_slide']['id'];
                }

            }
         $slides_html = '';
         $s_img_cls='';
    if (array_filter($ctl_slides)) {
        foreach ($ctl_slides as $key => $att_index) {
             if($attribute['type']=="horizontal"){
             $slides = wp_get_attachment_image_src($att_index, 'medium');
             $s_img_cls='gallery_images';
             }else{
            $slides = wp_get_attachment_image_src($att_index, 'large');
             $s_img_cls='';
             }
             if ($slides[0]) {
                 $sld = $slides[0];

                  if($stories_images_link == "popup") {
                      $slides_html .= '<li><a  class="ctl_prettyPhoto" rel="ctl_prettyPhoto[pp_gallery-'.$post->ID.']" href="'.$sld.'"><img class="'.$s_img_cls.'" src="' . $sld . '"></a></li>';
                  }else{
                    $slides_html .= '<li><img class="'.$s_img_cls.'" src="' . $sld . '"></li>';
                     }

                     
                  }
               

                }
             }     
       
             if($attribute['type']=="horizontal"){
                  $ctl_format_html .= '<div class="clt_gallery"><ul class="story-gallery">';
                 $ctl_format_html .= $slides_html . '</ul><div style="clear:both"></div></div>';
             }else{
             $ctl_format_html .= '<div class="full-width  ctl_slideshow">';
             $ctl_format_html .= '<div data-animationSpeed="' . $animation_speed . '"  data-slideshow="' . $ctl_slideshow . '" data-animation="' . $slider_animation . '" class="ctl_flexslider"><ul class="slides">';
              $ctl_format_html .= $slides_html . '</ul></div>';
                $ctl_format_html .= '</div>';
            }



        } else {


            if (isset($ctl_thumb_url) && !empty($ctl_thumb_url)) {

                if ($cont_size_cls == "full") {

                    $ctl_format_html .= '<div class="full-width">' . $story_img_link . '<img  class="story-img" src="' . $ctl_thumb_url . '">'.$s_l_close.'</div>';

                } else {

                    $s_img_w = $ctl_thumb_width / 2;

                    $s_img_h = $ctl_thumb_height / 2;

                    $ctl_format_html .= '<div class="pull-left">' . $story_img_link . '<img  width="' . $s_img_w . '" height="' . $s_img_h . '" class="story-img left_small" src="' . $ctl_thumb_url . '">'.$s_l_close.'</div>';

                }

            }

        }

