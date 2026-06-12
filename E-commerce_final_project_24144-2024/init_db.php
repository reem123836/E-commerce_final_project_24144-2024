<?php
require_once "config.php";

$sql = file_get_contents("init.sql");

try {
    $conn->exec($sql);
    echo "Database initialized successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>