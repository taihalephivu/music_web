<?php
require_once '../config/database.php';
// Kết nối DB
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách đơn hàng, join với users để lấy username
$sql = "SELECT o.id, u.username, o.phone, o.shipping_address, o.created_at, o.total_amount, o.status FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
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
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #fff; color: #111; }
        .admin-container { max-width: 1200px; margin: 40px auto; background: #fff; border-radius: 18px; padding: 32px; border: 1px solid #e0e0e0; }
        h2 { color: #2196f3; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #fff; }
        th, td { padding: 12px 8px; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background: #f4f6fb; color: #111; }
        tr:hover { background: #f4f6fb; }
        .status-label { font-weight: bold; padding: 6px 14px; border-radius: 8px; display: inline-block; }
        .btn { padding: 7px 16px; border-radius: 6px; border: none; background: #2196f3; color: #fff; cursor: pointer; margin-right: 4px; text-decoration: none; }
        .btn-cancel { background: #ff5252; }
        .btn-detail { background: #ffd600; color: #111; }
        .btn-process { background: #4caf50; }
        .btn-disabled { background: #888; cursor: not-allowed; }
        a { color: #2196f3; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Quản lý đơn hàng</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td><?php echo htmlspecialchars($order['phone']); ?></td>
                <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>₫</td>
                <td><span class="status-label" style="background:<?php echo statusColor($order['status']); ?>;color:#222;">
                    <?php echo statusLabel($order['status']); ?>
                </span></td>
                <td>
                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-detail">Chi tiết</a>
                    <?php if($order['status'] === 'pending'): ?>
                        <a href="process_order.php?id=<?php echo $order['id']; ?>" class="btn btn-process">Duyệt</a>
                        <a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc muốn hủy đơn này?');">Hủy</a>
                    <?php elseif($order['status'] === 'processing'): ?>
                        <a href="ship_order.php?id=<?php echo $order['id']; ?>" class="btn btn-process">Đã giao</a>
                    <?php else: ?>
                        <span class="btn btn-disabled">---</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a href="index.php">Quay lại trang admin</a>
    </div>
</body>
</html> 