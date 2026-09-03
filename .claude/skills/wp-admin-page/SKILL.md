---
name: wp-admin-page
description: Scaffold or extend a WordPress admin screen (settings page, tab, log viewer) for this plugin using franciscoblancojn/wordpress_utils components instead of hand-rolled HTML. Use when adding a new admin page, adding a tab/section to an existing one, or migrating src/page/page.php off raw markup.
---

# Construir pantallas de admin con `wordpress_utils`

Este plugin depende de `franciscoblancojn/wordpress_utils` (vendorizada en
`libs/franciscoblancojn/wordpress_utils`, ver `docs/wordpress-utils.md` para la
referencia completa de la API) precisamente para no reinventar tabs, banners de
resultado, modales, tooltips o logs cada vez. Antes de escribir HTML/CSS/JS a
mano para una pantalla de admin, revisa si una de estas clases ya lo resuelve:

- `FWUPage` — layout con pestañas (tabs), cada una en su propio archivo.
- `FWURespond` — banner de éxito/error tras procesar un `$_POST`.
- `FWUCollapse` / `FWUModal` / `FWUTooltip` / `FWUExportImport` — piezas de UI reusables.
- `FWUSystemLog` — si lo que se necesita es una vista de logs, ya existe completa (`FWUSystemLog::init($key)` + `FWUSystemLog::add($key, $log)`), no hace falta construir nada.

## Pasos para una pantalla nueva o para añadir un tab

1. Registrar la página en `admin_menu` (patrón ya usado en `src/page/add.php`):
   ```php
   add_action('admin_menu', function () {
       add_menu_page('Título', 'Menú', 'manage_options', AVWW_KEY, 'AVWW_MI_PAGINA_VIEW');
   });
   function AVWW_MI_PAGINA_VIEW() {
       require_once AVWW_DIR . 'src/page/mi-pagina.php';
   }
   ```

2. Dentro de `src/page/mi-pagina.php`, procesar el `$_POST` primero (guardar en
   `wp_options` vía `update_option`/`get_option`, como hace `src/page/page.php`)
   y armar un array `$respond` con el resultado.

3. Renderizar con `FWUPage::render()` en vez de HTML plano:
   ```php
   use franciscoblancojn\wordpress_utils\FWUPage;
   use franciscoblancojn\wordpress_utils\FWURespond;

   FWUPage::render(
       'avww-mi-pagina',
       'Título de la página',
       [
           ['key' => 'general', 'title' => 'General'],
           ['key' => 'avanzado', 'title' => 'Avanzado'],
       ],
       AVWW_DIR . 'src/page/tabs/mi-pagina',   // carpeta con general.php, avanzado.php
       ['CONFIG' => $CONFIG, 'respond' => $respond ?? []]
   );
   ```

4. Dentro de cada tab (`general.php`, etc.), usar `FWURespond::render($respond)`
   para el banner y el resto del markup del formulario. Reusar `FWUCollapse`,
   `FWUTooltip`, `FWUModal`, `FWUExportImport` donde aplique en vez de duplicar
   CSS/JS.

5. Si la pantalla necesita mostrar peticiones/errores históricos, no construyas
   un log nuevo: usa `FWUSystemLog::add($key, [...])` para escribirlo y deja que
   `FWUSystemLog::init($key)` (ya llamado una vez en `index.php` con `AVWW_KEY`)
   siga sirviendo la vista en **Ajustes → {KEY}_LOG**.

## Migrar `src/page/page.php`

Es candidato a este patrón si se le añade una segunda sección: hoy es una tabla
HTML con un solo campo (Token) sin usar ninguna clase de la librería. Al
tocarla, conviértela siguiendo los pasos de arriba en vez de agregar más HTML
suelto al archivo existente.

## Reglas que siguen aplicando aquí

- No edites nada en `libs/franciscoblancojn/wordpress_utils` — si falta algo en
  la librería, repórtalo/pídelo, no lo parches localmente (ver `CLAUDE.md`).
- No ejecutes `git add`/`commit`/`merge`/`push` al terminar — deja los cambios
  sin stagear para que la persona desarrolladora los revise.
