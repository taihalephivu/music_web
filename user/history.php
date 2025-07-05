<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$db = (new Database())->getConnection();

// Lấy danh sách đơn hàng
$stmt = $db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hàm chuyển trạng thái sang tiếng Việt
function statusLabel($status) {
    switch($status) {
        case 'pending': return 'Chờ xác nhận';
        case 'processing': return 'Đang giao';
        case 'shipped': return 'Đã giao';
        case 'delivered': return 'Đã nhận';
        case 'cancelled': return 'Đã hủy';
        default: return $status;
    }
}

function statusColor($status) {
    switch($status) {
        case 'pending': return '#ffd600';
        case 'processing': return '#2196f3';
        case 'shipped': return '#4caf50';
        case 'delivered': return '#00bfae';
        case 'cancelled': return '#ff5252';
        default: return '#fff';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng - Music Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #fff; 
            color: #111; 
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .history-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }
        .history-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .history-header h2 {
            color: #2196f3;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .history-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #2196f3;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .success-msg {
            background: #4caf50;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .order-block {
            background: #f4f6fb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .order-id {
            font-weight: 600;
            color: #2196f3;
            font-size: 1.1rem;
        }
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #111;
        }
        .order-items {
            margin: 16px 0;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-name {
            color: #111;
            font-weight: 500;
        }
        .item-details {
            color: #666;
            font-size: 0.9rem;
        }
        .order-total {
            text-align: right;
            font-weight: 700;
            color: #4caf50;
            font-size: 1.1rem;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e0e0e0;
        }
        .no-order {
            text-align: center;
            color: #666;
            padding: 40px;
            font-size: 1.1rem;
        }
        .no-order i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 16px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="history-container">
        <a href="../index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>
        
        <div class="history-header">
            <h2><i class="fas fa-history"></i> Lịch sử mua hàng</h2>
            <p>Xem lại các đơn hàng đã đặt</p>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i> Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận.
            </div>
        <?php endif; ?>
        
        <?php if (empty($orders)): ?>
            <div class="no-order">
                <i class="fas fa-shopping-bag"></i>
                <p>Bạn chưa có đơn hàng nào.</p>
                <p>Hãy mua sắm để xem lịch sử đơn hàng tại đây!</p>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-block">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Đơn hàng #<?php echo $order['id']; ?></div>
                            <div class="order-date">
                                <i class="fas fa-calendar"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                        <span class="order-status" style="background: <?php echo statusColor($order['status']); ?>">
                            <?php echo statusLabel($order['status']); ?>
                        </span>
                    </div>
                    
                    <div class="order-items">
                        <?php
                        $stmt2 = $db->prepare('SELECT oi.*, i.name FROM order_items oi LEFT JOIN instruments i ON oi.instrument_id = i.id WHERE oi.order_id = ?');
                        $stmt2->execute([$order['id']]);
                        $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($items as $item): ?>
                            <div class="order-item">
                                <div>
                                    <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="item-details">
                                        Số lượng: <?php echo $item['quantity']; ?> | 
                                        Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?>₫
                                    </div>
                                </div>
                                <div class="item-total">
                                    <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-total">
                        Tổng tiền: <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html> 