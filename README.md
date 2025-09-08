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

- [Download the latest ready-to-use plugin zip from GitHub Actions artifacts.](https://github.com/konfidoo/kfd-wordpress/actions?query=branch%3Amain)

### How to Install
1. Download the latest `kfd-wordpress.zip` from the link above.
2. In your WordPress Admin, go to **Plugins → Add New** and click **Upload Plugin**.
3. Select the downloaded zip file and click **Install Now**.
4. Activate the plugin once installation is complete.