<?php
// Función para generar breadcrumbs dinámicos con rutas anidadas
function getBreadcrumbs() {
    $current_page = basename($_SERVER['PHP_SELF']);
    $breadcrumbs = [];
    
    // Siempre mostramos Inicio
    $breadcrumbs[] = ['name' => 'Inicio', 'url' => 'index.php'];
    
    // Definición de páginas y sus jerarquías
    $pages = [
        'login.php' => [
            'name' => 'Iniciar Sesión',
            'parent' => null
        ],
        'register.php' => [
            'name' => 'Registro',
            'parent' => null
        ],
        'recover.php' => [
            'name' => 'Recuperar Contraseña',
            'parent' => null
        ],
        'reset.php' => [
            'name' => 'Restablecer Contraseña',
            'parent' => 'recover.php'
        ],
        'twofactor.php' => [
            'name' => 'Verificación 2FA',
            'parent' => 'login.php'
        ],
        'nosotros.php' => [
            'name' => 'Acerca de Nosotros',
            'parent' => null
        ],
        'ubicacion.php' => [
            'name' => 'Ubicación',
            'parent' => null
        ],
        'perfil.php' => [
            'name' => 'Mi Perfil',
            'parent' => null
        ],
        'carrito.php' => [
            'name' => 'Mi Carrito',
            'parent' => 'perfil.php'
        ],
        'suscripciones.php' => [
            'name' => 'Suscripciones',
            'parent' => 'perfil.php'
        ],
        'factura.php' => [
            'name' => 'Facturación',
            'parent' => 'perfil.php'
        ]
    ];
    
    // Si es una página conocida
    if (isset($pages[$current_page])) {
        $page = $pages[$current_page];
        
        // Si tiene padre y ese padre existe, agregar el padre primero
        if ($page['parent'] && isset($pages[$page['parent']])) {
            $parent = $pages[$page['parent']];
            $breadcrumbs[] = [
                'name' => $parent['name'],
                'url' => $page['parent']
            ];
        }
        
        // Agregar página actual
        $breadcrumbs[] = [
            'name' => $page['name'],
            'url' => ''
        ];
    }
    
    return $breadcrumbs;
}

$breadcrumbs = getBreadcrumbs();
?>

<div class="breadcrumbs">
    <div class="breadcrumbs-container">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <?php if ($index > 0): ?>
                <span class="separator">›</span>
            <?php endif; ?>
            
            <?php if (!empty($crumb['url'])): ?>
                <a href="<?php echo $crumb['url']; ?>" class="breadcrumb-link"><?php echo $crumb['name']; ?></a>
            <?php else: ?>
                <span class="breadcrumb-current"><?php echo $crumb['name']; ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>