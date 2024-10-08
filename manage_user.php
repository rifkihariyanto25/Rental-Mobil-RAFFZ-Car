<?php
session_start();
$conn = new mysqli("localhost", "root", "", "rental_mobil");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Deleting a user
if (isset($_POST['delete_user'])) {
    $id_user = $_POST['id_user'];
    $conn->query("DELETE FROM user WHERE id_user='$id_user'");
}

$user_result = $conn->query("SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
</head>
<body>
    <h1>Manage Users</h1>

    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $user_result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_user'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                    <button type="submit" name="delete_user">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
