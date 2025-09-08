<?php
include '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $message = "Registration successful! You can now sign in.";
    } else {
        $message = "Error: Could not complete registration. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
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
        .back-link, .sign-in-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a, .sign-in-link a {
            color: #388e3c;
            text-decoration: none;
            font-size: 18px;
        }
        .back-link a:hover, .sign-in-link a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: #2e7d32;
        }
    </style>
</head>
<body>

    <header>
        <h1>User Registration</h1>
    </header>

    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <div class="sign-in-link">
            <a href="user_login.php">Sign In</a>
        </div>
    <?php endif; ?>

    <form action="user_registration.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <div class="back-link">
        <a href="../index.php">Back to Homepage</a>
    </div>

</body>
</html>
