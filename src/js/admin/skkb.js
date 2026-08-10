var tabel_skkb;
var url_ctrl = site_url+"skkb/";

$(document).ready(function () {

    // --- Init Datepicker ---
    $('.datepicker').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'd/m/Y',
        closeOnDateSelect: true,
        scrollMonth: false,
        scrollInput: false,
    });

    $(".autocomplete").chosen();

    // --- DataTable ---
    tabel_skkb = $('#tabel_skkb').DataTable({
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
            url: site_url + 'skkb/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px', orderable: false, searchable: false },
            { data: '1'},
            { data: '2'},
            { data: '3', className: 'text-center' },
            { data: '4', className: 'text-center' },
            { data: '5'},
            { data: '6', className: 'text-center' },
            { data: '7', className: 'text-center' },
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
                               '<button class="ds-act-btn ds-act-print print-row-btn" data-id="' + row.DT_RowId + '" title="Cetak SKKB">' +
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
            emptyTable: 'Belum ada data SKKB',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[7, 'desc']],
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
        $('#MyModalContent .datepicker').datetimepicker({
            datepicker: true,
            timepicker: false,
            format: 'd/m/Y',
            closeOnDateSelect: true,
            scrollMonth: false,
            scrollInput: false,
        });
        $('#MyModal').modal('show');
        $('.autocomplete').chosen();
    }

    // --- Tambah SKKB ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'skkb/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah SKKB',
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
            age:             $('#age').val(),
            nik:             $('#nik').val(),
            company_name:    $('#company_name').val(),
            bagian:          $('#bagian').val(),
            jabatan:         $('#jabatan').val(),
            catatan:         $('#catatan').val(),
            docdate:         $('#docdate').val(),
            doctby:          $('#doctby').val(),
        };
        if($('#patient_name').val()==''){
			notifNo("Silahkan isi nama karyawan");
            return false;
		}
        if($('#nik').val()==''){
			notifNo("Silahkan isi NIK");
            return false;
		}
        if($('#company_name').val()==''){
			notifNo("Silahkan isi nama perusahaan");
            return false;
		}
        if($('#jabatan').val()==''){
			notifNo("Silahkan isi jabatan");
            return false;
		}
        if($('#doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        $.post(site_url + 'skkb/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skkb.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Lihat Detail SKKB ---
    $('#tabel_skkb tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skkb/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail SKKB',
                html,
                '',
                'modal-md'
            );
        });
    });

    // --- Edit SKKB ---
    $('#tabel_skkb tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'skkb/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit SKKB',
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
            age:              $('#edit_age').val(),
            nik:              $('#edit_nik').val(),
            company_name:     $('#edit_company_name').val(),
            bagian:           $('#edit_bagian').val(),
            jabatan:          $('#edit_jabatan').val(),
            catatan:          $('#edit_catatan').val(),
            docdate:          $('#edit_docdate').val(),
            doctby:           $('#edit_doctby').val(),
        };
        if($('#edit_patient_name').val()==''){
			notifNo("Silahkan isi nama karyawan");
            return false;
		}
        if($('#edit_nik').val()==''){
			notifNo("Silahkan isi NIK");
            return false;
		}
        if($('#edit_company_name').val()==''){
			notifNo("Silahkan isi nama perusahaan");
            return false;
		}
        if($('#edit_jabatan').val()==''){
			notifNo("Silahkan isi jabatan");
            return false;
		}
        if($('#edit_doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        $.post(site_url + 'skkb/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_skkb.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Hapus SKKB ---
    $('#tabel_skkb tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKKB?',
            text: 'Data SKKB akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'skkb/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel_skkb.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

    // --- Cetak SKKB ---
    $('#tabel_skkb tbody').on('click', '.print-row-btn', function () {
        var id = $(this).data('id');
        var url = site_url + 'skkb/cetak/' + id;
        window.open(url, '_blank');
    });

});
