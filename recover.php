<?php
include("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $exp = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Guardar token en BD
    $sql = "UPDATE auth_users SET reset_token=?, reset_expiration=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $exp, $email);
    $stmt->execute();

    // Configurar PHPMailer con Mailjet
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'in-v3.mailjet.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'c1919fabf503647e15db231010b5ec05';   // tu API Key pública de Mailjet
        $mail->Password = '4df00d457145f622ba7129c21b034603';   // tu API Key secreta de Mailjet
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('emilymunoz1018@gmail.com', 'DrudgeReport');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body    = "Haz clic en el siguiente enlace para resetear tu contraseña: 
                          <a href='https://drudgereport.onrender.com/reset.php?token=$token'>Resetear contraseña</a>";

        $mail->send();
        echo "📩 Correo enviado a $email";
    } catch (Exception $e) {
        echo "❌ Error al enviar correo: {$mail->ErrorInfo}";
    }
}
?>
<form method="post">
    <label>Usuario/Correo:</label><input type="text" name="email" required>
    <button type="submit">Enviar enlace</button>
</form>
