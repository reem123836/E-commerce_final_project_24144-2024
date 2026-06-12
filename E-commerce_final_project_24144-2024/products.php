<?php
session_start();
require_once "config.php";

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

/* =========================
   PRODUCT DETAILS MODE
========================= */
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
$view_details = false;

if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = $1";
    $result = pg_query_params($conn, $sql, [$product_id]);
    $product = pg_fetch_assoc($result);

    if ($product) {
        $view_details = true;
    }
}

/* =========================
   FILTERS (LIST MODE)
========================= */
$selected_category = $_GET['category'] ?? 'All';
$search_query = trim($_GET['search'] ?? '');

/* =========================
   BUILD SAFE QUERY
========================= */
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$param_index = 1;

/* Category filter */
if ($selected_category !== 'All') {
    $sql .= " AND product_type = $" . $param_index;
    $params[] = $selected_category;
    $param_index++;
}

/* Search filter */
if (!empty($search_query)) {
    $sql .= " AND (name LIKE $" . $param_index . " OR description LIKE $" . ($param_index + 1) . " OR brand LIKE $" . ($param_index + 2) . ")";
    $search_param = "%" . $search_query . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

/* Order */
$sql .= " ORDER BY id DESC";

/* =========================
   EXECUTE QUERY
========================= */
$stmt = pg_query_params($conn, $sql, $params);
$db_result = pg_fetch_all($stmt) ?: [];
?>