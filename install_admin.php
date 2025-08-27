<?php
// install_admin.php  — run once then delete for security
require 'config.php';
$username = 'naveenaya';
$plain = 'navee786';
$hash = password_hash($plain, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT id FROM admin WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin already exists. Username: naveenaya, Password: navee@786";
} else {
    $stmt->close();
    $ins = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $ins->bind_param("ss", $username, $hash);
    $ins->execute();
    echo "Admin created! Username: naveenaya, Password: navee786";
}