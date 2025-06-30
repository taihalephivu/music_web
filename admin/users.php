<?php
require_once '../config/database.php';
// Kiểm tra đăng nhập admin (có thể include file kiểm tra session ở đây)

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

// Lấy danh sách user (bỏ vip)
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
        body { background: #fff; color: #111; }
        .admin-container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 18px; padding: 32px; border: 1px solid #e0e0e0; }
        h2 { color: #2196f3; text-align: center; }
        form.search-form { text-align: right; margin-bottom: 18px; }
        input[type=text] { padding: 7px 12px; border-radius: 6px; border: 1px solid #e0e0e0; background: #fff; color: #111; }
        button, .btn { padding: 7px 16px; border-radius: 6px; border: none; background: #2196f3; color: #fff; cursor: pointer; margin-right: 4px; }
        .btn-edit { background: #4caf50; }
        .btn-delete { background: #ff5252; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; background: #fff; }
        th, td { padding: 12px 8px; border-bottom: 1px solid #e0e0e0; text-align: left; }
        th { background: #f4f6fb; color: #111; }
        tr:hover { background: #f4f6fb; }
        .role-admin { color: #ff5252; font-weight: bold; }
        .role-user { color: #2196f3; font-weight: bold; }
        a { color: #2196f3; }
    </style>
</head>
<body>
    <div class="admin-container">
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
    </div>
</body>
</html> 