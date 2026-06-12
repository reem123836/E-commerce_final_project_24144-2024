<?php
session_start();

/* =========================
   DATABASE CONNECTION (POSTGRES - RENDER)
========================= */
try {
    $pdo = new PDO(getenv("DATABASE_URL"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection Failure: " . $e->getMessage());
}

/* =========================
   CART VALIDATION
========================= */
$cart_items = $_SESSION['cart'] ?? [];

if (empty($cart_items)) {
    header("Location: index.php");
    exit();
}

/* =========================
   TOTAL CALCULATION
========================= */
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

/* =========================
   ORDER PROCESSING
========================= */
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['customer_name'];
    $email = $_POST['customer_email'];
    $phone = $_POST['customer_phone'];
    $address = $_POST['delivery_address'];
    $payment = $_POST['payment_method'];

    try {

        /* 1. INSERT ORDER */
        $stmt = $pdo->prepare("
            INSERT INTO orders
            (customer_name, customer_email, customer_phone, delivery_address, payment_method, total_amount)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $email,
            $phone,
            $address,
            $payment,
            $total_amount
        ]);

        $order_id = $pdo->lastInsertId();

        /* 2. INSERT ORDER ITEMS + UPDATE STOCK */
        foreach ($cart_items as $product_id => $item) {

            // insert order item
            $stmt_item = $pdo->prepare("
                INSERT INTO order_items
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            $stmt_item->execute([
                $order_id,
                $product_id,
                $item['quantity'],
                $item['price']
            ]);

            // update stock
            $stmt_stock = $pdo->prepare("
                UPDATE products
                SET stock_quantity = stock_quantity - ?
                WHERE id = ?
            ");

            $stmt_stock->execute([
                $item['quantity'],
                $product_id
            ]);
        }

        /* 3. CLEAR CART */
        unset($_SESSION['cart']);

        /* 4. REDIRECT */
        header("Location: confirmation.php?id=" . $order_id);
        exit();

    } catch (Exception $e) {
        $error_msg = "Database error: " . $e->getMessage();
    }
}
?>