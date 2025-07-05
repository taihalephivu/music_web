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

// Xử lý cập nhật số lượng sản phẩm trong giỏ hàng (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'update_quantity' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    $change = intval($input['change'] ?? 0);
    $user_id = $_SESSION['user']['id'] ?? 0;
    $ok = false;
    if ($id > 0 && $user_id) {
        // Kiểm tra sản phẩm có trong giỏ không
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM cart WHERE user_id = ? AND instrument_id = ?');
        $stmt->execute([$user_id, $id]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cartItem) {
            $newQty = $cartItem['quantity'] + $change;
            if ($newQty > 0) {
                $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND instrument_id = ?');
                $ok = $stmt->execute([$newQty, $user_id, $id]);
            } else {
                $stmt = $conn->prepare('DELETE FROM cart WHERE user_id = ? AND instrument_id = ?');
                $ok = $stmt->execute([$user_id, $id]);
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
}

// Xử lý cập nhật số lượng combo (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'update_combo_quantity' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $combo_id = $input['combo_id'] ?? '';
    $change = intval($input['change'] ?? 0);
    $user_id = $_SESSION['user']['id'] ?? 0;
    $ok = false;
    
    if ($combo_id && $user_id && isset($_SESSION['combo_cart'][$user_id][$combo_id])) {
        $newQty = $_SESSION['combo_cart'][$user_id][$combo_id]['quantity'] + $change;
        if ($newQty > 0) {
            $_SESSION['combo_cart'][$user_id][$combo_id]['quantity'] = $newQty;
            $ok = true;
        } else {
            unset($_SESSION['combo_cart'][$user_id][$combo_id]);
            $ok = true;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
}

// Xử lý xóa combo khỏi session (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'delete_combo_item' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $combo_id = $_GET['id'] ?? '';
    $user_id = $_SESSION['user']['id'] ?? 0;
    $ok = false;
    
    if ($combo_id && $user_id && isset($_SESSION['combo_cart'][$user_id][$combo_id])) {
        unset($_SESSION['combo_cart'][$user_id][$combo_id]);
        $ok = true;
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
}

// Xử lý xóa combo khỏi session (AJAX) - phiên bản cũ
if (isset($_GET['action']) && $_GET['action'] === 'delete_combo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $combo_id = $input['combo_id'] ?? '';
    $user_id = $_SESSION['user']['id'] ?? 0;
    $ok = false;
    if ($combo_id && $user_id && isset($_SESSION['combo_cart'][$user_id][$combo_id])) {
        unset($_SESSION['combo_cart'][$user_id][$combo_id]);
        $ok = true;
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => $ok]);
    exit;
} 

// Thêm vào giỏ hàng (POST) - chỉ kiểm tra product_id khi thêm sản phẩm thường
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
// Đảm bảo chỉ thêm combo khi có combo_id, combo_name, combo_price trong POST
if (isset($_POST['combo_id']) && isset($_POST['combo_name']) && isset($_POST['combo_price'])) {
    $combo_id = $_POST['combo_id'];
    $combo_name = $_POST['combo_name'];
    $combo_price = floatval($_POST['combo_price']);
    $user_id = $_SESSION['user']['id'];
    if (!isset($_SESSION['combo_cart'][$user_id])) {
        $_SESSION['combo_cart'][$user_id] = [];
    }
    // Nếu combo đã có thì tăng số lượng
    if (isset($_SESSION['combo_cart'][$user_id][$combo_id])) {
        $_SESSION['combo_cart'][$user_id][$combo_id]['quantity'] += 1;
    } else {
        $_SESSION['combo_cart'][$user_id][$combo_id] = [
            'name' => $combo_name,
            'price' => $combo_price,
            'quantity' => 1
        ];
    }
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
    header('Location: ' . $redirect);
    exit;
}
// Thêm sản phẩm thường vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $sale_price = isset($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
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
        // Nếu có giá sale, lưu vào session để xử lý khi hiển thị giỏ
        if ($sale_price) {
            $_SESSION['cart_sale'][$user_id][$product_id] = $sale_price;
        }
    } else {
        // Thêm mới
        $stmt = $conn->prepare('INSERT INTO cart (user_id, instrument_id, quantity) VALUES (?, ?, 1)');
        $stmt->execute([$user_id, $product_id]);
        // Nếu có giá sale, lưu vào session để xử lý khi hiển thị giỏ
        if ($sale_price) {
            $_SESSION['cart_sale'][$user_id][$product_id] = $sale_price;
        }
    }
    // Quay lại trang trước
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
    header('Location: ' . $redirect);
    exit;
}
// Nếu không khớp bất kỳ API/action nào thì về trang chủ
header('Location: index.php');
exit; 