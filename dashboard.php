<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user']['id_user'];
$statusPesanan = $conn->query("SELECT * FROM pesanan WHERE id_user='$id_user'");

// Fungsi untuk mengembalikan mobil
function kembalikanMobil($conn, $id_pesanan) {
    // Ambil informasi pesanan
    $pesanan = $conn->query("SELECT id_mobil FROM pesanan WHERE id_pesanan='$id_pesanan'")->fetch_assoc();
    
    // Pastikan pesanan ada
    if ($pesanan) {
        $id_mobil = $pesanan['id_mobil'];

        // Perbarui status pesanan menjadi 'Kembali'
        $conn->query("UPDATE pesanan SET status_pesanan='Kembali' WHERE id_pesanan='$id_pesanan'");

        // Tambah jumlah unit mobil
        $conn->query("UPDATE mobil SET jumlah_unit = jumlah_unit + 1 WHERE id_mobil='$id_mobil'");

        // Jika unit lebih dari 0, ubah status mobil menjadi tersedia
        $conn->query("UPDATE mobil SET status='tersedia' WHERE id_mobil='$id_mobil' AND jumlah_unit > 0");
    }
}

// Proses pesan mobil
if (isset($_POST['pesan_mobil'])) {
    $id_mobil = $_POST['id_mobil'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];

    // Mengambil jumlah unit mobil
    $mobil = $conn->query("SELECT jumlah_unit FROM mobil WHERE id_mobil='$id_mobil'")->fetch_assoc();

    if ($mobil['jumlah_unit'] > 0) {
        // Simpan pesanan ke database
        $conn->query("INSERT INTO pesanan (id_user, id_mobil, tanggal_mulai, tanggal_selesai, status_pesanan) VALUES ('$id_user', '$id_mobil', '$tanggal_mulai', '$tanggal_selesai', 'Menunggu')");

        // Kurangi jumlah unit mobil
        $conn->query("UPDATE mobil SET jumlah_unit = jumlah_unit - 1 WHERE id_mobil='$id_mobil'");

        // Jika unit habis, ubah status menjadi tidak tersedia
        if ($mobil['jumlah_unit'] - 1 == 0) {
            $conn->query("UPDATE mobil SET status='tidak tersedia' WHERE id_mobil='$id_mobil'");
        }

        header("Location: dashboard.php");
    } else {
        echo "<p>Mobil tidak tersedia!</p>";
    }
}

// Proses pengembalian mobil
if (isset($_POST['kembalikan_mobil'])) {
    $id_pesanan = $_POST['id_pesanan'];
    kembalikanMobil($conn, $id_pesanan);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rental Mobil</title>
</head>
<body>
    <h1>Beranda Pengguna</h1>
    <h2>Selamat datang, <?= $_SESSION['user']['nama'] ?>!</h2>

    <h2>Daftar Mobil</h2>
    <table border="1">
        <tr>
            <th>Nama Mobil</th>
            <th>Harga per Hari</th>
            <th>Jumlah Unit</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM mobil");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['nama_mobil'] ?></td>
            <td><?= $row['harga_per_hari'] ?></td>
            <td><?= $row['jumlah_unit'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['jumlah_unit'] > 0): ?>
                <form method="post">
                    <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                    <input type="date" name="tanggal_mulai" required>
                    <input type="date" name="tanggal_selesai" required>
                    <button type="submit" name="pesan_mobil">Pesan</button>
                </form>
                <?php else: ?>
                <p>Mobil tidak tersedia</p>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Status Pemesanan</h2>
    <table border="1">
        <tr>
            <th>Nama Mobil</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($pesanan = $statusPesanan->fetch_assoc()): 
            $mobil = $conn->query("SELECT nama_mobil FROM mobil WHERE id_mobil='{$pesanan['id_mobil']}'")->fetch_assoc();
        ?>
        <tr>
            <td><?= $mobil['nama_mobil'] ?></td>
            <td><?= $pesanan['tanggal_mulai'] ?></td>
            <td><?= $pesanan['tanggal_selesai'] ?></td>
            <td><?= $pesanan['status_pesanan'] ?></td>
            <td>
                <?php if ($pesanan['status_pesanan'] == 'Diterima'): ?>
                    <form method="post">
                        <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                        <button type="submit" name="kembalikan_mobil">Kembalikan</button>
                    </form>
                <?php elseif ($pesanan['status_pesanan'] == 'Kembali'): ?>
                    <p>Mobil sudah dikembalikan</p>
                <?php else: ?>
                    <p>Mobil dalam status <?= $pesanan['status_pesanan'] ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
