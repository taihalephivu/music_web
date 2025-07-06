<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// API: Đánh dấu đã đọc thông báo (đơn giản chỉ cần cờ)
if (isset($_GET['action']) && $_GET['action'] === 'mark_read' && isset($_SESSION['user']['id'])) {
    $_SESSION['notification_read'] = true;
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

// API: Lấy số lượng thông báo chưa đọc (nếu đã đọc thì trả về 0)
if (isset($_GET['action']) && $_GET['action'] === 'get_count' && isset($_SESSION['user']['id'])) {
    if (!empty($_SESSION['notification_read'])) {
        $total_count = 0;
    } else {
        $user_id = $_SESSION['user']['id'];
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare('SELECT COUNT(*) as count FROM orders 
                               WHERE user_id = ? 
                               AND status IN ("pending", "processing", "shipped", "delivered", "cancelled")');
        $stmt->execute([$user_id]);
        $total_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    header('Content-Type: application/json');
    echo json_encode(['count' => $total_count]);
    exit;
}

// API: Lấy danh sách thông báo (không cần phân biệt đã đọc)
if (isset($_GET['action']) && $_GET['action'] === 'get_notifications' && isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT o.id, o.total_amount, o.status, o.created_at
                           FROM orders o 
                           WHERE o.user_id = ? 
                           AND o.status IN ("pending", "processing", "shipped", "delivered", "cancelled")
                           ORDER BY o.created_at DESC LIMIT 10');
    $stmt->execute([$user_id]);
    $order_notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $notifications = [];
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

// Nếu không phải API call, trả về lỗi
if (!isset($_GET['action'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request';
    exit;
}
?> 