<?php
// dashboard.php
include 'db.php'; // We include db.php to start the session

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
        a { color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</h2>
        <p>You are now logged in to your dashboard.</p>
        <p>Your User ID is: <?php echo htmlspecialchars($_SESSION['id']); ?></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
