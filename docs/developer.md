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
6. Open the local WordPress site (commonly `http://localhost:8180` or the URL defined in `docker-compose.yaml`).
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

This section describes the recommended commands to build the plugin and prepare the `plugin/build/package` folder that
is used by CI. Run these commands from the repository root or the `plugin/` directory as noted.

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

3) Prepare `plugin/build/package` (local / CI)

Instead of creating a ZIP locally, copy the necessary files into `plugin/build/package`. This mirrors what the CI
workflow does and avoids nested ZIPs or accidental inclusion of development files.

Example (run from `plugin/`):

```bash
# clean and create package folder
rm -rf build/package && mkdir -p build/package

# copy repository files into build/package excluding development files
rsync -a --delete \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='build' \
  --exclude='package.json' \
  --exclude='package-lock.json' \
  --exclude='*.zip' \
  --exclude='**/*.scss' \
  ./ build/package/
```

4) Verify the package contains the plugin entry (important for activation)

```bash
# from repo root
if [ ! -f plugin/build/package/src/init.php ]; then
  echo 'ERROR: plugin/build/package/src/init.php is missing in the build' && exit 1
fi
```

5) Upload & activate the plugin (local option)

If you need to upload the plugin via the WP admin UI, create a ZIP from `plugin/build/package` before uploading:

```bash
# from plugin/ or repo root
cd plugin/build && zip -r ../kfd-wordpress.zip package
# then use WP Admin → Plugins → Add New → Upload Plugin → kfd-wordpress.zip
```

CI / releases

- The GitHub Actions workflow produces `plugin/build/package` and uploads that directory as an artifact. The artifact
  can
  be downloaded from the workflow run and contains the ready-to-deploy plugin directory structure.
- CI steps should run `npm install`, `npm run build`, prepare `plugin/build/package` (as shown above) and then upload
  `plugin/build/package` as an artifact.

Troubleshooting

- Plugin will not activate: confirm `plugin/build/package/src/init.php` exists in the package; check admin notices;
  inspect
  `wp-content/debug.log`.
- Fatal error due to missing file: older packaging steps may have accidentally excluded `src/` — ensure your
  rsync/exclude
  rules don't filter out `src/`.
- Block assets not updated: confirm `npm run start` or `npm run build` completed successfully and built assets are in
  `plugin/build/`.

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
