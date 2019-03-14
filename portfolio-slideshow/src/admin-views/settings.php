<?php
/**
 * Markup for the Portfolio Slideshow settings page and tabs.
 */
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_and_behavior'; ?>

<div class="wrap portfolio-slideshow-settings-wrap">

	<h2><?php esc_html_e( 'Portfolio Slideshow', 'portfolio-slideshow' ); ?></h2>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( Portfolio_Slideshow_Settings::get_tabs() as $tab_slug => $tab_name ) : ?>

		<a href="<?php echo add_query_arg( 'tab', $tab_slug, remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == $tab_slug ? 'nav-tab-active' : ''; ?>">
			<?php echo esc_html( $tab_name ); ?>
		</a>
		<?php endforeach; ?>
	</h2>

	<div id="poststuff">

		<div class="metabox-holder columns-2" id="post-body">

			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">

					<div id="tab_container">

						<form method="post" action="options.php"><?php

							switch ( $active_tab ) :

								case 'display_and_behavior' :

								settings_fields( 'portfolio_slideshow_options' ); ?>

								<h3><?php esc_html_e( 'Display Settings', 'portfolio-slideshow' ); ?></h3>
								<table class="form-table">
									<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_display' ); ?>
								</table>

								<h3><?php esc_html_e( 'Behavior Settings', 'portfolio-slideshow' ); ?></h3>
								<table class="form-table">
									<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_behavior' ); ?>
								</table>
								<?php break; ?>

							<?php case 'pager_and_navigation' : ?>

								<?php settings_fields( 'portfolio_slideshow_options' ); ?>

								<h3><?php esc_html_e( 'Navigation Settings', 'portfolio-slideshow' ); ?></h3>
								<table class="form-table">
									<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_navigation' ); ?>
								</table>

								<?php break; ?>

							<?php case 'documentation' : ?>
								<?php require 'documentation.php'; ?>
								<?php break; ?>

							<?php case 'system_information' : ?>
								<?php require PORTFOLIO_SLIDESHOW_PATH . 'src/Portfolio_Slideshow/Support.php'; ?>
								<?php require 'system-information.php'; ?>
								<?php break; ?>

							<?php endswitch; ?>

							<?php if ( 'documentation' !== $active_tab && 'system_information' !== $active_tab ) : ?>
								<?php submit_button(); ?>
							<?php endif; ?>
						</form>

					</div><!-- #tab_container-->

				</div><!-- /meta-box-sortables -->
			</div><!-- /post-body-content -->

			<div class="postbox-container" id="postbox-container-1">
				<div class="meta-box-sortables">

					<div class="postbox">
						<h3><?php esc_html_e( 'Documentation', 'portfolio-slideshow' ); ?></h3>

						<div class="inside">
							<ul>
								<?php
									foreach( Portfolio_Slideshow_Settings::get_documentation_sections() as $slug => $title ) {
										printf( '<li><a href="%s" target="_blank">%s</a></li>', admin_url( sprintf( 'options-general.php?page=portfolio_slideshow&tab=documentation#%s', $slug ) ), esc_html( $title ) );
									}
								?>
							</ul>
						</div>
					</div>

					<div class="postbox">
						<h3><?php esc_html_e( 'Support', 'portfolio-slideshow' ); ?></h3>

						<div class="inside">
							<p><?php echo wp_kses_post( sprintf( __( 'Need help? The <a href="%s" target="_blank">free Portfolio Slideshow support forums</a> are checked a few times per week.', 'portfolio-slideshow' ), 'http://wordpress.org/support/plugin/portfolio-slideshow' ) ); ?></p>
						</div>
					</div>

				</div><!-- /meta-box-sortables -->
			</div><!-- /postbox-container -->

		</div><!-- /post-body -->
		<br class="clear">

	</div><!-- /poststuff -->
</div><!-- /wrap -->