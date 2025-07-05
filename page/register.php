<?php
session_start();
require_once '../config/database.php';

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
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #f4f6fb; }
        .register-form {
            max-width: 440px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(79,140,255,0.08);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .register-form h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #4f8cff;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .register-form .form-group {
            margin-bottom: 1.1rem;
        }
        .register-form label {
            display: block;
            margin-bottom: 0.3rem;
            color: #222;
            font-weight: 500;
        }
        .register-form input, .register-form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            font-size: 1rem;
            background: #f8fafd;
            transition: border 0.2s;
        }
        .register-form input:focus, .register-form textarea:focus {
            border: 1.5px solid #4f8cff;
            outline: none;
            background: #fff;
        }
        .register-form button {
            width: 100%;
            background: linear-gradient(45deg, #4f8cff, #51cf66);
            color: #fff;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(79,140,255,0.08);
            transition: background 0.2s;
        }
        .register-form button:hover {
            background: #51cf66;
        }
        .register-form .error {
            color: #ff4757;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .register-form .success {
            color: #27ae60;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .register-form .login-link {
            display: block;
            text-align: center;
            margin-top: 1.2rem;
            color: #4f8cff;
            text-decoration: none;
        }
        .register-form .login-link:hover {
            text-decoration: underline;
        }
        .pw-strength {
            font-size: 0.95rem;
            margin-top: 0.2rem;
            font-weight: 500;
        }
        .pw-weak { color: #ff4757; }
        .pw-fair { color: #ffa502; }
        .pw-strong { color: #27ae60; }
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
    <form class="register-form" name="regForm" method="post" onsubmit="return validateForm()">
        <h2>Đăng ký tài khoản</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
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
        <button type="submit">Đăng ký</button>
        <a class="login-link" href="login.php">Đã có tài khoản? Đăng nhập</a>
    </form>
</body>
</html> 