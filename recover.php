<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $exp = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Guardar token en BD para ese usuario
    $sql = "UPDATE auth_users SET reset_token=?, reset_expiration=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $token, $exp, $username);
    $stmt->execute();

    // Configuración Resend API
    $resend_url = 'https://api.resend.com/emails';
    $resend_api_key = 'TU_API_KEY_RESEND';

    $data = [
        "from" => "onboarding@resend.dev",
        "to" => $email,
        "subject" => "Recuperación de contraseña",
        "html" => "Hola $username,<br>
                   Haz clic en el siguiente enlace para resetear tu contraseña: 
                   <a href='https://drudgereport.onrender.com/reset.php?token=$token&username=$username'>Resetear contraseña</a>"
    ];

    $ch = curl_init($resend_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $resend_api_key
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Status: $http_status<br>";
    echo "Response: $response<br>";
}
?>
<form method="post">
    <label>Usuario:</label><input type="text" name="username" required><br>
    <label>Correo:</label><input type="email" name="email" required><br>
    <button type="submit">Enviar enlace</button>
</form>
