<?php get_header(); ?>

<div class="site-content-inner">

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php

		include( WP_PLUGIN_DIR . '/cals_teams/includes/data/cals_teams_fields.php' ); //include Metabox and Metabox field group data

		
		$mbox_fields = $mbox['fields'];//Meta data for field groups
		//logit($mbox_fields,'$mbox_fields');
		//logit($mbox,'$mbox: ');


		$args = array('post_type'=>'team');//WP Query Args

		$cals_teams_query = new WP_Query($args);//Instantiate New WP Query Object
		//logit($cals_teams_query,'$cals_teams_query');
		//logit($cals_teams_query->post->ID,'$cals_teams_query id: ');

		// Start the loop.
		while ( have_posts() ) : the_post();

		$plugin_template_obj = new Cals_Teams(); //Instantiate Cals_Teams object
		//logit($plugin_template_obj,'$plugin_template_obj: ');

/*		$radio = $plugin_template_obj->meta_box->fields->calsteams_radio;
		//logit($radio,'$radio: ');*/

		$meta = $plugin_template_obj->calsteams_get_post_meta(); // Filter out unwanted elements from get_post_custom

		//logit($meta,'$get_post_meta: ');
		//$myctfields = new CTFields($mbox_fields);

		//$myctmetabox = new CTMetaBox($mbox);

		//logit($myctmetabox, '$myctmetabox: ');
		//logit($myctfields, '$myctfields: ');

		//$prfx = $myctmetabox->fields->calsteams_name_prefix->name;
		//logit($prfx,'$prfx');


		//logit($cals_teams_query, '$cals_teams_query');

			//////////////////////// INNER CONTENT ////////////////////////////////
			?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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
					echo '<div class="upper-container">';

					if(has_post_thumbnail()){
					echo '<div class="image-wrapper";">';
					the_post_thumbnail('full',array('class'=>'aligncenter'));
					echo '</div>';
				}else{

					echo '<div class="image-wrapper";">';
					the_post_thumbnail('full',array('class'=>'aligncenter')); ?>

					<img class="member-thumbnail" alt="person placeholder image" src="<?php echo plugins_url() . '/cals_teams/includes/images/calsteams_placeholder.png'  ?>" width="150" height="150" />
					
					<?php
					echo '</div>';
				}


					echo '<div class="short-vals-container">';

					foreach($plugin_template_obj->data as $_key_ => $_value_){

							if(!empty($_value_)){

								$id = $_key_ ;
								$the_fields = $plugin_template_obj->meta_box->fields;

								if( property_exists($plugin_template_obj->meta_box->fields, $id) ){

										if( $the_fields->$id->type == 'text' || $the_fields->$id->type == 'select' ){

										echo '<div class="team-item ' . $the_fields->$id->type . '" style="margin-bottom:20px;"><span class="team-item-label" style="font-weight:bold;">';

										echo $plugin_template_obj->meta_box->fields->$id->name . ': ';

										echo '</span>';

										echo '<span class="team-item-value">';

										echo $_value_;

										echo '</span></div>';

									}
									
								}
							}
					}
					echo '</div></div>';

					foreach($plugin_template_obj->data as $_key_ => $_value_){

							if(!empty($_value_)){

								$id = $_key_ ;
								$the_fields = $plugin_template_obj->meta_box->fields;

								if( property_exists($plugin_template_obj->meta_box->fields, $id) ){

										if( !($the_fields->$id->type == 'text' || $the_fields->$id->type == 'select')  ){

										echo '<div class="team-item ' . $the_fields->$id->type . '" style="margin-bottom:20px;"><span class="team-item-label" style="font-weight:bold;">';

										echo $plugin_template_obj->meta_box->fields->$id->name . ': ';

										echo '</span>';

										echo '<div class="team-item-value">';

										echo $_value_;

										echo '</div></div>';

									}
									
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
	</div><!-- .site-content-inner -->
	
<?php get_footer(); ?>