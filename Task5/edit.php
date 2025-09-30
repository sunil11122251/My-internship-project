<?php
include("config.php");
include("auth.php");
requireLogin();

$id = $_GET['id'] ?? 0;
$id = (int)$id;
$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'user';

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: index.php");
    exit;
}

// Only admin or owner can edit
if ($role != 'admin' && $post['username'] != $username) {
    header("Location: index.php");
    exit;
}

$msg = "";
$title = $post['title'];
$content = $post['content'];

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title && $content) {
        $stmt = $pdo->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        if ($stmt->execute([$title, $content, $id])) {
            header("Location: index.php");
            exit;
        } else {
            $msg = "Failed to update post.";
        }
    } else {
        $msg = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin:0; padding:0;}
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);}
        h2 { text-align:center; }
        form { display: flex; flex-direction: column; }
        input, textarea { padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 1em; }
        button { padding: 10px; background: #ffc107; color: #212529; border:none; border-radius:5px; cursor:pointer; font-size:1em; }
        button:hover { background: #e0a800; }
        a.back { display:inline-block; margin-top:10px; color:#007bff; text-decoration:none; }
        a.back:hover { text-decoration:underline; }
        .msg { color:red; margin-bottom: 10px; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Post</h2>
    <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
        <textarea name="content" rows="6" required><?= htmlspecialchars($content) ?></textarea>
        <button type="submit" name="submit">Update</button>
    </form>
    <a class="back" href="index.php">⬅ Back to Posts</a>
</div>
</body>
</html>
