<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách đánh giá
$stmt = $conn->query('SELECT sr.*, u.username FROM service_reviews sr LEFT JOIN users u ON sr.user_id = u.id ORDER BY sr.created_at DESC');
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đánh giá dịch vụ</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border: 1px solid #e0e0e0; }
        th { background: #2196f3; color: #fff; }
        tr:nth-child(even) { background: #f8f9fa; }
        .review-stars { color: #ffd600; font-size: 1.1rem; }
        .review-comment { color: #222; }
        .btn-delete { background: #ff5252; color: white; padding: 5px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-delete:hover { background: #d32f2f; }
        .back-link { margin-top: 20px; }
        .back-link a { color: #2196f3; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        .msg-success { background: #4caf50; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .msg-error { background: #f44336; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <h1 style="color: #2196f3;">Quản lý đánh giá dịch vụ</h1>
    
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'deleted'): ?>
            <div class="msg-success">
                <i class="fas fa-check-circle"></i> Đã xóa đánh giá thành công!
            </div>
        <?php elseif ($_GET['msg'] === 'error'): ?>
            <div class="msg-error">
                <i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra khi xóa đánh giá!
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Chất lượng SP</th>
                <th>Giao hàng</th>
                <th>Hỗ trợ KH</th>
                <th>Bình luận</th>
                <th>Ngày gửi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= $r['username'] ? htmlspecialchars($r['username']) : '<i>Khách</i>' ?></td>
                <td><span class="review-stars"><?= str_repeat('★', (int)$r['product_quality']) . str_repeat('☆', 5-(int)$r['product_quality']) ?></span></td>
                <td><span class="review-stars"><?= str_repeat('★', (int)$r['delivery_service']) . str_repeat('☆', 5-(int)$r['delivery_service']) ?></span></td>
                <td><span class="review-stars"><?= str_repeat('★', (int)$r['customer_service']) . str_repeat('☆', 5-(int)$r['customer_service']) ?></span></td>
                <td class="review-comment"><?= nl2br(htmlspecialchars($r['comment'])) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                <td>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="back-link">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Quay lại trang admin</a>
    </div>
</body>
</html> 