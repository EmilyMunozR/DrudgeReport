<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $exp = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Guardar token en BD
    $sql = "UPDATE auth_users SET reset_token=?, reset_expiration=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $exp, $email);
    $stmt->execute();

    // Configuración Resend API
    $resend_url = 'https://api.resend.com/emails';
    $resend_api_key = 're_RTtZCZR6_3QpKu3Xamwna1yzAUyUrYzi3'; // pega aquí tu API Key

    $data = [
        "from" => "onboarding@resend.dev", // remitente compartido de Resend
        "to" => "emilymunoz1018@gmail.com",                     // destinatario: el usuario que pidió recuperar contraseña
        "subject" => "Recuperación de contraseña",
        "html" => "Haz clic en el siguiente enlace para resetear tu contraseña: 
                   <a href='https://drudgereport.onrender.com/reset.php?token=$token'>Resetear contraseña</a>"
    ];

    // Enviar petición HTTP con cURL
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
    <label>Usuario/Correo:</label><input type="text" name="email" required>
    <button type="submit">Enviar enlace</button>
</form>
