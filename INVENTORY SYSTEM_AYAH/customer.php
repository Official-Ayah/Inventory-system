<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_customer'])) {
  $name = trim((string)($_POST['customer_name'] ?? ''));
  $contact = trim((string)($_POST['contact_number'] ?? ''));
  $address = trim((string)($_POST['address'] ?? ''));

  if ($name !== '' && $contact !== '' && $address !== '') {
    $stmt = mysqli_prepare($conn, 'INSERT INTO customers (customer_name, contact_number, address) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $name, $contact, $address);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

// READ
$customers = mysqli_query($conn, 'SELECT * FROM customers');

// DELETE
if (isset($_GET['delete'])) {
  $id = (int)($_GET['delete'] ?? 0);
  if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM customers WHERE customer_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
?>
