var tabel_patient;
var url_ctrl = site_url+"patient/"; 
	
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

    // --- DataTable (tanpa ajax — mulai kosong) ---
    tabel_patient = $('#tabel_patient').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        data: [],  // mulai tanpa data
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: 'jk', className: 'text-center',
                render: function (data) {
                    if (data === 'L') return '<span class="badge badge-info">Laki-laki</span>';
                    if (data === 'P') return '<span class="badge badge-danger">Perempuan</span>';
                    return '<span class="badge badge-secondary">-</span>';
                }
            },
            { data: '3', className: 'text-center' },
            { data: '4' },
            { data: '5' },
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
                               '<button class="ds-act-btn ds-act-delete delete-row-btn" data-id="' + row.DT_RowId + '" title="Hapus">' +
                                   '<i class="fa fa-trash"></i>' +
                               '</button>' +
                           '</div>';
                }
            },
            {
                data: null,
                width: '80px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<button class="ds-btn-daftar daftar-row-btn" data-id="' + row.DT_RowId + '" title="Daftarkan Pasien">' +
                               '<i class="fa fa-clipboard-list"></i> Daftar' +
                           '</button>';
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
            emptyTable: 'Silakan lakukan pencarian terlebih dahulu',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Fungsi pencarian ---
    function doSearch() {
        var rm   = $('#search_rm').val().trim();
        var ktp  = $('#search_ktp').val().trim();
        var nama = $('#search_nama').val().trim();

        tabel_patient.ajax.url(site_url + 'patient/table?rm=' + encodeURIComponent(rm) +
            '&ktp=' + encodeURIComponent(ktp) +
            '&nama=' + encodeURIComponent(nama)).load();
    }

    // --- Tombol Cari ---
    $('#search_btn').on('click', function () {
        doSearch();
    });

    // --- Enter pada field search ---
    $('#search_rm, #search_ktp, #search_nama').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            doSearch();
        }
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
        // Init datepicker on modal content (xdsoft datetimepicker)
        // Simpan value sebelum init agar tidak hilang
        $('#MyModalContent .datepicker').each(function () {
            var val = $(this).val();
            $(this).datetimepicker({
                datepicker: true,
                timepicker: false,
                format: 'd/m/Y',
                closeOnDateSelect: true,
                scrollMonth: false,
                scrollInput: false,
            });
            if (val) $(this).val(val);
        });
        $('#MyModal').modal('show');
        $('.autocomplete').chosen();
    }

    // --- Tambah Pasien ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'patient/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah Pasien',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            patient_name:        $('#patient_name').val(),
            patient_gender:      $('#patient_gender').val(),
            patient_nik:         $('#patient_nik').val(),
            patient_company:     $('#patient_company').val(),
            patient_department:  $('#patient_department').val(),
            patient_ktp:         $('#patient_ktp').val(),
            patient_bod:         $('#patient_bod').val(),
            patient_phone:       $('#patient_phone').val(),
            patient_address:     $('#patient_address').val(),
            patient_city_name:   $("#select_kecamatan option:selected").text(),
            patient_district_name: $("#select_kelurahan option:selected").text(),
            id_kecamatan:        $("#select_kecamatan").val(),
            id_kelurahan:        $("#select_kelurahan").val(),
        };
        if($('#patient_name').val()==''){
			notifNo("Silahkan isi nama pasien");
            return false;
		}
        if($('#patient_gender').val()==''){
			notifNo("Silahkan pilih jenis kelamin");
            return false;
		}
        if($('#patient_bod').val()==''){
			notifNo("Silahkan isi tanggal lahir");
            return false;
		}
        if($('#patient_phone').val()==''){
			notifNo("Silahkan isi nomor telepon");
            return false;
		}
        if($('#select_kecamatan').val()==''){
			notifNo("Silahkan pilih kecamatan");
            return false;
		}
        if($('#select_kelurahan').val()==''){
			notifNo("Silahkan pilih kelurahan");
            return false;
		}
        if($('#patient_address').val()==''){
			notifNo("Silahkan isi alamat");
            return false;
		}
        $.post(site_url + 'patient/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                if (res.patient_code) {
                    $('#search_rm').val(res.patient_code);
                    $('#search_ktp').val('');
                    $('#search_nama').val('');
                }
                doSearch();
            }
        }, 'json');
    });

    // --- Lihat Detail Pasien ---
    $('#tabel_patient tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'patient/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail Pasien',
                html,
                '',  /* no footer — close button is in the modal header */
                'modal-md'
            );
        });
    });

    // --- Edit Pasien ---
    $('#tabel_patient tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'patient/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit Pasien',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_edit_btn" style="padding:8px 22px">Simpan Perubahan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_edit_btn', function () {
        var payload = {
            id:                  $('#edit_id').val(),
            patient_name:        $('#edit_patient_name').val(),
            patient_gender:      $('#edit_patient_gender').val(),
            patient_nik:         $('#edit_patient_nik').val(),
            patient_company:     $('#edit_patient_company').val(),
            patient_department:  $('#edit_patient_department').val(),
            patient_ktp:         $('#edit_patient_ktp').val(),
            patient_bod:         $('#edit_patient_bod').val(),
            patient_phone:       $('#edit_patient_phone').val(),
            patient_address:     $('#edit_patient_address').val(),
            patient_city_name:   $("#edit_kecamatan option:selected").text(),
            patient_district_name: $("#edit_kelurahan option:selected").text(),
            id_kecamatan:        $("#edit_kecamatan").val(),
            id_kelurahan:        $("#edit_kelurahan").val(),
        };
        if($('#edit_patient_name').val()==''){
			notifNo("Silahkan isi nama pasien");
            return false;
		}
        if($('#edit_patient_gender').val()==''){
			notifNo("Silahkan pilih jenis kelamin");
            return false;
		}
        if($('#edit_patient_bod').val()==''){
			notifNo("Silahkan isi tanggal lahir");
            return false;
		}
        if($('#edit_patient_phone').val()==''){
			notifNo("Silahkan isi nomor telepon");
            return false;
		}
        if($('#edit_kecamatan').val()==''){
			notifNo("Silahkan pilih kecamatan");
            return false;
		}
        if($('#edit_kelurahan').val()==''){
			notifNo("Silahkan pilih kelurahan");
            return false;
		}
        if($('#edit_patient_address').val()==''){
			notifNo("Silahkan isi alamat");
            return false;
		}
        $.post(site_url + 'patient/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                doSearch();
            }
        }, 'json');
    });

    // --- Daftar Pasien ---
    $('#tabel_patient tbody').on('click', '.daftar-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'patient/daftar', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-clipboard-list"></i> Pendaftaran Pasien',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_daftar_btn" style="padding:8px 22px">Simpan Pendaftaran</button>',
                'modal-md'
            );
            // Init datetimepicker with time enabled for the daftar modal
            $('#MyModalContent .datetimepicker').datetimepicker({
                datepicker: true,
                timepicker: true,
                format: 'd/m/Y H:i',
                closeOnDateSelect: false,
                scrollMonth: false,
                scrollInput: false,
            });
        });
    });

    $(document).on('click', '#save_daftar_btn', function () {
        var id = $('#daftar_id').val();
        var trans_doc = $('#trans_doc').val();
        if (trans_doc == '') {
            notifNo('Silakan pilih tanggal dan jam pendaftaran');
            return false;
        }
        $.post(site_url + 'patient/act_daftar', { id: id, trans_doc: trans_doc }, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
            }
        }, 'json');
    });

    // --- Hapus Pasien ---
    $('#tabel_patient tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Nonaktifkan Pasien?',
            text: 'Data pasien akan dinonaktifkan (soft delete).',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Nonaktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'patient/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    doSearch();
                }
            }, 'json');
        });
    });

    $(document).on('change','#select_kecamatan',function(e){
      e.preventDefault();
      var kecamatan_id = $("#select_kecamatan").val();
      loadingShow();
      $.ajax({
        method: "GET",
        cache: false,
        url: url_ctrl+'get_kelurahan_not_default',
        data: { kecamatan_id: kecamatan_id },
      })
      .done(function(result) {
        loadingHide();
        var obj = jQuery.parseJSON(result);
        $('#select_kelurahan').html(obj.html).trigger('chosen:updated');
      })
      .fail(function(res){
        loadingHide();
        alert('Error Response !');
        console.log("responseText", res.responseText);
      });
    });

    $(document).on('change','#edit_kecamatan',function(e){
      e.preventDefault();
      var kecamatan_id = $("#edit_kecamatan").val();
      loadingShow();
      $.ajax({
        method: "GET",
        cache: false,
        url: url_ctrl+'get_kelurahan_not_default',
        data: { kecamatan_id: kecamatan_id },
      })
      .done(function(result) {
        loadingHide();
        var obj = jQuery.parseJSON(result);
        $('#edit_kelurahan').html(obj.html).trigger('chosen:updated');
      })
      .fail(function(res){
        loadingHide();
        alert('Error Response !');
        console.log("responseText", res.responseText);
      });
    });

});
