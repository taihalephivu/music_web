<?php
// Tăng thời gian sống session lên 7 ngày
ini_set('session.gc_maxlifetime', 604800);
session_set_cookie_params(604800);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_count') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit;
    }
    try {
        require_once 'config/database.php';
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare('SELECT SUM(quantity) as total FROM cart WHERE user_id = ?');
        $stmt->execute([$_SESSION['user']['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $cartCount = $result['total'] ?? 0;
        $readCount = $_SESSION['cart_count_read'] ?? 0;
        $showBadge = ($cartCount > 0 && $cartCount != $readCount);
        echo json_encode([
            'success' => true,
            'count' => $cartCount,
            'showBadge' => $showBadge
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi server',
            'count' => 0,
            'showBadge' => false
        ]);
    }
    exit;
}

require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare('SELECT c.instrument_id as id, i.name, i.price, i.image_url, c.quantity 
                        FROM cart c 
                        JOIN instruments i ON c.instrument_id = i.id 
                        WHERE c.user_id = ?');
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
// Đánh dấu đã đọc số lượng giỏ hàng khi vào trang cart
$_SESSION['cart_count_read'] = array_sum(array_column($cartItems, 'quantity'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Music Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f6f8fa;
        }
        .cart-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 0 60px 0;
        }
        .cart-wrapper {
            width: 100%;
            max-width: 1100px;
            background: none;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .cart-title-block {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 24px 32px 18px 32px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .cart-title-block h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2196f3;
            margin: 0;
        }
        .cart-content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 32px;
        }
        .cart-products {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 24px 0 0 0;
            min-height: 300px;
        }
        .cart-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0 32px 12px 32px;
            border-bottom: 1px solid #f1f1f1;
        }
        .select-all-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .select-all-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .select-all-label {
            font-weight: 600;
            color: #333;
            cursor: pointer;
        }
        .delete-selected-btn {
            background: #ff4757;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .delete-selected-btn:hover {
            background: #ff3742;
        }
        .cart-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .cart-item-row {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 32px;
            border-bottom: 1px solid #f1f1f1;
            background: none;
        }
        .cart-item-row:last-child {
            border-bottom: none;
        }
        .item-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .item-image {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .item-info {
            flex: 1;
            min-width: 0;
        }
        .item-name {
            font-weight: 600;
            color: #222;
            font-size: 1.08rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .item-price {
            color: #2196f3;
            font-weight: 600;
            font-size: 1.05rem;
        }
        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            background: #2196f3;
            color: #fff;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            transition: background 0.2s;
        }
        .quantity-btn:hover {
            background: #1976d2;
        }
        .quantity-display {
            font-weight: 600;
            color: #333;
            min-width: 30px;
            text-align: center;
        }
        .item-total {
            font-weight: 700;
            color: #2196f3;
            font-size: 1.1rem;
            min-width: 100px;
            text-align: right;
        }
        .item-delete {
            background: #ff5252;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .item-delete:hover {
            background: #ff3742;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-cart i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .empty-cart h2 {
            color: #666;
            margin-bottom: 16px;
        }
        .empty-cart p {
            color: #999;
            margin-bottom: 24px;
        }
        .cart-summary {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(33,150,243,0.07);
            padding: 32px 28px 28px 28px;
            height: fit-content;
            position: sticky;
            top: 110px;
            min-width: 0;
        }
        .summary-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1rem;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding-top: 16px;
            border-top: 2px solid #f1f1f1;
            font-size: 1.2rem;
            font-weight: 700;
            color: #2196f3;
        }
        .checkout-btn {
            width: 100%;
            background: #2196f3;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 12px;
        }
        .checkout-btn:hover {
            background: #1976d2;
        }
        .continue-shopping {
            width: 100%;
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .continue-shopping:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        @media (max-width: 900px) {
            .cart-content-grid {
                grid-template-columns: 1fr;
            }
            .cart-summary {
                position: static;
                margin-top: 32px;
            }
        }
        @media (max-width: 600px) {
            .cart-title-block {
                padding: 18px 10px 12px 10px;
                font-size: 1.2rem;
            }
            .cart-products {
                padding: 12px 0 0 0;
            }
            .cart-item-row {
                padding: 12px 8px;
                gap: 8px;
            }
            .cart-summary {
                padding: 18px 8px 18px 8px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="cart-main">
        <div class="cart-wrapper">
            <div class="cart-title-block">
                <i class="fas fa-shopping-cart" style="font-size:2.1rem;color:#2196f3;"></i>
                <h1>Giỏ hàng</h1>
            </div>
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Giỏ hàng trống</h2>
                    <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                    <a href="index.php" class="btn" style="background: #2196f3; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            <?php else: ?>
            <div class="cart-content-grid">
                <div class="cart-products">
                    <div class="cart-controls">
                        <div class="select-all-container">
                            <input type="checkbox" id="selectAll" class="select-all-checkbox" onchange="toggleSelectAll()">
                            <label for="selectAll" class="select-all-label">Chọn tất cả</label>
                        </div>
                        <button id="deleteSelectedBtn" class="delete-selected-btn" onclick="deleteSelectedItems()">
                            <i class="fas fa-trash"></i> Xóa đã chọn
                        </button>
                    </div>
                    <div class="cart-list">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item-row">
                            <input type="checkbox" class="item-checkbox" value="<?php echo $item['id']; ?>" onchange="updateDeleteButton()">
                            <img src="<?php echo $item['image_url'] ?: 'assets/images/default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 class="item-image"
                                 onerror="this.src='assets/images/default.jpg'">
                            <div class="item-info">
                                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</div>
                            </div>
                            <div class="item-quantity">
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                                <span class="quantity-display"><?php echo $item['quantity']; ?></span>
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                            </div>
                            <div class="item-total">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫
                            </div>
                            <button class="item-delete" onclick="deleteCartItem(<?php echo $item['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="cart-summary">
                    <div class="summary-title">Tóm tắt đơn hàng</div>
                    <div class="summary-item">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                    </div>
                    <div class="summary-item">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                    </div>
                    <button class="checkout-btn" onclick="checkout()">
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </button>
                    <a href="index.php" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateDeleteButton();
        }
        function updateDeleteButton() {
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            const totalCount = itemCheckboxes.length;
            if (deleteSelectedBtn) {
                deleteSelectedBtn.style.display = checkedCount > 0 ? 'flex' : 'none';
                if (checkedCount > 0) {
                    deleteSelectedBtn.innerHTML = `<i class=\"fas fa-trash\"></i> Xóa ${checkedCount} sản phẩm`;
                }
            }
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === totalCount && totalCount > 0;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        }
        function deleteSelectedItems() {
            const itemCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            const selectedIds = Array.from(itemCheckboxes).map(cb => parseInt(cb.value));
            if (selectedIds.length === 0) {
                alert('Vui lòng chọn sản phẩm cần xóa!');
                return;
            }
            if (!confirm(`Bạn có chắc muốn xóa ${selectedIds.length} sản phẩm đã chọn?`)) {
                return;
            }
            fetch('add_to_cart.php?action=delete_multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    item_ids: selectedIds
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Xóa sản phẩm thất bại!');
                }
            })
            .catch(error => {
                alert('Lỗi kết nối!');
            });
        }
        function deleteCartItem(id) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                return;
            }
            fetch('add_to_cart.php?action=delete_item&id=' + id, { 
                method: 'POST' 
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Xóa sản phẩm thất bại!');
                }
            })
            .catch(() => alert('Lỗi kết nối!'));
        }
        function updateQuantity(instrumentId, change) {
            fetch('add_to_cart.php?action=update_quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: instrumentId,
                    change: change
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Cập nhật số lượng thất bại!');
                }
            })
            .catch(() => alert('Lỗi kết nối!'));
        }
        function checkout() {
            // Lấy dữ liệu giỏ hàng từ PHP
            const cart = <?php echo json_encode($cartItems); ?>;
            if (!cart.length) {
                alert('Giỏ hàng trống!');
                return;
            }
            // Gửi qua form ẩn
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'user/checkout.php';
            form.style.display = 'none';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cart_json';
            input.value = JSON.stringify(cart);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateDeleteButton();
        });
    </script>
</body>
</html> 