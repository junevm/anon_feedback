# AnonFeedback Deployment Guide

This guide provides complete instructions for deploying the AnonFeedback application using Docker on any machine.

## Prerequisites

Before deploying AnonFeedback, ensure you have the following installed:

- **Docker Desktop** (version 20.10 or higher)
  - Download: https://www.docker.com/products/docker-desktop
- **Docker Compose** (usually included with Docker Desktop)
- **Git** (to clone the repository)

### System Requirements

- **OS**: Windows 10/11, macOS 10.15+, or Linux (Ubuntu 18.04+, Debian 10+, etc.)
- **RAM**: Minimum 4GB (8GB recommended)
- **Disk Space**: At least 2GB free space
- **Ports**: Ensure ports 8000 and 3306 are available

## Quick Start (Automated Deployment)

The easiest way to deploy AnonFeedback is using the automated deployment script:

```bash
# 1. Clone the repository
git clone <repository-url>
cd anon_feedback

# 2. Run the deployment script
./deploy.sh
```

The script will:
- Check prerequisites
- Build Docker containers
- Initialize the database
- Seed with sample data
- Display access information

**That's it!** The application will be running at http://localhost:8000

## Manual Deployment (Step by Step)

If you prefer manual deployment or the automated script doesn't work, follow these steps:

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd anon_feedback
```

### Step 2: Configure Environment

```bash
# Copy the example environment file
cp .env.example .env

# The default values work with Docker, but you can customize:
# - DB_PASSWORD: Change for production
# - APP_ENV: Set to 'production' for production deployment
# - APP_DEBUG: Set to 'false' for production
```

### Step 3: Build and Start Containers

```bash
# Build and start all services in detached mode
docker-compose up --build -d

# This will start:
# - Laravel application (PHP-FPM + Nginx)
# - MySQL database
```

### Step 4: Wait for MySQL

The MySQL database needs time to initialize on first run:

```bash
# Wait approximately 30 seconds
sleep 30

# Or check if MySQL is ready:
docker-compose logs mysql | grep "ready for connections"
```

### Step 5: Initialize the Application

```bash
# Access the application container
docker-compose exec app bash

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Seed the database with initial data
php artisan db:seed --force

# Exit the container
exit
```

### Step 6: Access the Application

Open your browser and navigate to:
- **Application URL**: http://localhost:8000

## Default Credentials

After deployment, you can log in with these accounts:

### Admin Account
- **Email**: `admin@anonfeedback.com`
- **Password**: `password`
- **Access**: Full admin dashboard, moderation, analytics

### Employee Accounts
- **Email**: `john@anonfeedback.com` or `jane@anonfeedback.com`
- **Password**: `password`
- **Access**: Feedback submission only

> ⚠️ **SECURITY WARNING**: Change these passwords immediately in production!

## Testing the Application

### 1. Test Employee Feedback Submission

1. Log in as employee (john@anonfeedback.com / password)
2. You'll be redirected to the feedback submission form
3. Select a category (e.g., "Work Culture")
4. Enter feedback text (minimum 10 characters)
5. Click "Submit Feedback Anonymously"
6. Verify success message appears

### 2. Test Admin Features

1. Log out and log in as admin (admin@anonfeedback.com / password)
2. View the admin dashboard with statistics
3. Navigate to "Moderation" to review feedback
4. Test approving, flagging, and resetting feedback
5. Check "Analytics & Reports" for trends and insights

### 3. Verify Anonymity

Run this command to verify feedback has no user_id:

```bash
docker-compose exec app php artisan tinker --execute="
\$feedback = \App\Models\Feedback::latest()->first();
echo 'Columns: ' . implode(', ', array_keys(\$feedback->getAttributes())) . PHP_EOL;
echo 'Has user_id: ' . (isset(\$feedback->user_id) ? 'YES - ERROR!' : 'NO - Correct!') . PHP_EOL;
"
```

Expected output should show NO user_id column.

## Docker Commands Reference

### Basic Operations

```bash
# Start the application
docker-compose up -d

# Stop the application
docker-compose down

# Stop and remove volumes (clean slate)
docker-compose down -v

# Restart services
docker-compose restart

# View logs (all services)
docker-compose logs -f

