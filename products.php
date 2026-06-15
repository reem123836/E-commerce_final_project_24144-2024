<?php
require_once "config.php";

// 1. فحص ما إذا كان المستخدم يطلب تفاصيل منتج معين (Specs)
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$view_details = false;

if ($product_id > 0) {
    // جلب تفاصيل المنتج المحدد بأمان من قاعدة البيانات
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        $view_details = true;
    }
}

// 2. جلب متغيرات الفلاتر في حال عرض القائمة الكاملة للمنتجات
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $view_details ? htmlspecialchars($product['name']) . " | Specs" : "Certified Inventory | AuraTech Agency"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
            --card-glass: rgba(30, 41, 59, 0.45);
            --img-bg-gradient: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(6, 182, 212, 0.05) 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 50%, #0f172a 100%);
            color: var(--text-light);
            min-height: 100vh;
            background-attachment: fixed;
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

        /* لوحة الفلتر الجانبية الكريستالية */
        .filter-sidebar {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 4px solid var(--neon-cyan);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* الكروت الحديثة ذات المظهر الانسيابي المتناسق */
        .product-cyber-card {
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .product-cyber-card:hover {
            transform: translateY(-8px);
            border-color: rgba(6, 182, 212, 0.4);
            background: rgba(30, 41, 59, 0.6);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
        }
        /* حاوية الصورة المبتكرة لدمج المنتجات بدون كتم الأبعاد */
        .product-img-container {
            background: var(--img-bg-gradient);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            height: 200px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            padding: 15px;
            transition: all 0.4s ease;
        }

        /* احتواء ذكي ومريح للعين يمنع تشوه صور اللابتوب والإكسسوارات */
        .product-img-inventory {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.5));
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .product-cyber-card:hover .product-img-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(6, 182, 212, 0.1) 100%);
        }

        .product-cyber-card:hover .product-img-inventory {
            transform: scale(1.06) translateY(-4px);
        }

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.12);
            color: var(--neon-cyan);
            border: 1px solid rgba(6, 182, 212, 0.25);
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .product-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            margin-top: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-add-cart, .btn-view-details {
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .btn-view-details {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-view-details:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .btn-add-cart {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            border: none;
        }

        .btn-add-cart:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
        }

        .cyber-filter-input {
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            border-radius: 8px;
        }

        .spec-table {
            color: var(--text-light) !important;
        }

        .spec-table th {
            background: rgba(255, 255, 255, 0.08) !important;
            color: var(--neon-cyan);
            width: 30%;
        }

        .spec-table td {
            background: rgba(255, 255, 255, 0.02) !important;
            color: var(--text-light);
        }

        .style-back-link {
            font-weight: 600;
            transition: opacity 0.2s ease;
        }
        .style-back-link:hover {
            opacity: 0.8;
        }

        .details-cyber-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
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
                    <a class="nav-item nav-link <?php echo $selected_category == 'Laptop' ? 'active' : ''; ?>" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link <?php echo $selected_category == 'Accessory' ? 'active' : ''; ?>" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                    
                    <a href="cart.php" class="btn position-relative p-2 text-white" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                        <i class="bi bi-cart3 fs-4" style="color: var(--neon-cyan);"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-5 my-4">

        <?php if ($view_details): ?>
            <div class="mb-4">
                <a href="products.php" class="text-decoration-none style-back-link" style="color: var(--neon-cyan);">
                    <i class="bi bi-arrow-left me-2"></i>Back to Inventory
                </a>
            </div>
            <div class="details-cyber-card p-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-5 text-center">
                        <div class="p-4 rounded-4" style="background: rgba(11, 15, 25, 0.5); border: 1px solid rgba(255,255,255,0.05);">
                            <img src="assets/images/products/<?php echo !empty($product['image_url']) ? $product['image_url'] : 'default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="img-fluid" style="max-height: 300px; object-fit: contain; filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.3));">
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <span class="badge badge-cyan mb-2"><?php echo htmlspecialchars($product['brand']); ?></span>
                        <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($product['product_type']); ?></span>
                        
                        <h1 class="display-5 fw-bold text-white mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="fs-2 fw-bold mb-4" style="color: var(--neon-cyan);">$<?php echo number_format($product['price'], 2); ?></p>
                        
                        <h5 class="fw-bold text-white-50 text-uppercase small"> Description</h5>
                        <p class="text-light opacity-75 lead mb-4"><?php echo htmlspecialchars($product['description']); ?></p>

                        <h5 class="fw-bold text-white-50 mb-3 small text-uppercase"> Specifications </h5>
                        <table class="table table-bordered spec-table">
                            <tbody>
                                <tr><th>Brand</th><td><?php echo htmlspecialchars($product['brand']); ?></td></tr>
                                <tr><th>Asset Pool</th><td><?php echo htmlspecialchars($product['product_type']); ?> Unit</td></tr>
                                <tr><th>Registry Status</th><td>
                                    <?php echo ($product['stock_quantity'] > 0) ? "<span class='text-success fw-bold'>In Stock ({$product['stock_quantity']} Units Active)</span>" : "<span class='text-danger fw-bold'>Out of Registry Stock</span>"; ?>
                                    </td></tr>
                                <tr><th>Warranty Spectrum</th><td>12 Months Certified Regional Manufacturer Warranty</td></tr>
                            </tbody>
                        </table>

                        <form action="cart_action.php" method="POST" class="mt-4">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto"><label class="col-form-label fw-bold text-white"> Quantity:</label></div>
                                <div class="col-auto">
                                    <input type="number" name="quantity" class="form-control cyber-filter-input" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 90px;">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-add-cart px-4 py-2 text-dark shadow" <?php echo ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                                        <i class="bi bi-cart-plus-fill me-2"></i> Shopping Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <header class="mb-5">
                <h1 class="display-4 fw-bold text-white mb-1">ALL Products</h1>
                <p class="text-light opacity-50 fs-5"></p>
            </header>

            <div class="row g-4">
                <aside class="col-lg-3">
                    <div class="filter-sidebar">
                        <h4 class="fw-bold mb-4 text-white"><i class="bi bi-sliders2-vertical me-2" style="color: var(--neon-cyan);"></i>Filters</h4>
                        
                        <form action="products.php" method="GET" class="mb-4">
                            <label class="form-label opacity-75 small fw-bold text-uppercase">Search Asset</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control cyber-filter-input" placeholder="e.g., HP, Mouse..." value="<?php echo htmlspecialchars($search_query); ?>">
                                <button class="btn btn-outline-info" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>

                        <div class="mb-4">
                            <label class="form-label opacity-75 small fw-bold text-uppercase">Category Focus</label>
                            <div class="d-flex flex-column gap-2 mt-2">
                                <a href="products.php?category=All" class="btn btn-sm text-start <?php echo $selected_category == 'All' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>">All products</a>
                                <a href="products.php?category=Laptop" class="btn btn-sm text-start <?php echo $selected_category == 'Laptop' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>"> Laptops</a>
                                <a href="products.php?category=Accessory" class="btn btn-sm text-start <?php echo $selected_category == 'Accessory' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>"> Accessories</a>
                            </div>
                        </div>
                    </div>
                </aside>
                <main class="col-lg-9">
                    <div class="row g-4">
                        <?php
                       
                        $query = "SELECT * FROM products WHERE 1=1";
                        
                        if ($selected_category !== 'All') {
                            $query .= " AND product_type = '" . $conn->real_escape_string($selected_category) . "'";
                        }
                        
                        if (!empty($search_query)) {
                            $query .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' OR description LIKE '%" . $conn->real_escape_string($search_query) . "%' OR brand LIKE '%" . $conn->real_escape_string($search_query) . "%')";
                        }

                       
                        $query .= " ORDER BY FIELD(product_type, 'Laptop', 'Accessory'), id DESC";

                        $db_result = $conn->query($query);
                        $displayed_count = 0;

                        if ($db_result && $db_result->num_rows > 0):
                            while($prod = $db_result->fetch_assoc()):
                                $displayed_count++;
                                $prod_img = !empty($prod['image_url']) ? $prod['image_url'] : 'default.jpg';
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="product-cyber-card">
                                <div>
                                    <div class="product-img-container">
    <?php
    // 1. مصفوفة يدوية للصور الثلاث الجديدة
   $special_images = [
    'Elgato Stream Deck MK.2' => 'stream_deck.png',
    'Corsair Virtuoso RGB Wireless SE' => 'corsair_virtuoso.png',
    'Anker Prime 20000mAh Power Bank' => 'anker_prime.png'
];

    // 2. اختيار الصورة (إذا كان المنتج موجوداً في المصفوفة نأخذ صورته، وإلا نأخذ من قاعدة البيانات)
    $display_img = isset($special_images[$prod['name']]) ? $special_images[$prod['name']] : $prod['image_url'];
    ?>

    <img src="assets/images/products/<?php echo !empty($display_img) ? $display_img : 'default.jpg'; ?>" 
         alt="<?php echo htmlspecialchars($prod['name']); ?>" 
         class="img-fluid product-img-inventory"
         onerror="this.src='assets/images/products/default.jpg'">
