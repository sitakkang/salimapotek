<style>
    .tables-container {
        padding: 20px;
    }
    .tables-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    .tables-header h3 {
        margin-bottom: 4px;
    }
    #tabel_custom td .btn-xs {
        font-size: 11px;
        padding: 2px 8px;
    }
</style>
<br>
<div class="tables-container">
    <div class="tables-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php if(!empty($panel)){echo $panel;}?>
                <p class="mb-0">Kelola semua meja warung</p>
            </div>
            <div>
                <button class="btn btn-light btn-lg" id="add_btn">
                    <i class="fa fa-plus"></i> Tambah Meja
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel_custom" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>No. Meja</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
