<?php include_once("config.php"); ?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
/* Same styling as before */
body{font-family:"Segoe UI",sans-serif;background:#f8f9fc;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
.container{background:#fff;padding:30px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.1);width:100%;max-width:400px;text-align:center;}
.container h2{margin-bottom:20px;font-size:24px;font-weight:600;color:#222;}
input,button{width:100%;padding:12px;margin:10px 0;border-radius:8px;font-size:15px;outline:none;}
input[type="text"],input[type="password"]{border:1px solid #ddd;transition:border 0.3s;}
input:focus{border-color:#007bff;}
button{background:#007bff;color:#fff;border:none;cursor:pointer;transition:background 0.3s;}
button:hover{background:#0056b3;}
.msg.success{background:#e6ffed;color:#2e7d32;border:1px solid #81c784;padding:10px;border-radius:6px;}
.msg.error{background:#ffe6e6;color:#c62828;border:1px solid #ef9a9a;padding:10px;border-radius:6px;}
</style>
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
if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if(strlen($username)<3){
        echo "<p class='msg error'>⚠️ Username must be at least 3 characters</p>";
    } elseif(strlen($password)<6){
        echo "<p class='msg error'>⚠️ Password must be at least 6 characters</p>";
    } else {
        $stmt=$pdo->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute([$username]);
        if($stmt->rowCount()>0){
            echo "<p class='msg error'>⚠️ Username already exists. Please <a href='login.php'>login</a>.</p>";
        } else {
            $hashedPassword = password_hash($password,PASSWORD_BCRYPT);
            $stmt=$pdo->prepare("INSERT INTO users (username,password,role) VALUES(?,?, 'user')");
            if($stmt->execute([$username,$hashedPassword])){
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
