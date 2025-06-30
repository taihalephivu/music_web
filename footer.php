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
                </div>
            </div>

            <!-- Đánh giá dịch vụ -->
            <div class="footer-section">
                <h3><i class="fas fa-star"></i> Đánh giá dịch vụ</h3>
                <div class="rating-section">
                    <div class="overall-rating">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="rating-text">4.8/5 từ 1,234 đánh giá</p>
                    </div>
                    <div class="rating-categories">
                        <div class="rating-item">
                            <span>Chất lượng sản phẩm:</span>
                            <div class="mini-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="rating-item">
                            <span>Dịch vụ giao hàng:</span>
                            <div class="mini-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <div class="rating-item">
                            <span>Hỗ trợ khách hàng:</span>
                            <div class="mini-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <button class="btn-rate" onclick="openRatingModal()">
                        <i class="fas fa-edit"></i> Đánh giá ngay
                    </button>
                </div>
            </div>

            <!-- Bản đồ chỉ dẫn -->
            <div class="footer-section">
                <h3><i class="fas fa-map"></i> Bản đồ chỉ dẫn</h3>
                <div class="map-container">
                    <div class="map-real">
                        <iframe
                            src="https://www.google.com/maps?q=123+Đường+ABC,+Quận+1,+TP.HCM&output=embed"
                            width="100%" height="250" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="map-info">
                            <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM</p>
                            <p><strong>Giờ mở cửa:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)</p>
                            <p><strong>Điện thoại:</strong> 0909 123 456</p>
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=123+Đường+ABC,+Quận+1,+TP.HCM" target="_blank" class="btn-map">
                            <i class="fas fa-directions"></i> Chỉ đường
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liên kết nhanh -->
            <div class="footer-section">
                <h3><i class="fas fa-link"></i> Liên kết nhanh</h3>
                <ul class="footer-links">
                    <li><a href="#about">Về chúng tôi</a></li>
                    <li><a href="#brands">Thương hiệu</a></li>
                    <li><a href="#categories">Danh mục</a></li>
                    <li><a href="user/profile.php">Tài khoản</a></li>
                    <li><a href="user/history.php">Lịch sử mua hàng</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <p>&copy; 2024 Music Store. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>

<!-- Rating Modal -->
<div id="ratingModal" class="rating-modal">
    <div class="rating-modal-content">
        <div class="rating-modal-header">
            <h3><i class="fas fa-star"></i> Đánh giá dịch vụ</h3>
            <button class="close-modal" onclick="closeRatingModal()">&times;</button>
        </div>
        <div class="rating-modal-body">
            <form id="ratingForm">
                <div class="rating-group">
                    <label>Chất lượng sản phẩm:</label>
                    <div class="star-rating">
                        <input type="radio" name="product_quality" value="5" id="pq5">
                        <label for="pq5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="4" id="pq4">
                        <label for="pq4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="3" id="pq3">
                        <label for="pq3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="2" id="pq2">
                        <label for="pq2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="1" id="pq1">
                        <label for="pq1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="rating-group">
                    <label>Dịch vụ giao hàng:</label>
                    <div class="star-rating">
                        <input type="radio" name="delivery_service" value="5" id="ds5">
                        <label for="ds5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="4" id="ds4">
                        <label for="ds4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="3" id="ds3">
                        <label for="ds3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="2" id="ds2">
                        <label for="ds2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="1" id="ds1">
                        <label for="ds1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="rating-group">
                    <label>Hỗ trợ khách hàng:</label>
                    <div class="star-rating">
                        <input type="radio" name="customer_service" value="5" id="cs5">
                        <label for="cs5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="4" id="cs4">
                        <label for="cs4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="3" id="cs3">
                        <label for="cs3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="2" id="cs2">
                        <label for="cs2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="1" id="cs1">
                        <label for="cs1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="rating-group">
                    <label for="rating_comment">Nhận xét (tùy chọn):</label>
                    <textarea id="rating_comment" name="comment" rows="3" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                </div>
                <div class="rating-actions">
                    <button type="submit" class="btn-submit">Gửi đánh giá</button>
                    <button type="button" class="btn-cancel" onclick="closeRatingModal()">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Footer Styles */
.footer {
    background: #f4f6fb;
    color: #111;
    padding: 60px 0 20px;
    margin-top: 80px;
    border-top: 1px solid #e0e0e0;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section h3 {
    color: #2196f3;
    margin-bottom: 20px;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-section h3 i {
    color: #2196f3;
}

.contact-info p {
    margin: 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.contact-info i {
    color: #2196f3;
    width: 16px;
}

/* Rating Section */
.rating-section {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.overall-rating {
    text-align: center;
    margin-bottom: 20px;
}

.stars {
    color: #ffd600;
    font-size: 1.5rem;
    margin-bottom: 8px;
}

.rating-text {
    color: #666;
    font-weight: 600;
}

.rating-categories {
    margin-bottom: 20px;
}

.rating-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 8px 0;
    font-size: 0.9rem;
}

.mini-stars {
    color: #ffd600;
    font-size: 0.8rem;
}

.btn-rate {
    width: 100%;
    padding: 10px;
    background: #2196f3;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-rate:hover {
    background: #1976d2;
}

/* Map Section */
.map-container {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
}

.map-real {
    padding: 20px;
    text-align: center;
    color: #666;
}

.map-real iframe {
    width: 100%;
    height: 250px;
    border: 0;
    border-radius: 12px;
}

.map-info {
    text-align: left;
    margin: 15px 0;
}

.map-info p {
    margin: 5px 0;
    font-size: 0.9rem;
}

.btn-map {
    display: inline-block;
    padding: 8px 16px;
    background: #4caf50;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-map:hover {
    background: #388e3c;
}

/* Footer Links */
.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin: 8px 0;
}

.footer-links a {
    color: #666;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-links a:hover {
    color: #2196f3;
}

.social-links {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #2196f3;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    transition: background 0.2s;
}

.social-link:hover {
    background: #1976d2;
}

/* Footer Bottom */
.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    color: #666;
}

/* Rating Modal */
.rating-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.rating-modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.rating-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.rating-modal-header h3 {
    color: #2196f3;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.rating-modal-body {
    padding: 20px;
}

.rating-group {
    margin-bottom: 20px;
}

.rating-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #111;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 4px;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.2rem;
    transition: color 0.2s;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffd600;
}

textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    resize: vertical;
    font-family: inherit;
}

.rating-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-submit, .btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-submit {
    background: #2196f3;
    color: #fff;
}

.btn-submit:hover {
    background: #1976d2;
}

.btn-cancel {
    background: #e0e0e0;
    color: #111;
}

.btn-cancel:hover {
    background: #d0d0d0;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .rating-modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    .rating-actions {
        flex-direction: column;
    }
}
</style>

<script>
function openRatingModal() {
    document.getElementById('ratingModal').style.display = 'block';
}

function closeRatingModal() {
    document.getElementById('ratingModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('ratingModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Handle rating form submission
document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const ratingData = {
        product_quality: formData.get('product_quality'),
        delivery_service: formData.get('delivery_service'),
        customer_service: formData.get('customer_service'),
        comment: formData.get('comment')
    };
    
    // Here you would typically send the data to your server
    console.log('Rating submitted:', ratingData);
    
    // Show success message
    alert('Cảm ơn bạn đã đánh giá! Đánh giá của bạn đã được ghi nhận.');
    
    // Close modal and reset form
    closeRatingModal();
    this.reset();
});
</script> 