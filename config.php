<?php

$host = 'sql102.infinityfree.comdb'; 
$user = 'if0_42174531';
$password = 'reemosama123';
$dbname = 'if0_42174531_laptop_agency_db'; 


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>