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

    // Configuración Mailjet API
    $mj_url = 'https://api.mailjet.com/v3.1/send';
    $mj_api_key = 'c1919fabf503647e15db231010b5ec05';
    $mj_api_secret = '4df00d457145f622ba7129c21b034603';

   $data = [
        'Messages' => [[
            'From' => [
                'Email' => 'emilymunoz1018@gmail.com', // debe estar verificado en Mailjet
                'Name'  => 'DrudgeReport'
            ],
            'To' => [[ 'Email' => $email ]],
            'Subject' => 'Recuperación de contraseña',
            'TextPart' => 'Haz clic en el enlace para resetear tu contraseña',
            'HTMLPart' => "Haz clic en el siguiente enlace: 
                           <a href='https://drudgereport.onrender.com/reset.php?token=$token'>Resetear contraseña</a>"
        ]]
    ];
    
    $ch = curl_init($mj_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_USERPWD, "$mj_api_key:$mj_api_secret");
    
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status: $http_status\n";
    echo "Response: $response\n";

?>
<form method="post">
    <label>Usuario/Correo:</label><input type="text" name="email" required>
    <button type="submit">Enviar enlace</button>
</form>
