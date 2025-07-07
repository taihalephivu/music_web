<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

//xóa user
$delete_msg = '';
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);
    if ($id > 0) {
        // Kiểm tra user tồn tại không
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($user['role'] === 'admin') {
                $delete_msg = 'Không thể xóa tài khoản admin!';
            } elseif ($id == $_SESSION['admin']['id']) {
                $delete_msg = 'Không thể xóa tài khoản đang đăng nhập!';
            } else {
                // Xóa các đơn hàng của user 
                $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = ?)");
                $stmt->execute([$id]);
                
                $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Xóa đánh giá
                $stmt = $conn->prepare("DELETE FROM service_reviews WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Xóa giỏ hàng
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Xóa user
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $result = $stmt->execute([$id]);
                
                if ($result) {
                    $delete_msg = 'success';
                } else {
                    $delete_msg = 'Lỗi khi xóa user!';
                }
            }
        } else {
            $delete_msg = 'Không tìm thấy user!';
        }
    }
}

//cập nhật user
$update_msg = '';
if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE users SET email=?, phone=?, address=? WHERE id=?");
        $result = $stmt->execute([$email, $phone, $address, $id]);
        if ($result) {
            $update_msg = 'Cập nhật thành công!';
        } else {
            $update_msg = 'Lỗi khi cập nhật!';
        }
    }
}

// tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$params = [];
if ($search !== '') {
    $where = "WHERE username LIKE :kw OR email LIKE :kw OR phone LIKE :kw";
    $params[':kw'] = "%$search%";
}

$sql = "SELECT id, username, email, phone, address, role FROM users $where ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .msg-success { background: #4caf50; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .msg-error { background: #f44336; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .edit-form { display: none; background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .edit-form input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        .btn-save { background: #4caf50; color: white; padding: 5px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-cancel { background: #f44336; color: white; padding: 5px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px; }
    </style>
</head>
<body>
    <h2>Quản lý người dùng</h2>
    
    <?php if ($delete_msg === 'success'): ?>
        <div class="msg-success">
            <i class="fas fa-check-circle"></i> Đã xóa user thành công!
        </div>
    <?php elseif ($delete_msg): ?>
        <div class="msg-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $delete_msg; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($update_msg): ?>
        <div class="msg-success">
            <i class="fas fa-check-circle"></i> <?php echo $update_msg; ?>
        </div>
    <?php endif; ?>
    
    <form class="search-form" method="get" action="index.php" style="margin-bottom:18px;display:flex;gap:8px;max-width:400px;">
        <input type="hidden" name="page" value="users">
        <input type="text" name="search" placeholder="Tìm kiếm username, email, SĐT..." value="<?php echo htmlspecialchars($search); ?>" style="flex:1;padding:8px 10px;border-radius:6px;border:1px solid #ccc;">
        <button type="submit" style="padding:8px 18px;border-radius:6px;background:#2196f3;color:#fff;border:none;">Tìm kiếm</button>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Quyền</th>
            <th>Hành động</th>
        </tr>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td><?php echo htmlspecialchars($user['address']); ?></td>
            <td class="<?php echo $user['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                <?php echo $user['role'] === 'admin' ? 'Admin' : 'User'; ?>
            </td>
            <td>
                <button onclick="showEditForm(<?php echo $user['id']; ?>)" class="btn btn-edit">Sửa</button>
                <?php if($user['role'] !== 'admin'): ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa user này?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-delete">Xóa</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <tr id="edit-form-<?php echo $user['id']; ?>" class="edit-form">
            <td colspan="7">
                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; align-items: end;">
                        <div>
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div>
                            <label>Số điện thoại:</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                        <div>
                            <label>Địa chỉ:</label>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                        </div>
                        <div>
                            <button type="submit" class="btn-save">Lưu</button>
                            <button type="button" class="btn-cancel" onclick="hideEditForm(<?php echo $user['id']; ?>)">Hủy</button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <br>
    <a href="index.php">Quay lại trang admin</a>

    <script>
        function showEditForm(userId) {
            document.getElementById('edit-form-' + userId).style.display = 'table-row';
        }
        
        function hideEditForm(userId) {
            document.getElementById('edit-form-' + userId).style.display = 'none';
        }
    </script>
</body>
</html> 