# AnonFeedback (Student Project)

This is a simple Anonymous Feedback System built with Laravel and Docker.

## Requirements

- Docker
- Docker Compose

## How to Run

1.  **Start the project**:
    \`\`\`bash
    docker-compose up -d --build
    \`\`\`

2.  **Setup the application** (First time only):
    Run the setup commands inside the container:
    \`\`\`bash

    # Install dependencies (they should be installed by Dockerfile but just in case)

    docker-compose exec app composer install

    # Generate key

    docker-compose exec app php artisan key:generate

    # Run migrations and seed admin user

    docker-compose exec app php artisan migrate --seed
    \`\`\`

3.  **Access the application**:
    - **Home**: http://localhost:8081
    - **Admin Dashboard**: http://localhost:8081/admin
      **Admin Credentials**:
    - Email: admin@example.com
    - Password: password

## Features

- Submit feedback anonymously (No User ID stored).
- Categorize feedback.
- Basic toxicity check (e.g. "hate", "stupid" are flagged).
- Admin dashboard to approve/flag feedback.

## MVC Structure

- **Model**: \`Feedback\` (app/Models/Feedback.php)
- **View**: Blade templates in \`resources/views/feedback\` and \`admin\`.
- **Controller**: \`FeedbackController\` and \`AdminController\`.
