
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy System</title>
    <style>
        /* Green Theme CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7e9;
            color: #2e7d32;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
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
        nav {
            text-align: center;
            margin-top: 40px;
        }
        nav a {
            color: #388e3c;
            text-decoration: none;
            margin: 0 20px;
            font-size: 18px;
            font-weight: normal; /* Not bold */
            transition: all 0.3s ease;
        }
        nav a:hover {
            text-decoration: underline;
            color: #2e7d32;
            transform: scale(1.1);
        }
        .container {
            margin-top: 150px; /* Push the content down a bit */
            text-align: center;
            flex: 1; /* Make this section flexible to take up available space */
        }
        .container h2 {
            font-size: 24px;
            font-weight: bold;
        }
        .container p {
            font-size: 18px;
            font-weight: normal; /* Not bold */
        }
        footer {
            background-color: #388e3c;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome to the Pharmacy System</h1>
    </header>

    <nav>
        <a href="user/user_registration.php">User Registration</a>
        <a href="user/user_login.php">User Login</a>
        <a href="admin/admin_login.php">Admin Login</a>
    </nav>

    <div class="container">
        <h2>Buy from Pharmacy</h2>
        <p>Register or Log in to manage products, view your cart, and more!</p>
    </div>

    <footer>
        <p>Pharmacy System</p>
    </footer>

</body>
</html>
