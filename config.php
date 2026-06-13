<?php
// إعدادات الاتصال الإلزامية لحاوية الدوكر
$host = 'db'; 
$user = 'root';
$password = 'root';
$dbname = 'laptop_agency_db'; 

// تفعيل نظام الأخطاء لمعرفة السبب لو حدث تعليق
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>