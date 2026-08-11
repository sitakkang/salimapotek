// ================================================================
// PEMERIKSAAN PAGE — Helper reload sections
// ================================================================
function getVisitIdPemeriksaan() {
    return $('#visit_id_sks').val();
}

function reloadDiagnosaTable() {
    var vid = getVisitIdPemeriksaan();
    if (!vid) return;
    $('#diagnosa-table-wrap').load(site_url + 'dokter/reload_diagnosa?visit_id=' + vid);
}

function reloadObatTable() {
    var vid = getVisitIdPemeriksaan();
    if (!vid) return;
    $('#obat-table-wrap').load(site_url + 'dokter/reload_obat?visit_id=' + vid, function () {
        reloadPulvList();
    });
}

function reloadPulvList() {
    var vid = getVisitIdPemeriksaan();
    if (!vid) return;
    $.get(site_url + 'dokter/reload_pulv?visit_id=' + vid, function (html) {
        $('#pulv-list-wrap').html(html);
    });
}

function reloadSksSection() {
    var vid = getVisitIdPemeriksaan();
    if (!vid) return;
    $.get(site_url + 'dokter/reload_sks?visit_id=' + vid, function (html) {
        $('#sks-section-wrap').html(html);
        $('#sks-section-wrap .datepicker').datetimepicker({
            datepicker: true, timepicker: false, format: 'd/m/Y',
            closeOnDateSelect: true, scrollMonth: false, scrollInput: false,
        });
        $('#sks-section-wrap .autocomplete').chosen();
    }).fail(function () {
        notifNo('Gagal memuat ulang data SKS');
    });
}

function reloadSkbsSection() {
    var vid = $('#skbs-section-wrap').data('visit-id') || $('#visit_id_skbs').val();
    if (!vid) return;
    $.get(site_url + 'dokter/reload_skbs?visit_id=' + vid, function (html) {
        $('#skbs-section-wrap').html(html);
    }).fail(function () {
        notifNo('Gagal memuat ulang data SKBS');
    });
}

function reloadSkmbSection() {
    var vid = $('#skmb-section-wrap').data('visit-id') || $('#visit_id_skmb').val();
    if (!vid) return;
    $.get(site_url + 'dokter/reload_skmb?visit_id=' + vid, function (html) {
        $('#skmb-section-wrap').html(html);
        $('#skmb-section-wrap .datepicker').datetimepicker({
            datepicker: true, timepicker: false, format: 'd/m/Y',
            closeOnDateSelect: true, scrollMonth: false, scrollInput: false,
        });
        $('#skmb-section-wrap .clockpicker').clockpicker({
            autoclose: true, donetext: 'OK', placement: 'bottom', align: 'left',
        });
    }).fail(function () {
        notifNo('Gagal memuat ulang data SKMB');
    });
}

