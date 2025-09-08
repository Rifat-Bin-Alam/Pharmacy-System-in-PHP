<?php
// Start session
session_start();

// Include database connection
include '../db.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    // Query to check if the email and password match
    $sql = "SELECT * FROM users WHERE email = '$user_email' AND password = '$user_password'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Store user details in the session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username']; // Using 'username' as the column

        // Redirect to user dashboard
        header("Location: user_dashboard.php");
        exit();
    } else {
        $message = "Email or Password doesn't match.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        /* Green Theme CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7e9;
            color: #2e7d32;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #388e3c;
            color: white;
            text-align: center;
            padding: 20px;
        }
        h1 {
            margin: 0;
        }
        form {
            max-width: 400px;
            margin: 40px auto;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #388e3c;
            border-radius: 4px;
        }
        button {
            background-color: #388e3c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .back-link, .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a, .register-link a {
            color: #388e3c;
            text-decoration: none;
            font-size: 18px;
        }
        .back-link a:hover, .register-link a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: #d32f2f; /* Red for error messages */
        }
    </style>
</head>
<body>

    <header>
        <h1>User Login</h1>
    </header>

    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="user_login.php" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <p>Don't have an account? <a href="user_registration.php">Register Here</a></p>
    </div>

    <div class="back-link">
        <a href="../index.php">Back to Homepage</a>
    </div>

</body>
</html>
