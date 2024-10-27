<?php
include('config.php');

// Fetch Sales Data with Product and Category Info
$query = "
    SELECT 
        sales.quantity,
        sales.sale_date,
        products.product_name,
        categories.category_name,
        products.price
    FROM 
        sales
    JOIN 
        products ON sales.product_id = products.id
    JOIN 
        categories ON products.category_id = categories.id
    ORDER BY 
        sales.sale_date DESC
";

$result = $conn->query($query);

// Initialize totals
$total_sales = 0;
$total_quantity = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
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
        .totals {
            font-weight: bold;
            margin-top: 20px;
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
    <h1>Sales Report</h1>
    <h2>Existing Sales</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Sale Date</th>
            <th>Quantity Sold</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']); ?></td>
            <td><?= htmlspecialchars($row['category_name']); ?></td>
            <td><?= number_format($row['price'], 2); ?></td>
            <td><?= htmlspecialchars($row['sale_date']); ?></td>
            <td><?= htmlspecialchars($row['quantity']); ?></td>
        </tr>
        <?php 
            $total_sales += $row['price'] * $row['quantity'];
            $total_quantity += $row['quantity'];
        endwhile; ?>
    </table>

    <div class="totals">
        <p>Total Sales: <?= number_format($total_sales, 2); ?></p>
        <p>Total Quantity Sold: <?= $total_quantity; ?></p>
    </div>
    <button onclick="window.print()">Print Table</button>
</div>


</body>
</html>
