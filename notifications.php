<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// API: Lấy thông báo cho user
if (isset($_GET['action']) && $_GET['action'] === 'get_notifications' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $db = new Database();
    $conn = $db->getConnection();
    
    // Kiểm tra thời gian đã đọc thông báo
    $read_at = $_SESSION['notifications_read_at'] ?? '1970-01-01 00:00:00';
    
    // Lấy thông báo đơn hàng (các trạng thái: pending, processing, shipped, delivered, cancelled)
    $stmt = $conn->prepare('SELECT o.id, o.total_amount, o.status, o.created_at
                           FROM orders o 
                           WHERE o.user_id = ? 
                           AND o.created_at > ?
                           AND o.status IN ("pending", "processing", "shipped", "delivered", "cancelled")
                           ORDER BY o.created_at DESC LIMIT 10');
    $stmt->execute([$user_id, $read_at]);
    $order_notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $notifications = [];
    
    // Thêm thông báo đơn hàng
    foreach ($order_notifications as $order) {
        $status_text = getStatusText($order['status']);
        $notifications[] = [
            'type' => 'order',
            'title' => 'Đơn hàng #' . $order['id'] . ' - ' . $status_text,
            'message' => 'Đơn hàng trị giá ' . number_format($order['total_amount'], 0, ',', '.') . '₫ ' . strtolower($status_text),
            'time' => getTimeAgo($order['created_at']),
            'order_id' => $order['id'],
            'status' => $order['status']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($notifications);
    exit;
}

// API: Đánh dấu thông báo đã đọc
if (isset($_GET['action']) && $_GET['action'] === 'mark_read' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $db = new Database();
    $conn = $db->getConnection();
    
    // Lưu thời gian đánh dấu đã đọc vào session
    $_SESSION['notifications_read_at'] = date('Y-m-d H:i:s');
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

// Hàm tính thời gian trước
function getTimeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' phút trước';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' giờ trước';
    } else {
        $days = floor($diff / 86400);
        return $days . ' ngày trước';
    }
}

// Hàm lấy text trạng thái
function getStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'Chờ xác nhận';
        case 'processing':
            return 'Đang xử lý';
        case 'shipped':
            return 'Đang giao hàng';
        case 'delivered':
            return 'Đã giao hàng';
        case 'cancelled':
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}

// API: Lấy số lượng thông báo chưa đọc
if (isset($_GET['action']) && $_GET['action'] === 'get_count' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $db = new Database();
    $conn = $db->getConnection();
    
    // Kiểm tra thời gian đã đọc thông báo
    $read_at = $_SESSION['notifications_read_at'] ?? '1970-01-01 00:00:00';
    
    // Đếm đơn hàng có cập nhật trạng thái mới
    $stmt = $conn->prepare('SELECT COUNT(*) as count FROM orders 
                           WHERE user_id = ? 
                           AND created_at > ?
                           AND status IN ("pending", "processing", "shipped", "delivered", "cancelled")');
    $stmt->execute([$user_id, $read_at]);
    $total_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    header('Content-Type: application/json');
    echo json_encode(['count' => $total_count]);
    exit;
}

// Nếu không phải API call, trả về lỗi
if (!isset($_GET['action'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request';
    exit;
}
?> 