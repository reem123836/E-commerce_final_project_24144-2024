<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Enterprise Computing</title>
    <!-- استدعاء البوتستراب لتفعيل التصميم -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تحسين مظهر الخلفية والأزرار لتطابق الهوية البصرية الحالية */
        .hero-section {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body class="bg-light">

    <!-- شريط التنقل (Navbar) الموحد والذكي الموجه للصفحات والفلترة المنفصلة -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-item nav-link active" href="index.php">Home</a>
                    <!-- عند الضغط هنا ننتقل لصفحة المنتجات مع إرسال نوع الفئة ليتم فلترتها برمجياً -->
                    <a class="nav-item nav-link" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- الـ Hero Section الفخمة الخاصة بالموقع (كما في الصورة) -->
    <header class="hero-section text-white py-5">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3 fw-bold px-3 py-2 rounded-pill">Authorized Agency - Rwanda</span>
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">
                        Enterprise Computing &<br>Hardware Architecture
                    </h1>
                    <p class="lead text-muted mb-5 fs-4" style="max-width: 650px;">
                        Powering the next computing generation. AuraTech provides original enterprise devices with certified manufacturer warranty matrices.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="products.php" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-dark">Explore Certified Inventory</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg px-4 py-3">Request Enterprise Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- قسم مميزات الوكالة السريعة - مصمم بالمستطيلات العريضة الطويلة -->
    <section class="container-fluid my-5 px-4">
        <div class="row bg-white border p-5 rounded-3 shadow-sm align-items-center">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3 text-dark">Why AuraTech Enterprise Logistics?</h2>
                <p class="text-secondary fs-5 mb-0" style="line-height: 1.7;">
                    We register every hardware serial number directly with official manufacturer diagnostics databases. Whether you are building an engineering simulation rig, setting up a student dev environment, or equipping an entire corporate lab network, our computing assets pass deep pipeline validation parameters before deployment.
                </p>
            </div>
        </div>
    </section>
    <!-- تذييل الصفحة الاحترافي والموثق باسمكِ الشخصي -->
    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <p class="mb-0">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <!-- ملفات الجافاسكريبت المساعدة للبوتستراب لتشغيل القائمة -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>