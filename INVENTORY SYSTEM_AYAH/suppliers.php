<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_supplier'])) {
  $name = $_POST['supplier_name'];
  $contact = $_POST['contact_number'];
  $address = $_POST['address'];

  mysqli_query($conn, "INSERT INTO suppliers (supplier_name, contact_number, address) 
                       VALUES ('$name', '$contact', '$address')");
}

// READ
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM suppliers WHERE supplier_id=$id");
}
?>
