<?php
session_start();
require_once 'database.php';
require 'session_auth.php'; // Include session settings and CSRF token setup


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        $mysqli = connectDB();
        $stmt = $mysqli->prepare("SELECT superuser, disabled FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($superuser, $disabled);
        $stmt->fetch();
        $stmt->close();
        closeDB($mysqli);

        // Debugging output
        echo "Username: $username<br>";
        echo "Disabled: $disabled<br>";

        if ($disabled) {
            $error = "Your account is disabled.";
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['superuser'] = $superuser;
            header("Location: profile.php");
            exit;
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="index.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="button">Login</button>
        </form>
        <p>Don't have an account? <a href="registrationform.php">Register here</a></p>
    </div>
</body>
</html>
