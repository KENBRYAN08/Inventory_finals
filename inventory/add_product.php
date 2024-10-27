<?php
include('config.php');

// Add Product
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $sale_date = $_POST['sale_date'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, quantity, price, category_id, sale_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiss", $product_name, $quantity, $price, $category_id, $sale_date);
    
    if ($stmt->execute()) {
        header("Location: add_product.php?success=Product added successfully");
        exit();
    } else {
        echo "Error adding product: " . $stmt->error;
    }

    $stmt->close();
}

// Edit Product
if (isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $sale_date = $_POST['sale_date'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, quantity = ?, price = ?, category_id = ?, sale_date = ? WHERE id = ?");
    $stmt->bind_param("siissi", $product_name, $quantity, $price, $category_id, $sale_date, $id);
    
    if ($stmt->execute()) {
        header("Location: add_product.php?success=Product updated successfully");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
}

// Delete Product
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    // No need to handle sales deletion explicitly if ON DELETE CASCADE is set in the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}


// Fetch Products
$result = $conn->query("SELECT products.*, categories.category_name FROM products JOIN categories ON products.category_id = categories.id ORDER BY products.id ASC");

// Fetch Categories for Dropdown
$category_result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: white;
            background-image: url("image/pic1.jpg");
            margin: 0;
            padding: 0;
            display: flex;
        }
        #sidebar {
            width: 210px;
            background-color: #444;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }
        #sidebar h2 {
            margin: 0;
            font-size: 24px;
            padding-bottom: 20px;
        }
        #sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            text-align: center;
            border: 2px solid #000;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        #sidebar a:hover {
            background-color: #575757;
        }
        #content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: black;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #444;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <h2>Inventory System</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_category.php">Add Categories</a>
        <a href="add_product.php">Add Products</a>
        <a href="sales.php">Add Sales</a>
        <a href="sales_report.php">Sales Report</a>
        <a href="add_supplier.php">Add Suppliers</a>
        <a href="user.php">Manage Users</a>
        <a href="logout.php">Logout</a>
</div>

<div id="content">
    <h1>Add Product</h1>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: lightgreen;"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($category = $category_result->fetch_assoc()): ?>
                <option value="<?= $category['id']; ?>"><?= $category['category_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="date" name="sale_date" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Existing Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Category</th>
            <th>Sale Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= $row['id']; ?></td>
                <td><input type="text" name="product_name" value="<?= htmlspecialchars($row['product_name']); ?>" required></td>
                <td><input type="number" name="quantity" value="<?= $row['quantity']; ?>" required></td>
                <td><input type="number" step="0.01" name="price" value="<?= $row['price']; ?>" required></td>
                <td>
                    <select name="category_id" required>
                        <?php
                        // Reset category result pointer
                        $category_result->data_seek(0);
                        while ($category = $category_result->fetch_assoc()): ?>
                            <option value="<?= $category['id']; ?>" <?= $category['id'] == $row['category_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($category['category_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td><input type="date" name="sale_date" value="<?= $row['sale_date']; ?>" required></td>
                <td>
                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="edit_product">Edit</button>
                    <button type="submit" name="delete_product">Delete</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
