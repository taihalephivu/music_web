-- Database cho Music Store - Cửa hàng dụng cụ âm nhạc
CREATE DATABASE IF NOT EXISTS music_web;
USE music_web;

-- Bảng danh mục dụng cụ
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng thương hiệu
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    logo_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng dụng cụ âm nhạc
CREATE TABLE instruments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT,
    brand_id INT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_url VARCHAR(500),
    specifications TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id)
);

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng giỏ hàng
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    instrument_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (instrument_id) REFERENCES instruments(id)
);

-- Bảng đơn hàng
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    instrument_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (instrument_id) REFERENCES instruments(id)
);

-- Thêm dữ liệu mẫu
INSERT INTO categories (name, description, image_url) VALUES
('Guitar', 'Các loại đàn guitar acoustic và electric', 'images/categories/guitar.jpg'),
('Piano', 'Đàn piano và keyboard', 'images/categories/piano.jpg'),
('Drum', 'Bộ trống và phụ kiện', 'images/categories/drum.jpg'),
('Violin', 'Đàn violin và các nhạc cụ dây', 'images/categories/violin.jpg'),
('Wind Instruments', 'Kèn, sáo và các nhạc cụ hơi', 'images/categories/wind.jpg'),
('Accessories', 'Phụ kiện âm nhạc', 'images/categories/accessories.jpg');

INSERT INTO brands (name, description, logo_url) VALUES
('Fender', 'Thương hiệu guitar hàng đầu thế giới', 'images/brands/fender.png'),
('Yamaha', 'Nhà sản xuất nhạc cụ đa dạng', 'images/brands/yamaha.png'),
('Roland', 'Chuyên về keyboard và drum machine', 'images/brands/roland.png'),
('Gibson', 'Guitar cao cấp', 'images/brands/gibson.png'),
('Pearl', 'Bộ trống chất lượng cao', 'images/brands/pearl.png'),
('Kawai', 'Piano và keyboard cao cấp', 'images/brands/kawai.png');

INSERT INTO instruments (name, description, category_id, brand_id, price, stock_quantity, image_url, specifications) VALUES
('Fender Stratocaster', 'Guitar electric cổ điển với âm thanh tuyệt vời', 1, 1, 25000000, 10, 'images/instruments/stratocaster.jpg', '6 dây, 22 phím, màu sunburst'),
('Yamaha C40', 'Guitar acoustic cho người mới bắt đầu', 1, 2, 3500000, 25, 'images/instruments/yamaha_c40.jpg', 'Gỗ spruce, 6 dây, phù hợp cho người mới học'),
('Roland FP-30', 'Digital piano với 88 phím weighted', 2, 3, 15000000, 8, 'images/instruments/roland_fp30.jpg', '88 phím, 96 âm sắc, Bluetooth'),
('Pearl Export', 'Bộ trống 5 piece cho người mới', 3, 5, 12000000, 5, 'images/instruments/pearl_export.jpg', '5 piece, bao gồm cymbal, pedal'),
('Gibson Les Paul', 'Guitar electric cao cấp', 1, 4, 45000000, 3, 'images/instruments/les_paul.jpg', 'Mahogany body, maple top, 2 humbuckers'),
('Kawai ES110', 'Digital piano portable', 2, 6, 18000000, 12, 'images/instruments/kawai_es110.jpg', '88 phím, 19 âm sắc, 192 polyphony'),
('Violin 4/4', 'Đàn violin size đầy đủ', 4, 2, 8500000, 15, 'images/instruments/violin_44.jpg', 'Gỗ maple, size 4/4, bao gồm vĩ'),
('Saxophone Alto', 'Kèn saxophone alto', 5, 2, 28000000, 6, 'images/instruments/saxophone_alto.jpg', 'Đồng vàng, key F#, bao gồm ống thổi'),
('Guitar Capo', 'Capo cho guitar', 6, 1, 350000, 50, 'images/accessories/capo.jpg', 'Thép không gỉ, phù hợp mọi loại guitar'),
('Guitar Strings', 'Dây đàn guitar acoustic', 6, 1, 250000, 100, 'images/accessories/strings.jpg', 'Dây bronze, set 6 dây, gauge 12-53');

ALTER TABLE users ADD COLUMN role ENUM('user','admin') DEFAULT 'user' AFTER address; 