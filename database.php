<?php
function connectDB() {
    $servername = "localhost";
    $username = "vibudhvh";
    $password = "1010";
    $dbname = "waph_team";

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    return $mysqli;
}

function closeDB($mysqli) {
    $mysqli->close();
}

function registerUser($username, $password, $name, $email, $phone) {
    $mysqli = connectDB();
    $hashedPassword = md5($password);

    $stmt = $mysqli->prepare("INSERT INTO users (username, password, name, email, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $hashedPassword, $name, $email, $phone);
    $result = $stmt->execute();
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function authenticateUser($username, $password) {
    $mysqli = connectDB();
    $hashedPassword = md5($password);

    $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();
    $stmt->store_result();
    $result = $stmt->num_rows > 0;
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function getUserProfile($username) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT name, email, phone FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($name, $email, $phone);
    $stmt->fetch();
    $stmt->close();
    closeDB($mysqli);

    return array('name' => $name, 'email' => $email, 'phone' => $phone);
}

function updateUserProfile($username, $name, $email, $phone) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE username = ?");
    $stmt->bind_param("ssss", $name, $email, $phone, $username);
    $result = $stmt->execute();
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function changeUserPassword($username, $oldPassword, $newPassword) {
    $mysqli = connectDB();
    $hashedOldPassword = md5($oldPassword);
    $hashedNewPassword = md5($newPassword);

    $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashedOldPassword);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashedNewPassword, $username);
        $result = $stmt->execute();
    } else {
        $result = false;
    }
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function addPost($username, $title, $content) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("INSERT INTO posts (title, content, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $username);
    $result = $stmt->execute();
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function getAllPosts() {
    $mysqli = connectDB();
    $posts = array();

    $stmt = $mysqli->prepare("SELECT posts.postID, posts.title, posts.content, posts.date, users.username FROM posts JOIN users ON posts.username = users.username ORDER BY posts.date DESC");
    $stmt->execute();
    $stmt->bind_result($postID, $title, $content, $date, $username);

    while ($stmt->fetch()) {
        $posts[] = array('postID' => $postID, 'title' => $title, 'content' => $content, 'date' => $date, 'username' => $username);
    }
    $stmt->close();
    closeDB($mysqli);

    return $posts;
}

function getPostByID($postID) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT postID, title, content, date, username FROM posts WHERE postID = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    closeDB($mysqli);

    return $post;
}

function updatePost($postID, $title, $content) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("UPDATE posts SET title = ?, content = ? WHERE postID = ?");
    $stmt->bind_param("ssi", $title, $content, $postID);
    $result = $stmt->execute();
    $stmt->close();
    closeDB($mysqli);

    return $result;
}
function deletePost($postID, $username) {
    $mysqli = connectDB();

    // Check if the post belongs to the user
    $stmt = $mysqli->prepare("SELECT username FROM posts WHERE postID = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->bind_result($postOwner);
    $stmt->fetch();
    $stmt->close();

    if ($postOwner === $username) {
        // Delete associated comments first
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE postID = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $stmt->close();

        // Delete the post
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE postID = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $stmt->close();

        closeDB($mysqli);
        return true;
    }

    closeDB($mysqli);
    return false;
}

function addComment($postID, $content, $username) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("INSERT INTO comments (postID, content, username) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $postID, $content, $username);
    $result = $stmt->execute();
    $stmt->close();
    closeDB($mysqli);

    return $result;
}

function getCommentsByPostID($postID) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT content, username, date FROM comments WHERE postID = ? ORDER BY date DESC");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    closeDB($mysqli);

    return $comments;
}

function postExists($postID) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT 1 FROM posts WHERE postID = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    closeDB($mysqli);

    return $exists;
}

function getUserByEmail($email) {
    $mysqli = connectDB();
    $stmt = $mysqli->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    closeDB($mysqli);

    return $user;
}

?>
