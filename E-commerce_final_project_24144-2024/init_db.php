<?php
require_once "config.php";

try {

    // 1. نحذف كل الجداول القديمة (RESET)
    $conn->exec("
        DROP TABLE IF EXISTS order_items CASCADE;
        DROP TABLE IF EXISTS orders CASCADE;
        DROP TABLE IF EXISTS products CASCADE;
        DROP TABLE IF EXISTS users CASCADE;
    ");

    // 2. نعيد بناء الداتابيز من الملف
    $sql = file_get_contents("init.sql");
    $conn->exec($sql);

    echo "✅ Database reset and recreated successfully!";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>