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
