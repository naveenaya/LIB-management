<?php
// login.php
session_start();
require 'config.php';
if (isset($_SESSION['admin'])) { header("Location: index.php"); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username=?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($p, $row['password'])) {
            $_SESSION['admin'] = $row['username'];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            header("Location: index.php"); exit;
        }
    }
    $error = "Invalid username or password.";
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body class="centered">
  <form class="card" method="post" autocomplete="off">
    <h2>Admin Login</h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <label>Username <input name="username" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Login</button>
    <p class="muted">Default: admin / admin123</p>
  </form>
</body>
</html>