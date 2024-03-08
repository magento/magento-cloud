#!/bin/bash
# Declare associative arrays for commands and PIDs
declare -A commands
declare -A pids

# Kill existing processes started from previous deployment
killall php
killall nginx

# Create CLOUD_ENVIRONMENT variable from MAGENTO_CLOUD_APP_DIR
export CLOUD_ENVIRONMENT=${MAGENTO_CLOUD_APP_DIR#/app/}

POST_DEPLOY_TIMESTAMP_FILE="${HOME}/app/etc/.post-deploy.timestamp"

# Prepare nginx configuration
envsubst '\$PORT \$CLOUD_ENVIRONMENT \$MAGENTO_CLOUD_APP_DIR' < ${MAGENTO_CLOUD_APP_DIR}/application-server/nginx.conf.sample > ${MAGENTO_CLOUD_APP_DIR}/app/etc/nginx.conf

# Populate the commands associative array
commands["ApplicationServer"]="php -dopcache.enable_cli=1 -dopcache.validate_timestamps=0 bin/magento server:run -vvv"
commands["Nginx"]="/usr/sbin/nginx -c ${MAGENTO_CLOUD_APP_DIR}/app/etc/nginx.conf"

# Function to convert CamelCase to kebab-case
camel_case_to_kebab_case() {
    local str="$1"
    echo "$str" | sed -r 's/([A-Z])/-\L\1/g' | cut -c 2-
}

# Start processes and store their PIDs
for key in "${!commands[@]}"; do
  # Convert command key to kebab-case for the log file name
  log_name=$(camel_case_to_kebab_case "$key")

  # Execute command with the output sent to the log file name
  ${commands[$key]} > ${MAGENTO_CLOUD_APP_DIR}/var/log/${log_name}.log 2>&1 &
  pids[$key]=$!

  echo $(date -u) "Started $key with PID ${pids[$key]}"
done

touch "$POST_DEPLOY_TIMESTAMP_FILE"
PREVIOUS_POST_DEPLOY_TIMESTAMP=$(stat -c %Z $POST_DEPLOY_TIMESTAMP_FILE 2> /dev/null || echo "0")

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
  # Check if new post-deployment has happened. Kill server if so. (It will restart in previous part of this loop.)
  CURRENT_POST_DEPLOY_TIMESTAMP=$(stat -c %Z $POST_DEPLOY_TIMESTAMP_FILE 2> /dev/null || echo "0")
  if [[ $CURRENT_POST_DEPLOY_TIMESTAMP -gt $PREVIOUS_POST_DEPLOY_TIMESTAMP ]]; then
    kill ${pids[ApplicationServer]}
    PREVIOUS_POST_DEPLOY_TIMESTAMP=$CURRENT_POST_DEPLOY_TIMESTAMP
  fi
  sleep 1
done
