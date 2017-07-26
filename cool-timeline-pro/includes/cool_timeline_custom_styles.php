<?php

if (!class_exists('Cooltimeline_Styles')) {

    class Cooltimeline_Styles {

        public function __construct() {
            // Turn off all error reporting
          
           add_action('wp_enqueue_scripts', array(&$this, 'ctl_custom_style_new'));

            add_action('wp_head', array(&$this, 'ctl_navigation_styles'));
        }


        public function  ctl_custom_style_new( $var_name )
        {
           // Turn off all error reporting
          // Report all errors except E_NOTICE
          // 
         
            if(WP_DEBUG==true){
            error_reporting(~E_NOTICE);
             }

            $clt_vars=array();
            $ctl_options_arr= get_option('cool_timeline_options');

           $disable_months= $ctl_options_arr['disable_months'] ? $ctl_options_arr['disable_months'] : "no";
            $title_alignment= $ctl_options_arr['title_alignment'] ? $ctl_options_arr['title_alignment'] : "center";

            /*
             * Style options
             */

            $background_type= isset($ctl_options_arr['background']['enabled']) ? $ctl_options_arr['background']['enabled'] : '';

            if ($background_type == 'on') {
               $bg_color=isset($ctl_options_arr['background']['bg_color']) ? $ctl_options_arr['background']['bg_color'] : 'none';
            }
            $first_post_color= null !== $ctl_options_arr['first_post'] && $ctl_options_arr['first_post']?$ctl_options_arr['first_post'] : "#02c5be";

            $second_post_color= null !== $ctl_options_arr['second_post'] && $ctl_options_arr['second_post']?$ctl_options_arr['second_post'] : "#f12945";


            $content_bg_color= null !== $ctl_options_arr['content_bg_color'] && $ctl_options_arr['content_bg_color']?$ctl_options_arr['content_bg_color'] : '#f9f9f9';

            $content_color= null !== $ctl_options_arr['content_color'] && $ctl_options_arr['content_color']?$ctl_options_arr['content_color'] : '#666666';

            $title_color= null !== $ctl_options_arr['title_color']? $ctl_options_arr['title_color'] : '#fff';

            $circle_border_color= null !== $ctl_options_arr['circle_border_color'] && $ctl_options_arr['circle_border_color']?$ctl_options_arr['circle_border_color'] : '#333333';

            $main_title_color= isset($ctl_options_arr['main_title_color']) && null !== $ctl_options_arr['main_title_color']?$ctl_options_arr['main_title_color'] : '#000';


            /*
             * Typography options
             */

            $ctl_main_title_typo = $ctl_options_arr['main_title_typo'];
            $ctl_post_title_typo = $ctl_options_arr['post_title_typo'];
            $ctl_post_content_typo = $ctl_options_arr['post_content_typo'];

            $ctl_date_typo = $ctl_options_arr['ctl_date_typo'];
            $custom_date_style = $ctl_options_arr['custom_date_style'];
            $custom_date_color= $ctl_options_arr['custom_date_color'];

            $post_title_text_style= $ctl_options_arr['post_title_text_style'] ? $ctl_options_arr['post_title_text_style'] : 'capitalize';

           $main_title_f= $ctl_main_title_typo['face'] ? $ctl_main_title_typo['face'] : 'inherit';
           $main_title_w= $ctl_main_title_typo['weight'] ? $ctl_main_title_typo['weight'] : 'inherit';
          $main_title_s= $ctl_main_title_typo['size'] ? $ctl_main_title_typo['size'] : '22px';


         $events_body_f= $ctl_post_content_typo['face'] ? $ctl_post_content_typo['face'] : 'inherit';
         $events_body_w= $ctl_post_content_typo['weight'] ? $ctl_post_content_typo['weight'] : 'inherit';
        $events_body_s= $ctl_post_content_typo['size'] ? $ctl_post_content_typo['size'] : 'inherit';

        $post_title_f= $ctl_post_title_typo['face'] ? $ctl_post_title_typo['face'] : 'inherit';
        $post_title_w= $ctl_post_title_typo['weight'] ? $ctl_post_title_typo['weight'] : 'inherit';
        $post_title_s= $ctl_post_title_typo['size'] ? $ctl_post_title_typo['size'] : '20px';

       $post_content_f= $ctl_post_content_typo['face'] ? $ctl_post_content_typo['face'] : 'inherit';
       $post_content_w= $ctl_post_content_typo['weight'] ? $ctl_post_content_typo['weight'] : 'inherit';
       $post_content_s= $ctl_post_content_typo['size'] ? $ctl_post_content_typo['size'] : 'inherit';

            if ($custom_date_style == "yes") {
               $ctl_date_f= $ctl_date_typo['face'] ? $ctl_date_typo['face'] : 'inherit';
               $ctl_date_w= $ctl_date_typo['weight'] ? $ctl_date_typo['weight'] : 'inherit';
               $ctl_date_s= $ctl_date_typo['size'] ? $ctl_date_typo['size'] : 'inherit';

            }
             $ctl_date_color='';
            if ($custom_date_color == "yes") {
               $ctl_date_color= null !== $ctl_options_arr['ctl_date_color'] && $ctl_options_arr['ctl_date_color']?$ctl_options_arr['ctl_date_color'] : '#fff';
            }
           
            $disable_r_stories = $ctl_options_arr['disable_r_stories'] ? $ctl_options_arr['disable_r_stories'] : 'no';

            $line_color= null !== $ctl_options_arr['line_color'] && $ctl_options_arr['line_color']  ? $ctl_options_arr['line_color'] : '#000';

           $custom_styles=isset($ctl_options_arr['custom_styles']) ? $ctl_options_arr['custom_styles'] : '';

        
             $styles='';
              $styles_hori='';
           $styles.='

           /*-----Custom CSS-------*/
           ';
           
            /*
            Dynamic styles starts from here 
            */

 $styles.='.cool_timeline.cool-timeline-wrapper {
  background:'.$bg_color.';}';

 $styles.='.cool_timeline h1.timeline-main-title {
    font-weight:'.$main_title_w.';
    font-family:'.$main_title_f.'!important;
    font-size:'.$main_title_s.';
    color:'.$main_title_color.';
    text-align:'.$title_alignment.';
}';
 $styles.='.cool-timeline.compact .timeline-post .timeline-content h2.compact-content-title,.cool-timeline.compact .timeline-post .timeline-content h2.content-title,.cool-timeline .timeline-post .timeline-content h2.content-title, .cool-timeline .timeline-post .timeline-content h2.content-title-2, .cool-timeline .timeline-post .timeline-content h2.content-title-simple, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont h2 {
    font-size:'.$post_title_s.';
    font-family:'.$post_title_f.';
    font-weight:'.$post_title_w.';
    text-transform:'.$post_title_text_style.';
}
.cool-timeline.white-timeline  .timeline-post .timeline-content .content-title a {
    color:#fff;
    font-size:'.$post_title_s.';
    font-family:'.$post_title_f.';
    font-weight:'.$post_title_w.';
    text-transform:'.$post_title_text_style.';
}
.cool-timeline .timeline-post .timeline-content .content-details, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content a.cd-read-more{
    font-size:'.$post_content_s.';
    font-family:'.$post_content_f.';
    font-weight:'.$post_content_w.';
}';

 $styles.='.cool-timeline .timeline-post .timeline-meta .meta-details, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .cd-date, .cool-timeline.compact .timeline-post .timeline-content .clt-compact-date {
    font-size:'.$ctl_date_s.';
    font-family:'.$ctl_date_f.';
    font-weight:'.$ctl_date_w.';
}
.ctl-bullets-container li a, .section-bullets-bottom li a {
    font-family:'.$ctl_date_f.';
    font-weight:'.$ctl_date_w.';
}
.cool-timeline .timeline-year .icon-placeholder span {
    font-family:'.$ctl_date_f.';
}';




 $styles.='.cool-timeline.white-timeline .light-grey-post .timeline-content .content-title { color:#ffffff; }

