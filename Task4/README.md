# Task 4 – Secure Blog Application

**Company:** ApexPlanet Software Pvt Ltd  
**Developer:** Sunil  
**Timeline:** 10 Days  

---

## Overview
This project is a secure blog application that allows users to create, view, edit, and delete posts. It includes user authentication, role-based access control, search and filter functionality, pagination, and styling for a modern interface.

The application implements security best practices including prepared statements, hashed passwords, and form validation.

---

## Features

### 1. Authentication & Roles
- **Login & Logout** functionality.
- User roles: `admin` and `user`.
- Admins can view, edit, and delete all posts.
- Users can manage only their own posts.
- Role selection is required during login.

### 2. Posts Management
- **Create**, **Edit**, **Delete** posts.
- **Search & Filter** posts by keywords and owner.
- **Pagination** to display posts in pages.
- Posts are displayed in a styled card layout.

### 3. Security
- **SQL Injection Protection** using PDO prepared statements.
- **Password Security** with `password_hash()` and `password_verify()`.
- **Server-side & Client-side Form Validation** for data integrity.
- Role-based access control to restrict unauthorized actions.

### 4. UI / UX
- Responsive design using CSS.
- Modern styled buttons, search bar, cards, and badges for roles.
- Easy navigation and user-friendly interface.

---

## Installation

1. **Clone the repository** to your local machine:
   ```bash
   git clone <repo-url>
````

2. **Setup XAMPP / WAMP** server.
3. **Create the database** using phpMyAdmin or import the `blog.sql` file provided.
4. **Update database configuration** in `config.php`:

   ```php
   <?php
   $host = 'localhost';
   $dbname = 'blog';
   $username = 'root';
   $password = '';
   try {
       $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die("DB Connection failed: " . $e->getMessage());
   }
   ?>
   ```
5. **Start the server** and open `http://localhost/<project-folder>/index.php`.

---

## File Structure

```
/project-folder
├── config.php        # Database connection
├── auth.php          # Authentication and login check
├── index.php         # Dashboard with posts listing
├── create.php        # Create new post
├── edit.php          # Edit existing post
├── delete.php        # Delete a post
├── login.php         # User login
├── logout.php        # Logout user
├── style.css         # Styling for all pages
├── blog.sql          # Database dump (tables: users, posts)
└── README.md         # Project documentation
```

---

## Database

* Database name: `blog`
* Tables:

  * **users**: `id`, `username`, `password`, `role`
  * **posts**: `id`, `username`, `title`, `content`, `created_at`

---

## Usage

1. Login as **admin** or **user** using credentials from the `users` table.
2. Navigate the dashboard:

   * Add new posts.
   * Search or filter posts.
   * Edit/Delete posts based on role.
3. Logout when finished.

---

## Security Measures

* **Prepared statements** to prevent SQL injection.
* **Password hashing** for user passwords.
* **Role-based access control**.
* **Form validation** for all user inputs.

---

## Notes

* Ensure the database is imported before running the application.
* Compatible with **XAMPP / WAMP** local server.
* Tested in **PHP 8+** environment.

---
👨‍💻 Author

Name: Sunil

Internship: ApexPlanet Software Pvt Ltd

Duration: 45 Days (PHP & MySQL)

