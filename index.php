<?php
require 'includes/header.php';
$q = trim($_GET['q'] ?? '');
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

if ($q !== '') {
    $like = "%$q%";
    $countStmt = $conn->prepare("SELECT COUNT(*) AS c FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ?");
    $countStmt->bind_param("sss", $like, $like, $like);
} else {
    $countStmt = $conn->prepare("SELECT COUNT(*) AS c FROM books");
}
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_assoc()['c'];
$pages = max(1, (int)ceil($total / $limit));

if ($q !== '') {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT * FROM books ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$res = $stmt->get_result();
?>
<section class="card">
  <h2>Books</h2>
  <form class="toolbar" method="get">
    <input type="text" name="q" placeholder="Search..." value="<?= e($q) ?>">
    <button type="submit">Search</button>
    <a class="btn" href="add.php">+ Add Book</a>
  </form>
  <div class="table-wrap">
    <table><thead><tr><th>#</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th>Actions</th></tr></thead><tbody>
      <?php if ($res->num_rows === 0): ?><tr><td colspan="6" class="muted">No books found.</td></tr><?php endif; ?>
      <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= (int)$row['id'] ?></td>
        <td><?= e($row['title']) ?></td>
        <td><?= e($row['author']) ?></td>
        <td><?= e($row['category']) ?></td>
        <td><?= $row['available'] ? 'Yes' : 'No' ?></td>
        <td class="actions">
          <a href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
          <form method="post" action="delete.php" class="inline" onsubmit="return confirm('Delete this book?');">
            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <button type="submit">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody></table>
  </div>
  <?php if ($pages > 1): ?>
  <div class="pagination">
    <?php for ($i=1;$i<=$pages;$i++): ?>
      <a class="<?= $i==$page ? 'active':'' ?>" href="?page=<?= $i ?>&q=<?= urlencode($q) ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
