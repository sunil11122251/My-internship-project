<?php 
include("config.php"); 

// ✅ Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

// Get user role
$sqlRole = $conn->query("SELECT role FROM users WHERE username='$username'");
$userData = $sqlRole->fetch_assoc();
$role = $userData['role'] ?? 'user';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 15px; }
        h2 { display: inline-block; }
        .role-badge { font-weight: bold; margin-left: 10px; color: #fff; background: #343a40; padding: 3px 8px; border-radius: 5px; font-size: 0.9em; }
        .action-buttons { margin: 15px 0; }
        .btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; margin-right: 5px; display: inline-block; }
        .btn-add { background: #28a745; color: #fff; }
        .btn-logout { background: #dc3545; color: #fff; }
        .posts { display: flex; flex-wrap: wrap; gap: 15px; }
        .post-card { background: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); flex: 1 1 calc(33% - 15px); min-width: 280px; display: flex; flex-direction: column; justify-content: space-between; }
        .post-card h3 { margin-top: 0; }
        .post-actions { margin-top: 10px; }
        .btn-edit { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: #fff; }
        .post-footer { margin-top: auto; font-size: 0.85em; color: #6c757d; }
        @media (max-width: 768px) {
            .post-card { flex: 1 1 calc(50% - 15px); }
        }
        @media (max-width: 500px) {
            .post-card { flex: 1 1 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- ✅ Welcome + Role -->
    <h2>👋 Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <span class="role-badge"><?php echo ucfirst($role); ?></span>

    <!-- ✅ Action Buttons -->
    <div class="action-buttons">
        <a href="create.php" class="btn btn-add">➕ Add New Post</a>
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <hr>
    <h3>All Posts</h3>
    <div class="posts">
        <?php
        // Admin sees all posts; others see only their own
        if (strtolower($role) === 'admin') {
            $sqlPosts = "SELECT * FROM posts ORDER BY id DESC";
        } else {
            $sqlPosts = "SELECT * FROM posts WHERE username='$username' ORDER BY id DESC";
        }

        $result = $conn->query($sqlPosts);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";

                // Edit/Delete buttons only for admin or post owner
                echo "<div class='post-actions'>";
                if (strtolower($role) === 'admin' || $row['username'] === $username) {
                    echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>✏ Edit</a> ";
                    echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete'>🗑 Delete</a>";
                }
                echo "</div>";

                echo "<div class='post-footer'>Posted on " . $row['created_at'] . " by <strong>" . htmlspecialchars($row['username']) . "</strong></div>";
                echo "</div>"; // post-card
            }
        } else {
            echo "<p>No posts yet.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
