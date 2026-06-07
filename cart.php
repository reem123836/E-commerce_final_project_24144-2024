<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config.php";

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_accumulation = 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart | AuraTech Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --main-blue: #0A2540; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: var(--main-blue) !important; }
        .table-responsive { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">✨ AuraTech Agency</a>
            <a class="btn btn-outline-light btn-sm" href="index.php">Continue Shopping</a>
        </div>
    </nav>

    <main class="container py-5">
        <h2 class="fw-bold mb-4 border-start border-4 border-primary ps-3">Your Secured Cart Inventory</h2>

        <?php if (!empty($cart_items)): ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="table-responsive border">
                        <table class="table align-middle m-0">
                            <thead>
                                <tr>
                                    <th>Hardware Asset</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $id => $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_accumulation += $subtotal;
                                ?>
                                    <tr>
                                        <td><span class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></span></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <form action="cart_action.php" method="POST" class="d-flex align-items-center" style="max-width: 120px;">
                                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="action" value="update">
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-secondary">$<?php echo number_format($subtotal, 2); ?></td>
                                        <td>
                                            <form action="cart_action.php" method="POST">
                                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="action" value="remove">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card p-4 shadow-sm border-0 rounded-3 bg-white">
                        <h4 class="fw-bold text-dark mb-3">Order Financial Summary</h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fs-5">
                            <span class="text-muted">Total Due:</span>
                            <span class="fw-bold text-success">$<?php echo number_format($total_accumulation, 2); ?></span>
                        </div>
                        <p class="text-muted small mb-4">* Prices exclusive of local regional logistics fees within Kigali provinces.</p>
                        <a href="checkout.php" class="btn btn-dark w-100 py-2 rounded-pill fw-bold">Proceed to Secure Checkout</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-5 rounded-3">
                <h4 class="fw-bold m-0">Your AuraTech shopping repository is currently vacant.</h4>
                <p class="text-muted mt-2">Please return to the dynamic inventory asset menu to add certified computing devices.</p>
                <a href="index.php" class="btn btn-primary btn-sm mt-3 px-4 rounded-pill">Browse Hardware Catalog</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="text-center py-4 bg-dark text-white-50 mt-5">
        <div class="container">
            <small>Designed by Reem Osama - AuraTech Unified Session Architecture</small>
        </div>
    </footer>

</body>
</html>