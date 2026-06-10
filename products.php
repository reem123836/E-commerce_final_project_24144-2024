<?php
// Initialize session handling
session_start();

// Session Gate Control: Redirect unauthenticated nodes to the login portal
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}
?>
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
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid var(--neon-cyan);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* بطاقات المنتجات العرضية وتعديل تمدد الصور */
        .product-cyber-card, .details-cyber-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
        }
        
        .product-cyber-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden; /* لضمان عدم خروج حواف الصورة عن انحناء الكرت */
        }

        .product-cyber-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--neon-cyan);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
        }

        /* تعديل الحاوية لتملأ العرض الكامل للكرت بدون هوامش داخلية جانباً وبإرتفاع مناسب */
        .product-img-container {
            background: rgba(11, 15, 25, 0.4);
            border-radius: 10px;
            margin-left: -25px;
            margin-right: -25px;
            margin-top: -25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 220px; /* زيادة الارتفاع لتبدو الصورة بارزة وتملأ المساحة */
            overflow: hidden;
        }

        /* جعل الصورة تملأ كامل مساحة الحاوية بشكل كامل ومتناسق */
        .product-img-inventory {
            width: 100%;
            height: 100%;
            object-fit: cover; /* تجعل الصورة تملأ المساحة بالكامل دون تشويه الأبعاد */
            filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.2));
            transition: transform 0.3s ease;
        }

        .product-cyber-card:hover .product-img-inventory {
            transform: scale(1.04); /* تأثير زووم خفيف عند تمرير الماوس */
        }

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--neon-cyan);
            border: 1px solid rgba(6, 182, 212, 0.3);
            font-weight: 600;
        }

        .btn-add-cart, .btn-view-details {
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-view-details {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-view-details:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-add-cart {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            border: none;
        }

        .btn-add-cart:hover {
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
            transform: translateY(-1px);
        }

        .cyber-filter-input {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
        }

        .spec-table {
            color: var(--text-light) !important;
        }

        .spec-table th {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--neon-cyan);
            width: 30%;
        }

        .spec-table td {
            background: rgba(255, 255, 255, 0.03) !important;
            color: var(--text-light);
        }

        .style-back-link {
            font-weight: 600;
            transition: opacity 0.2s ease;
        }
        .style-back-link:hover {
            opacity: 0.8;
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
                    <i class="bi bi-arrow-left me-2"></i>Back to  Inventory
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
                        
                        <h5 class="fw-bold text-white-50 text-uppercase small">Architecture Description</h5>
                        <p class="text-light opacity-75 lead mb-4"><?php echo htmlspecialchars($product['description']); ?></p>

                        <h5 class="fw-bold text-white-50 mb-3 small text-uppercase">Technical Specifications Matrix</h5>
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
                                <div class="col-auto"><label class="col-form-label fw-bold text-white">Execution Quantity:</label></div>
                                <div class="col-auto">
                                    <input type="number" name="quantity" class="form-control cyber-filter-input" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 90px;">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-add-cart px-4 py-2 text-dark shadow" <?php echo ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                                        <i class="bi bi-cart-plus-fill me-2"></i>Commit to Shopping Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <header class="mb-5">
                <h1 class="display-4 fw-bold text-white mb-1">All Products</h1>
                <p class="text-light opacity-50 fs-5">Browse Available Assets</p>
            </header>

            <div class="row g-4">
                <aside class="col-lg-3">
                    <div class="filter-sidebar">
                        <h4 class="fw-bold mb-4 text-white"><i class="bi bi-sliders2-vertical me-2" style="color: var(--neon-cyan);"></i>Filters </h4>
                        
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
                                <a href="products.php?category=All" class="btn btn-sm text-start <?php echo $selected_category == 'All' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>">All Products</a>
                                <a href="products.php?category=Laptop" class="btn btn-sm text-start <?php echo $selected_category == 'Laptop' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>">Authorized Laptops</a>
                                <a href="products.php?category=Accessory" class="btn btn-sm text-start <?php echo $selected_category == 'Accessory' ? 'btn-info text-dark fw-bold' : 'text-light bg-dark bg-opacity-25'; ?>">Official Accessories</a>
                            </div>
                        </div>
                    </div>
                </aside>

                <main class="col-lg-9">
                    <div class="row g-4">
                        <?php
                        // بناء استعلام البحث والتصفية الديناميكي لقاعدة البيانات بشكل مرن وصحيح
                        $query = "SELECT * FROM products WHERE 1=1";
                        
                        if ($selected_category !== 'All') {
                            $query .= " AND product_type = '" . $conn->real_escape_string($selected_category) . "'";
                        }
                        
                        if (!empty($search_query)) {
                            $query .= " AND (name LIKE '%" . $conn->real_escape_string($search_query) . "%' OR description LIKE '%" . $conn->real_escape_string($search_query) . "%' OR brand LIKE '%" . $conn->real_escape_string($search_query) . "%')";
                        }

                        // التعديل هنا فقط لترتيب الأنواع: اللابتوب أولاً ثم الإكسسوارات
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
                                        <img src="assets/images/products/<?php echo $prod_img; ?>" 
                                             alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                                             class="img-fluid product-img-inventory"
                                             onerror="this.src='assets/images/products/default.jpg'">
                                    </div>
                                    <span class="badge badge-cyan mb-2"><?php echo htmlspecialchars($prod['brand']); ?></span>
                                    <h5 class="fw-bold text-white mb-2"><?php echo htmlspecialchars($prod['name']); ?></h5>
                                    <p class="text-light opacity-50 small mb-3" style="line-height: 1.5; min-height: 45px;">
                                        <?php echo htmlspecialchars(substr($prod['description'], 0, 80)) . '...'; ?>
                                    </p>
                                </div>
                                
                                <div class="pt-2 border-top border-secondary border-opacity-25">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-4 fw-bold" style="color: var(--neon-cyan); font-family: sans-serif;">$<?php echo number_format($prod['price'], 2); ?></span>
                                    </div>
                                    <div class="d-grid gap-2 d-flex">
                                        <a href="products.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-view-details flex-grow-1">
                                            <i class="bi bi-eye me-1"></i> Specs
                                        </a>
                                        
                                        <form action="cart_action.php" method="POST" class="flex-grow-1">
                                            <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                            <input type="hidden" name="action" value="add">
                                            <button type="submit" class="btn btn-sm btn-add-cart w-100">
                                                <i class="bi bi-cart-plus"></i> Add
                                            </button>
                                        </form>
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