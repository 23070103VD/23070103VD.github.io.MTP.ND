<?php
// login.php
include 'db.php'; // Include the database connection file

$message = ''; // Variable to store messages for the user

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a statement to select the user by email
    $stmt = $conn->prepare("SELECT id, fname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a user with that email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fname, $hashed_password);
        $stmt->fetch();

        // Verify the submitted password against the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // Password is correct, so create session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['fname'] = $fname;

            // Redirect user to the dashboard page
            header("location: dashboard.php");
            exit;
        } else {
            // Incorrect password
            $message = "The password you entered was not valid.";
        }
    } else {
        // Incorrect email
        $message = "No account found with that email.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
        input[type="email"], input[type="password"] { width: 100%; padding: 8px; margin: 10px 0; }
        input[type="submit"] { background-color: #008CBA; color: white; padding: 10px; border: none; cursor: pointer; }
        .message { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($message)) { echo "<p class='message'>{$message}</p>"; } ?>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
