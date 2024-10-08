<?php

// 1. Proses Login
function processLogin($username, $password) {
    // Simulasi pengecekan kredensial
    if ($username === "admin" && $password === "password123") {
        return ["success" => true, "message" => "Login berhasil"];
    } else {
        return ["success" => false, "message" => "Username atau password salah"];
    }
}

// 2. Proses Pencarian Data
function searchData($keyword, $database) {
    $results = [];
    foreach ($database as $item) {
        if (strpos(strtolower($item), strtolower($keyword)) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// 3. Proses Validasi Input
function validateInput($data) {
    $errors = [];
    if (empty($data['name'])) {
        $errors[] = "Nama tidak boleh kosong";
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    if (strlen($data['password']) < 8) {
        $errors[] = "Password harus minimal 8 karakter";
    }
    return $errors;
}

// 4. Proses Kalkulasi Total Belanja
function calculateTotalPurchase($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// 5. Proses Pengiriman Email
function sendEmail($to, $subject, $message) {
    // Simulasi pengiriman email
    $success = mail($to, $subject, $message);
    if ($success) {
        return ["success" => true, "message" => "Email berhasil dikirim"];
    } else {
        return ["success" => false, "message" => "Gagal mengirim email"];
    }
}

// Contoh penggunaan:
$loginResult = processLogin("admin", "password123");
echo "Login: " . ($loginResult['success'] ? "Berhasil" : "Gagal") . "\n";

$searchResult = searchData("apple", ["apple", "banana", "orange", "grape"]);
echo "Hasil pencarian: " . implode(", ", $searchResult) . "\n";

$validationErrors = validateInput(["name" => "", "email" => "invalid-email", "password" => "123"]);
echo "Error validasi: " . implode(", ", $validationErrors) . "\n";

$totalPurchase = calculateTotalPurchase([
    ["price" => 10000, "quantity" => 2],
    ["price" => 15000, "quantity" => 1]
]);
echo "Total belanja: Rp " . number_format($totalPurchase, 0, ',', '.') . "\n";

$emailResult = sendEmail("user@example.com", "Test Subject", "This is a test message");
echo "Pengiriman email: " . ($emailResult['success'] ? "Berhasil" : "Gagal") . "\n";

?>