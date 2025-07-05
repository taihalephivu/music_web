<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$db = (new Database())->getConnection();
$stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - Music Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #fff; 
            color: #111; 
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-header h2 {
            color: #2196f3;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .profile-avatar {
            font-size: 4rem;
            color: #2196f3;
            margin-bottom: 20px;
        }
        .profile-info {
            background: #f4f6fb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 120px;
        }
        .info-value {
            color: #111;
            text-align: right;
            flex: 1;
        }
        .profile-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #2196f3;
            color: #fff;
        }
        .btn-primary:hover {
            background: #1976d2;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #111;
        }
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        .btn-danger {
            background: #ff5252;
            color: #fff;
        }
        .btn-danger:hover {
            background: #d32f2f;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link a:hover {
            color: #2196f3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2>Hồ sơ cá nhân</h2>
            <p>Thông tin tài khoản của bạn</p>
        </div>
        
        <div class="profile-info">
            <div class="info-row">
                <span class="info-label">Họ và tên:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tên đăng nhập:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Số điện thoại:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['phone']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['address']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày tạo:</span>
                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></span>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="edit_profile.php" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="history.php" class="btn btn-secondary">
                <i class="fas fa-history"></i> Lịch sử mua hàng
            </a>
            <a href="logout.php" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
        
        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html> 