<?php
session_start();

// 1. Configuración de la base de datos (Cambia estos datos por los tuyos)
$host = "localhost";
$dbname = "dwp";
$username = "root";
$password = "";

try {
    // 2. Conexión a la base de datos usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_contacto'])) {
        
        // Limpiar y sanitizar los datos para evitar XSS
        $nombre = htmlspecialchars(trim($_POST['nombre_contacto']));
        $email = filter_var(trim($_POST['email_contacto']), FILTER_SANITIZE_EMAIL);
        $mensaje = htmlspecialchars(trim($_POST['mensaje_contacto']));

        // Validar que no estén vacíos
        if (!empty($nombre) && !empty($email) && !empty($mensaje) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            // 4. Preparar la consulta SQL (Evita Inyección SQL)
            $sql = "INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)";
            $stmt = $conn->prepare($sql);
            
            // 5. Vincular los parámetros y ejecutar
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensaje', $mensaje);
            $stmt->execute();

            // Mensaje de éxito
            $_SESSION['msg_contacto'] = "¡Mensaje enviado correctamente!";
            header("Location: index.php");
            exit();
            
        } else {
            // Error de validación
            $_SESSION['msg_contacto'] = "Por favor, completa todos los campos con información válida.";
            header("Location: index.php");
            exit();
        }
    }
} catch(PDOException $e) {
    // Manejo de errores de base de datos
    $_SESSION['msg_contacto'] = "Error de conexión: " . $e->getMessage();
    header("Location: index.php");
    exit();
}
?>