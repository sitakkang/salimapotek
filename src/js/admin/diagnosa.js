var tabel_diagnosa;

$(document).ready(function () {

    tabel_diagnosa = $('#tabel_diagnosa').DataTable({
        processing: true,
        serverSide: true,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: { leftColumns: 1, rightColumns: 1 },
        ajax: { url: site_url + 'diagnosa/table', type: 'GET' },
        columns: [
            { data: '0', width: '40px', orderable: false, searchable: false },
            { data: '1', className: 'text-center' },
            { data: '2' },
            { data: '3', className: 'text-center' },
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
            emptyTable: 'Belum ada data diagnosa',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [],
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
    }

    $('#add_btn').on('click', function () {
        $.get(site_url + 'diagnosa/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah Diagnosa',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-sm'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            dgn_name: $('#dgn_name').val(),
            dgn_cat:  $('#dgn_cat').val(),
        };
        if (!payload.dgn_name) { notifNo('Silakan isi nama diagnosa'); return false; }

        $.post(site_url + 'diagnosa/act_add', payload, function (res) {
            if (res.status == 1) notifNo(res.notif);
            else { $('#MyModal').modal('hide'); notifYesAuto(res.notif); tabel_diagnosa.ajax.reload(null, false); }
        }, 'json');
    });

    $('#tabel_diagnosa tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'diagnosa/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit Diagnosa',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-sm'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:       $('#edit_id').val(),
            dgn_name: $('#edit_dgn_name').val(),
            dgn_cat:  $('#edit_dgn_cat').val(),
        };
        if (!payload.dgn_name) { notifNo('Silakan isi nama diagnosa'); return false; }

        $.post(site_url + 'diagnosa/act_edit', payload, function (res) {
            if (res.status == 1) notifNo(res.notif);
            else { $('#MyModal').modal('hide'); notifYesAuto(res.notif); tabel_diagnosa.ajax.reload(null, false); }
        }, 'json');
    });

    $('#tabel_diagnosa tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Diagnosa?',
            text: 'Data diagnosa akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'diagnosa/act_del', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); tabel_diagnosa.ajax.reload(null, false); }
            }, 'json');
        });
    });
});
