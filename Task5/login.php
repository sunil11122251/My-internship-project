<?php
include("config.php");
session_start();

$msg = "";
if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; // role from dropdown

    // fetch user only by username and role
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? AND role=? LIMIT 1");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password,$user['password'])){
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $msg = "Invalid username, password or role!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; }
        .container { max-width: 400px; margin: 100px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);}
        h2 { text-align:center; }
        form { display:flex; flex-direction: column; }
        input, select { padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
        button { padding:10px; background:#007bff; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:1em; }
        button:hover { background:#0069d9; }
        .msg { color:red; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="login">Login</button>
    </form>
    <p style="text-align:center;">Don’t have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
