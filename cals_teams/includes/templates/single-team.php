<?php get_header(); ?>

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php

		include( WP_PLUGIN_DIR . '/cals_teams/includes/data/cals_teams_fields.php' ); //include Metabox and Metabox field group data

		
		$mbox_fields = $mbox['fields'];//Meta data for field groups
		//logit($mbox_fields,'$mbox_fields');


		$args = array('post_type'=>'team');//WP Query Args

		$cals_teams_query = new WP_Query($args);//Instantiate New WP Query Object
		//logit($cals_teams_query,'$cals_teams_query');
		//logit($cals_teams_query->post->ID,'$cals_teams_query id: ');

		// Start the loop.
		while ( have_posts() ) : the_post();

		//Uncomment this to debug template origin
		//echo 'THIS IS TEMPLATE FROM Plugin';

		$plugin_template_obj = new Cals_Teams; //Instantiate Cals_Teams object


		$meta = $plugin_template_obj->calsteams_get_post_meta(); // Filter out unwanted elements from get_post_custom

		//logit($meta,'$meta: ');

		//logit($cals_teams_query, '$cals_teams_query');

			//////////////////////// INNER CONTENT ////////////////////////////////
			?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
				// Post thumbnail.

				//$post_thumbnail_id = get_post_thumbnail_id();//14

				//$thumb = $meta['_thumbnail_id'][0];//14

				if(has_post_thumbnail()){
					echo '<div class="image-wrapper" style="padding-top:20px;">';
					the_post_thumbnail('full',array('class'=>'aligncenter'));
					echo '</div>';
				}
			?>

			<header class="entry-header">
				<?php
					if ( is_single() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
					endif;
				?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php

				foreach ($mbox_fields as $key => $value) {
					$id = $value['id'];
					if(array_key_exists($id,$meta)){
						if($meta[$id][0]){

						echo '<div class="team-item" style="margin-bottom:20px;"><span class="team-item-label" style="font-weight:bold;">';
						echo $value['name'] . ': ';
						echo '</span>';

						echo '<span class="team-item-value">';
						
						echo $meta[$id][0];
						
						echo '</span></div>';

						}
					}
				}

					/* translators: %s: Name of current post */
/*					the_content( sprintf(
						__( 'Continue reading %s', 'twentyfifteen' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );*/

					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					) );
				?>
			</div><!-- .entry-content -->

			<?php
				// Author bio.
				if ( is_single() && get_the_author_meta( 'description' ) ) :
					get_template_part( 'author-bio' );
				endif;
			?>

			<div class="entry-footer">

				<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-footer -->

		</article><!-- #post-## -->
		<?php 
			
			/////////////////////////// END INNER CONTENT /////////////////////////////////

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

/*			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );*/

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
	
<?php get_footer(); ?>