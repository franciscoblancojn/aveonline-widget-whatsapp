# CLAUDE.md — Aveonline Widget WhatsApp

Contexto de trabajo para Claude Code en este repositorio. Léelo antes de tocar código.

## Qué es este proyecto

Plugin de WordPress (no un tema, no un plugin standalone genérico) que añade un widget
de **Elementor** con un botón flotante de WhatsApp para **Aveonline**. Captura datos de
contacto y los envía a **AveChat (LucidBot)** y a la API de **Aveonline** (creación de
leads). Ver `README.md` para la documentación funcional completa (instalación,
configuración, endpoints REST, detección de campañas).

Convenciones de código: todo símbolo global (funciones, constantes) usa el prefijo
`AVWW_`. El slug del plugin es `aveonline-widget-whatsapp`. PHP objetivo: 7.4.33
(ver `composer.json` → `config.platform.php`), por eso no se usan features de PHP 8+.

## Reglas de git — IMPORTANTE

**Nunca ejecutes `git add`, `git commit`, `git merge` ni `git push` en este repo.**
Están bloqueados a nivel de configuración en `.claude/settings.json`
(`permissions.deny`), pero aunque no lo estuvieran: estas operaciones son manuales,
las hace la persona desarrolladora.

Lo que sí puedes hacer libremente: `git status`, `git diff`, `git log`, `git show`,
crear/editar archivos, y correr los scripts de build.

Cuando termines un cambio: deja el working tree con las modificaciones sin stagear,
resume qué cambiaste y qué falta, y espera a que la persona revise y decida si
commitea/pushea. Nunca uses `npm run push-v`, `npm run push-tag` ni `npm run push`
(ver `package.json`) — los tres terminan haciendo `git commit`/`git tag`/`git push`
internamente. El `deny` de `.claude/settings.json` solo intercepta invocaciones
directas de `git ...`; una llamada a `npm run push-tag` no la detecta porque el
comando visible es `npm`, no `git`. Por eso esta regla es de comportamiento, no
solo de configuración: nunca ejecutes esos tres scripts, sin importar si la
config técnicamente los dejaría pasar.

## Gestión de dependencias — usar SIEMPRE el gestor de paquetes

**Nunca edites a mano nada dentro de `libs/`.** Esa carpeta es el equivalente a
`vendor/` de Composer, pero renombrada: el flujo de build (`npm run install` /
`npm run update`, ver `package.json`) corre `composer install`, limpia archivos de
desarrollo (`.git`, `composer.json`, `README.md` de las dependencias, etc.) y luego
mueve `vendor/` → `libs/` (`mv-post`). Es contenido generado, no fuente editable.

Para cambiar una dependencia:
- Añadir/actualizar una librería → edita `composer.json` (sección `require`) y
  corre `composer update <paquete>` o `npm run update`.
- Reinstalar limpio → `npm run update` (borra `libs/`, reinstala, vuelve a mover).
- Solo regenerar autoload sin bajar nada nuevo → `composer install --no-dev
  --optimize-autoloader` seguido de `npm run rm-post && npm run mv-post`, o
  simplemente `npm run install`.

No hay dependencias JS de runtime (no hay `node_modules` para el plugin en sí);
`package.json` solo orquesta scripts de build/versión/release, no un bundle.

### La librería `franciscoblancojn/wordpress_utils`

Es la librería de UI/infra para admin de WordPress que este plugin (y otros del
mismo autor) usan. Ahora mismo en `composer.json`: `"^1"` (vendorizada en
`libs/franciscoblancojn/wordpress_utils`, v1.3.0 al momento de escribir esto).

**Para construir cualquier interfaz de administración, sistema de logs, o
actualizador automático, usa las clases de esta librería en vez de reinventarlas.**
Referencia completa de la API en `docs/wordpress-utils.md`. Resumen:

| Clase | Para qué |
|---|---|
| `FWUUpdate` | Auto-actualización del plugin desde un release de GitHub |
| `FWUSystemLog` | Página de logs en el admin (`Ajustes → {KEY}_LOG`) + helpers `add()`/`get()` |
| `FWUPage` | Página de admin con pestañas (tabs) |
| `FWUComponent` | Clase base abstracta para componentes con `html()`/`css()`/`js()` |
| `FWURespond` | Banner de éxito/error tras guardar un formulario de admin |
| `FWUModal` | Modal genérico |
| `FWUCollapse` | Bloque colapsable (`<details>`) |
| `FWUTooltip` | Tooltip con ícono de info |
| `FWUExportImport` | Modal de exportar/importar JSON vía AJAX |

Nota: `src/page/page.php` y `src/page/add.php` de este plugin todavía NO usan estos
componentes (son HTML plano). Si tocas esa pantalla o añades una nueva, migra al
patrón de la librería (`FWUPage::render()` + `FWURespond::render()`) en vez de
repetir markup a mano — así queda consistente con el resto de plugins de
franciscoblancojn que ya la usan.

## Estructura relevante

```
index.php                  # Bootstrap: constantes AVWW_*, FWUUpdate::init(), FWUSystemLog::init()
update.php                 # Updater standalone antiguo, YA NO se usa (index.php usa FWUUpdate). No borrar sin confirmar con el usuario.
src/api/sendContact.php     # Endpoint REST + integración AveChat/Aveonline
src/component/*.php         # Widget de Elementor (frontend)
src/page/*.php              # Página de admin (Token AveChat) — candidata a migrar a FWUPage
libs/                       # NO EDITAR A MANO — generado por composer/npm (ver arriba)
```

## Versionado y release

La versión vive en 3 sitios que deben quedar sincronizados: `index.php` (header
`Version:`), `package.json` (`version`) y `README.md` (línea `**Versión
estable:**`). `index.php` es la fuente de verdad.

- `npm run sync:version` (= `sync:package` + `sync:readme`) propaga la versión de
  `index.php` a `package.json` y `README.md`. Seguro de correr — no toca git.
- `scripts/bump-version.sh` (invocado como `npm run push-v -- major|minor|patch`)
  incrementa la versión en `index.php` y al final intenta `npm run push-tag`.
- `npm run push-tag` hace `sync:version` **y además** `git add . && git commit &&
  git tag && git push` (dos remotos en el caso de `npm run push`). Esto es
  responsabilidad manual del mantenedor: si el usuario corre `push-v`/`push-tag`
  él mismo en su terminal está fuera del alcance de `.claude/settings.json` (esa
  config solo bloquea las llamadas de Claude a la herramienta Bash), pero **tú
  nunca debes invocar `push-v` ni `push-tag`** — solo `sync:version` si te piden
  sincronizar versión sin publicar.
