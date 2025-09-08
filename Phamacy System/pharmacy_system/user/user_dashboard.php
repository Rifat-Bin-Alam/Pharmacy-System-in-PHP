<?php
// Start session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// Include database connection
include '../db.php';

// Initialize variables
$message = "";

// Handle Add to Cart functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $user_id = $_SESSION['user_id'];

    // Insert product into the cart
    $sql = "INSERT INTO cart (user_id, product_name) VALUES ('$user_id', '$product_name')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Product added to cart successfully!";
    } else {
        $message = "Error: Could not add product to cart.";
    }
}

// Handle Search functionality
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_query = $_POST['search_query'];

    // Search for products in the database
    $like_query = "%" . $search_query . "%";
    $sql = "SELECT product_name, price FROM products WHERE product_name LIKE '$like_query'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}

// Fetch all products by default
$all_products = [];
$sql = "SELECT product_name, price FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all_products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        header a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="search"] {
            width: calc(100% - 20px);
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
            font-size: 16px;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .message {
            margin: 20px 0;
            color: #2e7d32;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #388e3c;
            color: white;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Search Products</h2>
        <form action="user_dashboard.php" method="POST">
            <input type="search" name="search_query" placeholder="Enter product name..." required>
            <button type="submit" name="search">Search</button>
        </form>

        <?php if (!empty($search_results)): ?>
            <h3>Search Results</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])): ?>
            <p>No products found matching your search.</p>
        <?php endif; ?>

        <!-- Add Check Cart Link -->
        <a href="user_add_to_cart.php" style="text-decoration: none; background-color: #388e3c; color: white; padding: 10px 15px; border-radius: 4px;">Check Cart</a>

        <h2>All Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <form action="user_dashboard.php" method="POST">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

</body>
</html>
