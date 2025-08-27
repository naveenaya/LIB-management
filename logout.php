<?php
// logout.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
    session_unset();
    session_destroy();
}
header("Location: login.php");