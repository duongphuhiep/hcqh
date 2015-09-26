<?php

//sleep(1);
define("BASE_DIR", "../");
define("APP_ID", "786362358731-q3s0lph8krhk90sc2bp1eujokfjbburt.apps.googleusercontent.com");

// true on Windows
define("WIN_OS", startsWith(strtolower(PHP_OS), "win"));

require_once("lib/ChromePhp.php");

/* join paths without double slashes */
function joinPaths($p1, $p2) {
	return join(DIRECTORY_SEPARATOR, array(trim($p1, DIRECTORY_SEPARATOR), trim($p2, DIRECTORY_SEPARATOR)));
}
function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

/**
 * check if the token comes from an administrator
 * @param $idtoken
 * @return bool
 */
function isAdmin($idtoken) {
	$ch = curl_init("https://www.googleapis.com/oauth2/v3/tokeninfo");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("id_token" => $idtoken)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //otherwise, the curl_exec will forward the page to googleapis.com
	$dataString = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($dataString, true);

	if ($data["aud"] == APP_ID) {
		$googleUserId = $data["sub"];
		//$email = $data["email"];

		$AUTH_USERS = array("113703431246868902879"); //add more admin users later here

		if (in_array($googleUserId, $AUTH_USERS, true)) {
			return true;
		}
	}
	header('HTTP/1.0 401 Unauthorized');
	return false;
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

/**
 * Rename file/folder and return list of sibling file/folder
 * @param $path: the folder
 * @return array
 */
function ren($parentPath, $currentName, $newName) {
	rename(joinPaths($parentPath, $currentName), joinPaths($parentPath, $newName));
	return ls($parentPath);
}

/**
 * delete a file/folder
 * @param $parentPath: string parent folder
 * @param $itemName: string file name
 * @return array: ls file/folder in the parentPath after deleting
 */
function rm($parentPath, $itemName) {
	$path = joinPaths($parentPath, $itemName);
	if (is_dir($path)) {
		$it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
			RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($path);
	}
	else {
		unlink($path);
	}

	$resu = ls($parentPath);

	//work-around for Windows: the deleted folder is still in the result list, we must to delete it in the list before return the result
	if (WIN_OS) {
		if (($key = array_search($itemName, $resu)) !== false) {
			unset($resu[$key]);
		}
		$resu = array_values($resu);
	}

	return $resu;
}

/* read request */
$requestBodyStr = file_get_contents('php://input');
$requestBody = json_decode($requestBodyStr);
if (is_null($requestBody)) {
	header('HTTP/1.1 400 Bad Request');
	return;
}

/* handle request */

$objResult = null;
$action = $requestBody->action;
if ($action=="ls") {
	$objResult = ls(BASE_DIR.$requestBody->path);
}
else if ($action=="ren") {
	if (isAdmin($requestBody->adminToken)) {
		$parentPath = BASE_DIR . $requestBody->parentPath;
		$objResult = ren($parentPath, $requestBody->currentName, $requestBody->newName);
	}
}
else if ($action=="rm") {
	if (isAdmin($requestBody->adminToken)) {
		$parentPath = BASE_DIR . $requestBody->parentPath;
		$objResult = rm($parentPath, $requestBody->currentName);
	}
}
else {
	header('HTTP/1.1 400 Bad Request');
	header('Content-Type: text/plain');
	echo "Unknow action ".$action;
}

// return result

if ($objResult != null) {
	header('Content-Type: application/json');
	echo(json_encode($objResult));
}
