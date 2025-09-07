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

// Fetch the post
if ($role === 'admin') {
    // Admin can fetch any post by id
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
    $stmt->bind_param("i", $id);
} else {
    // Regular users can fetch only their own posts
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id=? AND username=?");
    $stmt->bind_param("is", $id, $username);
}
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "<p class='msg error'>⚠️ Post not found or you don’t have permission.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Post</h2>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br>
        <textarea name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea><br>
        <button type="submit" name="update" class="btn btn-edit">Update</button>
    </form>
    <p><a href="index.php" class="btn btn-back">⬅ Back to Posts</a></p>
</div>
</body>
</html>

<?php
if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($role === 'admin') {
        // Admin can update any post
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $id);
    } else {
        // Regular users can update only their own posts
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=? AND username=?");
        $stmt->bind_param("ssis", $title, $content, $id, $username);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p class='msg error'>Error: " . $stmt->error . "</p>";
    }
}
?>
