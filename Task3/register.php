<?php include("config.php"); ?>

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
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>

    <?php
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if username exists
        $check = $conn->prepare("SELECT * FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<p class='msg error'>⚠️ Username already exists. Please <a href='login.php'>login</a>.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                echo "<p class='msg success'>✅ Registration successful! Redirecting to login...</p>";
                header("refresh:2;url=login.php");
            } else {
                echo "<p class='msg error'>❌ Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $check->close();
    }
    ?>
</div>
</body>
</html>
