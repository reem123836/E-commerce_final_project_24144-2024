<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Enterprise Hardware Architecture</title>
    <!-- استدعاء البوتستراب -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* الأكواد اللونية الدقيقة المأخوذة من واجهتك الأصلية الفخمة */
        :root {
            --dark-blue: #14213d; /* الأزرق الداكن الملكي */
            --luxury-gold: #fca311; /* الأصفر الذهبي الأنيق */
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        /* شريط التنقل بنفس لون الخلفية الزرقاء الداكنة لدمج الواجهة */
        .custom-navbar {
            background-color: var(--dark-blue) !important;
            border-bottom: 1px solid rgba(252, 163, 17, 0.2);
            padding: 20px 0;
        }

        .custom-navbar .navbar-brand, .custom-navbar .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .custom-navbar .nav-link:hover, .custom-navbar .nav-link.active {
            color: var(--luxury-gold) !important;
        }

        /* الـ Hero Section الفخمة جداً مع توسيط المحتوى والحركات */
        .luxury-hero {
            background: linear-gradient(180deg, var(--dark-blue) 0%, #0a1128 100%);
            min-height: 75vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-center: center;
        }

        /* حركات الأزرار الفخمة في المنتصف */
        .btn-gold {
            background-color: var(--luxury-gold);
            color: var(--dark-blue) !important;
            font-weight: bold;
            border: 2px solid var(--luxury-gold);
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(252, 163, 17, 0.3);
        }

        .btn-gold:hover {
            background-color: transparent;
            color: var(--luxury-gold) !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(252, 163, 17, 0.5);
        }

        .btn-outline-gold {
            border: 2px solid var(--luxury-gold);
            color: var(--luxury-gold) !important;
            font-weight: bold;
            transition: all 0.4s ease;
        }

        .btn-outline-gold:hover {
            background-color: var(--luxury-gold);
            color: var(--dark-blue) !important;
            transform: translateY(-3px);
        }

        /* ستايل المستطيلات الطويلة الممتدة */
        .long-rectangle-section {
            background: #ffffff;
            border-left: 6px solid var(--luxury-gold);
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .long-rectangle-section:hover {
            transform: scale(1.01);
        }
    </style>
</head>
<body>

    <!-- شريط القائمة العلوية الأزرق الداكن الفخم -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold fs-3" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto fs-5 gap-3">
                    <a class="nav-item nav-link active" href="index.php">Home</a>
                    <a class="nav-item nav-link" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- واجهة الـ Hero الفخمة مع الأزرار والحركات المتمركزة في المنتصف -->
    <header class="luxury-hero text-white text-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <span class="badge mb-4 fw-bold px-4 py-2 rounded-pill fs-6" style="background-color: var(--luxury-gold); color: var(--dark-blue);">
                        Authorized Agency - Rwanda
                    </span>
                    <h1 class="display-2 fw-bold mb-4" style="letter-spacing: -1px;">
                        Enterprise Computing &<br>Hardware Architecture
                    </h1>
                    <p class="lead text-light opacity-75 mb-5 fs-4 mx-auto" style="max-width: 750px;">
                        Powering the next computing generation. AuraTech provides original enterprise devices with certified manufacturer warranty matrices.
                    </p>
                    
                    <!-- الأزرار الفخمة والحركات في المنتصف تماماً -->
                    <div class="d-flex gap-4 justify-content-center">
                        <a href="products.php" class="btn btn-gold btn-lg px-5 py-3 fs-5">Explore Certified Inventory</a>
                        <a href="contact.php" class="btn btn-outline-gold btn-lg px-5 py-3 fs-5">Request Enterprise Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- مستطيل طويل ممتد بعرض الصفحة لاستعراض مميزات الوكالة -->
    <main class="container-fluid my-5 px-5">
        <div class="row long-rectangle-section border p-5 shadow-sm align-items-center">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3" style="color: var(--dark-blue);">Why AuraTech Enterprise Logistics?</h2>
                <p class="text-secondary fs-5 mb-0" style="line-height: 1.8;">
                    We register every hardware serial number directly with official manufacturer diagnostics databases. Whether you are building an engineering simulation rig, setting up a student dev environment, or equipping an entire corporate lab network, our computing assets pass deep pipeline validation parameters before deployment.
                </p>
            </div>
        </div>

        <!-- عنوان قسم الـ Profile لأحدث المنتجات -->
        <div class="row my-5">
            <div class="col-12">
                <h3 class="fw-bold border-bottom pb-2" style="color: var(--dark-blue);">Featured Hardware Profile</h3>
            </div>
        </div>

        <!-- محاكاة عرض أحدث 3 منتجات بنظام المستطيلات الطويلة الفخمة متناسقة الألوان -->
        <?php
        // محاكاة مصفوفة المنتجات (لاحقاً نربطها بقاعدة البيانات الفصيلية)
        $featured_products = [
            ['name' => 'High-Performance Architecture Laptop', 'desc' => 'Next-gen processing units optimized for heavy engineering code compile matrices.', 'price' => '1,299.00', 'tag' => 'Laptop'],
            ['name' => 'Enterprise Simulation Workstation', 'desc' => 'Certified high-end dev environments with integrated cloud deployment hardware.', 'price' => '1,850.00', 'tag' => 'Laptop'],
            ['name' => 'Official Microcontroller Interface Kit', 'desc' => 'Hardware components calibrated for embedded IoT system architecture.', 'price' => '89.00', 'tag' => 'Accessory']
        ];

        foreach($featured_products as $prod):
        ?>
        <div class="row bg-white border rounded-3 p-4 mb-4 align-items-center shadow-sm long-rectangle-section">
            <div class="col-md-9">
                <span class="badge bg-dark mb-2 text-white"><?php echo $prod['tag']; ?></span>
                <h4 class="fw-bold mb-2" style="color: var(--dark-blue);"><?php echo $prod['name']; ?></h4>
                <p class="text-muted mb-0"><?php echo $prod['desc']; ?></p>
            </div>
            <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                <h4 class="fw-bold mb-3" style="color: var(--dark-blue);">$<?php echo $prod['price']; ?></h4>
                <a href="products.php" class="btn btn-outline-dark btn-sm px-4 py-2">View in Inventory</a>
            </div>
        </div>
        <?php endforeach; ?>
    </main>

    <!-- التذييل الفخم والموحد الموثق باسمكِ -->
    <footer class="text-white text-center py-4 mt-5" style="background-color: var(--dark-blue); border-top: 2px solid var(--luxury-gold);">
        <p class="mb-0">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <!-- ملفات الجافاسكريبت للبوتستراب -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>