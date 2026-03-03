// ============================================
// Orders List - JavaScript
// loaded after jQuery via $data['js']
// ============================================

let ordersData = [];

// Label bahasa Indonesia untuk status
function statusLabel(status) {
    var labels = {
        'PROCESSING': 'Sedang Diproses',
        'PAID':       'Sudah Bayar',
        'CANCEL':     'Batal'
    };
    return labels[status] || status;
}

function goToPayment(orderId) {
    sessionStorage.setItem('pos_load_order_id', orderId);
    window.location.href = site_url + 'pos';
}

$(document).ready(function () {
    loadOrders();
});

function loadOrders() {
    let status = $('#statusFilter').val();

    $.ajax({
        url: site_url + 'orders/get_orders',
        type: 'GET',
        data: { status: status },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                displayOrders(response.data);
            }
        },
        error: function () {
            $('#ordersTableBody').html('<tr><td colspan="10" class="text-center text-danger">Gagal memuat data pesanan</td></tr>');
        }
    });
}

function displayOrders(orders) {
    ordersData = orders;
    let html = '';

    if (orders.length === 0) {
        html = '<tr><td colspan="10" class="text-center text-muted">Tidak ada data</td></tr>';
    } else {
        orders.forEach(function (order, index) {
            let tableInfo = order.order_type === 'DINE_IN'
                ? 'Meja ' + (order.table_number || '-')
                : '-';

            let customerInfo = order.customer_name || '-';

            let actionBtns = `
                <button class="btn btn-sm btn-info" onclick="viewDetail(${order.order_id})" title="Lihat Detail">
                    <i class="fa fa-eye"></i>
                </button>`;

            if (order.status === 'PROCESSING') {
                actionBtns += `
                <button class="btn btn-sm btn-success" onclick="goToPayment(${order.order_id})" title="Bayar">
                    <i class="fa fa-money-bill"></i> Bayar
                </button>`;
            }

            if (['PROCESSING'].indexOf(order.status) !== -1) {
                actionBtns += `
                <button class="btn btn-sm btn-danger" onclick="cancelOrder(${order.order_id}, '${order.order_number}')" title="Batalkan Pesanan">
                    <i class="fa fa-times"></i> Batal
                </button>`;
            }

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td class="text-center"><strong>${order.order_number}</strong></td>
                    <td class="text-center">${formatDateTime(order.created_at)}</td>
                    <td class="text-center"><span class="badge badge-${order.order_type === 'DINE_IN' ? 'primary' : 'success'}">${order.order_type}</span></td>
                    <td class="text-center">${tableInfo}</td>
                    <td>${customerInfo}</td>
                    <td class="text-right">${order.total_items}</td>
                    <td class="text-right">Rp ${formatNumber(order.total_amount)}</td>
                    <td class="text-center"><span class="status-badge status-${order.status}">${statusLabel(order.status)}</span></td>
                    <td class="text-center">${actionBtns}</td>
                </tr>`;
        });
    }

    $('#ordersTableBody').html(html);
}

function viewDetail(orderId) {
    $('#orderDetailContent').html('<div class="text-center p-3"><i class="fa fa-spinner fa-spin"></i> Memuat...</div>');
    $('#orderDetailModal').modal('show');

    $.ajax({
        url: site_url + 'orders/get_order_detail/' + orderId,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                displayOrderDetail(response.order, response.items);
            } else {
                $('#orderDetailContent').html('<div class="text-danger">Gagal memuat detail pesanan.</div>');
            }
        },
        error: function () {
            $('#orderDetailContent').html('<div class="text-danger">Gagal menghubungi server.</div>');
        }
    });
}

function displayOrderDetail(order, items) {
    function row(label, value) {
        return `<tr>
            <td style="width:140px; font-weight:600; color:#555; white-space:nowrap;">${label}</td>
            <td style="width:10px; color:#555; padding-left:0; padding-right:6px;">:</td>
            <td>${value}</td>
        </tr>`;
    }

    let html = `
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0" style="font-size:14px;">
                    ${row('No. Order', '<strong>' + order.order_number + '</strong>')}
                    ${row('Tanggal', formatDateTime(order.created_at))}
                    ${row('Tipe', order.order_type === 'DINE_IN' ? '<span class="badge badge-primary">Dine In</span>' : '<span class="badge badge-success">Takeaway</span>')}
                    ${row('Pelanggan', order.customer_name || '-')}
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0" style="font-size:14px;">
                    ${row('Meja', order.order_type === 'DINE_IN' ? 'Meja ' + (order.table_number || '-') : '-')}
                    ${row('Status', '<span class="status-badge status-' + order.status + '">' + statusLabel(order.status) + '</span>')}
                    ${row('Total Item', order.total_items + ' item')}
                    ${row('Total', '<strong>Rp ' + formatNumber(order.total_amount) + '</strong>')}
                </table>
            </div>
        </div>
        <h6 class="mb-2"><i class="fa fa-list"></i> Detail Item:</h6>
        <table class="table table-sm table-bordered">
            <thead class="thead-light"><tr><th>No</th><th>Nama Item</th><th>Harga</th><th>Qty</th><th class="text-right">Subtotal</th></tr></thead>
            <tbody>`;

    items.forEach(function (item, index) {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.namaitem}</td>
                <td class="text-right">Rp ${formatNumber(item.price)}</td>
                <td class="text-right">${item.quantity}</td>
                <td class="text-right">Rp ${formatNumber(item.subtotal)}</td>
            </tr>`;
    });

    html += `</tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right font-weight-bold">Total</td>
                    <td class="text-right font-weight-bold">Rp ${formatNumber(order.total_amount)}</td>
                </tr>
            </tfoot>
        </table>`;

    $('#orderDetailContent').html(html);
}

function cancelOrder(orderId, orderNumber) {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        html: 'Pesanan <strong>' + (orderNumber || '#' + orderId) + '</strong> akan dibatalkan.<br><small class="text-muted">Pesanan yang sudah dibayar tidak dapat dibatalkan.</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
    }).then(function (result) {
        if (!result.value) return;

        $.ajax({
            url: site_url + 'orders/cancel_order',
            type: 'POST',
            data: { order_id: orderId },
            dataType: 'json',
            beforeSend: function () {
                Swal.fire({
                    title: 'Membatalkan pesanan...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onOpen: function () { Swal.showLoading(); }
                });
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesanan Dibatalkan',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function () { loadOrders(); });
                } else {
                    Swal.fire('Gagal Membatalkan', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.', 'error');
            }
        });
    });
}

function formatNumber(num) {
    return parseFloat(num).toLocaleString('id-ID');
}

function formatDateTime(datetime) {
    let date = new Date(datetime);
    return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}
