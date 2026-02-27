#!/bin/bash

# Define the container name
CONTAINER_NAME="gacpac-ti-laravel.test-1"

# Function to check if running inside Docker
is_inside_docker() {
    if [ -f /.dockerenv ] || grep -q "docker" /proc/1/cgroup 2>/dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to check if the Docker command is available
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Logic to determine execution context
if is_inside_docker || ! command_exists docker; then
    # We are inside the container or Docker is not available
    # Assuming the script is run from the project root, we need to go into 
    
    TARGET_DIR="backend"
    
    if [ ! -d "$TARGET_DIR" ]; then
         # Fallback if we are already in backend or structure is different
         if [ -f "artisan" ]; then
            TARGET_DIR="."
         else
            echo "Error: Could not find 'artisan' file."
            exit 1
         fi
    fi

    cd "$TARGET_DIR" && php artisan boost:mcp "$@"
else
    # We are on the host and Docker is available
    docker compose exec -T laravel.test php artisan boost:mcp "$@"
fi
