# konfidoo WordPress Plugin

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

konfidoo is a WordPress plugin that provides a Gutenberg block for integrating konfidoo forms. It includes an admin settings page for configuring a global Project ID and automatic fallback functionality.

## Working Effectively

**CRITICAL**: This plugin requires Node.js 14.x due to node-sass compatibility issues. Node.js 20+ will cause build failures.

### Bootstrap and Build the Plugin
**NEVER CANCEL** - Allow ALL commands to complete. Build times are documented with 50% buffer.

1. **Install Node.js 14.21.3** (required for node-sass compatibility):
   ```bash
   curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
   export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
   nvm install 14.21.3
   nvm use 14.21.3
   ```

2. **Install build dependencies**:
   ```bash
   sudo apt-get update && sudo apt-get install -y build-essential
   ```

3. **Install and build the plugin**:
   ```bash
   cd plugin
   npm install  # Takes ~37 seconds. NEVER CANCEL. Set timeout to 90+ seconds.
   npm run build  # Takes ~2.4 seconds. NEVER CANCEL. Set timeout to 10+ seconds.
   ```

### Development Workflow
- **Development mode with file watching**:
  ```bash
  cd plugin
  npm start  # Starts watch mode. NEVER CANCEL. Runs continuously until stopped.
  ```

### WordPress Environment
- **Start WordPress with Docker**:
  ```bash
  docker compose up  # Takes ~60 seconds to start. NEVER CANCEL. Set timeout to 120+ seconds.
  ```
- **Start in background** (detached mode):
  ```bash
  docker compose up -d  # Returns immediately after containers start
  ```
- **Check container status**:
  ```bash
  docker ps  # Should show kfd-wordpress-wordpress-1 and kfd-wordpress-db-1 running
  ```
- **Access WordPress**: Open http://localhost:8080
- **Check WordPress readiness**:
  ```bash
  curl -I http://localhost:8080  # Should return HTTP 302 redirect to install.php
  ```
- **Stop WordPress**:
  ```bash
  docker compose down  # Takes ~6 seconds. NEVER CANCEL. Set timeout to 30+ seconds.
  ```
- **Reset WordPress data** (clean install):
  ```bash
  docker compose down
  docker volume prune -f  # Removes WordPress database and uploads
  ```

## Validation

### Manual Testing Requirements
**ALWAYS** test plugin functionality after making changes:

1. **Build and start WordPress environment**:
   ```bash
   cd plugin && npm run build
   cd .. && docker compose up  # Takes ~60 seconds (first run) or ~5 seconds (cached images)
   ```

2. **Complete WordPress Installation**:
   - Navigate to http://localhost:8080
   - Complete the 5-minute WordPress installation process
   - Use any site title (e.g., "konfidoo Test Site")
   - Use any username (e.g., "testuser") 
   - WordPress will generate a secure password - save it for login
   - Provide any valid email address (e.g., "test@example.com")

3. **Activate and test the plugin**:
   - Log into WordPress Admin using the credentials from installation
   - Go to WordPress Admin → Plugins
   - Activate the "konfidoo" plugin (appears as "konfidoo" in plugin list)
   - Go to WordPress Admin → Settings → konfidoo
   - Configure a global Project ID (test with any ID like "test-123")
   - Create a new post/page with Gutenberg editor
   - Add the "konfidoo" block from the block inserter
   - Verify the block configuration panel appears in the sidebar
   - Test both block-specific and global Project ID fallback

4. **Frontend validation**:
   - Publish the post/page
   - View the frontend to ensure the konfidoo script loads: `https://konfidoo.de/elements/v01/main.js`
   - Check browser developer tools network tab for script loading
   - Verify no JavaScript errors in browser console
   - Verify konfidoo forms render if Project ID is valid

### Build Validation
Always run these commands before committing changes:
- `npm run build` - Ensures plugin builds successfully
- Manual WordPress installation and plugin activation test
- Frontend verification of konfidoo script inclusion

## Common Tasks and Solutions

