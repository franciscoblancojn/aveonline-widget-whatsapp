---
name: release-version
description: Bump the plugin version consistently across index.php, package.json and README.md using the existing npm scripts, stopping before any git action. Use when asked to bump the version, prepare a release, or sync the version across files.
---

# Preparar una nueva versión (sin tocar git)

La versión del plugin vive en tres sitios que deben quedar sincronizados:
`index.php` (header `Version:`), `package.json` (`version`) y `README.md`
(línea `**Versión estable:**`). `index.php` es la fuente de verdad.

## Pasos

1. Editar manualmente el header de `index.php` con la nueva versión
   (`Version: X.Y.Z`).
2. Correr `npm run sync:version` — internamente ejecuta:
   - `sync:package` → lee la versión de `index.php` (`npm run v`, que hace
     `grep`/`sed` sobre el header) y la aplica a `package.json` con
     `npm version $VERSION --no-git-tag-version` (el flag evita que npm cree
     un commit/tag de git por sí solo).
   - `sync:readme` → actualiza la línea `**Versión estable:**` en `README.md`
     con `sed`.
3. Verificar con `git status`/`git diff` que solo cambiaron `index.php`,
   `package.json` y `README.md`, y que el número de versión coincide en los
   tres archivos.
4. **Detente ahí.** No corras `npm run push-tag` ni `npm run push` — ambos
   ejecutan `git commit`, `git tag` y `git push` internamente. Esas operaciones
   están bloqueadas para el asistente (`.claude/settings.json`) y son
   responsabilidad manual del mantenedor. Reporta a la persona desarrolladora
   que los archivos quedaron listos para que ella revise y haga el commit/tag/push.

## Qué no hacer

- No uses `git add`/`git commit`/`git merge`/`git push` en ningún paso.
- No inventes ni corrijas `scripts/bump-version.sh` (referenciado por
  `npm run push-v`) salvo que la persona desarrolladora lo pida explícitamente:
  hoy no existe en el repo y ese script tampoco es parte de este flujo.
- No toques `libs/` como parte de un release de versión — esa carpeta se
  gestiona con Composer/npm, no con el bump de versión (ver `CLAUDE.md`).
