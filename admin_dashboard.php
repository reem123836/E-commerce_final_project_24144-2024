<?php
// admin_dashboard.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 1. Security Gate: Only allow logged-in administrators
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: auth.php");
    exit();
}

require_once 'config.php';

$message = "";
$message_class = "";

// 2. CRUD Operations Engine (Customized for AuraTech Database Schema)

// Create (Insert New Hardware / Accessory Node)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_product'])) {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $product_type = $_POST['product_type']; // 'Laptop' or 'Accessory'
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $image_url = trim($_POST['image_url']);

    if (empty($image_url)) {
        $image_url = 'default.jpg';
    }

    try {
        $stmt = $conn->prepare("INSERT INTO products (name, brand, product_type, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdis", $name, $brand, $product_type, $description, $price, $stock_quantity, $image_url);
        if ($stmt->execute()) {
            $message = "Product initialized inside inventory block successfully.";
            $message_class = "alert-success";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $message = "Creation pipeline failure: " . $e->getMessage();
        $message_class = "alert-danger";
    }
}

// Update (Edit Existing Product Specification)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $id = intval($_POST['product_id']);
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $product_type = $_POST['product_type'];
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $image_url = trim($_POST['image_url']);

    if (empty($image_url)) {
        $image_url = 'default.jpg';
    }

    try {
        $stmt = $conn->prepare("UPDATE products SET name = ?, brand = ?, product_type = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssssdisi", $name, $brand, $product_type, $description, $price, $stock_quantity, $image_url, $id);
        if ($stmt->execute()) {
            $message = "Product hardware matrix updated successfully.";
            $message_class = "alert-success";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $message = "Update pipeline failure: " . $e->getMessage();
        $message_class = "alert-danger";
    }
}

// Delete (Remove Hardware Node)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Product terminated from live deployment node.";
            $message_class = "alert-success";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $message = "Termination failure: " . $e->getMessage();
        $message_class = "alert-danger";
    }
}

