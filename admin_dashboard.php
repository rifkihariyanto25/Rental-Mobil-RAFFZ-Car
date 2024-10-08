<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rental Mobil</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin!</p>
    <nav>
        <ul>
            <li><a href="manage_mobil.php">Manage Cars</a></li>
            <li><a href="manage_user.php">Manage Users</a></li>
            <li><a href="pesanan.php">Pesanan</a></li>
            <li><a href="logout_admin.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
