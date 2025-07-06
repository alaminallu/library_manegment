<?php
include 'conn.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_name = $_POST['user_name'];
  $password = $_POST['password'];

  // ডাটাবেজ থেকে ইউজার খোঁজো
  $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
  $stmt->bind_param("s", $user_name);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // পাসওয়ার্ড মিলিয়ে দেখো (simple check; real project এ hash check করা উচিত)
    if ($row['password'] === $password) {
      setcookie("admin_logged_in", true, time() + (86400 * 7), "/");
      header("Location: index.php");
      exit();
    } else {
      $error = "Password is incorrect!";
    }
  } else {
    $error = "Username not found!";
  }

  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(45deg, #6a11cb, #2575fc);
      font-family: 'Courier New', Courier, monospace;
      color: #fff;
    }

    .card {
      border-radius: 15px;
      border: 1px solid #fff;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .card-header {
      background-color: #5e3b8d;
      color: white;
      font-size: 24px;
      text-align: center;
    }

    .form-control {
      border-radius: 10px;
      padding: 15px;
      background-color: #f1f1f1;
      border: 1px solid #ddd;
    }

    .btn-primary {
      background-color: #5e3b8d;
      border: none;
      border-radius: 10px;
      padding: 10px;
      width: 100%;
    }

    .btn-primary:hover {
      background-color: #4a2b6e;
    }

    .alert-danger {
      border-radius: 10px;
    }

    .container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <div class="card-header">
      🔐 Admin Login
    </div>
    
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="email">user Name</label>
        <input type="text" name="user_name" class="form-control" required placeholder="user_name">
      </div>
      <div class="mb-3">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Enter password">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
