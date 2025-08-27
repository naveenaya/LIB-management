<?php require 'includes/header.php';
$errors=[]; $title=''; $author=''; $category=''; $available=1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) $errors[]='Invalid CSRF';
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $available = isset($_POST['available']) ? 1 : 0;
    if ($title==='') $errors[]='Title required';
    if ($author==='') $errors[]='Author required';
    if ($category==='') $errors[]='Category required';
    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, category, available) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $author, $category, $available);
        $stmt->execute();
        header("Location: index.php"); exit;
    }
}
?>
<section class="card">
  <h2>Add Book</h2>
  <?php foreach($errors as $er) echo "<div class='error'>".e($er)."</div>"; ?>
  <form method="post" class="form-grid">
    <label>Title<input name="title" value="<?= e($title) ?>" required></label>
    <label>Author<input name="author" value="<?= e($author) ?>" required></label>
    <label>Category<input name="category" value="<?= e($category) ?>" required></label>
    <label class="checkbox"><input type="checkbox" name="available" <?= $available ? 'checked':'' ?>> Available</label>
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <div class="actions"><button type="submit">Save</button><a class="btn" href="index.php">Cancel</a></div>
  </form>
</section>
<?php require 'includes/footer.php'; ?>