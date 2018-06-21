<?php
/**
 * Copyright (C) 2014-2018 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */
?>

<div class="ai1wm-field-set">
	<ul class="ai1wmme-sites-list">
		<li>
			<label for="ai1wmme-network">
				<input type="checkbox" name="options[network]" value="1" id="ai1wmme-network" class="ai1wmme-network" checked="checked" />
				<?php _e( 'Network (all sites)', AI1WMME_PLUGIN_NAME ); ?>
			</label>
		</li>
		<?php foreach ( ai1wmme_sites() as $site ) : ?>
			<li>
				<label for="ai1wmme-site-<?php echo esc_attr( $site['BlogID'] ); ?>">
					<input type="checkbox" name="options[sites][]" value="<?php echo esc_attr( $site['BlogID'] ); ?>" id="ai1wmme-site-<?php echo esc_attr( $site['BlogID'] ); ?>" class="ai1wmme-sites" />
					<?php echo esc_html( $site['Domain'] . untrailingslashit( $site['Path'] ) ); ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
