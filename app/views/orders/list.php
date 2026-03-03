<style>
        .orders-container {
            padding: 20px;
        }
        .orders-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-PROCESSING { background: #17a2b8; color: #fff; }
        .status-PAID { background: #28a745; color: #fff; }
        .status-CANCEL { background: #dc3545; color: #fff; }
    </style>
<br>
<div class="orders-container">
    <div class="orders-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php if(!empty($panel)){echo $panel;}?>
                <p class="mb-0">Kelola semua pesanan customer</p>
            </div>
            <div>
                <a href="<?php echo site_url('orders/create'); ?>" class="btn btn-light btn-lg">
                    <i class="fa fa-plus"></i> Buat Pesanan Baru
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="form-inline">
                <label class="mr-2">Filter Status:</label>
                <select class="form-control" id="statusFilter" onchange="loadOrders()">
                    <option value="ALL">Semua</option>
                    <option value="PROCESSING" selected>Sedang Diproses</option>
                    <option value="PAID">Sudah Bayar</option>
                    <option value="CANCEL">Batal</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="ordersTable">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>No. Order</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Total Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <tr>
                        <td colspan="10" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

