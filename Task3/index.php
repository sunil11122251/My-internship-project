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
$sqlRole = $conn->prepare("SELECT role FROM users WHERE username=?");
$sqlRole->bind_param("s", $username);
$sqlRole->execute();
$userData = $sqlRole->get_result()->fetch_assoc();
$role = $userData['role'] ?? 'user';

// ✅ Search input
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// ✅ Pagination setup
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// ✅ Build query
if (strtolower($role) === 'admin') {
    $sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
    $countSql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?";
} else {
    $sql = "SELECT * FROM posts WHERE username=? AND (title LIKE ? OR content LIKE ?) ORDER BY id DESC LIMIT ? OFFSET ?";
    $countSql = "SELECT COUNT(*) as total FROM posts WHERE username=? AND (title LIKE ? OR content LIKE ?)";
}

// ✅ Prepare search keyword
$searchTerm = "%$search%";

// ✅ Count total posts
if (strtolower($role) === 'admin') {
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("sss", $username, $searchTerm, $searchTerm);
}
$stmt->execute();
$totalPosts = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);

// ✅ Fetch posts
if (strtolower($role) === 'admin') {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $username, $searchTerm, $searchTerm, $limit, $offset);
}
$stmt->execute();
$posts = $stmt->get_result();
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
        .action-buttons { margin: 15px 0; display: flex; justify-content: space-between; align-items: center; }
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
        .search-box input { padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
        .search-box button { padding: 6px 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; }
        .pagination { margin-top: 20px; }
        .pagination a { margin: 0 5px; padding: 6px 12px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <!-- ✅ Welcome + Role -->
    <h2>👋 Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <span class="role-badge"><?php echo ucfirst($role); ?></span>

    <!-- ✅ Action Buttons -->
    <div class="action-buttons">
        <div>
            <a href="create.php" class="btn btn-add">➕ Add New Post</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
        <!-- ✅ Search Bar -->
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    <hr>
    <h3>All Posts</h3>
    <div class="posts">
        <?php
        if ($posts->num_rows > 0) {
            while ($row = $posts->fetch_assoc()) {
                echo "<div class='post-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";

                echo "<div class='post-actions'>";
                if (strtolower($role) === 'admin' || $row['username'] === $username) {
                    echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>✏ Edit</a> ";
                    echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete'>🗑 Delete</a>";
                }
                echo "</div>";

                echo "<div class='post-footer'>Posted on " . $row['created_at'] . " by <strong>" . htmlspecialchars($row['username']) . "</strong></div>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </div>

    <!-- ✅ Pagination -->
    <div class="pagination">
        <?php 
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = ($i == $page) ? "active" : "";
                echo "<a class='$active' href='?page=$i&search=" . urlencode($search) . "'>$i</a>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
