<?php
session_start();
require_once 'PHPGangsta/GoogleAuthenticator.php';
include("db.php");

$user_id = $_SESSION["user_id"];
$sql = "SELECT mfa_secret FROM auth_users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $user["mfa_secret"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $checkResult = $ga->verifyCode($secret, $code, 2);

    if ($checkResult) {
        $_SESSION["pending_mfa"] = false;
        echo "✅ Acceso concedido";
    } else {
        echo "❌ Código incorrecto";
    }
}
?>
<form method="post">
    <label>Ingresa tu código MFA:</label>
    <input type="text" name="code" required>
    <button type="submit">Confirmar</button>
</form>
