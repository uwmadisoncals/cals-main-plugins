<?php
/**
 * An admin notice with a call to action for an anonymous survey.
 *
 * @since 1.12.1
 *
 * @todo Remove in 1.12.2.
 */
$message = sprintf(
	__( '<p><strong>Portfolio Slideshow</strong> — Please share some feedback about Portfolio Slideshow in <a href="%s" target="_blank">this <strong>anonymous</strong> survey</a>. <a class="alignright" href="?portfolioslideshow_dismiss=yes">Dismiss</a></p>', 'portfolio-slideshow' ),
	'https://docs.google.com/forms/d/e/1FAIpQLSfWHUB3YJMsWOw_qb6AjEAsUeqK15UUQJHhvdmcQoKKHFM2GQ/viewform?fbzx=-7702295051255286000'
);

?>

<div class="notice notice-info">
	<?php echo wp_kses_post( $message ); ?>
</div>