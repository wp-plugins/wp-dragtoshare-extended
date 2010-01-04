<?php
/*
	Plugin Name: WP DragToShare eXtended
	Plugin URI: http://www.lejournaldublog.com/plugin-wordpress-drag-to-share-extended
	Description: This plugin was bring to you by <a href="http://www.milky-interactive.com/" title="Milky Interactive" target="_blank">Milky Interactive</a>. <strong>This plugin mimic the Mashable functionality</strong> where news stories and interesting articles <strong>can be shared to social networking sites</strong>. The functionality is driven by the images accompanying the articles, you <strong>click and hold on an image</strong> and can then <strong>drag it into a toolbar</strong> to <strong>share it</strong>. <strong><em>It's brilliant and intuitive!</em></strong>
	Author: Milky-Interactive
	Version: 1.13
	Author URI: http://www.milky-interactive.com
*/

/*  Copyright 2009  SyntaxTerr0r  (email : seb@milky-interactive.com)

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

// Init the plugins directory
define('DTSE_ABS_URL', WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
define('DTSE_REL_URL', dirname( plugin_basename(__FILE__) ));
define('DTSE_ABS_PATH', WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)).'/' );

// Include plugins files
include_once(DTSE_ABS_PATH .'lib/dtse.functions.php');
dtse_load_admin();

//Default options on plugin activation
register_activation_hook(__FILE__, 'dtse_install');

// Load translations
add_filter('init', 'dtse_init_locale');

//Load css & javascript into blog header
add_action('init', 'dtse_front_init_step_one');
add_action('wp_head', 'dtse_front_init_step_two');

global $permalink;
$permalink = get_option('dtse_permalink');
$auto = get_option('dtse_auto');

if($auto == 'true') {
	//Add filter to post content
	add_filter('the_content', 'dtse_content_filter');
	add_shortcode('dtse', 'dtse_no_shortcode');
} else {
	//handling chosen img
	if($permalink == 'true') {
		add_filter('the_content', 'dtse_content_script');
	}
	
	add_shortcode('dtse', 'dtse_shortcode');
}

//Register admin options
//add_action('admin_init', 'dtse_load_admin');
add_action('admin_menu', 'dtse_menu');

?>