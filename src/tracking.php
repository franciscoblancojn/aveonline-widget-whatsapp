<?php

if (!defined('ABSPATH')) exit;

/**
 * ============================================================
 * URL DE LLEGADA
 * ============================================================
 *
 * Guarda en localStorage la URL con la que el usuario entra al sitio,
 * para que el widget arme el codigo de origen aunque navegue a otras
 * paginas antes de escribir. Una pagina sin UTM no pisa la URL guardada.
 *
 */

function AVWW_tracking_save_landing_url()
{
?>
    <script>
        (function() {
            try {
                const key = "url_register_whatsapp";
                const href = window.location.href;
                const hasUtm = Array.from(new URL(href).searchParams.keys())
                    .some((k) => k.toLowerCase().startsWith("utm_"));
                if (hasUtm || !localStorage.getItem(key)) {
                    localStorage.setItem(key, href);
                }
            } catch (e) {}
        })();
    </script>
<?php
}
add_action('wp_head', 'AVWW_tracking_save_landing_url', 1);
