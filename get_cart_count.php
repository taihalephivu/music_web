<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

try {
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare('SELECT SUM(quantity) as total FROM cart WHERE user_id = ?');
    $stmt->execute([$_SESSION['user']['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $cartCount = $result['total'] ?? 0;
    
    echo json_encode([
        'success' => true,
        'count' => $cartCount
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi server',
        'count' => 0
    ]);
}
?> 