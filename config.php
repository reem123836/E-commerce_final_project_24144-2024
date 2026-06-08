<?php
// إعدادات الاتصال بقاعدة البيانات المهيأة لبيئة حاويات الدوكر (Docker Containers)
$db_host = 'db'; // اسم الحاوية الخاصة بقاعدة البيانات في Docker
$db_user = 'root';
$db_pass = 'root_password';
$db_name = 'laptop_agency_db';

// إنشاء الاتصال باستخدام مصفوفة mysqli
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// التحقق من نجاح الاتصال لمنع انهيار النظام الأساسي
if ($conn->connect_error) {
    die("Critical Error: Database Connection Failed Context -> " . $conn->connect_error);
}

// توحيد ترميز البيانات إلى UTF-8 لدعم اللغات والرموز بشكل صحيح
$conn->set_charset("utf8mb4");
?>