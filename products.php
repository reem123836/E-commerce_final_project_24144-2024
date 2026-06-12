<?php
session_start();
require_once "config.php";

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

/* =========================
   PRODUCT DETAILS MODE
========================= */
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
$view_details = false;

if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $view_details = true;
    }
}

/* =========================
   FILTERS (LIST MODE)
========================= */
$selected_category = $_GET['category'] ?? 'All';
$search_query = trim($_GET['search'] ?? '');

/* =========================
   BUILD SAFE QUERY
========================= */
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

/* Category filter */
if ($selected_category !== 'All') {
    $sql .= " AND product_type = ?";
    $params[] = $selected_category;
}

/* Search filter */
if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR description LIKE ? OR brand LIKE ?)";
    $search_param = "%" . $search_query . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

/* Order */
$sql .= " ORDER BY id DESC";

/* =========================
   EXECUTE QUERY (PDO FIX)
========================= */
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$db_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
<?php
echo $view_details
    ? htmlspecialchars($product['name']) . " | Specs"
    : "Certified Inventory | AuraTech Agency";
?>
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial;
    background: linear-gradient(180deg, #0b0f19, #1e1b4b);
    color: white;
    min-height: 100vh;
}

.product-cyber-card {
    background: rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 25px;
    border: 1px solid rgba(255,255,255,0.15);
}

.product-img-container {
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img-inventory {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge-cyan {
    background: rgba(6,182,212,0.1);
    color: #06b6d4;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-5">
        <a class="navbar-brand text-white fw-bold" href="products.php">AuraTech</a>
    </div>
</nav>

<div class="container-fluid px-5 my-4">

<?php if ($view_details): ?>

    <?php if (!$product): ?>
        <div class="alert alert-danger">Product not found</div>
    <?php else: ?>

    <div class="product-cyber-card p-5">
        <div class="row g-5">

            <div class="col-md-5">
                <img src="assets/images/products/<?php echo $product['image_url'] ?: 'default.jpg'; ?>" class="img-fluid">
            </div>

            <div class="col-md-7">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>

                <p class="fs-3 text-info">
                    $<?php echo number_format($product['price'], 2); ?>
                </p>

                <p><?php echo htmlspecialchars($product['description']); ?></p>

                <p><strong>Stock:</strong> <?php echo $product['stock_quantity']; ?></p>

                <form action="cart_action.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <input type="number" name="quantity" min="1"
                           max="<?php echo $product['stock_quantity']; ?>" value="1">

                    <button class="btn btn-info"
                        <?php echo ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                        Add to Cart
                    </button>
                </form>
            </div>

        </div>
    </div>

    <?php endif; ?>

<?php else: ?>

<!-- LIST MODE -->
<div class="row g-4">

    <?php foreach ($db_result as $prod): ?>

    <div class="col-md-4">
        <div class="product-cyber-card">

            <div class="product-img-container">
                <img src="assets/images/products/<?php echo $prod['image_url'] ?: 'default.jpg'; ?>"
                     class="product-img-inventory">
            </div>

            <span class="badge badge-cyan">
                <?php echo htmlspecialchars($prod['brand']); ?>
            </span>

            <h5><?php echo htmlspecialchars($prod['name']); ?></h5>

            <p class="text-light opacity-50">
                <?php echo substr(htmlspecialchars($prod['description']), 0, 80); ?>...
            </p>

            <h4 class="text-info">
                $<?php echo number_format($prod['price'], 2); ?>
            </h4>

            <a href="products.php?id=<?php echo $prod['id']; ?>" class="btn btn-outline-light">
                View Specs
            </a>

        </div>
    </div>

    <?php endforeach; ?>

</div>

<?php endif; ?>

</div>

</body>
</html>