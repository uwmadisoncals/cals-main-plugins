<?php
/**
 * Copyright (C) 2014-2017 ServMask Inc.
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

class Ai1wmme_Import_Confirm {

	public static function execute( $params ) {

		//  Check multiste.json file
		if ( true === is_file( ai1wm_multisite_path( $params ) ) ) {

			// Read multisite.json file
			$handle = ai1wm_open( ai1wm_multisite_path( $params ), 'r' );

			// Parse multisite.json file
			$multisite = ai1wm_read( $handle, filesize( ai1wm_multisite_path( $params ) ) );
			$multisite = json_decode( $multisite, true );

			// Close handle
			ai1wm_close( $handle );

			// Validate
			if ( empty( $multisite['Network'] ) ) {
				if ( isset( $multisite['Sites'] ) && ( $sites = $multisite['Sites'] ) ) {
					if ( count( $sites ) === 1 && ( $subsite = current( $sites ) ) ) {
						Ai1wm_Status::confirm(
							__(
								'The import process will overwrite your subsite including the database, media, plugins and themes. ' .
								'Please ensure you have a backup before proceeding to the next step.',
								AI1WMME_PLUGIN_NAME
							)
						);
					} else {
						Ai1wm_Status::confirm(
							__(
								'The import process will overwrite your subsites including the database, media, plugins and themes. ' .
								'Please ensure you have a backup before proceeding to the next step.',
								AI1WMME_PLUGIN_NAME
							)
						);
					}
				}
			} else {
				Ai1wm_Status::confirm(
					__(
						'The import process will overwrite your website including the database, media, plugins and themes. ' .
						'Please ensure you have a backup before proceeding to the next step.',
						AI1WMME_PLUGIN_NAME
					)
				);
			}
		} else {
			Ai1wm_Status::confirm(
				__(
					'The import process will overwrite your subsite including the database, media, plugins and themes. ' .
					'Please ensure you have a backup before proceeding to the next step.',
					AI1WMME_PLUGIN_NAME
				)
			);
		}

		exit;
	}
}
