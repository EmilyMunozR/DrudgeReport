<?php
include("db.php");

if (isset($_GET["token"])) {
    $token = $_GET["token"];

    $sql = "SELECT * FROM auth_users WHERE reset_token=? AND reset_expiration > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPass = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);

            $sql = "UPDATE auth_users SET password_hash=?, reset_token=NULL, reset_expiration=NULL WHERE reset_token=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newPass, $token);
            $stmt->execute();

            echo "✅ Contraseña actualizada correctamente. <a href='login.php'>Inicia sesión</a>";
        }
    } else {
        echo "❌ Token inválido o expirado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Resetear contraseña</title></head>
<body>
    <h2>Restablecer contraseña</h2>
    <form method="post">
        <label>Nueva contraseña:</label>
        <input type="password" name="newPassword" required>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
