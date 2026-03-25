<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["authenticated"])) {
    header("Location: login.php");
    exit;
}

echo "✅ Bienvenido al dashboard, " . $_SESSION["username"];
?>
