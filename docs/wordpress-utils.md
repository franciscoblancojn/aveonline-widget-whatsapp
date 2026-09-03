# `franciscoblancojn/wordpress_utils` — referencia de uso

Librería vendorizada en `libs/franciscoblancojn/wordpress_utils` (composer,
namespace `franciscoblancojn\wordpress_utils`, versión declarada en
`composer.json` como `"^1"`, `v1.3.0` al escribir esto). No tiene README propio en
el paquete, de ahí este documento.

**No edites los archivos de `libs/` directamente.** Si necesitas un cambio en la
librería, se hace en su propio repositorio (`franciscoblancojn/wordpress_utils`)
y aquí solo se actualiza la versión vía `composer update` (ver `CLAUDE.md`).

Todas las clases están guardadas detrás de un `class_exists(...) && function_exists(...)`
para que la librería no rompa fuera de un entorno WordPress cargado — así que solo
funcionan una vez que WordPress (y, en algunos casos, el admin) están inicializados.

## `FWUComponent` (clase base abstracta)

Contrato para cualquier "componente" con HTML + CSS + JS que se auto-inyecta una
sola vez por request:

```php
abstract class FWUComponent {
    abstract public static function html(...$args): string;
    abstract public static function css(): string;
    abstract public static function js(): string;

    public static function render(...$args): void; // echo html() + (css()+js() solo la 1a vez)
    public static function reset(): void;           // olvida que ya se renderizó css/js
}
```

Todas las clases de esta tabla (excepto `FWUUpdate` y `FWUSystemLog`, que son
utilidades de admin sin HTML reusable) extienden `FWUComponent`.

## `FWUUpdate` — auto-actualización desde GitHub

Ya está integrado en `index.php`. Comprueba `site_transient_update_plugins` contra
el último *release* de GitHub (API `.../releases/latest`) solo cuando se visita
`wp-admin/plugins.php` o la pantalla de actualización, con caché de 1 minuto vía
transient para no golpear el rate limit de la API de GitHub.

```php
use franciscoblancojn\wordpress_utils\FWUUpdate;

FWUUpdate::init([
    'basename' => AVWW_BASENAME,                 // plugin_basename(__FILE__)
    'dir' => AVWW_DIR,                           // plugin_dir_path(__FILE__)
    'file' => 'index.php',
    'path_repository' => 'usuario/repo',         // owner/repo en GitHub
    'branch' => 'master',
    'token_array_split' => [ /* char por char del PAT de GitHub */ ],
]);
```

El token se guarda **partido carácter por carácter** en un array y se reconstruye
con `join('', ...)` — es ofuscación superficial, no seguridad real; el repo debe
seguir siendo privado/controlado si el token tiene permisos sensibles. No
inventes ni cambies el token existente sin que te lo pida la persona
desarrolladora explícitamente.

`update.php` en la raíz de este plugin es una implementación standalone
equivalente (más antigua) que **ya no se usa** — `index.php` usa la clase de la
librería. No la dupliques ni la actives de nuevo sin confirmar con el usuario.

## `FWUSystemLog` — página de logs en el admin

Ya integrado en `index.php` con `FWUSystemLog::init(AVWW_KEY)`. Añade:
- Un enlace `{KEY}_LOG` en la admin bar.
- Una página en **Ajustes → {KEY}_LOG** con los logs agrupados por `type`,
  colapsables, con botón de copiar JSON y botón "Borrar Log".

Para escribir un log desde cualquier parte del código:

```php
use franciscoblancojn\wordpress_utils\FWUSystemLog;

FWUSystemLog::add('AVWW', [
    'type' => 'send_contact_error',   // agrupa entradas por tipo en la UI
    'message' => $e->getMessage(),
    'payload' => $data,
    'time' => current_time('mysql'),
]);
```

Se guarda en `wp_options` (clave `{KEY}_LOG_KEY`, por defecto `AVWW_LOG_KEY`) como
JSON, limitado a las últimas `{KEY}_LOG_COUNT` entradas por tipo (constante, por
defecto 100). Puedes sobreescribir estos valores definiendo antes las constantes
`AVWW_LOG_KEY` / `AVWW_LOG_COUNT` / `AVWW_LOG` (ver `FWUSystemLog::keys()`).

`AVWW_LOG` está definida como `false` en `index.php` — revisa ese flag si vas a
condicionar logging nuevo a esa constante.

