---
name: release-version
description: Bump the plugin version consistently across index.php, package.json and README.md using the existing npm scripts, stopping before any git action. Use when asked to bump the version, prepare a release, or sync the version across files.
---

# Preparar una nueva versión (sin tocar git)

La versión del plugin vive en tres sitios que deben quedar sincronizados:
`index.php` (header `Version:`), `package.json` (`version`) y `README.md`
(línea `**Versión estable:**`). `index.php` es la fuente de verdad.

Existe `scripts/bump-version.sh` (`npm run push-v -- major|minor|patch`), pero
**tú (el asistente) nunca lo invoques**: al terminar de bumpear la versión llama
internamente a `npm run push-tag`, que hace `git add && git commit && git tag &&
git push`. Ese script es para que lo corra la persona desarrolladora en su propia
terminal, no para el asistente.

## Pasos (lo que sí te corresponde a ti)

1. Editar manualmente el header de `index.php` con la nueva versión
   (`Version: X.Y.Z`) — replica a mano lo que hace la sección de bump de
   `scripts/bump-version.sh` (incrementar major/minor/patch), pero sin llamar
   al script.
2. Correr `npm run sync:version` — internamente ejecuta:
   - `sync:package` → lee la versión de `index.php` (`npm run v`, que hace
     `grep`/`sed` sobre el header) y la aplica a `package.json` con
     `npm version $VERSION --no-git-tag-version` (el flag evita que npm cree
     un commit/tag de git por sí solo).
   - `sync:readme` → actualiza la línea `**Versión estable:**` en `README.md`
     con `sed`. (El patrón busca literalmente `**Versión estable:**` — si
     alguna vez cambia ese texto en el README, hay que actualizar también el
     sed de `sync:readme` en `package.json`, o dejará de sincronizar en
     silencio, como pasó antes de que se corrigiera.)
3. Verificar con `git status`/`git diff` que solo cambiaron `index.php`,
   `package.json` y `README.md`, y que el número de versión coincide en los
   tres archivos.
4. **Detente ahí.** No corras `npm run push-v`, `npm run push-tag` ni
   `npm run push` — los tres terminan ejecutando `git commit`/`git tag`/
   `git push` internamente. Esas operaciones están bloqueadas para el
   asistente (`.claude/settings.json` bloquea las llamadas directas a
   `git add`/`commit`/`merge`/`push`, pero no una llamada indirecta vía un
   script npm — por eso la regla real es de comportamiento, no solo de
   configuración: nunca invoques esos tres scripts). Reporta a la persona
   desarrolladora que los archivos quedaron listos para que ella corra
   `push-v`/`push-tag` o haga el commit/tag/push manualmente.

## Qué no hacer

- No uses `git add`/`git commit`/`git merge`/`git push` en ningún paso, ni
  directo ni a través de `npm run push-v` / `push-tag` / `push`.
- No toques `libs/` como parte de un release de versión — esa carpeta se
  gestiona con Composer/npm, no con el bump de versión (ver `CLAUDE.md`).
