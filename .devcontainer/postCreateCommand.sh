#!/bin/bash
set -ex

# Wait until docker is ready for commands
wait_for_docker() {
  while true; do
    docker ps > /dev/null 2>&1 && break
    sleep 1
  done
  echo "Docker is ready."
}

wait_for_docker

# This file is called in three scenarios:
# 1. fresh creation of devcontainer
# 2. rebuild
# 3. full rebuild

# download images beforehand, optional
ddev debug download-images

# catch rebuilds
ddev poweroff

# Workaround for vite:
# We expose port 3002 for vite on codespaces, because ddev-router is not used  on codespace instances.
# Routing is handled by codespaces itself. This will  create an additional config file for DDEV
# which will expose port 3002. This port needs to be set to  public + HTTPS manually in ports tab,
# otherwise CORS errors will occur. See config/vite.php and vite.config.js as well.

if [ ! -f ".ddev/docker-compose.codespaces-vite.yaml" ]; then
    echo "Creating vite port workaround file for port exposing ..."
    # create workaround file for port exposing (if it does not exist yet)
    # info: this file should not be commited to git since it shouldn't be used on local DDEV
    cat >.ddev/docker-compose.codespaces-vite.yaml <<EOL
services:
  web:
    ports:
    - 3002:3002
EOL
fi

# Make sure large db is present
# git lfs pull

# Start ddev project
ddev start -y

# Normal project setup
ddev composer install
ddev npm install

# Restore the database
ddev import-db --file=./db.sql.gz

# Craft configuration
ddev craft setup/app-id
ddev craft setup/security-key
ddev craft up --interactive=0

# If craft was also installed (e.g. when you do a codespaces rebuild/full rebuild), this command
# will just state "craft already installed".
# ddev craft install/craft \
#   --interactive=0 \
#   --username=dev@simplygoodwork.com \
#   --email=dev@simplygoodwork.com \
#   --password=newPassword \
#   --site-name=Testsite

# install the vite plugin by nystudio107 after fresh install
# ddev craft plugin/install vite
