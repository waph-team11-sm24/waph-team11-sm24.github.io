<?php
require 'database.php';

function add_comment($post_id, $comment, $username) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO comments (post_id, comment, username) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $post_id, $comment, $username);
    return $stmt->execute();
}
?>