$(document).ready(function () {

    // ================================================================
    // PEMERIKSAAN PAGE — Diagnosa
    // ================================================================
    $(document).on('click', '#btn_add_diagnosa', function () {
        var mrd_id = $('#mrd_id_diagnosa').val();
        var dgn_id = $('#select_diagnosa').val();
        if (!dgn_id) { notifNo('Silakan pilih diagnosa'); return false; }

        var dgn_note = $('#dgn_note').val();
        $.post(site_url + 'dokter/act_add_diagnosa', {
            medical_record_id: mrd_id,
            dgn_id: dgn_id,
            dgn_note: dgn_note
        }, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                notifYesAuto(res.notif);
                $('#dgn_note').val('');
                reloadDiagnosaTable();
            }
        }, 'json');
    });

    $(document).on('click', '.del-diagnosa-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Diagnosa?',
            text: 'Data diagnosa akan dihapus.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_del_diagnosa', { id: id }, function (res) {
                if (res.status == 2) {
                    notifYesAuto(res.notif);
                    reloadDiagnosaTable();
                }
            }, 'json');
        });
    });

    // ================================================================
    // PEMERIKSAAN PAGE — Obat
    // ================================================================
    $(document).on('click','#obat-tab',function(e){
		reloadObatTable();
	});
    $(document).on('click', '#btn_add_obat', function () {
        var mrd_id = $('#mrd_id_obat').val();
        var obat_id = $('#select_obat').val();
        var qty = parseInt($('#obat_qty').val()) || 1;
        var dosis = $('#obat_dosis').val();
        if (!obat_id) { notifNo('Silakan pilih obat'); return false; }
        if (qty < 1) { notifNo('Qty minimal 1'); return false; }

        $.post(site_url + 'dokter/act_add_obat', {
            medical_record_id: mrd_id,
            obat_id: obat_id,
            qty: qty,
            dosis: dosis
        }, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                notifYesAuto(res.notif);
                reloadObatTable();
            }
        }, 'json');
    });

    $(document).on('click', '.del-obat-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Obat?',
            text: 'Data obat akan dihapus.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_del_obat', { id: id }, function (res) {
                if (res.status == 2) {
                    notifYesAuto(res.notif);
                    reloadObatTable();
                }
            }, 'json');
        });
    });

    // ================================================================
    // PEMERIKSAAN PAGE — SKS
    // ================================================================
    $(document).on('click','#sks-tab',function(e){
		reloadSksSection();
	});
    $(document).on('click', '#btn_buat_sks', function () {
        var payload = {
            visit_id:  $('#visit_id_sks').val(),
            doctby:    $('#sks_doctby').val(),
            sks_nik:   $('#sks_nik').val(),
            docnumb:   $('#sks_docnumb').val(),
            diagnosa:  $('#sks_diagnosa').val(),
            terapi:    $('#sks_terapi').val(),
            docdate:   $('#sks_docdate').val(),
            datefrom:  $('#sks_datefrom').val(),
            dateto:    $('#sks_dateto').val(),
        };

        if (!payload.doctby) { notifNo('Silakan pilih dokter'); return false; }
        if (!payload.diagnosa) { notifNo('Silakan isi diagnosa'); return false; }
        if (!payload.datefrom || !payload.dateto) { notifNo('Silakan isi tanggal berlaku'); return false; }

        $.post(site_url + 'dokter/act_buat_sks', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                notifYesAuto(res.notif);
                reloadSksSection();
            }
        }, 'json');
    });

    $(document).on('click', '.del-sks-btn', function () {
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
            $.post(site_url + 'dokter/act_del_sks', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); reloadSksSection(); }
            }, 'json');
        });
    });

    // ================================================================
    // PEMERIKSAAN PAGE — SKBS
    // ================================================================
    $(document).on('click','#skbs-tab',function(e){
		reloadSkbsSection();
	});
    $(document).on('click', '#btn_simpan_skbs', function () {
        var payload = {
            visit_id:      $(this).data('visit-id'),
            skbs_result:   $('#skbs_result').val(),
            skbs_desc:     $('#skbs_desc').val(),
            skbs_note:     $('#skbs_note').val(),
            skbs_blood_press: $('#skbs_blood_press').val(),
            skbs_pulse:    $('#skbs_pulse').val(),
            skbs_respirasi:$('#skbs_respirasi').val(),
            skbs_temp:     $('#skbs_temp').val(),
            skbs_tb:       $('#skbs_tb').val(),
            skbs_bb:       $('#skbs_bb').val(),
            skbs_bw:       $('#skbs_bw').val(),
            skbs_r:        $('#skbs_r').val(),
            skbs_l:        $('#skbs_l').val(),
            skbs_docnumb:  $('#skbs_docnumb').val(),
        };
        if (!payload.skbs_result) { notifNo('Silakan pilih hasil'); return false; }

        $.post(site_url + 'dokter/act_simpan_skbs', payload, function (res) {
            if (res.status == 1) notifNo(res.notif);
            else { notifYesAuto(res.notif); reloadSkbsSection(); }
        }, 'json');
    });

    $(document).on('click', '.del-skbs-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKBS?',
            text: 'Data SKBS akan dihapus.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_del_skbs', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); reloadSkbsSection(); }
            }, 'json');
        });
    });

    // ================================================================
    // PEMERIKSAAN PAGE — Ganti Dokter Pemeriksa
    // ================================================================
    $(document).on('click', '#change_doctor_btn', function () {
        $('#modalGantiDokter').modal('show');
    });

    $(document).on('click', '.pilih-dokter-item', function () {
        var doctorId = $(this).data('id');
        var doctorName = $(this).data('name');
        var visitId = $('#visit_id_sks').val();

        if (!doctorId || !visitId) return;

        $.post(site_url + 'dokter/act_update_doctor', {
            visit_id: visitId,
            doctor_id: doctorId
        }, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                $('#modalGantiDokter').modal('hide');
                $('#mrd_doct_by').val(doctorId);
                $('#doctor_name_display').text(doctorName);
                notifYesAuto(res.notif);
            }
        }, 'json');
    });

    // ================================================================
    // PEMERIKSAAN PAGE — SKMB
    // ================================================================
    $(document).on('click','#skmb-tab',function(e){
		reloadSkmbSection();
	});
    $(document).on('click', '#btn_simpan_skmb', function () {
        var payload = {
            visit_id:         $(this).data('visit-id'),
            patient_name:     $('#skmb_patient_name').val(),
            nik:              $('#skmb_nik').val(),
            company_name:     $('#skmb_company_name').val(),
            pengantar:        $('#skmb_pengantar').val(),
            nik_pengantar:    $('#skmb_nik_pengantar').val(),
            pekerjaan_pengantar: $('#skmb_pekerjaan_pengantar').val(),
            hubungan:         $('#skmb_hubungan').val(),
            tgl_datang:       $('#skmb_tgl_datang').val(),
            jam:              $('#skmb_jam').val(),
            skmb_docnumb:     $('#skmb_docnumb').val(),
        };

        if (!payload.patient_name) { notifNo('Silakan isi nama yang diantar'); return false; }
        if (!payload.tgl_datang) { notifNo('Silakan isi tanggal datang'); return false; }
        if (!payload.jam) { notifNo('Silakan isi jam'); return false; }
        if (!payload.hubungan) { notifNo('Silakan pilih hubungan'); return false; }
        if (!payload.pengantar) { notifNo('Silakan isi nama pengantar'); return false; }

        $.post(site_url + 'dokter/act_simpan_skmb', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                notifYesAuto(res.notif);
                reloadSkmbSection();
            }
        }, 'json');
    });

    $(document).on('click', '.del-skmb-btn', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus SKMB?',
            text: 'Data SKMB akan dihapus.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_del_skmb', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); reloadSkmbSection(); }
            }, 'json');
        });
    });

    // ================================================================
    // PEMERIKSAAN PAGE — Racikan
    // ================================================================

    // Tombol "Buat Racikan"
    $(document).on('click', '#btn-add-pulv', function () {
        var checked = [];
        $('.check-terapi:checked').each(function () {
            checked.push($(this).data('id'));
        });
        if (checked.length === 0) {
            notifNo('Centang obat terlebih dahulu!');
            return false;
        }

        var mrd_id = $('#mrd_id_diagnosa').val();
        $.get(site_url + 'dokter/get_pulv_popup', { mrd_id: mrd_id }, function (html) {
            $('#MyModalTitle').html('<i class="fa fa-mortar-pestle"></i> Buat Racikan');
            $('#MyModalContent').html(html);
            $('#MyModalFooter').html(
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="btn_save_pulv" style="padding:8px 22px">Simpan Racikan</button>'
            );
            $('.modal-dialog').addClass('ds-modal').addClass('modal-md');
            $('#MyModal').modal('show');
            // Simpan data checkbox ke modal
            $('#btn_save_pulv').data('obat-ids', checked);
        });
    });

    // Pilih racikan existing / baru
    $(document).on('change', '#option_pulv', function () {
        if ($(this).val() === 'new') {
            $('#pulv_add_area').slideDown(200);
        } else {
            $('#pulv_add_area').slideUp(200);
        }
    });

    // Simpan racikan
    $(document).on('click', '#btn_save_pulv', function () {
        var option_pulv = $('#option_pulv').val();
        if (!option_pulv) {
            notifNo('Pilih racikan tujuan atau buat baru');
            return false;
        }

        var obat_ids = $(this).data('obat-ids');
        var payload = {
            medical_record_id: $('#pulv_medical_record_id').val(),
            option_pulv:       option_pulv,
            obat_ids:          obat_ids,
        };

        if (option_pulv === 'new') {
            var pulv_name = $('#pulv_name').val();
            var pulv_dosis = $('#pulv_dosis').val();
            var pulv_qty = $('#pulv_qty').val();
            if (!pulv_name) { notifNo('Silahkan isi nama racikan'); return false; }
            if (!pulv_dosis) { notifNo('Silahkan isi dosis racikan'); return false; }
            if (!pulv_qty || pulv_qty < 1) { notifNo('Silahkan isi jumlah racikan'); return false; }
            payload.pulv_name = pulv_name;
            payload.pulv_dosis = pulv_dosis;
            payload.pulv_qty = pulv_qty;
            payload.pulv_notes = $('#pulv_notes').val();
        }

        $.post(site_url + 'dokter/act_save_pulv', payload, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                reloadObatTable();
            }
        }, 'json');
    });

    // Edit racikan
    $(document).on('click', '.btn-edit-pulv', function () {
        var id = $(this).data('id');
        $.get(site_url + 'dokter/edit_pulv_popup', { id: id }, function (html) {
            $('#MyModalTitle').html('<i class="fa fa-pen"></i> Edit Racikan');
            $('#MyModalContent').html(html);
            $('#MyModalFooter').html(
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>' +
                '<button type="button" class="ds-btn-action ds-btn-green" id="btn_update_pulv" style="padding:8px 22px">Simpan Perubahan</button>'
            );
            $('.modal-dialog').addClass('ds-modal').addClass('modal-md');
            $('#MyModal').modal('show');
        });
    });

    $(document).on('click', '#btn_update_pulv', function () {
        var id = $('#edit_pulv_id').val();
        var pulv_name = $('#edit_pulv_name').val();
        var pulv_dosis = $('#edit_pulv_dosis').val();
        var pulv_qty = $('#edit_pulv_qty').val();
        if (!pulv_name) { notifNo('Silahkan isi nama racikan'); return false; }
        if (!pulv_dosis) { notifNo('Silahkan isi dosis racikan'); return false; }
        if (!pulv_qty || pulv_qty < 1) { notifNo('Silahkan isi jumlah racikan'); return false; }

        $.post(site_url + 'dokter/act_update_pulv', {
            id: id,
            pulv_name: pulv_name,
            pulv_dosis: pulv_dosis,
            pulv_qty: pulv_qty,
            pulv_notes: $('#edit_pulv_notes').val(),
        }, function (res) {
            if (res.status == 1) {
                notifNo(res.notif);
            } else {
                $('#MyModal').modal('hide');
                notifYesAuto(res.notif);
                reloadPulvList();
            }
        }, 'json');
    });

    // Hapus racikan
    $(document).on('click', '.btn-delete-pulv', function () {
        var id = $(this).data('id');
        swal({
            title: 'Hapus Racikan?',
            text: 'Obat penyusun akan dikembalikan ke daftar obat biasa.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_delete_pulv', { id: id }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); reloadObatTable(); }
            }, 'json');
        });
    });

});
