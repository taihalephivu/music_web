<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
// XỬ LÝ POST CHO SẢN PHẨM 
if (isset($_GET['page']) && $_GET['page'] === 'products') {
    require_once '../config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock_quantity = intval($_POST['stock_quantity']);
        $category_id = intval($_POST['category_id']);
        $brand_id = intval($_POST['brand_id']);
        $specifications = trim($_POST['specifications']);
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $target = '../assets/images/products/' . uniqid('product_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = 'assets/images/products/' . basename($target);
            }
        }
        if (!empty($_POST['product_id'])) {
            // Cập nhật sản phẩm
            $id = intval($_POST['product_id']);
            $sql = "UPDATE instruments SET name=?, description=?, category_id=?, brand_id=?, price=?, stock_quantity=?, specifications=?";
            $params = [$name, $description, $category_id, $brand_id, $price, $stock_quantity, $specifications];
            if ($image_url) {
                $sql .= ", image_url=?";
                $params[] = $image_url;
            }
            $sql .= " WHERE id=?";
            $params[] = $id;
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        } else {
            // Thêm sản phẩm mới
            $stmt = $conn->prepare("INSERT INTO instruments (name, description, category_id, brand_id, price, stock_quantity, image_url, specifications) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $description, $category_id, $brand_id, $price, $stock_quantity, $image_url, $specifications]);
        }
        header('Location: index.php?page=products');
        exit;
    }
    if (isset($_POST['delete_id'])) {
        $id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM instruments WHERE id=?");
        $stmt->execute([$id]);
        header('Location: index.php?page=products');
        exit;
    }
}
// XỬ LÝ POST CHO ĐÁNH GIÁ 
if (isset($_GET['page']) && $_GET['page'] === 'reviews') {
    require_once '../config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['review_id'])) {
        $review_id = intval($_POST['review_id']);
        if ($review_id > 0) {
            $stmt = $conn->prepare("DELETE FROM service_reviews WHERE id=?");
            $result = $stmt->execute([$review_id]);
            if ($result) {
                header('Location: index.php?page=reviews&msg=deleted');
            } else {
                header('Location: index.php?page=reviews&msg=error');
            }
        } else {
            header('Location: index.php?page=reviews&msg=error');
        }
        exit;
    }
}
$page = $_GET['page'] ?? '';
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
        .admin-header-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 18px;
            justify-content: flex-end;
        }
        .admin-header-actions a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 2px 8px #2196f355;
            padding: 10px 22px;
        }
        .admin-header-actions .home-btn {
            background: #2196f3;
            color: #fff;
        }
        .admin-header-actions .logout-btn {
            background: #ff4757;
            color: #fff;
            box-shadow: 0 2px 8px #ff475799;
        }
        .admin-header-actions a:hover {
            opacity: 0.92;
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
                <a href="index.php?page=products"<?php if($page==='products') echo ' class="active"'; ?>><i class="fas fa-box"></i> Quản lý sản phẩm</a>
                <a href="index.php?page=orders"<?php if($page==='orders') echo ' class="active"'; ?>><i class="fas fa-receipt"></i> Quản lý đơn hàng</a>
                <a href="index.php?page=users"<?php if($page==='users') echo ' class="active"'; ?>><i class="fas fa-users"></i> Quản lý người dùng</a>
                <a href="index.php?page=reviews"<?php if($page==='reviews') echo ' class="active"'; ?>><i class="fas fa-star"></i> Quản lý đánh giá</a>
            </div>
        </nav>
        <main class="admin-content">
            <div class="admin-header-actions">
                <a href="../index.php" class="home-btn"><i class="fas fa-home"></i> Trở về trang chủ</a>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
            <?php
            switch($page) {
                case 'products':
                    include 'products.php';
                    break;
                case 'orders':
                    include 'orders.php';
                    break;
                case 'users':
                    include 'users.php';
                    break;
                case 'reviews':
                    include 'reviews.php';
                    break;
                default:
                    echo '<h2>Chào mừng đến với trang quản trị!</h2><p>Chọn chức năng ở menu bên trái để quản lý hệ thống.</p>';
            }
            ?>
        </main>
    </div>
</body>
</html> 