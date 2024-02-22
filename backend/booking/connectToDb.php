 <?php
$servername = "p3plcpnl0494.prod.phx3.secureserver.net";
$username = "hopcaquehuong";
$password = "secret";
$dbname = "hopcaquehuong";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
echo "<br>";

$sql = "SELECT id, firstname, lastname FROM booking";
$result = $conn->query($sql);




$sqlInsert = "INSERT into booking (firstname, lastname, seat, email) ";

for ($idx = 0; $idx < 100; ++$idx)
{
  echo "Write to database <br>";
  $firstName = "Ha"; $lastName  = "Nguyen";
  $seatNum = 0; $email = "HaNguyen";

  randomData($firstName, $lastName, $seatNum, $email);
  $sqlValues = " VALUES ('{$firstName}','{$lastName}', '{$seatNum}', '{$email}')";
  $command = $sqlInsert . $sqlValues;

	if ($conn->query($command) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Name</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["firstname"]." ".$row["lastname"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

function randomData(&$firstName, &$lastName, &$seatNum, &$email) {
  $randNum = mt_rand(0,10000);
  $firstName = $firstName . "_" . $randNum;
  $lastName  = $lastName . "_" . $randNum;
  $seatNum   = $randNum;
  $email = $email . "_" . $randNum . "@yahoo.com";
}
?>
