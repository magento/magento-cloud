#!/bin/bash
# Declare associative arrays for commands, PIDs, and process group IDs
declare -A commands
declare -A pids
declare -A pgids

# Check if the PLATFORM_FPM_WORKER is unset or empty, set default to "php-fpm8.2"
if [ -z "$PLATFORM_FPM_WORKER" ]; then
    PLATFORM_FPM_WORKER="php-fpm8.2"
fi

# Check if the PORT is unset or empty, set default to "28080"
if [ -z "$PORT" ]; then
    PORT="28080"
fi

# Check if $MAGENTO_CLOUD_APP_DIR is already set and non-empty
if [ -z "$MAGENTO_CLOUD_APP_DIR" ]; then
    # $MAGENTO_CLOUD_APP_DIR is not set or is empty, proceed to determine its value based on other variables
    if [ -n "$CLOUD_DIR" ]; then
        MAGENTO_CLOUD_APP_DIR=$CLOUD_DIR
    elif [ -n "$HOME" ]; then
        MAGENTO_CLOUD_APP_DIR=$HOME
    elif [ -n "$PWD" ]; then
        MAGENTO_CLOUD_APP_DIR=$PWD
    fi
fi

# Check if $USER is already set and non-empty
if [ -z "$USER" ]; then
    # Create variable from MAGENTO_CLOUD_APP_DIR
    USER=${MAGENTO_CLOUD_APP_DIR#/app/}
fi

# Export variables so they could be used in child processes
export PLATFORM_FPM_WORKER PORT MAGENTO_CLOUD_APP_DIR USER

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

# Gracefully (SIGTERM), then forcefully (SIGKILL) after a bounded wait, stop
# only the process group of a crashed command's own instance. Each managed
# command is started via setsid and its actual process group ID is read back
# from the OS (not assumed from the PID), so signalling that group reaches
# only its own orphaned children and never unrelated processes such as cron
# jobs, deploy hooks, or other independent php/php-fpm/nginx invocations
# sharing the same binary name.
stop_process_group() {
    local pgid="$1"
    [ -n "$pgid" ] || return 0
    kill -0 -- "-${pgid}" 2>/dev/null || return 0
    kill -TERM -- "-${pgid}" 2>/dev/null
    local waited=0
    while kill -0 -- "-${pgid}" 2>/dev/null && [ "$waited" -lt 30 ]; do
        sleep 1
        waited=$((waited + 1))
    done
    kill -KILL -- "-${pgid}" 2>/dev/null
}

# Start processes and store their PIDs
for key in "${!commands[@]}"; do
  # Convert command key to kebab-case for the log file name
  log_name=$(camel_case_to_kebab_case "$key")

  # Execute command in its own process group (via setsid) with output sent
  # to the log file name
  setsid ${commands[$key]} > ${MAGENTO_CLOUD_APP_DIR}/var/log/${log_name}.log 2>&1 &
  pids[$key]=$!
  pgids[$key]=$(ps -o pgid= -p "${pids[$key]}" | tr -d ' ')

  echo $(date -u) "Started $key with PID ${pids[$key]} and PGID ${pgids[$key]}"
done

# Infinite loop to keep all processes running
while true; do
  for key in "${!commands[@]}"; do
    if ! kill -0 ${pids[$key]} 2>/dev/null; then
      echo $(date -u) "$key process is not running. Restarting..."
      stop_process_group "${pgids[$key]}"
      setsid ${commands[$key]} &
      pids[$key]=$!
      pgids[$key]=$(ps -o pgid= -p "${pids[$key]}" | tr -d ' ')
      echo $(date -u) "Restarted $key with PID ${pids[$key]} and PGID ${pgids[$key]}"
    fi
  done
  sleep 1
done
