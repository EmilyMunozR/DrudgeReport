<?php
session_start();
include("db.php"); // conexión a la base

require_once 'PHPGangsta/GoogleAuthenticator.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['mfa_secret'])) {
    header("Location: login.php");
    exit;
}

$ga = new PHPGangsta_GoogleAuthenticator();
$codigoUsuario = $_POST['codigo'];
$secret = $_SESSION['mfa_secret'];

$checkResult = $ga->verifyCode($secret, $codigoUsuario, 2);

if ($checkResult) {
    echo "✅ Bienvenido, " . $_SESSION['usuario'] . 
         ". Tipo: " . $_SESSION['tipo'] . 
         " | Suscripción: " . $_SESSION['suscripcion'];
    // Aquí podrías redirigir a dashboard.php
    // header("Location: dashboard.php");
} else {
    echo "❌ Código incorrecto. Intenta de nuevo.";
}
