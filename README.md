
# Smart Task Manager

Smart Task Manager is a task management web application built using Laravel 12.

The main goal of this project is to manage daily tasks, track progress, set priorities, and organize work in one place.

I built this project to practice Laravel backend development and understand how to structure a real-world application.

## Features

### 1. User Authentication
- User registration
- User login and logout
- Each user can manage their own tasks

### 2. Task Management
- Create new tasks
- Edit existing tasks
- Delete tasks
- View all tasks
- Set task title and description
- Set task priority
- Set task due date
- Assign categories to tasks

### 3. Task Status
- Pending
- In Progress
- Completed
- Update task status from the task list

### 4. Search and Filters
- Search tasks by title
- Filter tasks by status
- Filter tasks by priority
- Filter tasks by category

### 5. Dashboard
- Total tasks
- Pending tasks
- In-progress tasks
- Completed tasks
- Task overview

### 6. Notifications
- Database notifications
- Task status notifications
- View user notifications

### 7. Activity History
- Track task activities
- Store task creation and update history
- View activity records

### 8. Background Jobs
- Laravel Jobs and Queues
- Database queue driver
- Overdue task checking
- Background notification processing

### 9. REST APIs
- Task APIs
- API authentication using Laravel Sanctum
- Create, update, view, and delete tasks

### 10. AI Task Suggestions
- Task suggestions based on task information
- Suggest subtasks and task priorities

Note: The current local version uses a rule-based suggestion approach. Ollama is not required.

## Technologies Used

### Backend
- PHP
- Laravel 12
- MySQL
- Laravel Sanctum
- Laravel Events and Listeners
- Laravel Jobs and Queues
- Laravel Notifications
- Repository Pattern
- Service Layer
- Form Requests
- Policies

### Frontend
- HTML
- CSS
- JavaScript
- Bootstrap
- Laravel Blade
- AJAX

### Development Tools
- Composer
- Node.js and NPM
- Git
- Postman

## Design Patterns and Concepts

This project uses some common Laravel development practices:

- Repository Pattern for database operations
- Service Layer for business logic
- Dependency Injection
- Interface-based development
- Form Request Validation
- Policy-based Authorization
- Events and Listeners
- Background Jobs
- REST API development

## Requirements

Make sure you have these installed:

- PHP 8.2 or higher
- Composer
- MySQL
- Node.js and NPM

## Installation

### 1. Clone the Repository

```bash
git clone YOUR_REPOSITORY_URL
```

### 2. Open the Project

```bash
cd smart-task-manager
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Create Environment File

```bash
copy .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Open the `.env` file and update your database details:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_task_manager
DB_USERNAME=root
DB_PASSWORD=
```

Create a database named `smart_task_manager` in MySQL.

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Install Frontend Dependencies

```bash
npm install
```

### 9. Build Frontend Assets

```bash
npm run build
```

### 10. Start Laravel Server

```bash
php artisan serve
```

Open the application:

http://127.0.0.1:8000

## Running Background Jobs

This project uses Laravel's database queue.

Make sure your `.env` contains:

```env
QUEUE_CONNECTION=database
```

Run the queue worker in a separate terminal:

```bash
php artisan queue:work
```

## API Testing

You can test the REST APIs using Postman.

API routes are available in:

`routes/api.php`

Authentication is handled using Laravel Sanctum.

## Optional Integrations

The following tools are not required to run the current local version:

- Laravel Reverb: For real-time updates using WebSockets.
- Laravel Horizon: For monitoring Redis queues.
- Ollama: For running AI models locally.

These integrations can be added later.

## Project Structure

```text
app/
├── Events/
├── Http/
│   ├── Controllers/
│   ├── Requests/
├── Jobs/
├── Listeners/
├── Models/
├── Notifications/
├── Policies/
├── Repositories/
└── Services/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
├── api.php
└── console.php

database/
├── migrations/
└── seeders/
```

## Future Improvements

- Real-time task updates using Laravel Reverb
- Queue monitoring using Laravel Horizon and Redis
- AI-powered task suggestions
- Email reminders
- Team-based task assignment
- Role-based access control
- Improved dashboard reports

## Author

Madhu Singh

PHP / Laravel Backend Developer
