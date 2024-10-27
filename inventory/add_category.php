<?php
include('config.php');

// Add Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->close();
}

// Edit Category
if (isset($_POST['edit_category'])) {
    $id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE id = ?");
    $stmt->bind_param("si", $category_name, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete Category
if (isset($_POST['delete_category'])) {
    $id = $_POST['category_id'];
    // Ensure you do not have products linked to this category before deleting
    $conn->query("SET foreign_key_checks = 0");
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->query("SET foreign_key_checks = 1");
}

// Fetch Categories
$result = $conn->query("SELECT * FROM categories ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Categories</title>
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
    <h1>Add Category</h1>
    <form method="post">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>

    <h2>Existing Categories</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= $row['id']; ?></td>
                <td><input type="text" name="category_name" value="<?= $row['category_name']; ?>" required></td>
                <td>
                    <input type="hidden" name="category_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="edit_category">Edit</button>
                    <button type="submit" name="delete_category">Delete</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
