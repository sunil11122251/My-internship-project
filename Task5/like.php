<?php
session_start();
include("config.php");

if(isset($_POST['post_id'])){
    $post_id = (int)$_POST['post_id'];
    if(!isset($_SESSION['liked_posts'])) $_SESSION['liked_posts'] = [];
    $liked = false;

    if(in_array($post_id, $_SESSION['liked_posts'])){
        $stmt = $pdo->prepare("UPDATE posts SET likes = likes - 1 WHERE id=?");
        $stmt->execute([$post_id]);
        $_SESSION['liked_posts'] = array_diff($_SESSION['liked_posts'], [$post_id]);
        $liked = false;
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id=?");
        $stmt->execute([$post_id]);
        $_SESSION['liked_posts'][] = $post_id;
        $liked = true;
    }

    $stmt = $pdo->prepare("SELECT likes FROM posts WHERE id=?");
    $stmt->execute([$post_id]);
    $likes = $stmt->fetchColumn();

    echo json_encode(['success'=>true,'likes'=>$likes,'liked'=>$liked]);
}
?>
