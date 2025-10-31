# Developer README

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
- `plugin/plugin.php` — main PHP plugin file
- `.github/workflows/` — CI/CD workflows (build, test, release)
- `docs/` — documentation files (this document)

Important npm scripts (see `plugin/package.json`)

- `npm install` — install dependencies (run inside `plugin/`)
- `npm run start` — start development watcher / dev server (uses `@wordpress/scripts`)
- `npm run build` — production build (uses `@wordpress/scripts`)
- `npm run lint` — run linters (if configured)
- `npm test` — run tests (if available)

Local development (step by step)

1. Open a terminal in the project root.
2. Change into the plugin folder: `cd plugin`.
3. Install dependencies: `npm install`.
4. Start the dev watcher: `npm run start` (this typically watches and rebuilds block JS/CSS on change).
5. In a separate terminal (at project root) run: `docker-compose up` to start a local WordPress + database (if a Docker
   Compose config is provided).
6. Open the local WordPress site (commonly `http://localhost:8080` or the URL defined in `docker-compose.yaml`).
7. Make sure the plugin directory is available to the WordPress container. Options:
    - Copy the `plugin/` directory into the container's `wp-content/plugins/` directory.
    - Use a Docker volume in `docker-compose.yaml` that mounts the workspace `plugin/` into the container so changes are
      visible immediately.
8. Activate the plugin in WP Admin → Plugins.

Notes about development workflow

- When developing Gutenberg blocks, `npm run start` should rebuild assets on file change; the editor will show updated
  blocks after reload.
- Use editor sourcemaps (if configured) to debug original source files in the browser.
- If the project previously used `cgb-scripts`, use `@wordpress/scripts` (`wp-scripts`) instead; the build output is
  produced in `plugin/build/`.

Build & packaging

This section describes the recommended commands to build the plugin, package it into a ZIP and verify the produced
archive contains the PHP sources (especially `src/init.php`). Run these commands from the repository root or the
`plugin/` directory as noted.

1) Change into the plugin directory and install dependencies (only the first time or when dependencies change):

```bash
cd plugin
npm install
```

2) Run a production build:

```bash
npm run build
```

The build creates production artifacts (for example in `plugin/build/`).

3) Create the ZIP

The project contains a `zip.sh` in the `plugin/` folder. The script should exclude only large/irrelevant folders such as
`node_modules` and the `.git` directory. Example content of `plugin/zip.sh`:

```bash
zip -r ../kfd-wordpress.zip . -x "node_modules/*" ".git/*"
```

Run the script:

```bash
./zip.sh
```

4) Verify the ZIP file and make sure `src/init.php` is included

It is important that the PHP source files (for example `src/init.php`) are included in the ZIP — otherwise activating
the plugin can cause a fatal error. Check the produced ZIP file:

```bash
# list archive contents (from plugin/ or repo root)
unzip -l ../kfd-wordpress.zip

# or check specifically for src/init.php
unzip -l ../kfd-wordpress.zip | grep 'src/init.php' || echo 'ERROR: src/init.php is missing in the ZIP'
```

If `src/init.php` is missing: adjust `zip.sh` so `src/` is not excluded and re-create the ZIP.

5) Upload & activate the plugin

- WP Admin Upload (easiest):
  Plugins → Add New → Upload Plugin → Choose `kfd-wordpress.zip` → Install Now → Activate.

- SCP/SSH (example, adjust paths and user):

```bash
# Upload
scp ../kfd-wordpress.zip user@server:/tmp/
# On the server: unpack into the WP plugins directory and set permissions (example path)
ssh user@server 'cd /var/www/html/wp-content/plugins && unzip -o /tmp/kfd-wordpress.zip && chown -R www-data:www-data kfd-wordpress && find kfd-wordpress -type d -exec chmod 755 {} \; && find kfd-wordpress -type f -exec chmod 644 {} \;'
```

Note: adjust `user`, paths and `www-data:www-data` to match the target system.

6) Post-upload — activation test

- Activate the plugin in WP Admin. If something goes wrong, check admin notices; after the protective checks in
  `plugin.php`, missing files now produce admin notices instead of fatal PHP errors.
- If errors occur: enable `WP_DEBUG` and `WP_DEBUG_LOG` and check `wp-content/debug.log`:

```php
// in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Upload / deployment tips

- Plugin directory name: If you previously deployed a version with a different folder name (for example
  `kfd-wordpress-1`), remove or rename the old folder to avoid conflicts.
- File permissions: Ensure files are readable by the web server (typically files 644 and directories 755).

CI / releases

- CI pipelines (GitHub Actions or similar) should:
    - run `npm install`
    - run `npm run build`
    - create a ZIP archive and attach it as a release artifact

Troubleshooting

- Plugin will not activate: confirm `src/init.php` exists in the plugin folder; check admin notices; inspect
  `wp-content/debug.log`.
- Fatal error due to missing file: older packaging steps may have accidentally excluded `src/` — ensure `zip.sh`
  excludes only `node_modules` and `.git` unless explicitly required.
- Block assets not updated: confirm `npm run start` or `npm run build` completed successfully and built assets are in
  the plugin folder used by WordPress.

Further improvements / next steps (optional)

- Add unit/integration tests for critical code paths.
- Add a GitHub Actions workflow to run lint/tests and publish release artifacts.
- Improve developer DX by adding a Makefile or npm script that orchestrates `npm install`, `npm run build` and Docker
  Compose commands.

Useful paths / files

- `plugin/plugin.php` — plugin header and PHP entry points
- `plugin/src/` — Gutenberg block source files and PHP helpers
- `plugin/build/` — production-built assets
- `docker-compose.yaml` — local Docker setup (project root) — inspect to see service URLs and volumes

If you want, I can also:

- produce the release ZIP and keep it in the repo root,
- add a small GitHub Actions workflow that builds and produces the ZIP automatically.
