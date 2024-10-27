<?php
include('config.php');

// Add Supplier
if (isset($_POST['add_supplier'])) {
    $supplier_name = $_POST['supplier_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];

    $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, contact_number, email, company_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $supplier_name, $contact_number, $email, $company_name);
    $stmt->execute();
    $stmt->close();
}

// Edit Supplier
if (isset($_POST['edit_supplier'])) {
    $id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];

    $stmt = $conn->prepare("UPDATE suppliers SET supplier_name = ?, contact_number = ?, email = ?, company_name = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $supplier_name, $contact_number, $email, $company_name, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete Supplier
if (isset($_POST['delete_supplier'])) {
    $id = $_POST['supplier_id'];
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch Suppliers
$result = $conn->query("SELECT * FROM suppliers ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Suppliers</title>
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
    <h1>Add Supplier</h1>
    <form method="post">
        <input type="text" name="supplier_name" placeholder="Supplier Name" required>
        <input type="text" name="contact_number" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="company_name" placeholder="Company Name" required>
        <button type="submit" name="add_supplier">Add Supplier</button>
    </form>

    <h2>Existing Suppliers</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Supplier Name</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Company Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= $row['id']; ?></td>
                <td><input type="text" name="supplier_name" value="<?= $row['supplier_name']; ?>" required></td>
                <td><input type="text" name="contact_number" value="<?= $row['contact_number']; ?>" required></td>
                <td><input type="email" name="email" value="<?= $row['email']; ?>" required></td>
                <td><input type="text" name="company_name" value="<?= $row['company_name']; ?>" required></td>
                <td>
                    <input type="hidden" name="supplier_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="edit_supplier">Edit</button>
                    <button type="submit" name="delete_supplier">Delete</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
