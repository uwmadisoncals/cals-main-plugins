<?php

defined( 'WPINC' ) or die;

class Portfolio_Slideshow_Slideshow {

	public $args;
	public $ID;
	public $slides;

	public $key;

	const PLACEHOLDER = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

	public function __construct( $args = array() ) {
		$this->args = $args;

		$maybe_id     = $this->arg( 'id' );
		$this->ID     = ! empty( $maybe_id ) ? absint( $maybe_id ) : get_the_ID();
		$this->key    = rand( 1, 999 );
		$this->slides = $this->get_slides();

		// Some option-forces from previous version.
		// @TODO – Clean up these forces.
		if ( 'true' == $this->arg( 'thumbs' ) )
			$this->args['pagerpos'] = 'bottom';

		if ( 'false' == $this->arg( 'nowrap' ) || 'true' == $this->arg( 'loop' ) )
			$this->args['loop'] = 'true';
	}

	/**
	 * Gets the supplied slideshow argument if it exists; otherwise, returns false.
	 *
	 * @return mixed|false
	 */
	function arg( $arg ) {
		return isset( $this->args[ $arg ] ) ? $this->args[ $arg ] : false;
	}

	/**
	 * Gets the slides from post meta, or queries for them if not found.
	 *
	 * @return array
	 */
	function get_slides() {

		$slides   = get_post_meta( $this->ID, '_portfolio_slideshow', true );
		$excluded = array();

		if ( empty( $slides ) ) {
			
			$slides = array();
			
			$slides_query_args = array(
				'post_parent'    => $this->ID,
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => 'image',
				'orderby'        => ( 'true' == $this->arg( 'random' ) ? 'rand' : 'menu_order' ),
				'order'          => 'ASC',
				'posts_per_page' => -1, // Get _all_ images – works around bug where limited by Reading settings.
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_is_excluded_from_portfolio_slideshow',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => 'IN'
					),
					array(
						'key'     => '_is_excluded_from_portfolio_slideshow',
						'value'   => '',
						'compare' => 'NOT EXISTS'
					)
				)
			);
	
			$slides_query_args = apply_filters( 'portfolio_slideshow_slides_query_args', $slides_query_args );
	
			$slides_query = new WP_Query( $slides_query_args );

			if ( $slides_query->have_posts() ) {

				while ( $slides_query->have_posts() ) {
					$slides_query->the_post();

					$slides[] = array(
						'image'   => absint( $slides_query->post->ID ),
						'caption' => isset( $slides_query->post->post_excerpt ) && is_string( $slides_query->post->post_excerpt ) ? $slides_query->post->post_excerpt : '',
						'url'     => sanitize_text_field( get_post_meta( $slides_query->post->ID, '_ps_image_link', true ) )
					);

				}
			}

