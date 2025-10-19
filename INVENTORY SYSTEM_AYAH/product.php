<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_product'])) {
  $name = $_POST['product_name'];
  $cat = $_POST['category_id'];
  $sup = $_POST['supplier_id'];
  $price = $_POST['price'];
  $qty = $_POST['quantity'];

  mysqli_query($conn, "INSERT INTO products (product_name, category_id, supplier_id, price, quantity)
                       VALUES ('$name', '$cat', '$sup', '$price', '$qty')");
}

// READ
$products = mysqli_query($conn, "SELECT p.*, c.category_name, s.supplier_name
                                 FROM products p
                                 LEFT JOIN categories c ON p.category_id=c.category_id
                                 LEFT JOIN suppliers s ON p.supplier_id=s.supplier_id");

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM products WHERE product_id=$id");
}
?>
