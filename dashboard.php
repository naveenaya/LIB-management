<?php
session_start();

// simple authentication check
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
</head>
<body>
    <h1>Welcome to Library Management System</h1>
    <p>Hello, <?php echo $_SESSION['username']; ?> 👋</p>
    <ul>
        <li><a href="add_book.php">Add New Book</a></li>
        <li><a href="view_books.php">View Books</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>