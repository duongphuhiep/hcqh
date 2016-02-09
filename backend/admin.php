<?php

require_once("lib/ChromePhp.php");
require_once("mainBackEnd.php");

/**
 * http://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning
 * turn every warning notice to error
 */
function myErrorHandler($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("myErrorHandler", E_ALL);

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

		//add/remove admin users here
		$AUTH_USERS = array(
			"113703431246868902879", //Phu Hiep
			"107145814703476265092", //Tich Ky
			"114101028161312088899", //Ngan Ha
			"116683251432505969319", //Quynh Nga
			"109388954927603052779", //Pham Dat
			"117475364919742721893", //Dau Xuan Tuan
			"110829167372218983395", //Tran Manh Ha
			"116778336854527196022", //chi Nguyen Hong Ha (chau co Ngan Ha)
			"104910698954701811046" //Hop Ca Que Huong
		);

		if (in_array($googleUserId, $AUTH_USERS, true)) {
			return $data;
		}
	}
	unauthorized('User '.$googleUserId.' ('.$data['email'].') is not administartor');
	return false;
}

/**
 * check if the parentPath is inside the /content folder otherwise die gracefully
 * eg:
 * "ect/content/foo" -> ok
 * "/content/foo" -> ok
 * "ect/content/" -> die
 *
 * @param $parentPath
 */
function checkAuthorizedPath($parentPath) {
	if (preg_match("/.*\/content\/.+/i", $parentPath) !== 1) {
		unauthorized($parentPath . ' is out of the "content/" folder');
	}
}

/**
 * Rename file/folder and return list of sibling file/folder
 * @param $path: the folder
 * @return array
 */
function ren($parentPath, $currentName, $newName) {
	try {
		checkAuthorizedPath($parentPath);
		if (IsNullOrEmptyString($currentName)) {
			badRequest("Cannot rename " . $parentPath . " because of empty currentName");
		}
		if (IsNullOrEmptyString($newName)) {
			badRequest("Cannot rename " . $parentPath . " because of empty newName");
		}

		$newFile = joinPaths($parentPath, $newName);
		if (!file_exists($newFile)) {
			$currentFile = joinPaths($parentPath, $currentName);
			if (!rename($currentFile, $newFile)) {
				internalError('Failed to rename from "' . $currentFile . '" to "' . $newFile . '"');
			}
		} else {
			conflict('Failed to rename "' . $currentName . '", the new name "' . $newName . '" is already used');
		}
		return ls($parentPath);
	}
	catch (Exception $e) {
		internalError($e);
	}
}

/**
 * remove recursively a folder
 */
function delTree($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
}

/**
 * delete a file/folder
 * @param $parentPath: string parent folder
 * @param $itemName: string file name
 * @return array: ls file/folder in the parentPath after deleting
 */
function rm($parentPath, $itemName) {
	try {
		checkAuthorizedPath($parentPath);
		if (IsNullOrEmptyString($itemName)) {
			badRequest("Cannot remove " . $parentPath . " itemName must not empty");
		}

		$path = joinPaths($parentPath, $itemName);
		if (is_dir($path)) {
			delTree($path);
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
	catch (Exception $e) {
		internalError($e);
	}
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
	try {
		checkAuthorizedPath($parentPath);
		if (IsNullOrEmptyString($srcFileName)) {
			badRequest("Cannot copy " . $parentPath . " because of empty srcFileName");
		}
		if (IsNullOrEmptyString($destFileName)) {
			badRequest("Cannot copy " . $parentPath . " because of empty destFileName");
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
	catch (Exception $e) {
		internalError($e);
	}
}

/**
 * Create a new post, and put some default content
 * @param $blogFolder
 * @param $postName
 * @param $userData
 * @return array
 */
function newPost($blogFolder, $postName, $userData) {
	try {
		$postId = $postName;
		if (!contains_date($postName)) {
			$postId = date('Y-m-d') . ' ' . $postName;
		}

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
	catch (Exception $e) {
		internalError($e);
	}
}

/**
 * inject other bootstrap theme to index.html
 * @param $themeUrl
 */
function setTheme($themeUrl) {
	$pathToIndexHtml = joinPaths(BASE_DIR, 'index.html');
	$content = file_get_contents($pathToIndexHtml);
	$pattern = '/(.+)(https:\/\/maxcdn.bootstrapcdn.com\/.+\/bootstrap.min.css)(.+)/i';
	$replacement = '${1}'.$themeUrl.'${3}';
	$content = preg_replace($pattern, $replacement, $content, 1);
	if (!is_null($content)) {
		file_put_contents($pathToIndexHtml, $content);
	}
}

/**
 * inject inverseNavBar to main.js
 * @param $inverseNavBar
 */
function setInverseNavBar($inverseNavBar) {
	$pathToMainJs = joinPaths(BASE_DIR, '_dist/main.js');
	$content = file_get_contents($pathToMainJs);
	$replacement = 'navbar-inverse:'.($inverseNavBar ? 'true': 'false');
	$content = str_replace('navbar-inverse:true', $replacement, $content);
	$content = str_replace('navbar-inverse:false', $replacement, $content);
	if (!is_null($content)) {
		file_put_contents($pathToMainJs, $content);
	}
}

/**
 * inject other bootstrap theme to index.html
 * @param $themeUrl
 * @param $inverseNavBar
 */
function applyTheme($themeUrl, $inverseNavBar) {
	try {
		setTheme($themeUrl);
		setInverseNavBar($inverseNavBar);
	}
	catch (Exception $e) {
		internalError($e);
	}
}

/**
 * write the text content to a file, then re-open the file, read the content and return it
 * @param $path
 * @param $content
 */
function save($path, $content) {
	try {
		checkAuthorizedPath($path);
		if (!file_put_contents($path, $content)) {
			internalError('Failed to save file "' . $path . '"');
		}
		return file_get_contents($path);
	}
	catch (Exception $e) {
		internalError($e);
	}
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
		else if ($action == "settheme") {
			if (isAdmin($requestBody->adminToken)) {
				applyTheme($requestBody->themeUrl, $requestBody->inverseNavBar);
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
