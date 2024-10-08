<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$pesanan_result = $conn->query("SELECT * FROM pesanan");

// Handle accepting a booking
if (isset($_POST['accept_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $conn->query("UPDATE pesanan SET status_pesanan='diterima' WHERE id_pesanan='$id_pesanan'");
}

// Handle deleting a booking
if (isset($_POST['delete_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $conn->query("DELETE FROM pesanan WHERE id_pesanan='$id_pesanan'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Mobil - Rental Mobil</title>
</head>
<body>
    <h1>Pesanan Mobil</h1>
    <table border="1">
        <tr>
            <th>ID Pesanan</th>
            <th>Nama Mobil</th>
            <th>ID User</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($pesanan = $pesanan_result->fetch_assoc()):
            $mobil = $conn->query("SELECT nama_mobil FROM mobil WHERE id_mobil='{$pesanan['id_mobil']}'")->fetch_assoc();
        ?>
        <tr>
            <td><?= $pesanan['id_pesanan'] ?></td>
            <td><?= $mobil['nama_mobil'] ?></td>
            <td><?= $pesanan['id_user'] ?></td>
            <td><?= $pesanan['tanggal_mulai'] ?></td>
            <td><?= $pesanan['tanggal_selesai'] ?></td>
            <td><?= $pesanan['status_pesanan'] ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                    <button type="submit" name="accept_pesanan">Terima</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                    <button type="submit" name="delete_pesanan" onclick="return confirm('Are you sure you want to delete this booking?');">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
