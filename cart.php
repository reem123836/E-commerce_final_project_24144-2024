<?php
require_once "config.php";

// بدء الجلسة إذا لم تكن بدأت بعد لجلب منتجات السلة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart Matrix | AuraTech Agency</title>
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

        /* شريط التنقل الشفاف الموحد */
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

        .custom-navbar .nav-link {
            color: #ffffff !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .custom-navbar .nav-link:hover, .custom-navbar .nav-link.active {
            color: var(--neon-cyan) !important;
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.6);
        }

        /* اللوحات الكريستالية الشفافة (Glassmorphism) */
        .cart-cyber-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        /* ستايل جدول المنتجات وتبييض كل النصوص */
        .cyber-table {
            color: #ffffff !important;
            vertical-align: middle;
            width: 100%;
        }

        .cyber-table thead th {
            background: rgba(6, 182, 212, 0.1) !important;
            color: var(--neon-cyan);
            border-bottom: 2px solid rgba(6, 182, 212, 0.3);
            text-uppercase: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            padding: 15px;
        }

        .cyber-table tbody td {
            background: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px 15px;
            color: #ffffff !important;
        }

        /* 📐 العرض الموسع الكبير لقسم المواصفات والصور لمنع الزحام */
        .col-specs { width: 55%; }
        .col-price { width: 12%; }
        .col-qty   { width: 13%; }
        .col-total { width: 12%; }
        .col-action{ width: 8%; }

        /* 📸 أبعاد ممتازة وواضحة جداً للصورة */
        .cart-img-container {
            background: rgba(11, 15, 25, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 8px;
            width: 120px;       
            height: 120px;      
            min-width: 120px;   
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.2));
        }

        /* الالتفاف التلقائي للنصوص المانع للتداخل البصري */
        .product-details-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            word-wrap: break-word;
            word-break: break-word;
            overflow: hidden;
        }

        .product-name-text {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff !important;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .product-brand-text {
            font-size: 0.9rem;
            color: #ffffff !important;
            opacity: 0.8;
        }

        /* مدخلات التحكم بالكمية */
        .cyber-qty-input {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            text-align: center;
            border-radius: 6px;
            width: 75px;
        }

        /* أزرار الإجراءات والمتابعة */
        .btn-cyber-action {
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-neon-cyan {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            border: none;
        }

        .btn-neon-cyan:hover {
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
            transform: translateY(-1px);
        }

        .btn-outline-cyber {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-outline-cyber:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        .btn-remove-item {
            color: var(--neon-pink);
            background: transparent;
            border: none;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .btn-remove-item:hover {
            color: #f43f5e;
            transform: scale(1.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.05rem;
            color: #ffffff !important;
        }
        
        .summary-row span {
            color: #ffffff !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold fs-3" href="products.php">AuraTech Agency</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto fs-5 gap-4 align-items-center">
                    <a class="nav-item nav-link" href="index.php">Home</a>
                    <a class="nav-item nav-link" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                    
                    <a href="cart.php" class="btn position-relative p-2 text-white active" style="background: rgba(6, 182, 212, 0.1); border: 1px solid var(--neon-cyan); border-radius: 8px;">
                        <i class="bi bi-cart3 fs-4" style="color: var(--neon-cyan);"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <header class="mb-5">
            <h1 class="display-4 fw-bold text-white mb-1"><i class="bi bi-cart3 me-3" style="color: var(--neon-cyan);"></i>Shopping Cart Matrix</h1>
            <p class="text-light opacity-50 fs-5" style="color: #ffffff !important; opacity: 0.7 !important;">Review committed hardware units before compiling order execution.</p>
        </header>

        <?php if (empty($cart_items)): ?>
            <div class="cart-cyber-card text-center py-5">
                <i class="bi bi-basket3 fs-1 opacity-25 d-block mb-3" style="color: var(--neon-cyan);"></i>
                <h3 class="fw-bold mb-3 text-white">Your Cart Matrix is Empty</h3>
                <p class="text-white opacity-75 mb-4">No enterprise deployment assets have been allocated yet.</p>
                <a href="products.php" class="btn btn-cyber-action btn-neon-cyan px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i>Return to Hardware Inventory
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-cyber-card p-4 table-responsive" style="overflow-x: hidden;">
                        <table class="table cyber-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="col-specs">Asset Specs</th>
                                    <th class="col-price">Price</th>
                                    <th class="col-qty text-center">Quantity</th>
                                    <th class="col-total">Total</th>
                                    <th class="col-action text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($cart_items as $id => $item): 
                                    $total_item_price = $item['price'] * $item['quantity'];
                                    $subtotal += $total_item_price;
                                    
                                    // 🔍 كود استخراج اسم ملف الصورة المعتمد
                                    $img_file = 'default.jpg';
                                    if (!empty($item['image_url'])) {
                                        $img_file = $item['image_url'];
                                    } elseif (!empty($item['image'])) {
                                        $img_file = $item['image'];
                                    } else {
                                        $prod_name_lower = strtolower($item['name']);
                                        if (strpos($prod_name_lower, 'probook') !== false) $img_file = 'hp_probook_450.png';
                                        elseif (strpos($prod_name_lower, 'xps') !== false) $img_file = 'dell_xps_15.png';
                                        elseif (strpos($prod_name_lower, 'macbook air') !== false) $img_file = 'macbook_air_m3.png';
                                        elseif (strpos($prod_name_lower, 'macbook pro') !== false) $img_file = 'macbook_pro_16.png';
                                        elseif (strpos($prod_name_lower, 'master 3s') !== false) $img_file = 'mx_master_3s.png';
                                        elseif (strpos($prod_name_lower, 'backpack') !== false) $img_file = 'dell_backpack.png';
                                        elseif (strpos($prod_name_lower, 'dock') !== false) $img_file = 'hp_dock.png';
                                        elseif (strpos($prod_name_lower, 'strix') !== false) $img_file = 'rog_scar_17.png';
                                        elseif (strpos($prod_name_lower, 'legion') !== false) $img_file = 'legion_pro_7i.png';
                                        elseif (strpos($prod_name_lower, 'stealth') !== false) $img_file = 'msi_stealth.png';
                                        elseif (strpos($prod_name_lower, 'blade') !== false) $img_file = 'razer_blade_14.png';
                                        elseif (strpos($prod_name_lower, 'latitude') !== false) $img_file = 'latitude_7440.png';
                                        elseif (strpos($prod_name_lower, 'mechanical') !== false) $img_file = 'mx_mechanical.png';
                                        elseif (strpos($prod_name_lower, 'wh-1000xm5') !== false) $img_file = 'sony_xm5.png';
                                        elseif (strpos($prod_name_lower, 'basilisk') !== false) $img_file = 'basilisk_v3.png';
                                        elseif (strpos($prod_name_lower, 'ultrasharp') !== false) $img_file = 'dell_32_4k.png';
                                        elseif (strpos($prod_name_lower, 'prime') !== false) $img_file = 'anker_prime.png';
                                        elseif (strpos($prod_name_lower, 'virtuoso') !== false) $img_file = 'corsair_virtuoso.png';
                                        elseif (strpos($prod_name_lower, 'stream deck') !== false) $img_file = 'stream_deck.png';
                                        elseif (strpos($prod_name_lower, 'x10 pro') !== false) $img_file = 'crucial_x10.png';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-4">
                                            <div class="cart-img-container">
                                                <img src="assets/images/products/<?php echo $img_file; ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" onerror="this.src='assets/images/products/default.jpg'">
                                            </div>
                                            <div class="product-details-box">
                                                <h6 class="product-name-text"><?php echo htmlspecialchars($item['name'] ?? ''); ?></h6>
                                                <span class="product-brand-text"><?php echo htmlspecialchars($item['brand'] ?? 'AuraTech Product'); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-white">$<?php echo number_format($item['price'], 2); ?></td>
                                    <td class="text-center">
                                        <form action="cart_action.php" method="POST" class="d-flex justify-content-center">
                                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                            <input type="hidden" name="action" value="update">
                                            <input type="number" name="quantity" class="form-control form-control-sm cyber-qty-input" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td style="color: var(--neon-cyan); font-weight: 700;">$<?php echo number_format($total_item_price, 2); ?></td>
                                    <td class="text-center">
                                        <form action="cart_action.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                            <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="btn-remove-item"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="products.php" class="btn btn-cyber-action btn-outline-cyber px-4 py-2">
                            <i class="bi bi-arrow-left me-2"></i>Continue Allocating Assets
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-cyber-card">
                        <h4 class="fw-bold mb-4 pb-2 border-bottom border-secondary border-opacity-25 text-white">Execution Summary</h4>
                        
                        <div class="summary-row">
                            <span>Allocated Units Subtotal:</span>
                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Secured Delivery Protocol:</span>
                            <span class="text-success fw-bold">FREE Logistics</span>
                        </div>
                        
                        <hr class="border-secondary border-opacity-50 my-4">
                        
                        <div class="summary-row fs-4 fw-bold mb-4">
                            <span>Total Amount:</span>
                            <span style="color: var(--neon-cyan);">$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        
                        <div class="d-grid">
                            <a href="checkout.php" class="btn btn-cyber-action btn-neon-cyan py-3 fs-5 shadow fw-bold">
                                <i class="bi bi-shield-check me-2"></i>Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center py-4 mt-5" style="background: rgba(11, 15, 25, 0.8); border-top: 1px solid rgba(6, 182, 212, 0.1);">
        <div class="container"><small class="text-white opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</small></div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>