.cool-timeline.white-timeline .light-grey-post .timeline-content:after,
.cool-timeline.white-timeline .light-grey-post .timeline-content:before  { border-left-color:'.$content_bg_color.'; }
.cool-timeline.white-timeline .light-grey-post .even .timeline-content:after,
.cool-timeline.white-timeline .light-grey-post .even .timeline-content:before,
.cool-timeline.white-timeline.one-sided .light-grey-post .timeline-content:after,
.cool-timeline.white-timeline.one-sided .light-grey-post .timeline-content:before,
.cool-timeline.white-timeline.one-sided .light-grey-post .even .timeline-content:after,
.cool-timeline.white-timeline.one-sided .light-grey-post .even .timeline-content:before{
    border: 15px solid transparent;
 /*   border-right-color:'.$content_bg_color.'; */
}';



 $styles.='.cool-timeline.white-timeline .timeline-icon.icon-larger.iconbg-indigo{
    background:'.$circle_border_color.';
}
.cool-timeline.white-timeline:before,
.cool-timeline.white-timeline.one-sided:before  {
    background-color:'.$line_color.';
     background-image: -webkit-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -moz-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -ms-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
}';

 $styles.='
 .cool-timeline.white-timeline .timeline-year{
    background:'.$circle_border_color.';
}
.cool-timeline.white-timeline .timeline-year{
    -webkit-box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
    box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
}';


 $styles.='.cool-timeline.white-timeline .timeline-post .timeline-content .content-title, .cool-timeline.white-timeline .timeline-post .timeline-content .content-title a, .cool-timeline.white-timeline .timeline-post .timeline-content .content-title a {
    color:'.$title_color.';
    text-decoration: none;
    box-shadow: none;
}

