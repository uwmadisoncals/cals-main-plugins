<?php

if (!class_exists('CoolTimeline_Shortcode')) {

    class CoolTimeline_Shortcode {

        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
            add_action('init', array(&$this, 'cooltimeline_register_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'ctl_load_scripts_styles'));
            new Cooltimeline_Styles();

            // Call actions and filters in after_setup_theme hook
            add_action('after_setup_theme', array(&$this, 'ctl_pro_read_more'));
            add_filter('excerpt_length', array(&$this, 'ctl_ex_len'), 999);
            add_filter( 'body_class', array(&$this, 'ctl_body_class') );
        }
      
        function cooltimeline_register_shortcode() {
            add_shortcode('cool-timeline', array(&$this, 'cooltimeline_view'));

        }

        function cooltimeline_view($atts, $content = null) {
            $design_cls='';
            $attribute = shortcode_atts(array(
                'class' => 'caption',
                'show-posts' => '',
                'order' => '',
                'post-type'=>'',
                'category' => 0,
                'taxonomy'=>'',
                'post-category'=>'',
                'tags'=>'',
            
               'layout' => 'compact',
                'designs'=>'',
                'items'=>'',
                'skin' =>'',
                'type'=>'',
                'icons' =>'',
                'animations'=>'',
                'animation'=>'',
                'date-format'=>'',
                'based'=>'default',
                'story-content'=>'',
                //new
                'compact-ele-pos'=>'main-date'
               ), $atts);

             wp_enqueue_style('ctl_gfonts');
             wp_enqueue_style('ctl_default_fonts');
            wp_enqueue_style('ctl_prettyPhoto');
            wp_enqueue_script('ctl_prettyPhoto');
            
             $layout=$attribute['layout'] ?$attribute['layout']:'default';
             require('common-query.php');
          
           if( $attribute['type'] &&  $attribute['type']=="horizontal"){
                wp_enqueue_style('ctl-styles-horizontal');
              
            
                wp_enqueue_script('ctl-slick-js');
                wp_enqueue_script('ctl_horizontal_scripts');
              
                wp_enqueue_style('ctl-styles-slick');
                 $ctl_options_arr = get_option('cool_timeline_options');
              if($attribute['designs'])
              {
                  $design_cls='ht-'.$attribute['designs'];
                  $design=$attribute['designs'];
                  }else{
                 $design_cls='ht-default';
                  $design='default';
              }
               $r_more= $ctl_options_arr['display_readmore']?$ctl_options_arr['display_readmore']:"yes";
                $clt_hori_view='';
             
                  wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

              require('views/horizontal-timeline.php');

                return $clt_hori_view;

            }
          else if( $attribute['post-type'] && !empty( $attribute['post-type'])) {
         if ($attribute['layout'] == 'horizontal') {
            wp_enqueue_style('ctl-styles-horizontal');
            wp_enqueue_script('ctl-slick-js');
            wp_enqueue_script('ctl_horizontal_scripts');
           wp_enqueue_style('ctl-styles-slick');
            }else{
              wp_enqueue_style('ctl_styles');
              wp_enqueue_style('section-scroll');
              wp_enqueue_script('section-scroll-js');
              wp_enqueue_script('ctl_viewportchecker');
              wp_enqueue_style('ctl_animate');
              wp_enqueue_script('ctl_scripts');
            }
           
        
            wp_enqueue_style('ctl_flexslider_style');
           // wp_enqueue_script('ctl_prettyPhoto');
            wp_enqueue_script('ctl_jquery_flexslider');
            wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
             wp_enqueue_style('ctl_gfonts');
             wp_enqueue_style('ctl_default_fonts');

                if($attribute['layout']=='horizontal' ) {
                    if ($attribute['designs']) {
                        $design_cls = 'ht-' . $attribute['designs'];
                        $design = $attribute['designs'];
                    } else {
                        $design_cls = 'ht-default';
                        $design = 'default';
                    }
                }else if($attribute['layout']=='default' || $attribute['layout']=='one-side' || $attribute['layout']=='compact') {
                    if ($attribute['designs']) {
                        $design_cls = 'main-' . $attribute['designs'];
                        $design = $attribute['designs'];
                    } else {
                        $design_cls = 'main-default';
                        $design = 'default';
                    }
                }
              
                  $output='';
                 require('views/content-timeline.php');

              return $output;
            }
            else {
                wp_enqueue_style('ctl_styles');
              
                wp_enqueue_style('ctl_flexslider_style');
                wp_enqueue_style('section-scroll');
               // wp_enqueue_script('ctl_prettyPhoto');
                 wp_enqueue_script('ctl_scripts');
                wp_enqueue_script('ctl_jquery_flexslider');
                wp_enqueue_script('section-scroll-js');

                wp_enqueue_script('ctl_viewportchecker');
                wp_enqueue_style('ctl_animate');


                wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

         if($attribute['layout'] == "compact"){
                  wp_enqueue_style('ctl-compact-tm');
             if (! wp_script_is('c_masonry','enqueued' )) { 
                  wp_enqueue_script('c_masonry');
                 wp_add_inline_script( 'c_masonry',"
                  ( function($) {
                  $(window).load(function(){ 
                 // masonry plugin call
              $('.compact-wrapper .clt-compact-cont').each(function(index){
               
               $(this).masonry({itemSelector : '.timeline-mansory'});
              });
         $('.compact-wrapper .clt-compact-cont').find('.timeline-mansory').each(function(index){
                var firstPos=$(this).position();
                if($(this).next('.timeline-post').length>0){
                    var secondPos=$(this).next().position();
                    var gap=secondPos.top-firstPos.top;
                     new_pos=secondPos.top+70;
                      if(gap<=35){
                    $(this).next().css({'top':new_pos+'px','margin-top':'0'});
                      }
                  }
             var leftPos=$(this).position();
               var id=$(this).attr('id');
                if(leftPos.left<=0){
                $(this).addClass('ctl-left');
                }else{
                  $(this).addClass('ctl-right');  
                }
             });
        $('.clt-compact-preloader').each(function(index){
            $(this).fadeOut('slow',function(){
            $(this).hide();});
              });
          });

        })(jQuery);
          ");
          }
        }




                if($attribute['designs'])
                {
                    $design_cls='main-'.$attribute['designs'];
                    $design=$attribute['designs'];
                   }else{
                    $design_cls='main-default';
                    $design='default';
                  }
                $output = '';
                $ctl_html = '';
                $ctl_format_html = '';
                /*
                 * Gerneral options
                 */

                //  $ctl_timeline_type = $ctl_options_arr['timeline_type'];
                $ctl_title_text = $ctl_options_arr['title_text'];
                $ctl_title_tag = $ctl_options_arr['title_tag'];

                $ctl_animation='';

                if (isset($attribute['animations'])) {
                    $ctl_animation=$attribute['animations'];
                }else if($attribute['animation']){
                  $ctl_animation=$attribute['animation'];
                 }else{
                  $ctl_animation ='bounceInUp';
                     }

       
          if (isset($ctl_options_arr['user_avatar']['id'])) {
                    $user_avatar = wp_get_attachment_image_src($ctl_options_arr['user_avatar']['id'], 'ctl_avatar');
                }

                /*
                 * images sizes
                 */
                $ctl_post_per_page = $ctl_post_per_page ? $ctl_post_per_page : 10;
                $ctl_avtar_html = '';
                $timeline_id = '';
                    $clt_icons='';

                if (isset($attribute['icons']) && $attribute['icons']=="YES"){
                    $clt_icons='icons_yes';
                }else{
                    $clt_icons='icons_no';
                }

                if ($attribute['category']) {
                  if(is_numeric($attribute['category'])){
                         $ctl_term = get_term_by('id', $attribute['category'], 'ctl-stories');
                        }else{
                    $ctl_term = get_term_by('slug', $attribute['category'], 'ctl-stories');
                      }
                  
                    if ($ctl_term->name == "Timeline Stories") {
                        $ctl_title_text = $ctl_title_text;
                    } else {
                        $ctl_title_text = $ctl_term->name;
                    }
                    $catId = $attribute['category'];
                    $timeline_id = "timeline-$catId";
                } else {
                    $ctl_title_text = $ctl_title_text ? $ctl_title_text : 'Timeline';
                    $timeline_id = "timeline-".rand(1,10);
                }
                  if (isset($user_avatar[0]) && !empty($user_avatar[0])) {
                        $ctl_avtar_html .= '<div class="avatar_container row"><span title="' . $ctl_title_text . '"><img  class=" center-block img-responsive img-circle" alt="' . $ctl_title_text . '" src="' . $user_avatar[0] . '"></span></div> ';
                    }
                $ctl_html_no_cont = '';

                $ctl_title_tag = $ctl_title_tag ? $ctl_title_tag : 'H2';
                //$ctl_title_pos = $ctl_title_pos ? $ctl_title_pos : 'left';
                $ctl_content_length ? $ctl_content_length : 100;
               
               
                $layout_wrp = '';
            $r_more= $ctl_options_arr['display_readmore']?$ctl_options_arr['display_readmore']:"yes";
                require("views/default.php");
              
       $main_wrp_id='tm-'.$attribute['layout'].'-'.$attribute['designs'].'-'.rand(1,20);
           
    $output .='<! ========= Cool Timeline PRO '.CTLPV.' =========>';
    
     $output .= '<div  id="'. $main_wrp_id.'" class="cool_timeline cool-timeline-wrapper  ' . $layout_wrp . ' ' . $wrapper_cls .' '.$design_cls.'" data-pagination="' . $enable_navigation . '"  data-pagination-position="' . $navigation_position . '">';
              $output .= $ctl_avtar_html;
                if ($title_visibilty == "yes") {
                    $output .= sprintf(__('<%s class="timeline-main-title center-block">%s</%s>', 'cool-timeline'), $ctl_title_tag, $ctl_title_text, $ctl_title_tag);
                }
                 $output .= '<div class="cool-timeline ultimate-style ' . $layout_cls . ' ' . $wrp_cls . '">';
                    $output .= '<div data-animations="'.$ctl_animation.'"  id="' . $timeline_id . '" class="cooltimeline_cont  clearfix '.$clt_icons.'">';
              $output .= $ctl_html;
                $output .= $ctl_html_no_cont;
                $output .= '</div>
			</div>

    </div>  <!-- end
 ================================================== -->';
                return $output;

            }

        }

        function ctl_pro_read_more() {

            // add more link to excerpt
            function ctl_p_excerpt_more($more) {
                global $post;
                $ctl_options_arr = get_option('cool_timeline_options');
                $r_more= $ctl_options_arr['display_readmore']?$ctl_options_arr['display_readmore']:"yes";

                    $read_more='';
                    if(isset($ctl_options_arr['read_more_lbl'])&& !empty($ctl_options_arr['read_more_lbl']))
                        {
                    $read_more=__($ctl_options_arr['read_more_lbl'],'cool-timeline');
                         } else{
                         $read_more=__('Read More', 'cool-timeline');
                         }  
                
                if ($post->post_type == 'cool_timeline' && !is_single()) {

                     $custom_link = get_post_meta($post->ID, 'story_custom_link', true);
                    if ($r_more == 'yes') {

                    
                    if($custom_link){
                        return '..<a  target="_blank" class="read_more ctl_read_more" href="' . $custom_link. '">' .$read_more. '</a>';
                         }else{
                        return '..<a class="read_more ctl_read_more" href="' . get_permalink($post->ID) . '">' .$read_more. '</a>';
                        }
                    }
                } else {
                    return $more;
                }
            }

            add_filter('excerpt_more', 'ctl_p_excerpt_more', 999);
        }

        function ctl_ex_len($length) {
            global $post;
            $ctl_options_arr = get_option('cool_timeline_options');
            $ctl_content_length = $ctl_options_arr['content_length'] ? $ctl_options_arr['content_length'] : 100;
            if ($post->post_type == 'cool_timeline' && !is_single()) {
                return $ctl_content_length;
            }
            return $length;
        }


        /*
         * Include this plugin's public JS & CSS files on posts.
         */

        function ctl_load_scripts_styles() {
             ctl_common_assets();
           }

       function safe_strtotime($string, $format) {
            if ($string) {
                $date = date_create($string);
                if (!$date) {
                    $e = date_get_last_errors();
                    foreach ($e['errors'] as $error) {
                        return "$error\n";
                    }
                    exit(1);
                }
               return date_format($date, __("$format", 'cool-timeline'));

            } else {
                return false;
            }
        }

        function ctl_body_class( $c ) {
            global $post;
            if( isset($post->post_content) && has_shortcode( $post->post_content, 'cool-timeline' ) ) {
                $c[] = 'cool-timeline-page';
            }
            return $c;
        }

    }

} // end class


