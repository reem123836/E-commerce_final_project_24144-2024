<?php
// config.php

// إعدادات الاتصال بقاعدة البيانات لبيئة AuraTech Containers
$db_host = 'db'; // اسم حاوية قاعدة البيانات في الـ Docker
$db_name = 'laptop_agency_db';
$db_user = 'root';
$db_pass = 'root_password';

// الاتصال باستخدام MySQLi (لأن صفحة التأكيد تستخدم bind_param)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// التحقق من نجاح عملية الاتصال بالـ Database Node
if ($conn->connect_error) {
    die("Database Connection Failure: " . $conn->connect_error);
}

// ضبط الترميز لاستقبال البيانات العربية بشكل صحيح دون مشاكل في الـ Pipeline
$conn->set_charset("utf8mb4");
?>