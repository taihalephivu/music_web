<?php
// Trang bài viết chia sẻ kinh nghiệm, hướng dẫn, review cho khách hàng
?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài viết - Chia sẻ kinh nghiệm & Hướng dẫn | Music Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f6f8fa; font-family: 'Inter', Arial, sans-serif; }
        .blog-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 18px; box-shadow: 0 2px 16px #2196f322; padding: 36px 32px; }
        .blog-title { font-size: 2.2rem; font-weight: 800; color: #2196f3; margin-bottom: 18px; text-align: center; }
        .blog-section { margin-bottom: 48px; }
        .blog-section h2 { color: #f8b500; font-size: 1.5rem; font-weight: 700; margin-bottom: 12px; }
        .blog-section h3 { color: #2196f3; font-size: 1.15rem; font-weight: 600; margin-top: 18px; }
        .blog-section ul, .blog-section ol { margin-left: 24px; }
        .customer-story { background: #fffbe6; border-left: 5px solid #f8b500; padding: 18px 22px; border-radius: 10px; margin-bottom: 18px; }
        .customer-story strong { color: #e65100; }
        @media (max-width: 600px) {
            .blog-container { padding: 16px 4px; }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="blog-container">
    <div class="blog-title"><i class="fas fa-lightbulb"></i> Bài viết & Kinh nghiệm chọn nhạc cụ</div>

    <div class="blog-section" id="huong-dan-chon-nhac-cu">
        <h2>1. Hướng dẫn chọn nhạc cụ cho người mới bắt đầu</h2>
        <h3>Cách chọn đàn guitar/piano/ukulele phù hợp với từng lứa tuổi, mục đích</h3>
        <ul>
            <li><strong>Guitar:</strong> Trẻ em nên chọn guitar size nhỏ (1/2, 3/4), dây nylon. Người lớn chọn acoustic hoặc classical tùy mục đích học đệm hát, solo, fingerstyle.</li>
            <li><strong>Piano:</strong> Trẻ em nên bắt đầu với piano điện nhỏ gọn, phím nhẹ. Người lớn chọn piano điện hoặc cơ tùy ngân sách, không gian.</li>
            <li><strong>Ukulele:</strong> Phù hợp mọi lứa tuổi, đặc biệt trẻ em và người mới vì nhỏ gọn, dễ học. Chọn soprano cho trẻ nhỏ, concert/tenor cho người lớn.</li>
        </ul>
        <h3>So sánh các dòng sản phẩm phổ biến (Yamaha, Roland, Fender...)</h3>
        <ul>
            <li><strong>Yamaha:</strong> Đa dạng mẫu mã, giá hợp lý, bền, phù hợp người mới và bán chuyên.</li>
            <li><strong>Roland:</strong> Nổi bật về piano điện, công nghệ âm thanh hiện đại, nhiều tính năng hỗ trợ học.</li>
            <li><strong>Fender:</strong> Nổi tiếng với guitar, ukulele, thiết kế đẹp, âm thanh sáng, phù hợp biểu diễn.</li>
        </ul>
    </div>

    <div class="blog-section" id="bi-quyet-bao-quan">
        <h2>2. Bí quyết bảo quản và vệ sinh nhạc cụ</h2>
        <h3>Hướng dẫn vệ sinh đàn guitar, piano, trống, phụ kiện</h3>
        <ul>
            <li><strong>Guitar:</strong> Lau dây, cần đàn bằng khăn mềm sau khi chơi. Dùng dung dịch chuyên dụng vệ sinh mặt đàn, tránh nước dính vào gỗ.</li>
            <li><strong>Piano:</strong> Lau phím bằng khăn ẩm, không dùng hóa chất mạnh. Vệ sinh bụi bẩn ở các khe phím, bảo dưỡng định kỳ.</li>
            <li><strong>Trống:</strong> Lau mặt trống, hardware bằng khăn khô, kiểm tra ốc vít thường xuyên.</li>
            <li><strong>Phụ kiện:</strong> Dây đeo, bao đàn, capo… nên vệ sinh định kỳ, tránh ẩm mốc.</li>
        </ul>
        <h3>Cách bảo quản nhạc cụ khi thời tiết thay đổi</h3>
        <ul>
            <li>Tránh để nhạc cụ nơi ẩm thấp, gần cửa sổ, điều hòa.</li>
            <li>Sử dụng túi hút ẩm, hộp bảo quản chuyên dụng.</li>
            <li>Định kỳ kiểm tra, lên dây, bảo dưỡng tại cửa hàng uy tín.</li>
        </ul>
    </div>

    <div class="blog-section" id="so-sanh-nhac-cu">
        <h2>4. So sánh nhạc cụ điện tử và nhạc cụ truyền thống</h2>
        <h3>Ưu nhược điểm của piano điện, guitar điện so với loại truyền thống</h3>
        <ul>
            <li><strong>Piano điện:</strong> Nhỏ gọn, dễ di chuyển, nhiều chức năng, giá rẻ hơn piano cơ. Nhược điểm: cảm giác phím, âm thanh không "thật" bằng piano cơ.</li>
            <li><strong>Guitar điện:</strong> Đa dạng hiệu ứng, phù hợp nhạc rock, pop, biểu diễn sân khấu. Nhược điểm: cần ampli, phụ kiện đi kèm, không phù hợp nhạc mộc, dân ca.</li>
            <li><strong>Truyền thống:</strong> Âm thanh tự nhiên, cảm xúc, phù hợp học nhạc cơ bản, biểu diễn cổ điển.</li>
        </ul>
        <h3>Đối tượng phù hợp cho từng loại</h3>
        <ul>
            <li>Nhạc cụ điện tử: Người thích công nghệ, biểu diễn, sáng tạo âm thanh.</li>
            <li>Nhạc cụ truyền thống: Người học nhạc cơ bản, luyện thi, yêu thích âm thanh tự nhiên.</li>
        </ul>
    </div>

    <div class="blog-section" id="cau-chuyen-khach-hang">
        <h2>5. Câu chuyện khách hàng/thành công</h2>
        <div class="customer-story">
            <strong>Minh (18 tuổi, Hà Nội):</strong> Mua đàn guitar Yamaha F310 tại shop, sau 3 tháng đã tự tin đệm hát, tham gia CLB âm nhạc trường.
        </div>
        <div class="customer-story">
            <strong>Chị Lan (35 tuổi, TP.HCM):</strong> Mua piano Roland FP-10 cho con gái, bé học online rất hiệu quả, âm thanh sống động, dễ sử dụng.
        </div>
        <div class="customer-story">
            <strong>Khách hàng thực tế:</strong> "Đàn chắc chắn, âm thanh hay, shop tư vấn nhiệt tình, giao hàng nhanh."
        </div>
        <h3 style="color:#e65100;margin-top:32px;">Đánh giá từ người nổi tiếng</h3>
        <div class="customer-story">
            <strong>Đen Vâu (Rapper):</strong> "Tôi rất ấn tượng với chất lượng đàn guitar tại Music Store. Âm thanh chuẩn, cảm giác chơi rất thật, phù hợp cả biểu diễn lẫn sáng tác."
        </div>
        <div class="customer-story">
            <strong>Hà Anh Tuấn (Ca sĩ):</strong> "Piano Roland FP-10 tại shop mang lại trải nghiệm tuyệt vời cho phòng thu cá nhân của tôi. Dịch vụ tư vấn và giao hàng rất chuyên nghiệp."
        </div>
        <div class="customer-story">
            <strong>Tiên Cookie (Nhạc sĩ/Producer):</strong> "Tôi thường chọn phụ kiện và nhạc cụ tại Music Store vì sự đa dạng, chính hãng và giá hợp lý. Rất hài lòng!"
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html> 