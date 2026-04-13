<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubicación - DrudgeReport</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
  <style>
    /* Estilos específicos para la página de ubicación */
    .map-container {
      width: 100%;
      height: 500px;
      margin: 20px 0;
      border-radius: 8px;
      overflow: hidden;
    }
    
    iframe {
      width: 100%;
      height: 100%;
      border: 0;
    }
    
    /* Asegurar que el contenido se vea bien en modo oscuro */
    body.dark-mode .map-container iframe {
      filter: brightness(0.8);
    }
    
    /* Contenedor principal */
    .location-main {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .location-title {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem;
      margin-bottom: 20px;
      text-align: center;
      color: var(--accent-red);
    }
    
    .location-address {
      text-align: center;
      margin-bottom: 30px;
      padding: 25px;
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .location-address h3 {
      font-family: 'Oswald', sans-serif;
      margin-bottom: 15px;
      font-size: 1.5rem;
    }
    
    .location-address p {
      margin: 8px 0;
      line-height: 1.6;
    }
    
    .location-note {
      font-size: 0.85rem;
      color: var(--text-gray);
      margin-top: 15px;
      font-style: italic;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    
    .info-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .info-card h4 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: var(--accent-red);
    }
  </style>
</head>
<body>

<header>
  <div class="header-center">
    <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
    <div class="date-line">
      <?php echo date("l, d F Y"); ?> | <span data-translate="ubicacion_titulo">Ubicación</span>
    </div>
  </div>

  <div class="session-controls">
    <?php if (!isset($_SESSION["username"])): ?>
      <a href="login.php">
        <button class="btn-login" data-translate="btn_iniciarSesion">Iniciar sesión</button>
      </a>
    <?php else: ?>
      <div class="session-row">
        <span class="username"><?php echo $_SESSION["username"]; ?></span>
        <form method="post" action="logout.php" style="display:inline;">
          <button type="submit" class="btn-logout" data-translate="btn_cerrarSesion">Cerrar sesión</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</header>

<!-- BREADCRUMBS -->
<?php include('breadcrumb.php'); ?>

<main class="location-main">
  <h1 class="location-title" data-translate="ubicacion_titulo_principal">Drudge Report Headquarters</h1>
  
  <!-- Información real de Drudge Report -->
  <div class="location-address">
    <h3 data-translate="ubicacion_direccion_titulo">Drudge Report</h3>
    <p data-translate="ubicacion_ciudad"><strong>Sede:</strong> Hollywood, California, Estados Unidos</p>
    <div class="location-note" data-translate="ubicacion_nota">
      <strong>Nota:</strong> Drudge Report no tiene una oficina física abierta al público. 
      La operación se realiza desde Hollywood, CA, pero no reciben visitas.
    </div>
  </div>

  <!-- Mapa de Hollywood, California -->
  <div class="map-container">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d423284.1022231849!2d-118.4117325!3d34.0201613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c75ddc27da13%3A0xe22fdf6f254608f4!2sHollywood%2C%20Los%20%C3%81ngeles%2C%20CA%2C%20EE.%20UU.!5e0!3m2!1ses-419!2smx!4v1700000000000!5m2!1ses-419!2smx" 
        width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</main>

<div class="ticker-wrap">
  <div class="ticker">
    <span class="ticker-item" data-translate="ticker1">ÚLTIMA HORA: MERCADOS ASIÁTICOS ABREN A LA BAJA</span>
    <span class="ticker-item" data-translate="ticker2">+++ ALERTA DE TORMENTA EN LA COSTA ESTE +++</span>
    <span class="ticker-item" data-translate="ticker3">NASA LANZA NUEVA MISIÓN A MARTE ESTA TARDE</span>
    <span class="ticker-item">DRUDGE REPORT 2026 ®</span>
  </div>
</div>

<footer>
  <div class="footer-container">
    <section class="about">
      <h2 data-translate="ftr_acercaNosotros">Acerca de Nosotros</h2>
      <p data-translate="ftr_textoAcerca">
        Drudge Report reúne titulares de política, economía, cultura y más. Fundado por Matt Drudge en 1995, 
        es uno de los sitios de noticias más influyentes de Estados Unidos.
      </p>
      <a href="nosotros.php" class="btn-more" data-translate="btn_masNosotros">Ver más sobre nosotros</a>
    </section>

    <section class="subscribe">
      <h2 data-translate="ftr_suscripcion">Suscríbete</h2>
      <p data-translate="ftr_textoSuscripcion">Suscríbete para obtener noticias y actualizaciones exclusivas.</p>
      <button id="toggleSubscribe" data-translate="btn_subscripcion">Quiero recibir noticias</button>
      <div id="subscribeBox" class="subscribe-box hidden">
        <form id="subscribeForm">
          <div class="form-group">
            <label data-translate="label_nombre">Nombre:</label>
            <input type="text" id="subName" required>
          </div>
          <div class="form-group">
            <label data-translate="label_email">Correo electrónico:</label>
            <input type="email" id="subEmail" required>
          </div>
          <button type="submit" data-translate="btn_enviar">Suscribirse</button>
          <p id="subMessage"></p>
        </form>
      </div>
    </section>

  </div>
</footer>

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