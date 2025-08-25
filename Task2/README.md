# ApexPlanet Internship - Task 2

## 📌 Task: Basic CRUD Application (with Authentication)

This is **Task 2** of the **ApexPlanet 45-Day Web Development Internship (PHP & MySQL)**.  
The goal of this task is to build a simple blog-style web application that supports **CRUD operations** (Create, Read, Update, Delete) along with **user authentication** (register, login, logout).

---

## ✅ Features
- **User Registration** (with secure password hashing)
- **User Login & Logout** (session-based authentication)
- **Create Post** (add new blog posts)
- **Read Post** (list all posts)
- **Update Post** (edit existing posts)
- **Delete Post** (remove posts)
- Session management (only logged-in users can manage posts)

---

## ⚡ Database Setup

Run these SQL commands in **phpMyAdmin** (`http://localhost/phpmyadmin/`) to create the database and tables:

```sql
CREATE DATABASE blog;

USE blog;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Posts table (linked to users by username)
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    username VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);


📂 Project Structure

internship-task2/
│── config.php      # Database connection + session
│── register.php    # User Registration
│── login.php       # User Login
│── logout.php      # User Logout
│── index.php       # List posts (Read)
│── create.php      # Add Post
│── edit.php        # Edit Post
│── delete.php      # Delete Post
│── README.md       # Documentation
🚀 How to Run

Example: C:\xampp\htdocs\internship-task2\

Start Apache and MySQL from the XAMPP Control Panel.

Open phpMyAdmin → Create the blog database → Run the SQL queries above to create users and posts tables.

Open your browser and test the app:

👉 http://localhost/internship-task2/register.php – Register a new user

👉 http://localhost/internship-task2/login.php – Login

👉 http://localhost/internship-task2/index.php – Manage posts (Create, Read, Update, Delete)

📜 Deliverables
A working CRUD application with user authentication.

Database schema (users and posts tables).

Source code uploaded to GitHub.

Screen recording demo uploaded to LinkedIn.

👨‍💻 Author
Name: Sunil

Internship: ApexPlanet Software Pvt Ltd

Duration: 45 Days (PHP & MySQL)