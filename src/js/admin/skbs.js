var tabel_skbs;

$(document).ready(function () {

    // --- Init Datepicker (xdsoft datetimepicker) ---
    $('.datepicker').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'd/m/Y',
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
    });

    $(".autocomplete").chosen();

    // --- Toggle field Keterangan / Catatan SKBS (dipanggil dari onchange form) ---
    window.toggleSkbsDesc = function (el) {
        var wrapDesc = document.getElementById('skbs_desc_wrap');
        var wrapNote = document.getElementById('skbs_note_wrap');
        if (wrapDesc) {
            wrapDesc.style.display = el.value === 'KETERANGAN' ? '' : 'none';
        }
        if (wrapNote) {
            wrapNote.style.display = el.value === 'FIT DENGAN CATATAN' ? '' : 'none';
        }
    };

    // --- DataTable ---
    tabel_skbs = $('#tabel_skbs').DataTable({
        processing: true,
        serverSide: true,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 1
        },
        ajax: {
            url: site_url + 'skbs/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px', orderable: false, searchable: false },
            { data: '1' },
            { data: '2', className: 'text-center' },
            { data: '3', className: 'text-center' },
            { data: '4' },
            { data: '5', className: 'text-center' },
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
                               '<button class="ds-act-btn ds-act-print print-row-btn" data-id="' + row.DT_RowId + '" title="Cetak SKBS">' +
                                   '<i class="fa fa-print"></i>' +
                               '</button>' +
                               '<button class="ds-act-btn ds-act-delete delete-row-btn" data-id="' + row.DT_RowId + '" title="Hapus">' +
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
            emptyTable: 'Belum ada data SKBS',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[5, 'desc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Helper: show modal with ds-modal class ---
    function showDsModal(title, contentHtml, footerHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html(footerHtml);
        $('.modal-dialog').addClass('ds-modal');
        if (size) {
            $('.modal-dialog').addClass(size);
        }
        // Init datepicker on modal content (xdsoft datetimepicker)
        $('#MyModalContent .datepicker').datetimepicker({
            datepicker: true,
            timepicker: false,
            format: 'd/m/Y',
            closeOnDateSelect: true,
            scrollMonth: false,
            scrollInput: false,
        });
        $('#MyModalContent .autocomplete').chosen();
        $('#MyModal').modal('show');
    }

    // --- Lihat Detail SKBS ---
    $('#tabel_skbs tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skbs/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail SKBS',
                html,
                '',
                'modal-lg'
            );
        });
    });

    // --- Cetak SKBS ---
    $('#tabel_skbs tbody').on('click', '.print-row-btn', function () {
        var id = $(this).data('id');
        var url = site_url + 'skbs/cetak/' + id;
        window.open(url, '_blank');
    });

    // --- Tambah SKBS ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'skbs/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah SKBS',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-lg'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            patient_name:      $('#patient_name').val(),
            patient_nik:       $('#patient_nik').val(),
            patient_ktp:       $('#patient_ktp').val(),
            skbs_gender:       $('#skbs_gender').val(),
            skbs_birth_place:  $('#skbs_birth_place').val(),
            skbs_bod:          $('#skbs_bod').val(),
            skbs_address:      $('#skbs_address').val(),
            skbs_result:       $('#skbs_result').val(),
            skbs_desc:         $('#skbs_desc').val(),
            skbs_note:         $('#skbs_note').val(),
            skbs_blood_press:  $('#skbs_blood_press').val(),
            skbs_pulse:        $('#skbs_pulse').val(),
            skbs_respirasi:    $('#skbs_respirasi').val(),
            skbs_temp:         $('#skbs_temp').val(),
            skbs_tb:           $('#skbs_tb').val(),
            skbs_bb:           $('#skbs_bb').val(),
            skbs_bw:           $('#skbs_bw').val(),
            skbs_r:            $('#skbs_r').val(),
            skbs_l:            $('#skbs_l').val(),
            doctby:            $('#doctby').val(),
            docdate:           $('#docdate').val(),
            skbs_docnumb:      $('#skbs_docnumb').val(),
        };
        if ($('#patient_name').val() == '') {
            notifNo('Silahkan isi nama pasien');
            return false;
        }
        if ($('#skbs_result').val() == '') {
            notifNo('Silahkan pilih hasil pemeriksaan');
            return false;
        }
        if ($('#doctby').val() == '') {
            notifNo('Silahkan pilih dokter pemeriksa');
            return false;
        }
        $.post(site_url + 'skbs/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skbs.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Edit SKBS ---
    $('#tabel_skbs tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skbs/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit SKBS',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-lg'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:                $('#edit_id').val(),
            patient_name:      $('#edit_patient_name').val(),
            patient_nik:       $('#edit_patient_nik').val(),
            patient_ktp:       $('#edit_patient_ktp').val(),
            skbs_gender:       $('#edit_skbs_gender').val(),
            skbs_birth_place:  $('#edit_skbs_birth_place').val(),
            skbs_bod:          $('#edit_skbs_bod').val(),
            skbs_address:      $('#edit_skbs_address').val(),
            skbs_result:       $('#edit_skbs_result').val(),
            skbs_desc:         $('#edit_skbs_desc').val(),
            skbs_note:         $('#edit_skbs_note').val(),
            skbs_blood_press:  $('#edit_skbs_blood_press').val(),
            skbs_pulse:        $('#edit_skbs_pulse').val(),
            skbs_respirasi:    $('#edit_skbs_respirasi').val(),
            skbs_temp:         $('#edit_skbs_temp').val(),
            skbs_tb:           $('#edit_skbs_tb').val(),
            skbs_bb:           $('#edit_skbs_bb').val(),
            skbs_bw:           $('#edit_skbs_bw').val(),
            skbs_r:            $('#edit_skbs_r').val(),
            skbs_l:            $('#edit_skbs_l').val(),
            doctby:            $('#edit_doctby').val(),
            docdate:           $('#edit_docdate').val(),
            skbs_docnumb:      $('#edit_skbs_docnumb').val(),
        };
        if ($('#edit_patient_name').val() == '') {
            notifNo('Silahkan isi nama pasien');
            return false;
        }
        if ($('#edit_skbs_result').val() == '') {
            notifNo('Silahkan pilih hasil pemeriksaan');
            return false;
        }
        if ($('#edit_doctby').val() == '') {
            notifNo('Silahkan pilih dokter pemeriksa');
            return false;
        }
        $.post(site_url + 'skbs/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skbs.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Hapus SKBS ---
    $('#tabel_skbs tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKBS?',
            text: 'Data SKBS akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'skbs/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel_skbs.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

});
