# AnonFeedback - Anonymous Office Feedback System

A production-ready Laravel 10 application that enables anonymous feedback submission in workplace environments. Built with security and privacy at its core, this system helps organizations collect honest feedback without compromising employee anonymity.

## Features

### Core Functionality
- **Anonymous Feedback Submission**: Employees can submit feedback without revealing their identity
- **Category-Based Organization**: Feedback organized into categories (Work Culture, Management, Workload, Ethics, etc.)
- **Automatic Toxicity Detection**: Built-in keyword-based abuse filtering that auto-flags inappropriate content
- **Three-State Moderation**: Feedback can be Pending, Approved, or Flagged
- **Admin Dashboard**: Comprehensive analytics and moderation tools
- **Reports & Analytics**: Aggregated statistics and trend analysis

### Security & Privacy
- **No User Tracking**: Feedback table does NOT contain user_id column
- **Anonymous Tokens**: Each submission uses a unique hashed token
- **Role-Based Access Control**: Admin and Employee roles with middleware protection
- **CSRF Protection**: All forms protected with Laravel's CSRF tokens
- **Secure Sessions**: Encrypted session handling

## Tech Stack

- **Framework**: Laravel 10
- **Authentication**: Laravel Breeze
- **Database**: MySQL 8.0
- **Frontend**: Blade Templates + Tailwind CSS
- **Server**: Nginx + PHP-FPM
- **Containerization**: Docker + Docker Compose

## Installation & Deployment with Docker

### Prerequisites

- Docker Desktop installed and running
- Docker Compose installed
- Git installed

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd anon_feedback
```

### Step 2: Configure Environment

```bash
# Copy the example environment file
cp .env.example .env

# Update .env file if needed (default values work with Docker)
```

### Step 3: Build and Start Docker Containers

```bash
# Build and start all services
docker-compose up --build -d

# Wait for MySQL to be fully ready (about 30 seconds)
sleep 30
```

### Step 4: Initialize the Application

```bash
# Access the app container
docker-compose exec app bash

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed

# Exit the container
exit
```

### Step 5: Access the Application

Open your browser and navigate to: **http://localhost:8000**

## Default Credentials

After seeding, you can log in with:

### Admin Account
- **Email**: admin@anonfeedback.com
- **Password**: password

### Employee Accounts
- **Email**: john@anonfeedback.com / jane@anonfeedback.com
- **Password**: password

⚠️ **IMPORTANT**: Change these passwords in production!

## Usage Guide

### For Employees

1. Log in with your employee credentials
2. You'll be redirected to the feedback submission form
3. Select a category and write your feedback
4. Click "Submit Feedback Anonymously"
5. Your feedback is submitted without any identifying information

### For Admins

1. Log in with admin credentials
2. Access the Admin Dashboard to see overall statistics
3. Navigate to **Moderation** to review feedback
4. View **Analytics & Reports** for trends and insights

## Docker Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Access app container
docker-compose exec app bash

# Access MySQL
docker-compose exec mysql mysql -u anonfeedback -psecret anonfeedback

# Rebuild containers
docker-compose up --build -d
```

## Security Considerations

1. **Anonymity**: The feedback table explicitly does NOT include user_id
2. **Token Hashing**: Anonymous tokens are hashed for security
3. **Toxicity Filter**: Automatic detection of inappropriate content
4. **Role Middleware**: Admin routes protected by custom middleware
5. **CSRF Protection**: All forms use Laravel's CSRF tokens

## License

This project is open-source software.
