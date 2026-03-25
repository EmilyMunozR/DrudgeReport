<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT password_hash, mfa_enabled FROM auth_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password_hash"])) {
            $_SESSION["username"] = $username;
            if ($row["mfa_enabled"]) {
                header("Location: twofactor.php");
                exit;
            } else {
                header("Location: dashboard.php");
                exit;
            }
        } else {
            echo "❌ Contraseña incorrecta.";
        }
    } else {
        echo "❌ Usuario no encontrado.";
    }
}
?>

<form method="post">
    <label>Usuario:</label><input type="text" name="username" required><br>
    <label>Contraseña:</label><input type="password" name="password" required><br>
    <button type="submit">Iniciar sesión</button>
</form>
