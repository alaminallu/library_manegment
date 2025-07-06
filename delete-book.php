<?php

include 'conn.php';

if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

$message = "";

// Check if book ID is provided
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Check if book is issued and not returned
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM issued_books WHERE book_id = ? AND status != 'returned'");
    $checkStmt->bind_param("i", $book_id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        $message = "❌ এই বইটি এখনও ফেরত আসেনি, তাই এটি ডিলিট করা যাবে না!";
    } else {
        // Delete book from the database
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);

        if ($stmt->execute()) {
            $message = "✅ Book deleted successfully!";
        } else {
            $message = "❌ Failed to delete book! Error: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $message = "❌ Invalid book ID!";
}
?>


<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-danger text-white text-center">
          <h4>🗑️ Delete Book</h4>
        </div>
        <div class="card-body">
          <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
          <?php endif; ?>
          <a href="view-books.php" class="btn btn-primary w-100">Go Back to Book List</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
