<?php

function ctl_google_fonts(){
        
            $ctl_options_arr = get_option('cool_timeline_options');
            $post_content_face = $ctl_options_arr['post_content_typo']['face'];
            $post_title = $ctl_options_arr['post_title_typo']['face'];
            $main_title = $ctl_options_arr['main_title_typo']['face'];
            $date_typo = $ctl_options_arr['ctl_date_typo']['face'];
            $selected_fonts = array($post_content_face, $post_title, $main_title,$date_typo);

            /*
            * google fonts
            */
            // Remove any duplicates in the list
            $selected_fonts = array_unique($selected_fonts);
            // If it is a Google font, go ahead and call the function to enqueue it
            $gfont_arr=array();

        if(is_array($selected_fonts)){

            foreach ($selected_fonts as $font) {
                if ($font && $font != 'inherit') {
                    if ($font == 'Raleway')
                        $font = 'Raleway:100';
                    $font = str_replace(" ", "+", $font);
                     $gfont_arr[]=$font;
                }
            }
           if(is_array($gfont_arr)&& !empty($gfont_arr)){
             $allfonts=implode("|",$gfont_arr);   
           wp_register_style("ctl_gfonts", "https://fonts.googleapis.com/css?family=$allfonts", false, null, 'all');
            }
          }
            wp_register_style("ctl_default_fonts", "https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800", false, null, 'all');

}
    
function ctl_common_assets(){
    
    wp_register_script('ctl_prettyPhoto', CTP_PLUGIN_URL . 'js/jquery.prettyPhoto.js', array('jquery'), null, true);
            wp_register_script('ctl_scripts', CTP_PLUGIN_URL . 'js/ctl_scripts.js', array('jquery'), null, true);
            wp_register_script('ctl_jquery_flexslider', CTP_PLUGIN_URL . 'js/jquery.flexslider-min.js', array('jquery'), null, true);
            wp_register_script('section-scroll-js', CTP_PLUGIN_URL . 'js/jquery.section-scroll.js', array('jquery'), null, true);
           
           wp_register_style('ctl_prettyPhoto', CTP_PLUGIN_URL . 'css/prettyPhoto.css', null, null, 'all');
           
           wp_register_style('ctl_styles', CTP_PLUGIN_URL . 'css/ctl_styles.css', null, null, 'all');
           
            wp_register_style('section-scroll', CTP_PLUGIN_URL . 'css/section-scroll.css', null, null, 'all');
            wp_register_style('ctl_flexslider_style', CTP_PLUGIN_URL . 'css/flexslider.css', null, null, 'all');

            wp_register_style('ctl_animate', CTP_PLUGIN_URL . 'css/animate.min.css', null, null, 'all');
            wp_register_script('ctl_viewportchecker', CTP_PLUGIN_URL . 'js/jquery.viewportchecker.js', array('jquery'), null, true);

          wp_register_script('c_masonry', CTP_PLUGIN_URL . 'js/masonry.pkgd.min.js', array('jquery'), null, true);
            /*
             * Horizontal timeline
             */

            wp_register_script('ctl_horizontal_scripts', CTP_PLUGIN_URL . 'js/ctl_horizontal_scripts.js', array('jquery'), null, true);
            wp_register_script('ctl-slick-js','https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', array('jquery'), null, true);
            wp_register_style('ctl-styles-horizontal', CTP_PLUGIN_URL . 'css/ctl-styles-horizontal.css', null, null, 'all');
            wp_register_style('ctl-styles-slick', CTP_PLUGIN_URL . 'css/slick.css', null, null, 'all');


             wp_register_style('ctl-compact-tm', CTP_PLUGIN_URL . 'css/ctl-compact-tm.css',array('ctl_styles'), null, 'all');

            ctl_google_fonts();
}


function ctl_get_story_date($post_id) {

    $ctl_story_date = get_post_meta($post_id, 'ctl_story_date', true);



    if ($ctl_story_date) {

        if (preg_match("/\d{4}/", $ctl_story_date, $match)) {

            $year = intval($match[0]); //converting the year to integer

            if ($year >= 1970) {



                $posted_date = date("M d , Y", strtotime("$ctl_story_date"));

            } else {

                $date = date_create($ctl_story_date);

                if (!$date) {

                    $e = date_get_last_errors();

                    foreach ($e['errors'] as $error) {

                        return "$error\n";

                    }

                    exit(1);

                }

                $posted_date = date_format($date, __("M d , Y", 'cool-timeline'));

            }

        }

        return  $posted_date;

    }

}





function ctl_pagination($numpages = '', $pagerange = '', $paged='') {



    if (empty($pagerange)) {

        $pagerange = 2;

    }

        if ( get_query_var('paged') ) { 
            $paged = get_query_var('paged'); 
            } elseif ( get_query_var('page') ) { 
            $paged = get_query_var('page'); 
            } else { 
            $paged = 1; 
            }
     if ($numpages == '') {

        global $wp_query;

        $numpages = $wp_query->max_num_pages;

        if(!$numpages) {

            $numpages = 1;

        }

    }

    $big = 999999999; 

    $of_lbl = __( ' of ', 'cool-timeline' ); 

    $page_lbl = __( ' Page ', 'cool-timeline' ); 

    $pagination_args = array(

        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),

        'format' => '?paged=%#%',

        'total'           => $numpages,

        'current'         => $paged,

        'show_all'        => False,

        'end_size'        => 1,

        'mid_size'        => $pagerange,

        'prev_next'       => True,

        'prev_text'       => __('&laquo;'),

        'next_text'       => __('&raquo;'),

        'type'            => 'plain',

        'add_args'        => false,

        'add_fragment'    => ''

    );



    $paginate_links = paginate_links($pagination_args);

    $ctl_pagi='';

    if ($paginate_links) {

        $ctl_pagi .= "<nav class='custom-pagination'>";

        $ctl_pagi .= "<span class='page-numbers page-num'> ".$page_lbl . $paged . $of_lbl . $numpages . "</span> ";

        $ctl_pagi .= $paginate_links;

        $ctl_pagi .= "</nav>";

        return $ctl_pagi;

    }



}


function ctl_get_ctp() {
    global $post, $typenow, $current_screen;

 
    if ( $post && $post->post_type )
        return $post->post_type;

  
    elseif( $typenow )
        return $typenow;

   
    elseif( $current_screen && $current_screen->post_type )
        return $current_screen->post_type;

  
    elseif( isset( $_REQUEST['post_type'] ) )
        return sanitize_key( $_REQUEST['post_type'] );

  
    return null;
}


if ( ! function_exists( 'ctl_entry_taxonomies' ) ) :
  
    function ctl_entry_taxonomies() {
        $categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'cool-timeline' ) );
        $cat_meta='';
        if ( $categories_list) {
            $cat_meta .= sprintf( '<i class="fa fa-folder-open" aria-hidden="true"></i><span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x( 'Categories', 'Used before category names.', 'cool-timeline' ),
                $categories_list
            );
        }
        return $cat_meta;
    }
endif;

function ctl_post_tags() {
    $tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'cool-timeline' ) );
    if ( $tags_list ) {
        return sprintf( '<span class="tags-links"><i class="fa fa-bookmark"></i><span class="screen-reader-text">%1$s </span>%2$s</span>',
            _x( 'Tags', 'Used before tag names.', 'cool-timeline' ),
            $tags_list
        );
    }
}

