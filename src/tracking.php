<?php

if (!defined('ABSPATH')) exit;

/**
 * ============================================================
 * URL DE LLEGADA
 * ============================================================
 *
 * Guarda en localStorage la URL con la que el usuario entra al sitio,
 * para que el widget arme el codigo de origen aunque navegue a otras
 * paginas antes de escribir. Solo la reemplaza una url con utm_* o con un
 * click id de anuncio (gclid, fbclid, ttclid, gbraid, wbraid).
 *
 * Se imprime en el footer de todo el sitio y tambien dentro del JS del
 * formulario (por si el tema no llama wp_footer); correrlo dos veces no
 * tiene efecto extra.
 *
 */

function AVWW_tracking_landing_url_js()
{
    return '
        (function() {
            try {
                const key = "url_register_whatsapp";
                const href = window.location.href;
                const clickIds = ["gclid", "fbclid", "ttclid", "gbraid", "wbraid"];
                const hasTracking = Array.from(new URL(href).searchParams.keys())
                    .map((k) => k.toLowerCase())
                    .some((k) => k.startsWith("utm_") || clickIds.includes(k));
                if (hasTracking || !localStorage.getItem(key)) {
                    localStorage.setItem(key, href);
                }
            } catch (e) {}
        })();
    ';
}

function AVWW_tracking_save_landing_url()
{
    echo '<script>' . AVWW_tracking_landing_url_js() . '</script>';
}
add_action('wp_footer', 'AVWW_tracking_save_landing_url', 1);
