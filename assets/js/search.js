// Hiển thị danh sách dụng cụ âm nhạc
function displayInstruments(instrumentsToShow) {
    const grid = document.getElementById('musicGrid');
    if (!grid) return;
    if (instrumentsToShow.length === 0) {
        grid.innerHTML = `
            <div style="color: white; text-align: center; grid-column: 1 / -1; padding: 2rem;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Không tìm thấy dụng cụ âm nhạc nào</p>
            </div>
        `;
        return;
    }
    grid.innerHTML = instrumentsToShow.map(instrument => `
        <div class="instrument-card">
            <img src="${instrument.image_url || 'https://via.placeholder.com/300x200/667eea/ffffff?text=Music+Instrument'}" 
                 alt="${instrument.name}" 
                 class="instrument-image"
                 onerror="this.src='https://via.placeholder.com/300x200/667eea/ffffff?text=Music+Instrument'">
            <div class="instrument-info">
                <h3 class="instrument-title">${instrument.name}</h3>
                <p class="instrument-brand">${instrument.brand_name || 'Thương hiệu'}</p>
                <p class="instrument-category">${instrument.category_name || 'Danh mục'}</p>
                <p class="instrument-price">${instrument.formatted_price}</p>
                <p class="instrument-stock">
                    <i class="fas fa-box"></i> 
                    Còn lại: ${instrument.stock_quantity} sản phẩm
                </p>
                <div class="instrument-actions">
                    <button class="btn-add-cart" onclick="window.addToCart(${instrument.id})">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                    <button class="btn-details" onclick="showInstrumentDetails(${instrument.id})">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Xử lý tìm kiếm
function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase().trim();
    if (searchTerm === '') {
        window.filteredInstruments = [...window.instruments];
    } else {
        window.filteredInstruments = window.instruments.filter(instrument => 
            instrument.name.toLowerCase().includes(searchTerm) ||
            (instrument.brand_name && instrument.brand_name.toLowerCase().includes(searchTerm)) ||
            (instrument.category_name && instrument.category_name.toLowerCase().includes(searchTerm)) ||
            (instrument.description && instrument.description.toLowerCase().includes(searchTerm))
        );
    }
    displayInstruments(window.filteredInstruments);
}

window.displayInstruments = displayInstruments;
window.handleSearch = handleSearch; 