<?php
session_start();
require_once 'securimage/securimage.php';

include_once 'header.php';
require_once 'config.php';

$ok = true;
$msg = '';
function process()
{
	$image = new Securimage();
	if ($image->check($_POST['captcha_code']) == true || DEBUG) {
		$seatCount = $_POST['seatCount'];
		if ($seatCount < 0 || $seatCount > 4) {
			return array('ok'=>false, 'msg'=>'Nombre de places invalide');
		}

		$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
		if ($conn->connect_error) {
			return array('ok'=>false, 'msg'=>'Base de donées indisponible');
		}
		$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

		$firstEmail = $_POST['email1'];
		if (empty($firstEmail)) {
			return array('ok'=>false, 'msg'=>'Email invalide');
		}
		$group = $seatCount>1 ? $firstEmail : null;

		for ($i=1; $i<=$seatCount; $i++) {
			$firstName = $_POST['firstName'.$i];
			$lastName = $_POST['lastName'.$i];
			$email = $_POST['email'.$i];
			$cancelToken = getGUID();

			$sql = "INSERT INTO booking(`group`, firstname, lastname, email, cancel_token, status, creation)
						VALUES ('".$group."', '".$firstName."', '".$lastName."', '".$email."', '".$cancelToken."', 0, '".date("Y-m-d H:i:s")."')";

			if ($conn->query($sql)) {
				return array('ok'=>true);
			} else {
				//return array('ok'=>false, 'msg'=>'Unable to insert "'.$firstName.' '.$lastName.'" : '.$conn->error);
				return array('ok'=>false, 'msg'=>'Unable to '.$sql.': '.$conn->error);
			}
		}

		$conn->commit();
		$conn->close();
	} else {
		return array('ok'=>false, 'msg'=>'L\'image CAPTCHA n\'est pas correct');
	}
}

$r = process();

if ($r['ok']) {?>

	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container"><h1>Merci</h1>
			<h2>Veuillez bien noté la date 3/12/2016 17h00. Salle Neuilly</h2>
			Vous pouvez peut-être recevoir une email de confirmation dans la répertoire Spam
		</div>
	</div>

<?php
} else {?>

	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container"><h1>Echoué</h1>
			<h3><a href="./">Ré-essayez</a></h3>
			<?php echo $r['msg']; ?>
		</div>
	</div>

<?php
}
include_once 'footer.php'; ?>
