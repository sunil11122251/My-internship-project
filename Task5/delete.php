<?php
include("config.php");
include("auth.php");
requireLogin();

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'user';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = (int)$_GET['id'];

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("<p style='text-align:center;color:red;'>Post not found. <a href='index.php'>Back to Dashboard</a></p>");
}

// Only admin or post owner can delete
if ($role != 'admin' && $post['username'] != $username) {
    die("<p style='text-align:center;color:red;'>You are not authorized to delete this post. <a href='index.php'>Back to Dashboard</a></p>");
}

// Delete post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
$stmt->execute([$post_id]);

header("Location: index.php");
exit();
?>
