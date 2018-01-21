<?php
/**
 * Created by PhpStorm.
 * User: DarkShadow
 * Date: 22-01-2018
 * Time: 00:54
 */

function add_pagespeed_info(){
	add_submenu_page(
		'edit.php?post_type=system',
		__('Google Pagespeed'),
		__('Google Pagespeed'),
		'manage_options',
		'google_pagespeed',
		'pagespeed_callback'
	);
}

add_action('admin_menu', 'add_pagespeed_info');

function pagespeed_callback(){
	$key = 'AIzaSyAqayHDudHTrNhPpN_ErWOoFJAUuC-i6sA';
	$url = 'http://www.metgimet.com';

	$pagespeed = 'https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url='.$url.'&key='.$key;

	$ch = curl_init();
	$timeout = 60;
	curl_setopt($ch, CURLOPT_URL, $pagespeed);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$res = curl_exec($ch);
	curl_close($ch);
	$json = (array)json_decode($res);
	echo 'URL tested: '.$url.'<br>';
	echo 'Google pagespeed score: '.$json['ruleGroups']->SPEED->score.'<br>';
	echo 'Number of CSS Resources: '.$json['pageStats']->numberCssResources.'<br>';
	echo 'Number of JS Resources: '.$json['pageStats']->numberJsResources.'<br>';

	echo '<pre>';
	var_dump($json);
	echo '</pre>';
}