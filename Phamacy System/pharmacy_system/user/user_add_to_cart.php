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

// Handle Remove from Cart functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    // Delete the product from the cart
    $sql = "DELETE FROM cart WHERE cart_id = $cart_id";
    
    if ($conn->query($sql)) {
        $message = "Product removed from cart successfully!";
    } else {
        $message = "Error: Could not remove product from cart.";
    }
}

// Retrieve products in the cart for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT cart_id, product_name FROM cart WHERE user_id = $user_id";
$result = $conn->query($sql);
$cart_products = [];

while ($row = $result->fetch_assoc()) {
    $cart_products[] = $row;
}

// Calculate the total price
$total_price = 0;
foreach ($cart_products as $cart_item) {
    $product_name = $cart_item['product_name'];
    $sql = "SELECT price FROM products WHERE product_name = '$product_name'";
    $product_result = $conn->query($sql);
    
    if ($product_row = $product_result->fetch_assoc()) {
        $total_price += $product_row['price'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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
        button {
            background-color: #388e3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .total-price {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #388e3c;
        }
    </style>
</head>
<body>

<header>
    <h1>Your Cart</h1>
    <a href="user_dashboard.php">Back to Products</a>
</header>

<main>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($cart_products)): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $cart_item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cart_item['product_name']); ?></td>
                        <td>
                            <form action="user_add_to_cart.php" method="POST">
                                <input type="hidden" name="cart_id" value="<?php echo $cart_item['cart_id']; ?>">
                                <button type="submit" name="remove_from_cart">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total-price">Total Price: ৳<?php echo number_format($total_price, 2); ?></div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</main>

</body>
</html>