.cool-timeline.white-timeline .timeline-post .timeline-content .content-title a:hover {
    color:'.$title_color.';
    filter: opacity(0.7);
    -webkit-filter: opacity(0.7);
}
.cool-timeline.white-timeline .timeline-post .timeline-content h2.content-title-simple, .cool-timeline.white-timeline .timeline-post .timeline-content h2.content-title-simple a, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont h2 {
    color:'.$content_color.';
    text-decoration: none;
    box-shadow: none;
    filter: brightness(1.05);
    -webkit-filter: brightness(1.05);
}
.cool-timeline.white-timeline .timeline-post .timeline-content h2.content-title-simple a:hover {
    color:'.$content_color.';
    filter: brightness(1.5);
    -webkit-filter: brightness(1.5);
}
.cool-timeline.white-timeline .timeline-post .timeline-content .content-details, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont, .section-bullets-bottom li.white-timeline a, .section-bullets-right li.white-timeline a, .section-bullets-left li.white-timeline a  {
    color:'.$content_color.';
}
.cool-timeline.white-timeline .timeline-post .timeline-content .content-details a, .cool-timeline.white-timeline .timeline-post .post_meta_details a, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont a, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content a.cd-read-more {
    color:'.$content_color.';
    filter: brightness(1.1);
    -webkit-filter: brightness(1.1);
}
.cool-timeline.white-timeline .timeline-post .timeline-content .content-details a:hover, .cool-timeline.white-timeline .timeline-post .post_meta_details a:hover, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content .ctl_social_cont a:hover, .cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content a.cd-read-more:hover {
    color:'.$content_color.';
    filter: brightness(1.25);
    -webkit-filter: brightness(1.25);
}
.cool-timeline.white-timeline .timeline-post .timeline-content, .section-bullets-bottom li.white-timeline, .section-bullets-right li.white-timeline, .section-bullets-left li.white-timeline{
    color:'.$content_color.';
    background:'.$content_bg_color.';
}
.ctl-footer-bullets-container li.white-timeline a:after, .section-bullets-right li.white-timeline a:after, .section-bullets-left li.white-timeline a:after {background:'.$content_color.';
filter: contrast(29%);
-webkit-filter: contrast(29%);
}
.ctl-footer-bullets-container li.white-timeline:before {border-bottom-color:'.$content_color.';
filter: contrast(29%);
-webkit-filter: contrast(29%);
}
.section-bullets-right li.white-timeline {border-left-color:'.$content_color.';}
.section-bullets-left li.white-timeline {border-right-color:'.$content_color.';}
.section-bullets-left li.white-timeline:before, .section-bullets-right li.white-timeline:before
{
background-image: inherit;
background-color: '.$content_color.';
filter: contrast(29%);
-webkit-filter: contrast(29%);
}
.section-bullets-bottom li.white-timeline {border-top-color:'.$content_color.';}
.cool-timeline.white-timeline .timeline-post .timeline-meta .meta-details, .cool-timeline.white-timeline.compact .timeline-post .timeline-content .clt-compact-date{
    color:'.$ctl_date_color.'!important;
}
';



 $styles.='.timeline-icon.icon-larger.iconbg-indigo.iconbg-turqoise.icon-color-white{
    -webkit-box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
    box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
}

.timeline-icon.icon-larger.iconbg-turqoise.icon-color-white{
    -webkit-box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
    box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
}';
 $styles.='.cool-timeline.white-timeline  .timeline-post.even .timeline-content .content-title {
    background:'.$first_post_color.';
}
.section-bullets-bottom li:nth-child(2n+1).white-timeline.active, .section-bullets-bottom li:nth-child(2n+1).white-timeline.active:after {border-top-color: '.$first_post_color.';}
.section-bullets-bottom li:nth-child(2n).white-timeline.active, .section-bullets-bottom li:nth-child(2n).white-timeline.active:after {border-top-color: '.$second_post_color.';}

.section-bullets-right li:nth-child(2n+1).white-timeline.active:after, .section-bullets-right li:nth-child(2n+1).white-timeline.active {border-left-color: '.$first_post_color.';}
.section-bullets-right li:nth-child(2n).white-timeline.active:after, .section-bullets-right li:nth-child(2n).white-timeline.active {border-left-color: '.$second_post_color.';}

.section-bullets-left li:nth-child(2n+1).white-timeline.active:after, .section-bullets-left li:nth-child(2n+1).white-timeline.active {border-right-color: '.$first_post_color.';}
.section-bullets-left li:nth-child(2n).white-timeline.active:after, .section-bullets-left li:nth-child(2n).white-timeline.active {border-right-color: '.$second_post_color.';}

