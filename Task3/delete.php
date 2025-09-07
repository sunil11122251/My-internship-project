<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$username = $_SESSION['username'];

// Get user role
$sqlRole = $conn->query("SELECT role FROM users WHERE username='$username'");
$userData = $sqlRole->fetch_assoc();
$role = strtolower($userData['role'] ?? 'user');

// Delete post
if ($role === 'admin') {
    // Admin can delete any post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $id);
} else {
    // Regular users can delete only their own posts
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=? AND username=?");
    $stmt->bind_param("is", $id, $username);
}

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    echo "<p class='msg error'>Error: " . $stmt->error . "</p>";
}
?>
