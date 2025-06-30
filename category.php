<?php
require_once 'config/database.php';
$cat = isset($_GET['cat']) ? trim($_GET['cat']) : '';
if ($cat == '') die('Không xác định danh mục!');

$db = new Database();
$conn = $db->getConnection();

// Lấy id danh mục
$stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
$stmt->execute([$cat]);
$catRow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$catRow) die('Danh mục không tồn tại!');
$catId = $catRow['id'];

// Lấy sản phẩm theo danh mục
$sql = "SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i
        LEFT JOIN brands b ON i.brand_id = b.id
        LEFT JOIN categories c ON i.category_id = c.id
        WHERE i.category_id = ? ORDER BY i.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$catId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($cat); ?> - Music Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; color: #111; }
        .container { max-width: 1200px; margin: 40px auto; }
        .section-header { text-align: center; margin-bottom: 32px; }
        .instruments-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; }
        .instrument-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); transition: transform 0.2s; border: 1px solid #e0e0e0; }
        .instrument-card:hover { transform: translateY(-6px) scale(1.02); }
        .instrument-image { width: 100%; height: 220px; object-fit: cover; background: #f4f6fb; }
        .instrument-info { padding: 18px; }
        .instrument-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 6px; color: #111; }
        .instrument-brand, .instrument-category { color: #2196f3; font-size: 0.98rem; }
        .instrument-price { color: #4caf50; font-size: 1.1rem; font-weight: bold; margin: 8px 0; }
        .instrument-stock { color: #ffd600; font-size: 0.98rem; }
        .instrument-actions { margin-top: 12px; }
        .btn { padding: 7px 16px; border-radius: 6px; border: none; background: #2196f3; color: #fff; cursor: pointer; margin-right: 8px; }
        .btn-details { background: #ffd600; color: #111; }
        @media (max-width: 700px) { .instruments-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="section-header">
            <h2><?php echo htmlspecialchars($cat); ?></h2>
            <p>Danh sách sản phẩm thuộc danh mục <b><?php echo htmlspecialchars($cat); ?></b></p>
        </div>
        <div class="instruments-grid">
            <?php if (count($products) == 0): ?>
                <div style="color: #111; text-align: center; grid-column: 1 / -1;">Không có sản phẩm nào trong danh mục này.</div>
            <?php else: foreach($products as $p): ?>
                <div class="instrument-card">
                    <img src="<?php echo $p['image_url'] ? $p['image_url'] : 'assets/images/default.jpg'; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="instrument-image" onerror="this.src='assets/images/default.jpg'">
                    <div class="instrument-info">
                        <h3 class="instrument-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                        <p class="instrument-brand"><?php echo htmlspecialchars($p['brand_name']); ?></p>
                        <p class="instrument-category"><?php echo htmlspecialchars($p['category_name']); ?></p>
                        <p class="instrument-price"><?php echo number_format($p['price'], 0, ',', '.'); ?>₫</p>
                        <p class="instrument-stock"><i class="fas fa-box"></i> Còn lại: <?php echo $p['stock_quantity']; ?> sản phẩm</p>
                        <div class="instrument-actions">
                            <form method="post" action="add_to_cart.php" style="display:inline">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit" class="btn">Thêm vào giỏ</button>
                            </form>
                            <button class="btn btn-details">Chi tiết</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html> 