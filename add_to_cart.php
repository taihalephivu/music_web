<?php
session_start();
require_once 'config/database.php';

// API: Lấy giỏ hàng từ database
if (isset($_GET['action']) && $_GET['action'] === 'get_cart' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT c.instrument_id as id, i.name, i.price, i.image_url, c.quantity 
                            FROM cart c 
                            JOIN instruments i ON c.instrument_id = i.id 
                            WHERE c.user_id = ?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($cart);
    exit;
}

// API: Xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['action']) && $_GET['action'] === 'delete_item' && isset($_SESSION['user']['id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user']['id'];
    $item_id = intval($_GET['id']);
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('DELETE FROM cart WHERE user_id = ? AND instrument_id = ?');
    $ok = $stmt->execute([$user_id, $item_id]);
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    return;
}

// Thêm vào giỏ hàng (POST)
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
$db = new Database();
$conn = $db->getConnection();
// Kiểm tra sản phẩm đã có trong giỏ chưa
$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare('SELECT * FROM cart WHERE user_id = ? AND instrument_id = ?');
$stmt->execute([$user_id, $product_id]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
if ($cartItem) {
    // Tăng số lượng
    $stmt = $conn->prepare('UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND instrument_id = ?');
    $stmt->execute([$user_id, $product_id]);
} else {
    // Thêm mới
    $stmt = $conn->prepare('INSERT INTO cart (user_id, instrument_id, quantity) VALUES (?, ?, 1)');
    $stmt->execute([$user_id, $product_id]);
}
// Quay lại trang trước
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
header('Location: ' . $redirect);
exit; 