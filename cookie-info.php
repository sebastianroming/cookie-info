<?php
/*
Plugin Name: Cookie-Info
Plugin URI: http://www.sebastianroming.de/wordpress-plugins/
Description: Ein simples Plugin zur Konformität mit der EU Cookie Richtline
Version: 0.1.1
Author: Sebastian Roming
Author URI: http://www.sebastianroming.de
License: GPL2
Text Domain: cookie-info
*/

/*	
	Copyright 2016 Sebastian Roming

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
ob_end_flush();

require_once dirname( __FILE__ ) . '/cookie-info-core.php';