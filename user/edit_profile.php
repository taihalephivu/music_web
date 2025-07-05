<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$db = (new Database())->getConnection();

// Lấy thông tin user hiện tại
$stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: ../login.php');
    exit;
}

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Kiểm tra dữ liệu
    if ($full_name === '' || $email === '') {
        $error = 'Họ tên và email không được để trống!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ!';
    } elseif ($password !== '' && $password !== $password_confirm) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } else {
        // Kiểm tra email đã tồn tại cho user khác chưa
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $error = 'Email đã được sử dụng bởi tài khoản khác!';
        } else {
            // Cập nhật thông tin
            if ($password !== '') {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sql = 'UPDATE users SET full_name=?, email=?, phone=?, address=?, password=? WHERE id=?';
                $params = [$full_name, $email, $phone, $address, $hashed, $user_id];
            } else {
                $sql = 'UPDATE users SET full_name=?, email=?, phone=?, address=? WHERE id=?';
                $params = [$full_name, $email, $phone, $address, $user_id];
            }
            $stmt = $db->prepare($sql);
            if ($stmt->execute($params)) {
                $success = 'Cập nhật hồ sơ thành công!';
                // Cập nhật lại session
                $_SESSION['user']['full_name'] = $full_name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['address'] = $address;
                // Lấy lại thông tin mới nhất
                $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hồ sơ - Music Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; color: #111; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; }
        .edit-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 18px; padding: 40px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid #e0e0e0; }
        .edit-header { text-align: center; margin-bottom: 30px; }
        .edit-header h2 { color: #2196f3; margin-bottom: 10px; font-size: 1.8rem; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 600; color: #666; display: block; margin-bottom: 8px; }
        input, textarea { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 1rem; }
        .btn { padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s; }
        .btn-primary { background: #2196f3; color: #fff; }
        .btn-primary:hover { background: #1976d2; }
        .btn-secondary { background: #e0e0e0; color: #111; }
        .btn-secondary:hover { background: #d0d0d0; }
        .msg-success { color: #2196f3; text-align: center; margin-bottom: 16px; }
        .msg-error { color: #ff5252; text-align: center; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h2>Chỉnh sửa hồ sơ</h2>
            <p>Cập nhật thông tin cá nhân của bạn</p>
        </div>
        <?php if ($success): ?>
            <div class="msg-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="msg-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu mới (bỏ trống nếu không đổi)</label>
                <input type="password" id="password" name="password" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="password_confirm">Xác nhận mật khẩu mới</label>
                <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
            </div>
            <div style="text-align:center;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                <a href="profile.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</body>
</html> 