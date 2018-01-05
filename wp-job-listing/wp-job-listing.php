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

function dwwp_admin_enqueue_scripts(){
    global $pagenow, $typenow;

    if(($pagenow == 'post.php') || ($pagenow == 'post-new.php') && $typenow == 'job'){
    	wp_enqueue_style('dwwp-admin-css', plugins_url('css/admin-jobs.css', __FILE__));
    	wp_enqueue_script('dwwwp-job-js', plugins_url('js/admin-jobs.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '20150204', true);
    	wp_enqueue_style('jquery-style', 'http://code.jquery.com/ui/1.8.20/themes/smoothness/jquery-ui.css');
    	wp_enqueue_script('dwwp-custom-quicktags', plugins_url('js/dwwp-quicktags.js', __FILE__), array('quicktags'),  '20150206', true);
    }
}

add_action('admin_enqueue_scripts', 'dwwp_admin_enqueue_scripts');

function dwwp_add_submenu_page(){
	add_submenu_page(
		'edit.php?post_type=job',
		'Reorder Jobs',
		'Reorder Jobs',
		'manage_options',
		'reorder_jobs',
		'reorder_admin_jobs_callback'
	);
}
add_action('admin_menu', 'dwwp_add_submenu_page');