<?php

defined( 'WPINC' ) or die;

/**
 * The filtered singlar slideshow label, "Slideshow"  by default in English
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_singular() {
	return apply_filters( 'portfolio_slideshow_slideshow_label_singular', __( 'Slideshow', 'portfolio-slideshow' ) );
}

/**
 * The filtered plural slideshow label, "Slideshows" by default in English.
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_plural() {
	return apply_filters( 'portfolio_slideshow_slideshow_label_plural', __( 'Slideshows', 'portfolio-slideshow' ) );
}

/**
 * The filtered singular slide label, "Slide" by default in English.
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_singular() {
	return apply_filters( 'portfolio_slideshow_slide_label_singular', __( 'Slide', 'portfolio-slideshow' ) );
}

/**
 * The filtered singular slide label, "Slides" by default in English.
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_plural() {
	return apply_filters( 'portfolio_slideshow_slide_label_plural', __( 'Slides', 'portfolio-slideshow' ) );
}