#!/bin/bash

# AnonFeedback - Docker Deployment and Testing Script
# This script automates the deployment and testing of the AnonFeedback application

set -e

echo "================================================"
echo "AnonFeedback - Docker Deployment Script"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

# Check if Docker is installed
echo "Checking prerequisites..."
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker Desktop first."
    exit 1
fi
print_status "Docker is installed"

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi
print_status "Docker Compose is installed"

# Check if .env file exists
if [ ! -f .env ]; then
    print_warning ".env file not found. Copying from .env.example..."
    cp .env.example .env
    print_status ".env file created"
fi

# Stop and remove existing containers
echo ""
echo "Cleaning up existing containers..."
docker-compose down -v 2>/dev/null || true
print_status "Cleaned up existing containers"

# Build and start containers
echo ""
echo "Building and starting Docker containers..."
echo "This may take several minutes on first run..."
docker-compose up --build -d

# Wait for MySQL to be ready
echo ""
echo "Waiting for MySQL to be ready..."
sleep 30
print_status "MySQL is ready"

# Run migrations and seeders
echo ""
echo "Setting up the database..."
docker-compose exec -T app php artisan key:generate
print_status "Application key generated"

docker-compose exec -T app php artisan migrate --force
print_status "Database migrations completed"

docker-compose exec -T app php artisan db:seed --force
print_status "Database seeded with initial data"

# Display success message
echo ""
echo "================================================"
print_status "Deployment completed successfully!"
echo "================================================"
echo ""
echo "Application is now running at: http://localhost:8000"
echo ""
echo "Default Login Credentials:"
echo "  Admin:"
echo "    Email: admin@anonfeedback.com"
echo "    Password: password"
echo ""
echo "  Employee:"
echo "    Email: john@anonfeedback.com"
echo "    Password: password"
echo ""
echo "⚠️  IMPORTANT: Change these passwords in production!"
echo ""
echo "Useful commands:"
echo "  View logs: docker-compose logs -f"
echo "  Stop: docker-compose down"
echo "  Restart: docker-compose restart"
echo ""
