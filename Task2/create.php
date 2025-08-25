<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $username = $_SESSION['username'];

    $sql = "INSERT INTO posts (username, title, content) VALUES ('$username', '$title', '$content')";
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Create New Post</h2>
    <form method="post">
        <input type="text" name="title" placeholder="Enter Title" required><br>
        <textarea name="content" placeholder="Enter Content" required></textarea><br>
        <button type="submit" class="btn btn-add">Submit</button>
    </form>
    <a href="index.php" class="btn btn-back">⬅ Back to Posts</a>
</div>
</body>
</html>
