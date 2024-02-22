<?php
session_start();
require_once 'securimage/securimage.php';
require_once 'config.php';
include_once 'header.php';

function process($email)
{
	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '\'') !== false) {
		return array('ok'=>false, 'msg'=>'Email invalide');
	}
	$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
	if ($conn->connect_error) {
		return array('ok'=>false, 'msg'=>'Base de donées indisponible');
	}

	//find email correspond to the cancel token
	$r = $conn->query("select id,email from booking where upper(email)=upper('".$email."')");
	if (!$r) {
		echo($conn->error);
		die(500);
	}
	if ($r->num_rows == 0) {
		return array('ok'=>false, 'msg'=>'il n\'y a pas de réservation sur cet email.');
	}

	$row = $r->fetch_row();
	$id = $row[0];
	$email = $row[1];

	$sqlquery = "select * from booking where upper(`group`)=upper('".$email."') or upper(email)=upper('".$email."')";
	$r = $conn->query($sqlquery);
	if (!$r) {
		return array('ok'=>false, 'msg'=>'Unable to '.$sqlquery.': '.$conn->error);
	}

	$leader='';
	$msg = "<table border='1'>";
	$msg .= "<tr>";
	$msg .= "<th>Name</th>";
	$msg .= "<th>Email</th>";
	$msg .= "<th>Date</th>";
	$msg .= "</tr>";

	if ($r->num_rows > 0) {
		// output data of each row
		while($row = $r->fetch_assoc()) {
			$fullname = $row["firstname"]." ".$row["lastname"];
			if (!empty($row["email"]))
				$leader = $fullname;
			$msg .=  "<tr>";
			$msg .= "<td>" . $fullname."</td>";
			$msg .= "<td>" . $row["email"]. "</td>";
			$msg .= "<td>" . $row["creation"]. "</td>";
			$msg .= "</tr>";
		}
		$msg .= "</table>";
	}

	$conn->close();
	return array('ok'=>true, 'msg' => $msg, 'id'=>$id, 'leader'=>$leader);
}


?>
<div class="container">
	<section>
		<form class='form-horizontal' onsubmit='return validateForm()' action='number2.php' method='post'>
			<div class='section'>
				<div class='form-group'>
					<label class='control-label col-sm-2' for='email'>Saisissez votre Email</label>
					<div class="col-sm-10">
						<input class="form-control" id='email' name='email' value="<?php echo @$_POST['email']?>" required=1 />
					</div>
				</div>
			</div>
			<div class='section'>
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-2">
						<img id="captcha" src="./securimage/securimage_show.php" alt="CAPTCHA Image" />
					</div>
					<label class='control-label col-sm-2' for='seatCount'>Que l'image dit? (Captcha)</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="captcha_code" size="10" maxlength="6" required />
						<a href="#" onclick="document.getElementById('captcha').src = './securimage/securimage_show.php?' + Math.random(); return false">Je ne vois pas très bien!</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<button type="submit" class="btn btn-primary center-block" style="min-width:120px">Récupérer le numéro de votre Reservation</button>
				</div>
			</div>
		</form>
	</section>
	<section style="padding:40px;background:white">
		<?php
		$email = $_POST['email'];
		if (!empty($email)) {
			$image = new Securimage();
			if ($image->check($_POST['captcha_code']) == true || DEBUG) {
				$r = process($email);
				if ($r['ok']) {
					echo "<div class='alert alert-success'><ul>";
					echo "<li><span class='lab'>N°Réservation:</span> <span class='val'>".$r['id']."</span>";
					echo "<li><span class='lab'>Initiateur:</span> <span class='val'>".$r['leader']."</span>";
					echo "</ul></div>";
					echo "Veuillez communiquer le numéro de réservation et le nom de l'initiateur aux membres de votre group:";
					echo $r['msg'];
					echo "<p /><div>";
					echo "Car à la réception, nous allons demander:";
					echo "<ul>";
					echo "<li>Quel est votre numéro de réservation? <i>Response: <span class='lab'>".$r['id']."</span></i>";
					echo "<li>ou Qui a réservé pour vous? <i>Response: <span class='lab'>".$r['leader']."</span></i>";
					echo "</ul></div>";
				}
				else {
					echo "<div class='alert alert-warning'>".$r['msg']."</div>";
				}
			}
			else {
				echo "<div class='alert alert-warning'>Mauvais saisir du Captcha, veuillez re-essayer</div>";
			}
		}
		?>
	</section>
</div>
<?php include_once 'footer.php'?>
