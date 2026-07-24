var tabel_sks;
var url_ctrl = site_url+"sks/"; 
	
$(document).ready(function () {

    // --- Init Datepicker (xdsoft datetimepicker) ---
    // Library: lib/datepicker/datepicker.min.js
    // Gunakan $.fn.datetimepicker BUKAN $.fn.datepicker
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
    tabel_sks = $('#tabel_sks').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 3,
            rightColumns: 2
        },
        ajax: {
            url: site_url + 'sks/table',
            type: 'GET',
        },
        columns: [
            { data: '0', width: '40px' },
            { data: '1'},
            { data: '2', className: 'text-center' },
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
                               '<button class="ds-act-btn ds-act-print print-row-btn" data-id="' + row.DT_RowId + '" title="Cetak SKS">' +
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
            emptyTable: 'Belum ada data SKS',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
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
        // Init datepicker on modal content (xdsoft datetimepicker)
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

    // --- Tambah SKS ---
    $('#add_btn').on('click', function () {
        $.get(site_url + 'sks/add', function (html) {
            showDsModal(
                '<i class="fa fa-plus-circle"></i> Tambah SKS',
                html,
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="save_add_btn" style="padding:8px 22px">Simpan</button>',
                'modal-md'
            );
        });
    });

    $(document).on('click', '#save_add_btn', function () {
        var payload = {
            patient_name: $('#patient_name').val(),
            company_name: $('#company_name').val(),
            age:          $('#age').val(),
            gender:       $('#gender').val(),
            desa:         $('#desa').val(),
            kecamatan: 	  $("#select_kecamatan option:selected").text(),
            kelurahan:    $("#select_kelurahan option:selected").text(),
            id_kecamatan: $("#select_kecamatan").val(),
            id_kelurahan: $("#select_kelurahan").val(),
            kabupaten:    $('#kabupaten').val(),
            provinsi:     $('#provinsi').val(),
            diagnosa:     $('#diagnosa').val(),
            terapi:       $('#terapi').val(),
            datefrom:     $('#datefrom').val(),
            dateto:       $('#dateto').val(),
            docdate:      $('#docdate').val(),
            doctby:       $('#doctby').val(),
        };
        if($('#patient_name').val()==''){
			notifNo("Silahkan isi nama pasien");
            return false;
		}
        if($('#age').val()==''){
			notifNo("Silahkan isi umur pasien");
            return false;
		}
        if($('#gender').val()==''){
			notifNo("Silahkan pilih jenis kelamin pasien");
            return false;
		}
        if($('#doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        if($('#diagnosa').val()==''){
			notifNo("Silahkan isi diagnosa");
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
        if($('#desa').val()==''){
			notifNo("Silahkan isi nama desa");
            return false;
		}
        if($('#datefrom').val()==''){
			notifNo("Silahkan isi tanggal mulai");
            return false;
		}
        if($('#dateto').val()==''){
			notifNo("Silahkan isi tanggal selesai");
            return false;
		}
        $.post(site_url + 'sks/act_add', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_sks.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Lihat Detail SKS ---
    $('#tabel_sks tbody').on('click', '.view-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'sks/detail', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-file-text-o"></i> Detail SKS',
                html,
                '',  /* no footer — close button is in the modal header */
                'modal-md'
            );
        });
    });

    // --- Edit SKS ---
    $('#tabel_sks tbody').on('click', '.edit-row-btn', function () {
        var id = $(this).data('id');
        $.get(site_url + 'sks/edit', { id: id }, function (html) {
            showDsModal(
                '<i class="fa fa-pencil"></i> Edit SKS',
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
            patient_name: $('#edit_patient_name').val(),
            company_name: $('#edit_company_name').val(),
            age:          $('#edit_age').val(),
            gender:       $('#edit_gender').val(),
            desa:         $('#edit_desa').val(),
            kecamatan: 	  $("#edit_kecamatan option:selected").text(),
            kelurahan:    $("#edit_kelurahan option:selected").text(),
            id_kecamatan: $("#edit_kecamatan").val(),
            id_kelurahan: $("#edit_kelurahan").val(),
            kabupaten:    $('#edit_kabupaten').val(),
            provinsi:     $('#edit_provinsi').val(),
            diagnosa:     $('#edit_diagnosa').val(),
            terapi:       $('#edit_terapi').val(),
            datefrom:     $('#edit_datefrom').val(),
            dateto:       $('#edit_dateto').val(),
            docdate:      $('#edit_docdate').val(),
            doctby:       $('#edit_doctby').val(),
            
        };
        if($('#patient_name').val()==''){
			notifNo("Silahkan isi nama pasien");
            return false;
		}
        if($('#age').val()==''){
			notifNo("Silahkan isi umur pasien");
            return false;
		}
        if($('#gender').val()==''){
			notifNo("Silahkan pilih jenis kelamin pasien");
            return false;
		}
        if($('#doctby').val()==''){
			notifNo("Silahkan pilih dokter");
            return false;
		}
        if($('#diagnosa').val()==''){
			notifNo("Silahkan isi diagnosa");
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
        if($('#desa').val()==''){
			notifNo("Silahkan isi nama desa");
            return false;
		}
        if($('#datefrom').val()==''){
			notifNo("Silahkan isi tanggal mulai");
            return false;
		}
        if($('#dateto').val()==''){
			notifNo("Silahkan isi tanggal selesai");
            return false;
		}
        $.post(site_url + 'sks/act_edit', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else if (res.status == 2) {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                tabel_sks.ajax.reload(null, false);
            }
        }, 'json');
    });

    // --- Hapus SKS ---
    $('#tabel_sks tbody').on('click', '.delete-row-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKS?',
            text: 'Data SKS akan dihapus permanen.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'sks/act_del', { id: id }, function (res) {
                if (res.status == 1) {
                    notifNo(res.notif);
                } else if (res.status == 2) {
                    notifYesAuto(res.notif);
                    tabel_sks.ajax.reload(null, false);
                }
            }, 'json');
        });
    });

    // --- Cetak SKS ---
    $('#tabel_sks tbody').on('click', '.print-row-btn', function () {
        var id = $(this).data('id');
        var url = site_url + 'sks/cetak/' + id;
        window.open(url, '_blank');
    });

    $(document).on('change','#select_kecamatan',function(e){
      e.preventDefault();
      var kecamatan_id                  = $("#select_kecamatan").val();
      loadingShow();
      $.ajax({
        method                          : "GET",
        cache                           : false,
        url                             : url_ctrl+'get_kelurahan_not_default',
        data                            : {
          kecamatan_id                  : kecamatan_id
        },
        
      })
      .done(function(result) {
        loadingHide();
        var obj                         = jQuery.parseJSON(result);
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
      var kecamatan_id                  = $("#edit_kecamatan").val();
      loadingShow();
      $.ajax({
        method                          : "GET",
        cache                           : false,
        url                             : url_ctrl+'get_kelurahan_not_default',
        data                            : {
          kecamatan_id                  : kecamatan_id
        },
        
      })
      .done(function(result) {
        loadingHide();
        var obj                         = jQuery.parseJSON(result);
        $('#edit_kelurahan').html(obj.html).trigger('chosen:updated');
      })
      .fail(function(res){
        loadingHide();
        alert('Error Response !');
        console.log("responseText", res.responseText);
      });
    });

});
