<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <?php if(!empty($panel)){echo $panel;}?>
                    </div>
                    <div>
                        <button class="btn btn-primary" id="filter-btn">
                            <i class="fa fa-filter"></i> Filter Tanggal
                        </button>
                        <a href="<?=site_url('pos')?>" class="btn btn-success">
                            <i class="fa fa-shopping-cart"></i> Kembali ke POS
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Date Filter -->
                    <div class="row mb-3" id="date-filter" style="display:none;">
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start-date" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end-date" value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary btn-block" id="apply-filter">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-primary">
                                <div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
                                <div class="stat-content">
                                    <h4 id="total-transactions">0</h4>
                                    <p>Total Transaksi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-success">
                                <div class="stat-icon"><i class="fa fa-money"></i></div>
                                <div class="stat-content">
                                    <h4 id="total-revenue">Rp 0</h4>
                                    <p>Total Pendapatan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-info">
                                <div class="stat-icon"><i class="fa fa-cube"></i></div>
                                <div class="stat-content">
                                    <h4 id="total-items">0</h4>
                                    <p>Total Item Terjual</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning">
                                <div class="stat-icon"><i class="fa fa-line-chart"></i></div>
                                <div class="stat-content">
                                    <h4 id="avg-transaction">Rp 0</h4>
                                    <p>Rata-rata Transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="tabel_history">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="50">No.</th>
                                    <th width="150">Invoice</th>
                                    <th width="150">Tanggal</th>
                                    <th width="80">Item</th>
                                    <th width="120">Total</th>
                                    <th width="100">Pembayaran</th>
                                    <th width="100">Tipe</th>
                                    <th>Kasir</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detail-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    padding: 20px;
    border-radius: 10px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 40px;
    opacity: 0.8;
}

.stat-content h4 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

.stat-content p {
    margin: 0;
    font-size: 13px;
    opacity: 0.9;
}

.thead-dark th {
    background: #2c3e50;
    color: white;
}
</style>
