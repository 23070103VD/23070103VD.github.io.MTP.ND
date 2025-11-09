<?php include 'db.php';
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html><head><title>Products</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container" style="margin-top:50px;">
    <h2>Product List</h2>
    <a href="add.php" class="btn btn-success">Add New</a>
    <a href="index.php" class="btn btn-default">Home</a>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Deleted!</div>
    <?php endif; ?>
    <table class="table table-bordered" style="margin-top:20px;">
        <thead><tr><th>ID</th><th>Name</th><th>Brand</th><th>Price</th><th>Size</th><th>Color</th><th>Actions</th></tr></thead>
        <tbody>
        <?php
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        $res = $stmt->get_result();
        while ($p = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['brand'] ?? '-') ?></td>
                <td>$<?= number_format($p['price'],2) ?></td>
                <td><?= htmlspecialchars($p['size'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['color'] ?? '-') ?></td>
                <td>
                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-xs">Edit</a>
                    <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body></html>