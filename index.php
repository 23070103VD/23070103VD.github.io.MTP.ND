<?php
// index.php
include 'db.php'; // Includes database connection and starts session

// Fetch products from the database to display on the homepage
// We'll limit it to the latest 8 products for this example
$products = [];
$errorMsg = '';
try {
    $sql = "SELECT id, brand, price, image, description FROM products ORDER BY id DESC LIMIT 8";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} catch (mysqli_sql_exception $e) {
    // If the 'brand' column (or others) don't exist, try to detect available columns
    try {
        $colsRes = $conn->query("SHOW COLUMNS FROM products");
        $cols = [];
        while ($c = $colsRes->fetch_assoc()) {
            $cols[] = $c['Field'];
        }

        // Decide which fields to select based on availability
        $select = [];
        if (in_array('id', $cols)) {
            $select[] = 'id';
        }

        // Choose a name/title field
        $nameField = null;
        foreach (['brand', 'name', 'title', 'product_name'] as $f) {
            if (in_array($f, $cols)) { $nameField = $f; $select[] = $f; break; }
        }

        // Price
        foreach (['price', 'cost'] as $f) {
            if (in_array($f, $cols)) { $select[] = $f; break; }
        }

        // Image
        foreach (['image', 'img', 'image_url', 'photo'] as $f) {
            if (in_array($f, $cols)) { $select[] = $f; break; }
        }

        // Description
        foreach (['description', 'desc', 'details'] as $f) {
            if (in_array($f, $cols)) { $select[] = $f; break; }
        }

        if (count($select) > 0) {
            // Build and run a safe SELECT using the available columns
            $safeCols = array_map(function($c){ return "`" . str_replace("`","",$c) . "`"; }, $select);
            $sql2 = "SELECT " . implode(', ', $safeCols) . " FROM products ORDER BY id DESC LIMIT 8";
            $res2 = $conn->query($sql2);
            while ($row = $res2->fetch_assoc()) {
                $products[] = $row;
            }
        } else {
            $errorMsg = 'Products table exists but has no expected columns (brand/name/price/image/description).';
        }
    } catch (mysqli_sql_exception $e2) {
        $errorMsg = 'Database error: ' . htmlspecialchars($e2->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Welcome to MTP Store</title>
</head>
<body>

    <nav class="navbar">
        <div class="logo"> <a href="index.php">MTP Store</a></div>
        <div>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </nav>

    <div class="hero">
        <h1>Your One-Stop Shop</h1>
        <p>Find the best products at unbeatable prices.</p>
    </div>

    <div class="container" style="background:transparent; box-shadow:none;">
        <?php if (!empty($errorMsg)) : ?>
            <p style="color: red;"><?= $errorMsg ?></p>
        <?php endif; ?>

        <?php if (count($products) > 0) : ?>
            <div class="products-grid">
                <?php foreach ($products as $p) : ?>
                    <div class="product-card">
                        <?php
                            // Determine display name
                            $name = null;
                            foreach (['brand','name','title','product_name'] as $k) {
                                if (isset($p[$k]) && $p[$k] !== null && $p[$k] !== '') { $name = $p[$k]; break; }
                            }

                            // Determine price
                            $price = null;
                            foreach (['price','cost'] as $k) { if (isset($p[$k]) && $p[$k] !== null) { $price = $p[$k]; break; } }

                            // Determine image
                            $img = null;
                            foreach (['image','img','image_url','photo'] as $k) { if (isset($p[$k]) && $p[$k] !== null && $p[$k] !== '') { $img = $p[$k]; break; } }

                            // Determine description
                            $desc = null;
                            foreach (['description','desc','details'] as $k) { if (isset($p[$k]) && $p[$k] !== null && $p[$k] !== '') { $desc = $p[$k]; break; } }
                        ?>

                        <?php if ($img) : ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($name ?? 'Product image') ?>">
                        <?php endif; ?>

                        <h3><?= htmlspecialchars($name ?? 'Product') ?></h3>

                        <?php if ($price !== null) : ?>
                            <div class="price"><?= htmlspecialchars($price) ?></div>
                        <?php endif; ?>

                        <?php if ($desc) : ?>
                            <p><?= htmlspecialchars($desc) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

</body>
</html>