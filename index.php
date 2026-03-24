<?php
// Incluimos la librería
require_once 'PHPGangsta/GoogleAuthenticator.php';

// Creamos el objeto
$ga = new PHPGangsta_GoogleAuthenticator();

// Generamos una clave secreta única para el usuario
$secret = $ga->createSecret();

// Mostramos la clave secreta
echo "Clave secreta: " . $secret . "<br>";

// Generamos la URL para el código QR (para escanear en la app Google Authenticator)
$qrCodeUrl = $ga->getQRCodeGoogleUrl('DrudgeReport', $secret);

// Mostramos el QR
echo "Escanea este código QR en tu app:<br>";
echo "<img src='" . $qrCodeUrl . "'><br>";

// Ejemplo de validación de un código introducido por el usuario
// Supongamos que el usuario mete el código en un formulario
if (isset($_POST['codigo'])) {
    $codigoUsuario = $_POST['codigo'];
    $checkResult = $ga->verifyCode($secret, $codigoUsuario, 2); // margen de 2*30 segundos

    if ($checkResult) {
        echo "✅ Código válido, acceso permitido.";
    } else {
        echo "❌ Código incorrecto, acceso denegado.";
    }
}
?>

<!-- Formulario para introducir el código -->
<form method="post">
    <label>Introduce el código de Google Authenticator:</label>
    <input type="text" name="codigo">
    <button type="submit">Verificar</button>
</form>