.cool-timeline .timeline-post.even .timeline-content h2.content-title-2, .cool-timeline .timeline-post.even .timeline-content h2.content-title-2 a , .cool-timeline.white-timeline .timeline-post.even .timeline-content h2.content-title-simple, .cool-timeline.white-timeline .timeline-post.even .timeline-content h2.content-title-simple a{
    color:'.$first_post_color.';
}';

 $styles.='.cool-timeline.white-timeline .timeline-post.even .timeline-content .content-title:before, .main-design-3 .cool-timeline.light-timeline .timeline-post.even .timeline-content:before, .main-design-3 .cool-timeline.dark-timeline .timeline-post.even .timeline-content:before, .main-design-3 .cool-timeline.white-timeline .timeline-post.even .timeline-content:before{
    border-right-color:'.$first_post_color.';
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left .timeline-content {
	border-right:6px solid '.$first_post_color.';
	border-left:0;
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-right .timeline-content {
	border-left:6px solid '.$second_post_color.';
	border-right:0;
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-right .timeline-content:before {
    border-right-color:'.$second_post_color.';
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left .timeline-content:before {
    border-left-color:'.$first_post_color.';
}
.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left .timeline-content .content-title:after
{
	border-left-color:'.$first_post_color.';
}
.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-right .timeline-content .content-title:after {
    border-right-color:'.$second_post_color.';
}
.cool-timeline.white-timeline  .timeline-post.even .icon-dot-full, .cool-timeline.one-sided.white-timeline .timeline-post.even .icon-dot-full, .cool-timeline.white-timeline.compact .timeline-post.ctl-left .icon-dot-full, .cool-timeline.white-timeline.compact  .timeline-post.ctl-left .timeline-content .content-title, .cool-timeline.white-timeline.compact .timeline-post.icons_yes.ctl-left .timeline-icon{
    background:'.$first_post_color.';
}
.cool-timeline.white-timeline.compact .cooltimeline_cont  .center-line { background: '.$line_color.'; }
.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.icons_yes  .iconbg-turqoise, .cool-timeline.white-timeline.compact .cooltimeline_cont .center-line:before, .cool-timeline.white-timeline.compact .cooltimeline_cont .center-line:after {
	border-color: '.$line_color.';
}
.cool-timeline.white-timeline  .timeline-post.even .icon-color-white, .cool-timeline.one-sided.white-timeline .timeline-post.even .icon-color-white{
    background:'.$first_post_color.';
}';



 $styles.='.cool-timeline.white-timeline  .timeline-post.odd .timeline-content .content-title, .cool-timeline.white-timeline.compact .timeline-post.ctl-right .icon-dot-full, .cool-timeline.white-timeline.compact  .timeline-post.ctl-right .timeline-content .content-title, .cool-timeline.white-timeline.compact .timeline-post.icons_yes.ctl-right .timeline-icon {
    background:'.$second_post_color.';
}

.cool-timeline .timeline-post.odd .timeline-content h2.content-title-2, .cool-timeline .timeline-post.odd .timeline-content h2.content-title-2 a , .cool-timeline.white-timeline .timeline-post .timeline-content h2.content-title-simple, .cool-timeline.white-timeline .timeline-post .timeline-content h2.content-title-simple a{
    color:'.$second_post_color.';
}

.main-design-4 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-right .timeline-content .content-title:before {
    border: 2px solid '.$second_post_color.';
}
.main-design-4 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left .timeline-content .content-title:before {
    border: 2px solid '.$first_post_color.';
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.ctl-left .timeline-content h2.content-title-2, .cool-timeline.white-timeline.compact .timeline-post.ctl-left .timeline-content h2.content-title-2 a, .cool-timeline.white-timeline.compact .timeline-post.ctl-left .timeline-content .clt-compact-date {
color:'.$first_post_color.';
}
.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.ctl-right .timeline-content h2.content-title-2, .cool-timeline.white-timeline.compact .timeline-post.ctl-right .timeline-content h2.content-title-2 a, .cool-timeline.white-timeline.compact .timeline-post.ctl-right .timeline-content .clt-compact-date {
color:'.$second_post_color.';
}
.main-design-3 .cool-timeline.white-timeline .timeline-post.even .timeline-content, .main-design-3 .cool-timeline.light-timeline .timeline-post.even .timeline-content, .main-design-3 .cool-timeline.dark-timeline .timeline-post.even .timeline-content {
    border-left: 6px solid '.$first_post_color.';
}
.main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.light-timeline .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.dark-timeline .timeline-post.odd .timeline-content {
    border-right: 6px solid '.$second_post_color.';
}
.main-design-3 .cool-timeline.white-timeline .timeline-post.even .timeline-content .content-title, .main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title {
    background: none !important;
}
.main-design-3 .cool-timeline.white-timeline .timeline-post.even .timeline-content h2.content-title, .main-design-3 .cool-timeline.white-timeline .timeline-post.even .timeline-content h2.content-title a
{
    color:'.$first_post_color.';
}
.main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content h2.content-title, .main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content h2.content-title a
{
    color:'.$second_post_color.';
}
.main-design-3 .cool-timeline.white-timeline.one-sided .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.light-timeline.one-sided .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.dark-timeline.one-sided .timeline-post.odd .timeline-content {
    border-left: 6px solid '.$second_post_color.';
    border-right: 1px solid #ccc;
}';

 $styles.='.main-design-4 .cool-timeline .timeline-post.even .timeline-content .content-title:before {
    content: "";
    width: 27px;
    border: 2px solid #222;
    position: absolute;
    left: -25px;
    top: 27px;
}
.main-design-4 .cool-timeline .timeline-post.odd .timeline-content .content-title:before {
    content: "";
    width: 27px;
    border: 2px solid #222;
    position: absolute;
    right: -25px;
    top: 27px;
}
.main-design-4 .cool-timeline.white-timeline .timeline-post.even .timeline-content .content-title:before {
    border: 2px solid '.$first_post_color.';
}
.main-design-4 .cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title:before {
    border: 2px solid '.$second_post_color.';
}
.main-design-4 .cool-timeline.light-timeline .timeline-post .timeline-content .content-title:before, .main-design-4 .cool-timeline.light-timeline.one-sided .timeline-post .timeline-content .content-title:before {
    border: 2px solid #eaeaea;
}
.main-design-4 .cool-timeline.dark-timeline .timeline-post .timeline-content .content-title:before, .main-design-4 .cool-timeline.dark-timeline.one-sided .timeline-post .timeline-content .content-title:before {
    border: 2px solid #111;
}';

 $styles.='.cool-timeline.white-timeline  .timeline-post .icon-dot-full, .cool-timeline.one-sided.white-timeline .timeline-post .icon-dot-full{
    background:'.$second_post_color.';
}

.cool-timeline.white-timeline  .timeline-post .icon-color-white, .cool-timeline.one-sided.white-timeline .timeline-post .icon-color-white{
    background: '.$second_post_color.';
}
.cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title:before, .main-design-3 .cool-timeline.light-timeline .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.dark-timeline .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content:before {
    border-left-color:'.$second_post_color.';
}
.cool-timeline.white-timeline.one-sided .timeline-post.odd .timeline-content .content-title:before, .main-design-3 .cool-timeline.light-timeline.one-sided .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.dark-timeline.one-sided .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.white-timeline.one-sided .timeline-post.odd .timeline-content:before {
    border-right-color:'.$second_post_color.';
    border-left-color: transparent;
}
.main-design-3 .cool-timeline.white-timeline.one-sided .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.light-timeline.one-sided .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.dark-timeline.one-sided .timeline-post.odd .timeline-content:before {
    right:inherit;
    left:-30px;
}


.cool-timeline.white-timeline  .timeline-post.even .timeline-meta .meta-details{
    color:'.$first_post_color.';
}
.cool-timeline.white-timeline  .timeline-post.odd .timeline-meta .meta-details{
    color:'.$second_post_color.';
}

.cool-timeline .timeline-post .timeline-content .content-title span{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cool-timeline .timeline-post .timeline-content .content-details { margin: 0; }
.cool-timeline .timeline-post .timeline-content .content-title {
    min-height: 50px;
    line-height:normal;
}';



 $styles.='.cool_timeline .avatar_container img.center-block.img-responsive.img-circle{
border:4px solid '.$line_color.';
}
img.center-block.img-responsive.img-circle{
    width:250px;
    height:250px;
}
.cool-timeline.white-timeline.one-sided .timeline-year:before {
    content: "";
    width: 42px;
    background: '.$line_color.';
    border: 2px solid '.$line_color.';
    position: absolute;
    left: -50px;
    top: 48.5%;
   }
.main-design-2 .cool-timeline.one-sided .timeline-year:before, .main-design-3 .cool-timeline.one-sided .timeline-year:before, .main-design-4 .cool-timeline.one-sided .timeline-year:before {
    width: 25px;
    left: -32px;
   }';


  $styles.='

/* Social Timeline Dynamic Styles */

.cool-social-timeline.white-timeline #ctl-social-timeline::before {
    background: '.$line_color.';
}
.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-content::before {
     border-left-color:'.$first_post_color.';
}
.cool-social-timeline.white-timeline .ctl-social-timeline-block.odd .ctl-social-timeline-content::before {
     border-right-color:'.$second_post_color.';
}
.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-content .cd-date { color:'.$first_post_color.'; }
.cool-social-timeline.white-timeline .ctl-social-timeline-block.odd .ctl-social-timeline-content .cd-date { color:'.$second_post_color.'; }

