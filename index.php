<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DRUDGE REPORT</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script> 
</head>
<body>

<header>
  <div class="search-controls">
    <input type="text" id="searchInput" placeholder="Buscar noticias..." data-translate="placeholder_buscar">
  </div>

  <div class="header-center">
    <div class="logo" data-translate="DrudgeReport">Drudge <span>Report</span></div>
    <div class="date-line">
      <?php echo date("l, d F Y"); ?> | <span data-translate="edicionGlobal">Edición Global</span>
    </div>
  </div>

<div class="session-controls">
    <?php if (!isset($_SESSION["username"])): ?>
        <a href="login.php">
            <button class="btn-login" data-translate="btn_iniciarSesion">Iniciar sesión</button>
        </a>
    <?php else: ?>
        <div class="session-row">
            <a href="perfil.php" class="username" style="text-decoration: none;"><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
            
            <?php if (isset($_SESSION["tipo"]) && $_SESSION["tipo"] === "admin"): ?>
                <a href="admin.php" style="text-decoration: none;">
                    <button class="btn-login" style="margin: 0 10px;" data-translate="admin_pad">Panel de Admin</button>
                </a>
            <?php endif; ?>

            <form method="post" action="logout.php" style="display:inline;">
                <button type="submit" class="btn-logout" data-translate="btn_cerrarSesion">Cerrar sesión</button>
            </form>
        </div>
    <?php endif; ?>
  </div>
</header>

<?php include('breadcrumb.php'); ?>

<main class="container">
    <aside class="side-col">
        <div class="link-group">
            <div class="group-title" data-translate="tit_PoliticaPoder">Política & Poder</div>
            <ul class="news-list">
                <li><a href="#" data-translate="noticia1">El Senado bloquea nueva ley de presupuesto en sesión maratónica</a></li>
                <li><a href="#" data-translate="noticia2">Escándalo en Bruselas: Filtraciones revelan acuerdos secretos</a></li>
                <li><a href="#" class="urgent-link" data-translate="noticia3">RENUNCIA INMINENTE DEL MINISTRO DE DEFENSA</a></li>
                <li><a href="#" data-translate="noticia4">Encuestas muestran caída histórica en aprobación presidencial</a></li>
            </ul>
        </div>
        
        <div class="link-group">
            <div class="group-title" data-translate="tit_Economia">Economía</div>
            <ul class="news-list">
                <li><a href="#" data-translate="eco1">Wall Street cierra en rojo por temores de inflación</a></li>
                <li><a href="#" data-translate="eco2">Crypto Crash: Bitcoin cae por debajo del soporte clave</a></li>
                <li><a href="#" data-translate="eco3">El precio del petróleo alcanza máximo anual</a></li>
            </ul>
        </div>
    </aside>

    <section class="main-story-col">
        <h1 class="main-headline"><a href="#" data-translate="tit_finDigital">¿EL FIN DE LA ERA DIGITAL?</a></h1>
        
        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80" alt="Mundo Digital" class="main-image">
        
        <div class="sub-headline">
            <a href="#" data-translate="sub_finDigital">NUEVA REGULACIÓN GLOBAL PODRÍA APAGAR LAS REDES SOCIALES TAL COMO LAS CONOCEMOS</a>
        </div>
        <p style="margin-top: 15px; font-style: italic;">
            <span data-translate="texto_finDigital">Expertos advierten sobre el impacto en la libertad de expresión...</span> <a href="#" style="color:red" data-translate="leerMas">[LEER MÁS]</a>
        </p>
        
        <hr style="margin: 20px 0; border: 0; border-top: 2px solid black;">

        <h2 class="main-headline" style="font-size: 2rem;"><a href="#" data-translate="tit_caos">CAOS EN LOS AEROPUERTOS</a></h2>
        <p data-translate="texto_caos">Fallo de sistema global deja a miles varados...</p>
    </section>

    <aside class="side-col">
        <div class="link-group">
            <div class="group-title" data-translate="tit_MediosShowbiz">Medios & Showbiz</div>
            <ul class="news-list">
                <li><a href="#" data-translate="show1">Disney anuncia pérdidas millonarias en streaming</a></li>
                <li><a href="#" data-translate="show2">La estrella pop cancela gira mundial por "agotamiento"</a></li>
                <li><a href="#" data-translate="show3">Nuevo récord de taquilla para película independiente</a></li>
            </ul>
        </div>

        <div class="link-group">
            <div class="group-title" data-translate="tit_viralExtrano">Viral / Extraño</div>
            <ul class="news-list">
                <li><a href="#" class="urgent-link" data-translate="viral1">AVISTAMIENTO DE OVNI CONFIRMADO POR PILOTOS</a></li>
                <li><a href="#" data-translate="viral2">Encuentran ciudad perdida en la selva amazónica usando LIDAR</a></li>
                <li><a href="#" data-translate="viral3">Robot de IA aprende a mentir para ganar juego de estrategia</a></li>
                <li><a href="#" data-translate="viral4">Hombre sobrevive 3 semanas perdido en el mar comiendo solo ketchup</a></li>
            </ul>
        </div>
    </aside>
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
      </section>

    <section class="subscribe">
      </section>

    <section class="location">
      <h2 data-translate="ftr_ubicacion">Ubicación</h2>
      <p><a href="ubicacion.php" data-translate="btn_ubicacion">Ver nuestra ubicación en el mapa</a></p>
    </section>

<section class="contact">
      <h2 data-translate="ftr_contacto">Contáctanos</h2>
      <p data-translate="ftr_textoContacto">Envíanos tus comentarios o reportes.</p>
      
<button id="toggleContact" class="btn-toggle" data-translate="btn_mostrar_contacto">Quiero enviar un mensaje</button>
      
      <?php 
        $claseOculta = isset($_SESSION['msg_contacto']) ? '' : 'hidden'; 
      ?>

      <div id="contactBox" class="subscribe-box <?php echo $claseOculta; ?>">
        <form action="procesar_contacto.php" method="POST">
          <div class="form-group">
            <label data-translate="label_nombre_contacto">Nombre:</label>
            <input type="text" name="nombre_contacto" required>
          </div>
          <div class="form-group">
            <label data-translate="label_email_contacto">Correo electrónico:</label>
            <input type="email" name="email_contacto" required>
          </div>
          <div class="form-group">
            <label data-translate="label_mensaje">Mensaje:</label>
            <textarea name="mensaje_contacto" rows="3" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
          </div>
          <button type="submit" name="enviar_contacto" data-translate="btn_enviar_contacto">Enviar Mensaje</button>
        </form>
        
        <?php if(isset($_SESSION['msg_contacto'])): ?>
            <p style="color: #d32f2f; margin-top: 15px; font-weight: bold; text-align: center;">
                <?php 
                    echo $_SESSION['msg_contacto']; 
                    unset($_SESSION['msg_contacto']); 
                ?>
            </p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</footer>

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