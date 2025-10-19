<?php
include 'db_connect.php';

// CREATE or UPDATE
if (isset($_POST['save_item'])) {
  $sale = $_POST['sale_id'];
  $product = $_POST['product_id'];
  $qty = $_POST['quantity'];
  $price = $_POST['price'];
  $id = isset($_POST['sale_item_id']) ? $_POST['sale_item_id'] : '';

  if ($sale && $product && $qty && $price) {
    if ($id) {
      // UPDATE existing item
      mysqli_query($conn, "UPDATE sale_items 
                           SET sale_id='$sale', product_id='$product', quantity='$qty', price='$price'
                           WHERE sale_item_id=$id");
    } else {
      // INSERT new item
      mysqli_query($conn, "INSERT INTO sale_items (sale_id, product_id, quantity, price)
                           VALUES ('$sale', '$product', '$qty', '$price')");
    }
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM sale_items WHERE sale_item_id=$id");
}

// READ
$sale_items = mysqli_query($conn, "SELECT si.*, p.product_name, s.sale_id 
                                   FROM sale_items si
                                   LEFT JOIN products p ON si.product_id=p.product_id
                                   LEFT JOIN sales s ON si.sale_id=s.sale_id");

// Calculate total
$total_result = mysqli_query($conn, "SELECT SUM(quantity * price) AS total_amount FROM sale_items");
$total_row = mysqli_fetch_assoc($total_result);
$total_amount = $total_row['total_amount'] ?? 0;

// Fetch dropdown data
$products = mysqli_query($conn, "SELECT * FROM products");
$sales = mysqli_query($conn, "SELECT * FROM sales");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sale Items Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4 text-center text-primary">Sale Items Management</h2>

  <form method="POST" class="card p-4 mb-4 shadow-sm">
    <input type="hidden" name="sale_item_id" value="<?php echo $_GET['edit_id'] ?? ''; ?>">

    <div class="row">
      <div class="col-md-3">
        <label>Sale ID</label>
        <select name="sale_id" class="form-control" required>
          <option value="">Select Sale</option>
          <?php while ($row = mysqli_fetch_assoc($sales)) { ?>
            <option value="<?= $row['sale_id'] ?>"><?= $row['sale_id'] ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-3">
        <label>Product</label>
        <select name="product_id" class="form-control" required>
          <option value="">Select Product</option>
          <?php while ($row = mysqli_fetch_assoc($products)) { ?>
            <option value="<?= $row['product_id'] ?>"><?= $row['product_name'] ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-2">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
      </div>

      <div class="col-md-2">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" name="save_item" class="btn btn-success w-100">Save Item</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered table-striped shadow-sm">
    <thead class="table-dark text-center">
      <tr>
        <th>ID</th>
        <th>Sale ID</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($sale_items)) { ?>
      <tr>
        <td><?= $row['sale_item_id']; ?></td>
        <td><?= $row['sale_id']; ?></td>
        <td><?= $row['product_name']; ?></td>
        <td><?= $row['quantity']; ?></td>
        <td>₱<?= number_format($row['price'], 2); ?></td>
        <td>₱<?= number_format($row['quantity'] * $row['price'], 2); ?></td>
        <td class="text-center">
          <a href="?delete=<?= $row['sale_item_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="text-end fw-bold fs-5">
    Total Sales Amount: ₱<?= number_format($total_amount, 2); ?>
  </div>
</div>

</body>
</html>
