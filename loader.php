<?php
/*
Plugin Name: Heavy Analytics
Plugin URI: http://www.heavyanalytics.com
Description: Alpha release of the Heavy Analytics plugin. Heavy Analytis is a WordPress plugin used to track content statistics.
Version: 0.5.3
Revision Date: 2013.06.23
Requires at least: WordPress 3.0
Tested up to: WordPress 3.5.2
License: GPL2
Author: Eric Johnson, Toby Cryns
Author URI: http://www.heavyanalytics.com

Copyright 2011  Eric Johnson, Toby Cryns  (email : info@heavyanalytics.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Only load the component if WordPress is loaded and initialized. */
function heavyanalytics_init() {
	require ( dirname(__FILE__) .'/includes/heavyanalytics-core.php');
}
add_action( 'init', 'heavyanalytics_init' );