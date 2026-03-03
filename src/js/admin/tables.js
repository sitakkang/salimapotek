var tabel;

$(document).ready(function () {

    // --- DataTable ---
    tabel = $('#tabel_custom').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: site_url + 'tables/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: '3' },
            {
                data: null,
                width: '140px',
                orderable: false,
                render: function (data, type, row) {
                    return '<button class="btn btn-xs btn-primary edit-row-btn mr-1" data-id="' + row.DT_RowId + '">' +
                               '<i class="fa fa-pencil"></i> Edit' +
                           '</button>' +
                           '<button class="btn btn-xs btn-danger delete-row-btn" data-id="' + row.DT_RowId + '">' +
                               '<i class="fa fa-trash"></i> Hapus' +
                           '</button>';
                }
            },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Data tidak ditemukan',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[1, 'asc']],
    });

    // --- Tambah Meja ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'tables/add', function (html) {
            $('#MyModalTitle').text('Tambah Meja');
            $('#MyModalContent').html(html);
            $('#MyModalFooter').html(
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="btn btn-primary" id="save_add_btn">Simpan</button>'
            );
            $('#MyModal').modal('show');
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            no_meja:   $('#no_meja').val(),
            kapasitas: $('#kapasitas').val(),
        };
        $.post(site_url + 'tables/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Edit Meja ---
    $('#tabel_custom tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'tables/edit', { id: id }, function (html) {
            $('#MyModalTitle').text('Edit Meja');
            $('#MyModalContent').html(html);
            $('#MyModalFooter').html(
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="btn btn-primary" id="save_edit_btn">Simpan Perubahan</button>'
            );
            $('#MyModal').modal('show');
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:        $('#edit_id').val(),
            no_meja:   $('#edit_no_meja').val(),
            kapasitas: $('#edit_kapasitas').val(),
            status:    $('#edit_status').val(),
        };
        $.post(site_url + 'tables/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Hapus Meja ---
    $('#tabel_custom tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Meja?',
            text: 'Data meja akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'tables/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

});
