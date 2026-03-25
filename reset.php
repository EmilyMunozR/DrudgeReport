<?php
include("db.php");

if (isset($_GET["token"]) && isset($_GET["username"])) {
    $token = $_GET["token"];
    $username = $_GET["username"];

    // Buscar token en BD
    $sql = "SELECT reset_expiration FROM auth_users WHERE username=? AND reset_token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $exp = $row["reset_expiration"];
        if (strtotime($exp) > time()) {
            // Token válido → mostrar formulario para nueva contraseña
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $newpass = password_hash($_POST["newpass"], PASSWORD_DEFAULT);
                $sql = "UPDATE auth_users 
                        SET password_hash=?, reset_token=NULL, reset_expiration=NULL 
                        WHERE username=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newpass, $username);
                $stmt->execute();
                echo "✅ Contraseña actualizada correctamente.";
            } else {
                echo '<form method="post">
                        <label>Nueva contraseña:</label>
                        <input type="password" name="newpass" required>
                        <button type="submit">Cambiar contraseña</button>
                      </form>';
            }
        } else {
            echo "❌ Token expirado.";
        }
    } else {
        echo "❌ Token inválido.";
    }
}
?>
