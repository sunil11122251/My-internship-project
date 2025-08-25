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

    <!-- ✅ Show success/error messages inside card -->
    <?php
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if username exists
        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($check->num_rows > 0) {
            echo "<p class='msg error'>⚠️ Username already exists. Please <a href='login.php'>login</a>.</p>";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='msg success'>✅ Registration successful! Redirecting to login...</p>";
                header("refresh:2;url=login.php"); // auto redirect in 2 sec
            } else {
                echo "<p class='msg error'>❌ Error: " . $conn->error . "</p>";
            }
        }
    }
    ?>
</div>
</body>
</html>
