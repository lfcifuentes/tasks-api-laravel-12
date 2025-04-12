# TasksAppApi

Simple API application for managing tasks.

## Tech Stack
- [PHP 8.4.1](https://www.php.net)
- [Laravel 12](https://laravel.com)
- [MySQL 8.0](https://www.mysql.com)
- [Mailpit](https://mailpit.axllent.org)
- [Docker](https://www.docker.com)
    - [Docker Compose](https://docs.docker.com/compose)


## Setup Instructions
1. Clone the repository
2. Copy .env.example to .env
3. Configure Mailpit settings
4. Configure database settings
5. Run migrations and seeders
6. Start the development server

## Email Testing Setup

### Environment Variables
Add these mail configuration variables to your `.env` file:
```properties
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Start Mailpit Container
```bash
docker-compose up -d mailpit
```

### Access Mailpit Interface
Visit [http://localhost:8025](http://localhost:8025) to view all captured emails.

### Testing Email Functionality
Send a test email using Tinker:
```bash
php artisan tinker
Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test Subject'); });
```

### Stop Mailpit Container
```bash
docker-compose stop mailpit
```

## Database Setup

### Environment Variables
Make sure you have the following environment variables set in your `.env` file:
```properties
DB_DATABASE=task_project_db
DB_USERNAME=user
DB_PASSWORD=user_password
```

### Database Commands

#### Start the Database Container
```bash
docker-compose up -d mysql
```

#### Stop the Database Container
```bash
docker-compose down
```

#### Remove Database Volume
⚠️ Warning: This will delete all data in the database
```bash
docker volume rm task-project_mysql_data
```

#### Verify Database Connection
Check if the database was created successfully:
```bash
docker exec -it mysql_db mysql -u luis -pluis_password -e "SHOW DATABASES;"
```

You should see `task_project_db` in the list of databases.

#### Connect to MySQL Shell
```bash
docker exec -it mysql_db mysql -u user -p user_password
```

## Project Setup

Run database migrations
```bash
php artisan migrate
```

Run database seeders
```bash
php artisan db:seed
```

Create storage link 
```bash
php artisan storage:link
```
