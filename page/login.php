<?php
session_start();
require_once '../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    } else {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name']
            ];
            header('Location: ../index.php');
            exit;
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .login-form {max-width: 400px; margin: 60px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 2rem;}
        .login-form h2 {text-align: center; margin-bottom: 1.5rem; color: #4f8cff;}
        .login-form input {width: 100%; padding: 12px; margin-bottom: 1rem; border-radius: 6px; border: 1px solid #e9ecef;}
        .login-form button {width: 100%; background: #4f8cff; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 600;}
        .login-form .error {color: #ff4757; text-align: center; margin-bottom: 1rem;}
        .login-form .register-link {display: block; text-align: center; margin-top: 1rem;}
    </style>
</head>
<body>
    <form class="login-form" method="post">
        <h2>Đăng nhập</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <input type="text" name="username" placeholder="Tên đăng nhập hoặc Email" required autofocus>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
        <a class="register-link" href="register.php">Chưa có tài khoản? Đăng ký</a>
    </form>
</body>
</html> 