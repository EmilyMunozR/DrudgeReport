<?php
session_start();
require 'db.php'; // Tu archivo de conexión

// Proteger la vista: solo admins pueden entrar (Usando 'tipo')
if (!isset($_SESSION["username"]) || !isset($_SESSION["tipo"]) || $_SESSION["tipo"] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Lógica para ELIMINAR una noticia
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $conn->query("DELETE FROM Noticias WHERE id = $id_eliminar");
    header("Location: admin.php"); // Recargar página
    exit();
}

// Lógica para AGREGAR una noticia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $enlace = $conn->real_escape_string($_POST['enlace']);
    $columna = $conn->real_escape_string($_POST['columna']);

    $sql = "INSERT INTO Noticias (titulo, enlace, columna) VALUES ('$titulo', '$enlace', '$columna')";
    $conn->query($sql);
    header("Location: admin.php"); // Recargar página
    exit();
}

// Obtener todas las noticias para mostrarlas
$resultado = $conn->query("SELECT * FROM Noticias ORDER BY fecha_creacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Noticias</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    
    <script src="script.js" defer></script> 
    
    <style>
        .admin-container { max-width: 800px; margin: 40px auto; padding: 20px; background: var(--card-bg, #fff); border: 1px solid var(--border-color, #ccc); }
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid var(--border-color, #ccc); padding: 10px; text-align: left; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
        
        /* Estilos para el botón de regresar */
        .back-home-link {
            display: inline-block;
            margin-bottom: 25px;
            color: var(--text-gray, #666);
            text-decoration: none;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.95rem;
            transition: color 0.3s ease, transform 0.2s ease;
        }
        .back-home-link:hover {
            color: var(--accent-red, #d50000);
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <a href="index.php" class="back-home-link" data-translate="volver_inicio">⬅ Volver al Inicio</a>

        <h2 data-translate="admin_titulo">Panel de Administración de Noticias</h2>

        <form method="POST" action="admin.php">
            <h3 data-translate="admin_agregar_titulo">Agregar Nueva Noticia</h3>
            <div class="form-group">
                <label data-translate="admin_label_titulo">Título del enlace:</label>
                <input type="text" name="titulo" required>
            </div>
            <div class="form-group">
                <label data-translate="admin_label_url">URL / Enlace (ej. https://...):</label>
                <input type="url" name="enlace" required>
            </div>
            <div class="form-group">
                <label data-translate="admin_label_columna">¿En qué columna mostrar?:</label>
                <select name="columna">
                    <option value="izquierda" data-translate="admin_col_izq">Columna Izquierda</option>
                    <option value="centro" data-translate="admin_col_cen">Historia Principal (Centro)</option>
                    <option value="derecha" data-translate="admin_col_der">Columna Derecha</option>
                </select>
            </div>
            <button type="submit" name="agregar" style="padding: 10px 20px; cursor: pointer;" data-translate="admin_btn_guardar">Guardar Noticia</button>
        </form>

        <hr style="margin: 30px 0;">

        <h3 data-translate="admin_noticias_actuales">Noticias Actuales</h3>
        <table>
            <thead>
                <tr>
                    <th data-translate="admin_th_id">ID</th>
                    <th data-translate="admin_th_titulo">Título</th>
                    <th data-translate="admin_th_columna">Columna</th>
                    <th data-translate="admin_th_accion">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><a href="<?php echo htmlspecialchars($fila['enlace']); ?>" target="_blank"><?php echo htmlspecialchars($fila['titulo']); ?></a></td>
                    
                    <td data-translate="<?php 
                        if($fila['columna'] == 'izquierda') echo 'admin_col_izq';
                        if($fila['columna'] == 'centro') echo 'admin_col_cen';
                        if($fila['columna'] == 'derecha') echo 'admin_col_der';
                    ?>">
                        <?php echo ucfirst(htmlspecialchars($fila['columna'])); ?>
                    </td>
                    
                    <td><a href="admin.php?eliminar=<?php echo $fila['id']; ?>" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminarla?');" data-translate="admin_btn_eliminar">Eliminar</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
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