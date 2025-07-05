<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $id = intval($_POST['order_id']);
    if ($_POST['action'] === 'process') {
        $status = 'processing';
    } elseif ($_POST['action'] === 'cancel') {
        $status = 'cancelled';
    } elseif ($_POST['action'] === 'ship') {
        $status = 'shipped';
    } else {
        $status = '';
    }
    if ($status) {
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);
        echo '<script>window.location.href="index.php?page=orders";</script>';
        exit;
    }
}
if (isset($_POST['delete_order_id'])) {
    $id = intval($_POST['delete_order_id']);
    // Xóa chi tiết đơn hàng trước
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id=?");
    $stmt->execute([$id]);
    // Xóa đơn hàng
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->execute([$id]);
    echo '<script>window.location.href="index.php?page=orders";</script>';
    exit;
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
            <?php if($order['status'] === 'pending'): ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <button type="submit" name="action" value="process" class="btn btn-process">Duyệt</button>
                </form>
                <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <button type="submit" name="action" value="cancel" class="btn btn-cancel">Hủy</button>
                </form>
            <?php elseif($order['status'] === 'processing'): ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <button type="submit" name="action" value="ship" class="btn btn-process">Đã giao</button>
                </form>
            <?php else: ?>
                <?php if($order['status'] === 'cancelled' || $order['status'] === 'delivered'): ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                        <input type="hidden" name="delete_order_id" value="<?php echo $order['id']; ?>">
                        <button type="submit" class="btn btn-delete">Xóa</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a href="index.php">Quay lại trang admin</a> 