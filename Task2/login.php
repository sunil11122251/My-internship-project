<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome Back</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register here</a></p>

    <!-- ✅ Show messages inside card -->
    <?php
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php"); // redirect to dashboard
                exit;
            } else {
                echo "<p class='msg error'>❌ Invalid password. Try again.</p>";
            }
        } else {
            echo "<p class='msg error'>⚠️ No account found. Please <a href='register.php'>register</a>.</p>";
        }
    }
    ?>
</div>
</body>
</html>
