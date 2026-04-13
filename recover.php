<?php
include("db.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña - Drudge Report</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
  <style>
    /* Estilos específicos para la página de recuperación */
    .recover-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
      min-height: calc(100vh - 200px);
    }
    
    .recover-box {
      max-width: 500px;
      width: 100%;
      background: var(--card-bg);
      border: 2px solid var(--border-color);
      padding: 40px;
      box-shadow: 8px 8px 0 rgba(0,0,0,0.1);
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .recover-box h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem;
      margin-bottom: 10px;
      text-transform: uppercase;
      color: var(--accent-red);
    }
    
    .subtitle {
      font-size: 0.9rem;
      color: var(--text-gray);
      margin-bottom: 30px;
      border-bottom: 1px solid var(--border-color);
      padding-bottom: 15px;
    }
    
    .form-group {
      margin-bottom: 25px;
      text-align: left;
    }
    
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
      font-size: 0.9rem;
      color: var(--text-black);
    }
    
    input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      font-family: inherit;
      font-size: 1rem;
      background: var(--bg-color);
      color: var(--text-black);
      transition: all 0.3s;
    }
    
    input:focus {
      outline: none;
      border-color: var(--accent-red);
      box-shadow: 0 0 0 2px rgba(213,0,0,0.2);
    }
    
    button {
      background: var(--accent-red);
      color: #fff;
      border: none;
      padding: 12px 30px;
      cursor: pointer;
      font-weight: bold;
      border-radius: 4px;
      font-family: 'Roboto Mono', monospace;
      font-size: 1rem;
      width: 100%;
      transition: background 0.3s;
    }
    
    button:hover {
      background: var(--link-blue);
    }
    
    .message {
      margin-top: 20px;
      padding: 12px;
      border-radius: 4px;
      font-size: 0.9rem;
    }
    
    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: var(--text-black);
      text-decoration: none;
      font-size: 0.9rem;
    }
    
    .back-link:hover {
      color: var(--accent-red);
      text-decoration: underline;
    }
    
    .info-note {
      margin-top: 20px;
      font-size: 0.75rem;
      color: var(--text-gray);
    }
    
    /* Modo oscuro para mensajes */
    body.dark-mode .message.success {
      background: #1e3a2f;
      color: #a5d6a5;
      border-color: #2e7d32;
    }
    
    body.dark-mode .message.error {
      background: #3a1e1e;
      color: #f8b4b4;
      border-color: #d50000;
    }
  </style>
</head>
<body>

<header>
  <div class="header-center">
    <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
    <div class="date-line">
      <?php echo date("l, d F Y"); ?> | <span data-translate="recover_titulo">Recuperar Contraseña</span>
    </div>
  </div>
</header>

<div class="recover-container">
  <div class="recover-box">
    <h2 data-translate="recover_titulo_principal">¿Olvidaste tu contraseña?</h2>
    <div class="subtitle" data-translate="recover_subtitulo">Ingresa tu usuario y te enviaremos un enlace para restablecerla</div>

    <?php
    $message = '';
    $messageType = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        
        // Verificar si el usuario existe
        $checkSql = "SELECT username FROM auth_users WHERE username=?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $token = bin2hex(random_bytes(32));
            $exp = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $sql = "UPDATE auth_users SET reset_token=?, reset_expiration=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $token, $exp, $username);
            
            if ($stmt->execute()) {
                $resend_url = 'https://api.resend.com/emails';
                $resend_api_key = 're_RTtZCZR6_3QpKu3Xamwna1yzAUyUrYzi3';
                
                $resetLink = "https://drudgereport.onrender.com/reset.php?token=$token&username=" . urlencode($username);
                
                $data = [
                    "from" => "onboarding@resend.dev",
                    "to" => "emilymunoz1018@gmail.com",
                    "subject" => "Recuperación de contraseña - Drudge Report",
                    "html" => "
                        <html>
                        <head>
                            <style>
                                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                .header { background: #d50000; color: white; padding: 20px; text-align: center; }
                                .content { padding: 20px; background: #f9f9f9; }
                                .button { display: inline-block; padding: 12px 24px; background: #d50000; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
                                .footer { font-size: 12px; text-align: center; padding: 20px; color: #666; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <h1>Drudge Report</h1>
                                    <p>Recuperación de contraseña</p>
                                </div>
                                <div class='content'>
                                    <p>Hola <strong>$username</strong>,</p>
                                    <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el botón de abajo para crear una nueva contraseña:</p>
                                    <p style='text-align: center;'>
                                        <a href='$resetLink' class='button' style='color: white;'>Restablecer contraseña</a>
                                    </p>
                                    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                                    <p>Este enlace expirará en 1 hora.</p>
                                    <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
                                    <p><small>$resetLink</small></p>
                                </div>
                                <div class='footer'>
                                    <p>© 2026 Drudge Report. Todos los derechos reservados.</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    "
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

                if ($http_status == 200) {
                    $message = "Se ha enviado un enlace de recuperación a tu correo registrado.";
                    $messageType = "success";
                } else {
                    $message = "Error al enviar el correo. Por favor, intenta de nuevo más tarde.";
                    $messageType = "error";
                }
            } else {
                $message = "Error al procesar la solicitud.";
                $messageType = "error";
            }
        } else {
            $message = " El usuario no existe en nuestro sistema.";
            $messageType = "error";
        }
    }
    ?>

    <?php if (!empty($message)): ?>
      <div class="message <?php echo $messageType; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label data-translate="recover_usuario">Nombre de usuario</label>
        <input type="text" name="username" required autocomplete="username" data-translate-placeholder="recover_usuario_placeholder" placeholder="Ej: juan_perez">
      </div>
      <button type="submit" data-translate="recover_btn">Enviar enlace de recuperación</button>
    </form>
    
    <a href="login.php" class="back-link" data-translate="recover_volver">← Volver a Iniciar Sesión</a>
    <div class="info-note" data-translate="recover_nota">El enlace expirará en 1 hora. Revisa también tu carpeta de spam.</div>
  </div>
</div>

<!-- Barra de herramientas con dos switches -->
<div class="tools-bar">
  <div class="theme-switch">
    <div class="switch-knob"></div>
  </div>
  <div id="langSwitch" class="lang-switch">
    <div class="lang-knob"></div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>