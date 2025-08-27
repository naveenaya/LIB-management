<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once _DIR_ . '/../config.php';

if (!isset($_SESSION['admin']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: login.php");
    exit;
}
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Library</title><link rel="stylesheet" href="/library_management/style.css"></head><body>
<header class="topbar">
  <div class="brand">📚 Library</div>
  <?php if (isset($_SESSION['admin'])): ?>
  <nav>
    <a href="index.php">Books</a>
    <a href="add.php">Add</a>
    <span class="welcome"><?= e($_SESSION['admin']) ?></span>
    <form action="logout.php" method="post" class="inline"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><button type="submit">Logout</button></form>
  </nav>
  <?php endif; ?>
</header>
<main class="container">