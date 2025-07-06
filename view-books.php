<?php
include 'conn.php';

if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

// সব বই আনো
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");

// মোট দামের হিসাব
$total_result = mysqli_query($conn, "SELECT SUM(quantity * book_price) AS total_value FROM books");
$total_row = mysqli_fetch_assoc($total_result);
$total_value = number_format($total_row['total_value'], 2);
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-dark text-white text-center">
      <h4>📖 Book List</h4>
    </div>
    <div class="card-body table-responsive">

      <!-- 🔍 Search Input -->
      <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by title, author, almari, thak...">
      </div>

      <table class="table table-bordered table-striped text-center" id="bookTable">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>📚 Title</th>
            <th>✍️ Author</th>
            <th>📦 Quantity</th>
            <th>💰 Total Price</th>
            <th>🗄️ Almari</th>
            <th>📍 Thak</th>
            <th>⚙️ Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['book_price'] * $row['quantity'], 2) ?> টাকা</td>
                <td><?= htmlspecialchars($row['almari_name']) ?></td>
                <td><?= htmlspecialchars($row['thak_name']) ?></td>
                <td>
                  <a href="edit-book.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                  <a href="delete-book.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">🗑️ Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-danger">❌ No books found!</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- ✅ Total Value of All Books -->
      <div class="mt-4 text-end">
        <h5>📊 মোট লাইব্রেরির বইয়ের মূল্য: <span class="text-success">৳<?= $total_value ?></span></h5>
      </div>

    </div>
  </div>
</div>

<!-- 🔍 JavaScript for Search Filter -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
  let filter = this.value.toLowerCase();
  let rows = document.querySelectorAll("#bookTable tbody tr");

  rows.forEach(function(row) {
    let text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? "" : "none";
  });
});
</script>

<?php include 'footer.php'; ?>
