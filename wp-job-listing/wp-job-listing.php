<?php
/*
 * Plugin Name: Job Listing
 * Plugin URI: http://hatrackmedia.com
 * Author: darkShadow
 * Description: This is a basic plugin
 * Author URI: http://hatrackmedia.com
 * Version: 1.0
 * License: GPLv2
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require  (plugin_dir_path(__FILE__).'wp-jobs-fields.php');
require  (plugin_dir_path(__FILE__).'wp-job-cpt.php');
require  (plugin_dir_path(__FILE__).'wp-job-render-admin.php');
//require_once ( plugin_dir_path(__FILE__) . 'wp-job-shortcode.php' );

function dwwp_admin_enqueue_scripts(){
	global $pagenow, $typenow;

	if( $typenow == 'job' )
	{
		wp_enqueue_style('dwwp-admin-css', plugins_url('css/admin-jobs.css', __FILE__));
	}

	if(($pagenow == 'post.php') || ($pagenow == 'post-new.php') && $typenow == 'job'){

		wp_enqueue_script('dwwwp-job-js', plugins_url('js/admin-jobs.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '20150204', true);
		wp_enqueue_style('jquery-style', 'http://code.jquery.com/ui/1.8.20/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('dwwp-custom-quicktags', plugins_url('js/dwwp-quicktags.js', __FILE__), array('quicktags'),  '20150206', true);

	}

	if( $typenow == 'job' && $pagenow == 'edit.php' )
	{
		wp_enqueue_script( 'reorder-js', plugins_url( 'js/reorder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20150626', true );
		wp_localize_script( 'reorder-js', 'WP_JOB_LISTING', array(
			'security' => wp_create_nonce( 'wp-job-order' ),
			'success' => __( 'Jobs sort order has been saved.' ),
			'failure' => __( 'There was an error saving the sort order, or you do not have proper permissions.' )
		) );
	}
}

add_action('admin_enqueue_scripts', 'dwwp_admin_enqueue_scripts');

