<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['cart_json'])) {
    echo '<div style="padding:2rem; color:#ff4757;">Dữ liệu không hợp lệ!</div>';
    exit;
}

$user_id = $_SESSION['user']['id'];
$cart = json_decode($_POST['cart_json'], true);
if (!$cart || !is_array($cart) || count($cart) === 0) {
    echo '<div style="padding:2rem; color:#ff4757;">Giỏ hàng trống!</div>';
    exit;
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$db = (new Database())->getConnection();

// Lấy thông tin user để lấy địa chỉ và số điện thoại
$stmt = $db->prepare('SELECT address, phone FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Lưu đơn hàng
$stmt = $db->prepare('INSERT INTO orders (user_id, total_amount, status, shipping_address, phone) VALUES (?, ?, ?, ?, ?)');
$address = $user['address'] ?? '';
$phone = $user['phone'] ?? '';
$stmt->execute([$user_id, $total, 'pending', $address, $phone]);
$order_id = $db->lastInsertId();

// Lưu từng sản phẩm
$stmt2 = $db->prepare('INSERT INTO order_items (order_id, instrument_id, quantity, price) VALUES (?, ?, ?, ?)');
foreach ($cart as $item) {
    $stmt2->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
}

// Xóa giỏ hàng sau khi đặt hàng thành công
unset($_SESSION['cart']);

// Sau khi lưu xong, chuyển về lịch sử giao dịch
header('Location: history.php?success=1');
exit; 