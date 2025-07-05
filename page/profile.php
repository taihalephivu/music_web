<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    echo '<div style="padding:2rem; text-align:center; color:#ff4757;">Bạn chưa đăng nhập!</div>';
    exit;
}

$user_id = $_SESSION['user']['id'];
$db = (new Database())->getConnection();
$stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo '<div style="padding:2rem; text-align:center; color:#ff4757;">Không tìm thấy thông tin người dùng!</div>';
    exit;
}
?>
<div class="cart-content" style="max-width: 400px;">
    <div class="cart-header">
        <h3 class="cart-title">Hồ sơ cá nhân</h3>
        <button class="close-cart" onclick="this.closest('.cart-modal').remove()">&times;</button>
    </div>
    <div style="padding: 1rem 0; text-align: left;">
        <div style='text-align:center;'>
            <i class="fas fa-user-circle" style="font-size: 3rem; color: #4f8cff;"></i>
        </div>
        <div style="margin-top:1rem;">
            <strong>Họ tên:</strong> <?php echo htmlspecialchars($user['full_name']); ?><br>
            <strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
            <strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user['phone']); ?><br>
            <strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user['address']); ?><br>
        </div>
    </div>
</div> 