<?php include("config.php"); 
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();
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
        <input type="text" name="title" value="<?php echo $post['title']; ?>" required><br>
        <textarea name="content" rows="5" required><?php echo $post['content']; ?></textarea><br>
        <button type="submit" name="update">Update</button>
    </form>
    <p><a href="index.php">⬅ Back to Posts</a></p>
</div>
</body>
</html>

<?php
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "<p class='msg error'>Error: " . $conn->error . "</p>";
    }
}
?>
