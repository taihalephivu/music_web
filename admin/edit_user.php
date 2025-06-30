<?php
require_once '../config/database.php';

// Lấy ID user từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('ID user không hợp lệ!');
}

// Kết nối DB
$db = new Database();
$conn = $db->getConnection();

// Lấy thông tin user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die('Không tìm thấy user!');
}
if ($user['role'] === 'admin') {
    die('Không thể sửa thông tin admin!');
}

// Xử lý cập nhật
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    // Có thể kiểm tra validate ở đây
    $stmt = $conn->prepare("UPDATE users SET email=?, phone=?, address=? WHERE id=?");
    $stmt->execute([$email, $phone, $address, $id]);
    $msg = 'Cập nhật thành công!';
    // Reload lại dữ liệu mới
    $user['email'] = $email;
    $user['phone'] = $phone;
    $user['address'] = $address;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin user</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #fff; color: #111; }
        .edit-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 18px; padding: 32px; border: 1px solid #e0e0e0; }
        h2 { color: #2196f3; text-align: center; }
        label { display: block; margin-top: 16px; color: #111; }
        input[type=text], input[type=email] { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #e0e0e0; background: #fff; color: #111; }
        button { margin-top: 20px; padding: 10px 24px; border-radius: 6px; border: none; background: #2196f3; color: #fff; cursor: pointer; }
        .msg { color: #4caf50; text-align: center; margin-bottom: 10px; }
        a { color: #2196f3; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Sửa thông tin user</h2>
        <?php if($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            <label>Địa chỉ:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            <button type="submit">Cập nhật</button>
        </form>
        <br>
        <a href="users.php">Quay lại danh sách user</a>
    </div>
</body>
</html> 