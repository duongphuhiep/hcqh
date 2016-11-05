<?php
require_once 'config.php';

$ok = true;
$msg = '';
function process()
{
	$token = @$_GET['token'];
	$email = @$_GET['mail'];

	if (empty($token)) {
		return array('ok'=>false, 'msg'=>'invalide cancellation token');
	}

	$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
	if ($conn->connect_error) {
		return array('ok'=>false, 'msg'=>'Base de donées indisponible');
	}

	$sql = "delete from booking where cancel_token='".$token."'";

	if (!$conn->query($sql)) {
		return array('ok'=>false, 'msg'=>'Unable to '.$sql.': '.$conn->error);
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
		<div class="container text-center"><h1>Echoué</h1>
			<?php echo $r['msg']; ?>
		</div>
	</div>

<?php
}
include_once 'footer.php'; ?>
