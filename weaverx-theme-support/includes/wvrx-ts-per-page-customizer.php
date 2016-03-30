<?php
//** experimental code for future addition of per page and per post options
// currently unable to make work because can't retrieve current page ID or post ID (on single page) to make the per page/post options meaningful.
//
if (false) {
if ( ! function_exists( 'weaverx_customizer_per_page_sections' ) ) :


function weaverx_customizer_per_page_sections( $sections ) {
	$panel = 'weaverx_per_page';
	$perpp_sections = array();


	/**
	 * basic intro page
	 */
	$perpp_sections['perpage-intro'] = array(
		'panel'   => $panel,
		'title'   => __( 'Per Page Intro', 'weaver-xtreme' ),
		'options' => array(
			'per-page-heading-header' => weaverx_cz_heading( 'Per Page Intro. PAGE ID:=' . the_ID(). '='),
		),
	);



	/**
	 * Filter the definitions for the controls in the Per Page panel of the Customizer.
	 *
	 * @since 1.3.0.
	 *
	 * @param array    $perpp_sections    The array of definitions.
	 */
	$perpp_sections = apply_filters( 'weaverx_customizer_perpage_sections', $perpp_sections );

	// Merge with master array
	return array_merge( $sections, $perpp_sections );
}
endif;

add_filter( 'weaverx_customizer_sections', 'weaverx_customizer_per_page_sections' );

add_filter ('weaverx_add_per_page_customizer', 'weaverx_customizer_add_per_page');

function weaverx_customizer_add_per_page( $data ) {

	return array( 'title' => __( 'Per Page Options', 'weaver-xtreme' ), 'priority' => 11200,
				'description'    => __( 'Define Custom Settings for currently displayed page.' , 'weaver-xtreme' ),
				'active_callback' => 'wvrx_ts_is_page' );

}

function wvrx_ts_is_page() {
	return true;
}
}
