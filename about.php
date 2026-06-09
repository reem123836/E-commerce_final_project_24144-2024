<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About AuraTech Agency</title>
    <!-- استدعاء البوتستراب لتفعيل التصميم -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- شريط التنقل (Navbar) الموحد والكامل لكل صفحات التطبيق -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-item nav-link" href="index.php">Home</a>
                    <a class="nav-item nav-link" href="products.php">Products</a>
                    <a class="nav-item nav-link active" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- محتوى الصفحة القائم على المستطيلات الطويلة الممتدة بعرض الصفحة -->
    <div class="container-fluid my-5 px-4">
        <!-- مستطيل العنوان الرئيسي الممتد -->
        <div class="row bg-dark text-white p-5 rounded-3 mb-4 align-items-center" style="min-height: 200px;">
            <div class="col-md-12 text-center">
                <h1 class="display-4 fw-bold">About AuraTech Agency</h1>
                <p class="lead text-muted">Empowering your digital world with premium computing solutions.</p>
            </div>
        </div>

        <!-- مستطيل الرؤية والأهداف - مستطيل طويل ممتد بعرض الصفحة -->
        <div class="row bg-white border p-5 rounded-3 mb-4 align-items-center shadow-sm">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3">Our Mission</h2>
                <p class="fs-5 text-secondary" style="line-height: 1.8;">
                    At AuraTech Agency, we specialize in providing high-performance laptops, professional hardware, and essential tech accessories tailored for students, developers, and tech enthusiasts. We bridge the gap between cutting-edge technology and affordability, ensuring our community has access to the best computing infrastructure.
                </p>
            </div>
        </div>

        <!-- مستطيل الجودة والضمان - مستطيل طويل آخر -->
        <div class="row bg-white border p-5 rounded-3 mb-4 align-items-center shadow-sm">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3 text-primary">Why Choose Us?</h2>
                <p class="fs-5 text-secondary" style="line-height: 1.8;">
                    Every device in our inventory undergoes rigorous quality assurance testing before deployment. From high-end engineering workstations to daily productivity setups, AuraTech guarantees peak performance, dedicated hardware support, and seamlessly integrated tech solutions.
                </p>
            </div>
        </div>
    </div>

    <!-- تذييل الصفحة (Footer) مدمج وموثق باسمكِ الشخصي -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <!-- ملفات الجافاسكريبت الخاصة بالبوتستراب لتشغيل القائمة المتجاوبة -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>