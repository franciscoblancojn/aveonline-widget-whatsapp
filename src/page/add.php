<?php

// use franciscoblancojn\wordpress_utils\FWUSystemLog;
// 1. Crear menú en el admin
add_action('admin_menu', function () {
    add_menu_page(
        'Aveonline Whatsapp Configuración', // Título página
        'Aveonline Whatsapp',              // Nombre en menú
        'manage_options',        // Permisos
        AVWW_KEY,      // Slug
        'AVWW_PAGE_VIEW'  // Callback
    );
});

// 2. Página HTML
function AVWW_PAGE_VIEW()
{
    require_once AVWW_DIR . 'src/page/page.php';
}
