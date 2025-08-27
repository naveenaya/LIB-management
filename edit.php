<?php require 'includes/header.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id<=0) { header("Location: index.php"); exit; }
$stmt = $conn->prepare("SELECT * FROM books WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute(); $book=$stmt->get_result()->fetch_assoc();
if (!$book) { header("Location: index.php"); exit; }

$errors=[]; $title = $_POST['title'] ?? $book['title']; $author = $_POST['author'] ?? $book['author']; $category = $_POST['category'] ?? $book['category']; $available = isset($_POST['available']) ? 1 : (int)$book['available'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) $errors[]='Invalid CSRF';
    if (trim($title) === '') $errors[]='Title required';
    if (trim($author) === '') $errors[]='Author required';
    if (trim($category) === '') $errors[]='Category required';
    if (!$errors) {
        $up = $conn->prepare("UPDATE books SET title=?, author=?, category=?, available=? WHERE id=?");
        $up->bind_param("sssii", $title, $author, $category, $available, $id);
        $up->execute();
        header("Location: index.php"); exit;
    }
}
?>
<section class="card">
  <h2>Edit Book #<?= (int)$id ?></h2>
  <?php foreach($errors as $er) echo "<div class='error'>".e($er)."</div>"; ?>
  <form method="post" class="form-grid">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <label>Title<input name="title" value="<?= e($title) ?>" required></label>
    <label>Author<input name="author" value="<?= e($author) ?>" required></label>
    <label>Category<input name="category" value="<?= e($category) ?>" required></label>
    <label class="checkbox"><input type="checkbox" name="available" <?= $available ? 'checked':'' ?>> Available</label>
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <div class="actions"><button type="submit">Update</button><a class="btn" href="index.php">Back</a></div>
  </form>
</section>
<?php require 'includes/footer.php'; ?>