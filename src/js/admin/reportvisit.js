var tabel_reportvisit;

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

    // --- Helper: show modal ---
    function showDsModal(title, contentHtml) {
        $('#MyModalTitle').html(title);
        $('#MyModalContent').html(contentHtml);
        $('#MyModalFooter').html('');
        $('.modal-dialog').addClass('ds-modal').addClass('modal-md');
        $('#MyModal').modal('show');
    }

    // --- DataTable (mulai kosong, load manual) ---
    tabel_reportvisit = $('#tabel_reportvisit').DataTable({
        processing: true,
        serverSide: false,
        scrollY: "500px",
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1
        },
        data: [],
        columns: [
            { data: '0', width: '40px' },
            { data: '1' },
            {
                data: '2',
                className: 'text-center',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a href="javascript:;" class="patient-list-link" data-tgl="' + row['1'] + '" style="font-weight:700;color:var(--ds-primary);text-decoration:underline;">' + data + '</a>';
                    }
                    return data;
                }
            },
        ],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ – _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data',
            zeroRecords: 'Tidak ada data',
            emptyTable: 'Silakan pilih rentang tanggal dan klik Tampilkan',
            paginate: { previous: '&laquo;', next: '&raquo;' },
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false,
    });

    // --- Fungsi load data ---
    function loadData() {
        var date_from = $('#filter_date_from').val().trim();
        var date_to   = $('#filter_date_to').val().trim();
        if (date_from == '' || date_to == '') {
            notifNo('Silakan pilih rentang tanggal terlebih dahulu');
            return;
        }

        $.ajax({
            url: site_url + 'reportvisit/table',
            type: 'GET',
            data: {
                date_from: date_from,
                date_to: date_to,
                draw: 1
            },
            dataType: 'json',
            success: function (json) {
                tabel_reportvisit.clear().rows.add(json.data).draw();
                // Update total
                var total = json.grand_total || 0;
                $('#footer_total').text(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            }
        });
    }

    // --- Tombol Tampilkan ---
    $('#search_btn').on('click', function () {
        loadData();
    });

    // --- Enter pada field filter ---
    $('#filter_date_from, #filter_date_to').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            loadData();
        }
    });

    // --- Download Excel ---
    $('#download_excel_btn').on('click', function () {
        var date_from = $('#filter_date_from').val().trim();
        var date_to   = $('#filter_date_to').val().trim();

        if (date_from == '' || date_to == '') {
            notifNo('Silakan pilih rentang tanggal terlebih dahulu');
            return;
        }

        var url = site_url + 'reportvisit/download_excel?date_from=' + encodeURIComponent(date_from) +
                  '&date_to=' + encodeURIComponent(date_to);

        window.open(url, '_blank');
    });

    // --- Klik jumlah kunjungan → tampilkan daftar pasien ---
    $(document).on('click', '.patient-list-link', function () {
        var tgl = $(this).data('tgl');
        $.get(site_url + 'reportvisit/patient_list', { date: tgl }, function (html) {
            showDsModal('<i class="fa fa-users"></i> Daftar Pasien - ' + tgl, html);
        });
    });

});
