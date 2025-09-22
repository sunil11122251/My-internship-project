<?php
include("config.php");
include("auth.php");
requireLogin();

$username = $_SESSION['username'];

$msg = "";

if(isset($_POST['submit'])){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if($title && $content){
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, username, created_at) VALUES (?, ?, ?, NOW())");
        if($stmt->execute([$title, $content, $username])){
            header("Location: index.php");
            exit;
        } else {
            $msg = "Failed to create post.";
        }
    } else {
        $msg = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin:0; padding:0;}
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);}
        h2 { text-align:center; }
        form { display: flex; flex-direction: column; }
        input, textarea { padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 1em; }
        button { padding: 10px; background: #28a745; color: #fff; border:none; border-radius:5px; cursor:pointer; font-size:1em; }
        button:hover { background: #218838; }
        a.back { display:inline-block; margin-top:10px; color:#007bff; text-decoration:none; }
        a.back:hover { text-decoration:underline; }
        .msg { color:red; margin-bottom: 10px; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Create New Post</h2>
    <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
        <input type="text" name="title" placeholder="Enter Title" required>
        <textarea name="content" rows="6" placeholder="Enter Content" required></textarea>
        <button type="submit" name="submit">Submit</button>
    </form>
    <a class="back" href="index.php">⬅ Back to Posts</a>
</div>
</body>
</html>
