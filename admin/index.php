<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #fff; }
        .admin-dashboard { max-width: 600px; margin: 60px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 2.5rem; border: 1px solid #e0e0e0; }
        .admin-dashboard h2 { color: #2196f3; text-align: center; margin-bottom: 2rem; }
        .admin-menu { display: flex; flex-direction: column; gap: 1.2rem; }
        .admin-menu a { display: block; background: #f4f6fb; color: #111; padding: 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; transition: background 0.2s; }
        .admin-menu a:hover { background: #2196f3; color: #fff; }
        .admin-logout { text-align: center; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h2>Chào, <?php echo htmlspecialchars($_SESSION['admin']['full_name']); ?>!</h2>
        <div class="admin-menu">
            <a href="products.php">Quản lý sản phẩm</a>
            <a href="orders.php">Quản lý đơn hàng</a>
            <a href="users.php">Quản lý người dùng</a>
        </div>
        <div class="admin-logout">
            <a href="logout.php" style="color:#ff4757;">Đăng xuất</a>
        </div>
    </div>
</body>
</html> 