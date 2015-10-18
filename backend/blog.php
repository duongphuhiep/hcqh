<?php
require_once("lib/mainBackEnd.php");

// 
$blogPath = joinPaths(BASE_DIR, "/content/blog");
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'GET') {
	if (isset($_GET['lang']) && isset($_GET['page'])) {
		blogPosts($_GET['lang'], $_GET['page']);
	}
}

// take list of blog posts
function blogPosts($lang, $page) {
	global $blogPath;
	$listFolders = ls($blogPath);
	$listBlogPosts = each($listFolders);

	$listBlogPosts = array();
	foreach ($listFolders as $value) {
		if (checkPostFolder($value)) {
			array_push($listBlogPosts, $value);
		}
	}
	//TODO 
	return reponseJson($listBlogPosts);
}

// check if post folder satisfy
function checkPostFolder($postFolder) {
	if (checkName($postFolder)) {
		if (checkContent($postFolder)) {
			return true;
		}
	}
	return false;
}

// check if post folder content satisfy
function checkContent($postFolder) {
	global $blogPath;
	$contents = ls(joinPaths($blogPath, $postFolder));
	$exist = false;
	foreach ($contents as $value) {
		if (strpos("vi.md", $value) !== FALSE) {
        	$exist = true;
    	}
	}
	return $exist;
}

// check if folder name satisfy
function checkName($postFolder) {
	if (contains_date($postFolder)) {
		if (preg_match('/[A-Z]+[a-z]+/', $postFolder))
		{
			return true;
		}
	}
	return false;
}

// Check if a string contains date (yyyy-mm-dd)
function contains_date($str)
{
    if (preg_match('/\b(\d{4})-(\d{2})-(\d{2})\b/', $str, $matches))
    {
        if (checkdate($matches[2], $matches[3], $matches[1]))
        {
            return true;
        }
    }
    return false;
}