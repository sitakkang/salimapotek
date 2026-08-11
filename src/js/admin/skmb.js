var tabel_skmb;
var url_ctrl = site_url+"skmb/";

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

    // --- Init Clockpicker ---
    $('.clockpicker').clockpicker({
        autoclose: true,
        donetext: 'OK',
        placement: 'bottom',
        align: 'left',
    });

    $(".autocomplete").chosen();

    // --- DataTable ---
    tabel_skmb = $('#tabel_skmb').DataTable({
        processing: true,
        serverSide: true,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 3,
            rightColumns: 2
        },
        ajax: {
            url: site_url + 'skmb/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px', orderable: false, searchable: false },
            { data: '1'},
            { data: '2'},
            { data: '3', className: 'text-center' },
            { data: '4'},
            { data: '5', className: 'text-center' },
            { data: '6', className: 'text-center' },
            { data: '7', className: 'text-center' },
            { data: '8', className: 'text-center' },
            {
                data: null,
                width: '120px',
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
                               '<button class="ds-act-btn ds-act-print print-row-btn" data-id="' + row.DT_RowId + '" title="Cetak SKMB">' +
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
            emptyTable: 'Belum ada data SKMB',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[8, 'desc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Helper: show modal with ds-modal class ---
    function showDsModal(title, contentHtml, footerHtml, size) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html(footerHtml);
        // Add ds-modal class + modal size
        $('.modal-dialog').addClass('ds-modal');
        if (size) {
            $('.modal-dialog').addClass(size);
        }
        // Init datepicker on modal content
        $('#MyModalContent .datepicker').datetimepicker({
            datepicker: true,
            timepicker: false,
            format: 'd/m/Y',
            closeOnDateSelect: true,
            scrollMonth: false,
            scrollInput: false,
        });
        // Init clockpicker on modal content
        $('#MyModalContent .clockpicker').clockpicker({
            autoclose: true,
            donetext: 'OK',
            placement: 'bottom',
            align: 'left',
        });
        $('#MyModal').modal('show');
        $('.autocomplete').chosen();
    }

    // --- Tambah SKMB ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'skmb/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah SKMB',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            patient_name:    $('#patient_name').val(),
            nik:             $('#nik').val(),
            company_name:    $('#company_name').val(),
            pengantar:       $('#pengantar').val(),
            nik_pengantar:   $('#nik_pengantar').val(),
            pekerjaan_pengantar: $('#pekerjaan_pengantar').val(),
            hubungan:        $('#hubungan').val(),
            tgl_datang:      $('#tgl_datang').val(),
            jam:             $('#jam').val(),
            patient_diagnosa: $('#diagnosa').val(),
            docdate:         $('#docdate').val(),
            doctby:          $('#doctby').val(),
            skmb_docnumb:    $('#skmb_docnumb').val(),
        };
        if($('#patient_name').val()==''){
			notifNo("Silahkan isi nama yang diantar");
            return false;
		}
        if($('#tgl_datang').val()==''){
			notifNo("Silahkan isi tanggal datang");
            return false;
		}
        if($('#jam').val()==''){
			notifNo("Silahkan isi jam datang");
            return false;
		}
        if($('#hubungan').val()==''){
			notifNo("Silahkan pilih hubungan");
            return false;
		}
        if($('#pengantar').val()==''){
			notifNo("Silahkan isi nama pengantar");
            return false;
		}
        if($('#doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        $.post(site_url + 'skmb/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skmb.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Lihat Detail SKMB ---
    $('#tabel_skmb tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skmb/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail SKMB',
                html,
                '',
                'modal-md'
            );
        });
    });

    // --- Edit SKMB ---
    $('#tabel_skmb tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skmb/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit SKMB',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:               $('#edit_id').val(),
            patient_name:     $('#edit_patient_name').val(),
            nik:              $('#edit_nik').val(),
            company_name:     $('#edit_company_name').val(),
            pengantar:        $('#edit_pengantar').val(),
            nik_pengantar:    $('#edit_nik_pengantar').val(),
            pekerjaan_pengantar: $('#edit_pekerjaan_pengantar').val(),
            hubungan:         $('#edit_hubungan').val(),
            tgl_datang:       $('#edit_tgl_datang').val(),
            jam:              $('#edit_jam').val(),
            patient_diagnosa: $('#edit_diagnosa').val(),
            docdate:          $('#edit_docdate').val(),
            doctby:           $('#edit_doctby').val(),
            skmb_docnumb:     $('#edit_skmb_docnumb').val(),
        };
        if($('#edit_patient_name').val()==''){
			notifNo("Silahkan isi nama yang diantar");
            return false;
		}
        if($('#edit_tgl_datang').val()==''){
			notifNo("Silahkan isi tanggal datang");
            return false;
		}
        if($('#edit_jam').val()==''){
			notifNo("Silahkan isi jam datang");
            return false;
		}
        if($('#edit_hubungan').val()==''){
			notifNo("Silahkan pilih hubungan");
            return false;
		}
        if($('#edit_pengantar').val()==''){
			notifNo("Silahkan isi nama pengantar");
            return false;
		}
        if($('#edit_doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        $.post(site_url + 'skmb/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skmb.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Hapus SKMB ---
    $('#tabel_skmb tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKMB?',
            text: 'Data SKMB akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'skmb/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel_skmb.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

    // --- Cetak SKMB ---
    $('#tabel_skmb tbody').on('click', '.print-row-btn', function () {
        var id = $(this).data('id');
        var url = site_url + 'skmb/cetak/' + id;
        window.open(url, '_blank');
    });

});
