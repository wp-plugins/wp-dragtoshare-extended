<?php 

if (!function_exists('get_option'))
{
    require_once("../../../../wp-config.php");
}

$action = $_GET['action'];

switch($action) {
	case 'isgd':
		dtse_is_gd($_GET['longurl']);
		break;
	
	default:
	header("HTTP/1.1 404 Not Found");
}

?>