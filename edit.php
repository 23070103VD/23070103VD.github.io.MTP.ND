<?php
include 'db.php';
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: list.php"); exit; }

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) { header("Location: list.php"); exit; }

$message = '';
if ($_POST) {
    $name  = trim($_POST['name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $price = $_POST['price'] ?? 0;
    $size  = trim($_POST['size'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if ($name && $price > 0) {
        $stmt = $conn->prepare("UPDATE products SET name=?, brand=?, price=?, size=?, color=?, image=?, description=? WHERE id=?");
        $stmt->bind_param("ssdssssi", $name, $brand, $price, $size, $color, $image, $desc, $id);
        $message = $stmt->execute()
            ? "<div class='alert alert-success'>Updated!</div>"
            : "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>Name and Price required.</div>";
    }
}
?>
<!DOCTYPE html>
<html><head><title>Edit</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container" style="max-width:600px;margin:50px auto;">
    <h2>Edit Product</h2>
    <?= $message ?>
    <form method="post">
        <input name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required><br>
        <input name="brand" value="<?= htmlspecialchars($product['brand'] ?? '') ?>" class="form-control"><br>
        <input name="price" type="number" step="0.01" value="<?= $product['price'] ?>" class="form-control" required><br>
        <input name="size" value="<?= htmlspecialchars($product['size'] ?? '') ?>" class="form-control"><br>
        <input name="color" value="<?= htmlspecialchars($product['color'] ?? '') ?>" class="form-control"><br>
        <input name="image" value="<?= htmlspecialchars($product['image'] ?? '') ?>" class="form-control"><br>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea><br>
        <button class="btn btn-primary btn-block">Update</button>
        <a href="list.php" class="btn btn-default btn-block">Back</a>
    </form>
</div>
</body></html>