.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-img { background:'.$first_post_color.'; }
.cool-social-timeline.white-timeline .ctl-social-timeline-block.odd .ctl-social-timeline-img { background:'.$second_post_color.'; }
.cool-social-timeline.white-timeline .ctl-social-timeline-block .ctl-social-timeline-img {
    webkit-box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
    box-shadow: 0 0 0 4px white, inset 0 0 0 2px rgba(0, 0, 0, 0.05), 0 0 0 8px '.$line_color.';
}

.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-content { border-right:5px solid '.$first_post_color.'; }
.cool-social-timeline.white-timeline .ctl-social-timeline-block.odd .ctl-social-timeline-content { border-left:5px solid '.$second_post_color.'; }

.cool-social-timeline .ctl-social-timeline-block .ctl-social-timeline-content { background:'.$content_bg_color.'; }';

 $styles_hori.='

/* Horizontal Styles */

.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post a.ctl_read_more {
    color: '.$content_color.';
    border: 1px solid '.$content_color.';
    filter: brightness(1.05);
    -webkit-filter: brightness(1.05);
}
.cool-timeline-horizontal .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post a.ctl_read_more:hover {
    filter: brightness(1.2);
    -webkit-filter: brightness(1.2);
}

.cool-timeline-horizontal .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post .content-title {
    background:'.$first_post_color.';
    color:'.$title_color.';
}

