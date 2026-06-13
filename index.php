<?php
// 1. Session initialization at the absolute top
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Next-Gen Hardware Infrastructure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-purple: #7c3aed;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
        }

        /* Continuous gradient flow across the entire viewport execution plane */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 50%, #0f172a 100%);
            color: var(--text-light);
            min-height: 100vh;
            background-attachment: fixed;
        }

        /* Your exact original floating transparent navbar structure */
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
            transform: translateY(-1px);
        }

        /* Hero Section illumination parameters */
        .luxury-hero {
            padding: 120px 0 60px 0;
            text-align: center;
        }

        .btn-cyan {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            font-weight: 700;
            border: none;
            border-radius: 30px;
            padding: 14px 38px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
        }

        .btn-cyan:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 35px rgba(6, 182, 212, 0.8);
        }

        .btn-outline-purple {
            border: 2px solid rgba(124, 58, 237, 0.6);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 30px;
            padding: 14px 38px;
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
        }

        .btn-outline-purple:hover {
            background: linear-gradient(90deg, var(--neon-purple), #6d28d9);
            border-color: var(--neon-purple);
            transform: translateY(-4px);
            box-shadow: 0 0 25px rgba(124, 58, 237, 0.6);
        }

        /* Glass morphism engineering plates spanning full layout metrics */
        .glass-rectangle {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 5px solid var(--neon-cyan);
            border-radius: 14px;
            padding: 40px;
            transition: all 0.4s ease;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
     }
        
        .glass-rectangle:hover {
            transform: translateY(-5px) scale(1.005);
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
        }
        
        .product-img-home {
            max-height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(6, 182, 212, 0.2));
            transition: transform 0.3s ease;
        }

        .glass-rectangle:hover .product-img-home {
            transform: scale(1.08) rotate(2deg);
        }

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--neon-cyan);
            border: 1px solid rgba(6, 182, 212, 0.3);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold fs-3" href="index.php">AuraTech Agency</a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto fs-5 gap-4 align-items-center">
                    <a class="nav-item nav-link active" href="index.php">Home</a>
                    <!-- Combined Laptops and Accessories into raw Inventory root node link -->
                    <a class="nav-item nav-link" href="products.php">Inventory</a>
                    <a class="nav-item nav-link" href="about.php">About Us</a>
                    <a class="nav-item nav-link" href="contact.php">Contact Us</a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Luminous active profile trace returning straight to core terminal gate -->
                        <a class="nav-item nav-link" href="auth.php" title="Manage Portal" style="color: var(--neon-cyan) !important; text-shadow: 0 0 10px rgba(6, 182, 212, 0.6);">
                            Login
                        </a>
                    <?php else: ?>
                        <!-- Standalone plaintext portal navigation parameter -->
                        <a class="nav-item nav-link" href="auth.php" title="Portal Login">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <header class="luxury-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <span class="badge mb-4 fw-bold px-4 py-2 rounded-pill fs-6 badge-cyan">
                        Authorized Agency - Rwanda
                    </span>
                    <h1 class="display-2 fw-bold mb-4" style="letter-spacing: -1px; background: linear-gradient(180deg, #ffffff 50%, #94a3b8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Enterprise Computing &<br>Hardware Architecture
                    </h1>
                    <p class="lead text-light opacity-75 mb-5 fs-4 mx-auto" style="max-width: 750px;">
                        Powering the next computing generation. AuraTech provides original enterprise devices with certified manufacturer warranty matrices.
                    </p>
                    
                    <div class="d-flex gap-4 justify-content-center">
                        <a href="products.php" class="btn btn-cyan btn-lg px-5 py-3 fs-5">Explore Inventory</a>
                        <a href="contact.php" class="btn btn-outline-purple btn-lg px-5 py-3 fs-5">Book consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
           <main class="container-fluid my-5 px-5">
        <div class="row glass-rectangle mb-5 align-items-center mx-1">
            <div class="col-md-12">
                <h2 class="fw-bold mb-3" style="color: var(--neon-cyan); text-shadow: 0 0 10px rgba(6, 182, 212, 0.3);">Why AuraTech Enterprise Logistics?</h2>
                <p class="fs-5 text-light opacity-75 mb-0" style="line-height: 1.8;">
                    We provide original devices with official manufacturer warranties. Whether you are setting up a personal developer environment or equipping an entire corporate computer lab, our hardware is fully certified and tested before delivery.
                </p>
            </div>
        </div>
        
        <div class="row my-5 mx-1">
            <div class="col-12">
                <h3 class="fw-bold border-bottom border-secondary pb-3 text-white" style="letter-spacing: 0.5px;">Featured Hardware Profile</h3>
            </div>
        </div>

        <?php
        $featured_products = [
            ['name' => 'HP ProBook 450 G10', 'desc' => 'Intel Core i7, 16GB RAM, 512GB SSD. Official 1-year warranty.', 'price' => '850.00', 'tag' => 'Laptop', 'img' => 'hp_probook.png'],
            ['name' => 'Dell XPS 15 Ultra', 'desc' => 'Intel Core i9, 32GB RAM, 1TB SSD, NVIDIA RTX 4050.', 'price' => '1,899.00', 'tag' => 'Laptop', 'img' => 'dell_xps.png'],
            ['name' => 'Logitech MX Master 3S', 'desc' => 'Performance wireless mouse, ergonomic silent design.', 'price' => '99.00', 'tag' => 'Accessory', 'img' => 'logitech_mouse.png']
        ];

        foreach($featured_products as $prod):
        ?>
        <div class="row glass-rectangle mb-4 align-items-center mx-1">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <img src="assets/images/products/<?php echo $prod['img']; ?>" 
                     alt="<?php echo $prod['name']; ?>" 
                     class="img-fluid product-img-home"
                     onerror="this.src='assets/images/products/default.jpg'"> 
            </div>

            <div class="col-md-7">
                <span class="badge badge-cyan mb-2"><?php echo $prod['tag']; ?></span>
                <h4 class="fw-bold mb-2 text-white"><?php echo $prod['name']; ?></h4>
                <p class="text-light opacity-50 mb-0 fs-5"><?php echo $prod['desc']; ?></p>
            </div>

            <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                <h3 class="fw-bold mb-3" style="color: var(--neon-cyan); text-shadow: 0 0 15px rgba(6, 182, 212, 0.4);">$<?php echo $prod['price']; ?></h3>
                <a href="products.php" class="btn btn-outline-purple btn-sm px-4 py-2">View in Inventory</a>
            </div>
        </div>
        <?php endforeach; ?>
    </main>

    <footer class="text-center py-4 mt-5" style="background: rgba(11, 15, 25, 0.8); border-top: 1px solid rgba(6, 182, 212, 0.1);">
        <p class="mb-0 text-light opacity-50">&copy; 2026 AuraTech Agency. Designed by Reem Osama.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>