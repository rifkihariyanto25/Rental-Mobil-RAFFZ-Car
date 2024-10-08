<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Mengambil daftar mobil
$mobil_result = $conn->query("SELECT * FROM mobil");

// Menangani penambahan mobil
if (isset($_POST['add_mobil'])) {
    $nama_mobil = $_POST['nama_mobil'];
    $harga_per_hari = $_POST['harga_per_hari'];
    $jumlah_unit = $_POST['jumlah_unit'];

    $conn->query("INSERT INTO mobil (nama_mobil, harga_per_hari, jumlah_unit, status) VALUES ('$nama_mobil', '$harga_per_hari', '$jumlah_unit', 'tersedia')");
    header("Location: manage_mobil.php");
}

// Menangani pembaruan mobil (harga, jumlah unit, dan status)
if (isset($_POST['update_mobil'])) {
    $id_mobil = $_POST['id_mobil'];
    $harga_per_hari = $_POST['harga_per_hari'];
    $jumlah_unit = $_POST['jumlah_unit'];
    $status = $_POST['status'];

    // Update semua data mobil
    $conn->query("UPDATE mobil SET harga_per_hari='$harga_per_hari', jumlah_unit='$jumlah_unit', status='$status' WHERE id_mobil='$id_mobil'");
    header("Location: manage_mobil.php");
}

// Menangani penghapusan mobil
if (isset($_POST['delete_mobil'])) {
    $id_mobil = $_POST['id_mobil'];
    $conn->query("DELETE FROM mobil WHERE id_mobil='$id_mobil'");
    header("Location: manage_mobil.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Mobil - Rental Mobil</title>
</head>

<body>
    <h1>Pengelolaan Mobil</h1>

    <h2>Tambah Mobil</h2>
    <form method="post">
        <input type="text" name="nama_mobil" required placeholder="Nama Mobil">
        <input type="number" name="harga_per_hari" required placeholder="Harga per Hari">
        <input type="number" name="jumlah_unit" required placeholder="Jumlah Unit">
        <button type="submit" name="add_mobil">Tambah</button>
    </form>

    <h2>Daftar Mobil</h2>
    <table border="1">
        <tr>
            <!-- Kolom ID Mobil dihilangkan dari tabel -->
            <th>Nama Mobil</th>
            <th>Harga per Hari</th>
            <th>Jumlah Unit</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($mobil = $mobil_result->fetch_assoc()): ?>
            <tr>
                <td><?= $mobil['nama_mobil'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="id_mobil" value="<?= $mobil['id_mobil'] ?>">
                        <input type="number" name="harga_per_hari" value="<?= $mobil['harga_per_hari'] ?>" required>
                </td>
                <td>
                    <input type="number" name="jumlah_unit" value="<?= $mobil['jumlah_unit'] ?>" required>
                </td>
                <td>
                    <select name="status">
                        <option value="tersedia" <?= $mobil['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="tidak tersedia" <?= $mobil['status'] == 'tidak tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                    </select>
                </td>
                <td>
                    <button type="submit" name="update_mobil">Ubah</button>
                    <button type="submit" name="delete_mobil">Hapus Mobil</button>
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