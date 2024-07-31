<?php
session_start();
require_once 'database.php'; // Include database connection and functions

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $phone = trim($_POST['phone']);

    $errors = [];

    // Validate username (3-20 alphanumeric characters and underscores)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = "Invalid username format";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password (at least one capital letter, one number, one special character, and minimum 8 characters)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = "Password does not meet requirements";
    }

    // Confirm password match
    if ($password !== $password_confirm) {
        $errors[] = "Passwords do not match";
    }

    // Validate phone number (example: 10 digits)
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Invalid phone number format";
    }

    // Check for existing username
    if (usernameExists($username)) {
        $errors[] = "Username already exists";
    }

    if (empty($errors)) {
        // Hash the password
        $hashed_password = md5($password); // Use MD5 as per your requirement

        // Register the user
        registerUser($username, $email, $hashed_password, $phone);

        // Redirect to login page
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlentities($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirm" placeholder="Confirm Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit" class="button">Register</button>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</body>
</html>
