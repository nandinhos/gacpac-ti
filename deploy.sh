#!/usr/bin/env bash
set -euo pipefail

# gacpac-ti deploy script
# - Detects high ports availability
# - Writes .env if missing
# - Builds and starts docker-compose

# Defaults (can be overridden by env or .env)
DEFAULT_MYSQL_HOST_PORT=53106
DEFAULT_BACKEND_HOST_PORT=55050
DEFAULT_FRONTEND_HOST_PORT=58100

project_root_dir="$(cd "$(dirname "$0")" && pwd)"
cd "$project_root_dir"

echo "[deploy] Project root: $project_root_dir"

# Check dependencies
command -v docker >/dev/null 2>&1 || { echo "[deploy] Docker not found. Install Docker."; exit 1; }
command -v docker compose >/dev/null 2>&1 || command -v docker-compose >/dev/null 2>&1 || { echo "[deploy] Docker Compose not found. Install docker compose."; exit 1; }

# Function to check if a TCP port is free on localhost
is_port_free() {
  local port=$1
  if ss -lntH | awk '{print $4}' | grep -qE "(^|:)${port}$"; then
    return 1 # in use
  else
    return 0 # free
  fi
}

# Find available high port starting from provided default
find_free_port() {
  local start=$1
  local port=$start
  while ! is_port_free "$port"; do
    port=$((port+1))
    if [ "$port" -gt 65535 ]; then
      echo "[deploy] No free port found above $start" >&2
      exit 1
    fi
  done
  echo "$port"
}

# Load existing .env if present to preserve user configs
if [ -f .env ]; then
  echo "[deploy] Loading existing .env"
  set -a; source .env; set +a
fi

# Compute ports
MYSQL_HOST_PORT=${MYSQL_HOST_PORT:-$DEFAULT_MYSQL_HOST_PORT}
BACKEND_HOST_PORT=${BACKEND_HOST_PORT:-$DEFAULT_BACKEND_HOST_PORT}
FRONTEND_HOST_PORT=${FRONTEND_HOST_PORT:-$DEFAULT_FRONTEND_HOST_PORT}

MYSQL_HOST_PORT=$(find_free_port "$MYSQL_HOST_PORT")
BACKEND_HOST_PORT=$(find_free_port "$BACKEND_HOST_PORT")
FRONTEND_HOST_PORT=$(find_free_port "$FRONTEND_HOST_PORT")

echo "[deploy] Ports selected: MYSQL=$MYSQL_HOST_PORT BACKEND=$BACKEND_HOST_PORT FRONTEND=$FRONTEND_HOST_PORT"

# Prepare VITE_API_URL consistent with backend host port
VITE_API_URL=${VITE_API_URL:-"http://localhost:${BACKEND_HOST_PORT}/api"}

# Write .env if missing or update ports
if [ ! -f .env ]; then
  echo "[deploy] Creating .env from template"
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi

# In-place update of port-related keys and VITE_API_URL
update_env_var() {
  local key=$1
  local value=$2
  if grep -qE "^${key}=" .env; then
    sed -i "s|^${key}=.*|${key}=${value}|" .env
  else
    echo "${key}=${value}" >> .env
  fi
}

update_env_var MYSQL_HOST_PORT "$MYSQL_HOST_PORT"
update_env_var BACKEND_HOST_PORT "$BACKEND_HOST_PORT"
update_env_var FRONTEND_HOST_PORT "$FRONTEND_HOST_PORT"
update_env_var VITE_API_URL "$VITE_API_URL"

echo "[deploy] .env updated"

# Build and start services
compose_cmd="docker compose"
if ! docker compose version >/dev/null 2>&1; then
  compose_cmd="docker-compose"
fi

echo "[deploy] Building images"
$compose_cmd build --no-cache

echo "[deploy] Starting stack"
$compose_cmd up -d

echo "[deploy] Stack is up"
echo "[deploy] Frontend: http://localhost:${FRONTEND_HOST_PORT}"
echo "[deploy] Backend API: http://localhost:${BACKEND_HOST_PORT}/api"
echo "[deploy] phpMyAdmin: http://localhost:${PHPMYADMIN_HOST_PORT:-58090}"