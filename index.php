<?php
include 'conn.php';
include 'header.php';

$totalBookNames = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM books"))['total'];
$totalBooks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM books"))['total'];
$issuedBooks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as issued FROM issued_books WHERE status='issued'"))['issued'];
$returnedBooks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as returned FROM issued_books WHERE status='returned'"))['returned'];
$availableBooks = $totalBooks - $issuedBooks;

// ✅ Total Price (price × quantity)
$totalPrice = 0;
$booksQuery = "SELECT book_price, quantity FROM books";
$booksResult = mysqli_query($conn, $booksQuery);
while ($book = mysqli_fetch_assoc($booksResult)) {
  $totalPrice += $book['book_price'] * $book['quantity'];
}
?>

<h2 class="mb-4">📚 Admin Dashboard</h2>

<!-- Summary Cards -->
<div class="row mb-4">
  <div class="col-md-3">
   <a style="text-decoration:none;" href="view-books.php"> 
   <div class="card text-white bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Total Books</h5>
        <p class="card-text fs-4"><?= $totalBookNames ?> (৳<?= number_format($totalPrice, 2) ?>)</p>
      </div>
    </div></a>
  </div>

  <div class="col-md-3">
    <a style="text-decoration:none;" href="available-books.php">
	<div class="card text-white bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Available Books</h5>
        <p class="card-text fs-4"><?= $availableBooks ?> পিস</p>
      </div>
    </div>
	</a>
  </div>

  <div class="col-md-3">
    <a style="text-decoration:none;" href="issue-book.php">
	<div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Issued Books</h5>
        <p class="card-text fs-4"><?= $issuedBooks ?></p>
      </div>
    </div>
	</a>
  </div>

  <div class="col-md-3">
    <a style="text-decoration:none;" href="return-book.php">
	<div class="card text-white bg-info mb-3">
      <div class="card-body">
        <h5 class="card-title">Returned Books</h5>
        <p class="card-text fs-4"><?= $returnedBooks ?></p>
      </div>
    </div>
	</a>
  </div>
</div>

<!-- Action Buttons -->
<div class="mb-4">
  <a href="add-book.php" class="btn btn-success me-2">➕ Add Book</a>
  <a href="view-books.php" class="btn btn-primary me-2">📘 View Books</a>
  <a href="issue-book.php" class="btn btn-warning me-2">📤 Issue Book</a>
  <a href="return-book.php" class="btn btn-info">📥 Return Book</a>
</div>

<!-- Recent Issued Books Table -->
<h4>🕓 Recent Issued Books</h4>
<table class="table table-bordered">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Book Title</th>
      <th>Issued To</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM issued_books ORDER BY id DESC LIMIT 5");
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
          <td>{$i}</td>
          <td>{$row['book_title']}</td>
          <td>{$row['issued_to']}</td>
          <td>{$row['issue_date']}</td>
          <td><span class='badge ".($row['status']=='issued' ? 'bg-warning' : 'bg-success')."'>{$row['status']}</span></td>
        </tr>";
        $i++;
    }
    ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
