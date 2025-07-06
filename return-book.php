<?php
include 'conn.php';

if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

$message = "";

// Return request handle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $issue_id = $_POST['issue_id'];
  $return_date = date('Y-m-d');

  // Get issued book details
  $issued = mysqli_fetch_assoc(mysqli_query($conn, "SELECT book_id FROM issued_books WHERE id = $issue_id AND status = 'Issued'"));

  if ($issued) {
    $book_id = $issued['book_id'];

    // Update issued_books table
    $update = mysqli_query($conn, "UPDATE issued_books SET status='Returned', return_date='$return_date' WHERE id = $issue_id");

    if ($update) {
      // Increase quantity back in books table
      mysqli_query($conn, "UPDATE books SET quantity = quantity + 1 WHERE id = $book_id");
      $message = "✅ Book returned successfully!";
    } else {
      $message = "❌ Failed to return book!";
    }
  } else {
    $message = "❌ Invalid issue record or already returned!";
  }
}

// Fetch issued books
$issued_books = mysqli_query($conn, "SELECT ib.id, b.title, ib.issued_to, ib.issue_date 
                                     FROM issued_books ib 
                                     JOIN books b ON ib.book_id = b.id 
                                     WHERE ib.status = 'Issued' 
                                     ORDER BY ib.issue_date DESC");
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
          <h4>📥 Return Book</h4>
        </div>
        <div class="card-body">
          <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
          <?php endif; ?>

          <?php if (mysqli_num_rows($issued_books) > 0): ?>
            <form method="POST" action="">
              <div class="mb-3">
                <label>Select Issued Book</label>
                <select name="issue_id" class="form-control" required>
                  <option value="">📚 Choose a book to return</option>
                  <?php while ($row = mysqli_fetch_assoc($issued_books)): ?>
                    <option value="<?= $row['id'] ?>">
                      <?= htmlspecialchars($row['title']) ?> | Issued To: <?= htmlspecialchars($row['issued_to']) ?> | Date: <?= $row['issue_date'] ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-success w-100">📥 Return Book</button>
            </form>
          <?php else: ?>
            <div class="text-danger text-center">❌ No issued books found!</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
