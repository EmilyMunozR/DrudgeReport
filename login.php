<?php
include("db.php");
session_start();

// Si ya inició sesión, redirigirlo a donde pertenece
if (isset($_SESSION["username"]) && isset($_SESSION["authenticated"])) {
    if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // AÑADIMOS 'tipo' a la consulta (Asegúrate de que la columna se llame así en tu BD)
    $sql = "SELECT password_hash, mfa_enabled, tipo FROM auth_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password_hash"])) {
            
            // Guardamos usuario y ROL en la sesión
            $_SESSION["username"] = $username;
            $_SESSION["tipo"] = $row["tipo"]; 

            // Si tiene MFA, lo mandamos a verificar el código
            if ($row["mfa_enabled"]) {
                header("Location: twofactor.php");
                exit;
            } else {
                $_SESSION["authenticated"] = true;
                
                // REDIRECCIÓN INTELIGENTE (Admin o Lector)
                if ($_SESSION["tipo"] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Drudge Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>

    <style>
        .login-container {
            max-width: 500px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .login-box {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 40px;
            box-shadow: 8px 8px 0 rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .login-box h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: var(--accent-red);
        }
        
        .login-subtitle {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
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
        
        .recover-link {
            margin-top: 20px;
            display: inline-block;
            color: var(--text-black);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .recover-link:hover {
            color: var(--accent-red);
            text-decoration: underline;
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
    </style>

</head>
<body>

<header>
  <div class="header-center">
    <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
  </div>
</header>

<main class="container" style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
    <div class="login-box">
        <h2 data-translate="login_titulo">Iniciar Sesión</h2>
        <div class="login-subtitle" data-translate="login_subtitulo">Accede a tu cuenta de Drudge Report</div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">⚠️ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label data-translate="login_usuario">Usuario:</label>
                <input type="text" name="username" required autocomplete="username" data-translate-placeholder="login_usuario_placeholder" placeholder="Ingresa tu usuario">
            </div>
            <div class="form-group">
                <label data-translate="login_password">Contraseña:</label>
                <input type="password" name="password" required autocomplete="current-password" data-translate-placeholder="login_password_placeholder" placeholder="Ingresa tu contraseña">
            </div>
            <button type="submit" data-translate="login_btn">Entrar</button>
        </form>
        
        <a href="recover.php" class="recover-link" data-translate="login_recover">¿Olvidaste tu contraseña?</a>
    </div>
</main>

<div class="tools-bar">
    <div class="theme-switch" title="Cambiar Tema">
        <div class="switch-knob"></div>
    </div>
    <div id="langSwitch" class="lang-switch" title="Cambiar Idioma">
        <div class="lang-knob"></div>
    </div>
    <button id="textSizeBtn" class="accessibility-btn" title="Aumentar tamaño de texto">A+</button>
</div>

</body>
</html>