<?php
session_start();
include 'config.php';

if (isset($_POST['login_admin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Ambil data admin
    $result = $conn->query("SELECT * FROM admin WHERE email='$email'");
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Email atau password salah.";
        }
    } else {
        $error_message = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Rental Mobil</title>
</head>
<body>
    <h1>Login Admin</h1>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
    <form method="post">
        <input type="email" name="email" required placeholder="Email Admin">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit" name="login_admin">Login</button>
    </form>
    <p>Belum punya akun? <a href="admin_register.php">Daftar di sini</a></p>
</body>
</html>
