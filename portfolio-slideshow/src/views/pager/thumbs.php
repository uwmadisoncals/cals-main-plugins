<?php
/**
 * The "thumbs" pager style.
 *
 * @var int $key This slideshow's key.
 * @var array $slides The slides for this slideshow.
 */

defined( 'WPINC' ) or die; ?>

<div class="pscarousel">
	<div id="<?php echo esc_attr( sprintf( 'pager%s', $key ) ); ?>" class="pager items clearfix">
	<?php foreach ( $slides as $pos => $slide ) : ?>
		<?php echo wp_get_attachment_image( $slide['image'], 'thumbnail', false, false ); ?>
	<?php endforeach; ?>
	</div>
</div>