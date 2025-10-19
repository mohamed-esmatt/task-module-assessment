# Task Management Module

A secure web application for managing tasks with assignment capabilities.  
Users can register, log in, create tasks, assign them to other users, and manage tasks with dynamic statuses.

---

## 🗂 Folder Structure

task-module-assessment/
│
├── backend/ # PHP backend
│ ├── api/
│ │ ├── auth/
│ │ │ ├── login.php
│ │ │ └── signup.php
│ │ ├── tasks/
│ │ │ └── index.php
│ │ └── logout.php
│ ├── db.php
│ └── .htaccess
│
├── frontend/ # React frontend
│ ├── src/
│ │ ├── api.js # Axios instance
│ │ ├── App.jsx
│ │ ├── main.jsx
│ │ └── pages/
│ │ ├── Login.jsx
│ │ ├── Signup.jsx
│ │ └── Dashboard.jsx
│ ├── index.html
│ ├── package.json
│ ├── package-lock.json
│ └── vite.config.js
│
├── README.md # This file
└── .gitignore


---

## ⚙️ Backend Setup

### Requirements

- PHP 8.x
- MySQL
- PDO extension enabled
- XAMPP/LAMP or native PHP server

### Steps

1. Navigate to the backend folder:

```bash
cd backend

    Create the MySQL database (example name: task_module):

CREATE DATABASE task_module;

    Create the tables:

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

    Start the PHP development server:

php -S localhost:8000

    Backend API will be available at http://localhost:8000/api/.

⚛️ Frontend Setup
Requirements

    Node.js v18+

    npm v9+

Steps

    Navigate to the frontend folder:

cd frontend

    Install dependencies:

npm install

    Start the development server:

npm run dev

    Open in browser:

http://localhost:5173

🔗 API Endpoints
Auth
Endpoint	Method	Description
/api/auth/signup.php	POST	Register a new user
/api/auth/login.php	POST	Login and start a session
/api/logout.php	GET	Logout and destroy session
Tasks
Endpoint	Method	Description
/api/tasks/index.php	GET	Get tasks assigned to logged-in user
/api/tasks/index.php	POST	Create a new task
/api/tasks/index.php	PUT	Update a task (mark complete/incomplete)
/api/tasks/index.php	DELETE	Delete a task
✅ Features

    User registration and login

    Task creation, editing, completion, and deletion

    Assign tasks to other users

    Task dynamic statuses: Done, Missed/Late, Due Today, Upcoming

    Task visibility restricted to assignee

    Session-based authentication

    Responsive UI with Bootstrap

    CORS-enabled for frontend-backend communication

⚠️ Notes

    Backend is running on http://localhost:8000

    Frontend is running on http://localhost:5173

    Axios is configured to send requests with credentials to the backend

    Use .gitignore to ignore node_modules/ and temporary files

🛠 Run Full Flow

    Start backend server (php -S localhost:8000 in backend/)

    Start frontend server (npm run dev in frontend/)

    Open browser: http://localhost:5173

    Sign up → login → create and manage tasks