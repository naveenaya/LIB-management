<?php
require 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php"); exit; }
$id = (int)($_POST['id'] ?? 0);
$csrf = $_POST['csrf'] ?? '';
if ($id>0 && hash_equals($_SESSION['csrf'] ?? '', $csrf)) {
    $del = $conn->prepare("DELETE FROM books WHERE id=?");
    $del->bind_param("i", $id);
    $del->execute();
}
header("Location: index.php");