<?php

$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    die("DATABASE_URL is not set");
}

$url = parse_url($DATABASE_URL);

$host = $url["host"];
$port = $url["port"] ?? 5432;
$dbname = ltrim($url["path"], "/");
$user = $url["user"];
$pass = $url["pass"];

try {
    // تعديل هنا: إضافة sslmode=require لضمان قبول Render للاتصال أونلاين
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>