.cool-timeline-horizontal .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post .content-title {
    background:'.$second_post_color.';
    color:'.$title_color.';
}

.cool-timeline-horizontal .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post .content-title a {
    color:'.$title_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide.slick-current:after {
    border-bottom-color:'.$first_post_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide.slick-current:after {
    border-bottom-color:'.$second_post_color.';
}

.cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post {
    border-top: 4px solid '.$first_post_color.';
}

.cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post {
    border-top: 4px solid '.$second_post_color.';
}

.cool-timeline-horizontal.ht-design-2 .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide:after {
    border-bottom-color: '.$first_post_color.';
}
.cool-timeline-horizontal.white-timeline.ht-design-3 .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide:after {
    border: 2px solid '.$first_post_color.';
    border-bottom-color: '.$first_post_color.';
}

.cool-timeline-horizontal.ht-design-2 .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide:after {
    border-bottom-color:'.$second_post_color.';
}
.cool-timeline-horizontal.white-timeline.ht-design-3 .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide:after {
    border: 2px solid '.$second_post_color.';
    border-bottom-color: '.$second_post_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n) span.icon-placeholder, .cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n) .ctl-story-time:after, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider .slick-list li:nth-child(2n) span.icon-placeholder, .cool-timeline-horizontal .wrp-desgin-4 ul.ctl_h_nav  .slick-list li:nth-child(2n) .ctl-story-time:after {
    background: '.$first_post_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1) span.icon-placeholder, .cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1) .ctl-story-time:after, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider .slick-list li:nth-child(2n+1) span.icon-placeholder, .cool-timeline-horizontal .wrp-desgin-4 ul.ctl_h_nav  .slick-list li:nth-child(2n+1) .ctl-story-time:after {
    background:'.$second_post_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li span.ctl-story-time, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider .slick-list li span.ctl-story-time {
    color:'.$ctl_date_color.'!important;
    font-size:'.$ctl_date_s.';
    font-family:'.$ctl_date_f.';
    font-weight:'.$ctl_date_w.';
    line-height:normal;
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n) span.ctl-story-time, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider .slick-list li:nth-child(2n) span.ctl-story-time, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post h2.content-title-simple a, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n).slick-slide .timeline-post h2.content-title-simple a {
    color:'.$first_post_color.';
}

.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:nth-child(2n+1) span.ctl-story-time, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider .slick-list li:nth-child(2n+1) span.ctl-story-time, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post h2.content-title-simple a, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li:nth-child(2n+1).slick-slide .timeline-post h2.content-title-simple a {
    color:'.$second_post_color.';
}
';
 $styles_hori.='.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title, .cool-timeline-horizontal.dark-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title, .cool-timeline-horizontal.light-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title-simple, .cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title a, .cool-timeline-horizontal.dark-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title a, .cool-timeline-horizontal.light-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title a, .cool-timeline-horizontal.ht-design-3 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title-simple a, .cool-timeline-horizontal.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post h2.content-title-simple a
 {
    font-size:'.$post_title_s.';
    font-family:'.$post_title_f.';
    font-weight:'.$post_title_w.';
    text-transform:'.$post_title_text_style.';
    color:#fff;
}
.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post .content-details, .cool-timeline-horizontal.dark-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post .content-details, .cool-timeline-horizontal.light-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post .content-details {
    font-size:'.$post_content_s.';
    font-family:'.$post_content_f.';
    font-weight:'.$post_content_w.';
}

.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post {
    color:'.$content_color.';
    background:'.$content_bg_color.';
}
.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post a {
    color:'.$content_color.';
    filter: brightness(1.05);
    -webkit-filter: brightness(1.05);
}
.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post a:hover {
    color:'.$content_color.';
    filter: brightness(1.2);
    -webkit-filter: brightness(1.2);
}';


   
 $styles_hori.='.cool-timeline-horizontal.white-timeline .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post .ctl_info {
    color:'.$content_color.';
}';
 

 $styles_hori.='.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal .clt_carousel_slider ul.slick-slider button.slick-next:before, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal .wrp-desgin-4 ul.slick-slider button.slick-next:before {
    color:'.$line_color.';
}

.cool-timeline-horizontal.light-timeline .clt_carousel_slider ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal.light-timeline .clt_carousel_slider ul.slick-slider button.slick-next:before, .cool-timeline-horizontal.light-timeline .wrp-desgin-4 ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal.light-timeline .wrp-desgin-4 ul.slick-slider button.slick-next:before {
    color:#666;
}

.cool-timeline-horizontal.dark-timeline .clt_carousel_slider ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal.dark-timeline .clt_carousel_slider ul.slick-slider button.slick-next:before, .cool-timeline-horizontal.dark-timeline .wrp-desgin-4 ul.slick-slider button.slick-prev:before, .cool-timeline-horizontal.dark-timeline .wrp-desgin-4 ul.slick-slider button.slick-next:before {
    color:#222;
}';



 $styles_hori.='
