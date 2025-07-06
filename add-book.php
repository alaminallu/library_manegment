<?php
include 'conn.php';

// যদি লগইন না করা থাকে তাহলে login page এ পাঠাও
if (!isset($_COOKIE['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ইনপুট নেওয়া
  $title = $_POST['title'];
  $author = $_POST['author'];
  $quantity = (int)$_POST['quantity'];
  $almari = $_POST['almari'];
  $thak = $_POST['thak'];
  $total_price = (float)$_POST['total_price'];  // মোট দাম ইনপুট

  // ইনপুট স্যানিটাইজ (SQL Injection প্রতিরোধ)
  $title = mysqli_real_escape_string($conn, $title);
  $author = mysqli_real_escape_string($conn, $author);
  $almari = mysqli_real_escape_string($conn, $almari);
  $thak = mysqli_real_escape_string($conn, $thak);

  // প্রতি বইয়ের দাম হিসাব (ভাগ করো)
  if ($quantity > 0) {
    $book_price = $total_price / $quantity;
  } else {
    $book_price = 0;
  }

  // Prepared Statement ব্যবহার করে ইনসার্ট
  $stmt = $conn->prepare("INSERT INTO books (title, author_name, quantity, almari_name, thak_name, book_price) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssisid", $title, $author, $quantity, $almari, $thak, $book_price);

  if ($stmt->execute()) {
    $message = "✅ Book added successfully! (Price per book: ৳" . number_format($book_price, 2) . ")";
  } else {
    $message = "❌ Failed to add book!";
  }

  $stmt->close();
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>📚 Add New Book</h4>
        </div>
        <div class="card-body">
          <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label>Book Title</label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Author</label>
              <input type="text" name="author" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Quantity</label>
              <input type="number" name="quantity" class="form-control" required min="1" value="1">
            </div>
            <div class="mb-3">
              <label>Almari Name</label>
              <input type="text" name="almari" class="form-control" required placeholder="e.g., Almari 1">
            </div>
            <div class="mb-3">
              <label>Thak Name</label>
              <input type="text" name="thak" class="form-control" required placeholder="e.g., Top Shelf">
            </div>
            <div class="mb-3">
              <label>Total Price (৳)</label>
              <input type="number" name="total_price" class="form-control" required min="0" step="0.01" placeholder="Total price for all books">
              <small class="text-muted">Per book price will be auto calculated.</small>
            </div>
            <button type="submit" class="btn btn-success w-100">➕ Add Book</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
