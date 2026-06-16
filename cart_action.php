<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id > 0 && !empty($action)) {
    
  
    if ($action === 'add') {
 
        $sql = "SELECT name, price, stock_quantity, brand, image_url FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();

        if ($product) {
            
            if (isset($_SESSION['cart'][$product_id])) {
                $new_qty = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
                
                if ($new_qty <= $product['stock_quantity']) {
                    $_SESSION['cart'][$product_id]['quantity'] = $new_qty;
                } else {
                    $_SESSION['cart'][$product_id]['quantity'] = $product['stock_quantity'];
                }
            } else {
                
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'brand' => $product['brand'] ?? 'AuraTech Product',
                    'image_url' => $product['image_url'] ?? 'default.jpg',
                    'quantity' => min($quantity, $product['stock_quantity'])
                ];
            }
        }
    }
    
  
    if ($action === 'update') {
        $sql = "SELECT stock_quantity FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();

        if ($product && isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0 && $quantity <= $product['stock_quantity']) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } elseif ($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]); 
            }
        }
    }
    
   
    if ($action === 'remove') {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}


header("Location: cart.php");
exit();
?>