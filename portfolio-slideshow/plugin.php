<?php
/*
 * Plugin Name: Portfolio Slideshow
 * Plugin URI: http://wordpress.org/plugins/portfolio-slideshow
 * Description: Build elegant, responsive slideshows in seconds.
 * Author: George Gecewicz
 * Version: 1.12.0
 * Author URI: http://ggwi.cz
 * License: GPLv2 or later
 * Text Domain: portfolio-slideshow
 *
 * Copyright 2016 George Gecewicz
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

define( '__PORTFOLIO_SLIDESHOW_PLUGIN_FILE__', __FILE__ );

require_once( dirname( __PORTFOLIO_SLIDESHOW_PLUGIN_FILE__ ) . '/src/Portfolio_Slideshow/Plugin.php' );

Portfolio_Slideshow_Plugin::instance();