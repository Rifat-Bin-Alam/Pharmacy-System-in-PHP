<?php
// Start session
session_start();

// Redirect to login if the admin is not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include '../db.php';

// Get the admin's name from the session
$admin_name = $_SESSION['admin_username'];

// Initialize variables
$message = "";
$product_to_edit = null;

// Handle Add Product functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Insert new product into the database
    $sql = "INSERT INTO products (product_name, price) VALUES ('$product_name', $price)";
    if ($conn->query($sql) === TRUE) {
        $message = "Product added successfully!";
    } else {
        $message = "Error: Could not add product.";
    }
}

// Handle Delete Product functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Error: Could not delete product.";
    }
}

// Handle Edit Product functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Update the product details in the database
    $sql = "UPDATE products SET product_name = '$product_name', price = $price WHERE product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error: Could not update product.";
    }
}

// Fetch all products
$products = [];
$sql = "SELECT product_id, product_name, price FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Check if an edit request was made for a specific product
if (isset($_GET['edit_product_id'])) {
    $product_id = $_GET['edit_product_id'];
    $sql = "SELECT product_id, product_name, price FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product_to_edit = $result->fetch_assoc();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        input[type="text"], input[type="number"], input[type="submit"] {
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
        <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h1>
        <a href="admin_logout.php">Logout</a>
    </header>

    <main>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Add New Product</h2>
        <form action="admin_dashboard.php" method="POST">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Product Price" required step="0.01">
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <?php if ($product_to_edit): ?>
            <h2>Edit Product</h2>
            <form action="admin_dashboard.php" method="POST">
                <input type="text" name="product_name" value="<?php echo htmlspecialchars($product_to_edit['product_name']); ?>" required>
                <input type="number" name="price" value="<?php echo htmlspecialchars($product_to_edit['price']); ?>" required step="0.01">
                <input type="hidden" name="product_id" value="<?php echo $product_to_edit['product_id']; ?>">
                <button type="submit" name="edit_product">Update Product</button>
            </form>
        <?php endif; ?>

        <h2>All Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <a href="admin_dashboard.php?edit_product_id=<?php echo $product['product_id']; ?>">Edit</a> |
                            <form action="admin_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                <button type="submit" name="delete_product">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>

</body>
</html>
