<?php

// Get DATABASE_URL from Render
$DATABASE_URL = getenv("DATABASE_URL");

if (!$DATABASE_URL) {
    die("DATABASE_URL is not set");
}

// Parse URL
$url = parse_url($DATABASE_URL);

$host = $url["host"];
$port = $url["port"] ?? 5432;
$dbname = ltrim($url["path"], "/");
$user = $url["user"];
$pass = $url["pass"];

// PostgreSQL DSN (IMPORTANT)
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

} catch (PDOException $e) {
    die("Database Connection Failure: " . $e->getMessage());
}

?>