<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Misión, Visión y FODA - Drudge Report</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
  <style>
    /* Estilos para la página nosotros - SIN FONDOS FORZADOS */
    .nosotros-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 40px;
      background: transparent;
    }
    
    /* Sección de información real de Drudge Report */
    .drudge-info {
      text-align: center;
      margin-bottom: 60px;
      padding: 40px 20px;
      background: transparent;
    }
    
    .drudge-info h1 {
      font-family: 'Oswald', sans-serif;
      font-size: 3rem;
      color: var(--accent-red);
      margin-bottom: 20px;
    }
    
    .info-stats {
      display: flex;
      justify-content: center;
      gap: 50px;
      flex-wrap: wrap;
      margin: 30px 0;
    }
    
    .stat {
      text-align: center;
    }
    
    .stat-number {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem;
      font-weight: bold;
      color: var(--accent-red);
    }
    
    .stat-label {
      font-size: 0.85rem;
      color: var(--text-gray);
    }
    
    .drudge-info p {
      max-width: 700px;
      margin: 20px auto 0;
      color: var(--text-black);
      line-height: 1.6;
    }
    
    /* Misión y Visión */
    .mv-section {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      margin-bottom: 60px;
      background: transparent;
    }
    
    .mv-section h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 2.5rem;
      color: var(--accent-red);
      margin-bottom: 25px;
      text-transform: uppercase;
    }
    
    .mv-section ul {
      list-style: none;
      padding-left: 0;
    }
    
    .mv-section li {
      margin-bottom: 15px;
      padding-left: 25px;
      position: relative;
      font-size: 1.1rem;
      line-height: 1.5;
      color: var(--text-black);
    }
    
    .mv-section li::before {
      content: "▹";
      color: var(--accent-red);
      position: absolute;
      left: 0;
    }
    
    /* FODA */
    .foda-section h2 {
      text-align: center;
      font-family: 'Oswald', sans-serif;
      font-size: 2.5rem;
      margin-bottom: 40px;
      color: var(--accent-red);
      text-transform: uppercase;
    }
    
    .foda-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 30px;
    }
    
    .foda-card h3 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.5rem;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--accent-red);
      color: var(--text-black);
    }
    
    .foda-card ul {
      list-style: none;
      padding-left: 0;
    }
    
    .foda-card li {
      margin-bottom: 12px;
      padding-left: 24px;
      position: relative;
      line-height: 1.5;
      color: var(--text-black);
    }
    
    .fortalezas li::before {
      content: "✓";
      color: var(--accent-red);
      position: absolute;
      left: 0;
    }
    
    .oportunidades li::before {
      content: "→";
      color: var(--accent-red);
      position: absolute;
      left: 0;
    }
    
    .debilidades li::before {
      content: "•";
      color: var(--accent-red);
      position: absolute;
      left: 0;
      font-size: 1.2rem;
    }
    
    .amenazas li::before {
      content: "!";
      color: var(--accent-red);
      position: absolute;
      left: 0;
      font-weight: bold;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
      .foda-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .nosotros-container {
        padding: 20px;
      }
    }
    
    @media (max-width: 768px) {
      .mv-section {
        grid-template-columns: 1fr;
        gap: 40px;
      }
      .foda-grid {
        grid-template-columns: 1fr;
      }
      .info-stats {
        gap: 30px;
      }
      .drudge-info h1 {
        font-size: 2rem;
      }
      .stat-number {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="header-center">
    <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
    <div class="date-line">
      <?php echo date("l, d F Y"); ?> | <span data-translate="nosotros_titulo">Acerca de Nosotros</span>
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
      <?php if (!isset($_SESSION["greeted"])): ?>
        <span class="greeting">Hola</span>
        <?php $_SESSION["greeted"] = true; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</header>

<!-- BREADCRUMBS -->
<?php include('breadcrumb.php'); ?>

<main class="nosotros-container">
  
  <!-- Información real de Drudge Report -->
  <div class="drudge-info">
    <h1 data-translate="drudge_titulo">Drudge Report</h1>
    <div class="info-stats">
      <div class="stat">
        <div class="stat-number" data-translate="drudge_fundador_num">Matt Drudge</div>
        <div class="stat-label" data-translate="drudge_fundador_label">Fundador</div>
      </div>
      <div class="stat">
        <div class="stat-number" data-translate="drudge_ano_num">1995</div>
        <div class="stat-label" data-translate="drudge_ano_label">Año de fundación</div>
      </div>
      <div class="stat">
        <div class="stat-number" data-translate="drudge_trafico_num">20M+</div>
        <div class="stat-label" data-translate="drudge_trafico_label">Visitas mensuales</div>
      </div>
      <div class="stat">
        <div class="stat-number" data-translate="drudge_sede_num">Hollywood, CA</div>
        <div class="stat-label" data-translate="drudge_sede_label">Sede</div>
      </div>
    </div>
    <p data-translate="drudge_descripcion">Uno de los sitios de noticias más influyentes de Estados Unidos, pionero en periodismo digital desde 1995. Conocido por su estilo único y su capacidad para romper noticias de última hora.</p>
  </div>

  <!-- Misión y Visión -->
  <div class="mv-section">
    <div class="mision" style="background: transparent;">
      <h2 data-translate="nosotros_mision_titulo">Misión</h2>
      <ul>
        <li data-translate="nosotros_mision_1">Informar con rapidez y claridad.</li>
        <li data-translate="nosotros_mision_2">Garantizar objetividad y credibilidad.</li>
        <li data-translate="nosotros_mision_3">Acercar la actualidad a todos los públicos.</li>
      </ul>
    </div>
    <div class="vision" style="background: transparent;">
      <h2 data-translate="nosotros_vision_titulo">Visión</h2>
      <ul>
        <li data-translate="nosotros_vision_1">Ser referente global en noticias digitales.</li>
        <li data-translate="nosotros_vision_2">Mantener independencia periodística.</li>
        <li data-translate="nosotros_vision_3">Innovar en formatos y plataformas.</li>
      </ul>
    </div>
  </div>

  <!-- FODA -->
  <div class="foda-section">
    <h2 data-translate="nosotros_foda_titulo">Análisis FODA</h2>
    <div class="foda-grid">
      <div class="foda-card fortalezas">
        <h3 data-translate="nosotros_fortalezas">Fortalezas</h3>
        <ul>
          <li data-translate="nosotros_fortalezas_1">Equipo ágil y comprometido.</li>
          <li data-translate="nosotros_fortalezas_2">Credibilidad consolidada.</li>
          <li data-translate="nosotros_fortalezas_3">Estilo único y reconocible.</li>
        </ul>
      </div>
      <div class="foda-card oportunidades">
        <h3 data-translate="nosotros_oportunidades">Oportunidades</h3>
        <ul>
          <li data-translate="nosotros_oportunidades_1">Expansión digital global.</li>
          <li data-translate="nosotros_oportunidades_2">Nuevas audiencias jóvenes.</li>
          <li data-translate="nosotros_oportunidades_3">Alianzas estratégicas con medios.</li>
        </ul>
      </div>
      <div class="foda-card debilidades">
        <h3 data-translate="nosotros_debilidades">Debilidades</h3>
        <ul>
          <li data-translate="nosotros_debilidades_1">Dependencia tecnológica.</li>
          <li data-translate="nosotros_debilidades_2">Recursos financieros limitados.</li>
          <li data-translate="nosotros_debilidades_3">Falta de diversificación.</li>
        </ul>
      </div>
      <div class="foda-card amenazas">
        <h3 data-translate="nosotros_amenazas">Amenazas</h3>
        <ul>
          <li data-translate="nosotros_amenazas_1">Competencia global intensa.</li>
          <li data-translate="nosotros_amenazas_2">Desinformación en redes sociales.</li>
          <li data-translate="nosotros_amenazas_3">Cambios regulatorios restrictivos.</li>
        </ul>
      </div>
    </div>
  </div>
</main>

<footer>
  <div class="footer-container">

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

    <section class="location">
      <h2 data-translate="ftr_ubicacion">Ubicación</h2>
      <p><a href="ubicacion.php" data-translate="btn_ubicacion">Ver nuestra ubicación en el mapa</a></p>
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