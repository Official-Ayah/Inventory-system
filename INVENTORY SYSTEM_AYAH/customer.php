<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_customer'])) {
  $name = $_POST['customer_name'];
  $contact = $_POST['contact_number'];
  $address = $_POST['address'];

  mysqli_query($conn, "INSERT INTO customers (customer_name, contact_number, address)
                       VALUES ('$name', '$contact', '$address')");
}

// READ
$customers = mysqli_query($conn, "SELECT * FROM customers");

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM customers WHERE customer_id=$id");
}
?>
