<?php
session_start();
include("db.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Obtener ID del usuario
$sql_user = "SELECT id FROM auth_users WHERE username=?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $_SESSION["username"]);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
$usuario_id = $user['id'];

// Agregar al carrito
if (isset($_POST["agregar_carrito"])) {
    $suscripcion_id = $_POST["suscripcion_id"];
    
    // Verificar si ya está en el carrito
    $check = $conn->prepare("SELECT id FROM carrito WHERE usuario_id=? AND suscripcion_id=?");
    $check->bind_param("ii", $usuario_id, $suscripcion_id);
    $check->execute();
    $exists = $check->get_result()->num_rows;
    
    if ($exists == 0) {
        $insert = $conn->prepare("INSERT INTO carrito (usuario_id, suscripcion_id) VALUES (?, ?)");
        $insert->bind_param("ii", $usuario_id, $suscripcion_id);
        $insert->execute();
        $mensaje = "" . ($_SESSION['lang'] ?? 'es') == 'es' ? "Producto agregado al carrito." : "Product added to cart.";
        $mensaje_tipo = "success";
    } else {
        $mensaje = "" . ($_SESSION['lang'] ?? 'es') == 'es' ? "Este producto ya está en tu carrito." : "This product is already in your cart.";
        $mensaje_tipo = "warning";
    }
}

// Eliminar del carrito
if (isset($_GET["eliminar"])) {
    $carrito_id = $_GET["eliminar"];
    $delete = $conn->prepare("DELETE FROM carrito WHERE id=? AND usuario_id=?");
    $delete->bind_param("ii", $carrito_id, $usuario_id);
    $delete->execute();
    $mensaje = "" . ($_SESSION['lang'] ?? 'es') == 'es' ? "Producto eliminado del carrito." : "Product removed from cart.";
    $mensaje_tipo = "warning";
}

// Procesar compra
if (isset($_POST["finalizar_compra"])) {
    $carrito_items = $_POST["carrito_items"] ?? [];
    
    if (!empty($carrito_items)) {
        foreach ($carrito_items as $item_id) {
            $sql_plan = "SELECT s.* FROM suscripciones s 
                         JOIN carrito c ON c.suscripcion_id = s.id 
                         WHERE c.id=? AND c.usuario_id=?";
            $stmt_plan = $conn->prepare($sql_plan);
            $stmt_plan->bind_param("ii", $item_id, $usuario_id);
            $stmt_plan->execute();
            $plan = $stmt_plan->get_result()->fetch_assoc();
            
            if ($plan) {
                $vencimiento = date('Y-m-d', strtotime("+{$plan['duracion_dias']} days"));
                $update = $conn->prepare("UPDATE auth_users SET suscripcion='activa', suscripcion_vencimiento=? WHERE id=?");
                $update->bind_param("si", $vencimiento, $usuario_id);
                $update->execute();
            }
        }
        
        $clear = $conn->prepare("DELETE FROM carrito WHERE usuario_id=?");
        $clear->bind_param("i", $usuario_id);
        $clear->execute();
        
        $mensaje = "" . ($_SESSION['lang'] ?? 'es') == 'es' ? "¡Compra realizada con éxito! Tu suscripción ya está activa." : "Purchase completed successfully! Your subscription is now active.";
        $mensaje_tipo = "success";
    }
}

// Obtener carrito
$carrito_sql = "SELECT c.id as carrito_id, s.* FROM carrito c 
                JOIN suscripciones s ON c.suscripcion_id = s.id 
                WHERE c.usuario_id=?";
$carrito_stmt = $conn->prepare($carrito_sql);
$carrito_stmt->bind_param("i", $usuario_id);
$carrito_stmt->execute();
$carrito = $carrito_stmt->get_result();

$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito - Drudge Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>
        .carrito-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .carrito-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
        }
        
        .carrito-card h2 {
            font-family: 'Oswald', sans-serif;
            color: var(--accent-red);
            margin-bottom: 25px;
        }
        
        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .carrito-item-info h3 {
            margin-bottom: 5px;
        }
        
        .carrito-item-info p {
            color: var(--text-gray);
            font-size: 0.85rem;
        }
        
        .carrito-item-precio {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--accent-red);
        }
        
        .btn-eliminar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 15px;
        }
        
        .btn-eliminar:hover {
            background: #c82333;
        }
        
        .carrito-total {
            text-align: right;
            padding: 20px;
            font-size: 1.3rem;
            border-top: 2px solid var(--border-color);
            margin-top: 20px;
        }
        
        .carrito-total span {
            font-weight: bold;
            color: var(--accent-red);
        }
        
        .btn-finalizar {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-finalizar:hover {
            background: #218838;
        }
        
        .btn-seguir {
            background: var(--accent-red);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .carrito-vacio {
            text-align: center;
            padding: 50px;
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
        
        .mensaje.warning {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>

<header>
    <div class="header-center">
        <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
        <div class="date-line">
            <?php echo date("l, d F Y"); ?> | <span data-translate="carrito_titulo">Mi Carrito</span>
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

<main class="carrito-container">
    <div class="carrito-card">
        <h2 data-translate="carrito_titulo_principal">Mi Carrito de Compras</h2>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje_tipo ?? 'warning'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($carrito->num_rows == 0): ?>
            <div class="carrito-vacio">
                <p style="font-size: 1.2rem; margin-bottom: 20px;" data-translate="carrito_vacio">Tu carrito está vacío</p>
                <a href="perfil.php" class="btn-seguir" data-translate="carrito_btn_seguir_comprando">Ver planes de suscripción</a>
            </div>
        <?php else: ?>
            <form method="post">
                <?php while ($item = $carrito->fetch_assoc()): 
                    $total += $item['precio'];
                ?>
                <div class="carrito-item">
                    <div class="carrito-item-info">
                        <h3><?php echo $item['nombre']; ?></h3>
                        <p><?php echo $item['descripcion']; ?></p>
                        <small><span data-translate="carrito_dias_acceso">días de acceso</span>: <?php echo $item['duracion_dias']; ?></small>
                    </div>
                    <div class="carrito-item-precio">
                        $<?php echo number_format($item['precio'], 2); ?>
                        <a href="carrito.php?eliminar=<?php echo $item['carrito_id']; ?>" class="btn-eliminar" onclick="return confirm('<?php echo ($_SESSION['lang'] ?? 'es') == 'es' ? '¿Eliminar este producto del carrito?' : 'Remove this product from cart?'; ?>')" data-translate="carrito_btn_eliminar">Eliminar</a>
                    </div>
                    <input type="checkbox" name="carrito_items[]" value="<?php echo $item['carrito_id']; ?>" checked style="display: none;">
                </div>
                <?php endwhile; ?>
                
                <div class="carrito-total">
                    <span data-translate="carrito_total">Total:</span> <span>$<?php echo number_format($total, 2); ?></span> MXN
                    
                </div>
                <button type="submit" name="finalizar_compra" class="btn-finalizar" data-translate="carrito_btn_finalizar">Finalizar Compra</button>
            </form>
            
            <div style="text-align: center;">
                <a href="perfil.php" class="btn-seguir" style="background: var(--accent-red);" data-translate="carrito_btn_seguir">← Seguir comprando</a>
            </div>
        <?php endif; ?>
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