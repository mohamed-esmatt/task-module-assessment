# Task Management Module - Frontend

This is the React/Vite frontend for the Task Management Module.

## Technologies

- React 18
- Vite
- Axios for API calls
- React Router DOM for routing
- Bootstrap 5 for styling

## Folder Structure

frontend/
│
├── src/
│ ├── api.js # Axios instance
│ ├── App.jsx
│ ├── main.jsx
│ └── pages/
│ ├── Login.jsx
│ ├── Signup.jsx
│ └── Dashboard.jsx
│
├── index.html
├── package.json
└── vite.config.js


## Setup

1. Install dependencies:

```bash
npm install

    Start the development server:

npm run dev

    Open the frontend in your browser:

http://localhost:5173

Features

    User registration and login

    Task creation, editing, completion, and deletion

    Task filtering and dynamic status: Done, Missed, Due Today, Upcoming

    Session-based authentication with API

    Responsive UI with Bootstrap

Notes

    Ensure the backend is running on http://localhost:8000.

    Axios is configured to send requests with credentials to the backend.