## `FWUPage` — página de admin con pestañas

Extiende `FWUComponent`. Pensada para pantallas de configuración con varias
secciones (tabs), cada una en su propio archivo PHP:

```php
use franciscoblancojn\wordpress_utils\FWUPage;

function AVWW_PAGE_VIEW() {
    FWUPage::render(
        'avww-settings',                 // pageKey (id del contenedor + prefijo JS)
        'Aveonline Whatsapp',             // título <h1>
        [                                  // tabs
            ['key' => 'general', 'title' => 'General'],
            ['key' => 'avanzado', 'title' => 'Avanzado'],
        ],
        AVWW_DIR . 'src/page/tabs',       // carpeta con general.php / avanzado.php
        ['CONFIG' => $CONFIG]             // datos extraídos con extract() en cada tab
    );
}
```

Cada tab es un archivo `{key}.php` dentro de `sectionsDir` que se incluye con
`require` (recibe las variables de `$data` ya extraídas). El JS de `FWUPage`
maneja el cambio de tab, el hash de la URL (`#tag-{key}`) y añade una clase
`fwue-loader` (spinner) a los botones `[type=submit]` al hacer submit.

`src/page/page.php` de este plugin **no usa `FWUPage` todavía** — es una tabla
HTML plana con un solo campo (Token). Si se le añaden más secciones, es el
momento de migrarla a este patrón en vez de crecer el HTML a mano.

## `FWURespond` — banner de resultado tras guardar

Extiende `FWUComponent`. Pinta un mensaje sticky de éxito/error arriba del
contenido, típicamente después de procesar un `$_POST`:

```php
use franciscoblancojn\wordpress_utils\FWURespond;

if (isset($_POST['save'])) {
    update_option(AVWW_KEY, $_POST);
    $respond = ['status' => 'ok', 'message' => 'Guardado correctamente.'];
}
// ...
FWURespond::render($respond ?? []);
```

`$respond['status']` es `'ok'` o cualquier otro valor (pinta como error). Si
`$respond['data']['url']` viene informado y `status === 'ok'`, añade un botón "Ver".
Si `$respond['data']['post_id']` existe, antepone `get_the_title($post_id) ⇒ `.

## `FWUModal` — modal genérico

```php
use franciscoblancojn\wordpress_utils\FWUModal;

echo FWUModal::html('mi-modal', 'Título', '<p>Contenido HTML</p>');
```

Se abre/cierra con las funciones JS globales `fwueOpenModal(id)` /
`fwueCloseModal(id)` (se cierra también al hacer click fuera del contenido).

## `FWUCollapse` — bloque colapsable

```php
use franciscoblancojn\wordpress_utils\FWUCollapse;

echo FWUCollapse::html('Ver detalles', '<p>Contenido</p>', $open = false);
```

Es un `<details>`/`<summary>` estilizado, sin JS propio.

## `FWUTooltip` — tooltip de ayuda

```php
use franciscoblancojn\wordpress_utils\FWUTooltip;

echo FWUTooltip::html('Token', 'Lo entrega AveChat al activar tu cuenta.');
```

## `FWUExportImport` — exportar/importar JSON vía AJAX

Combina un modal (`FWUModal`) con un formulario de import (textarea + file input)
y botones que llaman a acciones AJAX de WordPress (`admin-ajax.php`, vía la
constante global `ajaxurl`):

```php
use franciscoblancojn\wordpress_utils\FWUExportImport;

// Botón que dispara la descarga de un JSON (acción AJAX + payload fijo)
echo FWUExportImport::exportButtonHtml('avww_export', ['key' => AVWW_KEY], 'avww-config.json');

// Botón que abre el modal de importación
echo FWUExportImport::importButtonHtml('avww-import-modal');

// El modal en sí (acción AJAX que recibirá el campo `data` con el JSON pegado/cargado)
echo FWUExportImport::html('avww-import-modal', 'Importar configuración', 'avww_import', ['key' => AVWW_KEY]);
```

Las acciones AJAX (`avww_export`, `avww_import` en el ejemplo) hay que registrarlas
tú mismo con `add_action('wp_ajax_{action}', ...)`; la librería solo pone el
transporte (fetch a `ajaxurl`, `FormData`, descarga de blob) y la UI. El handler
de import debe responder con `wp_send_json_success(['message' => '...'])` o
`wp_send_json_error(['message' => '...'])`.
