# 🟢 ApexPlanet Internship – Task 3

![PHP](https://img.shields.io/badge/PHP-7.4-blue?logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-green?logo=mysql\&logoColor=white)
![Task 3](https://img.shields.io/badge/Task-3-brightgreen)

## 📌 Task: Advanced Features Implementation

This is **Task 3** of the **ApexPlanet 45-Day Web Development Internship (PHP & MySQL)**.
The goal is to **enhance the blog application from Task 2** with:

* **Search posts** by title/content
* **Pagination** for post listing
* **Role-based access control** (Admin/User)
* **Improved UI with Bootstrap & Custom CSS**

---

## ✅ Features

**🛡 Role-Based Access Control (RBAC)**

* **Admin:** Can view, edit, delete **all posts**
* **User:** Can create posts and manage **only their own posts**

**✏️ CRUD Post Management**

* **Create Post** – Add new posts
* **Read Post** – View posts with **pagination**
* **Update Post** – Edit posts (**role-based**)
* **Delete Post** – Delete posts (**role-based**)

**🔎 Search Functionality**

* Search posts by **title** or **content**
* Display results dynamically
* Implemented using **prepared statements for security**

**📄 Pagination**

* Display **5 posts per page**
* Navigation using **Previous / Next buttons** and **page numbers**

**🎨 User Interface Improvements**

* Clean layout using **Bootstrap 5 + Custom CSS**
* Styled buttons, forms, cards, and messages for **better UX**

**🔒 Security Enhancements**

* Prevent **SQL Injection** with prepared statements
* Session management for **authenticated access**
* Password hashing using `password_hash()`

---

## ⚡ Database Setup

Run these SQL commands in **phpMyAdmin** (`http://localhost/phpmyadmin/`):

```sql
CREATE DATABASE blog;

USE blog;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);

-- Posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    username VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);
```

---

## 📂 Project Structure

```
internship-task3/
│── config.php      # Database connection + session start
│── register.php    # User Registration
│── login.php       # Login page with role selection
│── logout.php      # User Logout
│── index.php       # Dashboard (Read posts + Search + Pagination)
│── create.php      # Add Post
│── edit.php        # Edit Post (role-based)
│── delete.php      # Delete Post (role-based)
│── style.css       # Custom CSS for UI
│── blog.sql        # Database schema + sample data
│── README.md       # Documentation
```

---

## 🚀 How to Run

1. Copy the `internship-task3/` folder to **XAMPP `htdocs`**:

```
C:\xampp\htdocs\internship-task3\
```

2. Start **Apache** and **MySQL** from XAMPP Control Panel.

3. Open **phpMyAdmin**, create the **blog database**, and import `blog.sql`.

4. Open your browser and test the application:

* **Register a new user**:
  👉 `http://localhost/internship-task3/register.php`

* **Login as Admin/User**:
  👉 `http://localhost/internship-task3/login.php`

* **Dashboard with search, pagination, and post management**:
  👉 `http://localhost/internship-task3/index.php`

---

## 🖼 Role-Based Workflow Diagram

```
        +----------------+
        |  Login Page    |
        | (Username +    |
        | Password + Role)|
        +--------+-------+
                 |
                 v
        +----------------+
        |  Session Setup |
        +--------+-------+
                 |
         +-------+--------+
         | Role Check     |
         v                v
+----------------+   +----------------+
| Admin Dashboard|   | User Dashboard |
| - View All     |   | - View Posts   |
| - Edit/Delete  |   | - Edit/Delete  |
|   Any Post     |   |   Own Posts    |
+----------------+   +----------------+
                 |
                 v
           CRUD Operations
       (Create, Read, Update, Delete)
       with Search + Pagination
```

---

## 📜 Deliverables

* **💾 Source code** (`index.php`, `login.php`, `register.php`, `edit.php`, `delete.php`, etc.)
* **🗄 Database schema** (`blog.sql`)
* **🎥 Screen recording demo** (shared on LinkedIn)

---

## 👨‍💻 Author

**Name:** Sunil
**Internship:** ApexPlanet Software Pvt Ltd
**Duration:** 45 Days (PHP & MySQL)
