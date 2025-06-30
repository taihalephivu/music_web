<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
// Kết nối DB
$db = new Database();
$conn = $db->getConnection();
$sql = "SELECT o.id, u.username, o.phone, o.shipping_address, o.created_at, o.total_amount, o.status FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
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