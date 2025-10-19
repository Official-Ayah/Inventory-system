<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_sale'])) {
  $customer = $_POST['customer_id'];
  $total = $_POST['total_amount'];

  mysqli_query($conn, "INSERT INTO sales (customer_id, total_amount)
                       VALUES ('$customer', '$total')");
}

// READ
$sales = mysqli_query($conn, "SELECT s.*, c.customer_name 
                              FROM sales s
                              LEFT JOIN customers c ON s.customer_id=c.customer_id");

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM sales WHERE sale_id=$id");
}
?>
