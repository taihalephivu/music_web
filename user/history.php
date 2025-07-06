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
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách đơn hàng
$stmt = $conn->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hàm chuyển trạng thái sang tiếng Việt
function statusLabel($status) {
    switch($status) {
        case 'pending': return 'Chờ xác nhận';
        case 'processing': return 'Đang xử lý';
        case 'shipped': return 'Đang giao hàng';
        case 'delivered': return 'Đã giao hàng';
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
            background: #f6f8fa;
        }
        .history-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 0 60px 0;
        }
        .history-wrapper {
            width: 100%;
            max-width: 1100px;
            background: none;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .history-title-block {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 24px 32px 18px 32px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }
        .history-title-block h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2196f3;
            margin: 0;
        }
        .history-content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 32px;
        }
        .history-orders {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 24px 0 0 0;
            min-height: 300px;
        }
        .history-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0 32px 12px 32px;
            border-bottom: 1px solid #f1f1f1;
        }
        .filter-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-select {
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            background: #fff;
            cursor: pointer;
        }
        .history-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .history-item-row {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 32px;
            border-bottom: 1px solid #f1f1f1;
            background: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .history-item-row:hover {
            background: #f8f9fa;
        }
        .history-item-row:last-child {
            border-bottom: none;
        }
        .order-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .order-info {
            flex: 1;
            min-width: 0;
        }
        .order-id {
            font-weight: 600;
            color: #2196f3;
            font-size: 1.08rem;
            margin-bottom: 2px;
        }
        .order-date {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .order-items-count {
            color: #888;
            font-size: 0.85rem;
        }
        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #111;
            min-width: 100px;
            text-align: center;
        }
        .order-total {
            font-weight: 700;
            color: #2196f3;
            font-size: 1.1rem;
            min-width: 120px;
            text-align: right;
        }
        .order-details {
            background: #ff5252;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .order-details:hover {
            background: #ff3742;
        }
        .empty-history {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-history i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .empty-history h2 {
            color: #666;
            margin-bottom: 16px;
        }
        .empty-history p {
            color: #999;
            margin-bottom: 24px;
        }
        .history-summary {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 32px 28px 28px 28px;
            height: fit-content;
            position: sticky;
            top: 110px;
            min-width: 0;
        }
        .summary-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1rem;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding-top: 16px;
            border-top: 2px solid #f1f1f1;
            font-size: 1.2rem;
            font-weight: 700;
            color: #2196f3;
        }
        .continue-shopping {
            width: 100%;
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .continue-shopping:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        .success-msg {
            background: #4caf50;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        @media (max-width: 900px) {
            .history-content-grid {
                grid-template-columns: 1fr;
            }
            .history-summary {
                position: static;
                margin-top: 32px;
            }
        }
        @media (max-width: 600px) {
            .history-title-block {
                padding: 18px 10px 12px 10px;
                font-size: 1.2rem;
            }
            .history-orders {
                padding: 12px 0 0 0;
            }
            .history-item-row {
                padding: 12px 8px;
                gap: 8px;
            }
            .history-summary {
                padding: 18px 8px 18px 8px;
            }
        }
    </style>
</head>
<body>
    <main class="history-main">
        <div class="history-wrapper">
            <a href="../index.php" style="color: #2196f3; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; font-size: 1.08rem;">
                <i class="fas fa-arrow-left"></i> Quay lại trang chủ
            </a>
            <div class="history-title-block">
                <i class="fas fa-history" style="font-size:2.1rem;color:#2196f3;"></i>
                <h1>Lịch sử mua hàng</h1>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success-msg">
                    <i class="fas fa-check-circle"></i>
                    Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận.
                </div>
            <?php endif; ?>
            
            <?php if (empty($orders)): ?>
                <div class="empty-history">
                    <i class="fas fa-shopping-bag"></i>
                    <h2>Chưa có đơn hàng</h2>
                    <p>Bạn chưa có đơn hàng nào. Hãy mua sắm để xem lịch sử đơn hàng tại đây!</p>
                    <a href="../index.php" class="btn" style="background: #2196f3; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            <?php else: ?>
            <div class="history-content-grid">
                <div class="history-orders">
                    <div class="history-controls">
                        <div class="filter-container">
                            <label for="statusFilter" style="font-weight: 600; color: #333;">Lọc theo trạng thái:</label>
                            <select id="statusFilter" class="filter-select" onchange="filterOrders()">
                                <option value="">Tất cả</option>
                                <option value="pending">Chờ xác nhận</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="shipped">Đang giao hàng</option>
                                <option value="delivered">Đã giao hàng</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="history-list">
                        <?php 
                        $totalOrders = 0;
                        $totalAmount = 0;
                        foreach ($orders as $order): 
                            $totalOrders++;
                            $totalAmount += $order['total_amount'];
                            
                            // Lấy thông tin sản phẩm đầu tiên để hiển thị ảnh và id
                            $stmt2 = $conn->prepare('SELECT i.name, i.image_url, i.id as instrument_id FROM order_items oi 
                                                   LEFT JOIN instruments i ON oi.instrument_id = i.id 
                                                   WHERE oi.order_id = ? LIMIT 1');
                            $stmt2->execute([$order['id']]);
                            $firstItem = $stmt2->fetch(PDO::FETCH_ASSOC);
                            
                            // Đếm số lượng sản phẩm trong đơn hàng
                            $stmt3 = $conn->prepare('SELECT COUNT(*) as count FROM order_items WHERE order_id = ?');
                            $stmt3->execute([$order['id']]);
                            $itemCount = $stmt3->fetch(PDO::FETCH_ASSOC)['count'];
                        ?>
                        <div class="history-item-row" data-status="<?php echo $order['status']; ?>" onclick="window.location.href='../product_detail.php?id=<?php echo $firstItem['instrument_id']; ?>'">
                            <img src="<?php echo $firstItem['image_url'] ?: '../assets/images/default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($firstItem['name']); ?>" 
                                 class="order-image"
                                 onerror="this.src='../assets/images/default.jpg'">
                            <div class="order-info">
                                <div class="order-id">Đơn hàng #<?php echo $order['id']; ?></div>
                                <div class="order-date">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </div>
                                <div class="order-items-count">
                                    <i class="fas fa-box"></i> 
                                    <?php echo $itemCount; ?> sản phẩm
                                </div>
                            </div>
                            <span class="order-status" style="background: <?php echo statusColor($order['status']); ?>">
                                <?php echo statusLabel($order['status']); ?>
                            </span>
                            <div class="order-total">
                                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫
                            </div>
                            <button class="order-details" onclick="event.stopPropagation(); window.open('../product_detail.php?id=<?php echo $firstItem['instrument_id']; ?>','_blank');">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="history-summary">
                    <div class="summary-title">Tóm tắt</div>
                    <div class="summary-item">
                        <span>Tổng đơn hàng:</span>
                        <span><?php echo $totalOrders; ?> đơn</span>
                    </div>
                    <div class="summary-item">
                        <span>Tổng chi tiêu:</span>
                        <span><?php echo number_format($totalAmount, 0, ',', '.'); ?>₫</span>
                    </div>
                    <div class="summary-item">
                        <span>Đơn hàng gần nhất:</span>
                        <span><?php echo date('d/m/Y', strtotime($orders[0]['created_at'])); ?></span>
                    </div>
                    <div class="summary-total">
                        <span>Trung bình/đơn:</span>
                        <span><?php echo number_format($totalAmount / $totalOrders, 0, ',', '.'); ?>₫</span>
                    </div>
                    <a href="../index.php" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        function filterOrders() {
            const filter = document.getElementById('statusFilter').value;
            const orderRows = document.querySelectorAll('.history-item-row');
            
            orderRows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (filter === '' || status === filter) {
                    row.style.display = 'flex';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function viewOrderDetails(orderId) {
            // Có thể mở modal hoặc chuyển trang để xem chi tiết
            window.location.href = 'order_details.php?order_id=' + orderId;
        }
    </script>
</body>
</html> 