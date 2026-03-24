<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM auth_users WHERE username = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();

        if (password_verify($password, $fila['password_hash'])) {
            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['usuario'] = $fila['username'];
            $_SESSION['tipo'] = $fila['tipo'];
            $_SESSION['suscripcion'] = $fila['suscripcion'];
            $_SESSION['mfa_enabled'] = $fila['mfa_enabled'];

            if ($fila['mfa_enabled'] == 1) {
                header("Location: twofactor.php");
                exit;
            } else {
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $error = "❌ Contraseña incorrecta";
        }
    } else {
        $error = "❌ Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h2>Inicio de Sesión</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Usuario:</label><input type="text" name="usuario" required><br>
        <label>Contraseña:</label><input type="password" name="password" required><br>
        <button type="submit">Entrar</button>
    </form>
    <p><a href="recover.php">¿Olvidaste tu contraseña?</a></p>
</body>
</html>
