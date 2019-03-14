<?php
/*
 * Plugin Name: Portfolio Slideshow
 * Plugin URI: http://wordpress.org/plugins/portfolio-slideshow
 * Description: Build elegant, responsive slideshows in seconds.
 * Author: George Gecewicz
 * Version: 1.13.0
 * Author URI: http://ggwi.cz
 * License: GPLv2 or later
 * Text Domain: portfolio-slideshow
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

define( 'PORTFOLIO_SLIDESHOW_VERSION', '1.13.0' );
define( 'PORTFOLIO_SLIDESHOW_URL', plugin_dir_url( __FILE__ ) );
define( 'PORTFOLIO_SLIDESHOW_PATH', plugin_dir_path( __FILE__ ) );
define( 'PORTFOLIO_SLIDESHOW_INC', PORTFOLIO_SLIDESHOW_PATH . 'includes/' );

require_once( PORTFOLIO_SLIDESHOW_PATH . '/src/Portfolio_Slideshow/Plugin.php' );

Portfolio_Slideshow_Plugin::instance();