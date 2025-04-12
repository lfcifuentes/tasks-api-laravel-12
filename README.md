# TasksAppApi

A robust API application for managing tasks, built with Laravel 12.

## Features
- Task Management (CRUD operations)
- Time Tracking
- File Attachments
- Comments System
- Email Notifications
- User Authorization
- Soft Deletes

## Tech Stack
- [PHP 8.4.1](https://www.php.net)
- [Laravel 12](https://laravel.com)
- [MySQL 8.0](https://www.mysql.com)
- [Mailpit](https://mailpit.axllent.org)
- [Docker](https://www.docker.com)
    - [Docker Compose](https://docs.docker.com/compose)

## Requirements
- Docker & Docker Compose
- PHP 8.4.1
- Composer


## Quick Start

1. Clone the repository
```bash
git clone https://github.com/lfcifuentes/tasks-api-laravel-12.git
cd task-project
```

2. Install dependencies
```bash
composer install
```

3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

4. Start Docker containers
```bash
docker-compose up -d
```

5. Run migrations and seeders
```bash
php artisan migrate --seed
```

6. Create storage link
```bash
php artisan storage:link
```

## API Documentation

### Authentication
All API routes require authentication via Bearer token.

### Available Endpoints

#### Tasks
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | /api/tasks | List all tasks |
| POST   | /api/tasks | Create a new task |
| GET    | /api/tasks/{id} | Get task details |
| PUT    | /api/tasks/{id} | Update a task |
| DELETE | /api/tasks/{id} | Delete a task |

#### Comments
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | /api/tasks/{id}/comments | Add comment |
| GET    | /api/tasks/{id}/comments | List comments |

#### Time Logs
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | /api/tasks/{id}/time-log | Log time for a task |
| GET    | /api/tasks/{id}/time-log | Get task time logs |

#### Files
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | /api/tasks/{id}/upload | Upload file |
| GET    | /api/tasks/{id}/files | List task files |

## Email Testing Setup

### Environment Variables
Add to your `.env`:
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

### Access Mailpit Interface
Visit [http://localhost:8025](http://localhost:8025) to view all captured emails.

### Testing Email Functionality
Send a test email using Tinker:
```bash
php artisan tinker
Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test Subject'); });
```


## Database Setup

### Connection Details
```properties
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_project_db
DB_USERNAME=user
DB_PASSWORD=user_password
```

#### Verify Database Connection
Check if the database was created successfully:
```bash
docker exec -it mysql_db mysql -u user -p user_password -e "SHOW DATABASES;"
```

You should see `task_project_db` in the list of databases.

#### Connect to MySQL Shell
```bash
docker exec -it mysql_db mysql -u user -p user_password
```

## Features Documentation

### Soft Deletes
Tasks implement soft deletion:
```php
// Retrieve only soft deleted tasks
Task::onlyTrashed()->get();

// Include soft deleted tasks in query
Task::withTrashed()->get();

// Restore a soft deleted task
$task->restore();
```

### Task Notifications
Notifications are sent for:
- Task creation
- Task updates
- Task deletion
- Task assignment

## Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter TaskTest
```

## Contributing
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
