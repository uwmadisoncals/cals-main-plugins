<?php
if (!class_exists('CoolContentTimeline')) {

    class CoolContentTimeline
    {
        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions
            add_action('init', array(&$this, 'cool_ct_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'ctl_ct_ss'));
            add_filter( 'body_class', array(&$this, 'ctl_ct_body_class') );
         }

        public function cool_ct_shortcode()
        {
            add_shortcode('cool-content-timeline', array(&$this, 'cool_ct_view'));
        }
        public function ctl_ct_body_class( $c ) {
            global $post;
            if( isset($post->post_content) && has_shortcode( $post->post_content, 'cool-content-timeline' ) ) {
                $c[] = 'cool-ct-page';
            }
            return $c;
        }

    
        public function cool_ct_view($atts, $content = null)
        {

            $design_cls = '';
            $attribute = shortcode_atts(array(
                'class' => 'caption',
                'show-posts' => '',
                'order' => '',
                'post-type' => '',
                'category' => 0,
                'taxonomy' => '',
                'post-category' => '',
                'tags' => '',
                'layout' => 'default',
                'designs' => '',
                'items' => '',
                'skin' => '',
                'type' => '',
                'icons' => '',
                'animations' => '',
                'date-format'=>'',
                'story-content'=>''
            ), $atts);
    
       $layout=$attribute['layout']?$attribute['layout']:'default';
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

            if($attribute['layout'] == "compact")
            {
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
     }
           wp_enqueue_style('ctl_prettyPhoto');
            wp_enqueue_style('ctl_flexslider_style');
            wp_enqueue_script('ctl_prettyPhoto');
            wp_enqueue_script('ctl_jquery_flexslider');
            wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
             wp_enqueue_style('ctl_gfonts');
             wp_enqueue_style('ctl_default_fonts');


            if ($attribute['layout'] == 'horizontal') {
                if ($attribute['designs']) {
                    $design_cls = 'ht-' . $attribute['designs'];
                    $design = $attribute['designs'];
                } else {
                    $design_cls = 'ht-default';
                    $design = 'default';
                }
            } else if ($attribute['layout'] == 'default' || $attribute['layout'] == 'one-side' || $attribute['layout']=='compact') {
                if ($attribute['designs']) {
                    $design_cls = 'main-' . $attribute['designs'];
                    $design = $attribute['designs'];
                } else {
                    $design_cls = 'main-default';
                    $design = 'default';
                }
            }
            wp_enqueue_style('ctl-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            $output='';
             require('content-timeline.php');
            return $output;
    }     

     /*
      * Include this plugin's public JS & CSS files on posts.
      */

        function ctl_ct_ss()
        {
            ctl_common_assets();

        }

    }
}


