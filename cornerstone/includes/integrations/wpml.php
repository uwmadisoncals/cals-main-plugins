<?php

class Cornerstone_Integration_WPML {


	public static function should_load() {
		return class_exists( 'SitePress' );
	}

	public function __construct() {
    add_action('cs_before_preview_frame', array( $this, 'before_preview_frame' ) );
    add_filter('cs_locate_wpml_language', array( $this, 'locate_wpml_language'), 10, 2);
	}

  public function locate_wpml_language( $lang, $post ) {
    global $sitepress;
    $language_details = $sitepress->get_element_language_details( $post->ID, 'post_' . $post->post_type );
    if ($language_details) {
      $lang = $language_details->language_code;
    }
    return $lang;
  }

  public function before_preview_frame() {

    if ( isset( $_REQUEST['lang'] ) && '3' === wpml_get_setting_filter(false, 'language_negotiation_type') ) {
      add_action('wp_loaded', array( $this, 'set_preview_lang' ), 11 );
    }
  }

  public function set_preview_lang() {
    global $sitepress;
    $sitepress->switch_lang($_REQUEST['lang']);
  }

}
