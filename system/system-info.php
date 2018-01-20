<?php
/**
 * Created by PhpStorm.
 * User: DarkShadow
 * Date: 20-01-2018
 * Time: 22:50
 */

$rustart = getrusage();
$user_agent = $_SERVER['HTTP_USER_AGENT'];

function add_system_info() {

	add_submenu_page(
		'edit.php?post_type=system',
		__( 'System Info' ),
		__( 'System Info' ),
		'manage_options',
		'system_info',
		'system_info_callback'
	);

}
add_action( 'admin_menu', 'add_system_info' );

function parse_phpinfo() {
	ob_start(); phpinfo(INFO_MODULES); $s = ob_get_contents(); ob_end_clean();
	$s = strip_tags($s, '<h2><th><td>');
	$s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
	$s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
	$t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
	$r = array(); $count = count($t);
	$p1 = '<info>([^<]+)<\/info>';
	$p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
	$p3 = '/'.$p1.'\s*'.$p1.'/';
	for ($i = 1; $i < $count; $i++) {
		if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
			$name = trim($matchs[1]);
			$vals = explode("\n", $t[$i + 1]);
			foreach ($vals AS $val) {
				if (preg_match($p2, $val, $matchs)) { // 3cols
					$r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
				} elseif (preg_match($p3, $val, $matchs)) { // 2cols
					$r[$name][trim($matchs[1])] = trim($matchs[2]);
				}
			}
		}
	}
	return $r;
}

function getOS() {

	global $user_agent;

	$os_platform    =   "Unknown OS Platform";

	$os_array       =   array(
		'/windows nt 10/i'     =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);

	foreach ($os_array as $regex => $value) {

		if (preg_match($regex, $user_agent)) {
			$os_platform    =   $value;
		}

	}

	return $os_platform;

}

function convert($size)
{
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}


function system_info_callback(){
	global $wp_version;
	echo $_SERVER['SERVER_ADDR'].'<br>';
	echo $_SERVER['HTTP_HOST'].'<br>';
	$out = array();
	exec("wmic cpu get DataWidth", $out);
	$bits = strstr(implode("", $out), "64") ? 64 : 32;
	echo $bits.'<br>';
	echo date_default_timezone_get().'<br>';
	$info = parse_phpinfo();
	echo $info['Core']['PHP Version'].'<br>';
	echo $info['mysqli']['Client API library version'].'<br>';
	$conn = mysqli_connect('127.0.0.1', 'root', 'root');
	if($conn){
		echo 'Access granted<br>';
	}else{
		echo 'Access denied<br>';
	}
	echo $_SERVER["SERVER_SOFTWARE"].'<br>';
	echo $_SERVER["DOCUMENT_ROOT"].'<br>';
	echo $info["max_execution_time"][0].'<br>';



	function rutime($ru, $rus, $index) {
		return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
		       -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
	}

	$ru = getrusage();
	echo "This process used " . rutime($ru, $rustart, "utime") .
	     " ms for its computations <br>";
	echo "It spent " . rutime($ru, $rustart, "stime") .
	     " ms in system calls <br>";

	echo $info['Core']['memory_limit'][0].'<br>';
	echo $info['Core']['sql.safe_mode'][0].'<br>';
	echo $info['openssl']['OpenSSL support'].'<br>';
	echo $info['bz2']['Stream Filter support'].'<br>';
	echo $info['curl']['cURL support'].'<br>';
	echo $info['Core']['post_max_size'][0].'<br>';
	echo $info['Core']['upload_max_filesize'][0].'<br>';
	echo memory_get_usage(true).'<br>';
	echo $wp_version.'<br>';
	echo getOS().'<br>';
	echo convert(memory_get_usage(true));


	echo '<pre>';
	var_dump($info);
	echo '</pre>';
}