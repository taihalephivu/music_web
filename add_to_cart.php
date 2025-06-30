<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    header('Location: index.php');
    exit;
}
$product_id = intval($_POST['product_id']);
if ($product_id <= 0) {
    header('Location: index.php');
    exit;
}
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT * FROM instruments WHERE id = ?');
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('Location: index.php');
    exit;
}
// Thêm vào session cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        $item['quantity']++;
        $found = true;
        break;
    }
}
if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image_url' => $product['image_url'],
        'quantity' => 1
    ];
}
// Quay lại trang trước
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
header('Location: ' . $redirect);
exit; 