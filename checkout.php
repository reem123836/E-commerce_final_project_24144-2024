<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config.php";

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header("Location: index.php");
    exit();
}

$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// معالجة إرسال البيانات وحفظها في قاعدة البيانات (Orders Management)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // 1. إدخال الطلب الرئيسي في جدول orders
    $insert_order_query = "INSERT INTO orders (customer_name, customer_email, customer_phone, delivery_address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_order_query);
    $stmt->bind_param("sssssd", $name, $email, $phone, $address, $payment, $total_amount);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id; // جلب رقم الطلب الذي تم إنشاؤه للتو

        // 2. إدخال تفاصيل المنتجات داخل جدول order_items وتحديث المخزون
        foreach ($cart_items as $product_id => $item) {
            $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_item = $conn->prepare($insert_item_query);
            $stmt_item->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
            $stmt_item->execute();

            // تحديث كميات المخزون في جدول المنتجات لتنفيذ الـ Full CRUD
            $update_stock = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
            $stmt_stock = $conn->prepare($update_stock);
            $stmt_stock->bind_param("ii", $item['quantity'], $product_id);
            $stmt_stock->execute();
        }

        // تفريغ السلة من الجلسة بعد نجاح العملية
        unset($_SESSION['cart']);

        // التوجيه لصفحة التأكيد مع تمرير رقم الطلب
        header("Location: confirmation.php?id=" . $order_id);
        exit();
    } else {
        $error_msg = "Critical Core Architecture Database execution error.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout Deployment | AuraTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-blue: #0A2540; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: var(--main-blue) !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">✨ AuraTech Agency</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card p-4 shadow-sm border-0 bg-white">
                    <h4 class="fw-bold text-dark mb-3">Customer Verification & Shipping Data</h4>
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>
                    <form action="checkout.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="John Doe" required>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="customer_email" class="form-control" placeholder="john@unilak.ac.rw" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mobile Phone Number</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="+250 78X XXX XXX" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Delivery Physical Address</label>
                            <textarea name="delivery_address" class="form-control" rows="3" placeholder="KK 21 Ave, Kigali, Rwanda" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Integrated Payment Gateway (Bonus Feature)</label>
                            <select name="payment_method" class="form-select bg-light fw-bold text-primary">
                                <option value="Mobile Money">MTN Mobile Money (MoMo Rwanda)</option>
                                <option value="Airtel Money">Airtel Money</option>
                                <option value="Cash on Delivery">Direct Agency Logistics Handover</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Authorize and Confirm Payment</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card p-4 shadow-sm border-0 bg-light">
                    <h5 class="fw-bold text-dark mb-3">Order Structural Summary</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach($cart_items as $item): ?>
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center small">
                                <div>
                                    <h6 class="m-0 fw-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                                <span class="text-secondary fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3 text-success">
                        <span>Total Invoice:</span>
                        <span>$<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 bg-dark text-white-50">
        <div class="container"><small>Designed by Reem Osama - Checkout Pipeline Context</small></div>
    </footer>
</body>
</html>