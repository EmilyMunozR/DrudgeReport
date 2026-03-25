<?php
include("db.php");
require_once 'PHPGangsta/GoogleAuthenticator.php';

session_start();
$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoIngresado = $_POST["codigo"];

    $sql = "SELECT mfa_secret FROM auth_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $secret = $row["mfa_secret"];

    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($secret, $codigoIngresado, 2);

    if ($checkResult) {
        $_SESSION["authenticated"] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "❌ Código incorrecto.";
    }
}
?>

<form method="post">
    <label>Código de 6 dígitos:</label>
    <input type="text" name="codigo" required>
    <button type="submit">Verificar</button>
</form>
