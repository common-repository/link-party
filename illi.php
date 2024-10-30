<?php
/*
Plugin Name: illi Link Party!
Plugin URI: http://www.illistyle.com/linkparty/
Description: Host your own Link Party, all form your own site.  Speed up image load times and receive the increased SEO benefits.  Users upload a single image and link to their site (nofollow) and then through shortodes, display all the images.  visit <a href="options-general.php?page=illi3/includes/admin.php">Settings &raquo; Link Party!</a>.
Version: 1.0
Author: Evan Liewer
Author URI: http://www.evanliewer.com
License: GPLv2+
*/

/*  Copyright 2013  Evan Liewer

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or 
	(at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Define Variables */
global $reporter;
if( ! defined( 'RL_VER' ))
	define( 'RL_VER', '1.2.0' );
if( ! defined( 'RL_BASE' ))
	define( 'RL_BASE' , dirname(__FILE__) );
if( ! defined( 'RL_DIRECTORY' ))
	define( 'RL_DIRECTORY' , get_option('siteurl') . '/wp-content/plugins/illi' );
if( ! defined( 'RL_INC' ))
	define( 'RL_INC' , RL_DIRECTORY . '/includes' );
if( ! defined( 'RL_BASE_INC' ))
	define( 'RL_BASE_INC', RL_BASE . '/includes' );

/* Check to see if this is a new installation or an upgrade */
$current_ver = get_option('illi_Version');

if( $current_ver && version_compare($current_ver, RL_VER, '<')) {
	// Clean up upgrades
	delete_option('plugin_illi_user');
	delete_option('plugin_illi_page');
	delete_option('plugin_illi_default');
	delete_option('plugin_illi_set');
	delete_option('plugin_illi_version');
}



if( version_compare($current_ver, '1.0', '<') || !$current_ver )
	include(RL_BASE_INC . '/install.php');

update_option('illi_Version', '1.2.0');


/*
 * Sets admin warnings regarding required PHP and WordPress versions.
 */
function _rl_wp_warning() {
	$data = get_plugin_data(__FILE__);
	
	echo '<div class="error"><p><strong>' . __('Warning:') . '</strong> '
		. sprintf(__('The active plugin %s is not compatible with your WordPress version.') .'</p><p>',
			'&laquo;' . $data['Name'] . ' ' . $data['Version'] . '&raquo;')
		. sprintf(__('%s is required for this plugin.'), 'WordPress 2.8 ');
	echo '</p></div>';
}

// START PROCEDURE

// Check required WordPress version.
if ( version_compare(get_bloginfo('version'), '2.8', '<')) {
	add_action('admin_notices', '_rl_wp_warning');
} else {
	include_once ( RL_BASE_INC . '/core.php' );
}

?>