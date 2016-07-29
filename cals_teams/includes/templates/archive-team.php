
<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main cals_teams archive_team" role="main">
		<?php

			include( WP_PLUGIN_DIR . '/cals_teams/includes/data/cals_teams_fields.php' ); //include Metabox and Metabox field group data

		
		$mbox_fields = $mbox['fields'];//Meta data for field groups
		//logit($mbox_fields,'$mbox_fields');
		

		$args = array('post_type'=>'team',
					  'orderby'=>'menu_order',
					  'order'=>'ASC');//WP Query Args

		$cals_teams_query = new WP_Query($args);//Instantiate New WP Query Object
		//logit($cals_teams_query,'$cals_teams_query');
		//logit($cals_teams_query->post->ID,'$cals_teams_query id: ');
		
		$cals_teams_obj = new Cals_Teams($mbox); //Instantiate Cals_Teams object
		//logit($cals_teams_obj,'$cals_teams_obj: ');

		 ?>

		<?php if ( $cals_teams_query->have_posts() ) : ?>

			<header>
				<?php
					//the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
				<h1 class="page-title" >Lab Members</h1>
			</header><!-- .page-header -->

			<div class="member-grouping bricklayer">
			
			<?php
			// Start the Loop.
			while ( $cals_teams_query->have_posts() ) : $cals_teams_query->the_post();

			$id = get_the_id();

			//logit($id,'$id: ');
			//logit(get_post_meta($id),'gpmid: ');
			
				$all_meta = get_post_meta($id);
				//logit($all_meta,'$all_meta: ');

				$name_prefix = get_post_meta($id, 'calsteams_name_prefix')[0];
				$first_name = get_post_meta($id, 'calsteams_first_name')[0];
				$last_name = get_post_meta($id, 'calsteams_last_name')[0];
				$pro_title = get_post_meta($id, 'calsteams_professional_title')[0];
				$image_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				//logit($image_url);

			?>
				<div class="member_wrapper">

				<?php if($image_url){ ?>

					<a href="<?php esc_url( the_permalink() ); ?>">
					<div class="newImg" style="background: url(<?php echo $image_url ?>) no-repeat center center; background-size: cover;"></div>
					</a>

				<?php }else{ ?>
					<a href="<?php esc_url( the_permalink() ); ?>">
					<div class="newImg" style="background: url('<?php echo plugins_url() . '/cals_teams/includes/images/calsteams_placeholder.png' ?>') no-repeat center center; background-size: cover;"></div>
					</a>
					
				<?php } ?>

				

				<div class="member_info_wrapper">

					<div class="member-heading-wrapper">
						<a class ="member" href="<?php esc_url(the_permalink()) ?>"><?php echo $name_prefix . ' ' . $first_name . ' ' . $last_name;  ?></a><br/>
						<span class="protitle"><?php echo $pro_title; ?></span>
					</div>

					<div class="member-body">
					<?php 
						if(has_post_thumbnail()) : ?>




<!-- 							<div class="member-image-wrapper" style="padding-top:20px;">
								<a href="<?php esc_url(the_permalink()) ?>">
									<?php the_post_thumbnail('medium',array('class'=>'member-thumbnail')); ?>
								</a>
							</div> -->

						<?php else : ?>

<!-- 							<div class="member-image-wrapper" style="padding-top:20px;">
								<a href="<?php esc_url(the_permalink()) ?>">
									<img class="member-thumbnail" alt="person placeholder image" src="<?php echo plugins_url() . '/cals_teams/includes/images/calsteams_placeholder.png'  ?>" width="150" height="150" />
								</a>
							</div>
 -->							
						<?php endif; ?>

						<div class="member-data-wrapper">
						<?php
					//using foreach
					foreach ($mbox_fields as $key => $value) {
						$field_id = $value['id'];//field id name
						$allowed_fields = array('calsteams_office_location','calsteams_phone','calsteams_email');

						if(!empty(get_post_meta($id,$field_id)[0]) && in_array($field_id,$allowed_fields) ){

							?>
							<div class="member-data-field <?php echo $field_id; ?>">
								<span class="field-label"></span>
								<span class="field-data"><?php echo get_post_meta($id,$field_id)[0]; ?></span>
							</div>

							<?php
							
						}
						
						
					}// END foreach
					?>

					</div><!-- END .member-data-wrapper -->

					</div><!--END .member-body -->

				</div><!-- END .member_info_wrapper  -->

			</div><!-- END .member_wrapper -->
				

				<?php

			// End the loop.
			endwhile; ?>

			</div><!-- END .member-grouping -->

			<?php

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
