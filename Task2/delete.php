<?php include("config.php"); 
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$sql = "DELETE FROM posts WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "<p class='msg error'>Error: " . $conn->error . "</p>";
}
?>
