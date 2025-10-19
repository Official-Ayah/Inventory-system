<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_sale'])) {
  $customer = (int)($_POST['customer_id'] ?? 0);
  $total = (float)($_POST['total_amount'] ?? 0);

  if ($customer > 0 && $total >= 0) {
    $stmt = mysqli_prepare($conn, 'INSERT INTO sales (customer_id, total_amount) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'id', $customer, $total);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

// READ
$sales = mysqli_query($conn, 'SELECT s.*, c.customer_name FROM sales s LEFT JOIN customers c ON s.customer_id=c.customer_id');

// DELETE
if (isset($_GET['delete'])) {
  $id = (int)($_GET['delete'] ?? 0);
  if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM sales WHERE sale_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
?>
