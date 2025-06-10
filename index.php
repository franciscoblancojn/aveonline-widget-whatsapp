<?php
/*
Plugin Name: Aveonline widget whatsapp
Plugin URI: https://github.com/franciscoblancojn/aveonline-widget-whatsapp
Description: Widget para mostrar guias de aveonline en elementor.
Version: 1.0.3
Author: franciscoblancojn
Author URI: https://franciscoblanco.vercel.app/
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aveonline-widget-whatsapp
*/

if (!function_exists( 'is_plugin_active' ))
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

//AVWW_
define("AVWW_KEY",'AVWW');
define("AVWW_SLUG",'aveonline-widget-whatsapp');
define("AVWW_LOG",false);
define("AVWW_RUTE",'avww');
define("AVWW_DIR",plugin_dir_path( __FILE__ ));
define("AVWW_URL",plugin_dir_url(__FILE__));
define("AVWW_BASENAME",plugin_basename(__FILE__));

require_once AVWW_DIR . 'update.php';
github_updater_plugin_wordpress([
    'basename'=>AVWW_BASENAME,
    'dir'=>AVWW_DIR,
    'file'=>"index.php",
    'path_repository'=>'franciscoblancojn/aveonline-widget-whatsapp',
    'branch'=>'master',
    'token_array_split'=>[
        "g",
        "h",
        "p",
        "_",
        "G",
        "4",
        "W",
        "E",
        "W",
        "F",
        "p",
        "V",
        "U",
        "E",
        "F",
        "V",
        "x",
        "F",
        "U",
        "n",
        "b",
        "M",
        "k",
        "P",
        "R",
        "x",
        "o",
        "f",
        "t",
        "Y",
        "8",
        "z",
        "j",
        "t",
        "4",
        "E",
        "x",
        "b",
        "i",
        "9"
    ]
]);

require_once AVWW_DIR . 'src/api/_.php';
require_once AVWW_DIR . 'src/component/_.php';

function AVWW_register_AveFormWhatsapp($widgets_manager) {
    require_once AVWW_DIR . 'src/widget.php';
    $widgets_manager->register(new \Elementor\AVWW_AveFormWhatsapp());
}
add_action('elementor/widgets/register', 'AVWW_register_AveFormWhatsapp');

function AVWW_register_AveFormWhatsapp_load() {
    if (!did_action('elementor/loaded')) {
        return;
    }
}
add_action('plugins_loaded', 'AVWW_register_AveFormWhatsapp_load');

