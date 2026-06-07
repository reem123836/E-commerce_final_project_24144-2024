<?php
require_once "config.php";

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// سحب تفاصيل المنتج بأمان باستخدام Prepared Statements
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Error: Authorized product architecture not found in registry.</div></div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | AuraTech Specs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-blue: #0A2540; --accent-blue: #635BFF; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: var(--main-blue) !important; }
        .spec-table th { background-color: #eaedf1; width: 30%; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">✨ AuraTech Agency</a>
            <a class="btn btn-outline-light btn-sm" href="cart.php">🛒 Cart</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4"><a href="index.php" class="text-decoration-none">← Back to Authorized Inventory</a></div>

        <div class="row g-5 bg-white p-4 rounded-3 shadow-sm">
            <div class="col-md-7">
                <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($product['brand']); ?></span>
                <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($product['product_type']); ?></span>
                <h1 class="display-5 fw-bold text-dark mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="fs-3 fw-bold text-success mb-4">$<?php echo number_format($product['price'], 2); ?></p>
                
                <h4 class="fw-bold text-secondary">Description</h4>
                <p class="text-muted lead mb-4"><?php echo htmlspecialchars($product['description']); ?></p>

                <h4 class="fw-bold text-secondary mb-3">Technical Specifications</h4>
                <table class="table table-bordered spec-table">
                    <tbody>
                        <tr><th>Brand</th><td><?php echo htmlspecialchars($product['brand']); ?></td></tr>
                        <tr><th>Type</th><td><?php echo htmlspecialchars($product['product_type']); ?> Hardware Asset</td></tr>
                        <tr><th>Stock Status</th><td>
                            <?php echo ($product['stock_quantity'] > 0) ? "<span class='text-success fw-bold'>In Stock ({$product['stock_quantity']} Left)</span>" : "<span class='text-danger fw-bold'>Out of Stock</span>"; ?>
                        </td></tr>
                        <tr><th>Warranty</th><td>12 Months Certified Regional Manufacturer Warranty</td></tr>
                    </tbody>
                </table>

                <form action="cart_action.php" method="POST" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto"><label class="col-form-label fw-bold">Quantity:</label></div>
                        <div class="col-auto"><input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 80px;"></div>
                        <div class="col-auto"><button type="submit" class="btn btn-dark px-4 py-2 rounded-pill shadow-sm" <?php echo ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>Add to Shopping Cart</button></div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 bg-dark text-white-50 mt-5">
        <div class="container"><small>Designed by Reem Osama - Evaluation Context</small></div>
    </footer>
</body>
</html>