<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Summer Sale</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
<style>
.summer-banner {
  background: linear-gradient(90deg, #fceabb 0%, #f8b500 100%);
  color: #fff;
  text-align: center;
  padding: 40px 10px 20px 10px;
  border-radius: 16px;
  margin: 30px auto 20px auto;
  max-width: 900px;
  position: relative;
  box-shadow: 0 4px 24px rgba(248,181,0,0.15);
}
.summer-banner h1 {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  letter-spacing: 2px;
}
.summer-banner .countdown {
  font-size: 1.3rem;
  font-weight: 500;
  background: rgba(255,255,255,0.2);
  display: inline-block;
  padding: 8px 24px;
  border-radius: 8px;
  margin-top: 10px;
}
.summer-products {
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
  justify-content: center;
  margin: 40px auto 0 auto;
  max-width: 1100px;
}
.summer-product {
  background: #fffbe6;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(248,181,0,0.08);
  width: 240px;
  padding: 18px 14px 20px 14px;
  position: relative;
  text-align: center;
  transition: transform 0.2s;
}
.summer-product:hover {
  transform: translateY(-6px) scale(1.03);
}
.summer-product img {
  width: 120px;
  height: 120px;
  object-fit: contain;
  margin-bottom: 10px;
}
.summer-sale-label {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #ff5252;
  color: #fff;
  font-size: 0.95rem;
  font-weight: bold;
  padding: 4px 12px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(255,82,82,0.12);
}
.summer-product .old-price {
  text-decoration: line-through;
  color: #bdbdbd;
  font-size: 1rem;
  margin-right: 8px;
}
.summer-product .new-price {
  color: #f8b500;
  font-size: 1.3rem;
  font-weight: bold;
}
.summer-product .product-name {
  font-size: 1.1rem;
  font-weight: 500;
  margin-bottom: 6px;
}
.summer-product .brand {
  color: #888;
  font-size: 0.95rem;
  margin-bottom: 10px;
}
</style>

<div class="summer-banner">
  <h1>Summer Sale – Giảm giá lên đến 50%!</h1>
  <div class="countdown" id="countdown">Còn lại: -- ngày --:--:--</div>
  <div style="margin-top:10px;font-size:1.1rem;">Nhanh tay mua sắm các sản phẩm âm nhạc hot nhất mùa hè này!</div>
</div>

<!-- Voucher Section (moved up) -->
<div class="voucher-section" style="max-width:500px;margin:32px auto 0 auto;padding:32px 24px;background:#fffbe6;border-radius:16px;box-shadow:0 2px 12px #f8b50022;text-align:center;">
  <h2 style="color:#f8b500;font-size:1.4rem;font-weight:bold;margin-bottom:18px;">Nhập mã giảm giá Summer Sale</h2>
  <input id="voucherInput" type="text" placeholder="Nhập mã (ví dụ: SUMMER2024)" style="padding:10px 18px;border-radius:8px;border:1px solid #f8b500;font-size:1.1rem;width:70%;margin-bottom:12px;">
  <br>
  <button onclick="window.checkVoucher()" style="background:#f8b500;color:#fff;font-weight:bold;padding:10px 28px;border:none;border-radius:8px;font-size:1.1rem;cursor:pointer;">Áp dụng</button>
  <div id="voucherMsg" style="margin-top:16px;font-size:1.1rem;"></div>
</div>

<div class="summer-products">
  <!-- Sản phẩm mẫu, bạn có thể thêm nhiều sản phẩm khác -->
  <div class="summer-product">
    <span class="summer-sale-label">Sale</span>
    <img src="assets/images/brands/yamaha.jpg" alt="Yamaha Guitar">
    <div class="product-name">Đàn Guitar Yamaha F310</div>
    <div class="brand">Yamaha</div>
    <span class="old-price">3.000.000đ</span>
    <span class="new-price">1.990.000đ</span>
  </div>
  <div class="summer-product">
    <span class="summer-sale-label">Sale</span>
    <img src="assets/images/brands/roland.png" alt="Roland Piano">
    <div class="product-name">Piano Roland FP-10</div>
    <div class="brand">Roland</div>
    <span class="old-price">15.000.000đ</span>
    <span class="new-price">11.500.000đ</span>
  </div>
  <div class="summer-product">
    <span class="summer-sale-label">Sale</span>
    <img src="assets/images/brands/pearl.jpg" alt="Pearl Drum">
    <div class="product-name">Trống Jazz Pearl Roadshow</div>
    <div class="brand">Pearl</div>
    <span class="old-price">12.000.000đ</span>
    <span class="new-price">8.900.000đ</span>
  </div>
  <div class="summer-product">
    <span class="summer-sale-label">Sale</span>
    <img src="assets/images/brands/fender.png" alt="Fender Ukulele">
    <div class="product-name">Ukulele Fender Venice</div>
    <div class="brand">Fender</div>
    <span class="old-price">1.200.000đ</span>
    <span class="new-price">790.000đ</span>
  </div>
</div>

<!-- Combo Ưu Đãi Mùa Hè -->
<div class="summer-combo-section" style="max-width:1100px;margin:48px auto 0 auto;">
  <h2 style="color:#f8b500;font-size:2rem;font-weight:bold;text-align:center;margin-bottom:24px;letter-spacing:1px;">Combo Ưu Đãi Mùa Hè</h2>
  <div class="summer-combos" style="display:flex;flex-wrap:wrap;gap:32px;justify-content:center;">
    <div class="summer-combo" style="background:#fffbe6;border-radius:14px;box-shadow:0 2px 12px rgba(248,181,0,0.08);width:340px;padding:22px 18px 24px 18px;position:relative;text-align:center;">
      <span style="position:absolute;top:16px;left:16px;background:#ff9800;color:#fff;font-size:1rem;font-weight:bold;padding:5px 16px;border-radius:10px;box-shadow:0 2px 8px #ff980033;">Combo Hot</span>
      <img src="assets/images/brands/yamaha.jpg" alt="Combo Guitar" style="width:120px;height:120px;object-fit:contain;margin-bottom:10px;">
      <div style="font-size:1.15rem;font-weight:600;margin-bottom:6px;">Guitar Yamaha F310 + Bao đàn + Capo</div>
      <div style="color:#888;font-size:1rem;margin-bottom:10px;">Combo lý tưởng cho người mới bắt đầu</div>
      <span style="text-decoration:line-through;color:#bdbdbd;font-size:1.05rem;margin-right:10px;">3.400.000đ</span>
      <span style="color:#f8b500;font-size:1.35rem;font-weight:bold;">2.390.000đ</span>
    </div>
    <div class="summer-combo" style="background:#fffbe6;border-radius:14px;box-shadow:0 2px 12px rgba(248,181,0,0.08);width:340px;padding:22px 18px 24px 18px;position:relative;text-align:center;">
      <span style="position:absolute;top:16px;left:16px;background:#ff9800;color:#fff;font-size:1rem;font-weight:bold;padding:5px 16px;border-radius:10px;box-shadow:0 2px 8px #ff980033;">Combo Hot</span>
      <img src="assets/images/brands/roland.png" alt="Combo Piano" style="width:120px;height:120px;object-fit:contain;margin-bottom:10px;">
      <div style="font-size:1.15rem;font-weight:600;margin-bottom:6px;">Piano Roland FP-10 + Ghế + Tai nghe</div>
      <div style="color:#888;font-size:1rem;margin-bottom:10px;">Combo tiết kiệm cho người yêu piano</div>
      <span style="text-decoration:line-through;color:#bdbdbd;font-size:1.05rem;margin-right:10px;">16.200.000đ</span>
      <span style="color:#f8b500;font-size:1.35rem;font-weight:bold;">12.500.000đ</span>
    </div>
  </div>
</div>

<script>
// Đếm ngược đến hết ngày 31/07/2024
const endDate = new Date('2024-07-31T23:59:59').getTime();
function updateCountdown() {
  const now = new Date().getTime();
  let distance = endDate - now;
  if (distance < 0) distance = 0;
  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);
  document.getElementById('countdown').innerText = `Còn lại: ${days} ngày ${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
}
setInterval(updateCountdown, 1000);
updateCountdown();
</script>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

<!-- Mini Game Popup (vòng quay animation) -->
<div id="miniGamePopup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.35);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fffbe6;padding:36px 32px 28px 32px;border-radius:18px;box-shadow:0 4px 32px #f8b50033;text-align:center;max-width:400px;margin:auto;">
    <h2 style="color:#f8b500;font-size:1.5rem;font-weight:bold;margin-bottom:18px;">Vòng quay may mắn!</h2>
    <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;">
      <div id="wheel-container" style="position:relative;width:220px;height:220px;margin-bottom:18px;">
        <canvas id="wheelCanvas" width="220" height="220"></canvas>
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2;">
          <img src="https://cdn-icons-png.flaticon.com/512/616/616490.png" alt="pointer" style="width:38px;height:38px;transform:rotate(-90deg);">
        </div>
      </div>
      <div id="gameResult" style="font-size:1.2rem;font-weight:500;margin-bottom:10px;min-height:32px;">Chúc bạn may mắn!</div>
      <button onclick="window.spinWheel()" id="spinBtn" style="background:#f8b500;color:#fff;font-weight:bold;padding:10px 28px;border:none;border-radius:8px;font-size:1.1rem;cursor:pointer;">Quay số</button>
      <br>
      <button onclick="window.closeMiniGame()" style="background:#ccc;color:#333;padding:8px 18px;border:none;border-radius:8px;font-size:1rem;cursor:pointer;">Đóng</button>
    </div>
  </div>
</div>

<script>
// Vòng quay may mắn
const prizes = [
  'Chúc bạn may mắn lần sau!',
  'Voucher 50k',
  'Voucher 100k',
  '1 bộ dây đàn miễn phí',
  'Voucher 20k',
  '1 móc khóa nhạc cụ',
  'Voucher 10% cho đơn tiếp theo!'
];
const colors = ['#FFD700','#FF9800','#FF5252','#4CAF50','#2196F3','#AB47BC','#F8B500'];
let wheelAngle = 0;
let spinning = false;

function drawWheel() {
  const canvas = document.getElementById('wheelCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const n = prizes.length;
  const radius = 110;
  ctx.clearRect(0,0,220,220);
  for(let i=0;i<n;i++) {
    ctx.beginPath();
    ctx.moveTo(110,110);
    ctx.arc(110,110,radius,i*2*Math.PI/n,(i+1)*2*Math.PI/n);
    ctx.closePath();
    ctx.fillStyle = colors[i%colors.length];
    ctx.fill();
    ctx.save();
    ctx.translate(110,110);
    ctx.rotate((i+0.5)*2*Math.PI/n);
    ctx.textAlign = 'right';
    ctx.font = 'bold 15px Arial';
    ctx.fillStyle = '#fff';
    ctx.fillText(prizes[i], radius-10,6);
    ctx.restore();
  }
  // Vẽ viền
  ctx.beginPath();
  ctx.arc(110,110,radius,0,2*Math.PI);
  ctx.lineWidth = 4;
  ctx.strokeStyle = '#fff';
  ctx.stroke();
}

window.checkVoucher = function() {
  var code = document.getElementById('voucherInput').value.trim().toUpperCase();
  var msg = document.getElementById('voucherMsg');
  if(code === 'SUMMER2024') {
    msg.innerHTML = '<span style="color:#43a047;font-weight:bold;">Áp dụng mã thành công! Bạn được giảm thêm 5% và có cơ hội quay số trúng thưởng!</span><br><button onclick="window.showMiniGame()" style="margin-top:12px;background:#f8b500;color:#fff;font-weight:bold;padding:8px 22px;border:none;border-radius:8px;font-size:1rem;cursor:pointer;">Quay số ngay</button>';
  } else {
    msg.innerHTML = '<span style="color:#e53935;font-weight:bold;">Mã không hợp lệ hoặc đã hết hạn!</span>';
  }
}

window.showMiniGame = function() {
  document.getElementById('miniGamePopup').style.display = 'flex';
  document.getElementById('gameResult').innerText = 'Chúc bạn may mắn!';
  document.getElementById('spinBtn').disabled = false;
  document.getElementById('spinBtn').innerText = 'Quay số';
  wheelAngle = 0;
  drawWheel();
  document.getElementById('wheelCanvas').style.transform = 'rotate(0deg)';
}

window.spinWheel = function() {
  if (spinning) return;
  spinning = true;
  document.getElementById('spinBtn').disabled = true;
  document.getElementById('spinBtn').innerText = 'Đang quay...';
  const n = prizes.length;
  const prizeIdx = Math.floor(Math.random()*n);
  const extraRounds = 5;
  const targetAngle = 360*extraRounds + (360/n)*prizeIdx + (360/n)/2;
  let start = null;
  let duration = 3500; // ms
  function animateWheel(ts) {
    if (!start) start = ts;
    let elapsed = ts - start;
    let progress = Math.min(elapsed/duration,1);
    let angle = wheelAngle + (targetAngle - wheelAngle)*easeOutCubic(progress);
    document.getElementById('wheelCanvas').style.transform = `rotate(${-angle}deg)`;
    if (progress < 1) {
      requestAnimationFrame(animateWheel);
    } else {
      spinning = false;
      document.getElementById('spinBtn').innerText = 'Đã quay';
      document.getElementById('gameResult').innerText = 'Bạn nhận được: ' + prizes[prizeIdx];
    }
  }
  requestAnimationFrame(animateWheel);
}

window.closeMiniGame = function() {
  document.getElementById('miniGamePopup').style.display = 'none';
}

function easeOutCubic(t) { return 1- Math.pow(1-t,3); }
drawWheel();
</script> 