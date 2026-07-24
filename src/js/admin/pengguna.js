var tabel_pengguna;
var url_ctrl = site_url + 'pengguna/';

$(document).ready(function () {

    // --- DataTable ---
    tabel_pengguna = $('#tabel_pengguna').DataTable({
        processing: true,
        serverSide: false,
        scrollY: '450px',
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 1
        },
        ajax: {
            url: url_ctrl + 'table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: '3', className: 'text-center' },
            { data: '4', className: 'text-center' },
            { data: '5', className: 'text-center' },
            { data: '6', className: 'text-center' },
            {
                data: null,
                width: '100px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<div class="ds-act-group">' +
                               '<button class="ds-act-btn ds-act-view view-row-btn" data-id="' + row.DT_RowId + '" title="Lihat Detail">' +
                                   '<i class="fa fa-eye"></i>' +
                               '</button>' +
                               '<button class="ds-act-btn ds-act-edit edit-row-btn" data-id="' + row.DT_RowId + '" title="Edit Data">' +
                                   '<i class="fa fa-pen"></i>' +
                               '</button>' +
                               '<button class="ds-act-btn ds-act-print reset-row-btn" data-id="' + row.DT_RowId + '" title="Reset Password">' +
                                   '<i class="fa fa-key"></i>' +
                               '</button>' +
                               '<button class="ds-act-btn ds-act-delete delete-row-btn" data-id="' + row.DT_RowId + '" title="Nonaktifkan">' +
                                   '<i class="fa fa-trash"></i>' +
                               '</button>' +
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
            emptyTable: 'Belum ada data pengguna',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Helper: show modal ---
    function showDsModal(title, contentHtml, footerHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html(footerHtml);
        $('.modal-dialog').addClass('ds-modal');
        if (size) {
            $('.modal-dialog').addClass(size);
        }
        $('#MyModal').modal('show');
    }

    // --- Tambah Pengguna ---
    $('#add_btn').on('click', function () {
        $.get(url_ctrl + 'add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah Pengguna',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            fullname: $('#fullname').val(),
            username: $('#username').val(),
            nip:      $('#nip').val(),
            password: $('#password').val(),
            passconf: $('#passconf').val(),
            level:    $('#level').val(),
            status:   $('#status').val(),
        };

        if (payload.fullname == '') { notifNo('Silahkan isi nama lengkap'); return false; }
        if (payload.username == '') { notifNo('Silahkan isi username'); return false; }
        if (payload.password == '') { notifNo('Silahkan isi password'); return false; }
        if (payload.password != payload.passconf) { notifNo('Password tidak cocok'); return false; }
        if (payload.level == '') { notifNo('Silahkan pilih level'); return false; }

        $.post(url_ctrl + 'act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_pengguna.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Lihat Detail ---
    $('#tabel_pengguna tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        // Simple detail view using existing data
        var data = tabel_pengguna.row($(this).closest('tr')).data();
        var html = '<div class="ds-detail-wrap">' +
            '<div class="ds-detail-card">' +
                '<div class="ds-detail-card-hd">' +
                    '<span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>' +
                    '<span>Informasi Pengguna</span>' +
                '</div>' +
                '<div class="ds-detail-card-bd">' +
                    '<div class="ds-detail-grid-2col">' +
                        '<div class="ds-detail-field">' +
                            '<span class="ds-detail-label">Nama Lengkap</span>' +
                            '<span class="ds-detail-value">' + data[1] + '</span>' +
                        '</div>' +
                        '<div class="ds-detail-field">' +
                            '<span class="ds-detail-label">Username</span>' +
                            '<span class="ds-detail-value ds-detail-code">' + data[2] + '</span>' +
                        '</div>' +
                        '<div class="ds-detail-field">' +
                            '<span class="ds-detail-label">NIP</span>' +
                            '<span class="ds-detail-value">' + (data[3] || '-') + '</span>' +
                        '</div>' +
                        '<div class="ds-detail-field">' +
                            '<span class="ds-detail-label">Level</span>' +
                            '<span class="ds-detail-value">' + data[4] + '</span>' +
                        '</div>' +
                        '<div class="ds-detail-field">' +
                            '<span class="ds-detail-label">Status</span>' +
                            '<span class="ds-detail-value">' + data[6] + '</span>' +
                        '</div>' +
                        '<div class="ds-detail-field ds-detail-field-full">' +
                            '<span class="ds-detail-label">Terakhir Login</span>' +
                            '<span class="ds-detail-value">' + (data[5] || '-') + '</span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        showDsModal(
            '<i class="fa fa-file-text-o"></i> Detail Pengguna',
            html,
            '',
            'modal-md'
        );
    });

    // --- Edit Pengguna ---
    $('#tabel_pengguna tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(url_ctrl + 'edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit Pengguna',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:           $('#edit_id').val(),
            fullname:     $('#edit_fullname').val(),
            username:     $('#edit_username').val(),
            nip:          $('#edit_nip').val(),
            username_old: $('#edit_username_old').val(),
            level:        $('#edit_level').val(),
            status:       $('#edit_status').val(),
        };

        if (payload.fullname == '') { notifNo('Silahkan isi nama lengkap'); return false; }
        if (payload.username == '') { notifNo('Silahkan isi username'); return false; }
        if (payload.level == '') { notifNo('Silahkan pilih level'); return false; }

        $.post(url_ctrl + 'act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_pengguna.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Reset Password ---
    $('#tabel_pengguna tbody').on('click', '.reset-row-btn', function () {
        var id = $(this).data('id');
        $.get(url_ctrl + 'reset', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-key"></i> Reset Password',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_reset_btn" style="padding:8px 22px">Simpan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_reset_btn', function () {
        var payload = {
            id:       $('#reset_id').val(),
            password: $('#reset_password').val(),
            passconf: $('#reset_passconf').val(),
        };

        if (payload.password == '') { notifNo('Silahkan isi password baru'); return false; }
        if (payload.password != payload.passconf) { notifNo('Password tidak cocok'); return false; }

        $.post(url_ctrl + 'act_reset', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
            }
        }, 'json');
    });

    // --- Nonaktifkan Pengguna ---
    $('#tabel_pengguna tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        var data = tabel_pengguna.row($(this).closest('tr')).data();
        var name = data[1];

        swal({
            title: 'Nonaktifkan Pengguna?',
            text: 'Pengguna ' + name + ' akan dinonaktifkan.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Nonaktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(url_ctrl + 'act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel_pengguna.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

});
