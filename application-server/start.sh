#!/bin/bash
# Declare associative arrays for commands and PIDs
declare -A commands
declare -A pids

# Kill existing processes started from previous deployment
killall php
killall nginx

# Create CLOUD_ENVIRONMENT variable from MAGENTO_CLOUD_APP_DIR
export CLOUD_ENVIRONMENT=${MAGENTO_CLOUD_APP_DIR#/app/}

# Prepare nginx configuration
envsubst '\$PORT \$CLOUD_ENVIRONMENT \$MAGENTO_CLOUD_APP_DIR' < ${MAGENTO_CLOUD_APP_DIR}/application-server/nginx.conf.sample > ${MAGENTO_CLOUD_APP_DIR}/app/etc/nginx.conf

# Populate the commands associative array
commands["ApplicationServer"]="php -dopcache.enable_cli=1 -dopcache.validate_timestamps=0 bin/magento server:run -vvv > ${MAGENTO_CLOUD_APP_DIR}/var/log/application-server.log 2>&1"
commands["Nginx"]="/usr/sbin/nginx -c ${MAGENTO_CLOUD_APP_DIR}/app/etc/nginx.conf > ${MAGENTO_CLOUD_APP_DIR}/var/log/nginx.log 2>&1 &"

# Start processes and store their PIDs
for key in "${!commands[@]}"; do
  ${commands[$key]}
  pids[$key]=$!
  echo $(date -u) "Started $key with PID ${pids[$key]}"
done

# Infinite loop to keep all processes running
while true; do
  for key in "${!commands[@]}"; do
    if ! kill -0 ${pids[$key]} 2>/dev/null; then
      echo $(date -u) "$key process is not running. Restarting..."
      ${commands[$key]} &
      pids[$key]=$!
      echo $(date -u) "Restarted $key with PID ${pids[$key]}"
    fi
  done
  sleep 1
done
