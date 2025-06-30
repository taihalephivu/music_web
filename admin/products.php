<?php
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();
// Lấy danh sách sản phẩm
$sql = "SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i
        LEFT JOIN brands b ON i.brand_id = b.id
        LEFT JOIN categories c ON i.category_id = c.id
        ORDER BY i.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        $target = '../assets/images/' . uniqid('product_') . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_url = 'assets/images/' . basename($target);
        }
    }
    $stmt = $conn->prepare("INSERT INTO instruments (name, description, category_id, brand_id, price, stock_quantity, image_url, specifications) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$name, $description, $category_id, $brand_id, $price, $stock_quantity, $image_url, $specifications]);
    echo '<script>location.reload();</script>';
    exit;
}
?>
<h2>Quản lý sản phẩm</h2>
<a href="#" class="btn btn-primary" style="margin-bottom:18px;" onclick="showAddProductModal();return false;">+ Thêm sản phẩm</a>
<!-- Modal thêm sản phẩm -->
<div id="addProductModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:32px 28px;border-radius:16px;max-width:480px;width:95vw;box-shadow:0 8px 32px #2196f355;position:relative;">
    <h3 style="color:#2196f3;margin-bottom:18px;">Thêm sản phẩm mới</h3>
    <form id="addProductForm" method="post" enctype="multipart/form-data">
      <label>Tên sản phẩm:</label>
      <input type="text" name="name" required style="width:100%;margin-bottom:10px;">
      <label>Mô tả:</label>
      <textarea name="description" rows="2" style="width:100%;margin-bottom:10px;"></textarea>
      <label>Giá:</label>
      <input type="number" name="price" min="0" required style="width:100%;margin-bottom:10px;">
      <label>Tồn kho:</label>
      <input type="number" name="stock_quantity" min="0" required style="width:100%;margin-bottom:10px;">
      <label>Danh mục:</label>
      <select name="category_id" required style="width:100%;margin-bottom:10px;">
        <?php
        $cats = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        foreach($cats as $cat) {
          echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['name']).'</option>';
        }
        ?>
      </select>
      <label>Thương hiệu:</label>
      <select name="brand_id" required style="width:100%;margin-bottom:10px;">
        <?php
        $brands = $conn->query("SELECT id, name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        foreach($brands as $brand) {
          echo '<option value="'.$brand['id'].'">'.htmlspecialchars($brand['name']).'</option>';
        }
        ?>
      </select>
      <label>Ảnh sản phẩm:</label>
      <input type="file" name="image" accept="image/*" style="width:100%;margin-bottom:10px;">
      <label>Thông số kỹ thuật:</label>
      <textarea name="specifications" rows="2" style="width:100%;margin-bottom:10px;"></textarea>
      <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:18px;">
        <button type="button" onclick="closeAddProductModal()" style="background:#e0e0e0;color:#111;padding:8px 18px;border-radius:6px;border:none;">Hủy</button>
        <button type="submit" class="btn btn-primary">Thêm</button>
      </div>
    </form>
    <button onclick="closeAddProductModal()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
  </div>
</div>
<script>
function showAddProductModal() {
  document.getElementById('addProductModal').style.display = 'flex';
}
function closeAddProductModal() {
  document.getElementById('addProductModal').style.display = 'none';
}
</script>
<table>
    <tr>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Tồn kho</th>
        <th>Danh mục</th>
        <th>Thương hiệu</th>
        <th>Hành động</th>
    </tr>
    <?php foreach($products as $product): ?>
    <tr>
        <td><?php echo htmlspecialchars($product['name']); ?></td>
        <td><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</td>
        <td><?php echo $product['stock_quantity']; ?></td>
        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
        <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
        <td>
            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">Sửa</a>
            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a href="index.php" style="color:#2196f3">Quay lại trang admin</a> 