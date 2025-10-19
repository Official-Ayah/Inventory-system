<?php
include 'db_connect.php';
session_start([
  'cookie_httponly' => true,
  'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
  'cookie_samesite' => 'Lax',
]);

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CREATE or UPDATE
if (isset($_POST['save_item'])) {
  $csrf = (string)($_POST['csrf_token'] ?? '');
  if (!hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(400);
    exit('Invalid request.');
  }
  $sale = (int)($_POST['sale_id'] ?? 0);
  $product = (int)($_POST['product_id'] ?? 0);
  $qty = (int)($_POST['quantity'] ?? 0);
  $price = (float)($_POST['price'] ?? 0);
  $id = isset($_POST['sale_item_id']) ? (int)$_POST['sale_item_id'] : 0;

  if ($sale > 0 && $product > 0 && $qty >= 0 && $price >= 0) {
    if ($id > 0) {
      $stmt = mysqli_prepare($conn, 'UPDATE sale_items SET sale_id = ?, product_id = ?, quantity = ?, price = ? WHERE sale_item_id = ?');
      mysqli_stmt_bind_param($stmt, 'iiidi', $sale, $product, $qty, $price, $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    } else {
      $stmt = mysqli_prepare($conn, 'INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
      mysqli_stmt_bind_param($stmt, 'iiid', $sale, $product, $qty, $price);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $csrf = (string)($_GET['csrf_token'] ?? '');
  if (!hash_equals($_SESSION['csrf_token'], $csrf)) {
    http_response_code(400);
    exit('Invalid request.');
  }
  $id = (int)($_GET['delete'] ?? 0);
  if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM sale_items WHERE sale_item_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

// READ
$sale_items = mysqli_query($conn, 'SELECT si.*, p.product_name, s.sale_id FROM sale_items si LEFT JOIN products p ON si.product_id=p.product_id LEFT JOIN sales s ON si.sale_id=s.sale_id');

// Calculate total
$total_result = mysqli_query($conn, 'SELECT SUM(quantity * price) AS total_amount FROM sale_items');
$total_row = mysqli_fetch_assoc($total_result);
$total_amount = $total_row['total_amount'] ?? 0;

// Fetch dropdown data
$products = mysqli_query($conn, 'SELECT * FROM products');
$sales = mysqli_query($conn, 'SELECT * FROM sales');
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
    <input type="hidden" name="sale_item_id" value="<?php echo isset($_GET['edit_id']) ? e((string)$_GET['edit_id']) : ''; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token']); ?>">

    <div class="row">
      <div class="col-md-3">
        <label>Sale ID</label>
        <select name="sale_id" class="form-control" required>
          <option value="">Select Sale</option>
          <?php while ($row = mysqli_fetch_assoc($sales)) { ?>
            <option value="<?= e((string)$row['sale_id']) ?>"><?= e((string)$row['sale_id']) ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-3">
        <label>Product</label>
        <select name="product_id" class="form-control" required>
          <option value="">Select Product</option>
          <?php while ($row = mysqli_fetch_assoc($products)) { ?>
            <option value="<?= e((string)$row['product_id']) ?>"><?= e($row['product_name']) ?></option>
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
        <td><?= e((string)$row['sale_item_id']); ?></td>
        <td><?= e((string)$row['sale_id']); ?></td>
        <td><?= e($row['product_name']); ?></td>
        <td><?= e((string)$row['quantity']); ?></td>
        <td>₱<?= number_format((float)$row['price'], 2); ?></td>
        <td>₱<?= number_format((float)$row['quantity'] * (float)$row['price'], 2); ?></td>
        <td class="text-center">
          <a href="?delete=<?= e((string)$row['sale_item_id']); ?>&csrf_token=<?= e($_SESSION['csrf_token']); ?>" class="btn btn-danger btn-sm">Delete</a>
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
