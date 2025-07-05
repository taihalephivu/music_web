<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    exit;
}

// API: Xóa nhiều sản phẩm khỏi giỏ hàng
if (isset($_GET['action']) && $_GET['action'] === 'delete_multiple' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $item_ids = $input['item_ids'] ?? [];
    
    if (empty($item_ids)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Không có sản phẩm nào được chọn']);
        exit;
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    $placeholders = str_repeat('?,', count($item_ids) - 1) . '?';
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND instrument_id IN ($placeholders)");
    $params = array_merge([$user_id], $item_ids);
    $ok = $stmt->execute($params);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
}

// API: Cập nhật số lượng sản phẩm
if (isset($_GET['action']) && $_GET['action'] === 'update_quantity' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = intval($input['product_id'] ?? 0);
    $change = intval($input['change'] ?? 0);
    
    if ($product_id <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
        exit;
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Lấy số lượng hiện tại
    $stmt = $conn->prepare('SELECT quantity FROM cart WHERE user_id = ? AND instrument_id = ?');
    $stmt->execute([$user_id, $product_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
        exit;
    }
    
    $new_quantity = $current['quantity'] + $change;
    
    if ($new_quantity <= 0) {
        // Xóa sản phẩm nếu số lượng <= 0
        $stmt = $conn->prepare('DELETE FROM cart WHERE user_id = ? AND instrument_id = ?');
        $ok = $stmt->execute([$user_id, $product_id]);
    } else {
        // Cập nhật số lượng
        $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND instrument_id = ?');
        $ok = $stmt->execute([$new_quantity, $user_id, $product_id]);
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
}

// Thêm vào giỏ hàng (POST)
if (!isset($_SESSION['user'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    header('Location: index.php');
    exit;
}

$product_id = intval($_POST['product_id']);
if ($product_id <= 0) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
        exit;
    }
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

// Lấy số lượng giỏ hàng mới
$stmt = $conn->prepare('SELECT SUM(quantity) as total FROM cart WHERE user_id = ?');
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$cartCount = $result['total'] ?? 0;

// Trả về response
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm vào giỏ hàng',
        'cartCount' => $cartCount
    ]);
    exit;
}

// Quay lại trang trước nếu không phải AJAX
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
header('Location: ' . $redirect);
exit; 