<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_product'])) {
  $name = trim((string)($_POST['product_name'] ?? ''));
  $cat = (int)($_POST['category_id'] ?? 0);
  $sup = (int)($_POST['supplier_id'] ?? 0);
  $price = (float)($_POST['price'] ?? 0);
  $qty = (int)($_POST['quantity'] ?? 0);

  if ($name !== '' && $cat > 0 && $sup > 0 && $price >= 0 && $qty >= 0) {
    $stmt = mysqli_prepare($conn, 'INSERT INTO products (product_name, category_id, supplier_id, price, quantity) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'siidi', $name, $cat, $sup, $price, $qty);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

// READ
$products = mysqli_query($conn, "SELECT p.*, c.category_name, s.supplier_name
                                 FROM products p
                                 LEFT JOIN categories c ON p.category_id=c.category_id
                                 LEFT JOIN suppliers s ON p.supplier_id=s.supplier_id");

// DELETE
if (isset($_GET['delete'])) {
  $id = (int)($_GET['delete'] ?? 0);
  if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM products WHERE product_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
?>
