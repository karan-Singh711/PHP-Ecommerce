<?php
require_once 'dbConnection.php'; // Include PDO connection

try {
    $productName = isset($_GET['q']) ? trim($_GET['q']) : '';

    if (empty($productName)) {
        echo '<div class="alert alert-warning text-center">Please enter a search term.</div>';
        exit;
    }

    $query = $pdo->prepare('SELECT * FROM products WHERE name_of_product LIKE :productName');
    $query->bindValue(':productName', '%' . $productName . '%');
    $query->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #98A1BC;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-title {
            color: #fff;
            background-color: #555879;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        .product-card {
            background-color: #555879;
            color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease-in-out;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .product-card .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .card-text {
            font-size: 0.95rem;
        }
        .alert {
            background-color: #fff;
            color: #333;
            border-radius: 10px;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Search Results for "<?php echo htmlspecialchars($productName); ?>"</h1>
        <div class="row g-4">
        <?php
        $resultsFound = false;
        while ($row = $query->fetch()) {
            $resultsFound = true;
        ?>
            <div class="col-md-4 col-sm-6">
                <a class="card product-card h-100" href = 'productDetail.php?id=<?php echo htmlspecialchars($row['id'])?>'>
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name_of_product']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description_of_product']); ?></p>
                    </div>
                </a>
            </div>
        <?php
        }
        if (!$resultsFound) {
            echo '<div class="alert text-center">No products found matching "' . htmlspecialchars($productName) . '".</div>';
        }
        ?>
        </div>
    </div>
</body>
</html>
<?php
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
