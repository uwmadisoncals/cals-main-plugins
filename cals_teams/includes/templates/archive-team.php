
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
		 	//Uncomment this to debug template origin
			echo 'This template is from plugin. archive-team.php';

			include( WP_PLUGIN_DIR . '/cals_teams/includes/data/cals_teams_fields.php' ); //include Metabox and Metabox field group data


		$mbox_fields = $mbox['fields'];//Meta data for field groups
		logit($mbox_fields,'$mbox_fields');


		$args = array('post_type'=>'team');//WP Query Args

		$cals_teams_query = new WP_Query($args);//Instantiate New WP Query Object
		//logit($cals_teams_query,'$cals_teams_query');
		//logit($cals_teams_query->post->ID,'$cals_teams_query id: ');

		 ?>

		<?php if ( have_posts() ) : ?>

			<header>
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="member-grouping">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

			$id = get_the_id();

			//logit($id,'$id: ');
			//logit(get_post_meta($id),'gpmid: ');

				$all_meta = get_post_meta($id);
				//logit($all_meta,'$all_meta: ');

				$name_prefix = get_post_meta($id, 'calsteams_name_prefix')[0];
				$first_name = get_post_meta($id, 'calsteams_first_name')[0];
				$last_name = get_post_meta($id, 'calsteams_last_name')[0];
				$pro_title = get_post_meta($id, 'calsteams_professional_title')[0];

				$checkBox = get_post_meta($id, 'calsteams_checkbox')[0];
				logit($checkBox,'$checkBox: ');
				logit($all_meta,'$all_meta: ');
			?>

				<div class="member-wrapper">

					<div class="member-heading-wrapper">
						<a class ="member" href="<?php esc_url(the_permalink()) ?>"><?php echo $name_prefix . ' ' . $first_name . ' ' . $last_name;  ?></a><br/>
						<span class="protitle"><?php echo $pro_title; ?></span>
					</div>

					<div class="member-body">
					<?php
						if(has_post_thumbnail()) : ?>

							<div class="member-image-wrapper" style="padding-top:20px;">
								<a href="<?php esc_url(the_permalink()) ?>">
									<?php if($checkBox === 'on'){
										the_post_thumbnail('thumbnail',array('class'=>'member-thumbnail round'));
									}else{
										the_post_thumbnail('thumbnail',array('class'=>'member-thumbnail'));
									}
									?>
								</a>
							</div>

						<?php else : ?>

							<div class="member-image-wrapper <?php// if(){} ?>" style="padding-top:20px;">
								<a href="<?php esc_url(the_permalink()) ?>">
									<img class="member-thumbnail" alt="person placeholder image" src="<?php echo plugins_url() . '/cals_teams/includes/images/calsteams_placeholder.png'  ?>" width="150" height="150" />
								</a>
							</div>

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

				</div><!-- END .member-wrapper  -->

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
