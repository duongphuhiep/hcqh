
<?php include_once 'header.php'?>

</div><div class="jumbotron" style="background:darkslateblue;color:whitesmoke">
	<div class="container"><h1>Notre plus grand concert de l'année</h1>
		<h2>Samedi, 3 Decembre 2016, 20h-22h. Espace Reuilly, 21 rue Hénard, 75012 Paris</h2>
		<ul>
			<li>Entrée sur réservation, participation libre aux frais
			<li>Les plus belles œuvres vietnamiennes pour chœur et orchestre symphonique seront présentées.
		</ul>
	</div>
</div>
<?php
require_once 'config.php';
$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
if ($conn->connect_error) {
	return array('ok'=>false, 'msg'=>'Base de donées indisponible');
}

//check number of reservation should not exceed 450 places
$r = $conn->query('select count(*) from booking');
if (!$r) {
	echo($conn->error);
	die(500);
}
$row = $r->fetch_row();
$count = $row[0];
if ($count >= 200) {
	?>
	<div class="container text-center">
		<div class="alert alert-info">
			Malheureusement, Il n'y a plus de places disponibles :(
		</div>
		Si on peut s'arranger plus de places (par example quelqu'un s'annule sa réservation), alors le siteweb va re-ouvrit à nouveau.
	</div>
	<?php
}
else {
	?>
	<div class="container">
		<div class="alert alert-warning">
			VITE! Nombre de places restés: <?php echo (NBPLACES - $count); ?>
		</div>
		<section class="main-form">
			<reservation-form></reservation-form>
		</section>
	</div>
	<?php
}
?>
<?php include_once 'footer.php'?>
