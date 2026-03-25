<?php
include("db.php");
require 'vendor/autoload.php';
require 'phpqrcode/qrlib.php';

use Base32\Base32;

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

// Generar secreto nuevo
$secret = Base32::encode(random_bytes(10));

// Guardar en BD
$sql = "UPDATE auth_users SET mfa_secret=?, mfa_enabled=1 WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $secret, $username);
$stmt->execute();

// Construir URL otpauth
$issuer = urlencode("MiAppSegura");
$url = "otpauth://totp/{$issuer}:{$username}?secret={$secret}&issuer={$issuer}";

// Mostrar QR
header('Content-Type: image/png');
QRcode::png($url);
?>
