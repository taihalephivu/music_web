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

// Xử lý cập nhật thông tin
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validation
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = 'Họ và tên không được để trống';
    }
    
    if (empty($email)) {
        $errors[] = 'Email không được để trống';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ';
    }
    
    if (empty($phone)) {
        $errors[] = 'Số điện thoại không được để trống';
    } elseif (!preg_match('/^[0-9+\-\s()]{10,15}$/', $phone)) {
        $errors[] = 'Số điện thoại không hợp lệ';
    }
    
    if (empty($address)) {
        $errors[] = 'Địa chỉ không được để trống';
    }
    
    // Kiểm tra email đã tồn tại chưa (trừ email hiện tại của user)
    if (empty($errors)) {
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = 'Email này đã được sử dụng bởi tài khoản khác';
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $db->prepare('UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
            $result = $stmt->execute([$full_name, $email, $phone, $address, $user_id]);
            
            if ($result) {
                $message = 'Cập nhật thông tin thành công!';
                $message_type = 'success';
                // Cập nhật session
                $_SESSION['user']['full_name'] = $full_name;
                $_SESSION['user']['email'] = $email;
            } else {
                $message = 'Có lỗi xảy ra khi cập nhật thông tin';
                $message_type = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Có lỗi xảy ra khi cập nhật thông tin';
            $message_type = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}

// Lấy thông tin user
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
            flex-wrap: wrap;
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
            font-size: 14px;
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
        .btn-success {
            background: #4caf50;
            color: #fff;
        }
        .btn-success:hover {
            background: #388e3c;
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
        
        /* Form chỉnh sửa */
        .edit-form {
            display: none;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 24px;
            margin: 20px 0;
            border: 1px solid #e0e0e0;
        }
        .edit-form.show {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #2196f3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        /* Thông báo */
        .message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message.success {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .message.error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                margin: 20px auto;
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            .info-value {
                text-align: left;
            }
            .profile-actions {
                flex-direction: column;
            }
            .form-actions {
                flex-direction: column;
            }
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
        
        <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
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
        
        <!-- Form chỉnh sửa -->
        <div class="edit-form" id="editForm">
            <h3 style="margin-top: 0; color: #2196f3;">Chỉnh sửa thông tin</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name">Họ và tên *</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Số điện thoại *</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Địa chỉ *</label>
                    <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="toggleEditForm()">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" name="update_profile" class="btn btn-success">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
        
        <div class="profile-actions">
            <button class="btn btn-primary" onclick="toggleEditForm()">
                <i class="fas fa-edit"></i> Chỉnh sửa thông tin
            </button>
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

    <script>
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            form.classList.toggle('show');
            
            // Cuộn đến form nếu đang hiển thị
            if (form.classList.contains('show')) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html> 