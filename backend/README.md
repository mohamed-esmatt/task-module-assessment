# Task Management Module - Backend

This is the backend API for the Task Management Module. It provides authentication, task CRUD operations, and session management.

## Technologies

- PHP 8.x
- MySQL
- PDO for database access
- Session-based authentication
- CORS enabled for frontend integration

## Folder Structure

backend/
│
├── api/
│ ├── auth/
│ │ ├── login.php
│ │ └── signup.php
│ ├── tasks/
│ │ └── index.php
│ └── logout.php
│
├── db.php
└── .htaccess


## Setup

1. Make sure XAMPP/LAMP is installed and running.
2. Create a database in MySQL called `task_module`.
3. Run the following SQL to create tables:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description TEXT,
  due_date DATE NOT NULL,
  priority ENUM('low','medium','high') DEFAULT 'low',
  is_completed TINYINT(1) DEFAULT 0,
  creator_id INT,
  assignee_id INT,
  FOREIGN KEY (creator_id) REFERENCES users(id),
  FOREIGN KEY (assignee_id) REFERENCES users(id)
);

    Start the PHP built-in server (or use XAMPP Apache):

php -S localhost:8000

    Your API will now be available at:

http://localhost:8000/api/

API Endpoints
Auth

    POST /api/auth/signup.php — Register a new user

    POST /api/auth/login.php — Login and start a session

    GET /api/logout.php — Logout and destroy session

Tasks

    GET /api/tasks/index.php — Get tasks assigned to the logged-in user

    POST /api/tasks/index.php — Create a new task

    PUT /api/tasks/index.php — Update a task (mark complete/incomplete)

    DELETE /api/tasks/index.php — Delete a task

Notes

    All requests must support CORS from http://localhost:5173.

    Session-based authentication is used. Ensure the frontend sends requests with credentials.