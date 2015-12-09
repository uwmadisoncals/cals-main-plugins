<?php
/**
 * The actual slides container, and the slides themselves.
 *
 * @var int $key
 * @var int $slides_count
 * @var string $maybe_min_height A pixel value for CSS min-height on the slides container *if* this slideshow has a slideheight set for it.
 * @var array $slides
 * @var string $placeholder 
 */

defined( 'WPINC' ) or die; ?>

<div id="<?php echo esc_attr( sprintf( 'portfolio-slideshow%s', $this->key ) ); ?>" class="portfolio-slideshow" style="<?php echo esc_attr( $maybe_min_height ); ?>">

	<?php $count = 0; ?>

	<?php foreach ( $this->slides as $pos => $slide ) : ?>

		<?php ++$count; ?>
		
		<?php $alttext = sprintf( __( 'Slide %s', 'portfolio-slideshow' ), absint( $pos + 1 ) ); ?>
	
		<div class="slideshow-next slideshow-content <?php 0 != $pos ? esc_attr_e( 'not-first' ) : '' ?>">
		<?php

			$slide_url = 'javascript:void(0)';

			if ( 'openurl' == $this->arg( 'click' ) ) {
				$image_link = get_post_meta( $slide['image'], '_ps_image_link', true );

				if ( ! empty( $image_link ) ) {
					$slide_url = $image_link;
				}
			}

			$class  = ( 'javascript:void(0)' == $slide_url ? 'slideshow-next' : '' );
			$target = ( 'javascript:void(0)' == $slide_url ? '' : sprintf( 'target="%s"', $this->arg( 'target' ) ) );

			if ( 'false' == $this->arg( 'loop' ) && $count - 1 != $pos || 'false' != $this->arg( 'loop' ) ) {
				printf( '<a class="%s" href="%s" %s>', $class, $slide_url, $target );
			}

				$img = wp_get_attachment_image_src( $slide['image'], $this->arg( 'size' ) );
	
				printf( '<img class="psp-active" data-img="%s" src="%s" height="%s" width="%s" alt="%s">',
					esc_attr( $img[0] ),
					esc_attr( $pos < 1 ? $img[0] : Portfolio_Slideshow_Slideshow::PLACEHOLDER ),
					esc_attr( $img[2] ),
					esc_attr( $img[1] ),
					esc_attr( sprintf( _x( 'Slide %s', 'Alt text for slide images, where %s is the current slide number as an integer.', 'portfolio-slideshow' ), absint( $pos + 1 ) ) )
				);

			if ( 'false' == $this->arg( 'loop' ) && $count - 1 != $pos || 'false' != $this->arg( 'loop' ) ) {
				print( '</a>' );
			}
		?>
		</div>

	<?php endforeach; ?>
</div>