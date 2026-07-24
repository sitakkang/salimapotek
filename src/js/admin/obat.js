var tabel_obat;

$(document).ready(function () {

    tabel_obat = $('#tabel_obat').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: { leftColumns: 1, rightColumns: 1 },
        ajax: { url: site_url + 'obat/table', type: 'GET' },
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2', className: 'text-center' },
            { data: '3', className: 'text-right' },
            { data: '4', className: 'text-center' },
            {
                data: null,
                width: '120px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<div class="ds-act-group">' +
                        '<button class="ds-act-btn ds-act-edit edit-row-btn" data-id="' + row.DT_RowId + '" title="Edit">' +
                            '<i class="fa fa-pen"></i></button>' +
                        '<button class="ds-act-btn ds-act-delete delete-row-btn" data-id="' + row.DT_RowId + '" title="Hapus">' +
                            '<i class="fa fa-trash"></i></button>' +
                    '</div>';
                }
            },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Belum ada data',
            emptyTable: 'Belum ada data obat',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    function showDsModal(title, contentHtml, footerHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html(footerHtml);
        $('.modal-dialog').addClass('ds-modal');
        if (size) $('.modal-dialog').addClass(size);
        $('#MyModal').modal('show');
        // Init mask for price
        $('#obat_price, #edit_obat_price').mask('#.##0', { reverse: true });
    }

    $('#add_btn').on('click', function () {
        $.get(site_url + 'obat/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah Obat',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-sm'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            obat_name:   $('#obat_name').val(),
            obat_satuan: $('#obat_satuan').val(),
            obat_price:  $('#obat_price').val(),
        };
        if (!payload.obat_name) { notifNo('Silakan isi nama obat'); return false; }
        if (!payload.obat_satuan) { notifNo('Silakan isi satuan'); return false; }
        if (!payload.obat_price) { notifNo('Silakan isi harga'); return false; }

        $.post(site_url + 'obat/act_add', payload, function (res) {
            if (res.status == 1) notifNo(res.notif);
            else { $('#MyModal').modal('hide'); notifYesAuto(res.notif); tabel_obat.ajax.reload(null, false); }
        }, 'json');
    });

    $('#tabel_obat tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'obat/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit Obat',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-sm'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:          $('#edit_id').val(),
            obat_name:   $('#edit_obat_name').val(),
            obat_satuan: $('#edit_obat_satuan').val(),
            obat_price:  $('#edit_obat_price').val(),
        };
        if (!payload.obat_name) { notifNo('Silakan isi nama obat'); return false; }

        $.post(site_url + 'obat/act_edit', payload, function (res) {
            if (res.status == 1) notifNo(res.notif);
            else { $('#MyModal').modal('hide'); notifYesAuto(res.notif); tabel_obat.ajax.reload(null, false); }
        }, 'json');
    });

    $('#tabel_obat tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Obat?',
            text: 'Data obat akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'obat/act_del', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); tabel_obat.ajax.reload(null, false); }
            }, 'json');
        });
    });
});
