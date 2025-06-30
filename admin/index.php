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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6fb; margin: 0; font-family: 'Inter', sans-serif; }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar {
            width: 230px;
            background: #222;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 32px 0 0 0;
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
        }
        .sidebar .admin-avatar {
            text-align: center;
            margin-bottom: 32px;
        }
        .sidebar .admin-avatar i {
            font-size: 2.5rem;
            color: #2196f3;
        }
        .sidebar .admin-avatar .admin-name {
            margin-top: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .sidebar-menu {
            flex: 1;
        }
        .sidebar-menu a {
            display: block;
            color: #fff;
            padding: 16px 32px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            border-left: 4px solid transparent;
            transition: background 0.2s, border-color 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #2196f3;
            border-left: 4px solid #ffd600;
            color: #fff;
        }
        .sidebar-logout {
            text-align: center;
            margin: 32px 0 0 0;
        }
        .sidebar-logout a {
            color: #ff4757;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 6px;
            background: #fff;
            display: inline-block;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar-logout a:hover {
            background: #ff4757;
            color: #fff;
        }
        .admin-content {
            flex: 1;
            padding: 48px 40px;
            background: #f4f6fb;
        }
        .admin-content h2 {
            color: #2196f3;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 900px) {
            .admin-layout { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; padding: 0; }
            .sidebar-menu { display: flex; flex-direction: row; }
            .sidebar-menu a { padding: 12px 18px; font-size: 0.95rem; border-left: none; border-bottom: 4px solid transparent; }
            .sidebar-menu a:hover, .sidebar-menu a.active { background: #2196f3; border-bottom: 4px solid #ffd600; }
            .sidebar-logout { margin: 0; }
            .admin-content { padding: 24px 10px; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <nav class="sidebar">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
                <div class="admin-name">Chào, <?php echo htmlspecialchars($_SESSION['admin']['full_name'] ?? $_SESSION['admin']['username']); ?>!</div>
            </div>
            <div class="sidebar-menu">
                <a href="products.php"><i class="fas fa-box"></i> Quản lý sản phẩm</a>
                <a href="orders.php"><i class="fas fa-receipt"></i> Quản lý đơn hàng</a>
                <a href="users.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
            </div>
            <div class="sidebar-logout">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </nav>
        <main class="admin-content">
            <h2>Chào mừng đến với trang quản trị!</h2>
            <p>Chọn chức năng ở menu bên trái để quản lý hệ thống.</p>
        </main>
    </div>
</body>
</html> 