<?php
/* return true if the email is valid */
require_once 'config.php';

$email = @$_GET['mail'];
if (empty($email)) {
	echo('true');
}
else {
	$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
	if ($conn->connect_error) {
		echo('Base de donées indisponible');
		die(500);
	}
	$sql = "select count(*) from booking where email='".$email."' limit 1";
	$r = $conn->query($sql);
	if (!$r) {
		echo($conn->error); 
		die(500);	
	}
	$row = $r->fetch_row();
	$d = $row[0];
	echo $d==1 ? 'false' : 'true'; 
}