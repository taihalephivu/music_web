<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
// Kết nối database
$db = new Database();
$conn = $db->getConnection();
// Xử lý tìm kiếm
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
<h2>Quản lý người dùng</h2>
<form class="search-form" method="get">
    <input type="text" name="search" placeholder="Tìm kiếm username, email, SĐT..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Tìm kiếm</button>
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
            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">Sửa</a>
            <?php if($user['role'] !== 'admin'): ?>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa user này?');">Xóa</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a href="index.php">Quay lại trang admin</a> 