<?php
/**
 * The text navigation style.
 *
 * @var int $key This slideshow's key.
 */

defined( 'WPINC' ) or die; ?>

<div id="<?php echo esc_attr( sprintf( 'slideshow-nav%s', $key ) ); ?>" class="slideshow-nav">
	<a class="pause" style="display:none" href="javascript:void(0);"><?php esc_html_e( 'Pause', 'portfolio-slideshow' ); ?></a>
	<a class="play" href="javascript:void(0);"><?php esc_html_e( 'Play', 'portfolio-slideshow' ); ?></a>
	<a class="restart" style="display:none" href="javascript: void(0);"><?php esc_html_e( 'Play', 'portfolio-slidehsow' ); ?></a>
	<a class="slideshow-prev" href="javascript: void(0);"><?php esc_html_e( 'Prev', 'portfolio-slidehsow' ); ?></a>
	<span class="sep">|</span>
	<a class="slideshow-next" href="javascript: void(0);"><?php esc_html_e( 'Next', 'portfolio-slidehsow' ); ?></a>
	<span class="<?php echo esc_attr( sprintf( 'slideshow-info%s', $key ) ); ?> slideshow-info"></span>
</div><!-- .slideshow-nav -->