<?php
include 'conn.php';

if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

$message = "";

// বইয়ের তথ্য নিয়ে আসা
if (isset($_GET['id'])) {
    $book_id = (int)$_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM books WHERE id = $book_id");
    $book = mysqli_fetch_assoc($result);

    if (!$book) {
        $message = "❌ Book not found!";
    }
} else {
    $message = "❌ Invalid book ID!";
}

// POST হলে আপডেট
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_title = $_POST['book_title'];
    $author_name = $_POST['author_name'];
    $new_quantity = (int)$_POST['quantity'];
    $almari_name = $_POST['almari_name'];
    $thak_name = $_POST['thak_name'];

    // per-book price DB থেকেই নিচ্ছি (পুরাতন)
    $per_book_price = (float)$book['book_price'];

    // Prepare statement দিয়ে আপডেট
    $stmt = $conn->prepare("UPDATE books SET title=?, author_name=?, quantity=?, almari_name=?, thak_name=? WHERE id=?");
    $stmt->bind_param("ssissi", $book_title, $author_name, $new_quantity, $almari_name, $thak_name, $book_id);

    if ($stmt->execute()) {
        $message = "✅ Book updated successfully!";
        // আপডেট হওয়া ডেটা আবার $book এ রাখছি যাতে ফর্মে দেখা যায়
        $book['title'] = $book_title;
        $book['author_name'] = $author_name;
        $book['quantity'] = $new_quantity;
        $book['almari_name'] = $almari_name;
        $book['thak_name'] = $thak_name;
        // book_price অপরিবর্তিত থাকবে, কারণ সেটা প্রতি বইয়ের দাম
    } else {
        $message = "❌ Failed to update book!";
    }
    $stmt->close();
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-warning text-white text-center">
          <h4>✏️ Edit Book</h4>
        </div>
        <div class="card-body">
          <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
          <?php endif; ?>

          <?php if ($book): ?>
          <form method="POST" action="">
            <div class="mb-3">
              <label for="book_title">📚 Title</label>
              <input type="text" id="book_title" name="book_title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="author_name">✍️ Author</label>
              <input type="text" id="author_name" name="author_name" class="form-control" value="<?= htmlspecialchars($book['author_name']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="quantity">📦 Quantity</label>
              <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="<?= $book['quantity'] ?>" required>
              <small class="text-muted">Per book price: ৳<?= number_format($book['book_price'], 2) ?></small>
            </div>

            <div class="mb-3">
              <label for="almari_name">🗄️ Almari</label>
              <input type="text" id="almari_name" name="almari_name" class="form-control" value="<?= htmlspecialchars($book['almari_name']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="thak_name">📍 Thak</label>
              <input type="text" id="thak_name" name="thak_name" class="form-control" value="<?= htmlspecialchars($book['thak_name']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="total_price">💰 Total Price (auto-calculated)</label>
              <input type="text" class="form-control" readonly value="৳<?= number_format($book['book_price'] * $book['quantity'], 2) ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100">✏️ Update Book</button>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
