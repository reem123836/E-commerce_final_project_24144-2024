<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AuraTech Agency</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4; /* اللون الأزرق السيبراني الأساسي */
            --neon-blue-glow: #3b82f6; /* أزرق متمم للتوهج */
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

        /* 💙 مستطيل زجاجي طولي ممتد بحافة وتوهج أزرق سيبراني */
        .contact-long-rectangle {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-left: 6px solid var(--neon-cyan); /* حافة زرقاء مضيئة */
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            transition: all 0.4s ease;
        }

        .contact-long-rectangle:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 25px 60px rgba(6, 182, 212, 0.25); /* توهج أزرق مذهل عند التمرير */
        }

        /* مربعات إدخال الكلام باللون الأبيض الشفاف المضيء */
        .cyber-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            border-radius: 8px;
            padding: 14px;
            transition: all 0.3s ease;
        }

        .cyber-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .cyber-input:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.4);
            outline: none;
        }

        /* مربع الملاحظة والتعليمات باللون الأبيض الشفاف والحواف الزرقاء */
        .info-note-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 8px;
            padding: 20px;
        }

        .btn-cyan-submit {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
        }

        .btn-cyan-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.6);
        }
        /* تخصيص لون العناوين الفرعية لتشع باللون الأزرق المضيء */
        .text-cyber-cyan {
            color: var(--neon-cyan) !important;
            text-shadow: 0 0 8px rgba(6, 182, 212, 0.2);
        }
    </style>
</head>
<body>

    <!-- شريط القائمة العلوية الشفاف الموحد -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold fs-3" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto fs-5 gap-4">
                    <a class="nav-item nav-link" href="index.php">Home</a>
                    <a class="nav-item nav-link" href="products.php?category=Laptop">Authorized Laptops</a>
                    <a class="nav-item nav-link" href="products.php?category=Accessory">Official Accessories</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link active" href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- واجهة العنوان المباشرة بالنقاء الكريستالي والأزرق -->
    <header class="container text-center" style="padding: 90px 0 40px 0;">
        <h1 class="display-3 fw-bold text-white mb-2" style="text-shadow: 0 0 15px rgba(6, 182, 212, 0.2);">Contact Our Logistics Desk</h1>
    </header>

    <!-- محتوى الصفحة الطولي بالمستطيلات العريضة الممتدة ذات اللمسات الزرقاء السيبرانية -->
    <main class="container-fluid px-5 my-4">
        <div class="row contact-long-rectangle mx-1">
            
            <!-- 📨 قسم فورم الرسالة الطولي -->
            <div class="col-12 mb-5">
                <h3 class="fw-bold mb-4 text-white">Send Secure Message</h3>
                <form action="#" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold opacity-75">Full Name</label>
                            <input type="text" class="form-control cyber-input" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold opacity-75">Email Address</label>
                            <input type="email" class="form-control cyber-input" placeholder="name@company.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold opacity-75">Subject</label>
                            <input type="text" class="form-control cyber-input" placeholder="Hardware Order / Inquiry" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold opacity-75">Message</label>
                            <textarea class="form-control cyber-input" rows="5" placeholder="Type your requirements here..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-cyan-submit w-100 fs-5">Transmit Message</button>
                        </div>
                    </div>
                </form>
            </div>

            <hr class="bg-light opacity-25 my-4">

            <!-- 📍 قسم معلومات الاتصال واللوجستيات الموحد لأسفل الهيكل الطولي -->
            <div class="col-12 mt-3">
                <div class="row g-4 text-center text-md-start">
                    <div class="col-md-4">
                        <h5 class="fw-bold text-cyber-cyan mb-2">📍 Location</h5>
                        <p class="text-light opacity-75 fs-5">Kigali, Rwanda — Corporate Technology District</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="fw-bold text-cyber-cyan mb-2">✉️ Email Endpoint</h5>
                        <p class="text-light opacity-75 fs-5">logistics@auratech.agency</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="fw-bold text-cyber-cyan mb-2">📞 Direct Line</h5>
                        <p class="text-light opacity-75 fs-5">+250 788 000 000</p>
                    </div>
                </div>

                <div class="info-note-box mt-5 text-center">
                    <p class="mb-0 text-light opacity-75 fs-6">
                        All submitted entries pass verification checks to ensure secure device logging parameters.
                    </p>
                </div>
            </div>

        </div>
    </main>

    <!-- التذييل الموحد الفخم والموثق باسمكِ -->
    <footer class="text-center py-4 mt-5" style="background: rgba(11, 15, 25, 0.8); border-top: 1px solid rgba(6, 182, 212, 0.1);">
        <p class="mb-0 text-light opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>