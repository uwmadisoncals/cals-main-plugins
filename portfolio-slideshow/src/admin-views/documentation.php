<?php

defined( 'WPINC' ) or die;

/*
 * List of plugin hooks in new 2.0.0:
 * 
 * @filter portfolio_slideshow_get_supported_types
 * @filter portfolio_slideshow_get_settings_tabs
 * @filter portfolio_slideshow_slideshow_label_singular
 * @filter portfolio_slideshow_slideshow_label_plural
 * @filter portfolio_slideshow_slide_label_singular
 * @filter portfolio_slideshow_slide_label_plural
 */
?>
<div class="wrap">

	<?php
	/**
	 * General Usage: outlines the very basics of how to use the plugin.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="general-usage">
		<h3><?php esc_html_e( 'General Usage', 'portfolio-slideshow' ); ?></h3>
		<p><?php esc_html_e( 'To use the plugin, upload your photos directly to a post or page using the WordPress media uploader. Use the [portfolio_slideshow] shortcode to display the slideshow in your page or post.', 'portfolio-slideshow' ); ?></p>
	</section>

	<?php
	/**
	 * Shortcode Attributes: outlines how to use shortcode attributes with image sizing as an example.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="shortcode-attributes">
		<h3><?php esc_html_e( 'Shortcode Attributes', 'portfolio-slideshow' ); ?></h3>
	
		<p><?php esc_html_e( 'If you would like to customize your slideshows on a per-slideshow basis, you can add the following 	attributes to the shortcode, which will temporarily override the defaults.', 'portfolio-slideshow' ); ?></p>
	
		<p><?php esc_html_e( 'To change the image size you would use the size attribute in the shortcode like this:', '	portfolio-slideshow' ); ?></p>
	
		<p>
			<ul>
				<li><code>[portfolio_slideshow size=thumbnail]</code></li>
				<li><code>[portfolio_slideshow size=medium]</code></li>
				<li><code>[portfolio_slideshow size=large]</code></li>
				<li><code>[portfolio_slideshow size=full]</code></li>
			</ul>
		</p>
	
		<p><?php esc_html_e( 'This setting can use any custom image size that you\'ve registered in WordPress.', 'portfolio-slideshow' ); ?></p>

	</section>

	<?php
	/**
	 * Slideheight: describes the slideheight attribute.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="slideheight">
		<h3><?php esc_html_e( 'Slideheight', 'portfolio-slideshow' ); ?></h3>

		<p><?php esc_html_e( 'You can add a custom slide container height like this:', 'portfolio-slideshow' ); ?></p>

		<p><code>[portfolio_slideshow slideheight=400]</code></p>

		<p><?php esc_html_e( 'This is useful if you don\'t want the page height to adjust with the slideshow.', 'portfolio-slideshow' ); ?></p>

	</section>

	<?php
	/**
	 * Transitions: overview of cycle "fx" and some other transition controls.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="transitions">
		<h3><?php esc_html_e( 'Transitions', 'portfolio-slideshow' ); ?></h3>

		<strong><?php esc_html_e( 'Transition Styles', 'portfolio-slideshow' ); ?></strong>

		<p><?php esc_html_e( 'You can use the trans shortcode attribute to supply any transition effect supported by jQuery Cycle, even if they\'re not in the plugin! Not all transitions will work with all themes; if in doubt, stick with "fade" or "none".', 'portfolio-slideshow' ); ?></p>

		<p><code>[portfolio_slideshow trans=scrollHorz]</code></p>
		<p><strong><a href="http://jquery.malsup.com/cycle/begin.html" target="_blank"><?php esc_html_e( 'Click here for a list of all supported transitions.', 'portfolio-slideshow' ); ?></a></strong></p>

		<strong><?php esc_html_e( 'Transition Speed', 'portfolio-slideshow' ); ?></strong>

		<p><?php esc_html_e( 'Transition speed is measured in milliseconds; e.g. a value of 1000 would equal one second. For example:', 'portfolio-slideshow' ); ?></p>
		
		<p><code>[portfolio_slideshow speed=400]</code></p>
	</section>

	<?php
	/**
	 * Titles, Captions, Descriptions: overview of slideshow meta.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="slideshow-meta">
		<h3><?php esc_html_e( 'Titles, Captions, and Descriptions', 'portfolio-slideshow' ); ?></h3>
	
		<p><?php esc_html_e( 'To show slideshow titles:', 'portfolio-slideshow' ); ?> <br><code>[portfolio_slideshow showtitles=true]</code></p>
	
		<p><?php esc_html_e( 'To show slideshow captions:', 'portfolio-slideshow' ); ?> <br><code>[portfolio_slideshow showcaps=true]</code></p>
	
		<p><?php esc_html_e( 'To show slideshow descriptions:', 'portfolio-slideshow' ); ?> <br><code>[portfolio_slideshow showdesc=true]</code></p>

		<p><?php esc_html_e( 'Mix and match these options as needed. For example:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow showdesc=true showcaps=false showtitles=true]</code></p>
	</section>

	<?php
	/**
	 * Slideshow Behaviors: general slideshow behavior attributes.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="slideshow-behaviors">
		<h3><?php esc_html_e( 'Slideshow Behaviors', 'portfolio-slideshow' ); ?></h3>
	
		<p><strong><?php esc_html_e( 'Time per slide when slideshow is playing (timeout):', 'portfolio-slideshow' ); ?></strong>
			<br>
			<code>[portfolio_slideshow timeout=4000]</code>
		</p>
	
		<p><strong><?php esc_html_e( 'Autoplay:', 'portfolio-slideshow' ); ?></strong>
			<br>
			<ul>
				<li><code>[portfolio_slideshow autoplay=true]</code></li>
				<li><code>[portfolio_slideshow autoplay=false]</code></li>
			</ul>
		</p>
	
		<p><strong><?php esc_html_e( 'Exclude featured image:', 'portfolio-slideshow' ); ?></strong>
			<br>
			<code>[portfolio_slideshow exclude_featured=true]</code>
		</p>
	
		<p><strong><?php esc_html_e( 'Loop the slideshow:', 'portfolio-slideshow' ); ?></strong>
			<br>
			<ul>
				<li><code>[portfolio_slideshow loop=true]</code></li>
				<li><code>[portfolio_slideshow loop=false]</code></li>
			</ul>
		</p>
	
		<p><strong><?php esc_html_e( 'Clicking on a slideshow image:', 'portfolio-slideshow' ); ?></strong>
			<br>
			<ul>
				<li><?php esc_html_e( 'Advance the slideshow:', 'portfolio-slideshow' ); ?> <code>[[portfolio_slideshow click=advance]</code></li>
				<li><?php esc_html_e( 'Open a custom URL (set in the media uploader):', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow click=openurl]</code></li>
				<li><?php esc_html_e( 'Do nothing:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow click=none]</code></li>
			</ul>
		</p>
	</section>

	<?php
	/**
	 * Navigation and Pager: documentation related to pagerpos, navpos, navstyle, etc.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="navigation-and-pager">
		<h3><?php esc_html_e( 'Navigation and Pager', 'portfolio-slideshow' ); ?></h3>

		<p><?php esc_html_e( 'Navigation links can be placed accordingly:', 'portfolio-slideshow' ); ?>
			<br>
			<ul>
				<li><?php esc_html_e( 'Above the slides:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow navpos=top]</code></li>
				<li><?php esc_html_e( 'Below the slides:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow navpos=bottom]</code></li>
				<li><?php esc_html_e( 'Hidden altogether:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow navpos=disabled]</code></li>
			</ul>
		</p>

		<p><?php esc_html_e( 'Note that when the navigation is hidden, if your settings allow for it then slideshows will still advance when clicking on slides, on the pager thumbnails, or with autoplay if it is enabled.', 'portfolio-slideshow' ); ?></p>

		<p><?php esc_html_e( 'Pager (thumbnails) position can be set in a similar way:', 'portfolio-slideshow' ); ?>
			<br>
			<ul>
				<li><?php esc_html_e( 'Above the slides:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow pagerpos=top]</code></li>
				<li><?php esc_html_e( 'Below the slides:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow pagerpos=bottom]</code></li>
				<li><?php esc_html_e( 'Hidden altogether:', 'portfolio-slideshow' ); ?> <code>[portfolio_slideshow pagerpos=disabled]</code></li>
			</ul>
		</p>
	</section>

	<?php
	/**
	 * Include or Exclude Slides: summary of these two handy features.
	 *
	 * @updated 10/7/2015
	 */
	?>
	<section id="include-exclude">
		<h3><?php esc_html_e( 'Include or Exclude Slides', 'portfolio-slideshow' ); ?></h3>
		
		<p><?php esc_html_e( 'You can include or exclude specific images by using their Attachment IDs within WordPress. The format required is to write out the IDs of the images you want to include or exclude in a comma-separated list format, like in the examples as follows:', 'portfolio-slideshow' ); ?>
			<br>
			<ul>
				<li><?php esc_html_e( 'Show only the images whose IDs are 42, 112, 99, and 7:', 'portfolio-slideshow' ); ?><br> <code>[portfolio_slideshow include="42,112,99,7"]</code></li>
				<li><?php esc_html_e( 'Show all slideshow images EXCEPT the images whose IDs are 42, 112, 99, and 7:', 'portfolio-slideshow' ); ?><br> <code>[portfolio_slideshow exclude="42,112,99,7"]</code></li>
			</ul>
		</p>

		<p><?php echo wp_kses_post( sprintf( __( 'Need help finding an image\'s Attachment ID? You can find it by navigating to the specific image in your <a href="%" target="_blank">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post. <strong>Do not use these attributes simultaneously, they are mutually exclusive.</strong>', 'portfolio-slideshow' ), esc_url( admin_url( 'upload.php' ) ) ) ); ?></p>
	</section>

</div><!-- /.wrap -->