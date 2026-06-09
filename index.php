<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Enterprise Hardware Architecture</title>
    <!-- استدعاء البوتستراب -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-blue: #0f172a;    /* كحلي داكن جداً ملكي */
            --mid-blue: #1e293b;     /* أزرق وسيط للدمج */
            --luxury-gold: #fca311;  /* الذهبي المضيء للموقع */
            --text-light: #f8fafc;
        }

        /* دمج خلفية الموقع بالكامل بتدرج انسيابي طويل يمنع أي قطع حاد */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, var(--dark-blue) 0%, var(--mid-blue) 40%, #334155 100%);
            color: var(--text-light);
            min-height: 100vh;
            background-attachment: fixed; /* يجعل التدرج ثابتاً وفخماً أثناء التمرير */
        }

        /* شريط تنقل شفاف تماماً يطفو فوق الخلفية المتدرجة */
        .custom-navbar {
            background-color: transparent !important;
            padding: 25px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .custom-navbar .navbar-brand, .custom-navbar .nav-link {
            color: #ffffff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .custom-navbar .nav-link:hover, .custom-navbar .nav-link.active {
            color: var(--luxury-gold) !important;
            transform: translateY(-1px);
        }

        /* الـ Hero Section المدمجة بسلاسة مع توسيط احترافي */
        .luxury-hero {
            padding: 100px 0 60px 0;
            text-align: center;
        }

        /* تأثيرات الأزرار الذهبية الحركية المضيئة في المنتصف */
        .btn-gold {
            background-color: var(--luxury-gold);
            color: var(--dark-blue) !important;
            font-weight: 700;
            border: 2px solid var(--luxury-gold);
            border-radius: 30px;
            padding: 14px 35px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 0 20px rgba(252, 163, 17, 0.4);
        }

        .btn-gold:hover {
            background-color: transparent;
            color: var(--luxury-gold) !important;
            transform: translateY(-4px);
            box-shadow: 0 0 30px rgba(252, 163, 17, 0.7);
        }

        .btn-outline-gold {
            border: 2px solid rgba(252, 163, 17, 0.6);
            color: var(--luxury-gold) !important;
            font-weight: 700;
            border-radius: 30px;
            padding: 14px 35px;
            transition: all 0.4s ease;
        }

        .btn-outline-gold:hover {
            background-color: var(--luxury-gold);
            color: var(--dark-blue) !important;
            border-color: var(--luxury-gold);
            transform: translateY(-4px);
            box-shadow: 0 0 25px rgba(252, 163, 17, 0.4);
        }

        /* الفكرة الواو: مستطيلات زجاجية شبه شفافة ممتدة (Glassmorphic Long Rectangles) */
        .glass-rectangle {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px); /* عمل تأثير ضبابي فخم للخلفية المتدرجة */
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-left: 5px solid var(--luxury-gold); /* الحافة الذهبية المميزة لمستطيلاتكِ */
            border-radius: 12px;
            padding: 40px;
            transition: all 0.4s ease;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .glass-rectangle:hover {
            transform: translateY(-5px) scale(1.005);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(252, 163, 17, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
   .badge-gold {
            background-color: rgba(252, 163, 17, 0.15);
            color: var(--luxury-gold);
            border: 1px solid rgba(252, 163, 17, 0.3);
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- شريط القائمة العلوية الشفاف والمنساب مع الخلفية -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold fs-3" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto fs-5 gap-4">
                    <a class="nav-item nav-link active" href="index.php">Home</a>
                    <a class="nav-item nav-link" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- واجهة الـ Hero الاحترافية المتمركزة في قلب التدرج اللوني -->
    <header class="luxury-hero text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <span class="badge mb-4 fw-bold px-4 py-2 rounded-pill fs-6 badge-gold">
                        Authorized Agency - Rwanda
                    </span>
                    <h1 class="display-2 fw-bold mb-4" style="letter-spacing: -1px; background: linear-gradient(180deg, #ffffff 60%, #cbd5e1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Enterprise Computing &<br>Hardware Architecture
                    </h1>
                    <p class="lead text-light opacity-75 mb-5 fs-4 mx-auto" style="max-width: 750px;">
                        Powering the next computing generation. AuraTech provides original enterprise devices with certified manufacturer warranty matrices.
                    </p>
                    
                    <!-- الأزرار الفخمة والمضيئة الممركزة في المنتصف تماماً -->
                    <div class="d-flex gap-4 justify-content-center">
                        <a href="products.php" class="btn btn-gold btn-lg px-5 py-3 fs-5">Explore Certified Inventory</a>
                        <a href="contact.php" class="btn btn-outline-gold btn-lg px-5 py-3 fs-5">Request Enterprise Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- مساحة محتوى المستطيلات الطويلة المنسابة مع تدرج الخلفية -->
    <main class="container-fluid my-5 px-5">
        
        <!-- مستطيل الميزات الزجاجي الممتد بعرض الصفحة -->
        <div class="row glass-rectangle mb-5 align-items-center mx-1">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3" style="color: var(--luxury-gold);">Why AuraTech Enterprise Logistics?</h2>
                <p class="fs-5 text-light opacity-75 mb-0" style="line-height: 1.8;">
                    We register every hardware serial number directly with official manufacturer diagnostics databases. Whether you are building an engineering simulation rig, setting up a student dev environment, or equipping an entire corporate lab network, our computing assets pass deep pipeline validation parameters before deployment.
                </p>
            </div>
        </div>

        <!-- عنوان قسم عرض الـ Profile للأجهزة المتاح محاكاتها -->
        <div class="row my-5 mx-1">
            <div class="col-12">
                <h3 class="fw-bold border-bottom border-secondary pb-3 text-white">Featured Hardware Profile</h3>
            </div>
        </div>
     <!-- عرض أحدث 3 منتجات بنظام المستطيلات الزجاجية الشفافة الممتدة الفخمة جداً -->
        <?php
        $featured_products = [
            ['name' => 'High-Performance Architecture Laptop', 'desc' => 'Next-gen processing units optimized for heavy engineering code compile matrices.', 'price' => '1,299.00', 'tag' => 'Laptop'],
            ['name' => 'Enterprise Simulation Workstation', 'desc' => 'Certified high-end dev environments with integrated cloud deployment hardware.', 'price' => '1,850.00', 'tag' => 'Laptop'],
            ['name' => 'Official Microcontroller Interface Kit', 'desc' => 'Hardware components calibrated for embedded IoT system architecture.', 'price' => '89.00', 'tag' => 'Accessory']
        ];

        foreach($featured_products as $prod):
        ?>
        <div class="row glass-rectangle mb-4 align-items-center mx-1">
            <div class="col-md-9">
                <span class="badge badge-gold mb-2"><?php echo $prod['tag']; ?></span>
                <h4 class="fw-bold mb-2 text-white"><?php echo $prod['name']; ?></h4>
                <p class="text-light opacity-50 mb-0 fs-5"><?php echo $prod['desc']; ?></p>
            </div>
            <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                <h3 class="fw-bold mb-3" style="color: var(--luxury-gold);">$<?php echo $prod['price']; ?></h3>
                <a href="products.php" class="btn btn-outline-gold btn-sm px-4 py-2">View in Inventory</a>
            </div>
        </div>
        <?php endforeach; ?>
    </main>

    <!-- التذييل المدمج النهائي والموثق باسمكِ -->
    <footer class="text-center py-4 mt-5" style="background: rgba(15, 23, 42, 0.6); border-top: 1px solid rgba(252, 163, 17, 0.2);">
        <p class="mb-0 text-light opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <!-- ملفات الجافاسكريبت للبوتستراب -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>        