<?php
include("config.php");
include("auth.php");
requireLogin();

$username = $_SESSION['username'];

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$post_id = (int)$_GET['id'];

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if(!$post){
    die("Post not found.");
}

// Only admin or post owner can delete
if(!isAdmin() && $post['username'] !== $username){
    die("You are not authorized to delete this post.");
}

// Delete post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
$stmt->execute([$post_id]);

header("Location: index.php");
exit();
?>
