<?php
include("config.php");
include("auth.php");
requireLogin();

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'user';

// Filter: all posts or my posts
$filter = $_GET['filter'] ?? 'all'; 

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Build SQL
$where = [];
$params = [];

if($filter === 'mine'){
    $where[] = "username=?";
    $params[] = $username;
}

if($search){
    $where[] = "(title LIKE ? OR content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT * FROM posts $whereSql ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$countSql = "SELECT COUNT(*) as total FROM posts $whereSql";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute(array_slice($params, 0, count($params)-2));
$totalPosts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalPosts / $limit);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 20px auto; padding: 0 15px; }
        h2 { display: inline-block; }
        .role-badge { font-weight: bold; margin-left: 10px; background: #343a40; color: #fff; padding: 4px 8px; border-radius: 5px; font-size: 0.9em; }
        .action-buttons { margin: 15px 0; display: flex; justify-content: space-between; align-items: center; }
        .btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; margin-right: 5px; display: inline-block; font-size: 0.9em; }
        .btn-add { background: #28a745; color: #fff; }
        .btn-logout { background: #dc3545; color: #fff; }
        .search-box input, .search-box select { padding: 6px; border-radius: 4px; border: 1px solid #ccc; margin-right: 5px; }
        .search-box button { padding: 6px 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; }
        .posts { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px; }
        .post-card { background: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); flex: 1 1 calc(48% - 15px); min-width: 300px; display: flex; flex-direction: column; justify-content: space-between; }
        .post-card h3 { margin-top: 0; }
        .post-actions { margin-top: 10px; }
        .btn-edit { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: #fff; }
        .post-footer { margin-top: auto; font-size: 0.85em; color: #6c757d; }
        .pagination { margin-top: 20px; }
        .pagination a { margin: 0 5px; padding: 6px 12px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #0056b3; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>👋 Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <span class="role-badge"><?php echo ucfirst($role); ?></span>

    <div class="action-buttons">
        <div>
            <a href="create.php" class="btn btn-add">➕ Add New Post</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="filter">
                <option value="all" <?php if($filter=='all') echo 'selected'; ?>>All Posts</option>
                <option value="mine" <?php if($filter=='mine') echo 'selected'; ?>>My Posts</option>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="posts">
        <?php
        if($posts){
            foreach($posts as $p){
                echo "<div class='post-card'>";
                echo "<h3>" . htmlspecialchars($p['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($p['content'])) . "</p>";
                if($role=='admin' || $p['username']==$username){
                    echo "<div class='post-actions'>";
                    echo "<a href='edit.php?id=".$p['id']."' class='btn btn-edit'>Edit</a> ";
                    echo "<a href='delete.php?id=".$p['id']."' class='btn btn-delete'>Delete</a>";
                    echo "</div>";
                }
                echo "<div class='post-footer'>Posted on " . $p['created_at'] . " by <strong>" . htmlspecialchars($p['username']) . "</strong></div>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        if($totalPages>1){
            for($i=1;$i<=$totalPages;$i++){
                $active = ($i==$page)?'active':'';
                echo "<a class='$active' href='?page=$i&search=".urlencode($search)."&filter=$filter'>$i</a>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
