<?php
require_once 'config.php';

$ok = true;
$msg = '';
function process()
{
	$token = @$_GET['token'];
	
	if (empty($token)) {
		return array('ok'=>false, 'msg'=>'invalid cancellation token');
	}

	$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
	if ($conn->connect_error) {
		return array('ok'=>false, 'msg'=>'Base de donées indisponible');
	}

	//find email correspond to the cancel token
	$r = $conn->query("select email from booking where cancel_token='".$token."'");
	if (!$r) {
		echo($conn->error); 
		die(500);	
	}
	if ($r->num_rows == 0) {
		return array('ok'=>false, 'msg'=>'invalid cancellation token');	
	}
	$row = $r->fetch_row();
	$email = $row[0];

	//delete all the group reservation correspond to the mail, or the individual reservation correspond to the cancelToken
	//if the cancelToken correspond to a group creator then all the group reservation will be removed
	//if the cancelToken correspond to an individual reservation then only this reservation will be removed
	if (empty($email))
		$sqlDelete = "delete from booking where cancel_token='".$token."'";
	else
		$sqlDelete = "delete from booking where cancel_token='".$token."' or `group`='".$email."'";

	if (!$conn->query($sqlDelete)) {
		return array('ok'=>false, 'msg'=>'Unable to '.$sqlDelete.': '.$conn->error);
	}

	$conn->close();
	return array('ok'=>true);
}

$r = process();
//$r = array('ok'=>true);
//$r = array('ok'=>false, 'msg'=>'Nombre de places invalide');


include_once 'header.php';

if ($r['ok']) {?>
	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container text-center">
			<h1>La réservation a été annulée</h1>
			<h2>Vous pouvez refaire <a href='.' class="retry-link">une autre reservation ici</a></h2>
		</div>
	</div>
<?php
} else {?>
	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container text-center">
			<h1>Echoué</h1>
			<?php echo $r['msg']; ?>
		</div>
	</div>

<?php
}
include_once 'footer.php'; ?>
