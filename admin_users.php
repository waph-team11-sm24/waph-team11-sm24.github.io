<?php
session_start();
require_once 'database.php';

// Check if the user is logged in and is a superuser
if (!isset($_SESSION['username']) || $_SESSION['superuser'] != 1) {
    header("Location: index.php");
    exit;
}

$mysqli = connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['username'])) {
        $username = $_POST['username'];
        $action = $_POST['action'];
        
        if ($action == 'enable') {
            $stmt = $mysqli->prepare("UPDATE users SET disabled = 0 WHERE username = ?");
        } elseif ($action == 'disable') {
            $stmt = $mysqli->prepare("UPDATE users SET disabled = 1 WHERE username = ?");
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }
}

$stmt = $mysqli->prepare("SELECT username, name, email, phone, disabled FROM users");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
closeDB($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Admin Users</h1>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlentities($user['username']); ?></td>
                    <td><?php echo htmlentities($user['name']); ?></td>
                    <td><?php echo htmlentities($user['email']); ?></td>
                    <td><?php echo htmlentities($user['phone']); ?></td>
                    <td><?php echo $user['disabled'] ? 'Disabled' : 'Enabled'; ?></td>
                    <td>
                        <form method="post" action="admin_users.php" style="display:inline;">
                            <input type="hidden" name="username" value="<?php echo htmlentities($user['username']); ?>">
                            <?php if ($user['disabled']): ?>
                                <button type="submit" name="action" value="enable">Enable</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="disable">Disable</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