# View logs (specific service)
docker-compose logs -f app
docker-compose logs -f mysql
```

### Application Management

```bash
# Access the application container
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:list

# Access MySQL database
docker-compose exec mysql mysql -u anonfeedback -psecret anonfeedback

# Check application status
docker-compose ps
```

### Troubleshooting Commands

```bash
# Rebuild containers from scratch
docker-compose build --no-cache
docker-compose up -d

# Check container resource usage
docker stats

# View container details
docker-compose exec app php -v
docker-compose exec app php -m

# Test database connection
docker-compose exec app php artisan tinker --execute="
try {
    \DB::connection()->getPdo();
    echo 'Database connection: SUCCESS' . PHP_EOL;
} catch (\Exception \$e) {
    echo 'Database connection: FAILED - ' . \$e->getMessage() . PHP_EOL;
}
"
```

## Common Issues and Solutions

### Issue 1: Port Already in Use

**Error**: "Bind for 0.0.0.0:8000 failed: port is already allocated"

**Solution**:
```bash
# Option 1: Stop the service using port 8000
# On Linux/Mac:
lsof -ti:8000 | xargs kill -9

# On Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F

# Option 2: Change the port in docker-compose.yml
# Edit: ports: "8080:80" instead of "8000:80"
```

### Issue 2: MySQL Connection Failed

**Error**: "SQLSTATE[HY000] [2002] Connection refused"

**Solution**:
```bash
# Wait longer for MySQL to initialize
sleep 30

# Check MySQL logs
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql

# Verify MySQL is running
docker-compose exec mysql mysqladmin -u root -proot_secret ping
```

### Issue 3: Permission Denied

**Error**: "Permission denied" for storage or bootstrap/cache

**Solution**:
```bash
# Fix permissions inside container
docker-compose exec app chmod -R 777 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Issue 4: npm/node Issues During Build

**Error**: Build fails during npm install or npm run build

**Solution**:
```bash
# Clear build cache
docker-compose down
docker system prune -a

# Rebuild without cache
docker-compose build --no-cache
docker-compose up -d
```

### Issue 5: Application Shows White Screen

**Solution**:
```bash
# Clear all caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

# Check logs
docker-compose logs app
```

## Production Deployment Checklist

Before deploying to production:

- [ ] Change all default passwords
- [ ] Update `.env` file:
  - [ ] Set `APP_ENV=production`
  - [ ] Set `APP_DEBUG=false`
  - [ ] Generate new `APP_KEY`
  - [ ] Use strong database passwords
  - [ ] Set correct `APP_URL`
- [ ] Configure HTTPS/SSL certificates
- [ ] Set up proper database backups
- [ ] Configure log rotation
- [ ] Enable firewall rules
- [ ] Set up monitoring and alerts
- [ ] Review and harden security settings
- [ ] Test disaster recovery procedures

## Performance Optimization

For production environments:

```bash
# Optimize Composer autoloader
docker-compose exec app composer install --optimize-autoloader --no-dev

# Cache configuration
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Enable OPcache (already configured in docker/php.ini)
```

## Backup and Restore

### Backup Database

```bash
# Create backup
docker-compose exec mysql mysqldump -u anonfeedback -psecret anonfeedback > backup.sql

# Or with timestamp
docker-compose exec mysql mysqldump -u anonfeedback -psecret anonfeedback > backup-$(date +%Y%m%d-%H%M%S).sql
```

### Restore Database

```bash
# Restore from backup
docker-compose exec -T mysql mysql -u anonfeedback -psecret anonfeedback < backup.sql
```

## Monitoring

### Check Application Health

```bash
# Application logs
docker-compose logs -f app

# MySQL logs
docker-compose logs -f mysql

# Resource usage
docker stats

# Disk usage
docker system df
```

## Uninstalling

To completely remove AnonFeedback:

```bash
# Stop and remove containers
docker-compose down -v

# Remove images
docker rmi anon_feedback_app
docker rmi mysql:8.0

# Remove project directory
cd ..
rm -rf anon_feedback
```

## Support

For issues, questions, or contributions:
- Open an issue in the repository
- Check existing documentation in README.md
- Review Laravel documentation: https://laravel.com/docs

## License

This project is open-source software.
