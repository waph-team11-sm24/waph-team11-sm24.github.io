<?php
session_start();
require 'database.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    $username = $_SESSION['username'];

    if (add_comment($post_id, $comment, $username)) {
        $comment_success = 'Comment added successfully!';
    } else {
        $comment_error = 'Error adding comment. Please try again.';
    }
}

$posts = get_posts();
require 'profile.php';
?>
