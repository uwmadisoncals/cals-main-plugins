<?php

defined( 'WPINC' ) or die;

/**
 * The filtered singlar slideshow label; defaults to "Slideshow".
 *
 * @since 1.12.0
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_singular() {
    /**
     * Allows filtering the singlar slideshow label.
     *
     * @since 1.12.0
     *
     * @return string
     */
    return apply_filters( 'portfolio_slideshow_slideshow_label_singular', esc_html__( 'Slideshow', 'portfolio-slideshow' ) );
}

/**
 * The filtered plural slideshow label; defaults to "Slideshows".
 *
 * @since 1.12.0
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_plural() {
    /**
     * Allows filtering the plural slideshow label.
     *
     * @since 1.12.0
     *
     * @return string
     */
    return apply_filters( 'portfolio_slideshow_slideshow_label_plural', esc_html__( 'Slideshows', 'portfolio-slideshow' ) );
}

/**
 * The filtered singular slide label; defaults to "Slide".
 *
 * @since 1.12.0
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_singular() {
    /**
     * Allows filtering the singlar slide label.
     *
     * @since 1.12.0
     *
     * @return string
     */
    return apply_filters( 'portfolio_slideshow_slide_label_singular', esc_html__( 'Slide', 'portfolio-slideshow' ) );
}

/**
 * The filtered plural slide label; defaults to "Slides".
 *
 * @since 1.12.0
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_plural() {
    /**
     * Allows filtering plural slide label.
     *
     * @since 1.12.0
     *
     * @return string
     */
    return apply_filters( 'portfolio_slideshow_slide_label_plural', esc_html__( 'Slides', 'portfolio-slideshow' ) );
}