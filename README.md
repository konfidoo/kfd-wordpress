# kfd-wordpress
Wordpress integration for konfidoo

## Features
- WordPress Gutenberg block for integrating konfidoo forms
- Admin settings page for configuring global Project ID
- Automatic fallback to global Project ID when block-specific ID is not set

## Settings
After activating the plugin, you can configure global settings by navigating to:
**WordPress Admin → Settings → konfidoo**

The global Project ID configured here will be used as a fallback when individual blocks don't have a specific Project ID set.

# Development
 1. `cd plugin`
 1. `npm i`
 1. `npm start`

## Start Wordpress 
 1. `docker-compose up`
 1. `open http://localhost:8080` 
 1. install wordpress
 1. go to plugins and activate

(plugin needs to be built)

# build
 1. `cd plugin`
 1. `npm i`
 1. `npm build`

## Download & Installation

### Automatic Builds
Every time the main branch is updated, a new plugin zip file is automatically built and made available for download.

- **[Download the latest kfd-wordpress.zip from GitHub Actions artifacts](https://github.com/konfidoo/kfd-wordpress/actions/workflows/build-plugin.yml?query=branch%3Amain)**

### How to Install
1. Go to the [GitHub Actions page](https://github.com/konfidoo/kfd-wordpress/actions/workflows/build-plugin.yml?query=branch%3Amain) for this repository
2. Click on the latest successful build (green checkmark)
3. Scroll down to the "Artifacts" section and download `kfd-wordpress`
4. Extract the downloaded zip file to get `kfd-wordpress.zip`
5. In your WordPress Admin, go to **Plugins → Add New** and click **Upload Plugin**
6. Select the `kfd-wordpress.zip` file and click **Install Now**
7. Activate the plugin once installation is complete

### Manual Installation (Development)
For development or if you want to build from source:
1. Clone this repository
2. Navigate to the `plugin` directory
3. Run `npm install` and `npm run build`
4. Copy the entire plugin directory to your WordPress `/wp-content/plugins/` folder
5. Activate the plugin in your WordPress admin