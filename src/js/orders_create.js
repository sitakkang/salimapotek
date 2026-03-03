// Order Create JS
let orderType = 'DINE_IN';
let cart = [];
let allItems = [];

$(document).ready(function() {
    loadItems();
    
    // Search items
    $('#searchItems').on('keyup', function() {
        let search = $(this).val().toLowerCase();
        displayItems(search);
    });
});

// Load all items
function loadItems() {
    $.ajax({
        url: site_url + 'orders/get_items',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                allItems = response.data;
                displayItems();
            }
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat data items', 'error');
        }
    });
}

// Display items in grid
function displayItems(search = '') {
    let html = '';
    let filteredItems = allItems.filter(item => {
        if(search === '') return true;
        return item.namaitem.toLowerCase().includes(search) || 
               item.kodeitem.toLowerCase().includes(search);
    });

    if(filteredItems.length === 0) {
        html = '<p class="text-muted text-center col-12">Tidak ada item ditemukan</p>';
    } else {
        filteredItems.forEach(item => {
            // Gunakan CSS background-image untuk menghindari onerror loop
            let imageStyle = '';
            if(item.itemimage && item.itemimage.trim() !== '') {
                imageStyle = `style="background-image: url('${site_url}uploads/items/${item.itemimage}');"`;
            }
            
            html += `
                <div class="item-card" onclick="addToCart(${item.id_item}, '${item.kodeitem}', '${item.namaitem}', ${item.hargasatuan})">
                    <div class="item-image" ${imageStyle}></div>
                    <div style="font-weight: bold; font-size: 12px;">${item.namaitem}</div>
                    <div style="color: #667eea; font-weight: bold;">Rp ${formatNumber(item.hargasatuan)}</div>
                </div>
            `;
        });
    }
    
    $('#itemsGrid').html(html);
}

// Select order type
function selectOrderType(type) {
    orderType = type;
    if(type === 'TAKEAWAY') {
        $('#tableSelection').hide();
        $('#tableId').val('');
    } else {
        $('#tableSelection').show();
    }
}

// Add item to cart
function addToCart(id, kode, nama, harga) {
    // Check if item already in cart
    let existingIndex = cart.findIndex(item => item.item_id === id);
    
    if(existingIndex >= 0) {
        // Increase quantity
        cart[existingIndex].quantity++;
        cart[existingIndex].subtotal = cart[existingIndex].quantity * cart[existingIndex].price;
    } else {
        // Add new item
        cart.push({
            item_id: id,
            kodeitem: kode,
            namaitem: nama,
            price: harga,
            quantity: 1,
            subtotal: harga,
            notes: ''
        });
    }
    
    updateCartDisplay();
}

// Update cart display
function updateCartDisplay() {
    if(cart.length === 0) {
        $('#cartItems').html('<p class="text-muted text-center">Belum ada item dipilih</p>');
        $('#totalAmount').text('Rp 0');
        return;
    }

    let html = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.subtotal;
        html += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div style="flex: 1;">
                        <strong>${item.namaitem}</strong><br>
                        <small class="text-muted">${item.kodeitem}</small><br>
                        <small>Rp ${formatNumber(item.price)}</small>
                    </div>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="qty-controls">
                        <button onclick="decreaseQty(${index})">-</button>
                        <input type="number" value="${item.quantity}" readonly style="width: 50px; text-align: center; border: 1px solid #ddd; border-radius: 5px;">
                        <button onclick="increaseQty(${index})">+</button>
                    </div>
                    <strong>Rp ${formatNumber(item.subtotal)}</strong>
                </div>
            </div>
        `;
    });

    $('#cartItems').html(html);
    $('#totalAmount').text('Rp ' + formatNumber(total));
}

// Increase quantity
function increaseQty(index) {
    cart[index].quantity++;
    cart[index].subtotal = cart[index].quantity * cart[index].price;
    updateCartDisplay();
}

// Decrease quantity
function decreaseQty(index) {
    if(cart[index].quantity > 1) {
        cart[index].quantity--;
        cart[index].subtotal = cart[index].quantity * cart[index].price;
        updateCartDisplay();
    } else {
        // qty = 1, hapus item otomatis
        cart.splice(index, 1);
        updateCartDisplay();
    }
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

// Submit order
function submitOrder() {
    // Validation
    if(cart.length === 0) {
        Swal.fire('Perhatian', 'Belum ada item dalam pesanan!', 'warning');
        return;
    }

    if(orderType === 'DINE_IN' && $('#tableId').val() === '') {
        Swal.fire('Perhatian', 'Pilih nomor meja untuk Dine In!', 'warning');
        return;
    }

    // Prepare data
    let formData = new FormData();
    formData.append('order_type', orderType);
    formData.append('table_id', $('#tableId').val());
    formData.append('customer_name', $('#customerName').val());
    formData.append('customer_phone', $('#customerPhone').val());
    formData.append('items', JSON.stringify(cart));
    formData.append('notes', $('#orderNotes').val());

    // Show loading
    Swal.fire({
        title: 'Menyimpan pesanan...',
        allowOutsideClick: false,
        onOpen: function() {
            Swal.showLoading();
        }
    });

    // Submit
    $.ajax({
        url: site_url + 'orders/save_order',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `Pesanan <strong>${response.order_number}</strong> telah dibuat`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = site_url + 'orders';
                });
            } else {
                Swal.fire('Gagal', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan pesanan', 'error');
        }
    });
}

// Format number
function formatNumber(num) {
    return parseFloat(num).toLocaleString('id-ID');
}
