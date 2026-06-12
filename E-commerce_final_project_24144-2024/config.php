<?php
$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    die("DATABASE_URL is not set");
}

// استخدام دالة الدريفر المتاحة والمؤكدة في السيرفر
$conn = pg_connect($DATABASE_URL);

if (!$conn) {
    die("Database Connection Failed: " . pg_last_error());
}
?>