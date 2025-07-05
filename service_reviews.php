<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xử lý POST request cho đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $product_quality = intval($input['product_quality'] ?? 0);
    $delivery_service = intval($input['delivery_service'] ?? 0);
    $customer_service = intval($input['customer_service'] ?? 0);
    $comment = trim($input['comment'] ?? '');
    
    // Validate ratings
    if ($product_quality < 1 || $product_quality > 5 ||
        $delivery_service < 1 || $delivery_service > 5 ||
        $customer_service < 1 || $customer_service > 5) {
        echo json_encode(['success' => false, 'message' => 'Đánh giá phải từ 1-5 sao']);
        exit;
    }
    
    $user_id = null;
    if (isset($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];
    }
    
    try {
        require_once 'config/database.php';
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare('INSERT INTO service_reviews (user_id, product_quality, delivery_service, customer_service, comment) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $product_quality, $delivery_service, $customer_service, $comment]);
        
        echo json_encode(['success' => true, 'message' => 'Cảm ơn bạn đã đánh giá!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
    }
    exit;
}

// Lấy dữ liệu đánh giá từ database
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Tính toán đánh giá trung bình
$stmt = $conn->query('SELECT 
    COUNT(*) as total_reviews,
    AVG(product_quality) as avg_product_quality,
    AVG(delivery_service) as avg_delivery_service,
    AVG(customer_service) as avg_customer_service,
    AVG((product_quality + delivery_service + customer_service) / 3) as overall_rating
FROM service_reviews');
$rating_data = $stmt->fetch(PDO::FETCH_ASSOC);

$total_reviews = $rating_data['total_reviews'] ?? 0;
$overall_rating = round($rating_data['overall_rating'] ?? 4.5, 1);
$avg_product_quality = round($rating_data['avg_product_quality'] ?? 4.5, 1);
$avg_delivery_service = round($rating_data['avg_delivery_service'] ?? 4.5, 1);
$avg_customer_service = round($rating_data['avg_customer_service'] ?? 4.5, 1);

// Hàm hiển thị sao
function displayStars($rating) {
    $full_stars = floor($rating);
    $has_half = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($has_half ? 1 : 0);
    
    $stars = str_repeat('<i class="fas fa-star"></i>', $full_stars);
    if ($has_half) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    $stars .= str_repeat('<i class="far fa-star"></i>', $empty_stars);
    
    return $stars;
}
?>

<!-- Đánh giá dịch vụ -->
<div class="footer-section">
    <h3><i class="fas fa-star"></i> Đánh giá dịch vụ</h3>
    <div class="rating-section">
        <div class="overall-rating">
            <div class="stars">
                <?php echo displayStars($overall_rating); ?>
            </div>
            <p class="rating-text"><?php echo $overall_rating; ?>/5 từ <?php echo number_format($total_reviews); ?> đánh giá</p>
        </div>
        <div class="rating-categories">
            <div class="rating-item">
                <span>Chất lượng sản phẩm:</span>
                <div class="mini-stars">
                    <?php echo displayStars($avg_product_quality); ?>
                </div>
            </div>
            <div class="rating-item">
                <span>Dịch vụ giao hàng:</span>
                <div class="mini-stars">
                    <?php echo displayStars($avg_delivery_service); ?>
                </div>
            </div>
            <div class="rating-item">
                <span>Hỗ trợ khách hàng:</span>
                <div class="mini-stars">
                    <?php echo displayStars($avg_customer_service); ?>
                </div>
            </div>
        </div>
        <button class="btn-rate" onclick="openRatingModal()">
            <i class="fas fa-edit"></i> Đánh giá ngay
        </button>
    </div>
</div>

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
                        <input type="radio" name="product_quality" value="5" id="pq5"><label for="pq5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="4" id="pq4"><label for="pq4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="3" id="pq3"><label for="pq3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="2" id="pq2"><label for="pq2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="product_quality" value="1" id="pq1"><label for="pq1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="rating-group">
                    <label>Dịch vụ giao hàng:</label>
                    <div class="star-rating">
                        <input type="radio" name="delivery_service" value="5" id="ds5"><label for="ds5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="4" id="ds4"><label for="ds4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="3" id="ds3"><label for="ds3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="2" id="ds2"><label for="ds2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="delivery_service" value="1" id="ds1"><label for="ds1"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <div class="rating-group">
                    <label>Hỗ trợ khách hàng:</label>
                    <div class="star-rating">
                        <input type="radio" name="customer_service" value="5" id="cs5"><label for="cs5"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="4" id="cs4"><label for="cs4"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="3" id="cs3"><label for="cs3"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="2" id="cs2"><label for="cs2"><i class="fas fa-star"></i></label>
                        <input type="radio" name="customer_service" value="1" id="cs1"><label for="cs1"><i class="fas fa-star"></i></label>
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
                <div id="serviceReviewMsg" style="margin-top:10px;color:#2196f3;font-weight:500;"></div>
            </form>
        </div>
    </div>
</div>

<style>
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
    
    // Validate required fields
    if (!ratingData.product_quality || !ratingData.delivery_service || !ratingData.customer_service) {
        alert('Vui lòng đánh giá đầy đủ các tiêu chí!');
        return;
    }
    
    // Send to server
    fetch('service_reviews.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(ratingData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeRatingModal();
            this.reset();
            // Reload page to update ratings
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    })
    .catch(error => {
        alert('Lỗi kết nối, vui lòng thử lại!');
    });
});
</script> 