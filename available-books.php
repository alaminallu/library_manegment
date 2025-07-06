<?php
include 'conn.php';
include 'header.php';

// Available book list
$books = mysqli_query($conn, "SELECT id, title, author_name, almari_name, thak_name, quantity FROM books WHERE quantity > 0 ORDER BY title ASC");
?>

<style>
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #343a40; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    h2 { color: #28a745; margin-top: 20px; }
</style>

<div class="container mt-4">
    <h2>📗 Available Books</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Quantity</th>
                <th>Almari</th>
                <th>Thak</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($books) > 0) {
                $sl = 1;
                while ($book = mysqli_fetch_assoc($books)) {
                    echo "<tr>
                            <td>{$sl}</td>
                            <td>".htmlspecialchars($book['title'])."</td>
                            <td>".htmlspecialchars($book['author_name'])."</td>
                            <td>{$book['quantity']}</td>
                            <td>".htmlspecialchars($book['almari_name'])."</td>
                            <td>".htmlspecialchars($book['thak_name'])."</td>
                          </tr>";
                    $sl++;
                }
            } else {
                echo "<tr><td colspan='6'>No available books found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
