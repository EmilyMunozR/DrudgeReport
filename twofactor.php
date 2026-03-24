<?php
session_start();
require_once 'PHPGangsta/GoogleAuthenticator.php';
include("db.php");

if (!isset($_SESSION['user_id'])) {
    die("No hay sesión activa. Vuelve a iniciar sesión.");
}

$sql = "SELECT mfa_secret FROM auth_users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || empty($user['mfa_secret'])) {
    die("No hay MFA configurado para este usuario.");
}

$secret = $user['mfa_secret'];
$ga = new PHPGangsta_GoogleAuthenticator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $checkResult = $ga->verifyCode($secret, $code, 4); // tolerancia de 4 intervalos (~2 minutos)

    if ($checkResult) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Código incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Verificación MFA</title></head>
<body>
    <h2>Autenticación Multifactor</h2>
    <p>Introduce el código de 6 dígitos de tu app Google Authenticator.</p>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Código MFA:</label><input type="text" name="code" required>
        <button type="submit">Confirmar</button>
    </form>
</body>
</html>