// Read (Fetch all active infrastructure products)
$products = [];
try {
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} catch (mysqli_sql_exception $e) {
    $message = "Data stream fetch error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Admin Infrastructure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-purple: #7c3aed;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
            --card-glass: rgba(255, 255, 255, 0.02);
            --border-glass: rgba(255, 255, 255, 0.05);
        }

        body {
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            background-attachment: fixed;
            padding-bottom: 60px;
        }

        .navbar-auratech {
            background: rgba(11, 15, 25, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-glass);
        }

        .dashboard-container {
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-glass);
            border-top: 4px solid var(--neon-cyan);
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            margin-top: 40px;
        }

        .brand-title {
            font-weight: 800;
            background: linear-gradient(90deg, #ffffff, var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control-auratech, .form-select-auratech {
            background-color: rgba(11, 15, 25, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
        }

        .form-control-auratech:focus, .form-select-auratech:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.4) !important;
        }

        .form-select-auratech option {
            background-color: #0b0f19;
            color: #fff;
        }

        .btn-auratech {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-auratech:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.6);
        }

        .table-auratech {
            color: var(--text-light) !important;
        }

        .table-auratech th {
            background-color: rgba(11, 15, 25, 0.8) !important;
            color: var(--neon-cyan) !important;
            border-color: var(--border-glass) !important;
        }

        .table-auratech td {
            background-color: rgba(255, 255, 255, 0.01) !important;
            border-color: var(--border-glass) !important;
            vertical-align: middle;
        }

        .alert-success {
            background-color: rgba(10, 26, 15, 0.5);
            border: 1px solid #064a1c;
            color: #59ff8b;
        }

        .alert-danger {
            background-color: rgba(26, 15, 10, 0.5);
            border: 1px solid #4a2306;
            color: #ff9e59;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-auratech">
    <div class="container">
        <a class="navbar-brand brand-title" href="#">AURATECH SYSTEM CONTROL</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3 opacity-75"><i class="bi bi-shield-lock me-1"></i> Global Cluster</span>
            <a href="index.php" class="btn btn-outline-info btn-sm">View Storefront</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="dashboard-container">
        
        <h2 class="mb-4 text-center" style="font-weight: 700; letter-spacing: 0.5px;">Infrastructure Catalog Controller</h2>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $message_class; ?> text-center mb-4"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Create Form Block -->
        <div class="card card-body bg-transparent border-0 p-0 mb-5">
            <h4 class="mb-3 text-start" style="color: var(--neon-cyan);"><i class="bi bi-plus-circle me-2"></i>Inject Hardware Node</h4>
            <form action="admin_dashboard.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small opacity-75">Product Name</label>
                        <input type="text" name="name" class="form-control form-control-auratech" required autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small opacity-75">Brand Matrix</label>
                        <input type="text" name="brand" class="form-control form-control-auratech" required autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small opacity-75">Product Pipeline Type</label>
                        <select name="product_type" class="form-select form-select-auratech" required>
                            <option value="Laptop">Laptop</option>
                            <option value="Accessory">Accessory</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small opacity-75">Price (USD)</label>
                        <input type="number" step="0.01" name="price" class="form-control form-control-auratech" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small opacity-75">Stock Allocation Matrix</label>
                        <input type="number" name="stock_quantity" class="form-control form-control-auratech" value="10" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small opacity-75">Asset Vector Image Filename</label>
                        <input type="text" name="image_url" class="form-control form-control-auratech" placeholder="default.jpg">
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" name="create_product" class="btn btn-auratech w-100">Deploy System Assets</button>
                    </div>
                    <div class="col-12">
                        <label class="form-label small opacity-75">System Specifications & Data Profile</label>
                        <textarea name="description" rows="2" class="form-control form-control-auratech"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <hr style="border-color: var(--border-glass);" class="my-5">

        <!-- Read and Management Data Matrix -->
        <h4 class="mb-3 text-start" style="color: var(--neon-cyan);"><i class="bi bi-hdd-network me-2"></i>Active Pipeline Grid</h4>
        <div class="table-responsive">
            <table class="table table-auratech">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Classification Block</th>
                        <th>Type</th>
                        <th>Cost Matrix</th>
                        <th>Stock Allocation</th>
                        <th>Execution Sequence</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" class="text-center opacity-50 py-4">No active cluster products initialized.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $prod): ?>
                            <tr>
                                <td>#<?php echo $prod['id']; ?></td>
                                <td>
                                    <span class="badge bg-secondary mb-1"><?php echo htmlspecialchars($prod['brand']); ?></span><br>
                                    <strong><?php echo htmlspecialchars($prod['name']); ?></strong>
                                    <div class="small opacity-50 text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($prod['description']); ?></div>
                                </td>
                                <td>
                                    <span class="text-info"><?php echo $prod['product_type']; ?></span>
                                </td>
                                <td>$<?php echo number_format($prod['price'], 2); ?></td>
                                <td><?php echo $prod['stock_quantity']; ?> units</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info me-2" onclick='openEditModal(<?php echo json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="admin_dashboard.php?delete=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirm hardware termination sequence?')">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Node Modal Setup -->
<div class="modal fade" id="editModal" static tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #11151f; border: 1px solid var(--neon-cyan); border-radius: 14px; color: var(--text-light);">
            <div class="modal-header border-0">
                <h5 class="modal-title" style="color: var(--neon-cyan);">Modify Structural Asset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin_dashboard.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-auratech" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand Matrix</label>
                        <input type="text" name="brand" id="edit_brand" class="form-control form-control-auratech" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Allocation Type</label>
                        <select name="product_type" id="edit_product_type" class="form-select form-select-auratech" required>
                            <option value="Laptop">Laptop</option>
                            <option value="Accessory">Accessory</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (USD)</label>
                        <input type="number" step="0.01" name="price" id="edit_price" class="form-control form-control-auratech" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock Units Allocation</label>
                        <input type="number" name="stock_quantity" id="edit_stock_quantity" class="form-control form-control-auratech" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asset Image Filename</label>
                        <input type="text" name="image_url" id="edit_image_url" class="form-control form-control-auratech">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Detailed Specification</label>
                        <textarea name="description" id="edit_description" rows="2" class="form-control form-control-auratech"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel Sequence</button>
                    <button type="submit" name="update_product" class="btn btn-auratech">Commit Adjustments</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    
    function openEditModal(product) {
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_brand').value = product.brand;
        document.getElementById('edit_product_type').value = product.product_type;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock_quantity').value = product.stock_quantity;
        document.getElementById('edit_image_url').value = product.image_url;
        document.getElementById('edit_description').value = product.description;
        editModal.show();
    }
</script>
</body>
</html>