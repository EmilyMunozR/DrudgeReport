<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];

    // Verificamos si existe
    $stmt = $conexion->prepare("SELECT * FROM auth_users WHERE username=?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $token = bin2hex(random_bytes(16)); // generamos token seguro
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conexion->prepare("UPDATE auth_users SET reset_token=?, reset_expiration=? WHERE username=?");
        $update->bind_param("sss", $token, $expira, $usuario);
        $update->execute();

        echo "✅ Se generó un enlace de recuperación:<br>";
        echo "<a href='reset.php?token=$token'>Resetear contraseña</a>";
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Recuperar contraseña</title></head>
<body>
    <h2>Recuperar contraseña</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Usuario:</label>
        <input type="text" name="usuario" required><br>
        <button type="submit">Generar enlace</button>
    </form>
</body>
</html>
