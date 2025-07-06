<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$id = intval($_GET['id']);
$stmt = $conn->prepare('SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i
    LEFT JOIN brands b ON i.brand_id = b.id
    LEFT JOIN categories c ON i.category_id = c.id
    WHERE i.id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Music Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .product-detail-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(33,150,243,0.08);
            padding: 40px 32px;
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }
        .product-image {
            width: 340px;
            height: 340px;
            object-fit: cover;
            border-radius: 14px;
            box-shadow: 0 2px 16px #2196f322;
            background: #f8f9fa;
        }
        .product-info {
            flex: 1;
        }
        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2196f3;
            margin-bottom: 10px;
        }
        .product-brand, .product-category {
            color: #888;
            font-size: 1rem;
            margin-bottom: 6px;
        }
        .product-price {
            color: #ff4757;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .product-stock {
            color: #27ae60;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .product-desc {
            color: #333;
            font-size: 1.08rem;
            margin-bottom: 24px;
            line-height: 1.7;
        }
        .product-actions {
            margin-top: 18px;
        }
        .btn-add-cart {
            background: #2196f3;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-add-cart:hover {
            background: #1976d2;
        }
        @media (max-width: 900px) {
            .product-detail-container { flex-direction: column; align-items: center; padding: 24px 8px; }
            .product-image { width: 90vw; max-width: 340px; height: 240px; }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="product-detail-container">
    <img class="product-image" src="<?php echo $product['image_url'] ? htmlspecialchars($product['image_url']) : 'assets/images/default.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='assets/images/default.jpg'">
    <div class="product-info">
        <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
        <div class="product-brand"><i class="fas fa-tag"></i> Thương hiệu: <?php echo htmlspecialchars($product['brand_name']); ?></div>
        <div class="product-category"><i class="fas fa-layer-group"></i> Danh mục: <?php echo htmlspecialchars($product['category_name']); ?></div>
        <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</div>
        <div class="product-stock"><i class="fas fa-box"></i> Còn lại: <?php echo $product['stock_quantity']; ?> sản phẩm</div>
        <div class="product-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>
        <div class="product-actions">
            <form method="post" action="add_to_cart.php" style="display:inline">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <button type="submit" class="btn-add-cart"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html> 