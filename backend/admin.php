<?php

sleep(1);
define("BASE_DIR", "../");

require_once("lib/ChromePhp.php");

function checkLogin($idtoken) {
	$ch = curl_init("https://www.googleapis.com/oauth2/v3/tokeninfo");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("id_token" => $idtoken)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //otherwise, the curl_exec will forward the page to googleapis.com
	$dataString = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($dataString, true);
	//var_dump($data[array("aud")]);
	if ($data["aud"] == "786362358731-q3s0lph8krhk90sc2bp1eujokfjbburt.apps.googleusercontent.com") {
		return $data;
	}
	return null;
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

/* read request */
$requestBodyStr = file_get_contents('php://input');
$requestBody = json_decode($requestBodyStr);
if (is_null($requestBody)) {
	header('HTTP/1.1 400 Bad Request');
	return;
}

/* handle request */

$action = $requestBody->action;
if ($action=="ls") {
	$path = $requestBody->path;
	$result = ls(BASE_DIR.$path);
	header('Content-Type: application/json');
	echo(json_encode($result));
}
