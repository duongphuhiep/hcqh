<?php

sleep(1);
define("BASE_DIR", "../../");
define("APP_ID", "786362358731-q3s0lph8krhk90sc2bp1eujokfjbburt.apps.googleusercontent.com");

// true on Windows
define("WIN_OS", startsWith(strtolower(PHP_OS), "win"));

require_once("lib/ChromePhp.php");

/* join paths without double slashes */
function joinPaths($p1, $p2) {
	//might use DIRECTORY_SEPARATOR instead of '/'
	return join('/', array(trim($p1, '/'), trim($p2, '/')));
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
	unauthorize('User '.$googleUserId.' ('.$data['email'].') is not administartor');
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
	if (!strpos($parentPath, 'content')) {
		unauthorize($parentPath.' is out of the "content" folder');
	}
	$newFile = joinPaths($parentPath, $newName);
	if (!file_exists($newFile)) {
		$currentFile = joinPaths($parentPath, $currentName);
		if (!rename($currentFile, $newFile)) {
			internalError('Failed to rename from "'.$currentFile.'" to "'.$newFile.'"');
		}
	}
	else {
		exitWithError('HTTP/1.1 409 Conflict', 'Failed to rename "'.$currentName.'", the new name "'.$newName.'" is already used');
	}
	return ls($parentPath);
}

/**
 * delete a file/folder
 * @param $parentPath: string parent folder
 * @param $itemName: string file name
 * @return array: ls file/folder in the parentPath after deleting
 */
function rm($parentPath, $itemName) {
	if (!strpos($parentPath, 'content')) {
		unauthorize($parentPath.' is out of the "content" folder');
	}
	$path = joinPaths($parentPath, $itemName);
	if (is_dir($path)) {
		$it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
			RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($files as $file) {
			if ($file->isDir()) {
				if (!rmdir($file->getRealPath())) {
					internalError('Failed to remove the directory "'.$file->getRealPath().'"');
				}
			} else {
				if (!unlink($file->getRealPath())) {
					internalError('Failed to remove the file "'.$file->getRealPath().'"');
				}
			}
		}
		if (!rmdir($path)) {
			internalError('Failed to remove the directory "'.$path.'"');
		}
	}
	else {
		if (!unlink($path)) {
			internalError('Failed to remove the file "'.$path.'"');
		}
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

function internalError($message) {
	exitWithError('HTTP/1.1 500 Internal Server Error', $message);
}
function badRequest($message) {
	exitWithError('HTTP/1.1 400 Bad Request', $message);
}
function unauthorize($message) {
	exitWithError('HTTP/1.0 401 Unauthorized', $message);
}
function exitWithError($header, $message) {
	header($header);
	if (isset($message)) {
		header('Content-Type: text/plain');
		echo $message;
	}
	exit;
}
function reponseJson($obj) {
	if (isset($obj)) {
		header('Content-Type: application/json');
		echo(json_encode($obj));
		exit;
	}
}


$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'POST') {

	/* Check if the request is coming from FormData */
	if (isset($_POST['adminToken'])) {
		//Yes the request is coming from a Form

		//so client is uploading files
		if (isAdmin($_POST['adminToken'])) {
			$targetFolder = joinPaths(BASE_DIR, $_POST['targetServerFolder']);
			//ChromePhp::info("upload to handle", $_POST['targetServerFolder'], $_FILES);

			foreach($_FILES as $key => $file) {
				$target_file = joinPaths($targetFolder, $file["name"]);

				if (file_exists($target_file)) {
					badRequest("File has already exists on server ".$target_file); return;
				}
				else {
					if(!move_uploaded_file($file["tmp_name"], $target_file)) {
						internalError('Failed to upload "'.$file["name"].'"');
					}
				}
			}

			$objResult = ls($targetFolder);
			reponseJson($objResult);
		}
	}
	else {
		//No, the request is coming from a XMLHttpRequest (iron-ajax)

		/* read request body */
		$requestBodyStr = file_get_contents('php://input');

		if (!empty($requestBodyStr)) {
			$requestBody = json_decode($requestBodyStr);
			if (!isset($requestBody)) {
				badRequest("Invalid request body ".$requestBodyStr);
			}
			/* handle request */
			$action = $requestBody->action;
			if ($action == "ls") {
				$objResult = ls(joinPaths(BASE_DIR, $requestBody->path));
				reponseJson($objResult);
			} else if ($action == "ren") {
				if (isAdmin($requestBody->adminToken)) {
					$parentPath = joinPaths(BASE_DIR, $requestBody->parentPath);
					$objResult = ren($parentPath, $requestBody->currentName, $requestBody->newName);
					reponseJson($objResult);
				}
			} else if ($action == "rm") {
				if (isAdmin($requestBody->adminToken)) {
					$parentPath = joinPaths(BASE_DIR, $requestBody->parentPath);
					$objResult = rm($parentPath, $requestBody->currentName);
					reponseJson($objResult);
				}
			}
			else {
				badRequest("Invalid action ".$action);
			}
		}
	}
}
else {
	badRequest("Invalid request method ".$requestMethod);
}
