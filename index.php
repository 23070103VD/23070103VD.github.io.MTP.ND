<?php
// index.php
include 'db.php'; // Includes database connection and starts session

// Fetch products from the database to display on the homepage
// We'll limit it to the latest 8 products for this example
$sql = "SELECT id, brand, price, image, description FROM products ORDER BY id DESC LIMIT 8";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to MTP Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #35424a;
            color: #ffffff;
            padding: 10px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 5px 15px;
            border-radius: 3px;
        }
        .navbar a:hover {
            background-color: #576a75;
        }
        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
            padding: 20px 0;
        }
        .hero {
            background: #35424a;
            color: #ffffff;
            padding: 60px 20px;
            text-align: center;
        }
        .hero h1 {
            margin: 0;
            font-size: 3em;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-card h3 {
            margin-top: 10px;
            font-size: 1.2em;
        }
        .product-card .price {
            color: #e8491d;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">MTP Store</div>
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

</body>
</html>