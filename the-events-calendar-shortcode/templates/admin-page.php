<div class="wrap">
	<h2><?php _e( 'The Events Calendar Shortcode' ); ?></h2>

	<p><?php echo sprintf( esc_html__( 'The shortcode displays lists of your events. For example the shortcode to show next 8 events in the category "%s" in ASC order with date showing:', 'the-events-calendar-shortcode' ), 'festival' ); ?></p>

	<pre>[ecs-list-events cat='festival' limit='8']</pre>

	<table>
		<tbody>
		<tr valign="top">
			<td valign="top">

				<div>
					<h2><?php echo esc_html( __( 'Basic shortcode', 'the-events-calendar-shortcode' ) ); ?></h2>
						<blockquote>[ecs-list-events]</blockquote>

					<h2><?php echo esc_html( __( 'Shortcode Options', 'the-events-calendar-shortcode' ) ); ?></h2>
					<?php do_action( 'ecs_admin_page_options_before' ); ?>

					<h3>cat</h3>
					<p><?php echo esc_html( __( 'Represents single event category.  Use commas when you want multiple categories', 'the-events-calendar-shortcode' ) ); ?>
						<blockquote>[ecs-list-events cat='festival']</blockquote>
						<blockquote>[ecs-list-events cat='festival, workshops']</blockquote>

					<?php do_action( 'ecs_admin_page_options_after_cat' ); ?>

					<h3>limit</h3>
					<p><?php echo esc_html( __( 'Total number of events to show. Default is 5.', 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events limit='3']</blockquote>
					<h3>order</h3>
					<p><?php echo esc_html( __( "Order of the events to be shown. Value can be 'ASC' or 'DESC'. Default is 'ASC'. Order is based on event date.", 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events order='DESC']</blockquote>
					<h3>date</h3>
					<p><?php echo esc_html( __( "To show or hide date. Value can be 'true' or 'false'. Default is true.", 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events eventdetails='false']</blockquote>
					<h3>venue</h3>
					<p><?php echo esc_html( __( "To show or hide the venue. Value can be 'true' or 'false'. Default is false.", 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events venue='true']</blockquote>
					<h3>excerpt</h3>
					<p><?php echo esc_html( __( 'To show or hide the excerpt and set excerpt length. Default is false.', 'the-events-calendar-shortcode' ) ); ?><p>
						<blockquote>[ecs-list-events excerpt='true']</blockquote>
						<blockquote>[ecs-list-events excerpt='300']</blockquote>
					<h3>thumb</h3>
					<p><?php echo esc_html( __( 'To show or hide thumbnail/featured image. Default is false.', 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events thumb='true']</blockquote>
					<p><?php echo esc_html( __( 'You can use 2 other attributes: thumbwidth and thumbheight to customize the thumbnail size', 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events thumb='true' thumbwidth='150' thumbheight='150']</blockquote>
					<h3>message</h3>
					<p><?php echo esc_html( sprintf( __( "Message to show when there are no events. Defaults to '%s'", 'the-events-calendar-shortcode' ), translate( 'There are no upcoming events at this time.', 'tribe-events-calendar' ) ) ); ?></p>
					<h3>viewall</h3>
					<p><?php echo esc_html( sprintf( __( "Determines whether to show '%s' or not. Values can be 'true' or 'false'. Default to 'true'", 'the-events-calendar-shortcode' ), translate( 'View all events', 'tribe-events-calendar' ) ) ); ?></p>
						<blockquote>[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false']</blockquote>
					<h3>contentorder</h3>
					<p><?php echo esc_html( sprintf( __( 'Manage the order of content with commas. Defaults to %s', 'the-events-calendar-shortcode' ), 'title, thumbnail, excerpt, date, venue' ) ); ?> </p>
						<blockquote>[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false' contentorder='title, thumbnail, excerpt, date, venue']</blockquote>
					<h3>month</h3>
					<p><?php echo esc_html( sprintf( __( "Show only specific Month. Type '%s' for displaying current month only, ie:", 'the-events-calendar-shortcode' ), 'current' ) ); ?></p>
						<blockquote>[ecs-list-events cat='festival' month='2015-06']</blockquote>
					<h3>past</h3>
					<p><?php echo esc_html( __( 'Show outdated events (ie. events that have already happened)', 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events cat='festival' past='yes']</blockquote>
					<h3>key</h3>
					<p><?php echo esc_html( __( 'Use to order by the start date instead of the end date', 'the-events-calendar-shortcode' ) ); ?></p>
						<blockquote>[ecs-list-events cat='festival' key='start date']</blockquote>

					<?php do_action( 'ecs_admin_page_options_after' ); ?>

				</div>

			</td>
			<td valign="top" class="styling">
				<h3>Styling/Design</h3>

				<?php do_action( 'ecs_admin_page_styling_before' ); ?>

				<?php if ( apply_filters( 'ecs_show_upgrades', true ) ): ?>

					<p><?php echo esc_html( __( 'By default the plugin does not include styling. Events are listed in ul li tags with appropriate classes for styling and you can add your own CSS:', 'the-events-calendar-shortcode' ) ) ?></p>

					<ul>
						<li>ul class="ecs-event-list"</li>
						<li>li class="ecs-event"</li>
						<li><?php echo esc_html( sprintf( __( 'event title link is %s', 'the-events-calendar-shortcode' ), 'H4 class="entry-title summary"' ) ); ?> </li>
						<li><?php echo esc_html( sprintf( __( 'date class is %s', 'the-events-calendar-shortcode' ), 'time' ) ); ?></li>
						<li><?php echo esc_html( sprintf( __( 'venue class is %s', 'the-events-calendar-shortcode' ), 'venue' ) ); ?></li>
						<li>span .ecs-all-events</li>
						<li>p .ecs-excerpt</li>
					</ul>

					<hr>

					<p><h3><?php echo esc_html__( 'Want a better looking design without adding any CSS?', 'the-events-calendar-shortcode' ) ?></h3></p>
					<p><?php echo sprintf( esc_html__( 'Check out %sThe Events Calendar Shortcode PRO%s', 'the-events-calendar-shortcode' ), '<a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design&utm_content=description">', '</a>' ); ?></p>
					<p><a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-1&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/shortcode-default-design-2.png', TECS_CORE_PLUGIN_FILE ) ?>"><br><?php echo esc_html( __( 'Pro version default design example', 'the-events-calendar-shortcode' ) ); ?></a></p>
					<p><a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-2&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/event-calendar-shortcode-compact-design.png', TECS_CORE_PLUGIN_FILE ) ?>"><br><?php echo esc_html( __( 'Pro version compact design example', 'the-events-calendar-shortcode' ) ); ?></a></p>

					<hr>

					<h3><?php echo esc_html__( "In addition to designs, you'll get more options including:", 'the-events-calendar-shortcode' ); ?></h3>
					<h4><?php echo esc_html__( 'Number of days', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Choose how many days to show events from, ie. 1 day or a week', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Tag', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Filter events listed by one or more tags', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Single Event', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'List the details of a single event by ID, for example on a blog post', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Year', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Show only events for a specific year', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Offset', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Skip a certain number of events from the beginning, useful for using multiple shortcodes on the same page or splitting into columns.', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Full Description', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Use the full description instead of the excerpt (short description) of an event in the listing', 'the-events-calendar-shortcode' ) ?></p>
					<h4><?php echo esc_html__( 'Custom Design', 'the-events-calendar-shortcode' ) ?></h4>
					<p><?php echo esc_html__( 'Use the new default or compact designs, or create your own using one or more templates in your theme folder', 'the-events-calendar-shortcode' ) ?></p>
					<p><?php echo sprintf( esc_html__( '%sGet The Events Calendar Shortcode PRO%s', 'the-events-calendar-shortcode' ), '<a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-after-options&utm_content=description">', '</a>' ); ?></p>

				<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>

	<p><small><?php echo sprintf( esc_html__( 'This plugin is not developed by or affiliated with The Events Calendar or %s in any way.', 'the-events-calendar-shortcode' ), 'Modern Tribe' ); ?></small></p>
</div>