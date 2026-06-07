<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config.php";

// حماية الصفحة: إذا لم يكن مسجلاً كمسؤول، يتم طرده لصفحة تسجيل الدخول
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// 1. معالجة عمليات الـ CRUD (إضافة منتج جديد)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $type = mysqli_real_escape_string($conn, $_POST['product_type']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock_quantity']);

    $insert_sql = "INSERT INTO products (name, brand, product_type, description, price, stock_quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssdi", $name, $brand, $type, $desc, $price, $stock);
    $stmt->execute();
    header("Location: admin-dashboard.php?success=Product+Deployed");
    exit();
}

// 2. معالجة عمليات الـ CRUD (حذف منتج)
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin-dashboard.php?success=Product+Purged");
    exit();
}

// جلب إحصائيات المتجر الكلية
$sales_res = $conn->query("SELECT SUM(total_amount) as total FROM orders");
$total_sales = $sales_res->fetch_assoc()['total'] ?? 0.00;

$orders_res = $conn->query("SELECT COUNT(id) as count FROM orders");
$total_orders = $orders_res->fetch_assoc()['count'] ?? 0;

// جلب الطلبات الحالية للعملاء
$orders_list = $conn->query("SELECT * FROM orders ORDER BY id DESC");

// جلب المنتجات الحالية لإدارتها
$products_list = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Mainframe | Admin Operations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-blue: #0A2540; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; }
        .sidebar-brand { background-color: var(--main-blue); color: white; padding: 20px; text-align: center; font-weight: bold; }
        .stat-card { border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

    <div class="sidebar-brand d-flex justify-content-between align-items-center px-4">
        <span>⚙️ AuraTech Administrative Management Mainframe</span>
        <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3">Secure Log Out</a>
    </div>

    <div class="container py-5">
        
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card bg-white p-4 text-center">
                    <h6 class="text-muted text-uppercase small fw-bold">Gross Capital Managed</h6>
                    <h2 class="fw-bold text-success">$<?php echo number_format($total_sales, 2); ?></h2>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card bg-white p-4 text-center">
                    <h6 class="text-muted text-uppercase small fw-bold">Committed Orders</h6>
                    <h2 class="fw-bold text-primary"><?php echo $total_orders; ?> Invoices</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4 shadow-sm border-0 bg-white mb-4">
                    <h5 class="fw-bold mb-3">Deploy New Hardware Asset (Create CRUD)</h5>
                    <form action="admin-dashboard.php" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Asset Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g., ThinkPad E14">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Brand</label>
                                <input type="text" name="brand" class="form-control form-control-sm" required placeholder="Lenovo">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Classification</label>
                                <select name="product_type" class="form-select form-select-sm">
                                    <option value="Laptop">Laptop Asset</option>
                                    <option value="Accessory">Peripheral Accessory</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Technical Description</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Specifications Matrix..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Price (USD)</label>
                                <input type="number" step="0.01" name="price" class="form-control form-control-sm" required placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Initial Stock Inventory Quantity</label>
                                <input type="number" name="stock_quantity" class="form-control form-control-sm" value="10" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-3 px-4 rounded-pill fw-bold">Commit Asset to Inventory</button>
                    </form>
                </div>

                <div class="card p-4 shadow-sm border-0 bg-white">
                    <h5 class="fw-bold mb-3">Active Warehouse Repository (Read & Delete CRUD)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Classification</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($prod = $products_list->fetch_assoc()): ?>
                                    <tr>
                                        <td><span class="fw-bold text-dark"><?php echo htmlspecialchars($prod['name']); ?></span><br><small class="text-muted"><?php echo $prod['brand']; ?></small></td>
                                        <td><span class="badge bg-secondary"><?php echo $prod['product_type']; ?></span></td>
                                        <td class="fw-bold text-success">$<?php echo number_format($prod['price'], 2); ?></td>
                                        <td><?php echo $prod['stock_quantity']; ?> units</td>
                                        <td>
                                            <a href="admin-dashboard.php?delete_id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Purge this asset permanently?')">Purge</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 shadow-sm border-0 bg-white">
                    <h5 class="fw-bold mb-3">Live Customer Invoices Matrix</h5>
                    <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                        <?php if($orders_list->num_rows > 0): ?>
                            <?php while($ord = $orders_list->fetch_assoc()): ?>
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark small">#AUT-000<?php echo $ord['id']; ?></span>
                                        <span class="badge bg-success small">$<?php echo number_format($ord['total_amount'], 2); ?></span>
                                    </div>
                                    <p class="m-0 small text-secondary"><strong>Client:</strong> <?php echo htmlspecialchars($ord['customer_name']); ?></p>
                                    <p class="m-0 small text-secondary"><strong>Phone:</strong> <?php echo htmlspecialchars($ord['customer_phone']); ?></p>
                                    <p class="m-0 small text-muted" style="font-size: 11px;">Gate: <?php echo $ord['payment_method']; ?> | <?php echo $ord['created_at']; ?></p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted small text-center py-4">No consumer invoices registered in relational schema context.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>