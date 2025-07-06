<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

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
            // Ghi nhớ tài khoản bằng cookie
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == '1') {
                setcookie('remember_user', $username, time() + 30*24*60*60, '/');
            } else {
                setcookie('remember_user', '', time() - 3600, '/');
            }
            // Kiểm tra role để redirect
            if ($user['role'] === 'admin') {
                $_SESSION['admin'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name']
                ];
                header('Location: admin/index.php');
            } else {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name']
                ];
                header('Location: index.php');
            }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Music Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; color: #111; font-family: 'Inter', sans-serif; margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { max-width: 400px; width: 100%; background: #fff; border-radius: 18px; padding: 40px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h2 { color: #2196f3; margin-bottom: 10px; font-size: 1.8rem; }
        .login-header p { color: #888; font-size: 0.9rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #111; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 16px; border-radius: 8px; border: 1px solid #e0e0e0; background: #fff; color: #111; font-size: 1rem; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #2196f3; box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2); }
        .login-btn { width: 100%; padding: 14px; background: #2196f3; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .login-btn:hover { background: #1976d2; }
        .error-message { background: #ff5252; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.9rem; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #2196f3; text-decoration: none; font-weight: 500; }
        .register-link a:hover { text-decoration: underline; }
        .back-home { text-align: center; margin-top: 15px; }
        .back-home a { color: #888; text-decoration: none; font-size: 0.9rem; }
        .back-home a:hover { color: #2196f3; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2><i class="fas fa-music"></i> Music Store</h2>
            <p>Đăng nhập vào tài khoản của bạn</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Tên đăng nhập hoặc Email</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['remember_user']) ? htmlspecialchars($_COOKIE['remember_user']) : htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <label style="display:flex;align-items:center;gap:6px;margin:8px 0 16px 0;">
                <input type="checkbox" name="remember_me" value="1" <?php if(isset($_COOKIE['remember_user'])) echo 'checked'; ?>> Ghi nhớ tài khoản
            </label>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </button>
        </form>
        
        <div class="register-link">
            <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </div>
        
        <div class="back-home">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html> 