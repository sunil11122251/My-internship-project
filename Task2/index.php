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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

    <!-- ✅ Action Buttons -->
    <div class="action-buttons">
        <a href="create.php" class="btn btn-add">➕ Add New Post</a>
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <hr>
    <h3>All Posts</h3>
    <div class="posts">
        <?php
        $result = $conn->query("SELECT * FROM posts WHERE username='$username' ORDER BY id DESC");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                echo "<div class='post-actions'>";
                echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>✏ Edit</a> ";
                echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete'>🗑 Delete</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts yet.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
