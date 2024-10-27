<?php
session_start();
include 'config.php';

// Handle add/edit/delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add User
    if (isset($_POST['add_user'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact = $_POST['contact'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, contact, username, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $contact, $username, $email, $password);
        $stmt->execute();
        $stmt->close();
    }

// Edit User
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update in database
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, contact=?, username=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $first_name, $last_name, $contact, $username, $email, $user_id);
    $stmt->execute();
    $stmt->close();
}

    // Delete User
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch users for displaying
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
    <h2>User Management</h2>
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
    <h1>Manage Users</h1>
    <form method="post">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="contact" placeholder="Contact" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h2>Existing Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Contact</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= $row['id']; ?></td>
                <td><input type="text" name="first_name" value="<?= $row['first_name']; ?>" required></td>
                <td><input type="text" name="last_name" value="<?= $row['last_name']; ?>" required></td>
                <td><input type="text" name="contact" value="<?= $row['contact']; ?>" required></td>
                <td><input type="text" name="username" value="<?= $row['username']; ?>" required></td>
                <td><input type="email" name="email" value="<?= $row['email']; ?>" required></td>
                <td>
                    <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="edit_user">Edit</button>
                    <button type="submit" name="delete_user">Delete</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
