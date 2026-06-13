<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

/* =========================
   FETCH ORDER (PDO FIX)
========================= */
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

/* If order not found */
if (!$order) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | AuraTech Agency</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 50%, #0f172a 100%);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .auth-bar-layout {
            width: 100%;
            max-width: 750px;
            background: rgba(255,255,255,0.02);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.05);
            border-left: 5px solid var(--neon-cyan);
            border-radius: 14px;
            padding: 50px;
            text-align: center;
        }

        .luminous-icon {
            font-size: 4rem;
            color: var(--neon-cyan);
        }

        .metrics-panel {
            background: rgba(11,15,25,0.6);
            border-radius: 10px;
            padding: 20px;
            text-align: left;
        }

        .metric-row {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .metric-row:last-child {
            border-bottom: none;
        }

        .btn-auratech-submit {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 30px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-auratech-submit:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

<div class="auth-bar-layout">

    <i class="bi bi-check-circle-fill luminous-icon"></i>

    <h1 class="fw-bold mt-3 mb-3"
        style="background:linear-gradient(90deg,#fff,var(--neon-cyan));
        -webkit-background-clip:text;-webkit-text-fill-color:transparent;">
        Order Successful
    </h1>

    <p class="text-light opacity-75 mb-4">
        Your order has been successfully processed.
    </p>

    <div class="metrics-panel">

        <div class="metric-row">
            <strong>Order ID:</strong>
            <span class="float-end">#AUT-000<?= htmlspecialchars($order['id']) ?></span>
        </div>

        <div class="metric-row">
            <strong>Customer:</strong>
            <span class="float-end"><?= htmlspecialchars($order['customer_name']) ?></span>
        </div>

        <div class="metric-row">
            <strong>Payment Method:</strong>
            <span class="float-end text-info">
                <?= htmlspecialchars($order['payment_method']) ?>
            </span>
        </div>

        <div class="metric-row">
            <strong>Total Paid:</strong>
            <span class="float-end text-info fw-bold">
                $<?= number_format($order['total_amount'], 2) ?>
            </span>
        </div>

    </div>

    <p class="mt-4 text-light opacity-75">
        We will contact you on <strong><?= htmlspecialchars($order['customer_phone']) ?></strong>
        for delivery details.
    </p>

    <a href="index.php" class="btn-auratech-submit mt-3">
        Return Home
    </a>

</div>

</body>
</html>