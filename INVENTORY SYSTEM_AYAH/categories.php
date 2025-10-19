<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_category'])) {
  $name = trim((string)($_POST['category_name'] ?? ''));
  if ($name !== '') {
    $stmt = mysqli_prepare($conn, 'INSERT INTO categories (category_name) VALUES (?)');
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

// READ
$categories = mysqli_query($conn, 'SELECT * FROM categories');

// DELETE
if (isset($_GET['delete'])) {
  $id = (int)($_GET['delete'] ?? 0);
  if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM categories WHERE category_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
?>
