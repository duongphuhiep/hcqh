<?php

define("NB_POSTS_IN_PAGE", 10); // A page has 10 blog posts
define("QUOTE_SIZE", 200);

require_once("mainBackEnd.php");

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

	$indexFirstBlog = ((int)$page -1) * NB_POSTS_IN_PAGE + 1;
	$indexLastBlog = $indexFirstBlog + NB_POSTS_IN_PAGE -1;
	$listFolders = ls($blogFolderPath);

	$count = 0;
	$posts = array();
	rsort($listFolders);

	foreach ($listFolders as $post) {
		if (checkPostFolder($post) && ($indexFirstBlog <= $indexLastBlog)) {
			$count++;
			if ($count >= $indexFirstBlog && $count <= $indexLastBlog) {
				$blogPostPath = joinPaths($blogFolderPath, $post);
				$blogFile = joinPaths($blogPostPath , $lang.".md");

				if (!file_exists($blogFile)) {
					$blogFile = joinPaths($blogPostPath , "vi.md");
				}

				$jsonBlogPost = readBlogPost($blogFile);
				if ($jsonBlogPost != null) {
					array_push($posts, $jsonBlogPost);
				}
			}
		}
	}

	$result = array("page" => (int)$page);
	$result["totalpages"] = (int)($count/NB_POSTS_IN_PAGE + 1);
	$result["totalposts"] = $count;
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
	    	$resPost[strtolower(trim($key))] = trim($value);
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
   	$resPost["excerpt"] = clean_quote($quote);

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
