<?php
include 'db_connect.php';

// CREATE
if (isset($_POST['add_category'])) {
  $name = $_POST['category_name'];
  mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$name')");
}

// READ
$categories = mysqli_query($conn, "SELECT * FROM categories");

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM categories WHERE category_id=$id");
}
?>
