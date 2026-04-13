<?php
session_start();
include("db.php");

// Verificar que el usuario esté logueado
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

// Obtener información del usuario
$sql = "SELECT id, username, email, nombre_completo, suscripcion, suscripcion_vencimiento, fecha_registro 
        FROM auth_users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Procesar actualización de perfil
$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar_perfil"])) {
    $nombre_completo = trim($_POST["nombre_completo"]);
    $email = trim($_POST["email"]);
    
    $update_sql = "UPDATE auth_users SET nombre_completo=?, email=? WHERE username=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sss", $nombre_completo, $email, $username);
    
    if ($update_stmt->execute()) {
        $mensaje = "✅ " . (($_SESSION['lang'] ?? 'es') == 'es' ? "Perfil actualizado correctamente." : "Profile updated successfully.");
        // Recargar datos
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        $mensaje = "❌ " . (($_SESSION['lang'] ?? 'es') == 'es' ? "Error al actualizar el perfil." : "Error updating profile.");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Drudge Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>
        .perfil-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .perfil-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }
        
        .perfil-sidebar {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            background: var(--accent-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: white;
            font-weight: bold;
        }
        
        .perfil-sidebar h3 {
            font-family: 'Oswald', sans-serif;
            margin-bottom: 10px;
        }
        
        .suscripcion-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .suscripcion-activa {
            background: #28a745;
            color: white;
        }
        
        .suscripcion-inactiva {
            background: #dc3545;
            color: white;
        }
        
        .perfil-main {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
        }
        
        .perfil-main h2 {
            font-family: 'Oswald', sans-serif;
            color: var(--accent-red);
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--bg-color);
            color: var(--text-black);
        }
        
        .btn-primary {
            background: var(--accent-red);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-primary:hover {
            background: var(--link-blue);
        }
        
        .mensaje {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .mensaje.success {
            background: #d4edda;
            color: #155724;
        }
        
        .mensaje.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .planes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .plan-card {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        
        .plan-card h3 {
            font-family: 'Oswald', sans-serif;
            color: var(--accent-red);
            margin-bottom: 10px;
        }
        
        .plan-precio {
            font-size: 2rem;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .plan-precio small {
            font-size: 0.8rem;
        }
        
        .plan-beneficios {
            list-style: none;
            padding: 0;
            margin: 15px 0;
            text-align: left;
        }
        
        .plan-beneficios li {
            padding: 5px 0;
            padding-left: 20px;
            position: relative;
        }
        
        .plan-beneficios li::before {
            content: "✓";
            color: var(--accent-red);
            position: absolute;
            left: 0;
        }
        
        .btn-comprar {
            background: var(--accent-red);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-comprar:hover {
            background: var(--link-blue);
        }
        
        @media (max-width: 768px) {
            .perfil-grid {
                grid-template-columns: 1fr;
            }
            .planes-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-center">
        <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
        <div class="date-line">
            <?php echo date("l, d F Y"); ?> | <span data-translate="perfil_titulo">Mi Perfil</span>
        </div>
    </div>

    <div class="session-controls">
        <div class="session-row">
            <a href="perfil.php" class="username" style="text-decoration: none;"><?php echo $_SESSION["username"]; ?></a>
            <form method="post" action="logout.php" style="display:inline;">
                <button type="submit" class="btn-logout" data-translate="btn_cerrarSesion">Cerrar sesión</button>
            </form>
        </div>
    </div>
</header>

<?php include('breadcrumb.php'); ?>

<main class="perfil-container">
    <div class="perfil-grid">
        <!-- Sidebar -->
        <div class="perfil-sidebar">
            <div class="avatar">
                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
            </div>
            <h3><?php echo htmlspecialchars($user['nombre_completo'] ?: $user['username']); ?></h3>
            <p style="color: var(--text-gray);">@<?php echo $user['username']; ?></p>
            
            <?php if ($user['suscripcion'] == 'activa'): ?>
                <div class="suscripcion-badge suscripcion-activa" data-translate="perfil_suscripcion_activa">✓ Suscripción Activa</div>
                <?php if ($user['suscripcion_vencimiento']): ?>
                    <p style="font-size: 0.8rem; margin-top: 10px;">
                        <span data-translate="perfil_vencimiento">Vence:</span> <?php echo date("d/m/Y", strtotime($user['suscripcion_vencimiento'])); ?>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <div class="suscripcion-badge suscripcion-inactiva" data-translate="perfil_suscripcion_inactiva">Sin Suscripción</div>
            <?php endif; ?>
            
            <p style="font-size: 0.7rem; margin-top: 15px;">
                <span data-translate="perfil_miembro_desde">Miembro desde:</span> <?php echo date("d/m/Y", strtotime($user['fecha_registro'])); ?>
            </p>
        </div>
        
        <!-- Contenido principal -->
        <div class="perfil-main">
            <?php if ($mensaje): ?>
                <div class="mensaje success"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <!-- Datos personales -->
            <h2 data-translate="perfil_datos_personales">Datos Personales</h2>
            <form method="post">
                <div class="form-group">
                    <label data-translate="perfil_nombre_completo">Nombre completo</label>
                    <input type="text" name="nombre_completo" value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" data-translate-placeholder="perfil_placeholder_nombre" placeholder="Tu nombre completo">
                </div>
                <div class="form-group">
                    <label data-translate="perfil_email">Correo electrónico</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" data-translate-placeholder="perfil_placeholder_email" placeholder="tu@email.com">
                </div>
                <button type="submit" name="actualizar_perfil" class="btn-primary" data-translate="perfil_actualizar">Actualizar Perfil</button>
            </form>
            
            <!-- Planes de suscripción -->
            <h2 style="margin-top: 40px;" data-translate="perfil_planes_titulo">Planes de Suscripción</h2>
            <div class="planes-grid">
                <?php
                $planes_sql = "SELECT * FROM suscripciones";
                $planes_result = $conn->query($planes_sql);
                while ($plan = $planes_result->fetch_assoc()):
                ?>
                <div class="plan-card">
                    <h3><?php echo $plan['nombre']; ?></h3>
                    <div class="plan-precio">
                        $<?php echo number_format($plan['precio'], 2); ?>
                        <small>/<?php echo $plan['duracion_dias']; ?> <span data-translate="perfil_dias">días</span></small>
                    </div>
                    <ul class="plan-beneficios">
                        <?php 
                        $beneficios = explode(',', $plan['beneficios']);
                        foreach ($beneficios as $beneficio): 
                        ?>
                            <li><?php echo trim($beneficio); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="post" action="carrito.php">
                        <input type="hidden" name="suscripcion_id" value="<?php echo $plan['id']; ?>">
                        <input type="hidden" name="precio" value="<?php echo $plan['precio']; ?>">
                        <button type="submit" name="agregar_carrito" class="btn-comprar" data-translate="<?php echo $plan['precio'] == 0 ? 'perfil_btn_gratis' : 'perfil_btn_comprar'; ?>">
                            <?php echo $plan['precio'] == 0 ? 'Gratis' : 'Agregar al carrito'; ?>
                        </button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Ver carrito -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="carrito.php" class="btn-primary" style="display: inline-block; text-decoration: none;" data-translate="perfil_btn_ver_carrito">Ver mi carrito</a>
            </div>
        </div>
    </div>
</main>

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