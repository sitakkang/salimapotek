<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <?php if(!empty($panel)){echo $panel;}?>
                </div>
                <div class="card-body">
                    <button id="add_btn" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Item</button><hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="tabel_custom">
                            <thead>
                                <tr>
                                    <th width="50">No.</th>
                                    <th width="120">Kode Item</th>
                                    <th>Nama Item</th>
                                    <th width="150">Harga Satuan</th>
                                    <th>Deskripsi</th>
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
<div id="popup_menu" class="popup_box">
    <button class="btn btn-default btn-block" id="edit_btn"><i class="fa fa-edit"></i>&nbsp;&nbsp;Ubah</button>
    <button class="btn btn-default btn-block" id="delete_btn"><i class="fa fa-trash"></i>&nbsp;&nbsp;Hapus</button>
</div>