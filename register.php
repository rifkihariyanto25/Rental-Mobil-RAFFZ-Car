<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cek apakah email sudah terdaftar
    $result = $conn->query("SELECT * FROM user WHERE email='$email'");
    if ($result->num_rows > 0) {
        $error_message = "Email sudah terdaftar.";
    } else {
        $query = "INSERT INTO user (nama, email, password) VALUES ('$nama', '$email', '$password')";
        if ($conn->query($query) === TRUE) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Rental Mobil</title>
</head>
<body>
    <h1>Registrasi Pengguna</h1>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
    <form method="post">
        <input type="text" name="nama" required placeholder="Nama Lengkap">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit" name="register">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>
