#!/bin/bash
# Declare associative arrays for commands and PIDs
declare -A commands
declare -A pids

# Check if the PLATFORM_FPM_WORKER is unset or empty, set default to "php-fpm8.2"
if [ -z "$PLATFORM_FPM_WORKER" ]; then
    export PLATFORM_FPM_WORKER="php-fpm8.2"
fi

# Check if the PORT is unset or empty, set default to "28080"
if [ -z "$PORT" ]; then
    export PORT="28080"
fi

# Check if $MAGENTO_CLOUD_APP_DIR is already set and non-empty
if [ -z "$MAGENTO_CLOUD_APP_DIR" ]; then
    # $MAGENTO_CLOUD_APP_DIR is not set or is empty, proceed to determine its value based on other variables
    if [ -n "$CLOUD_DIR" ]; then
        export MAGENTO_CLOUD_APP_DIR=$CLOUD_DIR
    elif [ -n "$HOME" ]; then
        export MAGENTO_CLOUD_APP_DIR=$HOME
    elif [ -n "$PWD" ]; then
        export MAGENTO_CLOUD_APP_DIR=$PWD
    fi
fi

# Check if $USER is already set and non-empty
if [ -z "$USER" ]; then
    # Create variable from MAGENTO_CLOUD_APP_DIR
    export $USER=${MAGENTO_CLOUD_APP_DIR#/app/}
fi

# Kill existing processes started from previous deployment
killall --wait ${PLATFORM_FPM_WORKER}
killall --wait php
killall --wait nginx

# Prepare nginx configuration
envsubst '\$PORT \$USER \$MAGENTO_CLOUD_APP_DIR' < ${MAGENTO_CLOUD_APP_DIR}/application-server/nginx.conf.sample > ${MAGENTO_CLOUD_APP_DIR}/app/etc/nginx.conf

# Populate the commands associative array
commands["PHP-FPM"]="/usr/sbin/${PLATFORM_FPM_WORKER} --fpm-config=/etc/platform/${USER}/php-fpm.conf -c /etc/platform/${USER}/php.ini --nodaemonize"
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
