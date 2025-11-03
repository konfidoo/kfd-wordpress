#!/bin/sh

set -e

# Prepare build/package folder (mirrors CI behavior)
rm -rf build/package
mkdir -p build/package

rsync -a --delete \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='build' \
  --exclude='package.json' \
  --exclude='package-lock.json' \
  --exclude='*.zip' \
  --exclude='**/*.scss' \
  ./ build/package/

# If called with 'zip' argument, create a zip from build/package (backwards compatible)
if [ "$1" = "zip" ]; then
  echo "Creating kfd-wordpress.zip from build/package..."
  (cd build && zip -r ../kfd-wordpress.zip package)
  echo "Created kfd-wordpress.zip"
else
  echo "Prepared plugin/build/package. To create a zip, run: ./zip.sh zip"
fi
