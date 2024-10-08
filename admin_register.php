<?php
session_start();
include 'config.php';

if (isset($_POST['register_admin'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cek apakah email sudah terdaftar
    $result = $conn->query("SELECT * FROM admin WHERE email='$email'");
    if ($result->num_rows > 0) {
        $error_message = "Email sudah terdaftar.";
    } else {
        $query = "INSERT INTO admin (email, password) VALUES ('$email', '$password')";
        if ($conn->query($query) === TRUE) {
            header("Location: admin_login.php?success=1");
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
    <title>Registrasi Admin - Rental Mobil</title>
</head>
<body>
    <h1>Registrasi Admin</h1>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
    <form method="post">
        <input type="email" name="email" required placeholder="Email Admin">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit" name="register_admin">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="admin_login.php">Login di sini</a></p>
</body>
</html>