			wp_reset_postdata();
		}

		if ( ! is_array( $slides ) || empty( $slides ) ) {
			return $this->public_add_slides_notice();
		}

		if ( 'true' == $this->arg( 'random' ) ) {
			shuffle( $slides );
		}

		if ( 'true' == $this->arg( 'exclude_featured' ) && current_theme_supports( 'post-thumbnails' ) ) {
			$excluded[] = get_post_thumbnail_id( $this->ID );
		}

		if ( ! empty( $this->args['exclude'] ) ) {
			foreach ( explode( ',', $this->args['exclude'] ) as $attachment_id ) {
				$excluded[] = $attachment_id;
			}
		}

		if ( ! empty( $this->args['include'] ) ) {
			$included = explode( ',', $this->args['include'] );
			
			foreach ( wp_list_pluck( $slides, 'image' ) as $key => $slide_id ) {
				if ( ! in_array( $slide_id, $included ) )
					$excluded[] = $slide_id;
			}
		}

		foreach ( $slides as $key => $slide ) {
			if ( in_array( $slide['image'], $excluded ) )
				unset( $slides[ $key ] );
		}

		return array_values( $slides );
	}

	/**
	 * For [portfolio_slideshow] instances with no slides; displays a message to admins, nothing to public.
	 *
	 * @return string
	 */
	function public_add_slides_notice() {
		$post_type    = get_post_type( $this->ID );
		$current_user = get_current_user_id();

		if ( ! $current_user || ! current_user_can( 'edit_' . $post_type, $current_user ) ) {
			return apply_filters( 'portfolio_slideshow_logged_out_no_slideshow_found', '' );
		}

		return apply_filters( 'portfolio_slideshow_logged_in_no_slideshow_found', sprintf(
			'<strong>%s – <a href="%s" target="_blank">%s</a>.</strong>',
			esc_html__( 'No slides found for this slideshow', 'portfolio-slideshow' ),
			esc_url( get_edit_post_link( $this->ID ) ),
			esc_html__( 'add slides here', 'portfolio-slideshow' )
		) );
	}

	/**
	 * If is_feed(), this is called. It just renders one "normal" image instead of JS-dependent slideshow.
	 *
	 * @return void
	 */
	function public_is_feed_notice() {
		echo wp_get_attachment_image( $this->slides[0]['image'], $this->arg( 'size' ) );
	}

	/**
	 * Renders the slideshow meta.
	 *
	 * @return void
	 */
	function the_meta() {
		$slides     = $this->slides;
		$showtitles = $this->arg( 'showtitles' );
		$showcaps   = $this->arg( 'showcaps' );
		$showdesc   = $this->arg( 'showdesc' );

		include Portfolio_Slideshow_Plugin::$plugin_path . 'src/views/meta.php';
	}

	/**
	 * Renders the slideshow navigation.
	 *
	 * @return void
	 */
	function the_nav() {
		$key = absint( $this->key );

		include Portfolio_Slideshow_Plugin::$plugin_path . 'src/views/nav/text.php';
	}

	/**
	 * Renders the slideshow pager.
	 *
	 * @return void
	 */
	function the_pager() {
		$key    = absint( $this->key );
		$slides = $this->slides;

		include Portfolio_Slideshow_Plugin::$plugin_path . 'src/views/pager/thumbs.php';
	}

	/**
	 * Renders each of the individual slides.
	 *
	 * @return void
	 */
	function the_slides() {
		$slides_count     = count( $this->slides );
		$maybe_min_height = '';

		if ( 0 < absint( $this->arg( 'slideheight' ) ) )  {
			$maybe_min_height = sprintf( 'min-height: %spx !important;', $this->arg( 'slideheight' ) );
		}

		include Portfolio_Slideshow_Plugin::$plugin_path . 'src/views/slides.php';
	}

	/**
	 * Assembles the full slideshow and renders it on the page.
	 *
	 * @return string
	 */
	function the_slideshow() {
		
		if ( ! is_array( $this->slides ) || empty( $this->slides ) ) {
			return;
		}

		if ( is_feed() ) {
			$this->public_is_feed_notice();
		}

		ob_start();

			$vars_html = '';
			
			$vars_raw = array(
				'timeout'  => $this->arg( 'timeout' ),
				'autoplay' => $this->arg( 'autoplay' ),
				'trans'    => $this->arg( 'trans' ),
				'loop'     => $this->arg( 'loop' ),
				'speed'    => $this->arg( 'speed'),
				'nowrap'   => $this->arg( 'nowrap'),
			);

			printf( '<script>/* <![CDATA[ */ portfolio_slideshow.slideshows[%s] = %s; /* ]]> */</script>', $this->key, json_encode( $vars_raw ) );

			print '<div id="slideshow-wrapper' . $this->key . '" class="slideshow-wrapper clearfix ' . ( true == $this->arg( 'centered' ) ? 'portfolio-slideshow-centered' : '' );

			if ( 'true' == Portfolio_Slideshow_Plugin::get_option( 'showloader' ) ) {
				print ' showloader';
			}

			print '">';

			if ( 'top' == $this->arg( 'navpos' ) ) $this->the_nav();

			if ( 'top' == $this->arg( 'pagerpos' ) ) $this->the_pager();

			$this->the_slides();

			if ( 'true' == $this->arg( 'showtitles' ) || 'true' == $this->arg( 'showcaps' ) || 'true' == $this->arg( 'showdesc' ) ) {
				$this->the_meta();
			}

			if ( 'bottom' == $this->arg( 'navpos' ) ) $this->the_nav();

			if ( 'bottom' == $this->arg( 'pagerpos' ) ) $this->the_pager();

			print '</div><!--#slideshow-wrapper-->';

		return ob_get_clean();
	}

	/**
	 * Adds legacy psHash and psLoader arguments to the header via wp_head(). Soon to be removed.
	 *
	 * @return void
	 */
	static function wp_head() {
		printf( '<script>/* <![CDATA[ */ portfolio_slideshow = { options : %s, slideshows : new Array() }; /* ]]> */</script>', json_encode(
			array(
				'psHash'   => Portfolio_Slideshow_Plugin::get_option( 'showhash' ),
				'psLoader' => Portfolio_Slideshow_Plugin::get_option( 'showloader' )
			)
		) );
	}
}