</div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge badge-cyan"><?php echo htmlspecialchars($prod['brand']); ?></span>
                                        <span class="small opacity-50" style="font-size: 0.8rem;"><?php echo htmlspecialchars($prod['product_type']); ?></span>
                                    </div>
                                    <h5 class="product-title" title="<?php echo htmlspecialchars($prod['name']); ?>"><?php echo htmlspecialchars($prod['name']); ?></h5>
                                    <p class="text-light opacity-50 small mb-3" style="line-height: 1.5; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo htmlspecialchars($prod['description']); ?>
                                    </p>
                                </div>
                                
                                <div class="pt-3 border-top border-secondary border-opacity-25">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fs-4 fw-bold" style="color: var(--neon-cyan); font-family: system-ui, sans-serif;">$<?php echo number_format($prod['price'], 2); ?></span>
                                        <span class="small <?php echo ($prod['stock_quantity'] > 0) ? 'text-success' : 'text-danger'; ?> fw-semibold">
                                            <?php echo ($prod['stock_quantity'] > 0) ? 'In Stock' : 'Out of Stock'; ?>
                                        </span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-5">
                                            <a href="products.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-view-details w-100 text-center">
                                                <i class="bi bi-eye me-1"></i> Specs
                                            </a>
                                        </div>
                                        <div class="col-7">
                                            <form action="cart_action.php" method="POST">
                                                <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                                <input type="hidden" name="action" value="add">
                                                <button type="submit" class="btn btn-sm btn-add-cart w-100" <?php echo ($prod['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        endif;

                        if($displayed_count == 0): 
                        ?>
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-cpu fs-1 opacity-25 d-block mb-3" style="color: var(--neon-cyan);"></i>
                            <p class="fs-4 opacity-50">Zero active database assets matched the parameters.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
        <?php endif; ?>

    </div>

    <footer class="text-center py-4 mt-5" style="background: rgba(11, 15, 25, 0.8); border-top: 1px solid rgba(6, 182, 212, 0.1);">
        <div class="container"><small class="text-light opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</small></div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>