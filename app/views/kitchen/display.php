<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .kitchen-container {
        padding: 20px;
        max-width: 1800px;
        margin: 0 auto;
    }
    .kitchen-header {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        color: white;
        padding: 30px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        border-radius: 20px;
        margin-bottom: 30px;
    }
    .kitchen-header h2 {
        margin: 0;
        font-size: 42px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .kitchen-header p {
        margin: 10px 0 0 0;
        font-size: 18px;
        opacity: 0.95;
    }
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
    }
    .order-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        overflow: hidden;
        transition: all 0.3s;
    }
    .order-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
    }
    .order-card-header {
        padding: 20px;
        color: white;
        font-weight: bold;
    }
    .order-card-header.PROCESSING {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.85; }
    }
    .order-card-body {
        padding: 20px;
        background: #fafafa;
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        font-size: 15px;
        transition: all 0.2s;
    }
    .order-item:hover {
        transform: translateX(5px);
        box-shadow: 0 3px 12px rgba(0,0,0,0.15);
    }
    .order-item:last-child {
        margin-bottom: 0;
    }
    .order-footer {
        padding: 20px;
        background: white;
        text-align: center;
    }
    .action-btn {
        margin: 5px;
        padding: 15px 30px;
        border: none;
        border-radius: 12px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .action-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    .action-btn:active {
        transform: scale(0.95);
    }
    .btn-preparing {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    .btn-ready {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
    }
    .time-badge {
        background: rgba(0,0,0,0.25);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        margin-left: 10px;
    }
    .stats-bar {
        background: white;
        padding: 25px;
        margin: 0 0 30px 0;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        display: flex;
        justify-content: space-around;
    }
    .stat-item {
        text-align: center;
        padding: 15px 40px;
        border-radius: 15px;
        transition: all 0.3s;
    }
    .stat-item:hover {
        background: #f8f9fa;
        transform: scale(1.05);
    }
    .stat-number {
        font-size: 48px;
        font-weight: bold;
        color: #FF6B6B;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .stat-label {
        font-size: 16px;
        color: #666;
        font-weight: 600;
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .empty-state {
        background: white;
        padding: 80px;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
</style>

<div class="kitchen-container">
    <div class="kitchen-header">
        <h2><i class="fa fa-fire"></i> KITCHEN DISPLAY SYSTEM</h2>
        <p class="mb-0">Monitor & Update Status Pesanan Secara Real-Time</p>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-number" id="statProcessing" style="color: #17a2b8;">0</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>

    <div class="orders-grid" id="ordersGrid">
        <!-- Orders will be loaded here -->
    </div>
</div>

<script>
let refreshInterval;

$(document).ready(function() {
    loadOrders();
    
    // Auto refresh every 10 seconds
    refreshInterval = setInterval(loadOrders, 10000);
});

function loadOrders() {
    $.ajax({
        url: site_url + 'kitchen/get_pending_orders',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Kitchen Response:', response);
            console.log('Orders Count:', response.count);
            console.log('Orders Data:', response.data);
            
            if(response.success) {
                displayOrders(response.data);
                updateStats(response.data);
            } else {
                console.error('Failed to load orders:', response.message);
                console.error('Debug Query:', response.debug_query);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading orders:', error);
            console.error('Response:', xhr.responseText);
            // Show error in grid
            $('#ordersGrid').html(`
                <div class="empty-state" style="grid-column: 1/-1; text-align: center;">
                    <i class="fa fa-exclamation-triangle" style="font-size: 80px; color: #dc3545; margin-bottom: 20px; display: block;"></i>
                    <h2 style="color: #666; margin: 0;">Gagal Memuat Data</h2>
                    <p style="color: #999; margin-top: 10px; font-size: 18px;">Silakan refresh halaman atau hubungi administrator</p>
                </div>
            `);
        }
    });
}

function displayOrders(orders) {
    if(orders.length === 0) {
        $('#ordersGrid').html(`
            <div class="empty-state" style="grid-column: 1/-1; text-align: center;">
                <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745; margin-bottom: 20px; display: block;"></i>
                <h2 style="color: #666; margin: 0;">Semua Pesanan Sudah Selesai!</h2>
                <p style="color: #999; margin-top: 10px; font-size: 18px;">Tidak ada pesanan yang perlu diproses saat ini</p>
            </div>
        `);
        return;
    }

    let html = '';
    orders.forEach(order => {
        let timeAgo = getTimeAgo(order.created_at);
        let tableInfo = order.order_type === 'DINE_IN' ? 
            `<i class="fa fa-utensils"></i> Meja ${order.table_number}` : 
            `<i class="fa fa-shopping-bag"></i> Takeaway`;
        
        html += `
            <div class="order-card">
                <div class="order-card-header ${order.status}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 22px; font-weight: bold;">${order.order_number}</div>
                            <div style="font-size: 15px; margin-top: 5px;">${tableInfo}</div>
                        </div>
                        <span class="time-badge"><i class="fa fa-clock"></i> ${timeAgo}</span>
                    </div>
                </div>
                <div class="order-card-body">
        `;
        
        // Display items
        order.items.forEach(item => {
            html += `
                <div class="order-item">
                    <div>
                        <strong style="font-size: 16px; color: #333;">${item.namaitem}</strong>
                        ${item.notes ? `<br><small style="color: #FF6B6B; font-weight: 600;"><i class="fa fa-comment-dots"></i> ${item.notes}</small>` : ''}
                    </div>
                    <div style="font-size: 24px; font-weight: bold; color: #667eea;">
                        ×${item.quantity}
                    </div>
                </div>
            `;
        });
        
        html += `
                </div>
                <div class="order-footer">
        `;
        
        // Action buttons based on status
        if(order.status === 'PROCESSING') {
            html += `
                <button class="action-btn btn-ready" onclick="markReadyForPayment(${order.order_id})">
                    <i class="fa fa-check"></i> Siap Disajikan
                </button>
            `;
        }
        
        html += `
                </div>
            </div>
        `;
    });
    
    $('#ordersGrid').html(html);
}

function updateStats(orders) {
    let processing = orders.filter(o => o.status === 'PROCESSING').length;
    $('#statProcessing').text(processing);
}

function markReadyForPayment(orderId) {
    // Hanya notifikasi visual, order tetap PROCESSING sampai kasir bayar
    Swal.fire({
        title: 'Pesanan Siap!',
        text: 'Pesanan siap disajikan ke pelanggan.',
        type: 'success',
        showConfirmButton: true,
        confirmButtonText: 'OK'
    });
}

function updateOrderStatus(orderId, status) {
    // Show loading
    Swal.fire({
        title: 'Memproses...',
        text: 'Mengupdate status pesanan',
        allowOutsideClick: false,
        onOpen: function() {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: site_url + 'kitchen/update_status',
        type: 'POST',
        data: {
            order_id: orderId,
            status: status
        },
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                // Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Play notification sound (optional)
                // new Audio(site_url + 'assets/notification.mp3').play();
                
                // Reload orders
                loadOrders();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengupdate status: ' + error
            });
        }
    });
}

function getTimeAgo(datetime) {
    let now = new Date();
    let created = new Date(datetime);
    let diff = Math.floor((now - created) / 1000); // seconds
    
    if(diff < 60) return diff + ' detik lalu';
    if(diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
    if(diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
    return Math.floor(diff / 86400) + ' hari lalu';
}
</script>