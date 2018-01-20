<?php
/*
 * Plugin Name: System Info
 * Plugin URI: http://metgimet.com
 * Author: darkShadow
 * Description: Provides information about the system
 * Version: 1.0
 * License: GPLv2
 */

if (! defined('ABSPATH')){
	exit;
}

require_once (plugin_dir_path(__FILE__).'wp-system.php');
require_once (plugin_dir_path(__FILE__).'system-info.php');