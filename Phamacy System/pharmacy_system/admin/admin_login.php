<?php
// Start session
session_start();

// Include database connection
include '../db.php';

// Initialize variables
$message = "";

// Handle the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Get email from the form
    $password = $_POST['password']; // Get password from the form

    // Query to check if the admin exists in the database by email
    $sql = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    // Check if a matching admin is found
    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        // Verify the password
        if ($admin['password'] == $password) {
            // Start session and store admin data
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Admin not found!";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Green Theme CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7e9;
            color: #2e7d32;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input[type="email"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #388e3c;
            border-radius: 4px;
        }
        .message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        button {
            background-color: #388e3c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .back-home {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 15px;
        }
        .back-home a {
            color: #388e3c;
            font-size: 16px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-home a:hover {
            color: #2e7d32;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Admin Login</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="back-home">
            <a href="../index.php">Back to Homepage</a>
        </div>
    </div>

</body>
</html>
