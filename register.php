<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    function password_strength($password) {
        $score = 0;
        if (strlen($password) >= 8) $score++;
        if (preg_match('/[A-Z]/', $password)) $score++;
        if (preg_match('/[a-z]/', $password)) $score++;
        if (preg_match('/[0-9]/', $password)) $score++;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score++;
        return $score;
    }

    if ($username === '' || $email === '' || $password === '' || $confirm_password === '' || $full_name === '' || $phone === '' || $address === '') {
        $error = 'Vui lòng nhập đầy đủ tất cả các trường.';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } else {
        $strength = password_strength($password);
        if ($strength < 4) {
            $error = 'Mật khẩu quá yếu! Hãy dùng ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
        } else {
            $db = (new Database())->getConnection();
            // Kiểm tra trùng username/email
            $stmt = $db->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->fetch()) {
                $error = 'Tên đăng nhập hoặc email đã tồn tại!';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare('INSERT INTO users (username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$username, $email, $hash, $full_name, $phone, $address]);
                $success = 'Đăng ký thành công! Bạn có thể đăng nhập.';
                header('Refresh: 1; url=login.php');
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
    <title>Đăng ký - Music Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; color: #111; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; min-height: 100vh; }
        .register-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 18px; padding: 40px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
        .register-header { text-align: center; margin-bottom: 30px; }
        .register-header h2 { color: #2196f3; margin-bottom: 10px; font-size: 1.8rem; }
        .register-header p { color: #888; font-size: 0.9rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #111; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 16px; border-radius: 8px; border: 1px solid #e0e0e0; background: #fff; color: #111; font-size: 1rem; box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #2196f3; box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2); }
        .register-btn { width: 100%; padding: 14px; background: #2196f3; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .register-btn:hover { background: #1976d2; }
        .error-message { background: #ff5252; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.9rem; }
        .success-message { background: #4caf50; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.9rem; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #2196f3; text-decoration: none; font-weight: 500; }
        .login-link a:hover { text-decoration: underline; }
        .back-home { text-align: center; margin-top: 15px; }
        .back-home a { color: #888; text-decoration: none; font-size: 0.9rem; }
        .back-home a:hover { color: #2196f3; }
        .pw-strength { font-size: 0.9rem; margin-top: 5px; font-weight: 500; }
        .pw-weak { color: #ff5252; }
        .pw-fair { color: #ffd600; }
        .pw-strong { color: #4caf50; }
    </style>
    <script>
    function checkPasswordStrength(pw) {
        let score = 0;
        if (pw.length >= 8) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }
    function showStrength() {
        var pw = document.getElementById('password').value;
        var el = document.getElementById('pwStrength');
        var score = checkPasswordStrength(pw);
        if (!pw) { el.textContent = ''; el.className = 'pw-strength'; return; }
        if (score < 3) { el.textContent = 'Mật khẩu yếu'; el.className = 'pw-strength pw-weak'; }
        else if (score === 3) { el.textContent = 'Mật khẩu khá'; el.className = 'pw-strength pw-fair'; }
        else { el.textContent = 'Mật khẩu mạnh'; el.className = 'pw-strength pw-strong'; }
    }
    function validateForm() {
        var fields = ['full_name','username','phone','address','email','password','confirm_password'];
        for (var i=0; i<fields.length; i++) {
            var v = document.forms['regForm'][fields[i]].value.trim();
            if (!v) {
                alert('Vui lòng nhập đầy đủ thông tin!');
                return false;
            }
        }
        var email = document.forms['regForm']['email'].value.trim();
        var phone = document.forms['regForm']['phone'].value.trim();
        var pw = document.forms['regForm']['password'].value;
        var pw2 = document.forms['regForm']['confirm_password'].value;
        var emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        var phonePattern = /^0[0-9]{9}$/;
        if (!emailPattern.test(email)) {
            alert('Email không hợp lệ!');
            return false;
        }
        if (!phonePattern.test(phone)) {
            alert('Số điện thoại phải là 10 số và bắt đầu bằng 0!');
            return false;
        }
        if (pw !== pw2) {
            alert('Mật khẩu xác nhận không khớp!');
            return false;
        }
        var score = checkPasswordStrength(pw);
        if (score < 4) {
            alert('Mật khẩu quá yếu! Hãy dùng ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.');
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2><i class="fas fa-music"></i> Đăng ký tài khoản</h2>
            <p>Tạo tài khoản mới để mua sắm</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form name="regForm" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="full_name">Họ và tên *</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="username">Tên đăng nhập *</label>
                <input type="text" name="username" id="username" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Số điện thoại *</label>
                <input type="text" name="phone" id="phone" required maxlength="10" pattern="0[0-9]{9}">
            </div>
            
            <div class="form-group">
                <label for="address">Địa chỉ *</label>
                <textarea name="address" id="address" rows="2" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu *</label>
                <input type="password" name="password" id="password" required minlength="8" oninput="showStrength()">
                <div id="pwStrength" class="pw-strength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu *</label>
                <input type="password" name="confirm_password" id="confirm_password" required minlength="8">
            </div>
            
            <button type="submit" class="register-btn">
                <i class="fas fa-user-plus"></i> Đăng ký
            </button>
        </form>
        
        <div class="login-link">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
        
        <div class="back-home">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html> 