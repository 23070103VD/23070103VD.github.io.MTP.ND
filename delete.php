<?php
include 'db.php';
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: list.php"); exit; }

$stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) { header("Location: list.php"); exit; }

if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: list.php?deleted=1");
    exit;
}
?>
<!DOCTYPE html>
<html><head><title>Delete</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container text-center" style="margin-top:100px;">
    <h3>Delete Product</h3>
    <p>Delete <strong><?= htmlspecialchars($product['name']) ?></strong>?</p>
    <form method="post" style="display:inline;">
        <input type="hidden" name="confirm" value="yes">
        <button class="btn btn-danger">Yes</button>
    </form>
    <a href="list.php" class="btn btn-default">No</a>
</div>
</body></html>