// ============================================
// POS System - JavaScript
// ============================================

$(document).ready(function(){
    const url_ctrl = site_url + "pos/";
    let cart = [];
    let allItems = [];
    let currentInvoice = '';
    let totalAmount = 0;
    
    // Track if this is from an existing order
    window.currentOrderId = null;

    // Initialize
    init();

    function init() {
        loadItems();
        updateClock();
        setInterval(updateClock, 1000);
        
        // Check if redirected from Orders page with a pending order to load
        const pendingOrderId = sessionStorage.getItem('pos_load_order_id');
        if(pendingOrderId) {
            sessionStorage.removeItem('pos_load_order_id');
            loadOrderById(pendingOrderId);
        } else {
            generateInvoice();
        }
    }

    // Load order by ID via AJAX, then populate cart
    function loadOrderById(orderId) {
        $.ajax({
            url: url_ctrl + 'get_order/' + orderId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    loadOrderToCart(response.order, response.items);
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Dimuat!',
                        text: 'Order ' + response.order.order_number + ' berhasil dimuat ke POS',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    generateInvoice();
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat data order', 'error');
                generateInvoice();
            }
        });
    }

    // Update Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        $('#current-time').text(timeString);
    }

    // Generate Invoice Number
    function generateInvoice() {
        $.ajax({
            url: url_ctrl + 'generate_invoice',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                currentInvoice = response.invoice;
                $('#invoice-number').text(currentInvoice);
            }
        });
    }

    // Load All Items
    function loadItems() {
        $('#items-container').html('<div class="loading-items"><i class="fa fa-spinner fa-spin"></i><p>Memuat produk...</p></div>');
        
        $.ajax({
            url: url_ctrl + 'get_items',
            method: 'GET',
            dataType: 'json',
            success: function(items) {
                allItems = items;
                displayItems(items);
            },
            error: function() {
                $('#items-container').html('<div class="text-center text-danger p-4">Gagal memuat produk</div>');
            }
        });
    }

    // Display Items Grid
    function displayItems(items) {
        let html = '';
        
        if(items.length === 0) {
            html = '<div class="col-12 text-center p-4">Tidak ada produk</div>';
        } else {
            items.forEach(function(item) {
                const imageSrc = item.itemimage ? site_url + item.itemimage : '';
                const imageHtml = imageSrc ? 
                    `<img src="${imageSrc}" class="item-image" alt="${item.namaitem}">` :
                    `<div class="item-image no-image"><i class="fa fa-cube"></i></div>`;
                
                html += `
                    <div class="item-card" data-id="${item.id_item}" data-kode="${item.kodeitem}" 
                         data-nama="${item.namaitem}" data-harga="${item.hargasatuan}">
                        ${imageHtml}
                        <div class="item-code">${item.kodeitem}</div>
                        <div class="item-name">${item.namaitem}</div>
                        <div class="item-price">Rp ${formatNumber(item.hargasatuan)}</div>
                    </div>
                `;
            });
        }
        
        $('#items-container').html(html);
    }

    // Search Items
    $('#search-item').on('keyup', function() {
        const keyword = $(this).val().toLowerCase();
        const filtered = allItems.filter(item => 
            item.namaitem.toLowerCase().includes(keyword) ||
            item.kodeitem.toLowerCase().includes(keyword)
        );
        displayItems(filtered);
    });

    // Add Item to Cart
    $(document).on('click', '.item-card', function() {
        const itemId = parseInt($(this).data('id'));
        const itemKode = String($(this).data('kode'));
        const itemNama = String($(this).data('nama'));
        const itemHarga = parseFloat($(this).data('harga'));

        console.log('Item clicked:', {itemId, itemKode, itemNama, itemHarga});
        console.log('Current cart:', cart);

        // Check if item already in cart by ID
        let existingIndex = -1;
        for(let i = 0; i < cart.length; i++) {
            if(parseInt(cart[i].id) === itemId) {
                existingIndex = i;
                break;
            }
        }
        
        if(existingIndex !== -1) {
            // Item exists, increase quantity
            cart[existingIndex].qty++;
            cart[existingIndex].subtotal = cart[existingIndex].qty * cart[existingIndex].harga;
            console.log('Item exists, increased qty to:', cart[existingIndex].qty);
        } else {
            // New item, add to cart
            cart.push({
                id: itemId,
                kodeitem: itemKode,
                namaitem: itemNama,
                harga: itemHarga,
                qty: 1,
                subtotal: itemHarga
            });
            console.log('New item added to cart');
        }

        console.log('Cart after update:', cart);
        updateCart();
        
        // Visual feedback
        $(this).addClass('btn-success');
        setTimeout(() => $(this).removeClass('btn-success'), 200);
    });

    // Update Cart Display
    function updateCart() {
        if(cart.length === 0) {
            $('#cart-items').html(`
                <div class="empty-cart">
                    <i class="fa fa-list-alt"></i>
                    <p>Belum ada pesanan dipilih.</p>
                    <small class="text-muted">Buat pesanan dari halaman <strong>Pesanan</strong> atau pilih pesanan yang ada.</small>
                </div>
            `);
            updateSummary();
            return;
        }

        let html = '';
        cart.forEach(function(item, index) {
            html += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.namaitem}</div>
                        <div class="cart-item-price">Rp ${formatNumber(item.harga)} &times; ${item.qty}</div>
                    </div>
                    <div class="cart-item-controls">
                        <div class="qty-controls">
                            <button class="qty-btn qty-minus" data-index="${index}">-</button>
                            <span class="qty-display">${item.qty}</span>
                            <button class="qty-btn qty-plus" data-index="${index}">+</button>
                        </div>
                        <div class="cart-item-total">Rp ${formatNumber(item.subtotal)}</div>
                        <button class="btn-remove-item" data-index="${index}"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
        });

        $('#cart-items').html(html);
        updateSummary();
    }

    // Increase Quantity
    $(document).on('click', '.qty-plus', function() {
        const index = $(this).data('index');
        cart[index].qty++;
        cart[index].subtotal = cart[index].qty * cart[index].harga;
        updateCart();
        syncCartToServer();
    });

    // Decrease Quantity
    $(document).on('click', '.qty-minus', function() {
        const index = $(this).data('index');
        if(cart[index].qty > 1) {
            cart[index].qty--;
            cart[index].subtotal = cart[index].qty * cart[index].harga;
            updateCart();
            syncCartToServer();
        } else {
            // qty = 1, hapus item otomatis
            cart.splice(index, 1);
            updateCart();
            syncCartToServer();
        }
    });

    // Remove Item
    $(document).on('click', '.btn-remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        updateCart();
        syncCartToServer();
    });

    // Sync perubahan cart ke database
    function syncCartToServer() {
        if (!window.currentOrderId) return;
        $.ajax({
            url: url_ctrl + 'update_cart',
            method: 'POST',
            data: {
                order_id: window.currentOrderId,
                items: JSON.stringify(cart)
            },
            dataType: 'json',
            error: function() {
                console.warn('Gagal sync cart ke server');
            }
        });
    }

    // Update Summary
    function updateSummary() {
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        totalAmount = subtotal;

        $('#total-items').text(totalItems);
        $('#subtotal').text('Rp ' + formatNumber(subtotal));
        $('#grand-total').text('Rp ' + formatNumber(totalAmount));
    }

    // Cancel Order
    $('#cancel-order-btn').on('click', function() {
        Swal.fire({
            title: 'Batal Pesanan?',
            text: 'Pesanan akan dibatalkan secara permanen dan tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result) {
                if (!window.currentOrderId) {
                    // Tidak ada order di DB, cukup reset lokal
                    cart = [];
                    window.currentOrderId = null;
                    updateCart();
                    generateInvoice();
                    refreshActionButtons();
                    return;
                }

                // Batalkan order ke backend
                $.ajax({
                    url: site_url + 'orders/cancel_order',
                    method: 'POST',
                    data: { order_id: window.currentOrderId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            cart = [];
                            window.currentOrderId = null;
                            updateCart();
                            generateInvoice();
                            refreshActionButtons();
                            Swal.fire({
                                icon: 'success',
                                title: 'Pesanan Dibatalkan',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghubungi server. Silakan coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // Proceed to Payment
    $('#proceed-payment').on('click', function() {
        if(cart.length === 0) {
            Swal.fire('Oops!', 'Keranjang masih kosong!', 'warning');
            return;
        }

        if(!window.currentOrderId) {
            Swal.fire('Belum Ada Pesanan', 'Silakan pilih pesanan dari halaman Pesanan atau dari tombol kuning di atas.', 'info');
            return;
        }

        // Populate payment modal
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        $('#payment-total-items').text(totalItems);
        $('#payment-subtotal').text('Rp ' + formatNumber(totalAmount));
        $('#payment-total').text('Rp ' + formatNumber(totalAmount));

        // Display items in payment modal
        let itemsHtml = '';
        cart.forEach(function(item) {
            itemsHtml += `
                <div class="payment-item">
                    <div>
                        <div class="payment-item-name">${item.namaitem}</div>
                        <div class="payment-item-qty">Rp ${formatNumber(item.harga)} x ${item.qty}</div>
                    </div>
                    <div class="payment-item-subtotal">Rp ${formatNumber(item.subtotal)}</div>
                </div>
            `;
        });
        $('#payment-items-list').html(itemsHtml);

        // Reset payment form
        $('#amount-paid').val('');
        $('#change-amount').text('Rp 0');
        $('#payment-method').val('CASH');
        $('#order-notes').val('');
        updatePaymentMethod();

        $('#paymentModal').modal('show');
    });

    // Toggle button states based on order existence
    function refreshActionButtons() {
        if(window.currentOrderId) {
            $('#cancel-order-btn').show();
            $('#proceed-payment').prop('disabled', false);
            $('#cart-order-badge').text('No. ' + currentInvoice).show();
        } else {
            $('#cancel-order-btn').hide();
            $('#proceed-payment').prop('disabled', true);
            $('#cart-order-badge').hide();
        }
    }

    // Initial button state
    refreshActionButtons();

    // Payment Method Change
    $('#payment-method').on('change', updatePaymentMethod);

    function updatePaymentMethod() {
        const method = $('#payment-method').val();
        
        if(method === 'CASH') {
            $('#cash-payment-section').show();
            $('#digital-payment-section').hide();
            $('#complete-payment').prop('disabled', true);
        } else {
            $('#cash-payment-section').hide();
            $('#digital-payment-section').show();
            $('#complete-payment').prop('disabled', false);
        }
    }

    // Calculate Change
    $('#amount-paid').on('keyup change', function() {
        const amountPaid = parseFloat($(this).val()) || 0;
        const change = amountPaid - totalAmount;
        
        if(change >= 0) {
            $('#change-amount').text('Rp ' + formatNumber(change)).removeClass('text-danger').addClass('text-success');
            $('#complete-payment').prop('disabled', false);
        } else {
            $('#change-amount').text('Kurang Rp ' + formatNumber(Math.abs(change))).removeClass('text-success').addClass('text-danger');
            $('#complete-payment').prop('disabled', true);
        }
    });

    // Quick Amount Buttons
    $('.quick-amount').on('click', function() {
        const amount = $(this).data('amount');
        $('#amount-paid').val(amount).trigger('change');
    });

    // Exact Amount
    $('#exact-amount').on('click', function() {
        $('#amount-paid').val(totalAmount).trigger('change');
    });

    // Complete Payment
    $('#complete-payment').on('click', function() {
        const paymentMethod = $('#payment-method').val();
        const tipeOrder = $('input[name="tipe_order"]:checked').val();
        const amountPaid = parseFloat($('#amount-paid').val()) || totalAmount;
        const change = amountPaid - totalAmount;
        const notes = $('#order-notes').val();

        // Check if this is from an order
        if(window.currentOrderId) {
            // Process payment for existing order
            const paymentData = {
                order_id: window.currentOrderId,
                payment_method: paymentMethod,
                amount_paid: amountPaid
            };

            // Disable button
            $('#complete-payment').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: site_url + 'orders/process_payment',
                method: 'POST',
                data: paymentData,
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#paymentModal').modal('hide');
                        window.currentOrderId = null;
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            html: `
                                <div style="text-align: left; padding: 20px;">
                                    <p><strong>No. Order:</strong> ${response.order_number}</p>
                                    <p><strong>Total:</strong> Rp ${formatNumber(response.total)}</p>
                                    <p><strong>Dibayar:</strong> Rp ${formatNumber(response.paid)}</p>
                                    <p><strong>Kembalian:</strong> Rp ${formatNumber(response.change)}</p>
                                </div>
                            `,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = site_url + 'orders';
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        $('#complete-payment').prop('disabled', false).html('<i class="fa fa-check"></i> Selesaikan Pembayaran');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat memproses pembayaran', 'error');
                    $('#complete-payment').prop('disabled', false).html('<i class="fa fa-check"></i> Selesaikan Pembayaran');
                }
            });
        } else {
            // Save as new POS transaction
            const transactionData = {
                no_invoice: currentInvoice,
                total_item: cart.reduce((sum, item) => sum + item.qty, 0),
                subtotal: totalAmount,
                diskon: 0,
                pajak: 0,
                total_bayar: totalAmount,
                jumlah_bayar: amountPaid,
                kembalian: change,
                jenis_pembayaran: paymentMethod,
                tipe_order: tipeOrder,
                catatan: notes,
                items: JSON.stringify(cart)
            };

            // Disable button
            $('#complete-payment').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: url_ctrl + 'save_transaction',
                method: 'POST',
                data: transactionData,
                dataType: 'json',
                success: function(response) {
                    if(response.status === 1) {
                        $('#paymentModal').modal('hide');
                        $('#success-invoice').text(response.invoice);
                        $('#successModal').modal('show');
                        
                        // Store transaction ID for printing
                        $('#print-receipt').data('transaction-id', response.transaction_id);
                        
                        // Reset state
                        cart = [];
                        window.currentOrderId = null;
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        $('#complete-payment').prop('disabled', false).html('<i class="fa fa-check"></i> Selesaikan Pembayaran');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan transaksi', 'error');
                    $('#complete-payment').prop('disabled', false).html('<i class="fa fa-check"></i> Selesaikan Pembayaran');
                }
            });
        }
    });

    // New Transaction
    $('#new-transaction').on('click', function() {
        $('#successModal').modal('hide');
        cart = [];
        window.currentOrderId = null;
        updateCart();
        generateInvoice();
        refreshActionButtons();
    });

    // Print Receipt
    $('#print-receipt').on('click', function() {
        const transactionId = $(this).data('transaction-id');
        window.open(site_url + 'pos/print_receipt/' + transactionId, '_blank');
    });

    // ============================================
    // ORDER MANAGEMENT FUNCTIONS
    // ============================================
    
    window.showOrderList = function() {
        $('#orderListModal').modal('show');
        loadUnpaidOrders();
    };

    function loadUnpaidOrders() {
        $('#orderListBody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
        
        $.ajax({
            url: site_url + 'pos/get_unpaid_orders',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    displayOrderList(response.data);
                }
            },
            error: function() {
                $('#orderListBody').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function displayOrderList(orders) {
        if(orders.length === 0) {
            $('#orderListBody').html('<tr><td colspan="7" class="text-center text-muted">Tidak ada pesanan yang belum dibayar</td></tr>');
            return;
        }

        let html = '';
        orders.forEach(function(order) {
            let tableInfo = order.order_type === 'DINE_IN' ? 
                `Meja ${order.table_number}` : 
                (order.customer_name || 'Takeaway');
            
            html += `
                <tr>
                    <td><strong>${order.order_number}</strong></td>
                    <td>${formatDateTime(order.created_at)}</td>
                    <td><span class="badge badge-${order.order_type === 'DINE_IN' ? 'primary' : 'success'}">${order.order_type}</span></td>
                    <td>${tableInfo}</td>
                    <td><strong>Rp ${formatNumber(order.total_amount)}</strong></td>
                    <td><span class="badge badge-info">${statusLabel(order.status)}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="selectOrder(${order.order_id})">
                            <i class="fa fa-check"></i> Pilih
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#orderListBody').html(html);
    }

    window.selectOrder = function(orderId) {
        $('#orderListModal').modal('hide');
        loadOrderById(orderId);
    };

    function loadOrderToCart(order, items) {
        // Clear existing cart
        cart = [];
        window.currentOrderId = order.order_id;
        
        // Set invoice number from order
        currentInvoice = order.order_number;
        $('#invoice-number').text(currentInvoice);
        
        // Set order type buttons
        const orderType = order.order_type || 'DINE_IN';
        $('input[name="tipe_order"]').parent().removeClass('active');
        $('input[name="tipe_order"][value="' + orderType + '"]').prop('checked', true).parent().addClass('active');
        
        // Load items to cart
        if (items && items.length > 0) {
            items.forEach(function(item) {
                cart.push({
                    order_item_id: parseInt(item.order_item_id), // simpan untuk sync DB
                    id: parseInt(item.item_id),
                    kodeitem: item.kodeitem,
                    namaitem: item.namaitem,
                    harga: parseFloat(item.price),
                    qty: parseInt(item.quantity),
                    subtotal: parseFloat(item.subtotal)
                });
            });
        }
        
        // Update cart display
        updateCart();

        // Switch to payment mode since order exists
        refreshActionButtons();
    }

    function formatDateTime(datetime) {
        const date = new Date(datetime);
        return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    // Format Number Helper - handles PHP decimal strings like "5000.00"
    function formatNumber(num) {
        const parsed = parseFloat(num) || 0;
        return Math.round(parsed).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function statusLabel(status) {
        var labels = { 'PROCESSING': 'Sedang Diproses', 'PAID': 'Sudah Bayar', 'CANCEL': 'Batal' };
        return labels[status] || status;
    }

});
