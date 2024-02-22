<?php

$servername = "p3plcpnl0494.prod.phx3.secureserver.net";
$username = "hopcaquehuong";
$password = "QueHuong.09";

// Create connection
$conn = new mysqli($servername, $username, $password, "hopcaquehuong");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully<br>";

$sql = "SELECT id, firstname, lastname FROM booking";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}
echo "OMg";

$conn->close();