.cool-timeline-horizontal.white-timeline ul.ctl_h_nav  .slick-list li:nth-child(2n) .ctl-story-time .ctl-tooltips span{
 background: '.$first_post_color.';
}
.cool-timeline-horizontal.white-timeline ul.ctl_h_nav  .slick-list li:nth-child(2n+1) .ctl-story-time .ctl-tooltips span {
 background: '.$second_post_color.';
}
.cool-timeline-horizontal.white-timeline ul.ctl_h_nav  .slick-list li:nth-child(2n) .ctl-story-time .ctl-tooltips span:after
{
    border-top-color: '.$first_post_color.';
}
.cool-timeline-horizontal.white-timeline ul.ctl_h_nav  .slick-list li:nth-child(2n+1) .ctl-story-time .ctl-tooltips span:after
{
    border-top-color:'.$second_post_color.';
    }
';

 $styles_hori.='.cool-timeline-horizontal.white-timeline.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post {
    background: -moz-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, '.$content_bg_color.'), color-stop(100%, rgba(255, 255, 255, 0)));
    background: -webkit-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -o-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -ms-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: linear-gradient(0deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);}';

    $styles_hori.='.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:before, .cool-timeline-horizontal .wrp-desgin-4 ul.ctl_h_nav  .slick-list li:before  {
    background-color:'.$line_color.';
    background-image: -webkit-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -moz-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%,'.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -ms-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);}';

   $styles.=" /*----------custom css---------*/    ".$custom_styles;
  $styles_hori.="/*----------custom css---------*/    ".$custom_styles;


