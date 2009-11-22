<?php 

function isValidURL($url)
{
 return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

$longUrl = $_GET['longurl'];

//Curl is available ?
if(function_exists("curl_init")) {

	//content provided is a valid URL ?
	if((!empty($longUrl)) && (is_string($longUrl)) && (isValidURL($longUrl))){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://is.gd/api.php?longurl='.$longUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		
		// Result provided is a valid URL ?
		if((!empty($result)) && (is_string($result)) && (isValidURL($result))) {
			echo $result;
		}
		
	} else {
		header("HTTP/1.1 404 Not Found");
	}

}

?>