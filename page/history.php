<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
$db = (new Database())->getConnection();
// Lấy danh sách đơn hàng
$stmt = $db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử giao dịch</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #f4f6fb; }
        .history-container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 2.5rem; }
        .history-title { text-align: center; color: #4f8cff; margin-bottom: 2rem; font-size: 2rem; font-weight: 700; }
        .order-block { border-bottom: 1px solid #e9ecef; margin-bottom: 2rem; padding-bottom: 1.5rem; }
        .order-block:last-child { border-bottom: none; }
        .order-info { margin-bottom: 1rem; color: #333; }
        .order-status { font-weight: 600; color: #51cf66; }
        .order-items { margin-left: 1.5rem; }
        .order-item { margin-bottom: 0.5rem; color: #444; }
        .order-total { font-weight: 700; color: #ff4757; }
        .no-order { text-align: center; color: #888; margin-top: 2rem; }
        .success-msg { color: #27ae60; text-align: center; margin-bottom: 1.5rem; font-weight: 600; }
        a.back-link { display: inline-block; margin-bottom: 1.5rem; color: #4f8cff; text-decoration: none; }
        a.back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="history-container">
        <a href="../index.php" class="back-link">&larr; Quay về trang chủ</a>
        <div class="history-title">Lịch sử giao dịch</div>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-msg">Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận.</div>
        <?php endif; ?>
        <?php if (!$orders): ?>
            <div class="no-order">Bạn chưa có giao dịch nào.</div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-block">
                    <div class="order-info">
                        <strong>Mã đơn hàng:</strong> #<?php echo $order['id']; ?> &nbsp; | &nbsp;
                        <strong>Ngày:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?> &nbsp; | &nbsp;
                        <span class="order-status">Trạng thái: <?php echo ucfirst($order['status']); ?></span>
                    </div>
                    <div class="order-items">
                        <?php
                        $stmt2 = $db->prepare('SELECT oi.*, i.name FROM order_items oi LEFT JOIN instruments i ON oi.instrument_id = i.id WHERE oi.order_id = ?');
                        $stmt2->execute([$order['id']]);
                        $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($items as $item): ?>
                            <div class="order-item">
                                <span><?php echo htmlspecialchars($item['name']); ?></span> -
                                SL: <?php echo $item['quantity']; ?> -
                                Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">Tổng tiền: <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html> 