$styles.='/* @responsive styling
----------------------------------------------- */
@media (max-width: 860px) {
	.main-design-4 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.even .timeline-content .content-title:before {
    border: 2px solid '.$first_post_color.';
	}
	.main-design-4 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.odd .timeline-content .content-title:before {
    border: 2px solid '.$second_post_color.';
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .timeline-content h2.content-title-2, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .timeline-content h2.content-title-2 a, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .timeline-content .clt-compact-date {
	color:'.$first_post_color.';
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .timeline-content h2.content-title-2, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .timeline-content h2.content-title-2 a, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .timeline-content .clt-compact-date {
	color:'.$second_post_color.';
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.even .timeline-content {
	border-left:6px solid '.$first_post_color.';
	border-right:0;
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.odd .timeline-content {
	border-left:6px solid '.$second_post_color.';
	border-right:0;
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.odd .timeline-content:before {
    border-right-color:'.$second_post_color.';
	border-left-color:transparent;
	}
	.main-design-3 .cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.even .timeline-content:before {
    border-right-color:'.$first_post_color.';
	border-left-color:transparent;
	}
	.cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .timeline-content .content-title, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.even .icon-dot-full, .cool-timeline.white-timeline.compact .timeline-post.icons_yes.ctl-left.even .timeline-icon {
		background:'.$first_post_color.';
	}
	.cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .timeline-content .content-title, .cool-timeline.white-timeline.compact .timeline-post.ctl-left.odd .icon-dot-full, .cool-timeline.white-timeline.compact .timeline-post.icons_yes.ctl-left.odd .timeline-icon {
		background:'.$second_post_color.';
	}
	.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.odd .timeline-content .content-title:after {
		border-right-color:'.$second_post_color.';
	}
	.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left.even .timeline-content .content-title:after {
		border-right-color:'.$first_post_color.';
	}
	
    .cool-timeline .light-grey-post .timeline-content:after,
    .cool-timeline .light-grey-post .timeline-content:before,
    .cool-timeline .light-grey-post .even .timeline-content:after,
    .cool-timeline .light-grey-post .even .timeline-content:before,
.cool-timeline.white-timeline.compact .timeline-post.timeline-mansory.ctl-left .timeline-content .content-title:after	{
        border-right-color:'.$first_post_color.';
        border-left-color:transparent;
    }
.cool-timeline .custom-pagination {
    margin-left: 30px;
}
.cool-timeline.one-sided .timeline-post .icon-dot-full {
    margin: 27px 0 0 -7px;
}
.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-content::before {
     border-right-color: '.$first_post_color.'; border-left-color:transparent;
}
.cool-social-timeline.white-timeline .ctl-social-timeline-block.even .ctl-social-timeline-content { border-left:5px solid '.$first_post_color.'; border-right:1px solid #ccc; }


.main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.light-timeline .timeline-post.odd .timeline-content, .main-design-3 .cool-timeline.dark-timeline .timeline-post.odd .timeline-content {
    border-left: 6px solid '.$second_post_color.';
    border-right: 1px solid #ccc;
}
.main-design-3 .cool-timeline .timeline-post.odd .timeline-content:before {
    right:inherit;
    left:-30px;
}';

$styles.='.main-design-3 .cool-timeline.white-timeline .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.light-timeline .timeline-post.odd .timeline-content:before, .main-design-3 .cool-timeline.dark-timeline .timeline-post.odd .timeline-content:before {
    border-right-color: '.$second_post_color.';
    border-left-color: transparent;
}

.main-design-4 .cool-timeline .timeline-post.even .timeline-content .content-title:before, .main-design-4 .cool-timeline .timeline-post.odd .timeline-content .content-title:before { top:33px; }

.main-design-4 .cool-timeline.light-timeline.one-sided .timeline-post.even .timeline-content .content-title:before, .main-design-4 .cool-timeline.light-timeline.one-sided .timeline-post.odd .timeline-content .content-title:before, .main-design-4 .cool-timeline.dark-timeline.one-sided .timeline-post.even .timeline-content .content-title:before, .main-design-4 .cool-timeline.dark-timeline.one-sided .timeline-post.odd .timeline-content .content-title:before { top:27px; }

    .cool-timeline.white-timeline .timeline-post.odd .timeline-content .content-title:before{
        border-right-color:'.$second_post_color.';
        border-left-color:transparent;
    }
   .cool-timeline .timeline-post.odd .timeline-content .content-title:before{
        border-left-color:transparent;
    }
    .cool-timeline.light-timeline .timeline-post.odd .timeline-content .content-title:before
   {
       border-right-color: #eaeaea;
       left: -25px;
       right: inherit;
   }
   .cool-timeline.dark-timeline .timeline-post.odd .timeline-content .content-title:before
   {
       border-right-color: #000;
   }';

 $styles.='.cool-timeline-horizontal.white-timeline.ht-design-4 .clt_caru_slider ul.slick-slider .slick-list li.slick-slide .timeline-post {
    background: -moz-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, '.$content_bg_color.'), color-stop(100%, rgba(255, 255, 255, 0)));
    background: -webkit-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -o-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: -ms-linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);
    background: linear-gradient(0deg, rgba(255, 255, 255, 0) 0%, '.$content_bg_color.' 100%);';

    $styles.='.cool-timeline-horizontal .clt_carousel_slider ul.slick-slider .slick-list li:before, .cool-timeline-horizontal .wrp-desgin-4 ul.ctl_h_nav  .slick-list li:before  {
    background-color:'.$line_color.';
    background-image: -webkit-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -moz-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%,'.$line_color.' 92%, '.$line_color.' 100%);
    background-image: -ms-linear-gradient(top, '.$line_color.' 0%, '.$line_color.' 8%, '.$line_color.' 92%, '.$line_color.' 100%);}';




 $styles.='.cool-timeline.light-timeline .timeline-year:before {
    content: "";
    width: 42px;
    background: #eaeaea;
    border: 2px solid #eaeaea;
    position: absolute;
    left: -50px;
    top: 48.5%;
   }
   .cool-timeline.light-timeline .timeline-year, .cool-timeline.dark-timeline .timeline-year, .cool-timeline.white-timeline .timeline-year
   {
    left:100px;
   }
   .cool-timeline.light-timeline.one-sided .timeline-year, .cool-timeline.light-timeline.one-sided .timeline-year, .cool-timeline.one-sided .timeline-year
   {
    left:85px;
   }

   .cool-timeline.dark-timeline .timeline-year:before {
    content: "";
    width: 42px;
    background: #222;
    border: 2px solid #000;
    position: absolute;
    left: -50px;
    top: 48.5%;
   }
   .cool-timeline.white-timeline .timeline-year:before {
    content: "";
    width: 42px;
    border: 2px solid '.$line_color.';
    position: absolute;
    left: -50px;
    top: 48.5%;
   }
.main-design-2 .cool-timeline .timeline-year:before, .main-design-3 .cool-timeline .timeline-year:before, .main-design-4 .cool-timeline .timeline-year:before {
    width: 25px;
    left: -32px;
}';
$styles.=' .cool-timeline .light-grey-post .timeline-content:after,
    .cool-timeline .light-grey-post .timeline-content:before,
    .cool-timeline .light-grey-post .odd .timeline-content:after,
    .cool-timeline .light-grey-post .odd .timeline-content:before {
        border-right-color:'.$second_post_color.';
        border-left-color:transparent;
    }
} ';
 $styles.='      ';

 

         wp_add_inline_style( 'ctl_styles',$styles );
         wp_add_inline_style( 'ctl-styles-horizontal',$styles_hori );

        }

       function ctl_navigation_styles() {
            $ctl_options_arr = get_option('cool_timeline_options');
            //var_dump($ctl_options_arr['navigation_position']);
            $navigation_position = $ctl_options_arr['navigation_position'] ? $ctl_options_arr['navigation_position'] : 'right';
            $output = '<style type="text/css">
                    .ctl-bullets-container {
                display: table;
                position: fixed;
                ' . $navigation_position . ': 0;
                height: 100%;
                z-index: 1049;
                font-weight: normal;
            }.clt-compact-preloader {
             border-radius:3px; position:absolute; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: rgba(245, 245, 245, 0.95) url("'.CTP_PLUGIN_URL.'/images/clt-compact-preloader.gif") no-repeat center 10px; }
	</style>';

            echo $output;
        }  
        
    }

}