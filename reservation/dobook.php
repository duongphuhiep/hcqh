<?php
session_start();
require_once 'securimage/securimage.php';
require_once 'config.php';

function process()
{
	$image = new Securimage();
	if ($image->check($_POST['captcha_code']) == true || DEBUG) {
		$seatCount = $_POST['seatCount'];
		if ($seatCount < 0 || $seatCount > 4) {
			return array('ok'=>false, 'msg'=>'Nombre de places invalide');
		}

		$firstEmail = $_POST['email1'];
		if (empty($firstEmail)) {
			return array('ok'=>false, 'msg'=>'Email invalide');
		}

		$group = $seatCount>1 ? $firstEmail : null;

		$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
		if ($conn->connect_error) {
			return array('ok'=>false, 'msg'=>'Base de donées indisponible');
		}
		$conn->query('START TRANSACTION');

		$mailData = array();
		for ($i=1; $i<=$seatCount; $i++) {
			$firstName = $_POST['firstName'.$i];
			$lastName = $_POST['lastName'.$i];
			$email = $_POST['email'.$i];
			$cancelToken = getGUID();

			$sql = "INSERT INTO booking(`group`, firstname, lastname, email, cancel_token, status, creation)
						VALUES ('".$group."', '".$firstName."', '".$lastName."', '".$email."', '".$cancelToken."', 0, '".date("Y-m-d H:i:s")."')";
			if (!empty($email))
				$mailData[$i] = array('email' => $email, 'firstname'=>$firstName, 'lastname'=>$lastName, 'cancelToken' => $cancelToken); 

			if (!$conn->query($sql)) {
				$serr = $conn->error;
				$conn->rollback();
				return array('ok'=>false, 'msg'=>'Unable to register "'.$firstName.' '.$lastName.'": '.$serr);
			} 
		}

		$conn->commit();
		$conn->close();

		foreach ($mailData as $md) {
			//var_dump($md);
		    mail($md['email'], 'Reservation confirmation',  "Bonjour ".$md['firstname']." ".$md['lastname'].",\nMerci d'avoir nous supporter.\n\nVous pouvez s'annuler la reservation a tout moment en cliquant sur ce lien: \nhttp://hopcaquehuong.org/reservation/cancel.php?token=".$md['cancelToken']);
		}

		return array('ok'=>true);
	} else {
		return array('ok'=>false, 'msg'=>'L\'image CAPTCHA n\'est pas correct');
	}
}

$r = process();
//$r = array('ok'=>true);
//$r = array('ok'=>false, 'msg'=>'Nombre de places invalide');


include_once 'header.php';

if ($r['ok']) {?>

	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container text-center">
			<h1>Merci</h1>
			<h2>Veuillez bien noté la date 3/12/2016 17h00. Salle Neuilly</h2>		
		</div>
	</div>
	<div>
		<ul>
			<li>We may transfer your reserved seat to another person if you are 15 minutes late.
		</ul>
	</div>
<?php
} else {?>

	<div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
		<div class="container text-center"><h1>Echoué</h1>
			<h3><a class="retry-link" href="./">Ré-essayez</a></h3>
			<?php echo $r['msg']; ?>
		</div>
	</div>

<?php
}
include_once 'footer.php'; ?>
