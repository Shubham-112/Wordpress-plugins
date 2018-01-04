<?php
/**
 * Created by PhpStorm.
 * User: DarkShadow
 * Date: 03-01-2018
 * Time: 16:51
 */

/*
 * Plugin Name: Job Listing
 * Plugin URI: http://hatrackmedia.com
 * Author: darkShadow
 * Description: This is a basic plugin
 * Author URI: http://hatrackmedia.com
 * Version: 1.0
 * License: GPLv2
 */

if (!defined('ABSPATH')){
    exit;
}

require  (plugin_dir_path(__FILE__).'wp-job-cpt.php');
require  (plugin_dir_path(__FILE__).'wp-job-render-admin.php');
require  (plugin_dir_path(__FILE__).'wp-jobs-fields.php');
