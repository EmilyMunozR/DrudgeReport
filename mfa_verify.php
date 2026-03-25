<?php
include("db.php");
require 'vendor/autoload.php';

use OTPHP\TOTP;

$username = $_POST["username"];
$codigoIngresado = $_POST["codigo"];

// Recuperar secreto de BD
$sql = "SELECT mfa_secret FROM auth_users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$secret = $row["mfa_secret"];

// Validar código
$totp = TOTP::create($secret);
if ($totp->verify($codigoIngresado)) {
    echo "✅ Código correcto";
} else {
    echo "❌ Código incorrecto";
}
?>
<form method="post">
    <label>Usuario:</label><input type="text" name="username" required><br>
    <label>Código:</label><input type="text" name="codigo" required><br>
    <button type="submit">Verificar</button>
</form>
