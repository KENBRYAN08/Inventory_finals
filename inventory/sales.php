<?php
include('config.php');

// Fetch Sales Data with Product and Category Info
$query = "
    SELECT 
        sales.id AS sale_id,
        sales.quantity,
        sales.sale_date,
        products.product_name,
        categories.category_name
    FROM 
        sales
    JOIN 
        products ON sales.product_id = products.id
    JOIN 
        categories ON products.category_id = categories.id
    ORDER BY 
        sales.sale_date DESC
";

// Execute the sales query
$result = $conn->query($query);

// Fetch available products for the dropdown
$product_query = "SELECT id, product_name FROM products";
$product_result = $conn->query($product_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Management</title>
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
    <h1>Sales Management</h1>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: lightgreen;"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

<?php
    // Display form to add a new sale
    echo "<h2>Add Sale</h2>";
    if ($product_result && $product_result->num_rows > 0) {
        echo "<form method='post'>";
        echo "<select name='product_id' required>";
        echo "<option value=''>Select Product</option>";

        // Populate product options
        while ($product = $product_result->fetch_assoc()) {
            echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
        }

        echo "</select>";
        echo "<input type='number' name='quantity' placeholder='Quantity Sold' required>";
        echo "<input type='date' name='sale_date' required>";
        echo "<button type='submit' name='add_sale'>Add Sale</button>";
        echo "</form>";
    } else {
        echo "No available products to sell.";
    }

    // Handle adding new sales
    if (isset($_POST['add_sale'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $sale_date = $_POST['sale_date'];

        $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, sale_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $product_id, $quantity, $sale_date);

        if ($stmt->execute()) {
            header("Location: sales.php?success=Sale added successfully");
            exit();
        } else {
            echo "Error adding sale: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle editing sales
    if (isset($_POST['edit_sale'])) {
        $sale_id = $_POST['sale_id'];
        $quantity = $_POST['quantity'];

        $stmt = $conn->prepare("UPDATE sales SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $sale_id);

        if ($stmt->execute()) {
            header("Location: sales.php?success=Sale updated successfully");
            exit();
        } else {
            echo "Error updating sale: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle deleting sales
    if (isset($_POST['delete_sale'])) {
        $sale_id = $_POST['sale_id'];

        $stmt = $conn->prepare("DELETE FROM sales WHERE id = ?");
        $stmt->bind_param("i", $sale_id);
        
        if ($stmt->execute()) {
            header("Location: sales.php?success=Sale deleted successfully");
            exit();
        } else {
            echo "Error deleting sale: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>

    <h2>Existing Sales</h2>
    <?php


    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Sale ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity Sold</th>
                <th>Sale Date</th>
              </tr>";

        // Fetch sales data and display it in the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['sale_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['category_name']}</td>
                    <td>
                        <form method='post'>
                            <input type='number' name='quantity' value='{$row['quantity']}' required>
                            <input type='hidden' name='sale_id' value='{$row['sale_id']}'>
                            <button type='submit' name='edit_sale'>Edit</button>
                            <button type='submit' name='delete_sale'>Delete</button>
                        </form>
                    </td>
                    <td>{$row['sale_date']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No sales found.";
    }
?>


</div>

</body>
</html>
