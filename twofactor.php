<?php
include("db.php");
require_once 'PHPGangsta/GoogleAuthenticator.php';

session_start();
$username = $_SESSION["username"];

// Verificar que hay un usuario en sesión
if (!isset($username)) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoIngresado = $_POST["codigo"];

    $sql = "SELECT mfa_secret FROM auth_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $secret = $row["mfa_secret"];

    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($secret, $codigoIngresado, 2);

    if ($checkResult) {
$_SESSION["authenticated"] = true;
if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === 'admin') {
    header("Location: admin.php");
} else {
    header("Location: index.php");
}
exit;
    } else {
        $error = "Código incorrecto. Por favor, verifica el código de tu aplicación Google Authenticator.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación en Dos Pasos - Drudge Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>
        /* Estilos específicos para la página de verificación 2FA */
        .verify-container {
            max-width: 500px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .verify-box {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 40px;
            box-shadow: 8px 8px 0 rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .verify-box h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: var(--accent-red);
        }
        
        .verify-subtitle {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }
        
        .info-box {
            background: rgba(213, 0, 0, 0.1);
            border-left: 4px solid var(--accent-red);
            padding: 15px;
            margin-bottom: 25px;
            text-align: left;
            font-size: 0.85rem;
            border-radius: 4px;
        }
        
        .info-box p {
            margin: 5px 0;
        }
        
        .info-box .icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: var(--text-black);
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            background: var(--bg-color);
            color: var(--text-black);
            font-size: 1.2rem;
            text-align: center;
            letter-spacing: 4px;
            transition: all 0.3s ease;
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
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: var(--text-black);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link:hover {
            color: var(--accent-red);
            text-decoration: underline;
        }
        
        /* Modo oscuro para mensajes de error */
        body.dark-mode .error-message {
            background: #2c1a1c;
            color: #f8b4b4;
            border-color: #d50000;
        }
        
        body.dark-mode .info-box {
            background: rgba(213, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<header>
    <div class="header-center">
        <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
        <div class="date-line">
            <?php echo date("l, d F Y"); ?> | <span data-translate="twofactor_titulo">Verificación en Dos Pasos</span>
        </div>
    </div>
</header>

<main class="verify-container">
    <div class="verify-box">
        <h2 data-translate="twofactor_titulo_principal">Verificación 2FA</h2>
        <div class="verify-subtitle" data-translate="twofactor_subtitulo">Autenticación de dos factores</div>
        
        <div class="info-box">
            <p><strong data-translate="twofactor_info_titulo">Google Authenticator</strong></p>
            <p data-translate="twofactor_info_texto">Abre la aplicación Google Authenticator en tu teléfono e ingresa el código de 6 dígitos que aparece para tu cuenta de Drudge Report.</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">⚠️ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label data-translate="twofactor_codigo">Código de 6 dígitos:</label>
                <input type="text" name="codigo" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="off" data-translate-placeholder="twofactor_codigo_placeholder" placeholder="000000">
            </div>
            <button type="submit" data-translate="twofactor_btn">Verificar y Acceder</button>
        </form>
        
        <a href="logout.php" class="back-link" data-translate="twofactor_volver">← Volver al inicio de sesión</a>
    </div>
</main>

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