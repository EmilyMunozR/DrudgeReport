<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    die("No hay sesión activa.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Dashboard</title></head>
<body>
    <h2>Bienvenido <?php echo $_SESSION['usuario']; ?></h2>
    <p>Rol: <?php echo $_SESSION['tipo']; ?></p>
    <p>Suscripción: <?php echo $_SESSION['suscripcion']; ?></p>
    <p>✅ Acceso concedido después de MFA</p>
</body>
</html>
