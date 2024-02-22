<?php

define("NB_POSTS_IN_PAGE", 10); // A page has 10 blog posts
define("QUOTE_SIZE", 256);

require_once("mainBackEnd.php");
//require_once("lib/ChromePhp.php");

//
$blogFolderPath = joinPaths(BASE_DIR, "/content/blog");
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'GET') {
	if (isset($_GET['lang']) && isset($_GET['page'])) {
		blogPosts($_GET['lang'], $_GET['page']);
	}
}

// take list of blog posts
function blogPosts($lang, $page) {

	global $blogFolderPath;

	$indexFirstBlog = ((int)$page -1) * NB_POSTS_IN_PAGE;
	$indexNextPage = $indexFirstBlog + NB_POSTS_IN_PAGE;
	$listFolders = ls($blogFolderPath);

	$i = 0;
	$posts = array();
	rsort($listFolders);

	//ChromePhp::info("Listing from to", $indexFirstBlog, $indexNextPage);

	foreach ($listFolders as $post) {
		if (checkPostFolder($post)) {
			$blogPostPath = joinPaths($blogFolderPath, $post);
			$blogFile = joinPaths($blogPostPath , $lang.".md");

			if (!file_exists($blogFile)) {
				$blogFile = joinPaths($blogPostPath , "vi.md"); //fallback to vi.md
			}

			$jsonBlogPost = readBlogPost($blogFile); //check status
			if ($jsonBlogPost != null) { //status ok
				if ($i >= $indexNextPage) {
					//optim: we won't check all to the last folder,
					// we only check until we met the first index of the next page
					// here we are sure that there is a next page
					break;
				}
				if ($indexFirstBlog <= $i && $i < $indexNextPage) {
					array_push($posts, $jsonBlogPost);
					//ChromePhp::info("OK", $post);
				}
				$i++;
			}
		}
	}
	//after the above foreach $i will point to the next page (in general)
	//let guess the total post is the number of folder
	$totalPosts = count($listFolders);

	//here $i can no longer go to the next page so we met the last page
	if ($i < $indexNextPage) {
		$totalPosts = $i; //we know exactly how many post are there
	}

	$result = array("page" => (int)$page);
	$result["totalpages"] = (int)((int)($totalPosts-1)/NB_POSTS_IN_PAGE) + 1;
	$result["totalposts"] = $totalPosts;
	$result["lang"] = $lang;
	$result["posts"] = $posts;

	return reponseJson($result);
}

// read blog post file
function readBlogPost($file) {
	$path_parts = pathinfo($file);

	$resPost = array();
	$path_dir = pathinfo($path_parts['dirname']);
	$dirname = $path_dir["filename"];

	$resPost["publish"] = get_date($dirname);
	$resPost["name"] = get_name($dirname);

	$postFile = fopen($file, "r") or die("Unable to open file!");

	$fileContents = fread($postFile,filesize($file));

	$posStart = strpos($fileContents, "<!--");
	$posEnd = strpos($fileContents, "-->");

	$header = substr($fileContents, ($posStart + 5), ($posEnd - $posStart - 5));

	foreach (preg_split("/\n/", $header) as $meta_data) {
		if ($meta_data != "") {
			$posSplit = strpos($meta_data, ":");
			$key = substr($meta_data, 0, $posSplit);
			$value = substr($meta_data, ($posSplit + 1));
			$resPost[strtolower(trim($key))] = convertToUtf8(trim($value)); //json_encode(): All string data must be UTF-8 encoded
		}
	}

	if (array_key_exists('status', $resPost)){
		if (strtolower($resPost["status"]) != "completed") {
			return;
		}
	}

	$resPost["lang"] = $path_parts["filename"];

	$contentsAndHeader = str_replace("\n", " ",  substr($fileContents, $posEnd + 3));
	$quote = substr(trim($contentsAndHeader), 0, QUOTE_SIZE);
	$resPost["excerpt"] = convertToUtf8(clean_quote($quote)); //json_encode(): All string data must be UTF-8 encoded

	fclose($postFile);
	return $resPost;
}

// check if post folder satisfy
function checkPostFolder($postFolder) {
	if (contains_date($postFolder)) { // check if folder name satisfy
		if (checkContent($postFolder)) {
			return true;
		}
	}
	return false;
}

// check if post folder content satisfy
function checkContent($postFolder) {
	global $blogFolderPath;
	$contents = ls(joinPaths($blogFolderPath, $postFolder));
	$viFile = false;
	foreach ($contents as $value) {
		if (strpos("vi.md", $value) !== FALSE) {
			$viFile = true;
		}
	}
	return $viFile;
}

// get date on string of folder name
function get_date($str) {
	if (preg_match('/\b(\d{4})-(\d{2})-(\d{2})\b/', $str, $matches)) {
		return $matches[0];
	}
}

// get name on string of folder name
function get_name($str) {
	return substr($str,11, strlen($str));
}

// remove markdown elements
function clean_quote($str) {
	$charsToRemove = array("#", "*", ">", "[", "]");

	foreach ($charsToRemove as $char) {
		$str = str_replace($char, "", $str);
	}

	//TODO clean image, link
	return trim($str);
}

function convertToUtf8($text) {
	return iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
}
