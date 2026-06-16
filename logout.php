<?php
session_start();

$temp_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;


session_unset();
session_destroy();


session_start();


if ($temp_cart !== null) {
    $_SESSION['cart'] = $temp_cart;
}

header("Location: auth.php");
exit;
?>