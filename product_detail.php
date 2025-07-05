<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div style="color:red;text-align:center;margin:40px;">Không tìm thấy sản phẩm!</div>';
    exit;
}
$stmt = $conn->prepare("SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i LEFT JOIN brands b ON i.brand_id = b.id LEFT JOIN categories c ON i.category_id = c.id WHERE i.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo '<div style="color:red;text-align:center;margin:40px;">Sản phẩm không tồn tại!</div>';
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm - Music Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<?php include 'header.php'; ?>
<div class="container" style="max-width:900px;margin:40px auto 60px auto;">
    <div style="display:flex;flex-wrap:wrap;gap:40px;background:#181c24;border-radius:18px;box-shadow:0 4px 32px #0002;padding:32px;align-items:flex-start;">
        <div style="flex:1 1 320px;min-width:280px;max-width:380px;">
            <img src="<?= $product['image_url'] ? $product['image_url'] : 'assets/images/default.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%;border-radius:12px;object-fit:cover;box-shadow:0 2px 16px #0003;" onerror="this.src='assets/images/default.jpg'">
        </div>
        <div style="flex:2 1 340px;min-width:260px;">
            <h2 style="color:#fff;font-size:2rem;font-weight:700;margin-bottom:10px;"><?= htmlspecialchars($product['name']) ?></h2>
            <div style="color:#2196f3;font-weight:500;margin-bottom:6px;">Thương hiệu: <?= htmlspecialchars($product['brand_name']) ?></div>
            <div style="color:#ffd600;font-weight:500;margin-bottom:16px;">Danh mục: <?= htmlspecialchars($product['category_name']) ?></div>
            <div style="color:#4caf50;font-size:1.5rem;font-weight:700;margin-bottom:18px;"><?= number_format($product['price'], 0, ',', '.') ?>₫</div>
            <div style="color:#ffd600;margin-bottom:12px;"><i class="fas fa-box"></i> Còn lại: <?= $product['stock_quantity'] ?> sản phẩm</div>
            <div style="color:#eee;margin-bottom:18px;line-height:1.7;">Mô tả: <?= nl2br(htmlspecialchars($product['description'])) ?></div>
            <?php if (!empty($product['specifications'])): ?>
            <div style="color:#eee;margin-bottom:18px;">
                <b>Thông số kỹ thuật:</b><br><?= nl2br(htmlspecialchars($product['specifications'])) ?>
            </div>
            <?php endif; ?>
            <form method="post" action="add_to_cart.php" style="margin-bottom:12px;display:inline-block;">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-size:1.1rem;font-weight:600;border-radius:8px;">Thêm vào giỏ</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?> 