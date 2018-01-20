<?php

function register_system(){
	$singular = 'System';
	$plural = 'Systems';

	$labels = array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'add_name'              => 'Add New',
		'add_new_item'          => 'Add New '.$singular,
		'edit'                  => 'Edit',
		'edit_item'             => 'Edit '.$singular,
		'new_item'              => 'New '.$singular,
		'view'                  => 'View'.$singular,
		'view_item'             => 'View'.$singular,
		'search_item'           => 'Search'.$plural,
		'parent'                => 'Parent'.$singular,
		'not_found'             => 'No '.$plural.' found',
		'not_found_in_trash'    => 'No '.$plural.' in Trash'
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'menu_icon'           => 'dashicons-admin-generic',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => true,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		// 'capabilities' => array(),
		'rewrite'             => array(
			'slug' => 'jobs',
			'with_front' => true,
			'pages' => true,
			'feeds' => true,
		),
		'supports'            => array(
			'title',
			'editor',
			'author',
			'custom-fields',
			'thumbnail'
		)
	);
	register_post_type('system', $args);
}

add_action('init', 'register_system');


function backup(){
	global $wpdb;

	$tables = $wpdb->get_results('SHOW TABLES');

	$upload_dir = wp_upload_dir();
	$file_path = $upload_dir['basedir'] . '/backup-' . time() . '.sql';
	$file = fopen($file_path, 'w');

	foreach ($tables as $table)
	{
		$table_name = $table->Tables_in_wordpress;
		$schema = $wpdb->get_row('SHOW CREATE TABLE ' . $table_name, ARRAY_A);
		fwrite($file, $schema['Create Table'] . ';' . PHP_EOL);

		$rows = $wpdb->get_results('SELECT * FROM ' . $table_name, ARRAY_A);

		if( $rows )
		{
			fwrite($file, 'INSERT INTO ' . $table_name . ' VALUES ');

			$total_rows = count($rows);
			$counter = 1;
			foreach ($rows as $row => $fields)
			{
				$line = '';
				foreach ($fields as $key => $value)
				{
					$value = addslashes($value);
					$line .= '"' . $value . '",';
				}

				$line = '(' . rtrim($line, ',') . ')';

				if ($counter != $total_rows)
				{
					$line .= ',' . PHP_EOL;
				}

				fwrite($file, $line);

				$counter++;
			}

			fwrite($file, '; ' . PHP_EOL);
		}
	}

	fclose($file);
}

add_action('wp_ajax_backup', 'backup');