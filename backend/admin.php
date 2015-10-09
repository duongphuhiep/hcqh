<?php

//sleep(1);
define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]); //gulp will replace it with define("ROOT_DIR", "../../"); or define("ROOT_DIR", "../");
define("BASE_DIR", "../");
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
			return $data;
		}
	}
	unauthorized('User '.$googleUserId.' ('.$data['email'].') is not administartor');
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
		unauthorized($parentPath.' is out of the "content/" folder');
	}
	$newFile = joinPaths($parentPath, $newName);
	if (!file_exists($newFile)) {
		$currentFile = joinPaths($parentPath, $currentName);
		if (!rename($currentFile, $newFile)) {
			internalError('Failed to rename from "'.$currentFile.'" to "'.$newFile.'"');
		}
	}
	else {
		conflict('Failed to rename "'.$currentName.'", the new name "'.$newName.'" is already used');
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
		unauthorized($parentPath . ' is out of the "content/" folder');
	}
	$path = joinPaths($parentPath, $itemName);
	if (is_dir($path)) {
		$iterator = new RecursiveDirectoryIterator($path);
		foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file)
		{
			if ($file->isDir()) {
				rmdir($file->getPathname());
			} else {
				unlink($file->getPathname());
			}
		}
		rmdir($path);
	} else {
		if (!unlink($path)) {
			internalError('Failed to remove the file "' . $path . '"');
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

///**
// * create a new text file with a initial content and return list of the siblings files
// * @param $parentPath: String - parent folder
// * @param $fileName: String - name of the new file to create
// * @param $content: String - initial content
// * @return array of files in the parent folder
// */
//function create($parentPath, $fileName, $content) {
//	if (!strpos($parentPath, 'content')) {
//		unauthorized($parentPath.' is out of the "content/" folder');
//	}
//	$newFile = joinPaths($parentPath, $fileName);
//	if (!file_exists($newFile)) {
//		if (!file_put_contents($newFile, $content)) {
//			internalError('Failed to create new file "'.$newFile.'"');
//		}
//	}
//	else {
//		conflict('Failed to create "'.$newFile.'", it already exist');
//	}
//	return ls($parentPath);
//}

/**
 * copy a file to a new file in a $parentFolder
 * @param $parentPath: String - parent folder
 * @param $srcFileName: String - name of the source file
 * @param $destFileName: String - name of the new file
 * @return array of files in the parent folder
 */
function cp($parentPath, $srcFileName, $destFileName) {
	if (!strpos($parentPath, 'content')) {
		unauthorized($parentPath.' is out of the "content/" folder');
	}
	$srcFile = joinPaths($parentPath, $srcFileName);
	$destFile = joinPaths($parentPath, $destFileName);
	if (!file_exists($destFile)) {
		if (!copy($srcFile, $destFile)) {
			internalError('Failed to copy from "'.$srcFile.'" to "'.$destFile.'"');
		}
	}
	else {
		conflict('Failed to create "'.$destFile.'", it already exist');
	}
	return ls($parentPath);
}

/**
 * Create a new post, and put some default content
 * @param $blogFolder
 * @param $postName
 * @param $userData
 * @return array
 */
function newPost($blogFolder, $postName, $userData) {
	$postId = date('Y-m-d').' '.$postName;
	$initialContent = "<!--"
		."\ntitle: ".$postName
		."\nauthor: ".$userData['name']
		."\nstatus: draft"
		."\n-->\n\n";

	$postFolder = joinPaths($blogFolder, $postId);
	if (!mkdir($postFolder)) {
		internalError('Failed to create folder "'.$postFolder.'"');
	}

	$newFile = joinPaths($postFolder, 'vi.md');
	if (!file_exists($newFile)) {
		if (!file_put_contents($newFile, $initialContent)) {
			internalError('Failed to create new file "'.$newFile.'"');
		}
	}
	else {
		conflict('Failed to create "'.$newFile.'", it already exist');
	}

	return array(
		'newPostId' => $postId,
		'allPostIds' => ls($blogFolder)
	);
}


/**
 * write the text content to a file, then re-open the file, read the content and return it
 * @param $path
 * @param $content
 */
function save($path, $content) {
	if (!strpos($path, 'content')) {
		unauthorized($path.' is out of the "content/" folder');
	}
	if (!file_put_contents($path, $content)) {
		internalError('Failed to save file "'.$path.'"');
	}
	return file_get_contents($path);
}

function internalError($message) {
	exitWithError('HTTP/1.1 500 Internal Server Error', $message);
}
function badRequest($message) {
	exitWithError('HTTP/1.1 400 Bad Request', $message);
}
function unauthorized($message) {
	exitWithError('HTTP/1.0 401 Unauthorized', $message);
}
function conflict($message) {
	exitWithError('HTTP/1.1 409 Conflict', $message);
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
			$targetFolder = joinPaths(ROOT_DIR, $_POST['targetServerFolder']);
			//ChromePhp::info("upload to handle", $_POST['targetServerFolder'], $_FILES);

			foreach($_FILES as $key => $file) {
				$target_file = joinPaths($targetFolder, $file["name"]);

				if (file_exists($target_file)) {
					conflict("File has already exists on server ".$target_file); return;
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
		$requestBody = json_decode($requestBodyStr);
		if (!isset($requestBody)) {
			badRequest("Invalid request body ".$requestBodyStr);
		}
		/* handle request */
		$action = $requestBody->action;
		if ($action == "ls") {
			$objResult = ls(joinPaths(ROOT_DIR, $requestBody->path));
			reponseJson($objResult);
		} else if ($action == "ren") {
			if (isAdmin($requestBody->adminToken)) {
				$parentPath = joinPaths(ROOT_DIR, $requestBody->parentPath);
				$objResult = ren($parentPath, $requestBody->currentName, $requestBody->newName);
				reponseJson($objResult);
			}
		} else if ($action == "rm") {
			if (isAdmin($requestBody->adminToken)) {
				$parentPath = joinPaths(ROOT_DIR, $requestBody->parentPath);
				$objResult = rm($parentPath, $requestBody->currentName);
				reponseJson($objResult);
			}
		} else if ($action == "cp") {
			if (isAdmin($requestBody->adminToken)) {
				$parentPath = joinPaths(ROOT_DIR, $requestBody->parentPath);
				$objResult =  cp($parentPath, $requestBody->srcFileName, $requestBody->destFileName);
				reponseJson($objResult);
			}
		}
		else if ($action == "newpost") {
			$userData = isAdmin($requestBody->adminToken);
			if ($userData) {
				$blogFolder = joinPaths(BASE_DIR, '/content/blog/');
				$objResult =  newPost($blogFolder, $requestBody->postName, $userData);
				reponseJson($objResult);
			}
		}
		else if ($action == "save") {
			if (isAdmin($requestBody->adminToken)) {
				$filePath = joinPaths(ROOT_DIR, $requestBody->filePath);
				echo save($filePath, $requestBody->newContent);
			}
		}
		else {
			badRequest("Invalid action ".$action);
		}
	}
}
else {
	badRequest("Invalid request method ".$requestMethod);
}
