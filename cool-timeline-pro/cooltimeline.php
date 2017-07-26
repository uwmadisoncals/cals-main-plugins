<?php 
/*
  Plugin Name:Cool Timeline Pro
  Plugin URI:http://www.cooltimeline.com
  Description:Use Cool Timeline pro wordpress plugin to showcase your life or your company story in a vertical timeline format. Cool Timeline Pro is an advanced timeline plugin that creates responsive vertical storyline automatically in chronological order based on the year and date of your posts.
  Version:2.2
  Author:Cool Timeline Team
  Author URI:http://www.cooltimeline.com
  License:GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages 
  Text Domain:cool-timeline
 */
/** Configuration * */

if (!defined('CTLPV')){
    define('CTLPV', '2.2');

}

define('CTP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CTP_PLUGIN_DIR', plugin_dir_path(__FILE__));
defined( 'CTP_FA_DIR' ) or define( 'CTP_FA_DIR', plugin_dir_path( __FILE__ ).'/fa-icons/' );
defined( 'CTP_FA_URL' ) or define( 'CTP_FA_URL', plugin_dir_url( __FILE__ ).'/fa-icons/'  );

if (!class_exists('CoolTimelinePro')) {

    class CoolTimelinePro {

        /**
         * Construct the plugin objects
         */
        public function __construct() {

            $this->plugin_path = plugin_dir_path(__FILE__);
            // Installation and uninstallation hooks
           register_activation_hook(__FILE__ , array($this,'ctp_activation_before'));
            //include the main class file
            require_once( CTP_PLUGIN_DIR ."admin-page-class/admin-page-class.php");
            require_once CTP_PLUGIN_DIR . 'includes/ctl-helpers.php';
            // cooltimeline post type
            require_once CTP_PLUGIN_DIR . 'includes/cool_timeline_posttype.php';
            //include the main class file
            require_once CTP_PLUGIN_DIR . "meta-box-class/my-meta-box-class.php";

            // vc addon
            require_once CTP_PLUGIN_DIR . "includes/cool_vc_addon.php";
            /*
             * View
             */
            require_once CTP_PLUGIN_DIR . 'includes/cool_timeline_custom_styles.php';
            require_once CTP_PLUGIN_DIR . 'includes/cool_timeline_shortcode.php';
            require_once CTP_PLUGIN_DIR . 'includes/views/ct-shortcode-new.php';
            require_once CTP_PLUGIN_DIR . 'includes/social-timeline.php';

            $cool_timeline_posttype = new CoolTimeline_Posttype();
             new CoolTimeline_Shortcode();
             new CoolContentTimeline();
             new CoolSocialTimeline();
            /*
             * Options panel
             */
            $this->ctl_option_panel();
            /*
             *  custom meta boxes 
             */
            $this->clt_meta_boxes();

            new CoolVCAddon();

            /**
             * Add an instance of our plugin to WordPress
             **/

           
            require CTP_PLUGIN_DIR .'fa-icons/fa-icons-class.php';
            new Ctl_Fa_Icons();
            // Include other PHP scripts
            add_action( 'init', array( $this, 'include_files' ) );


            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));

            // add a tinymce button that generates our shortcode for the user
            add_action('after_setup_theme', array(&$this, 'ctl_add_tinymce'));
            add_image_size('ctl_avatar', 250, 250, true); // Hard crop left top
            // Register a new custom image size
            add_action('plugins_loaded', array(&$this, 'clt_load_plugin_textdomain'));

            //Fixed bridge theme confliction using this action hook
            add_action( 'wp_print_scripts', array(&$this,'ctl_deregister_javascript'), 100 );

            add_action( 'admin_notices',array(&$this,'cool_admin_messages'));
            add_action( 'wp_ajax_hideRating',array(&$this,'cool_HideRating' ));
         }


