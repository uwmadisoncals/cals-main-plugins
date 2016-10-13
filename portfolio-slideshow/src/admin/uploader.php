<?php
/**
 * The main markup for the slides uploader meta box.
 *
 * @var object $post The WP_Post object for the current post.
 */
defined( 'WPINC' ) or die;

$post_id         = absint( $post->ID );
$slideshow       = new Portfolio_Slideshow_Slideshow( array( 'id' => $post_id ) );
$existing_slides = $slideshow->slides;

wp_nonce_field( 'portfolio_slideshow_save_metabox_slides', 'portfolio_slideshow_metabox_slides_' . $post_id ); ?>

<?php if ( ! is_array( $existing_slides ) || empty( $existing_slides ) ) : ?>
<p class="portfolio-slideshow-no-slides-prompt">
	<?php esc_html_e( 'This slideshow is empty for now. ', 'portfolio-slideshow-pro' ); ?>
</p>
<?php endif; ?>

<ol class="portfolio-slideshow-metabox-slides portfolio-slideshow-draggable-area clearfix">

	<?php if ( is_array( $existing_slides ) && ! empty( $existing_slides ) ) : ?>
	<?php foreach ( $existing_slides as $i => $slide ) : ?>

		<li class="portfolio-slideshow-draggable-item" data-attachment-id="<?php echo esc_attr( $slide['image'] ); ?>" title="<?php echo esc_attr( sprintf( __( 'Attachment ID: %s', 'portfolio-slideshow-pro' ), $slide['image'] ) ); ?>">
			<input name="portfolio_slideshow_metabox_slides_order[]" type="hidden" value="<?php echo esc_attr( $slide['image'] ); ?>">

			<?php $img_src = wp_get_attachment_image_src( $slide['image'], 'thumbnail' ); ?>
			<?php if ( ! empty( $img_src ) ) : ?>
				<img width="80" alt="<?php printf( esc_attr__( 'Slide %s', 'portfolio-slideshow' ), ( $i + 1 ) ); ?>" src="<?php echo esc_url( $img_src[0] ); ?>">
			<?php else : ?>
				<p class="portfolio-slideshow-twas-deleted">
					<b><?php esc_html_e( 'This attachment image was deleted.', 'portfolio-slideshow' ); ?></b>
					<a href="#" class="portfolio-slideshow-quick-trash"><?php esc_html_e( 'Remove slide', 'portfolio-slideshow' ); ?></a>
				</p>
			<?php endif; ?>

			<span class="portfolio-slideshow-slide-footer">
				<strong class="alignleft slide-index"><?php echo $i + 1; ?></strong>
				<a href="<?php printf( '%s&image-editor', esc_url( get_edit_post_link( $slide['image'] ) ) ); ?>" target="_blank" class="alignright portfolio-slideshow-quick-edit"><span class="dashicons dashicons-edit"></span></a>
				<a href="#" title="<?php esc_attr_e( 'Remove this image from the slideshow (does not delete the image from your WordPress media library).', 'portfolio-slideshow' ); ?>" class="alignright portfolio-slideshow-quick-trash"><span class="dashicons dashicons-no-alt"></span></a>
			</span>
		</li>

	<?php endforeach; ?>
	<?php endif; ?>
</ol>

<script type="text/template" id="tmpl-portfolio-slideshow-add-slides">
	<# _.each( data.attachments, function( attachment ) { #>

		<li class="portfolio-slideshow-draggable-item" data-attachment-id="{{{ attachment.id }}}">
			<input name="portfolio_slideshow_metabox_slides_order[]" type="hidden" value="{{{ attachment.id }}}">
			<img width="80" alt="{{{ attachment.alt }}}" src="{{{ attachment.sizes.thumbnail.url }}}">
			
			<span class="portfolio-slideshow-slide-footer">
				<strong class="alignleft slide-index"></strong>
				<a href="{{{ attachment.editLink }}}&image-editor" target="_blank" class="alignright portfolio-slideshow-quick-edit"><span class="dashicons dashicons-edit"></span></a>
				<a href="#" class="alignright portfolio-slideshow-quick-trash"><span class="dashicons dashicons dashicons-no-alt"></span></a>
			</span>
		</li>
	<# }); #>
</script>

<div id="portfolio-slideshow-add-slides" class="clearfix">	
	<button class="button button-primary button-large" id="portfolio-slideshow-add-slides-new"><?php esc_html_e( 'Add Slides', 'portfolio-slideshow' ); ?></button>
	<a target="_blank" href="<?php echo admin_url( 'options-general.php?page=portfolio_slideshow&tab=documentation' ); ?>" title="<?php esc_attr_e( 'Need help? Click here learn more about creating Portfolio Slideshows.', 'portfolio-slideshow' ); ?>" class="alignright portfolio-slideshow-metabox-help"><span class="dashicons dashicons-editor-help"></span></a>
</div>