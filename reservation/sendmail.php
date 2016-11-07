<?php
require_once 'config.php';

$token = @$_GET['token'];

$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
$r = $conn->query("select email from booking where cancel_token='".$token."'");
$row = $r->fetch_row();	
//var_dump($row)

echo "<pre>";

print_r($row);
//echo($r->num_rows);

echo "</pre>";

$conn->close();

/*
$to      = 'duongphuhiep@gmail.com';
$subject = 'Confirm reservation success html';

$msg = '<htm><body><h1>hello, you made a reservation</h1>Fécilitation <a href="http://hopcaquehuong.org">Dương Phú Hiệp</a></body></htm>';

//$headers = 'From: no-reply@hopcaquehuong.org' . "\r\n"+ 'Reply-To: no-reply@hopcaquehuong.org'.
$headers = 'From: no-reply@hopcaquehuong.org'
	.'\r\nReply-To: no-reply@hopcaquehuong.org'
	.'\r\nMIME-Version: 1.0'
	.'\r\nContent-Type: text/html; charset=utf8'
;


mail($to, $subject, $msg, $headers);
echo "Email sent html3";
*/
