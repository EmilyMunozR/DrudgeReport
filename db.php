<?php
$host = "46.28.42.226";
$user = "u760464709_23005014_usr";
$pass = "B|7k3UPs3&P";
$dbname = "u760464709_23005014_bd";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
