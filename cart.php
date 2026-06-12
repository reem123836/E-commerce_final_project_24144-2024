<?php
session_start();

/* =========================
   SECURITY CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

/* =========================
   CART INIT
========================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'];
$subtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | AuraTech Agency</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4;
            --neon-pink: #ec4899;
        }

        body {
            background: linear-gradient(180deg, var(--cyber-bg-top), var(--cyber-bg-bottom));
            color: white;
            font-family: Arial;
        }

        .cart-cyber-card {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 25px;
            backdrop-filter: blur(15px);
        }

        .cyber-table {
            color: white;
        }

        .cyber-table th {
            color: var(--neon-cyan);
        }

        .cart-img-container {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
        }

        .cart-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .btn-neon {
            background: var(--neon-cyan);
            border: none;
            color: black;
            font-weight: bold;
        }

        .btn-remove {
            color: var(--neon-pink);
            background: transparent;
            border: none;
            font-size: 18px;
        }

        .summary-box {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="container my-5">

    <h2 class="mb-4">
        <i class="bi bi-cart3 me-2"></i> Shopping Cart
    </h2>

    <?php if (empty($cart_items)): ?>

        <div class="cart-cyber-card text-center p-5">
            <h4>Your cart is empty</h4>
            <a href="products.php" class="btn btn-neon mt-3">Go Shopping</a>
        </div>

    <?php else: ?>

        <div class="row">

            <!-- CART TABLE -->
            <div class="col-lg-8">

                <div class="cart-cyber-card">

                    <table class="table cyber-table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($cart_items as $id => $item): ?>

                            <?php
                                $total = $item['price'] * $item['quantity'];
                                $subtotal += $total;
                            ?>

                            <tr>

                                <!-- PRODUCT -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">

                                        <div class="cart-img-container">
                                            <img src="assets/images/products/<?php echo $item['image_url'] ?? 'default.jpg'; ?>">
                                        </div>

                                        <div>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($item['brand'] ?? 'AuraTech'); ?></small>
                                        </div>

                                    </div>
                                </td>

                                <!-- PRICE -->
                                <td>$<?php echo number_format($item['price'], 2); ?></td>

                                <!-- QTY -->
                                <td>
                                    <form action="cart_action.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="update">

                                        <input type="number"
                                               name="quantity"
                                               value="<?php echo $item['quantity']; ?>"
                                               min="1"
                                               class="form-control"
                                               onchange="this.form.submit()">
                                    </form>
                                </td>

                                <!-- TOTAL -->
                                <td style="color: var(--neon-cyan); font-weight: bold;">
                                    $<?php echo number_format($total, 2); ?>
                                </td>

                                <!-- REMOVE -->
                                <td>
                                    <form action="cart_action.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="remove">

                                        <button class="btn-remove">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>

                </div>

            </div>

            <!-- SUMMARY -->
            <div class="col-lg-4">

                <div class="summary-box">

                    <h4>Summary</h4>

                    <p>Subtotal: <strong>$<?php echo number_format($subtotal, 2); ?></strong></p>
                    <p>Delivery: FREE</p>

                    <hr>

                    <h5>Total: $<?php echo number_format($subtotal, 2); ?></h5>

                    <a href="checkout.php" class="btn btn-neon w-100 mt-3">
                        Checkout
                    </a>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

</body>
</html>