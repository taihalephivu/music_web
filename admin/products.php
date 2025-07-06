<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
?>
<h2>Quản lý sản phẩm</h2>
<a href="#" class="btn btn-primary" style="margin-bottom:18px;" onclick="showAddProductModal();return false;">+ Thêm sản phẩm</a>
<!-- Modal thêm/sửa sản phẩm -->
<div id="productModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:32px 28px;border-radius:16px;max-width:480px;width:95vw;box-shadow:0 8px 32px #2196f355;position:relative;">
    <h3 id="modalTitle" style="color:#2196f3;margin-bottom:18px;">Thêm sản phẩm mới</h3>
    <form id="productForm" method="post" enctype="multipart/form-data">
      <input type="hidden" name="product_id" id="product_id">
      <label>Tên sản phẩm:</label>
      <input type="text" name="name" id="name" required style="width:100%;margin-bottom:10px;">
      <label>Mô tả:</label>
      <textarea name="description" id="description" rows="2" style="width:100%;margin-bottom:10px;"></textarea>
      <label>Giá:</label>
      <input type="number" name="price" id="price" min="0" required style="width:100%;margin-bottom:10px;">
      <label>Tồn kho:</label>
      <input type="number" name="stock_quantity" id="stock_quantity" min="0" required style="width:100%;margin-bottom:10px;">
      <label>Danh mục:</label>
      <select name="category_id" id="category_id" required style="width:100%;margin-bottom:10px;">
        <?php
        $cats = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        foreach($cats as $cat) {
          echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['name']).'</option>';
        }
        ?>
      </select>
      <label>Thương hiệu:</label>
      <select name="brand_id" id="brand_id" required style="width:100%;margin-bottom:10px;">
        <?php
        $brands = $conn->query("SELECT id, name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        foreach($brands as $brand) {
          echo '<option value="'.$brand['id'].'">'.htmlspecialchars($brand['name']).'</option>';
        }
        ?>
      </select>
      <label>Ảnh sản phẩm:</label>
      <input type="file" name="image" id="image" accept="image/*" style="width:100%;margin-bottom:10px;">
      <label>Thông số kỹ thuật:</label>
      <textarea name="specifications" id="specifications" rows="2" style="width:100%;margin-bottom:10px;"></textarea>
      <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:18px;">
        <button type="button" onclick="closeProductModal()" style="background:#e0e0e0;color:#111;padding:8px 18px;border-radius:6px;border:none;">Hủy</button>
        <button type="submit" class="btn btn-primary" id="modalSubmitBtn">Thêm</button>
      </div>
    </form>
    <button onclick="closeProductModal()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:1.3rem;color:#888;cursor:pointer;">&times;</button>
  </div>
</div>
<script>
function showAddProductModal() {
  document.getElementById('modalTitle').innerText = 'Thêm sản phẩm mới';
  document.getElementById('modalSubmitBtn').innerText = 'Thêm';
  document.getElementById('productForm').reset();
  document.getElementById('product_id').value = '';
  document.getElementById('productModal').style.display = 'flex';
}
function showEditProductModal(product) {
  document.getElementById('modalTitle').innerText = 'Sửa sản phẩm';
  document.getElementById('modalSubmitBtn').innerText = 'Cập nhật';
  document.getElementById('product_id').value = product.id;
  document.getElementById('name').value = product.name;
  document.getElementById('description').value = product.description;
  document.getElementById('price').value = product.price;
  document.getElementById('stock_quantity').value = product.stock_quantity;
  document.getElementById('category_id').value = product.category_id;
  document.getElementById('brand_id').value = product.brand_id;
  document.getElementById('specifications').value = product.specifications;
  document.getElementById('productModal').style.display = 'flex';
}
function closeProductModal() {
  document.getElementById('productModal').style.display = 'none';
}
function confirmDeleteProduct(id) {
  if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
    document.getElementById('delete_id').value = id;
    document.getElementById('deleteProductForm').submit();
  }
}
</script>
<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Tồn kho</th>
        <th>Danh mục</th>
        <th>Thương hiệu</th>
        <th>Hành động</th>
    </tr>
    <?php foreach($products as $product): ?>
    <tr>
        <td><?php echo $product['id']; ?></td>
        <td><?php echo htmlspecialchars($product['name']); ?></td>
        <td><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</td>
        <td><?php echo $product['stock_quantity']; ?></td>
        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
        <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
        <td>
            <a href="#" class="btn btn-edit" onclick='showEditProductModal(<?php echo json_encode([
                "id"=>$product["id"],
                "name"=>$product["name"],
                "description"=>$product["description"],
                "price"=>$product["price"],
                "stock_quantity"=>$product["stock_quantity"],
                "category_id"=>$product["category_id"],
                "brand_id"=>$product["brand_id"],
                "specifications"=>$product["specifications"]
            ]); ?>);return false;'>Sửa</a>
            <a href="#" class="btn btn-delete" onclick="confirmDeleteProduct(<?php echo $product['id']; ?>);return false;">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<form id="deleteProductForm" method="post" style="display:none;"><input type="hidden" name="delete_id" id="delete_id"></form>
<br>
<a href="index.php" style="color:#2196f3">Quay lại trang admin</a> 