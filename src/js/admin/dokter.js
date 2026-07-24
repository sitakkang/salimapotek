var tabel_dokter;
var url_ctrl = site_url + "dokter/";

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

    // --- Chosen ---
    $('.autocomplete').chosen();

    // ================================================================
    // LIST PAGE — DataTable
    // ================================================================
    tabel_dokter = $('#tabel_dokter').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: { leftColumns: 2, rightColumns: 1 },
        data: [],
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            { data: '2' },
            { data: '3' },
            { data: '4', className: 'text-center' },
            { data: '5', className: 'text-center' },
            {
                data: null,
                width: '130px',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<div style="display:flex;gap:4px;justify-content:center;">' +
                               '<a href="' + site_url + 'dokter/pemeriksaan/' + row.DT_RowId + '" class="ds-btn-action ds-btn-green" style="padding:5px 12px;text-decoration:none;font-size:12px;">' +
                                   '<i class="fa fa-stethoscope"></i> Periksa' +
                               '</a>' +
                               '<button class="ds-btn-action ds-btn-red batal-row-btn" data-visit-id="' + row.DT_RowId + '" data-patient="' + row.patient_name_raw + '" style="padding:5px 12px;font-size:12px;">' +
                                   '<i class="fa fa-times"></i> Batal' +
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
            emptyTable: 'Silakan pilih tanggal dan klik Tampilkan',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // ================================================================
    // LIST PAGE — Batal Pemeriksaan
    // ================================================================
    $(document).on('click', '.batal-row-btn', function () {
        var visitId = $(this).data('visit-id');
        var patientName = $(this).data('patient');
        swal({
            title: 'Yakin membatalkan pemeriksaan?',
            text: 'Pasien: ' + patientName,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(function (result) {
            if (!result.value) return;
            $.post(site_url + 'dokter/act_batal', { visit_id: visitId }, function (res) {
                if (res.status == 1) notifNo(res.notif);
                else { notifYesAuto(res.notif); tabel_dokter.ajax.reload(null, false); }
            }, 'json');
        });
    });

    function loadData() {
        var $filter = $('#filter_date');
        if (!$filter.length) return;
        var date = $filter.val().trim();
        if (date == '') { notifNo('Silakan pilih tanggal'); return; }
        tabel_dokter.ajax.url(site_url + 'dokter/table?date=' + encodeURIComponent(date)).load();
    }

    if ($('#filter_date').length) {
        $('#search_btn').on('click', function () { loadData(); });
        $('#filter_date').on('keypress', function (e) {
            if (e.which === 13) { e.preventDefault(); loadData(); }
        });
        setTimeout(function () { loadData(); }, 500);
    }

});
