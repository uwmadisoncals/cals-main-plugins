<?php

defined( 'WPINC' ) or die;

class Portfolio_Slideshow_Shortcode {

	/**
	 * The add_shortcode() callback for [portfolio_slideshow].
	 *
	 * @param string $atts
	 * @return string
	 */
	static function do_shortcode( $atts ) {

		static $i = 0;

		$args = shortcode_atts( array(
			'size'             => Portfolio_Slideshow_Plugin::get_option( 'size' ),
			'nowrap'           => Portfolio_Slideshow_Plugin::get_option( 'loop' ),
			'loop'             => Portfolio_Slideshow_Plugin::get_option( 'loop' ),
			'speed'            => Portfolio_Slideshow_Plugin::get_option( 'speed' ),
			'trans'            => Portfolio_Slideshow_Plugin::get_option( 'trans' ),
			'timeout'          => Portfolio_Slideshow_Plugin::get_option( 'timeout' ),
			'exclude_featured' => Portfolio_Slideshow_Plugin::get_option( 'exclude_featured' ),
			'autoplay'         => Portfolio_Slideshow_Plugin::get_option( 'autoplay' ),
			'pagerpos'         => Portfolio_Slideshow_Plugin::get_option( 'pagerpos' ),
			'navpos'           => Portfolio_Slideshow_Plugin::get_option( 'navpos' ),
			'showcaps'         => Portfolio_Slideshow_Plugin::get_option( 'showcaps' ),
			'showtitles'       => Portfolio_Slideshow_Plugin::get_option( 'showtitles' ),
			'showdesc'         => Portfolio_Slideshow_Plugin::get_option( 'showdesc' ),
			'click'            => Portfolio_Slideshow_Plugin::get_option( 'click' ),
			'target'           => Portfolio_Slideshow_Plugin::get_option( 'target' ),
			'centered'         => Portfolio_Slideshow_Plugin::get_option( 'centered' ),
			'thumbs'           => '',
			'slideheight'      => '',
			'id'               => '',
			'exclude'          => '',
			'include'          => ''
		), $atts, 'portfolio_slideshow' );
		
		wp_enqueue_style( 'ps-public-css' );
		wp_enqueue_script( 'ps-public-js' );

		$slideshow = new Portfolio_Slideshow_Slideshow( $args );
		
		return $slideshow->the_slideshow();
	}
}