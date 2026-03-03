<br>
<div class="pos-container">
    <!-- Left Panel - Menu Items -->
    <div class="pos-left-panel">
        <div class="pos-header">
            <h4><i class="fa fa-th-large"></i> Menu Items</h4>
            <div class="search-box">
                <input type="text" id="search-item" class="form-control" placeholder="Cari item...">
            </div>
        </div>
        
        <div class="items-grid" id="items-container">
            <!-- Items will be loaded here dynamically -->
        </div>
    </div>

    <!-- Right Panel - Cart & Payment -->
    <div class="pos-right-panel">
        <!-- Load Order Button -->
        <div style="margin-bottom: 15px;">
            <button class="btn btn-warning btn-block" onclick="showOrderList()">
                <i class="fa fa-list"></i> Pilih dari Pesanan yang Ada
            </button>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-number">
                <strong>Invoice:</strong> <span id="invoice-number">-</span>
            </div>
            <div class="kasir-info">
                <strong>Kasir:</strong> <?= $this->session->userdata('sess_name'); ?>
            </div>
            <div class="transaction-time">
                <i class="fa fa-clock-o"></i> <span id="current-time"></span>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="cart-container">
            <div class="cart-header">
                <h5><i class="fa fa-shopping-cart"></i> Detail Pesanan</h5>
                <span id="cart-order-badge" class="badge badge-secondary" style="display:none;"></span>
            </div>
            
            <div class="cart-items" id="cart-items">
                <div class="empty-cart">
                    <i class="fa fa-list-alt"></i>
                    <p>Belum ada pesanan dipilih.</p>
                    <small class="text-muted">Buat pesanan dari halaman <strong>Pesanan</strong> atau pilih pesanan yang ada.</small>
                </div>
            </div>
        </div>

        <!-- Order Type -->
        <div class="order-type-section">
            <label><i class="fa fa-cutlery"></i> Tipe Pesanan:</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-primary active">
                    <input type="radio" name="tipe_order" value="DINE_IN" checked> 
                    <i class="fa fa-users"></i> Dine In
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="tipe_order" value="TAKEAWAY"> 
                    <i class="fa fa-shopping-bag"></i> Takeaway
                </label>
            </div>
        </div>

        <!-- Summary -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>Total Item:</span>
                <strong id="total-items">0</strong>
            </div>
            <div class="summary-row">
                <span>Subtotal:</span>
                <strong id="subtotal">Rp 0</strong>
            </div>
            <div class="summary-row total-row">
                <span>TOTAL:</span>
                <strong id="grand-total">Rp 0</strong>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-danger btn-block" id="cancel-order-btn" style="display:none;">
                <i class="fa fa-times"></i> Batal Pesanan
            </button>
            <button class="btn btn-success btn-block btn-lg" id="proceed-payment" disabled>
                <i class="fa fa-credit-card"></i> Bayar
            </button>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-money"></i> Proses Pembayaran</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left: Payment Summary -->
                    <div class="col-md-6">
                        <h6 class="text-muted">Ringkasan Pembayaran</h6>
                        <div class="payment-summary">
                            <div class="summary-item">
                                <span>Total Item:</span>
                                <strong id="payment-total-items">0</strong>
                            </div>
                            <div class="summary-item">
                                <span>Subtotal:</span>
                                <strong id="payment-subtotal">Rp 0</strong>
                            </div>
                            <div class="summary-item total-payment">
                                <span>TOTAL BAYAR:</span>
                                <strong id="payment-total">Rp 0</strong>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Metode Pembayaran</label>
                            <select class="form-control form-control-lg" id="payment-method">
                                <option value="CASH">💵 Cash</option>
                                <option value="QRIS">📱 QRIS</option>
                                <option value="TRANSFER">🏦 Transfer Bank</option>
                            </select>
                        </div>

                        <div id="cash-payment-section">
                            <div class="form-group">
                                <label>Jumlah Bayar</label>
                                <input type="number" class="form-control form-control-lg" id="amount-paid" placeholder="0" min="0" step="1000">
                            </div>
                            
                            <!-- Quick Amount Buttons -->
                            <div class="quick-amount-btns">
                                <button class="btn btn-outline-secondary btn-sm quick-amount" data-amount="10000">10K</button>
                                <button class="btn btn-outline-secondary btn-sm quick-amount" data-amount="20000">20K</button>
                                <button class="btn btn-outline-secondary btn-sm quick-amount" data-amount="50000">50K</button>
                                <button class="btn btn-outline-secondary btn-sm quick-amount" data-amount="100000">100K</button>
                                <button class="btn btn-outline-primary btn-sm" id="exact-amount">Uang Pas</button>
                            </div>

                            <div class="change-display mt-3">
                                <label>Kembalian</label>
                                <h2 class="text-success" id="change-amount">Rp 0</h2>
                            </div>
                        </div>

                        <div id="digital-payment-section" style="display:none;">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Pastikan pembayaran sudah diterima sebelum menyelesaikan transaksi.
                            </div>
                        </div>
                    </div>

                    <!-- Right: Items List -->
                    <div class="col-md-6">
                        <h6 class="text-muted">Detail Pesanan</h6>
                        <div class="payment-items-list" id="payment-items-list">
                            <!-- Items akan dimuat di sini -->
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Catatan (Opsional)</label>
                    <textarea class="form-control" id="order-notes" rows="2" placeholder="Tambahkan catatan untuk pesanan ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-success btn-lg" id="complete-payment" disabled>
                    <i class="fa fa-check"></i> Selesaikan Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-body p-5">
                <div class="success-icon">
                    <i class="fa fa-check-circle text-success"></i>
                </div>
                <h3 class="mt-3">Transaksi Berhasil!</h3>
                <p class="text-muted">Invoice: <strong id="success-invoice"></strong></p>
                <div class="mt-4">
                    <button class="btn btn-outline-secondary" id="print-receipt">
                        <i class="fa fa-print"></i> Cetak Struk
                    </button>
                    <button class="btn btn-primary" id="new-transaction">
                        <i class="fa fa-plus"></i> Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Order List -->
<div class="modal fade" id="orderListModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fa fa-list"></i> Pilih Pesanan untuk Dibayar</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="orderListTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Meja/Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="orderListBody">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Load from pos.css */
</style>
