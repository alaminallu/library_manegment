<?php
// কুকি চেক (লগইন না থাকলে রিডাইরেক্ট)
if (!isset($_COOKIE['admin_logged_in'])) {
    header("Location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">📚 Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link" href="index.php">Dashboard</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="add-book.php">Add Book</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="view-books.php">View Books</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="issue-book.php">Issue Book</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="return-book.php">Return Book</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-danger" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main container start -->
<div class="container mt-4">
