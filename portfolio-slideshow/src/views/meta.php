<?php
/**
 * Meta for the slideshow.
 *
 * @var bool $showtitles
 * @var bool $showcaps
 * @var bool $showdesc
 * @var array $slides
 */

defined( 'WPINC' ) or die; ?>

<div class="slideshow-meta">
<?php foreach ( $slides as $pos => $slide ) : ?>

	<?php if ( 'true' == $showtitles ) : ?>
		<p class="slideshow-title">
			<?php echo sanitize_text_field( get_post_meta( $slide['image'], '_TODO_title', true ) ); ?>
		</p>
	<?php endif; ?>

	<?php if ( 'true' == $showcaps ) : ?>
		<p class="slideshow-caption">
			<?php echo sanitize_text_field( get_post_meta( $slide['image'], '_TODO_caption', true ) ); ?>
		</p>
	<?php endif; ?>

	<?php if ( 'true' == $showdesc ) : ?>
		<div class="slideshow-description">
			<?php echo sanitize_text_field( get_post_meta( $slide['image'], '_TODO_description', true ) ); ?>
		</div>
	<?php endif; ?>

<?php endforeach; ?>
</div>