<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Thông tin liên hệ -->
            <div class="footer-section">
                <h3><i class="fas fa-music"></i> Music Store</h3>
                <p>Cửa hàng dụng cụ âm nhạc hàng đầu Việt Nam</p>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</p>
                    <p><i class="fas fa-phone"></i> 0909 123 456</p>
                    <p><i class="fas fa-envelope"></i> info@musicstore.vn</p>
                    <p><i class="fas fa-clock"></i> 8:00 - 22:00 (Thứ 2 - Chủ nhật)</p>
                </div>
            </div>

            <!-- Liên kết nhanh -->
            <div class="footer-section">
                <h3><i class="fas fa-link"></i> Liên kết nhanh</h3>
                <ul class="footer-links">
                    <li><a href="#about">Về chúng tôi</a></li>
                    <li><a href="#brands">Thương hiệu</a></li>
                    <li><a href="#categories">Danh mục</a></li>
                    <li><a href="summer_sale.php">Summer Sale</a></li>
                    <li><a href="user/profile.php">Tài khoản</a></li>
                    <li><a href="user/history.php">Lịch sử mua hàng</a></li>
                </ul>
            </div>

            <!-- Đánh giá dịch vụ và Bản đồ -->
            <div class="footer-section reviews-map-section">
                <?php include 'service_reviews.php'; ?>
                
                <!-- Bản đồ chỉ dẫn -->
                <div class="map-section">
                    <h3><i class="fas fa-map"></i> Bản đồ chỉ dẫn</h3>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps?q=123+Đường+ABC,+Quận+1,+TP.HCM&output=embed"
                            width="100%" height="160" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="map-info">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=123+Đường+ABC,+Quận+1,+TP.HCM" target="_blank" class="btn-map">
                                <i class="fas fa-directions"></i> Chỉ đường
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media & Copyright -->
        <div class="footer-bottom">
            <div class="social-links">
                <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link" title="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link" title="TikTok"><i class="fab fa-tiktok"></i></a>
            </div>
            <p>&copy; 2024 Music Store. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background: #f4f6fb;
    color: #111;
    padding: 40px 0 15px;
    margin-top: 60px;
    border-top: 1px solid #e0e0e0;
}

.footer-content {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr;
    gap: 40px;
    margin-bottom: 30px;
    align-items: start;
}

.footer-section h3 {
    color: #2196f3;
    margin-bottom: 15px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.footer-section h3 i {
    color: #2196f3;
}

.footer-section p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 12px;
    line-height: 1.4;
}

.contact-info p {
    margin: 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 0.85rem;
}

.contact-info i {
    color: #2196f3;
    width: 14px;
    text-align: center;
}

/* Footer Links */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin: 8px 0;
}

.footer-links a {
    color: #666;
    text-decoration: none;
    transition: color 0.2s;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.footer-links a:hover {
    color: #2196f3;
}

.footer-links a::before {
    content: '→';
    color: #2196f3;
    font-weight: bold;
}

/* Reviews & Map Section */
.reviews-map-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: start;
    height: 100%;
}

.map-section h3 {
    color: #2196f3;
    margin-bottom: 15px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.map-section h3 i {
    color: #2196f3;
}

/* Map Section */
.map-container {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    padding: 12px;
    height: calc(100% - 50px);
    display: flex;
    flex-direction: column;
}

.map-container iframe {
    flex: 1;
    min-height: 180px;
}

.map-info {
    margin-top: 10px;
    text-align: center;
}

.btn-map {
    display: inline-block;
    padding: 8px 16px;
    background: #4caf50;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.btn-map:hover {
    background: #388e3c;
    transform: translateY(-1px);
}

/* Footer Bottom */
.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    color: #666;
}

.footer-bottom p {
    margin: 15px 0 0 0;
    font-size: 0.8rem;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 15px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #2196f3;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 1rem;
}

.social-link:hover {
    background: #1976d2;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

/* Responsive */
@media (max-width: 1024px) {
    .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 35px;
    }
    
    .reviews-map-section {
        grid-column: 1 / -1;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 30px 0 15px;
        margin-top: 40px;
    }
    
    .footer-content {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .reviews-map-section {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-section h3 {
        font-size: 1rem;
        margin-bottom: 12px;
    }
    
    .contact-info p {
        font-size: 0.8rem;
    }
    
    .footer-links a {
        font-size: 0.8rem;
    }
    
    .social-link {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    .map-container iframe {
        min-height: 160px;
    }
}

@media (max-width: 480px) {
    .footer-content {
        gap: 20px;
    }
    
    .reviews-map-section {
        gap: 15px;
    }
    
    .footer-section h3 {
        font-size: 0.95rem;
    }
    
    .contact-info p {
        font-size: 0.75rem;
    }
    
    .footer-links a {
        font-size: 0.75rem;
    }
    
    .social-link {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .map-container iframe {
        min-height: 140px;
    }
}
</style> 