# TasksAppApi

Simple API application for managing tasks.

## Tech Stack
- [PHP 8.4.1](https://www.php.net)
- [Laravel 12](https://laravel.com)
- [MySQL 8.0](https://www.mysql.com)
- [Docker](https://www.docker.com)
    - [Docker Compose](https://docs.docker.com/compose)


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
docker-compose up -d
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

