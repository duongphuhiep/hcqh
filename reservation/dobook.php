<?php
session_start();
require_once 'securimage/securimage.php';

// Code Validation

$image = new Securimage();
if ($image->check($_POST['captcha_code']) == true) {
	echo "Correct!";
} else {
	echo "Sorry, wrong code.";
}


function getUserIP() {
	$client = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote = $_SERVER['REMOTE_ADDR'];

	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}
	return $ip;
}

$requestBodyStr = file_get_contents('php://input');
$info = array("REMOTE_ADDR" => @$_SERVER['REMOTE_ADDR'], "IP" => getUserIP(), "AUTH_USER" => @$_SERVER['PHP_AUTH_USER'], "AUTH_PW" => @$_SERVER['PHP_AUTH_PW'], "GET" => $_GET, "POST" => $_POST, "PostBody" => $requestBodyStr);

echo "<pre>";
print_r($info);
echo "</pre>";
