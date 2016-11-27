<?php
require_once "secret.php";

$admintoken = @$_GET['admintoken'];
if ($admintoken != ADMINTOKEN) {
	echo('Invalid token');
	die(403);
}

$conn = new mysqli(DBHOST, DBLOGIN, DBPASS, DBNAME);
if ($conn->connect_error) {
	echo('Base de donées indisponible');
	die(500);
}
$sql = "select * from booking";
$r = $conn->query($sql);
if (!$r) {
	echo($conn->error);
	die(500);
}


include_once 'header.php'?>

<div class="container">
	<section><?php
		echo "<div class='alert alert-info'>".$r->num_rows." places are reserved</div>";
		if ($r->num_rows > 0) {
			echo "<table border='1'>";
			echo "<tr>";
			echo "<th>id</th>";
			echo "<th>group</th>";
			echo "<th>first name</th>";
			echo "<th>last name</th>";
			echo "<th>email</th>";
			echo "<th>reservation date</th>";
			echo "</tr>";
			// output data of each row
			while($row = $r->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . $row["id"]. "</td>";
				echo "<td>" . $row["group"]. "</td>";
				echo "<td>" . $row["firstname"]. "</td>";
				echo "<td>" . $row["lastname"]. "</td>";
				echo "<td>" . $row["email"]. "</td>";
				echo "<td>" . $row["creation"]. "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}

?></section></div>

<?php include_once 'footer.php'?>
