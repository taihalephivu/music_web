# Music Store - Trang Web Bán Dụng Cụ Âm Nhạc

## Mô tả dự án

Music Store là một trang web bán dụng cụ âm nhạc trực tuyến được xây dựng bằng HTML, CSS, PHP, JavaScript và MySQL. Trang web cung cấp giao diện hiện đại và thân thiện với người dùng để mua sắm các dụng cụ âm nhạc từ các thương hiệu nổi tiếng.

## Tính năng chính

### 🎵 Quản lý sản phẩm
- Hiển thị danh sách dụng cụ âm nhạc với hình ảnh và thông tin chi tiết
- Phân loại theo danh mục (Guitar, Piano, Drum, Violin, Wind Instruments, Accessories)
- Hiển thị thông tin thương hiệu và giá cả
- Tìm kiếm sản phẩm theo tên, thương hiệu, danh mục

### 🛒 Giỏ hàng
- Thêm sản phẩm vào giỏ hàng
- Cập nhật số lượng sản phẩm
- Xóa sản phẩm khỏi giỏ hàng
- Tính tổng tiền đơn hàng
- Lưu trữ giỏ hàng trong localStorage

### 🎨 Giao diện
- Thiết kế responsive, tương thích với mọi thiết bị
- Giao diện hiện đại với gradient và hiệu ứng hover
- Modal hiển thị chi tiết sản phẩm
- Animation mượt mà và thân thiện

### 📱 Responsive Design
- Tối ưu cho desktop, tablet và mobile
- Navigation menu thích ứng
- Grid layout linh hoạt

## Cấu trúc dự án

```
Music-Store/
├── api/
│   ├── get_songs.php          # API lấy danh sách dụng cụ âm nhạc
│   └── add_to_cart.php        # API thêm vào giỏ hàng
├── assets/
│   ├── css/
│   │   └── style.css          # File CSS chính
│   └── js/
│       └── script.js          # File JavaScript chính
├── config/
│   └── database.php           # Cấu hình kết nối database
├── database.sql               # File SQL tạo database và dữ liệu mẫu
├── index.php                  # Trang chủ
└── README.md                  # Hướng dẫn sử dụng
```

## Cài đặt và chạy dự án

### Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx) hoặc XAMPP/WAMP

### Bước 1: Cài đặt môi trường
1. Cài đặt XAMPP hoặc WAMP
2. Khởi động Apache và MySQL

### Bước 2: Tạo database
1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Tạo database mới tên `music_store`
3. Import file `database.sql` để tạo bảng và dữ liệu mẫu

### Bước 3: Cấu hình kết nối
1. Mở file `config/database.php`
2. Cập nhật thông tin kết nối database nếu cần:
   ```php
   private $host = "localhost";
   private $db_name = "music_store";
   private $username = "root";
   private $password = "";
   ```

### Bước 4: Chạy dự án
1. Copy toàn bộ thư mục vào `htdocs` (XAMPP) hoặc `www` (WAMP)
2. Truy cập: `http://localhost/Music-Store`

## Cấu trúc Database

### Bảng `categories`
- Lưu trữ danh mục dụng cụ âm nhạc
- Các trường: id, name, description, image_url, created_at

### Bảng `brands`
- Lưu trữ thông tin thương hiệu
- Các trường: id, name, description, logo_url, created_at

### Bảng `instruments`
- Lưu trữ thông tin dụng cụ âm nhạc
- Các trường: id, name, description, category_id, brand_id, price, stock_quantity, image_url, specifications, created_at

### Bảng `users`
- Lưu trữ thông tin người dùng
- Các trường: id, username, email, password, full_name, phone, address, created_at

### Bảng `cart`
- Lưu trữ giỏ hàng của người dùng
- Các trường: id, user_id, instrument_id, quantity, created_at

### Bảng `orders`
- Lưu trữ đơn hàng
- Các trường: id, user_id, total_amount, status, shipping_address, phone, created_at

### Bảng `order_items`
- Lưu trữ chi tiết đơn hàng
- Các trường: id, order_id, instrument_id, quantity, price

## Tính năng JavaScript

### Quản lý giỏ hàng
- `addToCart(instrumentId)`: Thêm sản phẩm vào giỏ hàng
- `updateQuantity(instrumentId, change)`: Cập nhật số lượng
- `clearCart()`: Xóa toàn bộ giỏ hàng
- `showCart()`: Hiển thị modal giỏ hàng

### Tìm kiếm và lọc
- `handleSearch(event)`: Xử lý tìm kiếm theo từ khóa
- `filterByCategory(categoryName)`: Lọc theo danh mục

### Hiển thị sản phẩm
- `displayInstruments(instruments)`: Hiển thị danh sách sản phẩm
- `showInstrumentDetails(instrumentId)`: Hiển thị chi tiết sản phẩm

## CSS Features

### Responsive Design
- Grid layout linh hoạt
- Media queries cho mobile và tablet
- Flexbox cho layout

### Animations
- Hover effects cho cards
- Smooth transitions
- Loading animations

### Modern UI
- Gradient backgrounds
- Glass morphism effects
- Shadow và border radius

## API Endpoints

### GET `/api/get_songs.php`
- Lấy danh sách dụng cụ âm nhạc
- Response: JSON với thông tin sản phẩm, thương hiệu, danh mục

### POST `/api/add_to_cart.php`
- Thêm sản phẩm vào giỏ hàng
- Body: `{instrument_id, user_id, quantity}`
- Response: JSON với thông báo thành công/lỗi

## Phát triển thêm

### Tính năng có thể thêm
1. **Đăng nhập/Đăng ký**: Hệ thống authentication
2. **Thanh toán**: Tích hợp payment gateway
3. **Quản lý đơn hàng**: Admin panel
4. **Đánh giá sản phẩm**: Review và rating
5. **Wishlist**: Danh sách yêu thích
6. **So sánh sản phẩm**: Compare features
7. **Blog/Tin tức**: Bài viết về âm nhạc

### Cải thiện kỹ thuật
1. **Security**: Input validation, SQL injection prevention
2. **Performance**: Caching, image optimization
3. **SEO**: Meta tags, structured data
4. **PWA**: Progressive Web App features

## Tác giả

Dự án được phát triển cho mục đích học tập và nghiên cứu.

## License

MIT License - Xem file LICENSE để biết thêm chi tiết. 