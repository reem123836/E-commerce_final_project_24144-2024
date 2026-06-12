<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
            --neon-cyan: #06b6d4;
        }

        body {
            font-family: 'Segoe UI', Tahoma;
            background: linear-gradient(180deg, var(--cyber-bg-top), var(--cyber-bg-bottom), #0f172a);
            color: white;
            min-height: 100vh;
            background-attachment: fixed;
        }

        .custom-navbar {
            background: transparent !important;
            padding: 25px 0;
            border-bottom: 1px solid rgba(6,182,212,0.1);
        }

        .navbar-brand {
            font-weight: 800;
            background: linear-gradient(90deg,#fff,var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: var(--neon-cyan) !important;
        }
    </style>
</head>

<body>

<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid px-5">

        <a class="navbar-brand fs-3" href="index.php">AuraTech Agency</a>

        <div class="navbar-nav ms-auto gap-3">

            <a class="nav-link" href="index.php">Home</a>
            <a class="nav-link" href="products.php">Inventory</a>
            <a class="nav-link" href="about.php">About</a>
            <a class="nav-link" href="contact.php">Contact</a>

            <!-- FIXED LOGIN LOGIC -->
            <?php if (!$isLoggedIn): ?>
                <a class="nav-link text-info" href="auth.php">Login</a>
            <?php else: ?>
                <a class="nav-link text-warning" href="auth.php">Dashboard</a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<header class="text-center py-5">
    <h1 class="display-3 fw-bold">Enterprise Computing</h1>
    <p class="text-white opacity-75">AuraTech Agency - Certified Hardware Supplier</p>
</header>

<main class="container my-5">

<?php
$featured_products = [
    [
        'name' => 'HP ProBook 450 G10',
        'desc' => 'Intel i7, 16GB RAM, 512GB SSD',
        'price' => '850.00',
        'tag' => 'Laptop',
        'img' => 'hp_probook.png'
    ],
    [
        'name' => 'Dell XPS 15 Ultra',
        'desc' => 'i9, 32GB RAM, RTX 4050',
        'price' => '1899.00',
        'tag' => 'Laptop',
        'img' => 'dell_xps.png'
    ],
    [
        'name' => 'Logitech MX Master 3S',
        'desc' => 'Ergonomic wireless mouse',
        'price' => '99.00',
        'tag' => 'Accessory',
        'img' => 'logitech_mouse.png'
    ]
];

foreach ($featured_products as $prod):
?>
    <div class="row mb-4 p-3 border rounded">

        <div class="col-md-2 text-center">
            <img src="assets/images/products/<?= htmlspecialchars($prod['img']) ?>"
                 onerror="this.src='assets/images/products/default.jpg'"
                 class="img-fluid" style="max-height:120px;">
        </div>

        <div class="col-md-7">
            <span class="badge bg-info"><?= htmlspecialchars($prod['tag']) ?></span>
            <h4><?= htmlspecialchars($prod['name']) ?></h4>
            <p class="text-white opacity-75"><?= htmlspecialchars($prod['desc']) ?></p>
        </div>

        <div class="col-md-3 text-end">
            <h4 class="text-info">$<?= number_format($prod['price'], 2) ?></h4>
            <a href="products.php" class="btn btn-outline-light">View</a>
        </div>

    </div>
<?php endforeach; ?>

</main>

<footer class="text-center py-4 text-white-50">
    &copy; 2026 AuraTech Agency
</footer>

</body>
</html>