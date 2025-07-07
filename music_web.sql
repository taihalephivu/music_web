-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 07, 2025 lúc 10:38 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `music_web`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `logo_url`, `created_at`) VALUES
(1, 'Fender', 'Thương hiệu guitar hàng đầu thế giới', 'images/brands/fender.png', '2025-06-29 14:32:26'),
(2, 'Yamaha', 'Nhà sản xuất nhạc cụ đa dạng', 'images/brands/yamaha.png', '2025-06-29 14:32:26'),
(3, 'Roland', 'Chuyên về keyboard và drum machine', 'images/brands/roland.png', '2025-06-29 14:32:26'),
(4, 'Gibson', 'Guitar cao cấp', 'images/brands/gibson.png', '2025-06-29 14:32:26'),
(5, 'Pearl', 'Bộ trống chất lượng cao', 'images/brands/pearl.png', '2025-06-29 14:32:26'),
(6, 'Kawai', 'Piano và keyboard cao cấp', 'images/brands/kawai.png', '2025-06-29 14:32:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `instrument_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image_url`, `created_at`) VALUES
(1, 'Guitar', 'Các loại đàn guitar acoustic và electric', 'images/categories/guitar.jpg', '2025-06-29 14:32:26'),
(2, 'Piano', 'Đàn piano và keyboard', 'images/categories/piano.jpg', '2025-06-29 14:32:26'),
(3, 'Drum', 'Bộ trống và phụ kiện', 'images/categories/drum.jpg', '2025-06-29 14:32:26'),
(4, 'Violin', 'Đàn violin và các nhạc cụ dây', 'images/categories/violin.jpg', '2025-06-29 14:32:26'),
(5, 'Wind Instruments', 'Kèn, sáo và các nhạc cụ hơi', 'images/categories/wind.jpg', '2025-06-29 14:32:26'),
(6, 'Accessories', 'Phụ kiện âm nhạc', 'images/categories/accessories.jpg', '2025-06-29 14:32:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `instruments`
--

CREATE TABLE `instruments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `instruments`
--

INSERT INTO `instruments` (`id`, `name`, `description`, `category_id`, `brand_id`, `price`, `stock_quantity`, `image_url`, `specifications`, `created_at`) VALUES
(277, 'Fender Player Stratocaster (SSS)', 'Fender Player Stratocaster (SSS) là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 22873925.00, 11, 'assets/images/products/product_686a44f3a63af.jpeg', 'electric,guitars', '2025-07-06 09:40:43'),
(278, 'Fender American Professional II Stratocaster', 'Fender American Professional II Stratocaster là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 10754071.00, 15, 'assets/images/products/product_686a457b1d0bb.jpg', 'electric,guitars', '2025-07-06 09:44:27'),
(279, 'Fender Vintera \'60s Stratocaster', 'Fender Vintera \'60s Stratocaster là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 35854374.00, 30, 'assets/images/products/product_686a45d91a71e.jpg', 'electric,guitars', '2025-07-06 09:46:01'),
(280, 'Gibson Les Paul Standard \'60s', 'Gibson Les Paul Standard \'60s là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 4, 9224873.00, 3, 'assets/images/products/product_686a463e65bb1.jpg', 'electric,guitars', '2025-07-06 09:47:42'),
(281, 'Epiphone Les Paul Standard', 'Epiphone Les Paul Standard là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 22460965.00, 22, 'assets/images/products/product_686a46810656c.jpg', 'Electric Guitars', '2025-07-06 09:48:49'),
(282, 'PRS SE Custom 24', 'PRS SE Custom 24 là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 5, 48916473.00, 17, 'assets/images/products/product_686a46f00e45c.jpg', 'electric,guitars', '2025-07-06 09:50:40'),
(283, 'Schaller M6 Locking Tuners', 'Schaller M6 Locking Tuners là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 5, 20168447.00, 21, 'assets/images/products/product_686a481554ad3.jpg', 'accessories', '2025-07-06 09:55:33'),
(284, 'Truetone 1 SPOT PRO CS12', 'Truetone 1 SPOT PRO CS12 là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 2, 35055804.00, 18, 'assets/images/products/product_686a48aba526a.jpg', 'accessories', '2025-07-06 09:57:41'),
(285, 'Jim Dunlop Jazz III', 'Jim Dunlop Jazz III là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 3, 45922474.00, 17, 'assets/images/products/product_686a49068958e.jpg', 'accessories', '2025-07-06 09:59:34'),
(286, 'Planet Waves American Stage Cable', 'Planet Waves American Stage Cable là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 6, 17722065.00, 6, 'assets/images/products/product_686a493eef1ca.jpg', 'Accessories', '2025-07-06 10:00:30'),
(288, 'D\'Addario NYXL 9-42', 'D\'Addario NYXL 9-42 là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 4, 3874671.00, 13, 'assets/images/products/product_686a49b4451bd.jpg', 'Accessories', '2025-07-06 10:02:28'),
(289, 'Elixir Nanoweb 10-46', 'Elixir Nanoweb 10-46 là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 1, 40591480.00, 19, 'assets/images/products/product_686a4b0f34ef1.jpg', 'Accessories', '2025-07-06 10:08:15'),
(290, 'Martin LX1E Little Martin', 'Martin LX1E Little Martin là một sản phẩm chất lượng cao thuộc dòng Acoustic Guitars.', 1, 2, 12070044.00, 28, 'assets/images/products/product_686a4bd6e3bc7.jpg', 'Acoustic Guitars', '2025-07-06 10:11:34'),
(291, 'Yamaha BB434', 'Yamaha BB434 là một sản phẩm chất lượng cao thuộc dòng Bass Guitars.', 1, 2, 12064086.00, 1, 'assets/images/products/product_686a4c04383e3.jpg', 'Bass Guitars', '2025-07-06 10:12:20'),
(292, 'Ibanez BTB845', 'Ibanez BTB845 là một sản phẩm chất lượng cao thuộc dòng Bass Guitars.', 1, 3, 12269679.00, 21, 'assets/images/products/product_686a4c3a47ba4.jpg', 'Bass Guitars', '2025-07-06 10:13:14'),
(293, 'Martin D-28', 'Martin D-28 là một sản phẩm chất lượng cao thuộc dòng Acoustic Guitars.', 1, 4, 28374090.00, 24, 'assets/images/products/product_686a4cc8c25b0.jpg', 'Acoustic Guitars', '2025-07-06 10:15:36'),
(294, 'Yamaha FG800', 'Yamaha FG800 là một sản phẩm chất lượng cao thuộc dòng Acoustic Guitars.', 1, 2, 35167344.00, 29, 'assets/images/products/product_686a4cf81727b.jpg', 'Acoustic Guitars', '2025-07-06 10:16:24'),
(295, 'Orange Rockerverb 50 MKIII Head', 'Orange Rockerverb 50 MKIII Head là một sản phẩm chất lượng cao thuộc dòng Electric Guitar Amps.', 6, 3, 27080010.00, 28, 'assets/images/products/product_686a4d2ba0c00.jpg', 'Electric Guitar Amps', '2025-07-06 10:17:15'),
(296, 'Marshall DSL20CR 20W Combo', 'Marshall DSL20CR 20W Combo là một sản phẩm chất lượng cao thuộc dòng Electric Guitar Amps.', 6, 5, 20769552.00, 22, 'assets/images/products/product_686a4dca939a8.jpeg', 'Electric Guitar Amps', '2025-07-06 10:19:54'),
(297, 'Ernie Ball Regular Slinky Strings', 'Ernie Ball Regular Slinky Strings là một sản phẩm chất lượng cao thuộc dòng Accessories.', 6, 4, 48474884.00, 7, 'assets/images/products/product_686a4e0847efc.jpg', 'Accessories', '2025-07-06 10:20:44'),
(298, 'Fender Player Stratocaster HSS', 'Fender Player Stratocaster HSS là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 24450207.00, 27, 'assets/images/products/product_686a4e72bd2e8.jpg', 'Electric Guitars', '2025-07-06 10:22:42'),
(299, 'Fender Player Stratocaster HSH', 'Fender Player Stratocaster HSH là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 1, 11111269.00, 4, 'assets/images/products/product_686a4eb2ee7c6.png', 'Electric Guitars', '2025-07-06 10:23:46'),
(300, 'Gibson Les Paul Custom', 'Gibson Les Paul Custom là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 4, 44418829.00, 21, 'assets/images/products/product_686a4f11b053d.jpg', 'Electric Guitars', '2025-07-06 10:25:21'),
(301, 'Gibson Les Paul Custom', 'Gibson Les Paul Custom là một sản phẩm chất lượng cao thuộc dòng Electric Guitars.', 1, 4, 44418829.00, 21, 'assets/images/products/product_686a4f35ac095.jpg', 'Electric Guitars', '2025-07-06 10:25:57'),
(302, 'Yamaha CLP-765', 'Piano điện Yamaha CLP-765 GP là dòng piano điện cao cấp được thiết kế như một cây Grand piano.', 2, 2, 99999999.99, 10, 'assets/images/products/product_686a50332b3bd.jpg', 'Piano điện', '2025-07-06 10:30:11'),
(303, 'Piano đứng U1J', 'Là cây đàn cao nhất và đặc biệt nhất trong dòng đàn U, với chiều cao 131 cm, mang lại khả năng biểu cảm cao hơn và dễ chơi hơn.', 2, 2, 98000000.00, 2, 'assets/images/products/product_686a510fb6975.jpg', 'Piano đứng', '2025-07-06 10:33:51'),
(304, 'Piano Điện Yamaha P-143', 'Yamaha P-143 là mẫu đàn cho người mới bắt đầu trong dòng đàn P, phù hợp lý tưởng cho những người mới bắt đầu chơi.', 2, 2, 12000000.00, 12, 'assets/images/products/product_686a51728f3ee.jpg', 'Piano điện', '2025-07-06 10:35:30'),
(305, 'piano Yamaha U3', 'Piano cơ Yamaha U3 là cây đàn upright piano thuộc Yamaha U Series. Yamaha U3 được đánh giá cao về chất lượng và hiệu suất.', 2, 2, 99980000.00, 2, 'assets/images/products/product_686a51d7602de.jfif', 'Piano', '2025-07-06 10:37:11'),
(306, 'Piano điện Kawai PW-160', 'Đàn Piano điện Kawai PW-160 sở hữu ngoại hình đẹp mắt, sang trọng là chiếc đàn hoàn hảo cho tiêu chí \"đẹp mà rẻ\" cho quý khách. Đàn là dòng piano thiếu phím, không hoàn chỉnh bao gồm 76 phím', 2, 6, 5000000.00, 25, 'assets/images/products/product_686a523545a2c.webp', 'Piano điện', '2025-07-06 10:38:45'),
(307, 'PIANO ETERNA SD05', 'Đàn piano điện Eterna SD-05 phiên bản mới được ra mắt năm 2025, với thiết kế kiểu dáng thanh lịch, gọn nhẹ, tích hợp pin sạc giúp chiếc piano dễ dàng di chuyển', 2, 6, 3500000.00, 15, 'assets/images/products/product_686a52a51b458.webp', 'Piano điện', '2025-07-06 10:40:37'),
(308, 'TAMA IP52H6W-BRM', 'Bộ trống Imperialstar là bộ nhạc cụ hoàn thiện mà mọi tay trống cần với mức giá phải chăng. Dòng Imperialstar có nhiều lựa chọn kiểu dáng và finish khác nhau.', 3, 3, 21010000.00, 8, 'assets/images/products/product_686a53294b21d.webp', 'Trống', '2025-07-06 10:42:49'),
(309, 'YAMAHA DRUM STAGE CUSTOM SBP2F5', 'Bộ trống Jazz Yamaha Rydeen Brown mới (combo 5 món) là sản phẩm dành cho cả người mới bắt đầu lẫn tay chơi trống chuyên nghiệp.', 3, 2, 32000000.00, 8, 'assets/images/products/product_686a5378c41ae.jpg', 'Trống cơ', '2025-07-06 10:44:08'),
(310, 'Gecheer Snare Drum', 'Sản phẩm GECHEER SNARE DRUM,PERCUSSION MUSICAL INSTRUMENTS 12INCH SNARE DRUM HEAD WITH DRUMSTICKS SHOULDER STRAP DRUM KEY (BLUE) là sự lựa chọn hoàn hảo nếu bạn đang tìm mua một món cho riêng mình.', 3, 3, 8000000.00, 6, 'assets/images/products/product_686a54077cadb.jpg', 'Trống đơn', '2025-07-06 10:46:31'),
(311, '5 Piece Drum Set', 'Gọn, đẹp và đủ đồ để bạn bắt đầu gõ những nhịp đầu tiên như một drummer thực thụ!', 3, 2, 28000000.00, 5, 'assets/images/products/product_686a54813492d.jpg', 'set 5 trống', '2025-07-06 10:48:33'),
(312, 'Tama Cocktail-Jam Drum', 'Bộ trống dàn cơ Tama Cocktail-Jam Drum Kit VD46CBCN-ISP là một trong những sản phẩm đáng chú ý của hãng trống danh tiếng thiết kế độc đáo và chất lượng âm thanh tuyệt vời', 3, 6, 17000000.00, 4, 'assets/images/products/product_686a5548a303d.jpg', 'bộ trống Tama Cocktail-Jam Drum Kit VD46CBCN-ISP', '2025-07-06 10:51:52'),
(313, 'Violin Yamaha V5SA Size 1/2', 'Hoàn hảo cho nghệ sĩ violin mới, V5SA với gỗ tùng bách ở mặt trên, gỗ Maple ở mặt lưng và phần cần đàn tạo nên từ vật liệu chất lượng cao.', 4, 2, 12500000.00, 12, 'assets/images/products/product_686a55bf261a4.webp', 'violin V5SA', '2025-07-06 10:53:51'),
(314, 'Violin Student Beginner Practice Violin', 'Hoàn hảo cho nghệ sĩ violin mới,mặt lưng và phần cần đàn tạo nên từ vật liệu chất lượng cao', 4, 2, 15000000.00, 4, 'assets/images/products/product_686a563657826.jpg', 'được làm thủ công, thể hiện độ tinh xảo và tính cá nhân', '2025-07-06 10:55:50'),
(315, 'OLD HICKORY VIOLIN', 'Có cấu tạo từ gỗ tùng bách (top), gỗ maple (back),Cần đàn cũng được làm từ vật liệu cao cấp', 4, 6, 18700000.00, 13, 'assets/images/products/product_686a56b963c4b.webp', 'thể hiện độ tinh xảo và tính cá nhân', '2025-07-06 10:58:01'),
(316, 'Selmer TS650', 'Conn TS650 Student Tenor Saxophones mang đến cho người sử dụng một âm thanh chất lượng và một thiết kế khá tiện ích, nổi bật', 5, 5, 34200000.00, 4, 'assets/images/products/product_686a577fe7c85.jpg', 'TS650 Student Tenor Saxophones', '2025-07-06 11:01:19'),
(317, 'Selmer 52JBL Alto Saxo', 'Saxophone Selmer Paris Series II là saxophone Selmer Paris phổ biến nhất trong lịch sử', 5, 3, 42500000.00, 7, 'assets/images/products/product_686a57d97d6e3.jpg', 'sự lựa chọn chuyên nghiệp cho một thế hệ', '2025-07-06 11:02:49'),
(318, 'Kèn Clarinet', 'Kèn Clarinet đã được phát minh tại Đức từ năm 1698 đến 1710. Có nhiều kích thước và cách chỉnh âm khác nhau, nhưng kèn Clarinet Bb là loại phổ biến nhất', 5, 3, 8000000.00, 9, 'assets/images/products/product_686a58498ab40.jfif', 'Kèn Clarinet Bb dài 66cm và có trọng lượng từ 600 gram đến 800 gram, trong khi kèn Clarinet Bass có thể nặng tới 7 kilogram.', '2025-07-06 11:04:41'),
(319, 'Sáo Piccolo', 'Piccolo là một loại nhạc cụ gỗ được chơi bằng cách dùng miệng thổi, không có dăm kèn, kích thước của loại nhạc cụ hơi này chỉ bằng một nửa so với sáo Concert', 5, 3, 7600000.00, 12, 'assets/images/products/product_686a58879fcd0.webp', 'Sáo Piccolo thường được làm từ gỗ, kim loại hoặc nhựa ABS, và thường được nghe thấy trong các tác phẩm orchestral hoặc âm nhạc quân đội truyền thống.', '2025-07-06 11:05:43'),
(320, 'Kèn Cor Anglaise', 'Cor Anglais là một thành viên của họ kèn Oboe, nhưng lớn hơn và có đầu hình chuông. Do đó, được làm từ những chất liệu tương tự như kèn Oboe.', 5, 3, 8600000.00, 3, 'assets/images/products/product_686a58e1b6955.webp', 'dài và lớn hơn so với một cây kèn Oboe tiêu chuẩn (khoảng 81cm), âm thanh của Cor Anglais trầm hơn và được điều chỉnh ở nốt F. Do kích thước lớn hơn, Cor Anglais nặng khoảng 1kg.', '2025-07-06 11:07:13'),
(321, 'Violin 3/4 HM-34V', 'Đàn Violin 3/4 HM-34V: Đàn violin còn có tên gọi khác là đàn vĩ cầm, gồm có bốn dây, mỗi dây cách nhau một quãng năm đúng', 4, 5, 1500000.00, 20, 'assets/images/products/product_686a59a13f190.jpg', 'àn violin được sử dụng trong nhiều thể loại nhạc như: cổ điển, jazz, âm nhạc dân gian, rock…', '2025-07-06 11:10:25'),
(322, 'Violin Agelin Size 4/4', 'Đàn Violin Agelin  được làm thủ công bằng phương pháp truyền thống, áp dụng tương tự cho các loại đàn violin cao cấp.', 4, 2, 3500000.00, 15, 'assets/images/products/product_686a59f3a4873.jpg', 'Đàn violin được sử dụng trong nhiều thể loại nhạc như: cổ điển, jazz, âm nhạc dân gian, rock…', '2025-07-06 11:11:47'),
(323, 'Bass YT100', 'phụ kiện cho đàn Guitar & Bass YT100 chút nào đó có dáng dấp của Yamaha FG cổ điển –từ phần thân đàn có dán thùng to đến workhorse được thiết kế để chơi, âm thanh và hình dáng đẹp và cài đặt theo kiểu Yamaha dễ dàng', 6, 1, 950000.00, 17, 'assets/images/products/product_686a5ad09efbc.jpg', 'Với việc sử dụng đèn LED chiếu sáng, bạn có thể kiểm tra, điều chỉnh thiết bị trong ánh sáng hoặc trong bóng tối, ở nhà hay trên sân khấu.', '2025-07-06 11:15:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `phone`, `created_at`) VALUES
(12, 6, 4450000.00, 'shipped', 'tung của', '0901106261', '2025-07-06 11:18:01'),
(13, 6, 3500000.00, 'shipped', 'tung của', '0901106261', '2025-07-06 11:35:34'),
(14, 6, 1500000.00, 'shipped', 'tung của', '0901106261', '2025-07-06 11:53:27'),
(15, 6, 1500000.00, 'shipped', 'tung của', '0901106261', '2025-07-06 11:53:45'),
(16, 6, 3500000.00, 'cancelled', 'campuchia', '0901106261', '2025-07-06 16:59:09'),
(17, 6, 950000.00, 'cancelled', 'campuchia', '0901106261', '2025-07-07 05:37:41'),
(18, 6, 3500000.00, 'shipped', 'campuchia', '0901106261', '2025-07-07 05:49:35'),
(19, 6, 1500000.00, 'pending', 'china', '0901106261', '2025-07-07 07:19:26'),
(20, 6, 34200000.00, 'pending', 'china', '0901106261', '2025-07-07 07:22:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `instrument_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `instrument_id`, `quantity`, `price`) VALUES
(14, 12, 307, 1, 3500000.00),
(15, 12, 323, 1, 950000.00),
(16, 13, 322, 1, 3500000.00),
(17, 14, 321, 1, 1500000.00),
(18, 15, 321, 1, 1500000.00),
(19, 16, 322, 1, 3500000.00),
(20, 17, 323, 1, 950000.00),
(21, 18, 322, 1, 3500000.00),
(22, 19, 321, 1, 1500000.00),
(23, 20, 316, 1, 34200000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_quality` int(11) NOT NULL CHECK (`product_quality` between 1 and 5),
  `delivery_service` int(11) NOT NULL CHECK (`delivery_service` between 1 and 5),
  `customer_service` int(11) NOT NULL CHECK (`customer_service` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `service_reviews`
--

INSERT INTO `service_reviews` (`id`, `user_id`, `product_quality`, `delivery_service`, `customer_service`, `comment`, `created_at`) VALUES
(4, 6, 5, 4, 3, 'tuyệt với', '2025-07-06 01:45:37'),
(8, 6, 4, 4, 2, 'ok', '2025-07-06 02:15:56'),
(9, 6, 3, 3, 3, 'cũng tạm', '2025-07-06 02:38:59'),
(10, 6, 5, 5, 5, 'Chất lượng 5 sao, đỉnh nóc kịch trần', '2025-07-06 23:28:58'),
(11, 6, 5, 5, 5, 'Sản phẩm tốt', '2025-07-06 23:29:34'),
(12, 6, 5, 5, 5, 'uuuu', '2025-07-07 12:45:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'hung', 'hung82390@gmail.com', '$2y$10$u12ul7WizlGpDaBW3IQvOeFmI4kSWDWO7ZBf6G1eEVaCypJ2CCAHC', 'ĐOÀN QUANG HƯNG', '0901106261', 'Tổ 3, Ấp Thuận Phú 3, Xã Thuận Phú, Huyện Đồng Phú, Tỉnh Bình Phước.', 'admin', '2025-06-29 15:04:16'),
(6, 'qhung', 'hungnek578@gmail.com', '$2y$10$otceLe1GjNi58N1b/4iaTel7id3s.So/xaxwT2fH3GP8VTD6r.5Ke', 'ĐOÀN QUANG HƯNG', '0901106261', 'china', 'user', '2025-07-05 17:02:52'),
(7, 'duy', 'duybo@gmail.com', '$2y$10$E3NTH.w4ubjxiIcalm63AuOHhibPXtAlZituUFVXHQ1LyMOCOm12y', 'Nguyễn Trường Duy', '0369476522', 'tây ninh', 'user', '2025-07-07 05:28:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `instrument_id` (`instrument_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `instrument_id` (`instrument_id`);

--
-- Chỉ mục cho bảng `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `instruments`
--
ALTER TABLE `instruments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`);

--
-- Các ràng buộc cho bảng `instruments`
--
ALTER TABLE `instruments`
  ADD CONSTRAINT `instruments_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `instruments_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`id`);

--
-- Các ràng buộc cho bảng `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD CONSTRAINT `service_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
