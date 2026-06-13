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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4;
            --neon-pink: #ec4899;
            --text-light: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 50%, #0f172a 100%);
            color: #ffffff;
            min-height: 100vh;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        /* شريط التنقل الشفاف الموحد المضيء */
        .custom-navbar {
            background-color: transparent !important;
            padding: 25px 0;
            border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        }

        .custom-navbar .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #ffffff, var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* اللوحات الكريستالية الشفافة (Glassmorphism) */
        .checkout-cyber-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            color: #ffffff !important;
        }

        /* تنسيق حقول الإدخال المتناسقة والمضيئة */
        .form-label {
            color: #ffffff !important;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.3) !important;
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        /* تخصيص قائمة خيارات الدفع لتظهر بشكل داكن وأنيق */
        .form-select option {
            background-color: #1e1b4b !important;
            color: #ffffff !important;
        }

        /* زر الإرسال المضيء الأصلي بالستايل الجديد */
        .btn-cyber-submit {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            border: none;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border-radius: 30px;
        }

        .btn-cyber-submit:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.6);
            transform: translateY(-1px);
            color: #0b0f19 !important;
        }

        /* عناصر قائمة المنتجات الجانبية */
        .list-group-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 15px 0 !important;
            color: #ffffff !important;
        }

        .product-title-text {
            color: #ffffff !important;
            font-size: 1.05rem;
        }

        /* التذييل السفلي الموحد */
        .custom-footer {
            background: rgba(11, 15, 25, 0.8) !important;
            border-top: 1px solid rgba(6, 182, 212, 0.1);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container px-5">
            <a class="navbar-brand fw-bold fs-3" href="index.php">AuraTech Agency</a>
        </div>
    </nav>

    <main class="container py-5">
        <header class="mb-5">
            <h1 class="display-4 fw-bold text-white mb-1"><i class="bi bi-shield-check me-3" style="color: var(--neon-cyan);"></i>Secure Checkout Deployment</h1>
            <p class="text-light opacity-50 fs-5" style="color: #ffffff !important; opacity: 0.7 !important;">Finalize secure enterprise asset acquisition protocols.</p>
        </header>

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card checkout-cyber-card border-0">
                    <h4 class="fw-bold text-white mb-4"><i class="bi bi-person-check me-2" style="color: var(--neon-cyan);"></i>Customer Verification & Shipping Data</h4>
                    
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-white mb-4"><?php echo $error_msg; ?></div>
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
                            <select name="payment_method" class="form-select fw-bold text-white">
                                <option value="Mobile Money">MTN Mobile Money (MoMo Rwanda)</option>
                                <option value="Airtel Money">Airtel Money</option>
                                <option value="Cash on Delivery">Direct Agency Logistics Handover</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-cyber-submit w-100 py-3 fw-bold fs-5 shadow">Authorize and Confirm Payment</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card checkout-cyber-card border-0" style="background: rgba(255, 255, 255, 0.03);">
                    <h5 class="fw-bold text-white mb-3"><i class="bi bi-receipt me-2" style="color: var(--neon-cyan);"></i>Order Structural Summary</h5>
                    <ul class="list-group list-group-flush mb-3 bg-transparent">
                        <?php foreach($cart_items as $item): ?>
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center small">
                                <div>
                                    <h6 class="m-0 fw-bold product-title-text"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-white opacity-50">Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                                <span class="fw-bold" style="color: #ffffff;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between fs-4 fw-bold border-top pt-3" style="color: var(--neon-cyan);">
                        <span>Total Invoice:</span>
                        <span>$<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-white-50 custom-footer mt-5">
        <div class="container"><small class="text-white opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama - Checkout Pipeline Context</small></div>
    </footer>
</body>
</html>