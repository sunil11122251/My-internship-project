🔐 SECURITY.md
📌 Overview

This project is part of the ApexPlanet Internship – Task 4, where we upgraded our PHP + MySQL blog application with a strong focus on security best practices.

The goal was to ensure data protection, secure authentication, and role-based authorization.

✅ Security Features Implemented
1. Password Hashing

User passwords are stored securely using password_hash().

During login, we verify using password_verify().

Ensures passwords are never stored in plain text.

2. Prepared Statements (PDO)

All database queries use PDO prepared statements.

Prevents SQL Injection Attacks by separating SQL logic from user inputs.

3. Session Management

session_start() is called safely across all files.

On login:

Username and role are stored in $_SESSION.

On logout:

Session is destroyed with session_destroy().

Prevents unauthorized access after logout.

4. Role-Based Access Control (RBAC)

Admin:

Can view, edit, and delete all posts.

User:

Can only manage their own posts.

Implemented via helper functions in auth.php:

function isAdmin() { return $_SESSION['role'] === 'admin'; }
function isUser() { return $_SESSION['role'] === 'user'; }


Prevents privilege escalation attacks.

5. Cross-Site Scripting (XSS) Protection

User inputs and database content are escaped using:

htmlspecialchars($data)
nl2br(htmlspecialchars($data))


Ensures malicious scripts cannot be injected into posts.

6. Form Validation

All form inputs (register, login, create, edit) are validated:

Required fields must not be empty.

Passwords must have a minimum length.

SQL queries only execute with valid inputs.

7. Access Control & Authentication

Users must be logged in to access:

index.php

create.php

edit.php

delete.php

Implemented in auth.php with:

function requireLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }
}

🚫 Potential Vulnerabilities Mitigated

❌ SQL Injection → Solved with PDO prepared statements

❌ Plaintext Passwords → Solved with password hashing

❌ Session Hijacking → Solved with session validation & logout

❌ Unauthorized Access → Solved with RBAC + requireLogin()

❌ XSS Attacks → Solved with htmlspecialchars()

🔒 Future Security Improvements (Optional)

Enable HTTPS (SSL/TLS) in production.

Implement CSRF tokens for forms.

Add Rate Limiting to prevent brute-force login attempts.

Use HTTP-only, Secure session cookies.

👨‍💻 Author

Name: Sunil

Internship: ApexPlanet Software Pvt Ltd

Duration: 45 Days (PHP & MySQL)