function ctp_activation_before() {

    if (is_plugin_active( 'cool-timeline/cooltimeline.php' ) ) 
        {
        deactivate_plugins( 'cool-timeline/cooltimeline.php' );
       }
        update_option("cool-timelne-v",CTLPV);
        update_option("cool-timelne-type","PRO");
      
        update_option("cool-timelne-installDate",date('Y-m-d h:i:s') );
        update_option("cool-timelne-ratingDiv","no");

      $ctl_settings=get_option('cool_timeline_options');
      if(is_array($ctl_settings) && !empty($ctl_settings)){
      if(!isset($ctl_settings['enable_navigation']) && !in_array('enable_navigation', $ctl_settings)){
         update_option("ctl-can-migrate","yes");
        }else{
           update_option("ctl-can-migrate","no");
        }
       }else{
        update_option("ctl-can-migrate","no");
       }
  }
   
function clt_load_plugin_textdomain() {

            $rs = load_plugin_textdomain('cool-timeline', FALSE, basename(dirname(__FILE__)) . '/languages/');
        }



        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=cool_timeline_page">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }
        /**
         * Include other PHP scripts for the plugin
         * @return void
         *
         **/
        public function include_files() {
            // Files specific for the front-ned
            if ( ! is_admin() ) {
                // Load template tags (always last)
                include CTP_PLUGIN_DIR .'fa-icons/includes/template-tags.php';
            }
        }

        /*
        * Fixed Bridge theme confliction
        */
        function ctl_deregister_javascript() {

            if(is_admin()) {
                $screen = get_current_screen();
                if ($screen->base == "toplevel_page_cool_timeline_page") {
                    wp_deregister_script('default');
                }
            }
        }


        function ctl_option_panel() {

            /**
             * configure your admin page
             */
            $config = array(
                'menu' => array('top' => 'cool_timeline'), //sub page to settings page
                'page_title' => __('Cool Timeline Pro', 'apc'), //The name of this page 
                'capability' => 'manage_options', // The capability needed to view the page
                'option_group' => 'cool_timeline_options', //the name of the option to create in the database
                'id' => 'cool_timeline_page', // meta box id, unique per page
                'fields' => array(), // list of fields (can be added by field arrays)
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );

            /**
             * instantiate your admin page
             */
            $options_panel = new BF_Admin_Page_Class_Pro($config);
            $options_panel->OpenTabs_container('');

            /**
             * define your admin page tabs listing
             */
            $options_panel->TabsListing(array(
                'links' => array(
                    'options_1' => __('General Settings', 'apc'),
                    'options_2' => __('Style Settings', 'apc'),
                    'options_3' => __('Typography Settings', 'apc'),
                    'options_4' => __('Stories Settings', 'apc'),
                    'options_5' => __('Date Settings', 'apc'),
                    'options_7' => __('Navigation Settings', 'apc'),
                    'options_8' => __('Timeline Display', 'apc'),
                    'options_6' => __('Extra Settings', 'apc'),
                     'options_10' => __('Migrations', 'apc'),
                    
                )
            ));

            /**
             * Open admin page first tab
             */
            $options_panel->OpenTab('options_1');

            /**
             * Add fields to your admin page first tab
             * 
             * Simple options:
             * input text, checbox, select, radio 
             * textarea
             */
            //title
            $options_panel->Title(__("General Settings", "apc"));
            $options_panel->addText('title_text', array('name' => __('Timeline Title (Default) ', 'apc'), 'std' => 'Cool Timeline', 'desc' => __('', 'apc')));

            //select field
            $options_panel->addSelect('title_tag', array('h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6'), array('name' => __('Title Heading Tag ', 'apc'), 'std' => array('h1'), 'desc' => __('', 'apc')));
            $options_panel->addRadio('title_alignment', array('left' => 'Left',
                'center' => 'Center', 'right' => 'Right'), array('name' => __('Title Alignment ?', 'apc'), 'std' => array('center'), 'desc' => __('', 'apc')));
            $options_panel->addRadio('display_title', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Display Title ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));

            $options_panel->addText('post_per_page', array('name' => __('Number of stories to display ?', 'apc'), 'std' =>20, 'desc' => __('This option is overridden by shortcode. Please check shortcode generator.', 'apc')));
         
            $options_panel->addText('content_length', array('name' => __('Content Length ? ', 'apc'), 'std' => 50, 'desc' => __('Please enter no of words', 'apc')));
            //Image field
            
            $options_panel->addImage('user_avatar', array('name' => __('Timeline default Image', 'apc'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('desc_type', array('short' => 'Short (Default)',
                'full' => 'Full (with HTML)'), array('name' => __('Stories Description?', 'apc'), 'std' => array('short'), 'desc' => __('This option is overridden by shortcode in V2.1. Please check shortcode generator.', 'apc')));

            $options_panel->addRadio('display_readmore', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Display read more ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));

            $options_panel->addText('read_more_lbl', array('name' => __('Stories Read more Text', 'apc'), 'std' => '', 'desc' => __('', 'apc')));


            $options_panel->addRadio('posts_orders', array('DESC' => 'DESC',
                'ASC' => 'ASC'), array('name' => __('Stories Order ?', 'apc'), 'std' => array('DESC'), 'desc' => __('This option is overridden by shortcode. Please check your shortcode generator.', 'apc')));
              //select field
              $options_panel->CloseTab();

			 /**
             * Open admin page secondsetting-error-tgmpa tab
             */
            $options_panel->OpenTab('options_2');
            $options_panel->Title(__("Style Settings", "apc"));
            /**
             * To Create a Conditional Block first create an array of fields (just like a repeater block
             * use the same functions as above but add true as a last param
             */
            $Conditinal_fields[] = $options_panel->addColor('bg_color', array('name' => __('Background Color', 'apc')), true);

            /**
             * Then just add the fields to the repeater block
             */
            //conditinal block 
            $options_panel->addCondition('background', array(
                'name' => __('Container Background ', 'apc'),
                'desc' => __('', 'apc'),
                'fields' => $Conditinal_fields,
                'std' => false
            ));

            //Color field
            $options_panel->addColor('content_bg_color', array('name' => __('Story Background Color', 'apc'), 'std' =>'#f9f9f9', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/bg-color.png" style="width:125px;height:97px;"></div>', 'apc')));

            $options_panel->addColor('content_color', array('name' => __('Content Font Color', 'apc'),'std' =>'#666666', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/font-color.png" style="width:125px;height:97px;"></div>', 'apc')));
            $options_panel->addColor('title_color', array('name' => __('Story Title Color', 'apc'),'std' =>'#fff', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/title-color.png"></div>', 'apc')));

            $options_panel->addColor('circle_border_color', array('name' => __('Circle Color', 'apc'), 'std' =>'#222222', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/circle-color.png" style="width:100px;height:86px;"></div>', 'apc')));

            $options_panel->addColor('line_color', array('name' => __('Line Color', 'apc'), 'std' =>'#000', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/line.png" style="height:86px;"></div>', 'apc')));
            //Color field
            $options_panel->addColor('first_post', array('name' => __('First Color', 'apc'), 'std' =>'#02c5be', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/first.png" style="width:250px;"></div>', 'apc')));
            $options_panel->addColor('second_post', array('name' => __('Second Color', 'apc'), 'std' =>'#f12945', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/second.png" style="width:250px;"></div>', 'apc')));
            // $options_panel->addColor('third_post',array('name'=> __('Third Post','apc'),'std'=>array('#000'), 'desc' => __('','apc')));
            $options_panel->CloseTab();

			
			
            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_3');

            //title
            $options_panel->Title(__("Typography Settings", "apc"));
            $options_panel->addTypo('main_title_typo', array('name' => __("Main Title", "apc"), 'std' => array('size' => '22px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img-small"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/main-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addTypo('post_title_typo', array('name' => __("Story Title", "apc"), 'std' => array('size' => '20px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img-small"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/story-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addRadio('post_title_text_style', array('lowercase' => 'Lowercase',
                'uppercase' => 'Uppercase', 'capitalize' => 'Capitalize',
                'none' => 'None'    
                ), array('name' => __('Story Title Style ?', 'apc'), 'std' => array('capitalize'), 'desc' => __('<div class="info_img-small"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/story-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addTypo('post_content_typo', array('name' => __("Story Content", "apc"), 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/story-content.png" style="width:150px;"></div>', 'apc')));



            $options_panel->CloseTab();

           

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_4');
            $options_panel->Title(__("Stories Settings", "apc"));
           $options_panel->addText('post_type_slug', array('name' => __('Custom slug of timeline stories', 'apc'), 'std' => '', 'desc' => __('Remember to save the permalink again in settings -> Permalinks.', 'apc')));

            //An optionl descrption paragraph
            $options_panel->addParagraph(__("Animation Effects option is added in shortcode generator in Version 1.9 or Later","apc"));

            $options_panel->addRadio('stories_images', array('popup' => 'In Popup',
                'single' => 'Story detail link','disable_links'=>'Disable links'), array('name' => __('Stories Images?', 'apc'), 'std' => array('popup'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('ctl_slideshow', array('true' => 'Enable',
                'false' => 'Disable'), array('name' => __('Stories slideshow ?', 'apc'), 'std' => array('true'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('slider_animation', array('slide' => 'Slide',
                'fade' => 'FadeIn'), array('name' => __('Slider animation ?', 'apc'), 'std' => array('slide'), 'desc' => __('', 'apc')));
            $options_panel->addText('animation_speed', array('name' => __('Slide Show Speed ?', 'apc'), 'std' => '5000', 'desc' => __('Enter the speed in milliseconds 1000 = 1 second', 'apc')));

            $options_panel->addText('default_icon', array('name' => __('Stories default icon', 'apc'), 'std' => '', 'desc' => __('Please add stories default  icon class from here <a target="_blank" href="http://fontawesome.io/icons">Font Awesome</a>', 'apc')));


            $options_panel->CloseTab();


            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_5');
            $options_panel->Title(__("Stories Date Settings", "apc"));
            $options_panel->addRadio('disable_months', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Disable Stories Dates ?', 'apc'), 'std' => array('no'), 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));

            $options_panel->addRadio('ctl_date_formats', array('M d' => date('M d'),
                'F j, Y' => date('F j, Y'), 'Y-m-d' => date('Y-m-d'),
                'm/d/Y' => date('m/d/Y'), 'd/m/Y' => date('d/m/Y')
                    ), array('name' => __('Stories Date Formats ?', 'apc'), 'std' => array('M d'), 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));

            $options_panel->addText('custom_date_formats', array('name' => __('Custom date formats', 'apc'), 'std' => '', 'desc' => __('Stories date formats   e.g  D,M,Y <a  target="_blank" href="http://php.net/manual/en/function.date.php">Click here to view more</a>', 'apc')));

            $options_panel->addRadio('custom_date_style', array('no' => 'No(Default style)',
                'yes' => 'Yes'), array('name' => __('Enable custom date styles', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));

            $options_panel->addTypo('ctl_date_typo', array('name' => __("Stories date Font style", "apc"), 'std' => array('size' => '22px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));
           
		   $options_panel->addRadio('custom_date_color', array('no' => 'No(Default style)',
                'yes' => 'Yes'), array('name' => __('Enable custom date Color', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));
		   $options_panel->addColor('ctl_date_color', array('name' => __('Stories date color', 'apc'), 'std' =>'#000000', 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));



            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_7');
            $options_panel->Title(__("Timeline Scrolling Navigation settings", "apc"));
            $options_panel->addRadio('enable_navigation', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Enable Scrolling  Navigation ?', 'apc'), 'std' => array('yes'), 'desc' => __('<div class="info_img"><img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/small-nav.png" style="width:66%;"></div>', 'apc')));

            $options_panel->addRadio('navigation_position', array(
                'left' => 'Left Side', 'right' => 'Right Side','bottom' => 'Bottom Fixed ',
                    ), array('name' => __('Scrolling Navigation Position ?', 'apc'), 'std' => array('right'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('enable_pagination', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Enable Pagination ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));

            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_6');
            /**
             * Editor options:
             * WYSIWYG (tinyMCE editor)
             * Syntax code editor (css,html,js,php)
             */
            //code editor field
            $options_panel->addCode('custom_styles', array('name' =>
            __('Custom Styles', 'apc'), 'syntax' => 'css'));
// Close 3rd tab
            //title
            //  $options_panel->Title(__("Editor Options","apc"));
            //wysiwyg field
           // $options_panel->addWysiwyg('no_posts', array('name' => __('No Timeline Posts content', 'apc'), 'desc' => __('', 'apc')));

            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_8');
            //An optionl descrption paragraph
            $options_panel->addParagraph(__('<img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/timeline shortcode.png" style="width:100%">', "apc"));
            $options_panel->addParagraph(__('<img src="' . CTP_PLUGIN_URL . '/admin-page-class/images/category-based timeline.png" style=" width:100%">', "apc"));
            $options_panel->addParagraph(__('Please use below added shortcode for default timeline. <br><br>
		<code><strong>[cool-timeline layout="default" skin="default" show-posts="20" order="DESC" icons="NO"] </strong> </code>', "apc"));

            $options_panel->addParagraph(__('Please use below added shortcode for multiple timeline (category based timeline). <br> <br> <code><strong>[cool-timeline  layout="default"  skin="default"  order="DESC" icons="NO" category="{add here story category id}" show-posts="20"] </strong></code>', "apc"));

          $options_panel->addParagraph(__('Horizontal Timeline. <br><br>
		<code><strong>[cool-timeline type="horizontal" category="{add here story category id}" skin="default" show-posts="20" order="DESC" icons="NO"]</strong> </code>', "apc"));

            $options_panel->addParagraph(__('Content Timeline(any post type). <br><br>
		<code><strong>[cool-content-timeline post-type="post" layout="default" skin="default" show-posts="20" order="DESC" icons="NO"]</strong> </code>', "apc"));

            $options_panel->addParagraph(__('Social Timeline(Facebook). <br><br>
		<code><strong>[cool-social-timeline type="social" fb-app-id="{APP ID}" fb-app-secret-key="{APP SECRET KET}" fb-page-name="{Facebook Page name}" show-posts="20" skin="default"]</strong> </code>', "apc"));

            $options_panel->CloseTab();

          
            /**
             * Open admin page 7th tab
             */
            $options_panel->OpenTab('options_10');
             $options_panel->Title(__("Story Migrations","apc"));
              $options_panel->content_migration();

            $options_panel->CloseTab();
            $options_panel->CloseTab();

        }
        public function ctl_add_tinymce() {
         global $typenow;
         if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
              return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
       add_filter('mce_external_plugins', array(&$this, 'ctl_add_tinymce_plugin'));
            add_filter('mce_buttons', array(&$this, 'ctl_add_tinymce_button'));
          }    

        }

    
        public function ctl_add_tinymce_plugin($plugin_array) {
            $plugin_array['cool_timeline'] =CTP_PLUGIN_URL.'js/shortcode-btn.js';
            return $plugin_array;
        }

        // Add the button key for address via JS
        function ctl_add_tinymce_button($buttons) {
            array_push($buttons, 'cool_timeline_shortcode_button');
            return $buttons;
        }

        // end tinymce button functions           

        /**
         * Activate the plugin
         */
        public function activate() {
          /*  if ( is_plugin_active('cool-timeline/cool-timeline.php') ) {
                deactivate_plugins('cool-timeline/cool-timeline.php');
            }
            // Compare versions.
         /*   if ( version_compare(phpversion(),  '5.6', '<') ) {
               deactivate_plugins( plugin_basename( __FILE__ ) );
                return false;
               // wp_die( 'This plugin requires PHP Version 5.2.  Sorry about that.' );

             } */
               // Do activate Stuff now.

        }
        // END public static function activate

        /**
         * Deactivate the plugin
         */
        public function deactivate() {

        }


        public function clt_meta_boxes() {
            /*
             * configure your meta box
             */
            $config = array(
                'id' => 'demo_meta_box', // meta box id, unique per meta box 
                'title' => __('Timeline story settings', 'apc'), // meta box title
                'pages' => array('cool_timeline'), // post types, accept custom post types as well, default is array('post'); optional
                'context' => 'normal', // where the meta box appear: normal (default), advanced, side; optional
                'priority' => 'high', // order of meta box: high (default), low; optional
                'fields' => array(), // list of meta fields (can be added by field arrays) or using the class's functions
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false            //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );

            for ($i = 1000; $i <= 2050; $i++) {
                $story_year_list[$i] = $i;
            }
             for ($i = 0; $i <= 200; $i++) {
                $story_orders[$i] = $i;
            }
            /*
             * Initiate your meta box
             */
            $my_meta = new AT_Meta_Box($config);

            /*
             * Add fields to your meta box
             */
             $my_meta->addRadio('story_based_on', array('default' => __('Date Based', 'apc'), 'custom' => 
                __('Custom Order Based', 'apc')), array('name' => __('Story Based On ', 'apc'), 
                'class'=>'story_based_on',

                'std' => array('default')));


            $my_meta->addSelect('ctl_story_year', $story_year_list, array('name' =>__('Story Year  <span class="ctl_required">*</span>', 'apc'), 'desc' =>__('<p class="ctl_required">Please select story year.</p>', 'apc'), 
                'class'=>'date_based',
                'std' => array(date('Y'))
                ));

            /* 	$my_meta->addDate('ctl_story_year',array('name'=> 'Story Year','desc'=>'des','std'=>date('Y'),'format'=>'yy'));
             */
            $my_meta->addDate('ctl_story_date', array('name' =>__('Story Date <span class="ctl_required">*</span>','apc'), 'desc' =>__('<p class="ctl_required">Please select same year of story date. <strong>Date Format( mm/dd/yy hh:mm )</strong></p>','apc'),
             'std' => date('m/d/Y h:m a'),

              'format' =>__('d MM yy','apc'),
                 'class'=>''
                ));

          

           $my_meta->addText('ctl_story_lbl',array('name'=>__('Add custom label','apc'),
                'desc' =>__(' ','apc'),
                'class'=>'custom_based'
                )); 
           $my_meta->addText('ctl_story_lbl_2',array('name'=>__('Add second custom label','apc'),
                'class'=>'custom_based',
                'desc' =>__('','apc'))); 

           $my_meta->addSelect('ctl_story_order',$story_orders, array('name' =>__('Order<span class="ctl_required">*</span>', 'apc'), 'desc' =>__('<p class="ctl_required">Please select story Order.</p>', 'apc'), 
               'class'=>'custom_based',
            'std' =>0));

             //radio field
            $my_meta->addRadio('story_format', array('default' => __('Default(Image)', 'apc'), 'video' => __('Video', 'apc'), 'slideshow' => __('Slideshow', 'apc')), array('name' => __('Story Format', 'apc'), 
                'class'=>'story_format',
                'std' => array('default')));

            /*
             * To Create a reapeater Block first create an array of fields
             * use the same functions as above but add true as a last param
             */

            $repeater_fields[] = $my_meta->addImage('ctl_slide', array('name' => __('Slide', 'apc')), true);
              
            /*
             * Then just add the fields to the repeater block
             */
            //repeater block
            $my_meta->addRepeaterBlock('re_', array('inline' => true, 'name' => __('Add slideshow slides', 'apc'),
                  'class'=>'story_format_slideshow',
             'fields' => $repeater_fields));
            /*
             * Don't Forget to Close up the meta box deceleration
             */

            $my_meta->addTextarea('ctl_video', array('name' => __('Add Youtube video url e.g <small>https://www.youtube.com/watch?v=PLHo6uyICVk</small>', 'apc'),'class'=>'story_format_video'));

            $my_meta->addRadio('img_cont_size', array('full' => __('Full', 'apc'), 'small' => __('Small', 'apc')), array('name' => __('Story image size', 'apc'),
                'class'=>'story_format_image',
             'std' => array('full')));
            
            $my_meta->addText('story_custom_link',array('name'=>__('Story custom link','apc'),
                'desc' =>__('','apc'))); 
            //Finish Meta Box Deceleration
            $my_meta->Finish();
        }

 public function cool_admin_messages() {
      
         if( !current_user_can( 'update_plugins' ) ){
            return;
         }
        $install_date = get_option( 'cool-timelne-installDate' );
        $ratingDiv =get_option( 'cool-timelne-ratingDiv' )!=false?get_option( 'cool-timelne-ratingDiv'):"no";

        $dynamic_msz='<div class="cool_fivestar update-nag" style="box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);">
          <p>Dear Cool Timeline PRO Plugin User, Hopefully you\'re happy with our plugin. <br> May I ask you to give it a <strong>5-star rating</strong> on Wordpress? 
            This will help to spread its popularity and to make this plugin a better one.
            <br><br>Your help is much appreciated.Thank you very much!
            <ul>
                <li class="float:left"><a href="https://codecanyon.net/item/cool-timeline-pro-wordpress-timeline-plugin/reviews/17046256" class="thankyou button button-primary" target="_new" title="I Like Cool Timeline" style="color: #ffffff;-webkit-box-shadow: 0 1px 0 #256e34;box-shadow: 0 1px 0 #256e34;font-weight: normal;float:left;margin-right:10px;">I Like Cool Timeline PRO</a></li>
                <li><a href="javascript:void(0);" class="coolHideRating button" title="I already did" style="">I already rated it</a></li>
                <li><a href="javascript:void(0);" class="coolHideRating" title="No, not good enough" style="">No, not good enough, i do not like to rate it!</a></li>
            </ul>
        </div>
        <script>
        jQuery( document ).ready(function( $ ) {

        jQuery(\'.coolHideRating\').click(function(){
            var data={\'action\':\'hideRating\'}
                 jQuery.ajax({
            
            url: "' . admin_url( 'admin-ajax.php' ) . '",
            type: "post",
            data: data,
            dataType: "json",
            async: !0,
            success: function(e) {
                if (e=="success") {
                   jQuery(\'.cool_fivestar\').slideUp(\'fast\');
             
                }
            }
             });
            })
        
        });
        </script>';

         if(get_option( 'cool-timelne-installDate' )==false && $ratingDiv== "no" )
           {
           echo $dynamic_msz;
           }else{
                $display_date = date( 'Y-m-d h:i:s' );
                $install_date= new DateTime( $install_date );
                $current_date = new DateTime( $display_date );
                $difference = $install_date->diff($current_date);
              $diff_days= $difference->days;
           if (isset($diff_days) && $diff_days>=7 && $ratingDiv == "no" ) {
                echo $dynamic_msz;
                }
             }   
           }   
     public function cool_HideRating() {
        update_option( 'cool-timelne-ratingDiv','yes' );
        echo json_encode( array("success") );
        exit;
        }
 }
    //end class
}


foreach (array('post.php','post-new.php','edit-tags.php','term.php') as $hook) {

    add_action("admin_head-$hook", 'ctl_admin_head');
}

   

/**
 * Localize Script
 */
function ctl_admin_head() {

    $plugin_url = plugins_url('/', __FILE__);
   if(version_compare(get_bloginfo('version'),'4.5.0', '>=') ){
    $terms = get_terms(array(
     'taxonomy' => 'ctl-stories',
    'hide_empty' => false,
     ));
    }else{
            $terms = get_terms('ctl-stories', array('hide_empty' => false,
        ) );
      }

    if (!empty($terms) || !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $ctl_terms_l[$term->slug] =$term->slug;
        }
    }


    if (isset($ctl_terms_l) && array_filter($ctl_terms_l) != null) {
        $category =json_encode($ctl_terms_l);
    } else {
        $category = json_encode(array('0' => 'No category'));
    }
    ?>
    <!-- TinyMCE Shortcode Plugin -->
<script type='text/javascript'>
   var ctl_cat_obj = {
        'category':'<?php echo $category; ?>'
    };
</script>
    <style type="text/css">
    .mce-container[aria-label="Add Content Timeline Shortcode"] .mce-reset {
    max-height: 600px;
    overflow-y: scroll;
    overflow-x: hidden;
}
    </style>
    <!-- TinyMCE Shortcode Plugin -->
    <?php
}

// instantiate the plugin class
 new CoolTimelinePro();

