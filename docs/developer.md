# README-developer.md

Project: kfd-wordpress — WordPress integration for konfidoo

Purpose

- Provide developer guidance for building, developing and debugging the WordPress plugin.
- Document local development workflows, CI/CD and packaging steps.

Requirements

- Node.js 14.x (or a compatible LTS) and npm
- Docker & Docker Compose (for a local WordPress environment)
- A code editor (WebStorm, VS Code, or similar)
- PHP and a local WordPress environment for manual testing (if not using Docker)

Project structure (short)

- `plugin/` — main plugin source
- `plugin/src/` — JavaScript, Gutenberg blocks, styles and other assets
- `plugin/build/` — built assets (output)
- `plugin/plugin.php` or `plugin/kfd-wordpress.php` — main PHP plugin file(s)
- `.github/workflows/` — CI/CD workflows (build, test, release)
- `docs/` — documentation files (this document)

Important npm scripts (see `plugin/package.json`)

- `npm install` — install dependencies
- `npm run start` — start development watcher / dev server (watch JS/blocks)
- `npm run build` — production build (prepare assets for the plugin)
- `npm run lint` — run linters (if configured)
- `npm test` — run tests (if available)

Local development (step by step)

1. Open a terminal in the project root.
2. Change into the plugin folder: `cd plugin`.
3. Install dependencies: `npm install`.
4. Start the dev watcher: `npm run start` (this typically watches and rebuilds block JS/CSS on change).
5. In a separate terminal at project root run: `docker-compose up` to start a local WordPress + database (if a Docker Compose config is provided).
6. Open the local WordPress site (commonly `http://localhost:8080` or the URL defined in `docker-compose.yaml`).
7. Make sure the plugin directory is available to the WordPress container. Options:
   - Copy the `plugin/` directory into the container's `wp-content/plugins/` directory.
   - Use a Docker volume in `docker-compose.yaml` that mounts the workspace `plugin/` into the container so changes are visible immediately.
8. Activate the plugin in WP Admin → Plugins.

Notes about development workflow

- When developing Gutenberg blocks, `npm run start` should rebuild assets on file change; the editor will show updated blocks after reload.
- Use editor sourcemaps (if configured) to debug original source files in the browser.

Build & packaging

1. From the `plugin/` folder: `npm install` and `npm run build`.
2. Confirm that production assets are created in `plugin/build/` (or other configured output folder).
3. Prepare a distribution ZIP of the plugin directory (example from the folder above the plugin directory):

```bash
zip -r kfd-wordpress.zip kfd-wordpress/
```

- The ZIP can be used to upload the plugin via WP Admin or attached to a release.

CI / Releases

- GitHub Actions (or other CI) should run the build pipeline on pushes to the main branch and can produce a ZIP artifact for releases.
- Typical CI steps: install dependencies, run lint/tests, run `npm run build`, archive plugin directory as `kfd-wordpress.zip`.

Debugging & logs

- Enable WP_DEBUG in `wp-config.php` for local debugging:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

- Check `wp-content/debug.log` for PHP errors when `WP_DEBUG_LOG` is enabled.
- Use browser DevTools for JavaScript debugging. If `npm run start` provides a dev server with hot reload, use that during block development.

Code contribution guidelines

- Create feature branches from `main`.
- Keep commits small and focused; write clear commit messages.
- Open a pull request (PR) with a description of changes and testing steps.
- Follow any configured contribution rules (e.g. linting, code style, tests).

Tests & linting

- If tests are configured, add a short test for new behavior and run `npm test` locally before PR.
- Run linters and formatters (if present) before pushing changes: `npm run lint` / `npm run format`.

Useful paths / files

- `plugin/kfd-wordpress.php` (or `plugin/plugin.php`) — plugin header and PHP entry points
- `plugin/src/blocks/` — Gutenberg block source files
- `plugin/src/admin/` — admin settings and pages
- `plugin/build/` — production-built assets
- `docker-compose.yaml` — local Docker setup (project root) — inspect to see service URLs and volumes

Troubleshooting

- Plugin not activating: check PHP errors and plugin file header; check file permissions.
- Block assets not updated: ensure `npm run start` or `npm run build` ran successfully and build output is copied to the plugin directory used by WordPress.
- API / network errors: inspect browser console and network tab; ensure CORS or network routing to konfidoo endpoints is allowed in local environment.

Further improvements / next steps (optional)

- Add unit/integration tests for critical code paths.
- Add GitHub Actions workflow to automatically run lint/tests and publish release artifacts.
- Improve developer DX by adding a Makefile or npm scripts that orchestrate `npm install`, `npm run build`, and Docker compose commands.
