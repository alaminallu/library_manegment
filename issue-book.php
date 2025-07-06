<?php
include 'conn.php';

if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $book_id = $_POST['book_id'];
  $issued_to = $_POST['issued_to'];
  $issue_date = date('Y-m-d');
  $status = "Issued";

  // Book availability check & title fetch
  $book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT quantity, title, almari_name, thak_name FROM books WHERE id = $book_id"));

  if ($book && $book['quantity'] > 0) {
    $book_name = $book['title'];
    $almari_name = $book['almari_name'];  // Almari Name
    $thak_name = $book['thak_name'];      // Thak Name

    // Insert into issued_books
    $stmt = $conn->prepare("INSERT INTO issued_books (book_id, book_title, issued_to, issue_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $book_id, $book_name, $issued_to, $issue_date, $status);

    if ($stmt->execute()) {
      // Reduce quantity from books table
      mysqli_query($conn, "UPDATE books SET quantity = quantity - 1 WHERE id = $book_id");
      $message = "✅ Book issued successfully!";
    } else {
      $message = "❌ Failed to issue book!";
    }

    $stmt->close();
  } else {
    $message = "❌ Book is not available!";
  }
}

// Get all available books
$books = mysqli_query($conn, "SELECT id, title, almari_name, thak_name FROM books WHERE quantity > 0 ORDER BY title ASC");
?>

<?php include 'header.php'; ?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4>📤 Issue Book</h4>
        </div>
        <div class="card-body">
          <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label>Select Book</label>
              <select name="book_id" class="form-control" id="book_select" required>
                <option value="">📚 Choose a book</option>
                <?php while ($book = mysqli_fetch_assoc($books)): ?>
                  <option value="<?= $book['id'] ?>">
                    <?= htmlspecialchars($book['title']) ?> 
                    (<?= htmlspecialchars($book['almari_name']) ?>, <?= htmlspecialchars($book['thak_name']) ?>)
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label>Issued To (User Name)</label>
              <input type="text" name="issued_to" class="form-control" required placeholder="👤 Enter user name">
            </div>
            <button type="submit" class="btn btn-primary w-100">📤 Issue Book</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('#book_select').select2({
      placeholder: "📚 Search or select a book",
      width: '100%'
    });
  });
</script>

<?php include 'footer.php'; ?>
