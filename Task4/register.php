<?php include_once("conf.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Create Account</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required minlength="3">
        <input type="password" name="password" placeholder="Enter Password" required minlength="6">
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>

    <?php
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (strlen($username) < 3) {
            echo "<p class='msg error'>⚠️ Username must be at least 3 characters</p>";
        } elseif (strlen($password) < 6) {
            echo "<p class='msg error'>⚠️ Password must be at least 6 characters</p>";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                echo "<p class='msg error'>⚠️ Username already exists. Please <a href='login.php'>login</a>.</p>";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                if ($stmt->execute([$username, $hashedPassword])) {
                    echo "<p class='msg success'>✅ Registration successful! Redirecting...</p>";
                    header("refresh:2;url=login.php");
                } else {
                    echo "<p class='msg error'>❌ Registration failed.</p>";
                }
            }
        }
    }
    ?>
</div>
</body>
</html>
