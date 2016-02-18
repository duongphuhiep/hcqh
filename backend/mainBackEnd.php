<?php

//sleep(1);
define("ROOT_DIR", "../../"); //gulp will replace it with define("ROOT_DIR", "../../"); or define("ROOT_DIR", "../");
define("BASE_DIR", "../");
define("APP_ID", "786362358731-q3s0lph8krhk90sc2bp1eujokfjbburt.apps.googleusercontent.com");

// true on Windows
define("WIN_OS", startsWith(strtolower(PHP_OS), "win"));

function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function IsNullOrEmptyString($s){
	return (!isset($s) || trim($s)==='');
}

/* join paths without double slashes */
function joinPaths($p1, $p2) {
	//might use DIRECTORY_SEPARATOR instead of '/'
	return join('/', array(trim($p1, '/'), trim($p2, '/')));
}

// Check if a string contains date (yyyy-mm-dd)
// Check if a string contains date (yyyy-mm-dd)
function contains_date($str) {
	if (preg_match("/(\\d{4})-(\\d{2})-(\\d{2}) [^:\\\\\\/*?<>|\\[\\]]*/", $str, $matches)) {
		if (checkdate($matches[2], $matches[3], $matches[1])) {
			return true;
		}
	}
	return false;
}

function reponseJson($obj) {
	if (isset($obj)) {
		header('Content-Type: application/json');
		echo(json_encode($obj));
		exit;
	}
}

/**
 * List all children of the folder
 * @param $path: the folder
 * @return array
 */
function ls($path) {
	$all = scandir($path);
	return array_slice($all, 2); //remove the "." and ".." folder
}

?>
