<?php
include("db.php");
require_once 'PHPGangsta/GoogleAuthenticator.php';

session_start();
$username = $_SESSION["username"];

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();

// Guardar en BD
$sql = "UPDATE auth_users SET mfa_secret=?, mfa_enabled=1 WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $secret, $username);
$stmt->execute();

// Generar URL QR
$qrCodeUrl = $ga->getQRCodeGoogleUrl($username, $secret, "MiAppSegura");

// Mostrar QR
echo "<img src='".$qrCodeUrl."'>";
?>
