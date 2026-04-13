<?php
include("db.php");
require_once 'PHPGangsta/GoogleAuthenticator.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $tipo = $_POST["tipo"];
    $suscripcion = $_POST["suscripcion"];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $ga = new PHPGangsta_GoogleAuthenticator();
    $secret = $ga->createSecret();

    $sql = "INSERT INTO auth_users (username, password_hash, tipo, suscripcion, mfa_secret, mfa_enabled) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $enabled = 1;
    $stmt->bind_param("sssssi", $username, $password_hash, $tipo, $suscripcion, $secret, $enabled);

    if ($stmt->execute()) {
        echo "✅ Usuario registrado correctamente.<br>";
        $qrCodeUrl = $ga->getQRCodeGoogleUrl("DrudgeReport", $secret);
        echo "Escanea este código QR en Google Authenticator:<br>";
        echo "<img src='" . $qrCodeUrl . "'><br>";
        echo "Clave manual: <b>$secret</b>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
<form method="post">
    <label>Usuario:</label><input type="text" name="username" required><br>
    <label>Contraseña:</label><input type="password" name="password" required><br>
    <label>Tipo:</label>
    <select name="tipo"><option value="admin">Admin</option><option value="cliente">Cliente</option></select><br>
    <label>Suscripción:</label>
    <select name="suscripcion"><option value="activa">Activa</option><option value="inactiva">Inactiva</option></select><br>
    <button type="submit">Registrar</button>
</form>
