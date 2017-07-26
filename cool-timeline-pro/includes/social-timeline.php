<?php
if (!class_exists('CoolSocialTimeline')) {

    class CoolSocialTimeline
    {
        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions
            add_action('init', array(&$this, 'social_re_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'ctl_social_scripts'));

        }

        function social_re_shortcode() {
            add_shortcode('cool-social-timeline', array(&$this, 'cstv'));
         }
        function cstv($atts, $content = null)
        {
            wp_enqueue_style('ctl_styles');
            wp_enqueue_style('ctl-social-timeline-style');
            wp_enqueue_script('ctl-social-timeline-mod');
            wp_enqueue_script('ctl-social-timeline');
            wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
             wp_enqueue_style('ctl_gfonts');
             wp_enqueue_style('ctl_default_fonts');

            $out = '';
            $design_cls = '';
            $attribute = shortcode_atts(array(
                'show-posts' => '',
                'layout' => 'default',
                'skin' => '',
                'type' => '',
                'icons' => '',
                'animations' => '',
                'fb-app-id'=>'',
                'fb-app-secret-key'=>'',
                'fb-page-name'=>'',
            ), $atts);

            $ctl_options_arr = get_option('cool_timeline_options');
            if (isset($attribute['fb-app-id']) && !empty($attribute['fb-app-id'])) {
                $facbook_app_id = $attribute['fb-app-id'];
            } else {
                $out .= "<h2>Please enter Facebook APP ID</h2>";
            }

            if (!empty($attribute['fb-app-secret-key'])) {
                $facbook_app_secret_key = $attribute['fb-app-secret-key'];
            } else {
                $out .= "<h2>Please enter Facebook APP Secret key</h2>";
            }
            if (!empty($attribute['fb-page-name'])) {
                $facbook_page_name = $attribute['fb-page-name'];
            } else {
                $out .= "<h2>Please enter Facebook page name</h2>";
            }
            if (isset($attribute['show-posts']) && !empty($attribute['show-posts'])) {
                $post_limit = $attribute['show-posts'];
            } else {
                $post_limit = 10;
            }


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

            $ctl_animation='';

            if (isset($attribute['animations'])) {
                $ctl_animation=$attribute['animations'];
            }else{
                $ctl_animation ='bounceInUp';
            }

            $ctl_html = '';
            $ctl_format_html = '';
            $display_s_date = '';
            if (isset($ctl_options_arr['custom_date_formats']) && !empty($ctl_options_arr['custom_date_formats'])) {
                $date_formats = $ctl_options_arr['custom_date_formats'] ? $ctl_options_arr['custom_date_formats'] : "M d";
            } else {
                $date_formats = $ctl_options_arr['ctl_date_formats'] ? $ctl_options_arr['ctl_date_formats'] : "M d";
            }


            $display_year = '';
            $format = __('d/m/Y', 'cool-timeline');
            $output = '';
            $multiple_posts = array();
          /*  $output .= '<!-- Preloader -->
                    <div id="preloader">
                        <div id="status">&nbsp;</div>
                    </div>';
           */
            $profile_pic='';
            if (!empty($facbook_app_id) && !empty($facbook_app_secret_key) && !empty($facbook_page_name)) {

                $token = $facbook_app_id . '|' . $facbook_app_secret_key;
                $username = '';
                $graph_url = "https://graph.facebook.com/v2.8/" . $facbook_page_name . "?fields=posts.limit($post_limit){id,name,source,message,full_picture,link,updated_time,description,likes,comments,shares}&access_token=" . $token;
                $pp_pic = "https://graph.facebook.com/v2.8/" . $facbook_page_name . "/picture?type=large";


                try {


                    array_push($multiple_posts, json_decode(@file_get_contents($graph_url), true));
                    $p_pick='';
                } catch (Exception $ex) {
                    $out = esc_html("Sorry, we are not able to get this feed. Are you sure, your internet connection is fine ? ");

                }



                 if (!empty($multiple_posts)):
                    $overall_key = 0;
                    $like_count = '';
                    $feed_des = '';
                    $name = '';
                    $link = '';
                    $link_open = '';
                    $link_close = '';


                     $profile_pic='';
                     $profile_pic .= '<div class="avatar_container row"><span ><a target="_blank" href="https://www.facebook.com/'. $facbook_page_name .'">';
                     $profile_pic .='<img src="'.$pp_pic.'" class=" center-block img-responsive img-rounded">';
                     $profile_pic .= '</a></span></div>';
                     $i=0;
                     foreach ($multiple_posts as $key) {
                        $page_posts = $multiple_posts[$overall_key]['posts']['data'];
                        $iteration = 0;

                        if (!empty($page_posts)) {

                            $p_pic_url=$page_posts[0]['full_picture'];
                            $p_link=$page_posts[0]['link'];
                            $p_shares=$page_posts[0]['shares'];



                            foreach ($page_posts as $post_feed) {
                                $feed_link = "http://www.facebook.com/" . $post_feed['id'];
                                $video = "";
                                if (isset($post_feed['source'])):
                                    $video = $post_feed['source'];
                                endif;
                                if (isset($post_feed['message'])):
                                    $feed_des = $post_feed['message'];
                                endif;
                                if (isset($post_feed['name'])):
                                    $name = $post_feed['name'];
                                endif;
                                if (isset($post_feed['link'])):
                                    $link = $post_feed['link'];
                                    $link_open = '<a target="_blank" href="' . $link . '">';
                                    $link_close = '</a>';
                                endif;
                                $feed_img = "";
                                $shr_count = "";

                                if (isset($post_feed['full_picture'])):
                                    $feed_img = $post_feed['full_picture'];
                                endif;

                                if ($iteration % 2 == 0) {

                                    $even_odd = "even";

                                } else {

                                    $even_odd = "odd";

                                }
                                $feed_time = strtotime($post_feed['updated_time']);
                                $like_count = isset($post_feed['likes']['data']) ? count($post_feed['likes']['data']) : '';
                                $cmt_count = isset($post_feed['likes']['data']) ? count($post_feed['likes']['data']) : '';
                                if (isset($post_feed['shares']['count'])):
                                    $shr_count = $post_feed['shares']['count'];
                                endif;
                                $out .= '<div class="ctl-social-timeline-block '.$even_odd.'">';
                                if (!empty($feed_img) && !empty($feed_des)):
                                    $out .= '<div class="ctl-social-timeline-img cd-location" >
                        <img src="' . CTP_PLUGIN_URL . '/images/cd-icon-picture.svg" alt="Picture">
                        </div> <!-- ctl-social-timeline-img -->';
                                elseif (!empty($feed_img) && empty($feed_des)):
                                    $out .= '<div class="ctl-social-timeline-img cd-location" >
                    <img src="' . CTP_PLUGIN_URL . '/images/cd-icon-picture.svg" alt="Picture">
                    </div> <!-- ctl-social-timeline-img -->';
                                elseif (empty($feed_img) && !empty($feed_des)) :
                                    $out .= '<div class="ctl-social-timeline-img cd-picture" >
                        <img src="' . CTP_PLUGIN_URL . '/images/pencil.png" alt="Picture">
                        </div> <!-- ctl-social-timeline-img -->';
                                elseif (!empty($video)):
                                    $out .= '<div class="ctl-social-timeline-img cd-location" >
                    <img src="' . CTP_PLUGIN_URL . '/images/cd-icon-movie.svg" alt="Picture">
                    </div> <!-- ctl-social-timeline-img -->';
                                endif;
                                $out .= '<div class="ctl-social-timeline-content" >';
                                if ($feed_img && empty($video)):
                                    if (!empty($aft_imgpost) && !empty($feed_des)) {
                                        $feed_des = substr($feed_des, 0, $aft_imgpost) . "...";
                                    }
                                    if (!empty($name)) {
                                        $p_name = '<h2>' . $name . '</h2>';
                                    } else {
                                        $p_name = '';
                                    }

                         $out .= "<div class='feed_img feed_left' style=''>" . $link_open . "<img src='" . $feed_img . "' alt='Feed Image' title='" . $feed_des . "' />" . $link_close . "<div class='ctl_social_cont'>
							" . $p_name . $feed_des . "</div></div>";
                                elseif ($video):
                                    if (!empty($aft_imgpost) && !empty($feed_des)) {
                                        $feed_des = substr($feed_des, 0, $aft_imgpost) . "...";
                                    }
                                    $video_id = explode("_", $post_feed['id']);
                                    $out .= '<div class="feed_img feed_left" style="">
                            <div class="fb-video" data-href="https://www.facebook.com/facebook/videos/' . $video_id[1] . '" data-width="500">
                            </div><div class="ctl_social_cont">' . $feed_des . '</div></div>';
                                else:
                                    if (!empty($aft_simpost)) {
                                        $feed_des = substr($feed_des, 0, $aft_simpost) . "...";
                                    }
                                    $out .= '<div class="ctl_social_cont"><p>' . $feed_des . '</p></div>';
                                endif;

                                $feed_des = "";
                                $out .= '<a href="' . $feed_link . '" class="cd-read-more" style="">Read more</a>
                <span class="cd-date">' . date("F j, Y", $feed_time) . '</span>
                </div> <!-- ctl-social-timeline-content -->
				</div> <!-- ctl-social-timeline-block -->';
                                $iteration++;
                            }
                        }
                        $overall_key++;
                    }

                     $output .= '<div class="cool-social-timeline ' . $wrp_cls . '"><div class="timeline-wrapper ' . $wrapper_cls . '" >';
                     $output .= $profile_pic;
                     $output .= '<section id="ctl-social-timeline" class="ctl-process-container"><div id="fb-root"></div><style>.ctl-social-timeline-block:nth-child(even) .ctl-social-timeline-content::before {  border-right-color: ; } .ctl-social-timeline-content::before { } #ctl-social-timeline::before{    background: ; }</style>';
                     $output .=$out;
                     $output .= "</section></div> </div><!-- ctl-social-timeline -->";
                endif;
            }
        return $output;
        }
        /*
                * Include this plugin's public JS & CSS files on posts.
                */

        function ctl_social_scripts() {
            /*
            * social timeline
            */
            wp_register_style('ctl_styles', CTP_PLUGIN_URL . 'css/ctl_styles.css', null, null, 'all');

            wp_register_script('ctl-social-timeline-mod', CTP_PLUGIN_URL . 'js/modernizr.js', array('jquery'), null, true);
            wp_register_script('ctl-social-timeline', CTP_PLUGIN_URL . 'js/social-main.js', array('jquery'), null, true);
            wp_register_style('ctl-social-timeline-style', CTP_PLUGIN_URL . 'css/social-timeline-style.css', null, null, 'all');

             ctl_google_fonts();

        }
    }
}