### Node.js Version Issues
- **Symptom**: `npm install` fails with node-gyp or node-sass errors
- **Solution**: Use Node.js 14.21.3 exactly. Node.js 20+ will not work with this project's dependencies.

### Build Errors
- **Clean rebuild**:
  ```bash
  cd plugin
  rm -rf node_modules package-lock.json
  npm install
  npm run build
  ```

### Docker Issues
- **Reset WordPress environment**:
  ```bash
  docker compose down
  docker volume prune -f  # Removes WordPress database
  docker compose up
  ```

## Project Structure

### Repository Root
```
├── README.md               # Basic project documentation
├── docker-compose.yaml     # WordPress + MySQL environment
├── plugin/                 # WordPress plugin directory
│   ├── package.json        # Node.js dependencies (cgb-scripts)
│   ├── plugin.php          # Main plugin file
│   ├── admin-settings.php  # Settings page implementation
│   ├── src/               # Plugin source code
│   │   ├── init.php       # Block registration and assets
│   │   ├── blocks.js      # Main JavaScript entry point
│   │   └── block/         # Gutenberg block implementation
│   └── dist/              # Built assets (generated)
```

### Key Files
- **plugin/plugin.php**: Main plugin entry point, loads konfidoo scripts
- **plugin/admin-settings.php**: WordPress admin settings page for global Project ID
- **plugin/src/init.php**: Registers Gutenberg block and enqueues assets
- **plugin/src/block/block.js**: Gutenberg block implementation with project ID configuration
- **docker-compose.yaml**: Local WordPress development environment

### Build Process
The plugin uses Create Guten Block (cgb-scripts) which:
- Compiles JavaScript/JSX using Webpack and Babel
- Processes SCSS to CSS
- Generates production-ready assets in `dist/` directory:
  - `blocks.build.js` (~33KB): Main Gutenberg block JavaScript
  - `blocks.editor.build.css` (~469B): Editor-only styles
  - `blocks.style.build.css` (~173B): Frontend and editor styles
- Provides development mode with file watching and hot reload

### WordPress Plugin Integration
- Plugin automatically loads konfidoo script: `https://konfidoo.de/elements/v01/main.js`
- Mounts as `dev-plugin` in Docker environment at `/var/www/html/wp-content/plugins/dev-plugin/`
- Requires WordPress 5.0+ for Gutenberg block support
- Registers block type: `cgb/block-konfidoo`
- Creates admin menu: Settings → konfidoo

## Dependencies and Requirements

### System Requirements
- Node.js 14.21.3 (exact version required)
- npm 6.14.18 (comes with Node.js 14.21.3)
- Docker and Docker Compose v2.38.2+
- build-essential package for node-sass compilation

### Plugin Dependencies
- **cgb-scripts 1.23.1**: Create Guten Block build system
- **WordPress 5.0+**: Required for Gutenberg block support
- **PHP 7.4+**: WordPress requirement

### Known Limitations
- Cannot use Node.js versions 15+ due to node-sass compatibility
- Docker Compose v1 commands may not work (use `docker compose` not `docker-compose`)
- Plugin requires manual WordPress installation on first run
- No automated tests available - requires manual validation

## Timing Reference
Based on actual measurements with 50% safety buffers:
- `npm install`: ~37 seconds clean install (set timeout to 90+ seconds)
- `npm run build`: ~2.4 seconds (set timeout to 10+ seconds)
- `npm start`: ~3 seconds to start watch mode (runs indefinitely)
- `docker compose up`: 
  - First run: ~60 seconds (set timeout to 120+ seconds)
  - Cached images: ~5 seconds (set timeout to 30+ seconds)
- `docker compose down`: ~1-6 seconds (set timeout to 30+ seconds)
- WordPress installation: ~2-3 minutes manual process
- WordPress login and plugin activation: ~1-2 minutes manual process

## Error Recovery
If any command fails:
1. Check Node.js version: `node --version` (must be 14.21.3)
2. Verify Docker is running: `docker ps`
3. Clean build if needed: `rm -rf plugin/node_modules plugin/package-lock.json`
4. Always re-run complete validation sequence after fixes